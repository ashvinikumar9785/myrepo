<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
class VerifyJWTToken
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
        try{
            $token = JWTAuth::getToken(); 
            if($token){
                //$user = JWTAuth::toUser($request->input('token'));
                $user = JWTAuth::toUser($token);
                if(!isset($user->status))
                {
                    return response()->json(['status'=>false,'deactive'=>'true','message'=>'Your account has been deleted. Please contact the administrator'],401); 
                }

                if($user->status==0){
                    return response()->json(['status'=>false,'deactive'=>'true','message'=>'Your account has been marked as Inactive. Please contact the administrator.'],401);    
                }
            }else{
                return response()->json(['status'=>false,'message'=>'Token is required.'],401); 
            }
            
        }catch (\Exception $e) {
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['status'=>false,'message'=>'token_expired'],401);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['status'=>false,'message'=>'token_invalid'],401);
            }else{
                return response()->json(['status'=>false,'message'=>'Token is required'],401);
            }
        }
       return $next($request);
    }
}
