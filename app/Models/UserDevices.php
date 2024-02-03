<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UserDevices extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','device_token', 'device_type'
    ];
    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    public static function deviceHandle($data){
        $userDevice = UserDevices::where(['device_type'=> $data['device_type'],'device_token'=>$data['device_token']])->first();
        if($userDevice){
            UserDevices::where(['device_type'=> $data['device_type'],'device_token'=>$data['device_token']])->delete();
            self::createDevice($data);
        }else{
            self::createDevice($data);
        }
        return true;
    } 
    
    public static function createDevice($data){		
        $device = UserDevices::create([
            "user_id"       =>  $data['id'],
            "device_type"   =>  $data['device_type'],
            "device_token"  =>  $data['device_token'],
        ]);                
    }
    
}
