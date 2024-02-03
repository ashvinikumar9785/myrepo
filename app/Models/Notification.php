<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{  
    protected $fillable = [
        'user_id','notification_type','message','request_id','data','is_seen','event_id','other_user_id'
    ]; 

    public function eventby(){
       return $this->hasOne('App\Models\User','id','other_user_id');
    }
}
