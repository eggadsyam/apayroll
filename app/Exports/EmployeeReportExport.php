<?php

namespace App\Exports;

use App\Models\Employee;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeeReportExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    public function collection()
    {
        return Employee::with(['department', 'position'])->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode',
            'Nama',
            'Departemen',
            'Jabatan',
            'Status',
            'Tgl Masuk',
            'Gaji Pokok',
        ];
    }

    public function map($employee): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $employee->employee_code,
            $employee->name,
            $employee->department->name ?? '-',
            $employee->position->name ?? '-',
            ucfirst($employee->status),
            $employee->join_date ? Carbon::parse($employee->join_date)->format('d-m-Y') : '-',
            $employee->basic_salary,
        ];
    }
}
