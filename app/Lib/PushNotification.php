<?php
namespace App\Lib;
use App\Models\UserDevices;

class PushNotification  {

    public $live = false;
    

    public static  function sendFcmNotify($user_devices, $message, $dictionary = '', $type = '' , $sound = '')
    {


        // return  $type;
        $url = 'https://fcm.googleapis.com/fcm/send';
        // $server_key = 'AIzaSyCjokZIacW0pZS1zd2LCeTGAY6k65Tn0JY';
        $server_key = 'AAAAWUsKk0I:APA91bG7iXJ-Riqr-3xW47fMWWKj_i0iINkZ61IeD0Bhx9AkttKotCioxyCLGzuM8hSO6ZseSpWJ0w2KcKAHSZzmzMC6PYZGAqW-lzbMO-pLncQNBYFQmqiIv3GZhJMJOAkCdGJNNFG0';

        $ttl = 86400;
        $randomNum = rand(10, 100);
        $fields = array
        (
            'priority'             => "high",
            'data'         => array( "title"=>"CVS", "body" =>$message,'sound' => 'default','type'=>$type,'dictionary' => $dictionary),
            'notification'         => array( "title"=>"CVS","message"=>$message, "body" =>$message,'sound' => 'default','type'=>$type,'dictionary' => $dictionary),
        ); 
        if(count($user_devices)>1)
        {
                $fields['registration_ids'] = $user_devices;
        }
        else
        {
                $fields['to'] = $user_devices[0];
        }
     
        $headers = array(
                        'Content-Type:application/json',
                        'Authorization:key='.$server_key
                    );
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        // dd($result);
        curl_close($ch);
        /*if ($result === FALSE) {
           die('Problem occurred: ' . curl_error($ch));
        }*/
    }
    
    
   public static function Notify($users,$message,$type="",$dic=[],$sound=""){
       $UserDevicesIOS      =[];
       $UserDevicesAndroid  =[];
       foreach($users as $userid){
          $deviceinfo= UserDevices::where([["user_id",'=',$userid],['device_token','!=','SIMULATOR']])->get()->toArray();
         
         //   $deviceinfo= UserDevices::getUser("user_id='$userid' and device_token!=''","device_token");
                foreach($deviceinfo as $key => $deviceToken){
                    if($deviceToken["device_type"]=="IOS" || $deviceToken["device_type"]=="ios")
                        $UserDevicesAndroid[]=$deviceToken["device_token"];
                    if($deviceToken["device_type"]=="ANDROID" || $deviceToken["device_type"]=="android")
                        $UserDevicesAndroid[]=$deviceToken["device_token"];
                }
        }

        // dd($UserDevicesAndroid);

         PushNotification::sendFcmNotify($UserDevicesAndroid, $message,$dic,$type,$sound);
    }
   
}

?>