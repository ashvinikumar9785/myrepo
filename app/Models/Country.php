<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{

    protected $fillable = [
        'name', 'iso_code_2', 'status',
    ];
    public $timestamps = false;
    
    protected $table = "countries";

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */    
}
