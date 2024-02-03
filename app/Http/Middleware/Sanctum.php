<?php

namespace App\Http\Middleware;

use Closure;

class Sanctum
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {   
            $user = $request->user();
            if(!isset($user->status))
            {
                return response()->json(['status'=>'false','user_status'=>'false','message'=>'Your account is deleted contact to administrator.']); 
            }

            if($user->status==0){
                return response()->json(['status'=>'false','user_status'=>'false','message'=>'Your account is inactivated contact to administrator.']);    
            } 
        } catch (\Exception $e) {
            return response()->json(['status'=>'false','error'=>$e->getMessage(),'user_status'=>'false',]);
        }
        return $next($request);
    }
}
