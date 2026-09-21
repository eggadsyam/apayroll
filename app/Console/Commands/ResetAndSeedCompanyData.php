<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\CompanySetting;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmploymentStatus;
use App\Models\Overtime;
use App\Models\Payroll;
use App\Models\PayrollDetail;
use App\Models\PayrollPeriod;
use App\Models\Position;
use App\Models\User;
use App\Services\PayrollService;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetAndSeedCompanyData extends Command
{
    protected $signature = 'company:reset-and-seed';

    protected $description = 'Reset company data and seed exactly 500 employees with realistic distribution and 3 months payroll';

    public function handle()
    {
        $this->info('Starting data reset and seeding...');

        $superAdminUser = User::role('super_admin')->first();
        if (! $superAdminUser) {
            $superAdminUser = User::where('email', 'admin@payroll.com')->first();
        }

        $superAdminEmployeeId = $superAdminUser ? $superAdminUser->employee_id : null;

        // Truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Attendance::truncate();
        Overtime::truncate();
        PayrollDetail::truncate();
        Payroll::truncate();
        PayrollPeriod::truncate();

        // Delete users except super admin
        if ($superAdminUser) {
            User::where('id', '!=', $superAdminUser->id)->delete();
        } else {
            User::truncate();
        }

        // Delete employees except super admin's employee
        if ($superAdminEmployeeId) {
            Employee::where('id', '!=', $superAdminEmployeeId)->delete();
        } else {
            Employee::truncate();
        }

        Department::truncate();
        Position::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('Old data cleared. Recreating Master Data...');

        // Recreate Departments
        $depts = [
            'IT' => Department::create(['name' => 'IT', 'code' => 'IT']),
            'HRD' => Department::create(['name' => 'HRD', 'code' => 'HRD']),
            'Finance' => Department::create(['name' => 'Finance', 'code' => 'FIN']),
            'Marketing' => Department::create(['name' => 'Marketing', 'code' => 'MKT']),
            'Produksi' => Department::create(['name' => 'Produksi', 'code' => 'PRD']),
        ];

        // Recreate Positions
        $positions = [
            'Manager' => Position::create(['name' => 'Manager', 'code' => 'MGR']),
            'Supervisor' => Position::create(['name' => 'Supervisor', 'code' => 'SPV']),
            'Staff' => Position::create(['name' => 'Staff', 'code' => 'STF']),
            'Operator' => Position::create(['name' => 'Operator', 'code' => 'OPR']),
        ];

        $status = EmploymentStatus::firstOrCreate(['name' => 'Permanent'], ['name' => 'Permanent', 'code' => 'PRM']);

        $faker = Factory::create('id_ID');

        $structure = [
            'IT' => ['Manager' => 1, 'Supervisor' => 1, 'Staff' => 8],
            'HRD' => ['Manager' => 1, 'Supervisor' => 1, 'Staff' => 8],
            'Finance' => ['Manager' => 1, 'Supervisor' => 2, 'Staff' => 12],
            'Marketing' => ['Manager' => 1, 'Supervisor' => 2, 'Staff' => 12],
            'Produksi' => ['Manager' => 1, 'Supervisor' => 10, 'Operator' => 439],
        ];

        $salaryRanges = [
            'Manager' => [15000000, 20000000],
            'Supervisor' => [8000000, 10000000],
            'Staff' => [5000000, 7000000],
            'Operator' => [4500000, 5500000],
        ];

        $empCount = 1;
        $allEmployees = collect();

        $this->info('Generating 500 Employees...');

        foreach ($structure as $deptName => $roles) {
            foreach ($roles as $roleName => $count) {
                for ($i = 0; $i < $count; $i++) {
                    $gender = $faker->randomElement(['male', 'female']);
                    $firstName = $gender === 'male' ? $faker->firstNameMale : $faker->firstNameFemale;
                    $lastName = $faker->lastName;
                    $name = $firstName.' '.$lastName;

                    // clean up name for email
                    $cleanFirst = preg_replace('/[^a-zA-Z]/', '', $firstName);
                    $cleanLast = preg_replace('/[^a-zA-Z]/', '', $lastName);

                    $email = strtolower($cleanFirst).'.'.strtolower($cleanLast).$empCount.'@company.com';

                    $salaryMin = $salaryRanges[$roleName][0];
                    $salaryMax = $salaryRanges[$roleName][1];
                    // round to nearest 100k
                    $basicSalary = round($faker->numberBetween($salaryMin, $salaryMax) / 100000) * 100000;

                    $emp = Employee::create([
                        'nik' => '321'.str_pad($empCount, 8, '0', STR_PAD_LEFT),
                        'employee_code' => 'EMP'.str_pad($empCount, 4, '0', STR_PAD_LEFT),
                        'name' => $name,
                        'gender' => $gender,
                        'birth_place' => $faker->city,
                        'birth_date' => $faker->date('Y-m-d', '-25 years'),
                        'address' => $faker->address,
                        'phone' => $faker->phoneNumber,
                        'email' => $email,
                        'department_id' => $depts[$deptName]->id,
                        'position_id' => $positions[$roleName]->id,
                        'employment_status_id' => $status->id,
                        'join_date' => Carbon::now()->subYears($faker->numberBetween(1, 5)),
                        'basic_salary' => $basicSalary,
                        'status' => 'active',
                        'ptkp_status' => $faker->randomElement(['TK/0', 'TK/1', 'K/0', 'K/1', 'K/2']),
                        'tax_method' => 'gross',
                        'is_bpjs_kesehatan_active' => true,
                        'is_bpjs_ketenagakerjaan_active' => true,
                    ]);

                    $user = $emp->user;

                    if ($roleName === 'Manager') {
                        $user->assignRole('manager');
                    }
                    if ($deptName === 'HRD') {
                        $user->assignRole('hrd');
                    } elseif ($deptName === 'Finance') {
                        $user->assignRole('finance');
                    }

                    $allEmployees->push($emp);
                    $empCount++;
                }
            }
        }
        $this->info('Created '.count($allEmployees).' employees.');

        // Generate 3 months of payroll
        $this->info('Generating 3 months of payroll (This might take a while)...');
        $payrollService = app(PayrollService::class);
        $setting = CompanySetting::first();
        if ($setting) {
            $setting->update([
                'late_penalty_per_minute' => 1000,
                'overtime_rate_per_hour' => 50000,
            ]);
        }

        $now = now();
        $months = [
            $now->copy()->subMonths(3),
            $now->copy()->subMonths(2),
            $now->copy()->subMonths(1),
        ];

        foreach ($months as $month) {
            $period = PayrollPeriod::create([
                'name' => 'Payroll '.$month->translatedFormat('F Y'),
                'month' => $month->month,
                'year' => $month->year,
                'start_date' => $month->copy()->startOfMonth()->format('Y-m-d'),
                'end_date' => $month->copy()->endOfMonth()->format('Y-m-d'),
                'payment_date' => $month->copy()->endOfMonth()->format('Y-m-d'),
                'status' => 'draft',
            ]);

            $this->info("Processing period: {$period->month}/{$period->year}");

            $startDate = Carbon::parse($period->start_date);
            $endDate = Carbon::parse($period->end_date);

            $attendanceData = [];
            $overtimeData = [];

            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                if ($date->isWeekend()) {
                    continue;
                }
                $dateStr = $date->format('Y-m-d');

                foreach ($allEmployees as $emp) {
                    $rand = rand(1, 100);

                    if ($rand <= 2) {
                        $statusA = 'absent';
                        $clockIn = null;
                        $clockOut = null;
                        $lateMinutes = 0;
                        $workingHours = 0;
                    } elseif ($rand <= 10) {
                        $statusA = 'late';
                        $minute = rand(1, 59);
                        $clockIn = '08:'.str_pad($minute, 2, '0', STR_PAD_LEFT).':00';
                        $clockOut = '17:'.str_pad(rand(0, 30), 2, '0', STR_PAD_LEFT).':00';
                        $lateMinutes = $minute;
                        $workingHours = 8;
                    } else {
                        $statusA = 'present';
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
                        'status' => $statusA,
                        'late_minutes' => $lateMinutes,
                        'working_hours' => $workingHours,
                        'overtime_hours' => 0,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];

                    $isStaffOrOperator = in_array($emp->position->name, ['Operator', 'Staff']);
                    $overtimeChance = $isStaffOrOperator ? 30 : 5; // 30% for Staff/Operator, 5% for Mgr/Spv

                    if ($statusA !== 'absent' && rand(1, 100) <= $overtimeChance) {
                        $overtimeHours = rand(1, 3);
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
                            'notes' => 'Lembur Harian',
                            'status' => 'approved',
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }
            }

            foreach (array_chunk($attendanceData, 2000) as $chunk) {
                Attendance::insert($chunk);
            }
            foreach (array_chunk($overtimeData, 2000) as $chunk) {
                Overtime::insert($chunk);
            }

            $payrollService->generatePayroll($period);
            $payrollService->submitForApproval($period);
            $payrollService->approvePayroll($period, $superAdminUser ?? User::first());
            $payrollService->markAsPaid($period);
        }

        $this->info('Successfully reset and seeded data!');
    }
}
