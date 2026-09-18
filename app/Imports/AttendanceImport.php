<?php

namespace App\Imports;

use App\Models\Attendance;
use App\Models\CompanySetting;
use App\Models\Employee;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AttendanceImport implements ToModel, WithHeadingRow, WithValidation
{
    use SkipsErrors;

    private int $rowCount = 0;

    private AttendanceService $service;

    private CompanySetting $settings;

    public function __construct()
    {
        $this->service = app(AttendanceService::class);
        $this->settings = CompanySetting::first();
    }

    /**
     * Map each row to an Attendance model.
     * Expected columns: employee_id (or employee_code), date (tanggal), clock_in (jam_masuk), clock_out (jam_keluar)
     */
    public function model(array $row)
    {
        $this->rowCount++;

        // Find employee by code
        $employee = Employee::where('employee_code', $row['employee_id'] ?? $row['kode_karyawan'] ?? null)->first();

        if (! $employee) {
            return null;
        }

        $clockIn = $row['clock_in'] ?? $row['jam_masuk'] ?? null;
        $clockOut = $row['clock_out'] ?? $row['jam_keluar'] ?? null;
        $date = $row['date'] ?? $row['tanggal'] ?? null;

        // Parse date
        if (is_numeric($date)) {
            $date = Carbon::instance(Date::excelToDateTimeObject($date))->format('Y-m-d');
        } else {
            $date = Carbon::parse($date)->format('Y-m-d');
        }

        // Parse time
        if (is_numeric($clockIn)) {
            $clockIn = Carbon::instance(Date::excelToDateTimeObject($clockIn))->format('H:i');
        }
        if (is_numeric($clockOut)) {
            $clockOut = Carbon::instance(Date::excelToDateTimeObject($clockOut))->format('H:i');
        }

        // Calculate fields
        $defaultClockIn = $employee->shift ? $employee->shift->clock_in : $this->settings->default_clock_in;
        $lateMinutes = $clockIn ? $this->service->calculateLateMinutes($clockIn, $defaultClockIn) : 0;
        $workingHours = ($clockIn && $clockOut) ? $this->service->calculateWorkingHours($clockIn, $clockOut) : 0;
        $overtimeHours = $workingHours > 0 ? $this->service->calculateOvertimeHours($workingHours, $this->settings->working_hours_per_day) : 0;
        $status = $lateMinutes > 0 ? 'late' : ($clockIn ? 'present' : 'absent');

        // Check for existing record (update if exists)
        $attendance = Attendance::updateOrCreate(
            ['employee_id' => $employee->id, 'date' => $date],
            [
                'clock_in' => $clockIn,
                'clock_out' => $clockOut,
                'status' => $status,
                'late_minutes' => $lateMinutes,
                'working_hours' => $workingHours,
                'overtime_hours' => $overtimeHours,
            ]
        );

        return null; // We use updateOrCreate above, so don't return model
    }

    public function rules(): array
    {
        return [
            // Flexible column name support
        ];
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }
}
