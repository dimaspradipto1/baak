<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratAktif extends Model
{
    protected $guarded = [];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'program_studi_id');
    }

    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik_id');  // Pastikan nama kolom 'tahun_akademik_id' sesuai dengan kolom di database
    }
}

