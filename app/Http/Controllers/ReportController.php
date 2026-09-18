<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Overtime;
use App\Models\Payroll;
use App\Models\PayrollPeriod;
use App\Models\Position;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function payrollSummary(Request $request)
    {
        $periods = PayrollPeriod::latest()->get();
        $selectedPeriod = $request->filled('period_id') ? PayrollPeriod::findOrFail($request->period_id) : $periods->first();

        $payrolls = $selectedPeriod ? Payroll::where('payroll_period_id', $selectedPeriod->id)
            ->with(['employee.department'])
            ->get() : collect();

        $summary = [
            'total_employees' => $payrolls->count(),
            'total_gross' => $payrolls->sum('gross_salary'),
            'total_deduction' => $payrolls->sum('total_deduction'),
            'total_net' => $payrolls->sum('net_salary'),
        ];

        return view('reports.payroll-summary', compact('periods', 'selectedPeriod', 'payrolls', 'summary'));
    }

    public function payrollDetail(Request $request)
    {
        $periods = PayrollPeriod::latest()->get();
        $selectedPeriod = $request->filled('period_id') ? PayrollPeriod::findOrFail($request->period_id) : $periods->first();

        $payrolls = $selectedPeriod ? Payroll::where('payroll_period_id', $selectedPeriod->id)
            ->with(['employee.department', 'details'])
            ->get() : collect();

        return view('reports.payroll-detail', compact('periods', 'selectedPeriod', 'payrolls'));
    }

    public function attendance(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $query = Attendance::with('employee.department')
            ->whereBetween('date', [$startDate, $endDate]);

        if ($request->filled('department_id')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        $attendances = $query->get();
        $departments = Department::all();

        $summary = [
            'present' => $attendances->where('status', 'present')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'leave' => $attendances->whereIn('status', ['annual_leave', 'sick', 'permission'])->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
        ];

        return view('reports.attendance', compact('attendances', 'startDate', 'endDate', 'departments', 'summary'));
    }

    public function overtime(Request $request)
    {
        $monthYear = $request->input('month_year', date('Y-m'));
        $month = date('m', strtotime($monthYear));
        $year = date('Y', strtotime($monthYear));

        $overtimes = Overtime::with('employee')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->selectRaw('employee_id, sum(hours) as total_hours, sum(amount) as total_amount')
            ->groupBy('employee_id')
            ->get();

        return view('reports.overtime', compact('overtimes'));
    }

    public function employee(Request $request)
    {
        $query = Employee::with(['department', 'position'])->active();

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('position_id')) {
            $query->where('position_id', $request->position_id);
        }

        $employees = $query->get();
        $departments = Department::all();
        $positions = Position::all();

        return view('reports.employee', compact('employees', 'departments', 'positions'));
    }

    public function export(Request $request, string $type)
    {
        // Implementation for export using Maatwebsite\Excel
        return back()->with('info', 'Fitur export sedang dalam pengembangan.');
    }
}
