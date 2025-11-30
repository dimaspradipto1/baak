<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jenissk extends Model
{
    protected $guarded = [];

    public function sk()
    {
        return $this->hasMany(Sk::class);
    }
}
