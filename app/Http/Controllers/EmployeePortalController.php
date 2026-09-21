<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Overtime;
use App\Models\Payroll;
use App\Models\User;
use App\Notifications\LeaveNotification;
use App\Services\PayslipService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class EmployeePortalController extends Controller
{
    public function dashboard()
    {
        $employeeId = auth()->user()->employee_id;
        $attendances = Attendance::where('employee_id', $employeeId)->latest('date')->take(5)->get();
        $leaves = Leave::where('employee_id', $employeeId)->latest()->take(5)->get();

        return view('portal.dashboard', compact('attendances', 'leaves'));
    }

    public function profile()
    {
        $employee = auth()->user()->employee;
        if ($employee) {
            $employee->load(['department', 'position', 'employmentStatus']);
        }

        return view('portal.profile', compact('employee'));
    }

    public function attendance()
    {
        $attendances = Attendance::where('employee_id', auth()->user()->employee_id)
            ->latest('date')->paginate(10);

        return view('portal.attendance', compact('attendances'));
    }

    public function leave()
    {
        $leaves = Leave::where('employee_id', auth()->user()->employee_id)
            ->with('leaveType')->latest()->paginate(10);
        $leaveTypes = LeaveType::all();

        return view('portal.leave.index', compact('leaves', 'leaveTypes'));
    }

    public function storeLeave(Request $request)
    {
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);
        $validated['employee_id'] = auth()->user()->employee_id;
        $validated['status'] = 'pending_supervisor';
        $validated['days'] = Carbon::parse($validated['start_date'])->diffInDays(Carbon::parse($validated['end_date'])) + 1;

        $leave = Leave::create($validated);

        // Notify Supervisor, Manager and HRD
        $supervisorId = auth()->user()->employee->supervisor_id ?? null;
        $departmentId = auth()->user()->employee->department_id ?? null;

        $supervisors = User::role('supervisor')->whereHas('employee', function ($q) use ($supervisorId) {
            $q->where('id', $supervisorId);
        })->get();

        $managers = User::role('manager')->whereHas('employee', function ($q) use ($departmentId) {
            $q->where('department_id', $departmentId);
        })->get();
        $hrds = User::role('hrd')->get();
        $recipients = $supervisors->merge($managers)->merge($hrds);

        Notification::send($recipients, new LeaveNotification(
            'Pengajuan Cuti Baru',
            auth()->user()->name.' mengajukan cuti.',
            route('leaves.index')
        ));

        return back()->with('success', 'Pengajuan cuti berhasil ditambahkan');
    }

    public function overtime()
    {
        $overtimes = Overtime::where('employee_id', auth()->user()->employee_id)
            ->latest('date')->paginate(10);

        return view('portal.overtime', compact('overtimes'));
    }

    public function payslip()
    {
        $payrolls = Payroll::where('employee_id', auth()->user()->employee_id)
            ->with('payrollPeriod')->latest()->paginate(10);

        return view('portal.payslip', compact('payrolls'));
    }

    public function downloadPayslip(Payroll $payroll)
    {
        if ($payroll->employee_id !== auth()->user()->employee_id) {
            abort(403);
        }

        return app(PayslipService::class)->generatePdf($payroll);
    }
}
