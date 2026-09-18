<?php

namespace App\Exports;

use App\Models\Payroll;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PayrollSummaryExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    private int $periodId;

    public function __construct(int $periodId)
    {
        $this->periodId = $periodId;
    }

    public function collection()
    {
        return Payroll::where('payroll_period_id', $this->periodId)
            ->with(['employee.department', 'employee.position'])
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Karyawan',
            'Nama',
            'Departemen',
            'Jabatan',
            'Gaji Pokok',
            'Total Tunjangan',
            'Lembur',
            'Gaji Kotor',
            'Total Potongan',
            'Gaji Bersih',
            'Status',
        ];
    }

    public function map($payroll): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $payroll->employee->employee_code,
            $payroll->employee->name,
            $payroll->employee->department->name ?? '-',
            $payroll->employee->position->name ?? '-',
            $payroll->basic_salary,
            $payroll->total_earning,
            $payroll->overtime_amount,
            $payroll->gross_salary,
            $payroll->total_deduction,
            $payroll->net_salary,
            $payroll->status,
        ];
    }
}
