<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\CompanySetting;
use App\Models\Employee;
use Carbon\Carbon;

class AttendanceService
{
    /**
     * Create attendance record with auto-calculated fields
     */
    public function createAttendance(array $data): Attendance
    {
        $settings = CompanySetting::first();

        $employee = isset($data['employee_id']) ? Employee::with('shift')->find($data['employee_id']) : null;
        $defaultClockIn = $employee && $employee->shift ? $employee->shift->clock_in : $settings->default_clock_in;

        if (isset($data['clock_in']) && $data['clock_in']) {
            $data['late_minutes'] = $this->calculateLateMinutes($data['clock_in'], $defaultClockIn);

            if ($data['late_minutes'] > 0 && $data['status'] === 'present') {
                $data['status'] = 'late';
            }
        }

        if (isset($data['clock_in']) && isset($data['clock_out']) && $data['clock_in'] && $data['clock_out']) {
            $data['working_hours'] = $this->calculateWorkingHours($data['clock_in'], $data['clock_out']);
            $data['overtime_hours'] = $this->calculateOvertimeHours($data['working_hours'], $settings->working_hours_per_day);
        }

        return Attendance::create($data);
    }

    /**
     * Update attendance record with recalculated fields
     */
    public function updateAttendance(Attendance $attendance, array $data): Attendance
    {
        // Same calculation logic as create
        $settings = CompanySetting::first();

        $employee = $attendance->employee ?? (isset($data['employee_id']) ? Employee::with('shift')->find($data['employee_id']) : null);
        $defaultClockIn = $employee && $employee->shift ? $employee->shift->clock_in : $settings->default_clock_in;

        if (isset($data['clock_in']) && $data['clock_in']) {
            $data['late_minutes'] = $this->calculateLateMinutes($data['clock_in'], $defaultClockIn);

            if ($data['late_minutes'] > 0 && $data['status'] === 'present') {
                $data['status'] = 'late';
            }
        }

        if (isset($data['clock_in']) && isset($data['clock_out']) && $data['clock_in'] && $data['clock_out']) {
            $data['working_hours'] = $this->calculateWorkingHours($data['clock_in'], $data['clock_out']);
            $data['overtime_hours'] = $this->calculateOvertimeHours($data['working_hours'], $settings->working_hours_per_day);
        }

        $attendance->update($data);

        return $attendance;
    }

    public function calculateLateMinutes(string $clockIn, string $defaultClockIn): int
    {
        $clockInTime = Carbon::parse($clockIn);
        // Ensure defaultClockIn has seconds for createFromFormat if it's H:i:s
        if (strlen($defaultClockIn) == 5) {
            $defaultClockIn .= ':00';
        }
        $defaultTime = Carbon::createFromFormat('H:i:s', $defaultClockIn);

        if ($clockInTime->gt($defaultTime)) {
            return (int) $clockInTime->diffInMinutes($defaultTime);
        }

        return 0;
    }

    /**
     * Calculate working hours from clock in/out
     */
    public function calculateWorkingHours(string $clockIn, string $clockOut): float
    {
        $in = Carbon::parse($clockIn);
        $out = Carbon::parse($clockOut);

        // Subtract 1 hour for lunch break
        $hours = $in->diffInMinutes($out) / 60;
        if ($hours > 5) {
            $hours -= 1; // lunch break
        }

        return round($hours, 2);
    }

    /**
     * Calculate overtime hours (beyond standard working hours)
     */
    public function calculateOvertimeHours(float $workingHours, int $standardHours): float
    {
        if ($workingHours > $standardHours) {
            return round($workingHours - $standardHours, 2);
        }

        return 0;
    }
}
