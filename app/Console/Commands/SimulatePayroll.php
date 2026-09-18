<?php

namespace App\Console\Commands;

use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeSalaryComponent;
use App\Models\EmploymentStatus;
use App\Models\Payroll;
use App\Models\PayrollDetail;
use App\Models\PayrollPeriod;
use App\Models\Position;
use App\Models\SalaryComponent;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SimulatePayroll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:simulate-payroll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean DB and simulate 1 year of payroll for exactly 500 employees with proper distribution';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting clean up and payroll simulation...');

        $faker = Faker::create('id_ID');

        try {
            $this->info('Wiping existing data (except admin account)...');
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Wipe data
            PayrollDetail::truncate();
            Payroll::truncate();
            PayrollPeriod::truncate();
            EmployeeSalaryComponent::truncate();
            SalaryComponent::truncate();

            // Delete users except super admin
            $adminUser = User::role('super_admin')->first() ?? User::first();
            $adminId = $adminUser ? $adminUser->id : 1;
            User::where('id', '!=', $adminId)->delete();

            Employee::truncate();
            Department::truncate();
            Position::truncate();
            EmploymentStatus::truncate();

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // Start transaction after DDL (truncate) because DDL causes implicit commit in MySQL
            DB::beginTransaction();

            // 1. Create Master Data
            $this->info('Creating master data...');
            $deptProduksi = Department::create(['name' => 'Produksi']);
            $deptHrd = Department::create(['name' => 'HRD']);
            $deptFinance = Department::create(['name' => 'Finance']);
            $deptIt = Department::create(['name' => 'IT']);
            $deptMarketing = Department::create(['name' => 'Marketing']);

            $posManager = Position::create(['name' => 'Manager']);
            $posSpv = Position::create(['name' => 'Supervisor']);
            $posStaff = Position::create(['name' => 'Staff']);
            $posOperator = Position::create(['name' => 'Operator']);

            $permanentStatus = EmploymentStatus::create(['name' => 'Tetap']);
            $contractStatus = EmploymentStatus::create(['name' => 'Kontrak']);

            // 2. Setup Salary Components
            $allowance = SalaryComponent::create([
                'code' => 'T-TRANSPORT',
                'name' => 'Tunjangan Transport',
                'type' => 'earning',
                'calculation_type' => 'fixed',
                'taxable' => true,
            ]);
            $deduction = SalaryComponent::create([
                'code' => 'P-KOPERASI',
                'name' => 'Potongan Koperasi',
                'type' => 'deduction',
                'calculation_type' => 'fixed',
                'taxable' => false,
            ]);

            // 3. Define employee distribution
            // Total 500 employees
            $distribution = [
                // [Department, Position, Count, Salary Min, Salary Max]
                [$deptIt, $posManager, 1, 15000000, 25000000],
                [$deptIt, $posSpv, 1, 8000000, 12000000],
                [$deptIt, $posStaff, 8, 5000000, 7000000],

                [$deptHrd, $posManager, 1, 15000000, 20000000],
                [$deptHrd, $posSpv, 1, 8000000, 10000000],
                [$deptHrd, $posStaff, 8, 5000000, 7000000],

                [$deptFinance, $posManager, 1, 15000000, 22000000],
                [$deptFinance, $posSpv, 2, 8000000, 11000000],
                [$deptFinance, $posStaff, 12, 5000000, 7000000],

                [$deptMarketing, $posManager, 1, 15000000, 25000000],
                [$deptMarketing, $posSpv, 2, 8000000, 12000000],
                [$deptMarketing, $posStaff, 12, 5000000, 7000000],

                [$deptProduksi, $posManager, 1, 15000000, 25000000],
                [$deptProduksi, $posSpv, 10, 7000000, 9000000],
                // Remaining for Operator Produksi: 500 - 10 - 10 - 15 - 15 - 11 = 439
                [$deptProduksi, $posOperator, 439, 4500000, 5500000],
            ];

            $this->info('Creating 500 employees based on distribution...');
            $bar = $this->output->createProgressBar(500);

            foreach ($distribution as $dist) {
                [$dept, $position, $count, $minSal, $maxSal] = $dist;

                for ($i = 0; $i < $count; $i++) {
                    $status = $faker->boolean(80) ? $permanentStatus : $contractStatus;
                    $gender = $faker->randomElement(['male', 'female']);
                    $joinDate = Carbon::now()->subMonths($faker->numberBetween(12, 60));
                    $salary = $faker->numberBetween($minSal, $maxSal);

                    // Round to nearest 100,000
                    $salary = round($salary / 100000) * 100000;

                    $email = strtolower(Str::random(6)).'.'.$faker->unique()->safeEmail();

                    $employee = Employee::create([
                        'nik' => $faker->numerify('################'),
                        'name' => $faker->name($gender),
                        'gender' => $gender,
                        'birth_place' => $faker->city,
                        'birth_date' => Carbon::now()->subYears($faker->numberBetween(20, 45)),
                        'phone' => $faker->phoneNumber,
                        'email' => $email,
                        'department_id' => $dept->id,
                        'position_id' => $position->id,
                        'employment_status_id' => $status->id,
                        'join_date' => $joinDate,
                        'basic_salary' => $salary,
                        'status' => 'active',
                    ]);

                    // Assign basic salary components
                    EmployeeSalaryComponent::create([
                        'employee_id' => $employee->id,
                        'salary_component_id' => $allowance->id,
                        'amount' => 500000,
                        'effective_date' => $joinDate,
                    ]);

                    if ($faker->boolean(30)) {
                        EmployeeSalaryComponent::create([
                            'employee_id' => $employee->id,
                            'salary_component_id' => $deduction->id,
                            'amount' => 100000,
                            'effective_date' => $joinDate,
                        ]);
                    }

                    $bar->advance();
                }
            }
            $bar->finish();
            $this->newLine();

            // 4. Generate Payrolls for the last 12 months
            $this->info('Generating 1 year of payrolls...');
            $employees = Employee::active()->get();

            $barPayroll = $this->output->createProgressBar(12 * $employees->count());

            for ($i = 11; $i >= 0; $i--) {
                $targetDate = Carbon::now()->startOfMonth()->subMonths($i);
                $month = $targetDate->month;
                $year = $targetDate->year;

                $periodName = 'Periode '.$targetDate->translatedFormat('F Y');

                $period = PayrollPeriod::create([
                    'month' => $month,
                    'year' => $year,
                    'name' => $periodName,
                    'start_date' => $targetDate->copy()->startOfMonth(),
                    'end_date' => $targetDate->copy()->endOfMonth(),
                    'payment_date' => $targetDate->copy()->endOfMonth(),
                    'status' => 'paid',
                ]);

                // Generate payroll for each employee
                foreach ($employees as $emp) {
                    if ($emp->join_date > $targetDate->copy()->endOfMonth()) {
                        $barPayroll->advance();

                        continue; // Employee not joined yet
                    }

                    $payroll = Payroll::create([
                        'payroll_period_id' => $period->id,
                        'employee_id' => $emp->id,
                        'basic_salary' => $emp->basic_salary,
                        'status' => 'paid',
                        'processed_by' => $adminId,
                        'approved_by' => $adminId,
                        'processed_at' => $targetDate->copy()->endOfMonth()->subDays(2),
                        'approved_at' => $targetDate->copy()->endOfMonth()->subDay(),
                        'paid_at' => $targetDate->copy()->endOfMonth(),
                    ]);

                    $totalEarning = 0;
                    $totalDeduction = 0;

                    // Retrieve active components for this employee
                    $components = $emp->salaryComponents()->with('salaryComponent')->get();

                    foreach ($components as $empComp) {
                        $comp = $empComp->salaryComponent;
                        if (! $comp) {
                            continue;
                        }

                        PayrollDetail::create([
                            'payroll_id' => $payroll->id,
                            'salary_component_id' => $comp->id,
                            'component_name' => $comp->name,
                            'component_type' => $comp->type,
                            'amount' => $empComp->amount,
                        ]);

                        if ($comp->type === 'earning') {
                            $totalEarning += $empComp->amount;
                        } else {
                            $totalDeduction += $empComp->amount;
                        }
                    }

                    // Overtime mostly for Operator & Staff
                    $overtimeAmount = 0;
                    if ($emp->position->name === 'Operator' || $emp->position->name === 'Staff') {
                        if ($faker->boolean(60)) {
                            $overtimeAmount = $faker->numberBetween(1, 20) * 25000;
                            PayrollDetail::create([
                                'payroll_id' => $payroll->id,
                                'component_name' => 'Lembur',
                                'component_type' => 'earning',
                                'amount' => $overtimeAmount,
                            ]);
                            $totalEarning += $overtimeAmount;
                        }
                    }

                    $grossSalary = $emp->basic_salary + $totalEarning;
                    $netSalary = $grossSalary - $totalDeduction;

                    $payroll->update([
                        'total_earning' => $totalEarning,
                        'total_deduction' => $totalDeduction,
                        'overtime_amount' => $overtimeAmount,
                        'gross_salary' => $grossSalary,
                        'net_salary' => $netSalary,
                    ]);

                    $barPayroll->advance();
                }
            }
            $barPayroll->finish();
            $this->newLine();

            DB::commit();
            $this->info('Payroll simulation and database reset completed successfully!');

        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            $this->error('An error occurred: '.$e->getMessage());
        }
    }
}
