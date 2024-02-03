<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
     public $table = 'vehicles';
    public $timestamps = false;
    // public $table = 'news';

    protected $fillable = [
        'user_id','vehicle_number','type','image'
    ];





   
}
