<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'phone', 'password', 'fajr_id', 'zohr_id', 'asr_id', 'maghrib_id', 'isha_id', 'api_token',
    ];

    protected $hidden = [
        'password', 'remember_token', 'api_token',
    ];

    public function favorite()
    {
        return $this->hasMany(Favorite::class, 'user_id', 'id');
    }

    public function responsible()
    {
        return $this->hasMany(Responsible::class, 'user_id', 'id');
    }
}
