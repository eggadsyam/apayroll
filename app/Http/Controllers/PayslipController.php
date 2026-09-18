<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\Payroll;
use App\Models\PayrollPeriod;
use App\Services\PayslipService;
use Illuminate\Http\Request;

class PayslipController extends Controller
{
    public function index(Request $request)
    {
        $periods = PayrollPeriod::whereIn('status', ['paid', 'approved', 'closed'])->latest()->get();
        $selectedPeriod = $request->filled('period_id') ? PayrollPeriod::findOrFail($request->period_id) : $periods->first();

        $payrolls = $selectedPeriod ? Payroll::where('payroll_period_id', $selectedPeriod->id)
            ->with(['employee.department', 'employee.position'])
            ->paginate(20) : collect();

        return view('payslips.index', compact('periods', 'selectedPeriod', 'payrolls'));
    }

    public function show(Payroll $payroll)
    {
        $payroll->load(['employee.department', 'employee.position', 'details', 'payrollPeriod']);
        $company = CompanySetting::first();

        return view('payslips.show', compact('payroll', 'company'));
    }

    public function downloadPdf(Payroll $payroll)
    {
        return app(PayslipService::class)->generatePdf($payroll);
    }

    public function downloadBulkPdf(PayrollPeriod $period)
    {
        return app(PayslipService::class)->generateBulkPdf($period);
    }
}
