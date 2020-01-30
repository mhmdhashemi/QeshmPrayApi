<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public $timestamps = false;

    public function mosque()
    {
        return $this->hasMany(Mosque::class, 'mosque_id', 'id');
    }
}
