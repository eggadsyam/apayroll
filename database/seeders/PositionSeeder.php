<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            'Manager',
            'Supervisor',
            'Staff',
            'Operator',
            'Admin',
        ];

        foreach ($positions as $positionName) {
            Position::firstOrCreate([
                'name' => $positionName,
            ]);
        }
    }
}
