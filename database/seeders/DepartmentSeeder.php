<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            'IT',
            'Finance',
            'HRD',
            'Marketing',
            'Warehouse',
            'Production',
        ];

        foreach ($departments as $departmentName) {
            Department::firstOrCreate([
                'name' => $departmentName,
            ]);
        }
    }
}
