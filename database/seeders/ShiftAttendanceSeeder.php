<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ShiftAttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create shifts
        $shiftStaff = Shift::firstOrCreate(
            ['name' => 'Shift Staff'],
            ['clock_in' => '08:00:00', 'clock_out' => '17:00:00']
        );
        $shift1 = Shift::firstOrCreate(
            ['name' => 'Shift 1'],
            ['clock_in' => '07:00:00', 'clock_out' => '15:00:00']
        );
        $shift2 = Shift::firstOrCreate(
            ['name' => 'Shift 2'],
            ['clock_in' => '15:00:00', 'clock_out' => '23:00:00']
        );
        $shift3 = Shift::firstOrCreate(
            ['name' => 'Shift 3'],
            ['clock_in' => '23:00:00', 'clock_out' => '07:00:00']
        );

        // 2. Assign shifts to employees
        // Assuming position 'Operator' exists
        $employees = Employee::with('position')->get();
        foreach ($employees as $employee) {
            $isOperator = false;
            if ($employee->position && stripos($employee->position->name, 'Operator') !== false) {
                $isOperator = true;
            }

            if ($isOperator) {
                // Randomly assign one of the 3 shifts for Operator
                $assignedShift = collect([$shift1, $shift2, $shift3])->random();
            } else {
                // Staff or others get Staff Shift
                $assignedShift = $shiftStaff;
            }

            $employee->shift_id = $assignedShift->id;
            $employee->save();

            // 3. Adjust their attendance to match the shift schedule
            $attendances = Attendance::where('employee_id', $employee->id)->whereNotNull('clock_in')->get();
            foreach ($attendances as $attendance) {
                $clockInBase = Carbon::parse($assignedShift->clock_in);
                $clockOutBase = Carbon::parse($assignedShift->clock_out);

                // Add some random variation
                $randomIn = rand(-15, 15);
                $randomOut = rand(0, 30);

                $actualIn = $clockInBase->copy()->addMinutes($randomIn);
                $actualOut = $clockOutBase->copy()->addMinutes($randomOut);

                $attendance->clock_in = $actualIn->format('H:i:s');
                $attendance->clock_out = $actualOut->format('H:i:s');

                // Re-calculate late minutes
                $late = 0;
                if ($randomIn > 0) {
                    $late = $randomIn;
                }
                $attendance->late_minutes = $late;

                // Re-calculate working hours
                $attendance->working_hours = 8;

                $attendance->save();
            }
        }
    }
}
