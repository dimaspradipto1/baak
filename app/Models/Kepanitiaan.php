<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kepanitiaan extends Model
{
    protected $guarded = [];

    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
