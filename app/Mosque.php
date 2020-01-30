<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mosque extends Model
{
    protected $fillable = [
        'city_id', 'user_id', 'name', 'imam', 'address', 'fajr', 'eq_fajr',
        'zohr', 'eq_zohr', 'asr', 'eq_asr', 'maghrib', 'eq_maghrib', 'isha', 'eq_isha', 'favorite_cont'
    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function responsible()
    {
        return $this->hasMany(Responsible::class, 'mosque_id', 'id');
    }
}
