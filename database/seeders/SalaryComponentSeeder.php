<?php

namespace Database\Seeders;

use App\Models\SalaryComponent;
use Illuminate\Database\Seeder;

class SalaryComponentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $components = [
            [
                'code' => 'GAPOK',
                'name' => 'Gaji Pokok',
                'type' => 'earning',
                'calculation_type' => 'fixed',
                'default_amount' => 0,
                'taxable' => true,
                'active' => true,
                'sort_order' => 1,
            ],
            [
                'code' => 'TJ_JAB',
                'name' => 'Tunjangan Jabatan',
                'type' => 'earning',
                'calculation_type' => 'fixed',
                'default_amount' => 0,
                'taxable' => true,
                'active' => true,
                'sort_order' => 2,
            ],
            [
                'code' => 'TJ_TRANS',
                'name' => 'Tunjangan Transport',
                'type' => 'earning',
                'calculation_type' => 'fixed',
                'default_amount' => 500000,
                'taxable' => true,
                'active' => true,
                'sort_order' => 3,
            ],
            [
                'code' => 'U_MAKAN',
                'name' => 'Uang Makan',
                'type' => 'earning',
                'calculation_type' => 'fixed',
                'default_amount' => 500000,
                'taxable' => true,
                'active' => true,
                'sort_order' => 4,
            ],
            [
                'code' => 'LEMBUR',
                'name' => 'Lembur',
                'type' => 'earning',
                'calculation_type' => 'fixed',
                'default_amount' => 0,
                'taxable' => true,
                'active' => true,
                'sort_order' => 5,
            ],
            [
                'code' => 'BONUS',
                'name' => 'Bonus',
                'type' => 'earning',
                'calculation_type' => 'fixed',
                'default_amount' => 0,
                'taxable' => true,
                'active' => true,
                'sort_order' => 6,
            ],
            [
                'code' => 'BPJS_KES',
                'name' => 'BPJS Kesehatan',
                'type' => 'deduction',
                'calculation_type' => 'fixed',
                'default_amount' => 0,
                'taxable' => false,
                'active' => true,
                'sort_order' => 7,
            ],
            [
                'code' => 'BPJS_TK',
                'name' => 'BPJS Ketenagakerjaan',
                'type' => 'deduction',
                'calculation_type' => 'fixed',
                'default_amount' => 0,
                'taxable' => false,
                'active' => true,
                'sort_order' => 8,
            ],
            [
                'code' => 'PPH21',
                'name' => 'PPh 21',
                'type' => 'deduction',
                'calculation_type' => 'fixed',
                'default_amount' => 0,
                'taxable' => false,
                'active' => false, // Coming Soon V2
                'sort_order' => 9,
            ],
            [
                'code' => 'KASBON',
                'name' => 'Kasbon',
                'type' => 'deduction',
                'calculation_type' => 'fixed',
                'default_amount' => 0,
                'taxable' => false,
                'active' => true,
                'sort_order' => 10,
            ],
            [
                'code' => 'POT_ABS',
                'name' => 'Potongan Absensi',
                'type' => 'deduction',
                'calculation_type' => 'fixed',
                'default_amount' => 0,
                'taxable' => false,
                'active' => true,
                'sort_order' => 11,
            ],
            [
                'code' => 'POT_LAIN',
                'name' => 'Potongan Lainnya',
                'type' => 'deduction',
                'calculation_type' => 'fixed',
                'default_amount' => 0,
                'taxable' => false,
                'active' => true,
                'sort_order' => 12,
            ],
        ];

        foreach ($components as $component) {
            SalaryComponent::firstOrCreate(
                ['code' => $component['code']],
                $component
            );
        }
    }
}
