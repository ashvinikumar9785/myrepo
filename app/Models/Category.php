<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{  
	use SoftDeletes;

    protected $fillable = [
        'title','slug','image','status','description','thumb'
    ];

    public function rating(){
    	return $this->hasOne("App\Models\CategoryRating",'category_id');
    }
 

}
