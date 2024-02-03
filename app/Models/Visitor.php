<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    public $timestamps = false;
     public $table = 'visitors';

    protected $fillable = [
        'name','user_id','mobile_number','image','document'
    ];
}
