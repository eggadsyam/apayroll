<?php

namespace App\Exports;

use App\Models\Payroll;
use App\Models\PayrollDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PayrollDetailExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    private int $periodId;

    private array $components = [];

    public function __construct(int $periodId)
    {
        $this->periodId = $periodId;

        // Get all unique component names for this period
        $this->components = PayrollDetail::whereHas('payroll', function ($q) {
            $q->where('payroll_period_id', $this->periodId);
        })->pluck('component_name')->unique()->toArray();
    }

    public function collection()
    {
        return Payroll::where('payroll_period_id', $this->periodId)
            ->with(['employee.department', 'employee.position', 'details'])
            ->get();
    }

    public function headings(): array
    {
        $headings = [
            'No',
            'Kode Karyawan',
            'Nama',
            'Departemen',
            'Jabatan',
            'Gaji Pokok',
        ];

        // Add dynamic components
        foreach ($this->components as $component) {
            if ($component !== 'Gaji Pokok') {
                $headings[] = $component;
            }
        }

        $headings = array_merge($headings, [
            'Lembur',
            'Gaji Kotor',
            'Total Potongan',
            'Gaji Bersih',
            'Status',
        ]);

        return $headings;
    }

    public function map($payroll): array
    {
        static $no = 0;
        $no++;

        $row = [
            $no,
            $payroll->employee->employee_code,
            $payroll->employee->name,
            $payroll->employee->department->name ?? '-',
            $payroll->employee->position->name ?? '-',
            $payroll->basic_salary,
        ];

        // Map dynamic components
        $details = $payroll->details->keyBy('component_name');

        foreach ($this->components as $component) {
            if ($component !== 'Gaji Pokok') {
                $amount = isset($details[$component]) ? $details[$component]->amount : 0;
                $row[] = $amount;
            }
        }

        $row = array_merge($row, [
            $payroll->overtime_amount,
            $payroll->gross_salary,
            $payroll->total_deduction,
            $payroll->net_salary,
            $payroll->status,
        ]);

        return $row;
    }
}
