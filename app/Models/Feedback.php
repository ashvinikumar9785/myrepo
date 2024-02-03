<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
     protected $appends = ['image'];
     public $table = 'feedbacks';
    public $timestamps = false;
    // public $table = 'news';

    protected $fillable = [
        'user_id','society_id','status','title','description','status'
    ];



    public function getImageAttribute(){
        return FeedbackImage::where('feedback_id',$this->id)->get();
    }


    public function user(){
       return $this->belongsTo('App\Models\User','user_id');
    } 
}
