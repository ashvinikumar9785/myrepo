<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocietyCharges extends Model
{
    public $timestamps = false;
     public $table = 'society_charges';

    protected $fillable = [
        'society_id','title','description','amount','image','status'
    ];
}
