<?php

namespace App\Services;

use App\Models\CompanySetting;
use App\Models\Payroll;
use App\Models\PayrollPeriod;
use Barryvdh\DomPDF\Facade\Pdf;

class PayslipService
{
    /**
     * Generate PDF payslip for a single employee's payroll.
     */
    public function generatePdf(Payroll $payroll)
    {
        $payroll->load(['employee.department', 'employee.position', 'details', 'payrollPeriod']);
        $company = CompanySetting::first();

        $pdf = Pdf::loadView('payslips.pdf', compact('payroll', 'company'));
        $pdf->setPaper('A5', 'portrait');

        $filename = 'slip-gaji-'.$payroll->employee->employee_code.'-'.$payroll->payrollPeriod->month.'-'.$payroll->payrollPeriod->year.'.pdf';

        return $pdf->download($filename);
    }

    /**
     * Generate bulk PDF payslips for all employees in a period.
     * Each payslip on a separate page.
     */
    public function generateBulkPdf(PayrollPeriod $period)
    {
        $payrolls = Payroll::where('payroll_period_id', $period->id)
            ->with(['employee.department', 'employee.position', 'details'])
            ->get();

        $company = CompanySetting::first();

        $pdf = Pdf::loadView('payslips.pdf-bulk', compact('payrolls', 'company', 'period'));
        $pdf->setPaper('A5', 'portrait');

        $filename = 'slip-gaji-'.$period->month.'-'.$period->year.'-semua.pdf';

        return $pdf->download($filename);
    }
}
