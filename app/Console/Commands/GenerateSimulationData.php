<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Overtime;
use App\Models\PayrollPeriod;
use App\Models\User;
use App\Services\PayrollService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateSimulationData extends Command
{
    protected $signature = 'simulation:generate';

    protected $description = 'Generate attendance, overtime, leave, and payrolls for the past 3 months';

    public function handle(PayrollService $payrollService)
    {
        $this->info('Starting simulation data generation...');

        $months = [
            Carbon::now()->subMonths(3)->startOfMonth(),
            Carbon::now()->subMonths(2)->startOfMonth(),
            Carbon::now()->subMonths(1)->startOfMonth(),
        ];

        $employees = Employee::active()->get();
        if ($employees->isEmpty()) {
            $this->error('No employees found. Run seeder first.');

            return;
        }

        $superAdmin = User::role('super_admin')->first() ?? User::first();
        $leaveType = LeaveType::first();

        foreach ($months as $monthStart) {
            $monthEnd = $monthStart->copy()->endOfMonth();
            $periodName = $monthStart->translatedFormat('F Y');

            $this->info("Processing period: {$periodName}");

            // 1. Create Payroll Period
            $period = PayrollPeriod::firstOrCreate(
                ['name' => $periodName],
                [
                    'month' => $monthStart->month,
                    'year' => $monthStart->year,
                    'start_date' => $monthStart->format('Y-m-d'),
                    'end_date' => $monthEnd->format('Y-m-d'),
                    'payment_date' => $monthEnd->format('Y-m-d'),
                    'status' => 'draft',
                ]
            );

            // 2. Generate Attendances (Mass Insert)
            $this->info('Generating attendances...');
            $attendances = [];
            $overtimes = [];
            $leaves = [];

            $daysInMonth = $monthStart->daysInMonth;

            $bar = $this->output->createProgressBar($employees->count());

            foreach ($employees as $employee) {
                for ($d = 1; $d <= $daysInMonth; $d++) {
                    $date = $monthStart->copy()->addDays($d - 1);
                    if ($date->isWeekday()) {
                        // 5% chance of taking leave
                        if (rand(1, 100) <= 5 && $leaveType) {
                            $leaves[] = [
                                'employee_id' => $employee->id,
                                'leave_type_id' => $leaveType->id,
                                'start_date' => $date->format('Y-m-d'),
                                'end_date' => $date->format('Y-m-d'),
                                'days' => 1,
                                'reason' => 'Keperluan keluarga (Simulation)',
                                'status' => 'approved',
                                'approved_by' => $superAdmin->id,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        } else {
                            $isLate = rand(1, 100) <= 10;
                            $clockIn = clone $date;
                            $clockIn->setHour(8)->setMinute($isLate ? rand(10, 59) : rand(0, 15));

                            $clockOut = clone $date;
                            $clockOut->setHour(17)->setMinute(rand(0, 30));

                            $lateMinutes = $isLate ? $clockIn->minute : 0;

                            $attendances[] = [
                                'employee_id' => $employee->id,
                                'date' => $date->format('Y-m-d'),
                                'clock_in' => $clockIn->format('H:i:s'),
                                'clock_out' => $clockOut->format('H:i:s'),
                                'status' => 'present',
                                'late_minutes' => $lateMinutes,
                                'working_hours' => 8,
                                'overtime_hours' => 0,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];

                            // 10% chance of overtime
                            if (rand(1, 100) <= 10) {
                                $otStart = $clockOut->format('H:i:s');
                                $otEnd = (clone $clockOut)->addHours(rand(1, 3))->format('H:i:s');

                                $overtimes[] = [
                                    'employee_id' => $employee->id,
                                    'date' => $date->format('Y-m-d'),
                                    'start_time' => $otStart,
                                    'end_time' => $otEnd,
                                    'status' => 'approved',
                                    'approved_by' => $superAdmin->id,
                                ];
                            }
                        }
                    }
                }

                // Batch insert to avoid huge memory spikes
                if (count($attendances) >= 5000) {
                    Attendance::insert($attendances);
                    $attendances = [];
                }
                if (count($leaves) >= 500) {
                    Leave::insert($leaves);
                    $leaves = [];
                }

                $bar->advance();
            }
            $bar->finish();
            $this->newLine();

            // Insert remaining
            if (count($attendances) > 0) {
                Attendance::insert($attendances);
            }
            if (count($leaves) > 0) {
                Leave::insert($leaves);
            }

            // Overtimes require Model boot logic for hours & amount calculation, so we use loop and save
            $this->info('Saving overtimes...');
            foreach ($overtimes as $ot) {
                Overtime::create($ot);
            }

            // 3. Generate Payroll
            $this->info('Calculating payroll...');
            // Because PayrollService loops over employees, this will take some time
            $payrollService->generatePayroll($period);

            // 4. Approve and Pay
            $this->info('Approving and paying payroll...');
            $payrollService->approvePayroll($period, $superAdmin);
            $payrollService->markAsPaid($period);

            $this->info("Completed period: {$periodName}");
            $this->newLine();
        }

        $this->info('All simulation data generated successfully!');
    }
}
