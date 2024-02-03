<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'title','slug','content','meta_title','meta_description','status','society_id'
    ];
}
