<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventImage extends Model
{
    public $timestamps = false;
    // public $table = 'news';
     public $table = 'event_images';

    protected $fillable = [
        'event_id','image'
    ];
}
