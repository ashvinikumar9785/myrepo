<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    public $timestamps = false;
     public $table = 'banners';

    protected $fillable = [
        'society_id','banner','status'
    ];
}
