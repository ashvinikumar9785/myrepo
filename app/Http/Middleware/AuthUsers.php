<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class AuthUsers
{
    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request $request
    * @param  \Closure $next
    * @return mixed
    */
    public function handle($request, Closure $next)
    {
    	if (false == Auth::check()) {
            return redirect(url('/society/login')); //redirect User to login page
        }

        try {

        	$userId = Auth::id();
        	$user = User::where('id',$userId)->first();
            if(empty($user))
            {
            	Auth::guard('web')->logout();
		        Session::flush();
		        Session::flash('danger','Your account is deleted contact to administrator.');
		        return redirect(url('/society/login'));
            }

            if($user->status==0){
                Auth::guard('web')->logout();
		        Session::flush();
		        Session::flash('danger','Your account is inactive, please contact to administrator.');
		        return redirect(url('/society/login'));   
            }

        } catch (\Exception $e) {
            return response()->json(['status'=>'false','error'=>$e->getMessage(),'user_status'=>'false',]);
        }

        return $next($request);

    }
}