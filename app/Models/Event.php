<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
     protected $appends = ['image'];
     public $table = 'events';
    public $timestamps = false;
    // public $table = 'news';

    protected $fillable = [
        'society_id','status','title','description','status','date','time','type','drive_link','tithi','address','banner'
    ];



    public function getImageAttribute(){
        return EventImage::where('event_id',$this->id)->get();
    }


    public function user(){
       return $this->belongsTo('App\Models\User','society_id');
    } 
}
