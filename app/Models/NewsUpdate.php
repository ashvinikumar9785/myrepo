<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsUpdate extends Model
{
    public $timestamps = false;
    // public $table = 'news';
     // protected $appends = ['image'];

    protected $fillable = [
        'created_by','title','description','status','image'
    ];


    // public function getImageAttribute(){
    //     $image = 'public/uploads/cvs-image/logo.png';
    //     // if(isset($this->image) && $this->image != 'null'){
    //     //     $image  = (@$this->image)?$this->image:'';
    //     // }
    //     // return $image;
    // }
}
