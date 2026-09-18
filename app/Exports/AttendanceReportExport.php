<?php

namespace App\Exports;

use App\Models\Attendance;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceReportExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    private string $startDate;

    private string $endDate;

    public function __construct(string $startDate, string $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return Attendance::with(['employee.department'])
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->orderBy('date', 'asc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Karyawan',
            'Nama',
            'Departemen',
            'Tanggal',
            'Jam Masuk',
            'Jam Keluar',
            'Status',
            'Terlambat (menit)',
            'Jam Kerja',
            'Lembur (jam)',
        ];
    }

    public function map($attendance): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $attendance->employee->employee_code,
            $attendance->employee->name,
            $attendance->employee->department->name ?? '-',
            Carbon::parse($attendance->date)->format('d-m-Y'),
            $attendance->clock_in ?? '-',
            $attendance->clock_out ?? '-',
            ucfirst($attendance->status),
            $attendance->late_minutes,
            $attendance->working_hours,
            $attendance->overtime_hours,
        ];
    }
}
