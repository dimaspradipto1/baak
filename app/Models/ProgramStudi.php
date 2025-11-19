<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    protected $guarded = [];

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class); // Jika satu program studi memiliki banyak mahasiswa
    }

    public function suratAktif()
    {
        return $this->hasMany(SuratAktif::class);
    }
}
