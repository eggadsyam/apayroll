<?php

use App\Models\Attendance;
use App\Models\Employee;

$employees = Employee::active()->get();
$now = now();
// 30 days ago to today
$startDate = now()->subDays(30);
$endDate = now();

// Clear existing attendance in this range to prevent duplicates
Attendance::whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])->delete();

$attendanceData = [];

for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
    if ($date->isWeekend()) {
        continue;
    }

    $dateStr = $date->format('Y-m-d');

    foreach ($employees as $emp) {
        $rand = rand(1, 100);

        if ($rand <= 5) {
            $status = 'absent';
            $clockIn = null;
            $clockOut = null;
            $lateMinutes = 0;
            $workingHours = 0;
        } elseif ($rand <= 15) {
            $status = 'late';
            $minute = rand(1, 59);
            $clockIn = '08:'.str_pad($minute, 2, '0', STR_PAD_LEFT).':00';
            $clockOut = '17:'.str_pad(rand(0, 30), 2, '0', STR_PAD_LEFT).':00';
            $lateMinutes = $minute;
            $workingHours = 8;
        } else {
            $status = 'present';
            $clockIn = '07:'.str_pad(rand(30, 59), 2, '0', STR_PAD_LEFT).':00';
            $clockOut = '17:'.str_pad(rand(0, 30), 2, '0', STR_PAD_LEFT).':00';
            $lateMinutes = 0;
            $workingHours = 9;
        }

        $attendanceData[] = [
            'employee_id' => $emp->id,
            'date' => $dateStr,
            'clock_in' => $clockIn,
            'clock_out' => $clockOut,
            'status' => $status,
            'late_minutes' => $lateMinutes,
            'working_hours' => $workingHours,
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
}

// Insert in chunks of 1000
foreach (array_chunk($attendanceData, 1000) as $chunk) {
    Attendance::insert($chunk);
}

echo 'Successfully generated '.count($attendanceData)." attendance records for all 500 employees over the last 30 days.\n";
