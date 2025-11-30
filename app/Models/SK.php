<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SK extends Model
{
    protected $guarded = [];

    public function jenissk()
    {
        return $this->belongsTo(Jenissk::class, 'jenissk_id');
    }

    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
