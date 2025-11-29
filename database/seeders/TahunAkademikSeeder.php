<?php

namespace Database\Seeders;

use App\Models\TahunAkademik;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TahunAkademikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahunAkademik =[
            [
                'tahun_akademik' => '2025/2026 Genap',
            ],
            [
                'tahun_akademik' => '2025/2026 Gasal',
            ],
            [
                'tahun_akademik' => '2024/2025 Genap',
            ],
            [
                'tahun_akademik' => '2024/2025 Gasal',
            ],
        ];

        foreach ($tahunAkademik as $tahunAkademik) {
            TahunAkademik::create($tahunAkademik);
        }
    }
}
