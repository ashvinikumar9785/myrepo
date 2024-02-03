<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Notification;
use App\Models\User;
use App\Lib\PushNotification;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function validationHandle($validation)
    {
        foreach ($validation->getMessages() as $field_name => $messages){
            if(!isset($firstError)){
                $firstError = $messages[0];
            } 
        }
        return $firstError;
    }
/**
 * @OA\Get(
 *     path="/projects",
 *     @OA\Response(response="200", description="Display a listing of projects.")
 * )
 */
    public function sentNotification($user_id,$type, $message,$event_id,$data=[],$other_user_id=null){
        try {
            //Create notification
            Notification::create([
                'user_id' => $user_id,
                'notification_type' => $type,
                'message' => $message, 
                // 'event_id' => $event_id, 
                'is_seen' => '0',
                'data' => json_encode($data),
                // 'data' => $data,
                // 'other_user_id'=>$other_user_id
            ]);
             $checkNotificion = User::where('id',$user_id)->first();
            if($checkNotificion && $checkNotificion->notification == 1){
                 PushNotification::Notify([$user_id], $message, $data, $type);
            }
            
        } catch (\Exception $e) {
            
        }
    }

    
}
