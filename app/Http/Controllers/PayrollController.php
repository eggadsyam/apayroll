<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\PayrollPeriod;
use App\Services\PayrollService;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function __construct(private PayrollService $payrollService) {}

    public function index(Request $request)
    {
        $periods = PayrollPeriod::latest()->get();
        $selectedPeriod = $request->filled('period_id')
            ? PayrollPeriod::findOrFail($request->period_id)
            : PayrollPeriod::latest()->first();

        $payrollsQuery = Payroll::where('payroll_period_id', $selectedPeriod?->id)
            ->with(['employee.department', 'employee.position']);

        $user = auth()->user();
        if ($user->hasRole('supervisor') && ! $user->hasRole('super_admin') && ! $user->hasRole('hrd') && ! $user->hasRole('finance') && ! $user->hasRole('manager')) {
            $payrollsQuery->whereHas('employee', function ($q) use ($user) {
                if ($user->employee) {
                    $q->where('supervisor_id', $user->employee->id);
                }
            });
        } elseif ($user->hasRole('manager') && ! $user->hasRole('super_admin') && ! $user->hasRole('hrd') && ! $user->hasRole('finance')) {
            $payrollsQuery->whereHas('employee', function ($q) use ($user) {
                if ($user->employee) {
                    $q->where('department_id', $user->employee->department_id);
                }
            });
        }

        $payrolls = $selectedPeriod ? $payrollsQuery->paginate(10)->withQueryString() : collect();

        return view('payrolls.index', compact('periods', 'selectedPeriod', 'payrolls'));
    }

    public function show(Payroll $payroll)
    {
        $payroll->load(['employee.department', 'employee.position', 'details', 'payrollPeriod']);

        return view('payrolls.show', compact('payroll'));
    }

    public function generate(PayrollPeriod $period)
    {
        try {
            $this->payrollService->generatePayroll($period);

            return back()->with('success', 'Payroll berhasil digenerate untuk semua karyawan aktif');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal generate payroll: '.$e->getMessage());
        }
    }

    public function submit(PayrollPeriod $period)
    {
        $this->payrollService->submitForApproval($period);

        return back()->with('success', 'Payroll berhasil disubmit untuk approval');
    }

    public function approve(PayrollPeriod $period)
    {
        $this->payrollService->approvePayroll($period, auth()->user());

        return back()->with('success', 'Payroll berhasil disetujui');
    }

    public function reject(Request $request, PayrollPeriod $period)
    {
        $request->validate(['reason' => 'required|string']);
        $this->payrollService->rejectPayroll($period, auth()->user(), $request->reason);

        return back()->with('success', 'Payroll ditolak');
    }

    public function pay(PayrollPeriod $period)
    {
        $this->payrollService->markAsPaid($period);

        return back()->with('success', 'Payroll berhasil ditandai sebagai dibayar');
    }
}
