<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\CompanySetting;
use App\Models\CooperativeRecord;
use App\Models\Employee;
use App\Models\Overtime;
use App\Models\Payroll;
use App\Models\PayrollDetail;
use App\Models\PayrollPeriod;
use App\Models\TaxTerCategory;
use App\Models\TaxTerRate;
use App\Models\User;
use App\Notifications\PayrollNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    public function generatePayroll(PayrollPeriod $period): void
    {
        if ($period->status !== 'draft') {
            throw new \Exception('Payroll hanya bisa digenerate untuk periode dengan status Draft');
        }

        DB::transaction(function () use ($period) {
            $existingPayrolls = Payroll::where('payroll_period_id', $period->id)->get();
            foreach ($existingPayrolls as $p) {
                $p->delete();
            }
        });

        $employees = Employee::active()->get();
        $settings = CompanySetting::first();

        DB::transaction(function () use ($employees, $period, $settings) {
            foreach ($employees as $employee) {
                $this->calculateEmployeePayroll($employee, $period, $settings);
            }
            $period->update(['status' => 'processing']);
        });
    }

    public function calculateEmployeePayroll(Employee $employee, PayrollPeriod $period, ?CompanySetting $settings = null): Payroll
    {
        $settings = $settings ?? CompanySetting::first();

        // 1. Prorate Basic Salary
        $prorateFactor = $this->calculateProrateFactor($employee, $period);
        $basicSalary = round($employee->basic_salary * $prorateFactor, 2);

        // 2. Get active earning components
        $earnings = $employee->salaryComponents()
            ->active()
            ->whereHas('salaryComponent', fn ($q) => $q->where('type', 'earning'))
            ->with('salaryComponent')
            ->get();

        $totalEarning = 0;
        foreach ($earnings as $earning) {
            // Some allowances might need prorate too, but let's assume they are fixed for now or prorate them all
            $earningAmount = round($earning->amount * $prorateFactor, 2);
            $earning->calculated_amount = $earningAmount;
            $totalEarning += $earningAmount;
        }

        // 3. Overtime
        $overtimeAmount = $this->calculateOvertimeAmount($employee, $period, $settings);

        // 4. Gross Salary for Tax and BPJS basis
        $grossSalary = $basicSalary + $totalEarning + $overtimeAmount;

        // 5. BPJS Calculation (Employee Deductions)
        $bpjsDeductions = $this->calculateBpjs($employee, $basicSalary, $settings);

        // 6. Other Deductions (Loans, Late, Components)
        $deductions = $employee->salaryComponents()
            ->active()
            ->whereHas('salaryComponent', fn ($q) => $q->where('type', 'deduction')->whereNotIn('code', ['KASBON', 'PPH21', 'BPJS_KES', 'BPJS_TK']))
            ->with('salaryComponent')
            ->get();
        $totalOtherDeduction = $deductions->sum('amount');

        $cooperativeDeduction = $this->calculateCooperativeDeduction($employee, $period);
        $latePenalty = $this->calculateLatePenalty($employee, $period, $settings);

        // 7. PPh 21 (TER) Calculation
        $taxAmount = 0;
        if ($employee->tax_method !== 'nett') {
            $taxAmount = $this->calculateTaxTer($employee, $grossSalary);
        }

        // 8. Total Deductions
        $totalDeduction = $totalOtherDeduction + $cooperativeDeduction + $latePenalty + $taxAmount + array_sum($bpjsDeductions);

        // 9. Net Salary
        $netSalary = $grossSalary - $totalDeduction;

        // 10. Save Payroll
        $payroll = Payroll::create([
            'payroll_period_id' => $period->id,
            'employee_id' => $employee->id,
            'basic_salary' => $basicSalary,
            'total_earning' => $totalEarning,
            'total_deduction' => $totalDeduction,
            'overtime_amount' => $overtimeAmount,
            'gross_salary' => $grossSalary,
            'net_salary' => $netSalary,
            'status' => 'draft',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        $this->createPayrollDetails($payroll, $basicSalary, $earnings, $overtimeAmount, $deductions, $cooperativeDeduction, $latePenalty, $taxAmount, $bpjsDeductions, $employee);

        return $payroll;
    }

    private function calculateProrateFactor(Employee $employee, PayrollPeriod $period): float
    {
        $start = Carbon::parse($period->start_date);
        $end = Carbon::parse($period->end_date);
        $totalDays = $start->diffInDays($end) + 1;

        $joinDate = Carbon::parse($employee->join_date);
        $resignDate = $employee->resign_date ? Carbon::parse($employee->resign_date) : null;

        if ($joinDate->lte($start) && (! $resignDate || $resignDate->gte($end))) {
            return 1.0;
        }

        $effectiveStart = $joinDate->max($start);
        $effectiveEnd = $resignDate ? $resignDate->min($end) : $end;

        if ($effectiveStart->gt($effectiveEnd)) {
            return 0;
        }

        $workedDays = $effectiveStart->diffInDays($effectiveEnd) + 1;

        return round($workedDays / $totalDays, 4);
    }

    private function calculateBpjs(Employee $employee, float $basicSalary, CompanySetting $settings): array
    {
        $deductions = ['BPJS_KES' => 0, 'BPJS_TK' => 0];

        if ($employee->is_bpjs_kesehatan_active) {
            $basisKes = min($basicSalary, $settings->bpjs_kesehatan_capping);
            $deductions['BPJS_KES'] = round($basisKes * 0.01, 2); // 1% Employee
        }

        if ($employee->is_bpjs_ketenagakerjaan_active) {
            $basisJp = min($basicSalary, $settings->bpjs_jp_capping);
            $jht = round($basicSalary * 0.02, 2); // 2% Employee
            $jp = round($basisJp * 0.01, 2); // 1% Employee
            $deductions['BPJS_TK'] = $jht + $jp;
        }

        return $deductions;
    }

    private function calculateTaxTer(Employee $employee, float $bruto): float
    {
        // Find category by PTKP status
        $categories = TaxTerCategory::all();
        $category = $categories->first(function ($cat) use ($employee) {
            $list = array_map('trim', explode(',', $cat->ptkp_list));

            return in_array($employee->ptkp_status, $list);
        });
        if (! $category) {
            return 0;
        }

        // Find rate
        $rate = TaxTerRate::where('tax_ter_category_id', $category->id)
            ->where('min_bruto', '<=', $bruto)
            ->where(function ($q) use ($bruto) {
                $q->whereNull('max_bruto')
                    ->orWhere('max_bruto', '>=', $bruto);
            })->first();

        if (! $rate) {
            return 0;
        }

        return round($bruto * ($rate->percentage / 100), 2);
    }

    private function createPayrollDetails($payroll, $basicSalary, $earnings, $overtime, $deductions, $cooperativeDeduction, $late, $tax, $bpjs, $employee)
    {
        // Earnings
        PayrollDetail::create(['payroll_id' => $payroll->id, 'component_name' => 'Gaji Pokok', 'component_type' => 'earning', 'amount' => $basicSalary]);
        foreach ($earnings as $earning) {
            PayrollDetail::create([
                'payroll_id' => $payroll->id,
                'salary_component_id' => $earning->salary_component_id,
                'component_name' => $earning->salaryComponent->name,
                'component_type' => 'earning',
                'amount' => $earning->calculated_amount,
            ]);
        }
        if ($overtime > 0) {
            PayrollDetail::create(['payroll_id' => $payroll->id, 'component_name' => 'Lembur', 'component_type' => 'earning', 'amount' => $overtime]);
        }

        // Deductions
        foreach ($deductions as $deduction) {
            PayrollDetail::create([
                'payroll_id' => $payroll->id,
                'salary_component_id' => $deduction->salary_component_id,
                'component_name' => $deduction->salaryComponent->name,
                'component_type' => 'deduction',
                'amount' => $deduction->amount,
            ]);
        }

        if ($bpjs['BPJS_KES'] > 0) {
            PayrollDetail::create(['payroll_id' => $payroll->id, 'component_name' => 'BPJS Kesehatan (1%)', 'component_type' => 'deduction', 'amount' => $bpjs['BPJS_KES']]);
        }
        if ($bpjs['BPJS_TK'] > 0) {
            PayrollDetail::create(['payroll_id' => $payroll->id, 'component_name' => 'BPJS Ketenagakerjaan (JHT+JP)', 'component_type' => 'deduction', 'amount' => $bpjs['BPJS_TK']]);
        }
        if ($tax > 0) {
            PayrollDetail::create(['payroll_id' => $payroll->id, 'component_name' => 'PPh 21', 'component_type' => 'deduction', 'amount' => $tax]);
        }

        if ($cooperativeDeduction > 0) {
            PayrollDetail::create(['payroll_id' => $payroll->id, 'component_name' => 'Potongan Koperasi', 'component_type' => 'deduction', 'amount' => $cooperativeDeduction]);
        }

        if ($late > 0) {
            PayrollDetail::create(['payroll_id' => $payroll->id, 'component_name' => 'Potongan Keterlambatan', 'component_type' => 'deduction', 'amount' => $late]);
        }
    }

    public function calculateOvertimeAmount(Employee $employee, PayrollPeriod $period, CompanySetting $settings): float
    {
        return Overtime::where('employee_id', $employee->id)
            ->where('status', 'approved')
            ->whereBetween('date', [$period->start_date, $period->end_date])
            ->sum('amount');
    }

    public function calculateCooperativeDeduction(Employee $employee, PayrollPeriod $period): float
    {
        return CooperativeRecord::where('employee_id', $employee->id)
            ->whereBetween('date', [$period->start_date, $period->end_date])
            ->sum('amount');
    }

    public function calculateLatePenalty(Employee $employee, PayrollPeriod $period, CompanySetting $settings): float
    {
        if ($settings->late_penalty_per_minute <= 0) {
            return 0;
        }
        $totalLateMinutes = Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$period->start_date, $period->end_date])
            ->sum('late_minutes');

        return $totalLateMinutes * $settings->late_penalty_per_minute;
    }

    public function submitForApproval(PayrollPeriod $period): void
    {
        DB::transaction(function () use ($period) {
            Payroll::where('payroll_period_id', $period->id)->update(['status' => 'submitted']);
            $period->update(['status' => 'waiting_approval']);
        });
    }

    public function approvePayroll(PayrollPeriod $period, User $approver): void
    {
        DB::transaction(function () use ($period, $approver) {
            Payroll::where('payroll_period_id', $period->id)->update(['status' => 'approved', 'approved_by' => $approver->id, 'approved_at' => now()]);
            $period->update(['status' => 'approved']);
        });
    }

    public function rejectPayroll(PayrollPeriod $period, User $rejector, string $reason): void
    {
        DB::transaction(function () use ($period, $reason) {
            Payroll::where('payroll_period_id', $period->id)->update(['status' => 'rejected', 'notes' => $reason]);
            $period->update(['status' => 'draft']);
        });
    }

    public function markAsPaid(PayrollPeriod $period): void
    {
        DB::transaction(function () use ($period) {
            Payroll::where('payroll_period_id', $period->id)->update(['status' => 'paid', 'paid_at' => now()]);
            $period->update(['status' => 'paid']);

            // Notify all employees with payrolls in this period
            $payrolls = Payroll::where('payroll_period_id', $period->id)->with('employee.user')->get();
            foreach ($payrolls as $payroll) {
                if ($payroll->employee && $payroll->employee->user) {
                    $payroll->employee->user->notify(new PayrollNotification(
                        'Slip Gaji Tersedia',
                        'Slip gaji anda untuk periode '.$period->name.' telah tersedia.',
                        route('portal.payslip')
                    ));
                }
            }
        });
    }
}
