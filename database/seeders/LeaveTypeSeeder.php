<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leaveTypes = [
            [
                'name' => 'Cuti Tahunan',
                'max_days' => 12,
                'description' => 'Hak cuti tahunan (minimal 12 hari kerja setelah 12 bulan bekerja terus menerus).',
            ],
            [
                'name' => 'Cuti Sakit',
                'max_days' => 0,
                'description' => 'Cuti karena sakit (memerlukan surat keterangan dokter).',
            ],
            [
                'name' => 'Cuti Melahirkan',
                'max_days' => 90,
                'description' => 'Cuti melahirkan untuk pekerja perempuan (1,5 bulan sebelum dan 1,5 bulan sesudah melahirkan).',
            ],
            [
                'name' => 'Cuti Keguguran',
                'max_days' => 45,
                'description' => 'Cuti karena keguguran kandungan (1,5 bulan atau sesuai surat keterangan dokter kandungan).',
            ],
            [
                'name' => 'Cuti Haid',
                'max_days' => 2,
                'description' => 'Cuti sakit karena haid (hari pertama dan kedua pada masa haid).',
            ],
            [
                'name' => 'Cuti Menikah',
                'max_days' => 3,
                'description' => 'Cuti alasan penting: Pekerja/Buruh menikah.',
            ],
            [
                'name' => 'Cuti Menikahkan Anak',
                'max_days' => 2,
                'description' => 'Cuti alasan penting: Menikahkan anak.',
            ],
            [
                'name' => 'Cuti Khitanan/Baptis Anak',
                'max_days' => 2,
                'description' => 'Cuti alasan penting: Mengkhitankan atau membaptiskan anak.',
            ],
            [
                'name' => 'Cuti Istri Melahirkan/Keguguran',
                'max_days' => 2,
                'description' => 'Cuti alasan penting: Istri melahirkan atau mengalami keguguran kandungan.',
            ],
            [
                'name' => 'Cuti Keluarga Meninggal',
                'max_days' => 2,
                'description' => 'Cuti alasan penting: Suami/istri, orang tua/mertua, atau anak/menantu meninggal dunia.',
            ],
            [
                'name' => 'Cuti Anggota Keluarga Serumah Meninggal',
                'max_days' => 1,
                'description' => 'Cuti alasan penting: Anggota keluarga dalam satu rumah meninggal dunia.',
            ],
        ];

        foreach ($leaveTypes as $type) {
            LeaveType::firstOrCreate(
                ['name' => $type['name']],
                $type
            );
        }
    }
}
