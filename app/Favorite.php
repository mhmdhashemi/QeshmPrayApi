<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'user_id', 'mosque_id',
    ];
}
