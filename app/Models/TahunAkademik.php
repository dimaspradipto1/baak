<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SuratAktif;

class TahunAkademik extends Model
{
    protected $guarded = [];

    public function suratAktif()
    {
        return $this->hasMany(SuratAktif::class, 'tahun_akademik_id');  // Pastikan 'tahun_akademik_id' adalah nama kolom yang menghubungkan dengan tabel surat_aktif
    }
}

