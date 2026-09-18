<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DummyAttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::active()->pluck('id');
        $dates = [];

        // Generate dates for the last 14 days up to today
        for ($i = 14; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            // Skip weekends
            if ($date->isWeekday()) {
                $dates[] = $date->format('Y-m-d');
            }
        }

        $insertData = [];
        $now = now();

        foreach ($dates as $date) {
            foreach ($employees as $empId) {
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

                $insertData[] = [
                    'employee_id' => $empId,
                    'date' => $date,
                    'clock_in' => $clockIn,
                    'clock_out' => $clockOut,
                    'status' => $status,
                    'late_minutes' => $lateMinutes,
                    'working_hours' => $workingHours,
                    'overtime_hours' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        // Truncate previous data or just append? The user wants data because it's empty,
        // but just in case, we can keep appending or delete the date range. Let's just insert.
        foreach (array_chunk($insertData, 1000) as $chunk) {
            Attendance::insert($chunk);
        }
    }
}
