<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackImage extends Model
{
    public $timestamps = false;
    // public $table = 'news';
     public $table = 'feedback_images';

    protected $fillable = [
        'feedback_id','image'
    ];
}
