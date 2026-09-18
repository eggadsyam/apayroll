<?php

namespace Database\Seeders;

use App\Models\EmploymentStatus;
use Illuminate\Database\Seeder;

class EmploymentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            'Tetap',
            'Kontrak',
            'Harian',
            'Freelance',
            'Probation',
        ];

        foreach ($statuses as $statusName) {
            EmploymentStatus::firstOrCreate([
                'name' => $statusName,
            ]);
        }
    }
}
