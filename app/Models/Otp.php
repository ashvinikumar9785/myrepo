<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    public $timestamps = false;
     public $table = 'otps';

    protected $fillable = [
        'email','type','otp'
    ];
}
