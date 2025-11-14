<?php

namespace Database\Seeders;

use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProgramStudiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programStudis = [
            [
                'nama_program_studi' => 'S1-KESEHATAN DAN KESEHATAN KERJA',
            ],
            [
                'nama_program_studi' => 'S1-KESEHATAN LINGKUNGAN',
            ],
            [
                'nama_program_studi' => 'S1-AKUNTANSI',
            ],
            [
                'nama_program_studi' => 'S1-MANAJEMEN',
            ],
            [
                'nama_program_studi' => 'S1-SISTEM INFORMASI',
            ],
            [
                'nama_program_studi' => 'S1-TEKNIK INDUSTRI',
            ],
            [
                'nama_program_studi' => 'S1-TEKNIK INFORMATIKA',
            ],
            [
                'nama_program_studi' => 'S1-TEKNIK LOGISTIK',
            ],
            [
                'nama_program_studi' => 'S1-TEKNIK PERKAPALAN',
            ],
            [
                'nama_program_studi' => 'S2-MAGISTER KESEHATAN MASYARAKAT',
            ],
            [
                'nama_program_studi' => 'S2-MANAJEMEN',
            ]
        ];

        foreach ($programStudis as $programStudi) {
            ProgramStudi::create($programStudi);
        }
    }

}
