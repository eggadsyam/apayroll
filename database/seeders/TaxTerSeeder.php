<?php

namespace Database\Seeders;

use App\Models\TaxTerCategory;
use Illuminate\Database\Seeder;

class TaxTerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Kategori A',
                'ptkp_list' => 'TK/0, TK/1, K/0',
                'rates' => [
                    ['min' => 0, 'max' => 5400000, 'pct' => 0],
                    ['min' => 5400000, 'max' => 5650000, 'pct' => 0.25],
                    ['min' => 5650000, 'max' => 5950000, 'pct' => 0.5],
                    ['min' => 5950000, 'max' => 6300000, 'pct' => 0.75],
                    ['min' => 6300000, 'max' => 6750000, 'pct' => 1.0],
                    ['min' => 6750000, 'max' => 7500000, 'pct' => 1.25],
                    ['min' => 7500000, 'max' => 8550000, 'pct' => 1.5],
                    ['min' => 8550000, 'max' => 9650000, 'pct' => 1.75],
                    ['min' => 9650000, 'max' => 10050000, 'pct' => 2.0],
                    ['min' => 10050000, 'max' => 10350000, 'pct' => 2.25],
                    ['min' => 10350000, 'max' => 10700000, 'pct' => 2.5],
                    ['min' => 10700000, 'max' => 11050000, 'pct' => 3.0],
                    ['min' => 11050000, 'max' => 11600000, 'pct' => 3.5],
                    ['min' => 11600000, 'max' => 12500000, 'pct' => 4.0],
                    ['min' => 12500000, 'max' => 13750000, 'pct' => 5.0],
                    ['min' => 13750000, 'max' => 15100000, 'pct' => 6.0],
                    ['min' => 15100000, 'max' => 16950000, 'pct' => 7.0],
                    ['min' => 16950000, 'max' => 20750000, 'pct' => 8.0],
                    ['min' => 20750000, 'max' => 26850000, 'pct' => 9.0],
                    ['min' => 26850000, 'max' => null, 'pct' => 10.0], // Simplified max for testing
                ],
            ],
            [
                'name' => 'Kategori B',
                'ptkp_list' => 'TK/2, TK/3, K/1, K/2',
                'rates' => [
                    ['min' => 0, 'max' => 6200000, 'pct' => 0],
                    ['min' => 6200000, 'max' => 6500000, 'pct' => 0.25],
                    ['min' => 6500000, 'max' => 6850000, 'pct' => 0.5],
                    ['min' => 6850000, 'max' => 7300000, 'pct' => 0.75],
                    ['min' => 7300000, 'max' => 9200000, 'pct' => 1.0],
                    ['min' => 9200000, 'max' => 10750000, 'pct' => 1.5],
                    ['min' => 10750000, 'max' => 11250000, 'pct' => 2.0],
                    ['min' => 11250000, 'max' => 11600000, 'pct' => 2.5],
                    ['min' => 11600000, 'max' => 12600000, 'pct' => 3.0],
                    ['min' => 12600000, 'max' => 13600000, 'pct' => 4.0],
                    ['min' => 13600000, 'max' => 14950000, 'pct' => 5.0],
                    ['min' => 14950000, 'max' => 16400000, 'pct' => 6.0],
                    ['min' => 16400000, 'max' => 18450000, 'pct' => 7.0],
                    ['min' => 18450000, 'max' => 22700000, 'pct' => 8.0],
                    ['min' => 22700000, 'max' => null, 'pct' => 9.0], // Simplified max for testing
                ],
            ],
            [
                'name' => 'Kategori C',
                'ptkp_list' => 'K/3',
                'rates' => [
                    ['min' => 0, 'max' => 6600000, 'pct' => 0],
                    ['min' => 6600000, 'max' => 6950000, 'pct' => 0.25],
                    ['min' => 6950000, 'max' => 7350000, 'pct' => 0.5],
                    ['min' => 7350000, 'max' => 7800000, 'pct' => 0.75],
                    ['min' => 7800000, 'max' => 8850000, 'pct' => 1.0],
                    ['min' => 8850000, 'max' => 9800000, 'pct' => 1.25],
                    ['min' => 9800000, 'max' => 10950000, 'pct' => 1.5],
                    ['min' => 10950000, 'max' => 11200000, 'pct' => 1.75],
                    ['min' => 11200000, 'max' => 11600000, 'pct' => 2.0],
                    ['min' => 11600000, 'max' => null, 'pct' => 3.0], // Simplified max for testing
                ],
            ],
        ];

        foreach ($categories as $catData) {
            $category = TaxTerCategory::firstOrCreate([
                'name' => $catData['name'],
                'ptkp_list' => $catData['ptkp_list'],
            ]);

            // Clear old rates
            $category->rates()->delete();

            foreach ($catData['rates'] as $rate) {
                $category->rates()->create([
                    'min_bruto' => $rate['min'],
                    'max_bruto' => $rate['max'],
                    'percentage' => $rate['pct'],
                ]);
            }
        }
    }
}
