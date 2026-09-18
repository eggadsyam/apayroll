<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\CompanySetting;
use App\Models\Employee;
use App\Models\Overtime;
use App\Models\PayrollPeriod;
use App\Models\User;
use App\Services\PayrollService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RealSimulationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clean up previous simulation data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Attendance::truncate();
        Overtime::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $employees = Employee::active()->get();
        // Get last 6 payroll periods
        $periods = PayrollPeriod::orderBy('start_date', 'desc')->take(6)->get()->reverse();

        $now = now();

        // Setup company settings for penalties
        $setting = CompanySetting::first();
        if ($setting) {
            $setting->update([
                'late_penalty_per_minute' => 1000, // 1000 rupiah per minute
                'overtime_rate_per_hour' => 50000,
            ]);
        }

        // Ensure some employees have BPJS active and tax method gross
        foreach ($employees->random(min(100, $employees->count())) as $emp) {
            $emp->update([
                'is_bpjs_kesehatan_active' => true,
                'is_bpjs_ketenagakerjaan_active' => true,
                'tax_method' => 'gross', // ensures tax is calculated and deducted
            ]);
        }

        $payrollService = app(PayrollService::class);
        $user = User::first() ?? User::factory()->create();

        foreach ($periods as $period) {
            $this->command->info('Processing period: '.$period->month.'/'.$period->year);

            $startDate = Carbon::parse($period->start_date);
            $endDate = Carbon::parse($period->end_date);

            // We'll generate data in chunks to prevent memory issues
            $attendanceData = [];
            $overtimeData = [];

            // Loop through each day in the period
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                if ($date->isWeekend()) {
                    continue; // Skip weekends
                }

                $dateStr = $date->format('Y-m-d');

                // Process 50 random employees to save time, doing all 500 would be 500 * 20 = 10000 records per month
                // Let's do 100 random employees per day to keep DB size reasonable but realistic
                $dailyEmps = $employees->random(min(100, $employees->count()));

                foreach ($dailyEmps as $emp) {
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
                        'overtime_hours' => 0,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];

                    // 5% chance of overtime
                    if ($rand > 95) {
                        $overtimeHours = rand(1, 4);
                        $overtimeRate = 50000;
                        $overtimeAmount = $overtimeHours * $overtimeRate;
                        $overtimeData[] = [
                            'employee_id' => $emp->id,
                            'date' => $dateStr,
                            'start_time' => '17:00:00',
                            'end_time' => (17 + $overtimeHours).':00:00',
                            'hours' => $overtimeHours,
                            'rate' => $overtimeRate,
                            'amount' => $overtimeAmount,
                            'notes' => 'Simulasi Lembur',
                            'status' => 'approved',
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }
            }

            // Insert Attendance
            foreach (array_chunk($attendanceData, 1000) as $chunk) {
                Attendance::insert($chunk);
            }

            // Insert Overtime
            foreach (array_chunk($overtimeData, 1000) as $chunk) {
                Overtime::insert($chunk);
            }

            // Re-generate payroll for this period
            // Need to set status to draft first
            $period->update(['status' => 'draft']);
            $payrollService->generatePayroll($period);

            // Approve and Pay
            $payrollService->submitForApproval($period);
            $payrollService->approvePayroll($period, $user);
            $payrollService->markAsPaid($period);
        }
    }
}
