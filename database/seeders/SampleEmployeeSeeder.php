<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\EmploymentStatus;
use App\Models\Position;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SampleEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Assign Super Admin role to Egga Dinarul Syam
        $eggaUser = User::where('email', 'eggadsyam@gmail.com')->first();
        if ($eggaUser) {
            $eggaUser->assignRole('super_admin');
        }

        $departments = Department::all();
        $managerPosition = Position::firstOrCreate(['name' => 'Manager'], ['name' => 'Manager']);
        $staffPosition = Position::firstOrCreate(['name' => 'Staff'], ['name' => 'Staff']);
        $status = EmploymentStatus::firstOrCreate(['name' => 'Permanent'], ['name' => 'Permanent']);

        foreach ($departments as $index => $dept) {
            $deptName = $dept->name;

            // Create Manager for this department
            $managerName = 'Manager '.$deptName;
            $managerEmail = 'manager.'.strtolower(str_replace(' ', '', $deptName)).'@payroll.com';

            // Generate unique nik and code
            $managerCode = 'EMP'.str_pad(($index + 1) * 100 + 1, 5, '0', STR_PAD_LEFT);
            $managerNik = '32101118069'.str_pad(($index + 1) * 100 + 1, 5, '0', STR_PAD_LEFT);

            $managerEmployee = Employee::firstOrCreate(
                ['email' => $managerEmail],
                [
                    'employee_code' => $managerCode,
                    'nik' => $managerNik,
                    'name' => $managerName,
                    'gender' => 'male',
                    'birth_date' => Carbon::now()->subYears(30),
                    'department_id' => $dept->id,
                    'position_id' => $managerPosition->id,
                    'employment_status_id' => $status->id,
                    'join_date' => Carbon::now()->subYears(2),
                    'basic_salary' => 10000000,
                    'status' => 'active',
                ]
            );

            $managerUser = User::firstOrCreate(
                ['email' => $managerEmail],
                [
                    'name' => $managerName,
                    'password' => Hash::make('password'),
                    'employee_id' => $managerEmployee->id,
                ]
            );
            $managerUser->assignRole('manager');

            if ($deptName === 'HRD') {
                $managerUser->assignRole('hrd');
            } elseif ($deptName === 'Finance') {
                $managerUser->assignRole('finance');
            }

            // Create Staff for this department
            $staffName = 'Staff '.$deptName;
            $staffEmail = 'staff.'.strtolower(str_replace(' ', '', $deptName)).'@payroll.com';

            $staffCode = 'EMP'.str_pad(($index + 1) * 100 + 2, 5, '0', STR_PAD_LEFT);
            $staffNik = '32101118069'.str_pad(($index + 1) * 100 + 2, 5, '0', STR_PAD_LEFT);

            $staffEmployee = Employee::firstOrCreate(
                ['email' => $staffEmail],
                [
                    'employee_code' => $staffCode,
                    'nik' => $staffNik,
                    'name' => $staffName,
                    'gender' => 'female',
                    'birth_date' => Carbon::now()->subYears(25),
                    'department_id' => $dept->id,
                    'position_id' => $staffPosition->id,
                    'employment_status_id' => $status->id,
                    'join_date' => Carbon::now()->subYears(1),
                    'basic_salary' => 5000000,
                    'status' => 'active',
                ]
            );

            $staffUser = User::firstOrCreate(
                ['email' => $staffEmail],
                [
                    'name' => $staffName,
                    'password' => Hash::make('password'),
                    'employee_id' => $staffEmployee->id,
                ]
            );
            $staffUser->assignRole('employee');

            if ($deptName === 'HRD') {
                $staffUser->assignRole('hrd');
            } elseif ($deptName === 'Finance') {
                $staffUser->assignRole('finance');
            }
        }
    }
}
