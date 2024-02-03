<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Hash;
use Validator;
class HomeController extends Controller
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
    public function index()
    {
        return view('home');
    }

    public function verify(Request $request,$token){
        $title = "Verify Email Address";
        $data = User::where('token',$token)->first();
        if($data){
            $data->update(['status'=>1,'token'=>'']);
            $message = "Thank you for joining, your email has been verified successfully, you can now login to your account.";
            return view('admin.auth.message',compact('title','message'));
        }else{
            $message = "You verification link has expired or already been used.";
            return view('admin.auth.message',compact('title','message'));
        }
    }

    public function resetPassword(Request $request, $token){
        if($request->ajax() && $request->isMethod('post')){
            try {
                $validator = Validator::make($request->all(), [
                    'password'     =>      'required|min:8|max:45|required_with:password_confirmation|confirmed',
                    'password_confirmation' =>  'required'
                ]);
                if($validator->fails()){
                    return response()->json(array('errors' => $validator->messages()), 422);
                }else{ 
                    $token_data = DB::table('password_resets')->where('token',$token)->first();
                    if($token_data){ 
                        $to = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $token_data->created_at);
                        $from = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s")); 
                        $diff_in_minutes = $to->diffInMinutes($from);
                        if($diff_in_minutes<=60){
                            $new_password = Hash::make(trim($request->get('password')));
                            User::where('email',$token_data->email)->update(['password'=>$new_password]);
                            DB::table('password_resets')->where('token',$token)->delete();
                            $message = 'Your password has been changed successfully.'; 
                            return ['status'=>'true','message'=>$message];
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
        $token_data = DB::table('password_resets')->where('token',$token)->first();
        $title = "Reset Password";
        if($token_data){ 
            $to = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $token_data->created_at);
            $from = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s")); 
            $diff_in_minutes = $to->diffInMinutes($from); 
            if($token_data && $diff_in_minutes<=60){  
                return view('admin.auth.user_reset_password',compact('title','token'));
            }else{
                $message = "Your reset password link has expired or already been used."; 
                return view('admin.auth.message',compact('title','message'));
            }
        }else{
            $message = "Your reset password link has expired or already been used."; 
            return view('admin.auth.message',compact('title','message'));
        }
    }
}
