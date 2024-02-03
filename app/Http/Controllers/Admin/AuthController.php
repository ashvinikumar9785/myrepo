<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Http\Controllers\Controller;
use Auth;
use Hash;
use Session;
use Validator;
use URL;
use DB;
use Str;
use App\Lib\Email;
class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    { 

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function login(Request $request)
    {
        if($request->ajax() && $request->isMethod('post')){
            try {
                $validator = Validator::make($request->all(), [
                    'email'     =>      'required|email|exists:admins,email',
                    'password'  =>      'required'
                ]);
                if($validator->fails()){
                    return response()->json(array('errors' => $validator->messages()), 422);
                }else{ 
                    $admin = Admin::where('email',$request->get('email'))->first();
                    if($admin){
                        if(Hash::check($request->get('password'),$admin->password)){
                            if(Auth::guard('admin')->attempt(['email'=>$request->get('email'),'password'=>$request->get('password')],true)){
                                $redirect = redirect()->intended(URL::route('admin.home'));
                                return['status' => 'true', 'message' => __("Signin sucessfully."),'url'=>$redirect->getTargetUrl()];
                            }else{
                                return['status' => 'false', 'message' => __("Something went wrong, please try again.")];
                            } 
                        }else{  
                            return response()->json(['status' => 'false', 'message' => __("Incorrect Password.")]);
                        }
                    }else{
                        return ['status'=>'false','message'=>__('The selected email is invalid.')];
                    }
                }
            } catch (\Exception $e) {
                return ['status'=>'false','message'=>$e->getMessage()];
            }
        }
        $title = __('Admin Login');
        return view('admin.auth.login',compact('title'));
    }

    public function logout(){
        Auth::guard('admin')->logout();
        Session::flush();
        return redirect(route('admin.login'));
    }

    public function forgotPassword(Request $request){
        if($request->ajax() && $request->isMethod('post')){
            try {
                $validator = Validator::make($request->all(), [
                    'email'     =>      'required|email|exists:admins,email',
                ]);
                if($validator->fails()){
                    return response()->json(array('errors' => $validator->messages()), 422);
                }else{ 
                    $admin = Admin::where('email',$request->get('email'))->first();
                    if($admin){
                        $token = Str::random(60);
                        $check = DB::table('admin_password_resets')->where('email',$request->get('email'))->first();
                        if($check){
                            DB::table('admin_password_resets')->where('email',$request->get('email'))->update(['token'=>$token,'created_at'=>date("Y-m-d H:i:s")]);
                        }else{
                            DB::table('admin_password_resets')->insert(['email'=>$request->get('email'),'token'=>$token,'created_at'=>date("Y-m-d H:i:s")]);
                        }
                        $mail_data['link'] = '<a class="btn-mail" href="'.route('admin.resetpassword',$token).'">Reset Password</a>'; 
                        $mail_data['url'] = route('admin.resetpassword',$token);
                        Email::send('reset-password',$mail_data,$request->get('email'),'Reset Password Notification');
                        return ['status'=>'true','message'=>__('We have emailed your password reset link!')];
                    }else{
                        return ['status'=>'false','message'=>__('The selected email is invalid.')];
                    }
                }
            } catch (\Exception $e) {
                return ['status'=>'false','message'=>$e->getMessage()];
            }
        }
    }

    public function resetPassword(Request $request,$token){
        if($request->ajax() && $request->isMethod('post')){
            try {
                $validator = Validator::make($request->all(), [
                    'password'     =>      'required|min:8|max:45|required_with:password_confirmation|confirmed',
                    'password_confirmation' =>  'required'
                ]);
                if($validator->fails()){
                    return response()->json(array('errors' => $validator->messages()), 422);
                }else{ 
                    $token_data = DB::table('admin_password_resets')->where('token',$token)->first(); 
                    if($token_data){
                        $to = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $token_data->created_at);
                        $from = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s")); 
                        $diff_in_minutes = $to->diffInMinutes($from);
                        if($diff_in_minutes<=60){
                            $new_password = Hash::make(trim($request->get('password')));
                            Admin::where('email',$token_data->email)->update(['password'=>$new_password]);
                            DB::table('admin_password_resets')->where('token',$token)->delete();
                            $message = 'Your password has been changed successfully.';
                            Session::flash('success',$message);
                            return ['status'=>'true','message'=>$message,'url'=>route('admin.login')];
                        }else{
                            return ['status'=>'false','message'=>"You password reset token has expired."];
                        }
                    }else{
                        return ['status'=>'false','message'=>"You password reset token has expired."];
                    }
                }
            } catch (\Exception $e) {
                return ['status'=>'false','message'=>$e->getMessage()];
            }
        }
        $token_data = DB::table('admin_password_resets')->where('token',$token)->first(); 
        if($token_data){
            $to = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $token_data->created_at);
            $from = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s")); 
            $diff_in_minutes = $to->diffInMinutes($from);
            if($token_data && $diff_in_minutes<=60){  
                $title = "Reset Password";
                return view('admin.auth.reset_password',compact('title','token'));
            }else{
                $message = "Your reset password link has expired or already been used.";
                Session::flash('danger',$message);
                return redirect(route('admin.login'));
            }
        }else{
            $message = "Your reset password link has expired or already been used.";
            Session::flash('danger',$message);
            return redirect(route('admin.login'));
        }
    }


}
