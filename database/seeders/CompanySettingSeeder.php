<?php

namespace Database\Seeders;

use App\Models\CompanySetting;
use Illuminate\Database\Seeder;

class CompanySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CompanySetting::firstOrCreate(
            ['id' => 1],
            [
                'company_name' => 'PT. Nama Perusahaan',
                'address' => 'Jl. Contoh No. 123, Jakarta',
                'phone' => '021-1234567',
                'email' => 'info@perusahaan.com',
                'overtime_rate_per_hour' => 25000,
                'late_penalty_per_minute' => 0,
                'working_hours_per_day' => 8,
                'default_clock_in' => '08:00:00',
                'default_clock_out' => '17:00:00',
            ]
        );
    }
}
