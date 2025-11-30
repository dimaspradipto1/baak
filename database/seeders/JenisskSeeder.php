<?php

namespace Database\Seeders;

use App\Models\Jenissk;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JenisskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenissks = [
            [
                'nama_jenis_sk' => 'SK Penerimaan Mahasiswa Baru (PMB)',
            ],
            [
                'nama_jenis_sk' => 'SK PANITA KONVERSI',
            ],
            [
                'nama_jenis_sk' => 'SK Semester Pendek (SP)',
            ],
            [
                'nama_jenis_sk' => 'SK Kartu Rencana Studi (KRS)',
            ],
            [
                'nama_jenis_sk' => 'SK Kuliah Pengabdian Masyarakat (KPM)',
            ],
            [
                'nama_jenis_sk' => 'SK ESQ',
            ],
            [
                'nama_jenis_sk' => 'SK Yudisium',
            ],
            [
                'nama_jenis_sk' => 'SK Wisuda',
            ],
            [
                'nama_jenis_sk' => 'SK SEMINAR PROPOSAL DAN SIDANG AKHIR',
            ],
            [
                'nama_jenis_sk' => 'SK TOEFL',
            ],
            [
                'nama_jenis_sk' => 'SK BKD',
            ],
            [
                'nama_jenis_sk' => 'SK Ujian Tengah Semester (UTS)',
            ],
            [
                'nama_jenis_sk' => 'SK Ujian Akhir Semester (UAS)',
            ],
            [
                'nama_jenis_sk' => 'SK dan lainnya',
            ],
            [
                'nama_jenis_sk' => 'SK Reviewer',
            ]
        ];

        foreach ($jenissks as $jenissk) {
            Jenissk::create($jenissk);
        }
    }
}
