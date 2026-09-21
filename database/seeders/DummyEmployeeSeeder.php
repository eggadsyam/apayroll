<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\EmploymentStatus;
use App\Models\Position;
use App\Models\Shift;
use Faker\Factory;
use Illuminate\Database\Seeder;

class DummyEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = Department::pluck('id')->toArray();
        $positions = Position::pluck('id')->toArray();
        $statuses = EmploymentStatus::pluck('id')->toArray();
        $shifts = Shift::pluck('id')->toArray();

        // Ensure there's at least one of each to avoid errors
        if (empty($departments)) {
            $departments = [Department::create(['name' => 'General', 'code' => 'GEN'])->id];
        }
        if (empty($positions)) {
            $positions = [Position::create(['name' => 'Staff', 'code' => 'STF'])->id];
        }
        if (empty($statuses)) {
            $statuses = [EmploymentStatus::create(['name' => 'Permanent'])->id];
        }

        $faker = Factory::create('id_ID');

        $employees = [];
        for ($i = 1; $i <= 500; $i++) {
            $gender = $faker->randomElement(['male', 'female']);
            $firstName = $gender === 'male' ? $faker->firstNameMale : $faker->firstNameFemale;
            $lastName = $faker->lastName;
            $name = $firstName.' '.$lastName;

            // Unique email
            $email = 'emp'.$i.'@payroll.com';

            Employee::create([
                'nik' => $faker->unique()->numerify('################'),
                'name' => $name,
                'gender' => $gender,
                'birth_place' => $faker->city,
                'birth_date' => $faker->date('Y-m-d', '-20 years'),
                'address' => $faker->address,
                'phone' => $faker->phoneNumber,
                'email' => $email,
                'department_id' => $faker->randomElement($departments),
                'position_id' => $faker->randomElement($positions),
                'employment_status_id' => $faker->randomElement($statuses),
                'join_date' => $faker->date('Y-m-d', 'now'),
                'basic_salary' => $faker->numberBetween(3000000, 15000000),
                'status' => 'active',
                'shift_id' => ! empty($shifts) ? $faker->randomElement($shifts) : null,
                'ptkp_status' => $faker->randomElement(['TK/0', 'TK/1', 'K/0', 'K/1', 'K/2']),
                'tax_method' => $faker->randomElement(['gross', 'nett', 'gross_up']),
            ]);
        }
    }
}
