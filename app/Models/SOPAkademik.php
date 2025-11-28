<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SOPAkademik extends Model
{
    protected $fillable = [
        'nama_sop',
        'file',
        'users_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
