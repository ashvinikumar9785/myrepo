<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Country;
use App\Models\UserDevices;
use App\Models\User; 
use App\Models\Page;
use App\Models\Category;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\Event;
use App\Models\EventImage;
use App\Models\FamilyMember;
use App\Models\Otp;
use App\Models\Vehicle;
use App\Models\Banner;
use App\Models\AppSetting;
use App\Models\SocietyCharges;
use App\Models\Transaction;

use App\Models\Visitor;
use App\Lib\Uploader;
use App\Lib\Email;
use DB;
use Hash;
use Validator;
use Str;
use Avatar;
use Image;
use JWTAuth;
use App\Models\NewsUpdate;
use App\Models\Feedback;
use App\Models\FeedbackImage;
class UserController extends Controller
{
    
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    // This method use for signup
    protected function signup(Request $request){
        try { 
            $data = $request->all();
            $validator = Validator::make($data, [
                'name'              => 'required|max:45', 
                'email'             => 'required|unique:users|email', 
                'password'          => 'required|min:8|max:45', 
                'device_type'       => 'required|in:IOS,ANDROID',
                'device_token'      => 'required', 
            ]);
            if ($validator->fails()){
                $error = $this->validationHandle($validator->messages());
                return response()->json(['status' => false, 'message' => $error],403); 
            }else{
                $token = Str::random(45);
                $formData = $request->except('password');
                $formData['password'] = Hash::make($request->get('password'));                
                $formData['token'] = $token;
                $formData['status'] = 2; 
                $formData['user_type'] = 'App'; 
                $profile_picture = "public/uploads/profile/".time().".jpg";
                $thumb = "public/uploads/profile/thumb/".time().".jpg";
                Avatar::create(trim($request->get('name')))->save($profile_picture);
                Avatar::create(trim($request->get('name')))->save($thumb);
                $formData['profile_picture'] = $profile_picture;
                $formData['thumb'] = $thumb;
                $user = User::create($formData);  
                $user = User::find($user->id);
                // Send Email   
                $email_data['link']         = '<a class="btn-mail" href="'.route('users.verify',$token).'">Verify Email Address</a>';
                $email_data['url']          = route('users.verify',$token);
                Email::send('email-verification',$email_data,$user->email,"Verify Email Address"); 
                if($user){
                    UserDevices::deviceHandle([
                        "id"            =>  $user->id,
                        "device_type"   =>  $data['device_type'],
                        "device_token"  =>  $data['device_token'],
                    ]);
                }             
                $message = "Your account has been created successfully, Please verify your email address.";
                return response()->json(['status' => true, 'message' => $message,'data'=>[]]);          
            }  
        } catch (\Exception $e) { 
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]]); 
        }                     
    }

    // This method use for checkUser
    protected function checkUser(Request $request){
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'social_id'         => 'required',
                'device_type'       => 'required|in:IOS,ANDROID',
                'device_token'      => 'required',
                'social_type'       => 'required|in:FACEBOOK,GOOGLE',
                'name'              => 'required',
                'email'             => 'required|email'   
            ]);              
            if ($validator->fails()){
                $error = $this->validationHandle($validator->messages()); 
                return response()->json(['status' => false, 'message' => $error],403);  
            }else{ 
                if(isset($data['email']) && !empty($data['email'])){
                    //User::where('email','=',$data['email'])->update(['social_id'=>$data['social_id']]);
                    $user = User::where('email','=',$data['email'])->first();
                }else{
                    $user = User::where('social_id','=',$data['social_id'])->first();
                }
                if($user){
                    UserDevices::deviceHandle([
                        "id"       =>  $user->id,
                        "device_type"   =>  $data['device_type'],
                        "device_token"  =>  $data['device_token'],
                    ]);
                    $security_token = $user->createToken($request->device_type)->plainTextToken;    
                    return response()->json(['status' => true, 'message' => 'User Details','data'=>$user,'security_token'=>$security_token]);
                }else{
                    $formData = [
                        'social_id'         =>  $data['social_id'],
                        'social_type'       =>  $data['social_type'],
                        'user_type'         =>  "Social"  
                    ];
                    if($request->has('email') && $request->get('email') != ''){
                        $formData['email'] = $request->get('email');
                    }
                    if($request->has('name') && $request->get('name') != ''){
                        $formData['name'] = $request->get('name');
                    }
                    if($request->has('mobile') && $request->get('mobile') != ''){
                        $formData['mobile'] = $request->get('mobile');
                    }
                    $profile_picture = "public/uploads/profile/".time().".jpg";
                    $thumb = "public/uploads/profile/thumb/".time().".jpg";
                    if($request->has('profile_picture')){
                        Image::make($request->get('profile_picture'))->save($profile_picture,70);
                        Image::make($request->get('profile_picture'))->heighten(200)->save($thumb,70);
                        $formData['profile_picture'] = $profile_picture;
                        $formData['thumb'] = $thumb;
                    }else{ 
                        Avatar::create(trim($request->get('name')))->save($profile_picture);
                        Avatar::create(trim($request->get('name')))->save($thumb);
                        $formData['profile_picture'] = $profile_picture;
                        $formData['thumb'] = $thumb;
                    }
                    $formData['status'] = 1;
                    $user = User::create($formData);  
                    $user = User::find($user->id);
                    if($user){
                        UserDevices::deviceHandle([
                            "id"            =>  $user->id,
                            "device_type"   =>  $data['device_type'],
                            "device_token"  =>  $data['device_token'],
                        ]);
                    }   
                    $security_token = $user->createToken($request->device_type)->plainTextToken;  
                    return response()->json(['status' => true, 'message' => 'User Details','data'=>$user,'security_token'=>$security_token]);
                }
            }
        } catch (\Exception $e) { 
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]]); 
        }         
    }

    // This method use for signin
    protected function signin(Request $request){
        try {
           
             $data = $request->all();
            $validator = Validator::make($data, [
                'mobile_number'         => 'required',
                'password'      => 'required',
                // 'device_type'   => 'required|in:IOS,ANDROID',
                // 'device_token'  => 'required',
            ]);
            if ($validator->fails()) {
                $error = $this->validationHandle($validator->messages()); 
                return response()->json(['status' => false, 'message' => $error],403); 
            } else {            
                $user = User::where('role',1);
                    if(isset($request->type) && $request->type == 'email'){
                        $user= $user->where('email',$data['email']);

                    }
                    else{
                        $user= $user->where('mobile_number',$data['mobile_number']);
                    }
                $user= $user->first();
                if(!$user){ 
                    return response()->json(['status' => false, 'message' => 'User not exist.','data'=>[]],404);
                }else{
                    if(Hash::check($data['password'],$user->password)){
                        if($user->status==2){ 
                            return response()->json(['status' => 'verification', 'message' => 'Your email is not verified, please verify your email address.','data'=>[]],401);
                        }else if ($user->status==0){ 
                            return response()->json(['status' => false, 'message' => 'Your account is inactive, please contact to administrator.','data'=>[]],401);
                        }else{ 
                            // UserDevices::deviceHandle([
                            //     "id"       =>  $user->id,
                            //     "device_type"   =>  $data['device_type'],
                            //     "device_token"  =>  $data['device_token'],
                            // ]);
                            $jwtResponse = User::authorizeToken($user);   

                            $app_settings = AppSetting::where('id',1)->first();
                            $user->app_settings= $app_settings;
                            $user->app_settings->name= @$user->society_detail->name;
                             
                             if($app_settings->force_update == 1){
                                $user->app_settings->message = $app_settings->force_update_message;
                            }    
                            if($app_settings->is_maintenance == 1){
                                $user->app_settings->message = $app_settings->is_maintenance_message;
                            } 
                            return response()->json(['status' => true, 'message' => 'Signin successfully.','data'=>$user,'token'=>@$jwtResponse['token']]);
                            // $security_token = $user->createToken($request->device_type)->plainTextToken; 
                            // return response()->json(['status' => true, 'message' => 'Signin sucessfully.','data'=>$user,'security_token'=>$security_token]);
                        }
                    }else{
                        return response()->json(['status' => false, 'message' => 'Incorrect Password.','data'=>[]],404);
                    }
                }
            }
        }catch (\Exception $e) { 
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]]); 
        }  
    }

    // This method use for get profile
    public function getProfile(Request $request){  
        try{
            $userId = $request->user()->id;
            $profile = User::getProfile($userId);
            if(count($profile)>0){
                return response()->json(['status' => true, 'message' => 'User Profile','data'=>$profile]);
            }else{
                return response()->json(['status' => false, 'message' => 'User Not Found','data'=>[]]);   
            } 
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]]);         
        }  
    }

    // This method use for update profile
    public function updateProfile(Request $request){ 
        try {  
            $data = $request->all();
            $userId = $request->user()->id; 
            $validator = Validator::make($request->all(), [
                'name'              => 'required|max:45', 
                'mobile_number'     => 'required|unique:users,mobile_number,'.$userId,
                'email'             => 'required|email|unique:users,email,'.$userId,  
                'gender'=>'nullable',
                'date_of_birth'=>'nullable',
                'occupation'=>'nullable',
                'anniversary_date'=>'nullable',
                'blood_group'=>'nullable',
                'pin_code'=>'nullable',
                'address'=>'nullable'

            ]);
            if ($validator->fails()){
                $error = $this->validationHandle($validator->messages()); 
                return response()->json(['status' => false, 'message' => $error],403);  
            }else{ 
                $user = User::find($userId);
                $user->name = $data['name'];   
                $user->mobile_number = $data['mobile_number']; 
                $user->email = $data['email'];   
                 if($request->date_of_birth && $request->date_of_birth != 'null'){
                    $user->dob = $data['date_of_birth'];   
                }
                if($request->blood_group){
                    $user->blood_group = $data['blood_group'];   
                }
                if($request->anniversary_date && $request->anniversary_date != 'null' ){
                    $user->anniversary_date = $data['anniversary_date'];   
                }
                if($request->address){
                    $user->address = $data['address'];   
                }
                 if($request->pin_code){
                    $user->pin_code = $data['pin_code'];   
                }
                 if($request->gender){
                    $user->gender = $data['gender'];   
                }
                $user->occupation = @$data['occupation'];   

                if($request->file('profile_picture')!==null){
                    $destinationPath = '/uploads/profile/';
                    $responseData = Uploader::doUpload($request->file('profile_picture'),$destinationPath,true);    
                    if($responseData['status']=="true"){ 
                        $user->profile_picture = $responseData['file']; 
                        $user->thumb = $responseData['thumb']; 
                    }                             
                }                          
                if($user->save()){         
                    $data = User::getProfile($user->id);  
                    return response()->json(['status' => true, 'message' => 'Profile updated successfully.','data'=>$data]);
                }else{
                    $data = User::getProfile($user->id); 
                    return response()->json(['status' => false, 'message' => 'Unknown error accured while updating information.','data'=>$data]);
                }
            }
        }catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);         
        } 
    }

    // This method use for change password
    public function changePassword(Request $request){ 
        try {
            $data = $request->all();
            $validator = Validator::make($request->all(), [
                'current_password'          => 'required|min:8|max:45',
                'new_password'              => 'required|min:8|max:45',
                'confirm_password'          => 'required|same:new_password',
            ]);
            if ($validator->fails()){
                $error = $this->validationHandle($validator->messages()); 
                return response()->json(['status' => false, 'message' => $error],403); 
            }else{
                $userId = $request->user()->id; 
                $user = User::find($userId);
                if(!$user){
                    return response()->json(['status' => false, 'message' => 'User Not Found.','data'=>[]]);
                }else{
                    if(Hash::check($data['current_password'],$user->password)){
                        $user->password = bcrypt($data['new_password']);
                        $user->save();
                        return response()->json(['status' => true, 'message' => 'Password change successfully.','data'=>[]]);
                    }else{ 
                        return response()->json(['status' => false, 'message' => "Current Password doesn't match.",'data'=>[]],403);
                    }
                }
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);  
        }
    }

    // This method use for forgot password
    public function forgot(Request $request){
        try {
            $data = $request->all(); 
            $validator = Validator::make($data, ['email' => 'required|email']);
            if($validator->fails()){
                $error = $this->validationHandle($validator->messages()); 
                return response()->json(['status' => false, 'message' => $error],403); 
            }else{ 
                 if(isset($request->type) && $request->type =='EMAIL_REGISTER'){
                    $user = User::where('email',$data['email'])->where('role',1)->first();
                    if($user){
                        return response()->json(['status' => false, 'message' => 'Email alrady exist.'],404); 
                    }else{
                       
                            $otp = mt_rand(100000, 999999);
                            // $otp = 1234;
                            // $data['otp'] = $otp;
                            $data['email'] = $request->email;
                            Otp::where('email',$request->email)->delete();
                            Otp::create(['email'=>$request->email,'otp'=>$otp,'type'=>'Forgot Password']);
                       Email::send('email-verification',$email_data,$request->get('email'),'Reset Password Notification');     
                            return response()->json(['status' => true, 'message' => 'We have emailed an otp for password reset!','data'=>$data]); 
                        
                    }
                }
                $user = User::where('email',$data['email'])->where('role',1)->first();
                if(!$user){
                    return response()->json(['status' => false, 'message' => 'Email does not exist.'],404); 
                }else{
                    if($user->status==2){ 
                        return response()->json(['status' => false, 'message' => 'Your email is not verified.','data'=>[]],401);
                    }else if ($user->status==0){ 
                        return response()->json(['status' => false, 'message' => 'Your account is inactive, please contact to administrator.','data'=>[]],401);
                    }else{
                        $otp = mt_rand(1000, 9999);
                        // $otp = 1234;
                        // $data['otp'] = $otp;
                         $email_data['otp']         = $otp;
                       $data['email'] = $request->email;
                        Otp::where('email',$request->email)->delete();
                        Otp::create(['email'=>$request->email,'otp'=>$otp,'type'=>'Forgot Password']);
                         Email::send('reset-password',$email_data,$request->get('email'),'Reset Password Notification');    
                        return response()->json(['status' => true, 'message' => 'We have emailed an otp for password reset!','data'=>$data]); 
                    }
                }
            }
        }catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]]); 
        }
    }




      // This method use for forgot password
    public function resendOtp(Request $request){
        try {
            $data = $request->all(); 
            $validator = Validator::make($data, ['email' => 'required|email']);
            if($validator->fails()){
                $error = $this->validationHandle($validator->messages()); 
                return response()->json(['status' => false, 'message' => $error],403); 
            }else{ 
                $user = User::where('email',$data['email'])->where('role',1)->first();
                if(!$user){
                    return response()->json(['status' => false, 'message' => 'Email does not exist.'],404); 
                }else{
                    if($user->status==2){ 
                        return response()->json(['status' => false, 'message' => 'Your email is not verified.','data'=>[]],401);
                    }else if ($user->status==0){ 
                        return response()->json(['status' => false, 'message' => 'Your account is inactive, please contact to administrator.','data'=>[]],401);
                    }else{
                        $otp = mt_rand(100000, 999999);
                        $otp = 1234;
                        // $data['otp'] = $otp;
                        $data['email'] = $request->email;
                        Otp::where('email',$request->email)->delete();
                        Otp::create(['email'=>$request->email,'otp'=>$otp,'type'=>'Forgot Password']);
                    // Email::send('reset-password',$mail_data,$request->get('email'),'Reset Password Notification');    
                        return response()->json(['status' => true, 'message' => 'We have emailed an otp for password reset!','data'=>$data]); 
                    }
                }
            }
        }catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);  
        }
    }


    public function resetPassword(Request $request){ 
        try {
            $data = $request->all();
            $validator = Validator::make($request->all(), [
                'email'=>'required|email',
                'otp'=>'nullable',
                'new_password'              => 'required|min:8|max:45',
                'confirm_password'          => 'required|same:new_password',
            ]);
            if ($validator->fails()){
                $error = $this->validationHandle($validator->messages()); 
                return response()->json(['status' => false, 'message' => $error],403); 
            }else{
               $user = User::where('email',$data['email'])->first();
                if(!$user){
                    return response()->json(['status' => false, 'message' => 'Email does not exist.'],404); 
                }else{
                    if($user->status==2){ 
                        return response()->json(['status' => false, 'message' => 'Your email is not verified.','data'=>[]],401);
                    }else if ($user->status==0){ 
                        return response()->json(['status' => false, 'message' => 'Your account is inactive, please contact to administrator.','data'=>[]],401);
                    }else{
                        if($request->otp){
                                 $checkOtp = Otp::where('email',$request->email)->where('otp',$request->otp)->first();
                           if($checkOtp){
                                 $user->password = bcrypt($data['new_password']);
                                $user->save();
                                return response()->json(['status' => true, 'message' => 'Password change successfully.','data'=>[]]);
                           }
                           else{
                             return response()->json(['status' => false, 'message' => 'Invalid otp!'],401); 
                           }
                        }
                        else{
                            $user->password = bcrypt($data['new_password']);
                            $user->save();
                            return response()->json(['status' => true, 'message' => 'Password change successfully.','data'=>[]]);
                        }
                      
                        // Email::send('reset-password',$mail_data,$request->get('email'),'Reset Password Notification');    
                       
                    }
                }
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);  
        }
    }


     public function otpVerify(Request $request){ 
        try {
            $data = $request->all();
            $validator = Validator::make($request->all(), [
                'email'=>'required|email',
                'otp'=>'required',
            ]);
            if ($validator->fails()){
                $error = $this->validationHandle($validator->messages()); 
                return response()->json(['status' => false, 'message' => $error],403); 
            }else{
               $user = User::where('email',$data['email'])->first();
                if(!$user){
                    return response()->json(['status' => false, 'message' => 'Email does not exist.'],404); 
                }else{
                    if($user->status==2){ 
                        return response()->json(['status' => false, 'message' => 'Your email is not verified.','data'=>[]],401);
                    }else if ($user->status==0){ 
                        return response()->json(['status' => false, 'message' => 'Your account is inactive, please contact to administrator.','data'=>[]],401);
                    }else{
                       $checkOtp = Otp::where('email',$request->email)->where('otp',$request->otp)->first();
                       if($checkOtp){
                             
                            return response()->json(['status' => true, 'message' => 'Otp Verified.','data'=>$data]);
                       }
                       else{
                         return response()->json(['status' => false, 'message' => 'Invalid otp!'],401); 
                       }
                        // Email::send('reset-password',$mail_data,$request->get('email'),'Reset Password Notification');    
                       
                    }
                }
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);  
        }
    }


    // This method use for logout user.
    public function logout(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'device_type'       => 'required|in:ANDROID,IOS',
                'device_token'      => 'required',
            ]); 
            if ($validator->fails()){
                $error = $this->validationHandle($validator->messages()); 
                return response()->json(['status' => false, 'message' => $error],403); 
            }else{    
                 $userDevice = UserDevices::where(['device_type'=> $request['device_type'],'device_token'=>$request['device_token']])->first();
                $token = JWTAuth::getToken();
                if ($token) {
                    JWTAuth::setToken($token)->invalidate();
                }            
                if($userDevice){
                    UserDevices::where(['device_type'=> $request['device_type'],'device_token'=>$request['device_token']])->delete();
                }
                return response()->json(['status' => true, 'message' => 'Logout Successfully']);
            }  
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);  
        }     
    }

    // list of country
    public function getCountry(){
        try{
            $countries = []; 
            $countries = Country::all()->toArray();
            foreach($countries as $key => $country){
                $countries[$key]['id']  = (string) $country['id'];
                $countries[$key]['phonecode']  = (string) "+".$country['phonecode'];
            }
            return response()->json(['status' => true, 'message' => 'Country List Data','data'=>$countries]);
        }catch(\Exception $e){ 
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);  
        }
    }

    public function setNotificationStatus(Request $request){ 
        try { 
            $validator = Validator::make($request->all(), [
                'status'          =>       'required|in:0,1',
            ]);
            if ($validator->fails()){
                $error = $this->validationHandle($validator->messages()); 
                return response()->json(['status' => false, 'message' => $error],403); 
            }else{
                $userId = $request->user()->id; 
                $status = $request->get('status');
                $user = User::where('id',$userId)->update(['notification'=>$status]);
                if($user){
                    return response()->json(['status' => true, 'message' => 'Notification status updated successfully.','data'=>[]]);
                }else{
                    return response()->json(['status' => false, 'message' => 'Error while updatig status, please try again.','data'=>[]]);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);   
        }
    }

    public function getStaticPage(Request $request){ 
        try { 
            $validator = Validator::make($request->all(), [
                'slug'          =>       'required',
            ]);
            if ($validator->fails()){
                $error = $this->validationHandle($validator->messages()); 
                return response()->json(['status' => false, 'message' => $error],403); 
            }else{
                //$userId = JWTAuth::toUser(JWTAuth::getToken())->id; 
                $slug = $request->get('slug');
                $page = Page::where('slug',$slug)->select('id','title','content')->first();
                if($page){
                    return response()->json(['status' => true, 'message' => 'Static page data.','data'=>$page]);
                }else{
                    return response()->json(['status' => false, 'message' => 'Invalid slug passed.','data'=>[]]);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);  
        }
    }
 

    // method for get notification

    public function getNotifications(Request $request){ 
        try {               
            $userId = $request->user()->id;   
            Notification::where(['user_id'=>$userId,'is_seen'=>'0'])->update(['is_seen'=>'1']);
            $notifications = Notification::where(['user_id'=>$userId])->orderBy('id','desc');
            $notifications = $notifications->paginate(15)->toArray();
            return response()->json(['status' => true, 'message' => "Notification List",'data'=>$notifications]);    
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);  
        }
    }
 


    // method of page content
    public function getPageContent(Request $request){
        try {
           
            $data = $request->all();
            $validator = Validator::make($request->all(), [
               'slug'      => 'required|max:45',  
            ]);
            if ($validator->fails()){
                $error = $this->validationHandle($validator->messages()); 
                return response()->json(['status' => false, 'message' => $error],403);  
            }else{ 
                $page = Page::where('slug',$data['slug'])->first();
                if($page){
                 return response()->json(['status' => true, 'message' => "Page data",'data'=>$page]);                
                }
                else{
                    return response()->json(['status' => false, 'message' => "Page not found",'data'=>[]]); 
                }
            }

        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);  
        }
    } 
 

   public function getSettings(Request $request){ 
        try { 
            $settings = Setting::all()->pluck('value','field_name')->toArray();
            return response()->json(['status' => true, 'message' => 'Data.','data'=>$settings]);
              
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);  
        }
    }
 

   // method for resend verificatio email
    public function resendVerificationEmail(Request $request){
        try {
            $data = $request->all();
            $rules =[
                'email'      => 'required|email',
            ];
            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                $error = $this->validationHandle($validator->messages()); 
                return response()->json(['status' => false, 'message' => $error],403); 
            } else {
                $user = User::where('email',$data['email'])->first();
                if(!$user){ 
                    return response()->json(['status' => false, 'message' => 'Email does not exist.','data'=>[]]);
                }else{
                    if($user->status==1){ 
                        return response()->json(['status' => 'verification', 'message' => 'Your email has already verified.','data'=>[]]);
                    }else if ($user->status==0){ 
                        return response()->json(['status' => false, 'message' => 'Your account is inactive, please contact to administrator.','data'=>[]]);
                    }else{
                        $email_data['link']         = '<a class="btn-mail" href="'.route('users.verify',$user->token).'">Verify Email Address</a>';
                        $email_data['url']          = route('users.verify',$user->token);
                        Email::send('email-verification',$email_data,$user->email,"Verify Email Address");
                        $message = "Verification email sent successfully, Kindly check your email.";
                        return response()->json(['status' => true, 'message' => $message,'data'=>[]]);
                    }
                }
            }     
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);  
        }
    }
   


   // This method use for get profile
    public function getNewsUpdate(Request $request){  
        try{
            $created_by = $request->user()->created_by;
            $profile = NewsUpdate::where('created_by',$created_by)->orderBy('id','desc')->paginate(10);
           // if(count($profile)>0){
                return response()->json(['status' => true, 'message' => 'News update list','data'=>$profile]);
            // }else{
            //     return response()->json(['status' => false, 'message' => 'Data Not Found','data'=>[]]);   
            // } 
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);         
        }  
    }


    // This method use for get profile
    public function getNewsUpdateDetail(Request $request,$id){  
        try{
            $created_by = $request->user()->created_by;
            $profile = NewsUpdate::where('id',$id)->first();
            if($profile){
                return response()->json(['status' => true, 'message' => 'News update detail','data'=>$profile]);
            }else{
                return response()->json(['status' => false, 'message' => 'Data Not Found','data'=>[]]);   
            } 
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);         
        }  
    }

    public function submitFeedback(Request $request){ 
        try {  
            $data = $request->all();
            $userId = $request->user()->id; 
            $societyId = $request->user()->created_by; 
            $validator = Validator::make($request->all(), [
                'title'              => 'required|max:255', 
                'description'     => 'required',
                'image'     => 'array',
                
            ]);
            if ($validator->fails()){
                $error = $this->validationHandle($validator->messages()); 
                return response()->json(['status' => false, 'message' => $error],403);  
            }else{ 
                $user = new Feedback();
                $user->title = $data['title'];   
                $user->description = $data['description']; 
                $user->society_id = $societyId;   
                $user->user_id = $userId;   

                                      
                if($user->save()){         
                    if(isset($request->image) && count($request->image)>0){
                        $destinationPath = '/uploads/suggestion/';
                        foreach($request->image as $image){
                            $responseData = Uploader::doUpload($image,$destinationPath,true);    
                            if($responseData['status']=="true"){ 
                                $imgdata = new FeedbackImage();
                                $imgdata->image = $responseData['file'];   
                                $imgdata->feedback_id = $user->id;   

                                $imgdata->save();
                               
                            }  
                        }
                                                    
                    }   
                    return response()->json(['status' => true, 'message' => 'Feedback submitted successfully.','data'=>$data]);
                }else{
                    $data = User::getProfile($user->id); 
                    return response()->json(['status' => false, 'message' => 'Unknown error accured while updating information.','data'=>[]]);
                }
            }
        }catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);     
        } 
    }


    public function getMyFeedback(Request $request){  
        try{
            $created_by = $request->user()->created_by;
            $userId = $request->user()->id; 

            $profile = Feedback::where('society_id',$created_by);
            if($request->status && $request->status!= ''){
                $profile = $profile->where('status',$request->status);
            }
            $profile = $profile->where('user_id',$userId)->orderBy('id','desc')->paginate(10);
            // if(count($profile)>0){
                return response()->json(['status' => true, 'message' => 'Feedback list','data'=>$profile]);
            // }else{
            //     return response()->json(['status' => false, 'message' => 'Data Not Found','data'=>[]]);   
            // } 
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);          
        }  
    }



     public function getFeedbackDetail(Request $request,$id){  
        try{
            $created_by = $request->user()->created_by;
            $profile = Feedback::where('id',$id)->first();
            if($profile){
                return response()->json(['status' => true, 'message' => 'Feedback detail','data'=>$profile]);
            }else{
                return response()->json(['status' => false, 'message' => 'Data Not Found','data'=>[]]);   
            } 
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);          
        }  
    }



    public function getEventList(Request $request){ 
        try {  
            $societyId = $request->user()->created_by; 
            $validator = Validator::make($request->all(), [
                'date'              => 'nullable', 
                'type'     => 'nullable',
                
            ]);
            if ($validator->fails()){
                $error = $this->validationHandle($validator->messages()); 
                return response()->json(['status' => false, 'message' => $error],403);  
            }else{ 
                $getEvent = Event::where('society_id',$societyId);
                if(isset($request->type)){
                    $getEvent = $getEvent->where('type',$request->type);
                }
                // else{
                //     $getEvent = $getEvent->where('date',date('Y-m-d'));

                // }
                if(isset($request->date)){
                    $getEvent = $getEvent->where('date',$request->date);
                }
                // else{
                //     $getEvent = $getEvent->where('date',date('Y-m-d'));

                // }

                $getEvent = $getEvent->orderBy('id','desc')->paginate(10);
                                      
                //if(count($getEvent)>0){      
                    
                    return response()->json(['status' => true, 'message' => 'Event list.','data'=>$getEvent]);
                // }else{
                //     return response()->json(['status' => false, 'message' => 'Event Not found.','data'=>[]]);
                // }
            }
        }catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);        
        } 
    }


    public function getEventDetail(Request $request,$id){  
        try{
            $created_by = $request->user()->created_by;
            $profile = Event::where('id',$id)->first();
            if($profile){
                return response()->json(['status' => true, 'message' => 'Event detail','data'=>$profile]);
            }else{
                return response()->json(['status' => false, 'message' => 'Data Not Found','data'=>[]]);   
            } 
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);          
        }  
    }

    // This method use for added family memeber
    public function addFamilyMember(Request $request){ 
        try {  
            $data = $request->all();
            $userId = $request->user()->id; 
            $validator = Validator::make($request->all(), [
                'name'              => 'required|max:45', 
                'mobile_number'     => 'nullable',
                'gender'=>'nullable',
                'date_of_birth'=>'required',
                'education'=>'nullable',
                'relation'=>'required',
                'profile_picture'=>'nullable',
                'blood_group'=>'nullable',
            ]);
            if ($validator->fails()){
                $error = $this->validationHandle($validator->messages()); 
                return response()->json(['status' => false, 'message' => $error],403);  
            }else{ 
                
                $formData = $request->except('profile_picture');
                $formData['user_id'] = $userId;

                if($request->file('profile_picture')!==null){
                    $destinationPath = '/uploads/family-member/';
                    $responseData = Uploader::doUpload($request->file('profile_picture'),$destinationPath,true);    
                    if($responseData['status']=="true"){ 
                        $formData['profile_picture'] = $responseData['file']; 
                    }                             
                } 
                 $familyMember = FamilyMember::create($formData);   
                    $data = User::getProfile($userId);  

                if($familyMember){         
                    return response()->json(['status' => true, 'message' => 'Family member added successfully.','data'=>$data]);
                }else{
                    return response()->json(['status' => false, 'message' => 'Unknown error accured while updating information.','data'=>$data]);
                }
            }
        }catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);        
        } 
    }


     // This method use for update family memeber
    public function updateFamilyMember(Request $request){ 
        try {  
            $data = $request->all();
            $userId = $request->user()->id; 
            $validator = Validator::make($request->all(), [
                'member_id'         => 'required', 
                'name'              => 'required|max:45', 
                // 'mobile_number'     => 'nullable|unique:family_members,mobile_number',
                'mobile_number'     => 'nullable',

                'gender'=>'nullable',
                'date_of_birth'=>'required',
                'education'=>'nullable',
                'relation'=>'required',
                'profile_picture'=>'nullable',
                'blood_group'=>'nullable',
                
            ]);
            if ($validator->fails()){
                $error = $this->validationHandle($validator->messages()); 
                return response()->json(['status' => false, 'message' => $error],403);  
            }else{ 
                
                 $formData = $request->except(['profile_picture','member_id']);
                $formData['user_id'] = $userId;
                if($request->file('profile_picture')!==null){
                    $destinationPath = '/uploads/family-member/';
                    $responseData = Uploader::doUpload($request->file('profile_picture'),$destinationPath,true);    
                    if($responseData['status']=="true"){ 
                        $formData['profile_picture'] = $responseData['file']; 
                    }                             
                } 
                $familyMember = FamilyMember::where('id',$request->member_id)->update($formData);    
                    $data = User::getProfile($userId);  

                return response()->json(['status' => true, 'message' => 'Family member updated successfully.','data'=>$data]);
            }
        }catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);          
        } 
    }

    // delete family member
    public function deleteFamilyMember(Request $request){ 
        try {  
            $data = $request->all();
            $userId = $request->user()->id; 
            $validator = Validator::make($request->all(), [
                'member_id'         => 'required', 
               
            ]);
            if ($validator->fails()){
                $error = $this->validationHandle($validator->messages()); 
                return response()->json(['status' => false, 'message' => $error],403);  
            }else{ 
                
               
                $familyMember = FamilyMember::where('id',$request->member_id)->delete();    
                    $data = User::getProfile($userId);  

                if($familyMember){         
                    return response()->json(['status' => true, 'message' => 'Family member deleted successfully.','data'=>$data]);
                }else{
                    return response()->json(['status' => false, 'message' => 'Unknown error accured while delete information.','data'=>[]]);
                }
            }
        }catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);      
        } 
    }

    // get family member lisst
    public function getFamilyMember(Request $request){  
        try{
            $created_by = $request->user()->created_by;
            $userId = $request->user()->id; 

            $members = FamilyMember::where('user_id',$userId)->orderBy('id','desc')->paginate(10);
            // if(count($members)>0){
                return response()->json(['status' => true, 'message' => 'Family Member list','data'=>$members]);
            // }else{
            //     return response()->json(['status' => false, 'message' => 'Data Not Found','data'=>[]]);   
            // } 
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);          
        }  
    }



    // vehicle api methods
     // This method use for added Vehicle 
    public function addVehicle(Request $request){ 
        try {  
            $data = $request->all();
            $userId = $request->user()->id; 
            $validator = Validator::make($request->all(), [
                'vehicle_number'              => 'required|max:45', 
                'type'     => 'required',
                
                'image'=>'nullable',
            ]);
            if ($validator->fails()){
                $error = $this->validationHandle($validator->messages()); 
                return response()->json(['status' => false, 'message' => $error],403);  
            }else{ 
                
                $formData = $request->except('profile_picture');
                $formData['user_id'] = $userId;

                if($request->file('image')!==null){
                    $destinationPath = '/uploads/vehicle/';
                    $responseData = Uploader::doUpload($request->file('image'),$destinationPath,true);    
                    if($responseData['status']=="true"){ 
                        $formData['image'] = $responseData['file']; 
                    }                             
                } 
                 $familyMember = Vehicle::create($formData);   
                    $data = User::getProfile($userId);  

                if($familyMember){         
                    return response()->json(['status' => true, 'message' => 'Vehicle added successfully.','data'=>$familyMember]);
                }else{
                    return response()->json(['status' => false, 'message' => 'Unknown error accured while updating information.','data'=>$data]);
                }
            }
        }catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);       
        } 
    }


     // This method use for update Vehicle
    public function updateVehicle(Request $request){ 
        try {  
            $data = $request->all();
            $userId = $request->user()->id; 
            $validator = Validator::make($request->all(), [
                'vehicle_id'         => 'required', 
                'vehicle_number'              => 'required|max:45', 
                'type'     => 'required',
                
                'image'=>'nullable',
            ]);
            if ($validator->fails()){
                $error = $this->validationHandle($validator->messages()); 
                return response()->json(['status' => false, 'message' => $error],403);  
            }else{ 
                
                $formData = $request->except(['profile_picture','vehicle_id']);
                $formData['user_id'] = $userId;
               if($request->file('image')!==null){
                    $destinationPath = '/uploads/vehicle/';
                    $responseData = Uploader::doUpload($request->file('image'),$destinationPath,true);    
                    if($responseData['status']=="true"){ 
                        $formData['image'] = $responseData['file']; 
                    }                             
                } 
                $vehicle = Vehicle::where('id',$request->vehicle_id)->update($formData);    

                return response()->json(['status' => true, 'message' => 'Vehicle updated successfully.','data'=>$vehicle]);
            }
        }catch (\Exception $e) {
           return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);           
        } 
    }

    // delete Vehicle
    public function deleteVehicle(Request $request){ 
        try {  
            $data = $request->all();
            $userId = $request->user()->id; 
            $validator = Validator::make($request->all(), [
                'vehicle_id'         => 'required', 
               
            ]);
            if ($validator->fails()){
                $error = $this->validationHandle($validator->messages()); 
                return response()->json(['status' => false, 'message' => $error],403);  
            }else{ 
                
               
                $vehicle = Vehicle::where('id',$request->vehicle_id)->delete();    

                if($vehicle){         
                    return response()->json(['status' => true, 'message' => 'Vehicle deleted successfully.','data'=>[]]);
                }else{
                    return response()->json(['status' => false, 'message' => 'Unknown error accured while delete information.','data'=>[]]);
                }
            }
        }catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);          
        } 
    }

    // get Vehicle
    public function getVehicle(Request $request){  
        try{
            $created_by = $request->user()->created_by;
            $userId = $request->user()->id; 

            $vehicle = Vehicle::where('user_id',$userId)->orderBy('id','desc')->paginate(10);
            // if(count($vehicle)>0){
                return response()->json(['status' => true, 'message' => 'Vehicle list','data'=>$vehicle]);
            // }else{
            //     return response()->json(['status' => false, 'message' => 'Data Not Found','data'=>[]]);   
            // } 
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);          
        }  
    }



     public function pageDetail(Request $request,$slug){  
        try{
            $created_by = $request->user()->created_by;

            $pageDetail = Page::where('society_id',$created_by)->where('slug',$slug)->first();
            if($pageDetail){
                return response()->json(['status' => true, 'message' => 'Page Details','data'=>$pageDetail]);
            }else{
                return response()->json(['status' => false, 'message' => 'Data Not Found','data'=>[]]);   
            } 
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);          
        }  
    }



     public function getDashboardDetail(Request $request){  
        try{
            $created_by = $request->user()->created_by;
             $userId = $request->user()->id;
             $email = $request->user()->id;
            $dashboard=[];
            $dashboard['banner'] = Banner::where('society_id',$created_by)->where('status',1)->get();
            $getLogo = User::where('id',$created_by)->first();
            $dashboard['logo']=@$getLogo->profile_picture;
            $dashboard['name']=@$getLogo->name;
            $userDetail = User::where('id',$userId)->first();
            $dashboard['is_email_added']=($userDetail->email && $userDetail->email != null)?1:0;
           
            $dashboard['news_update'] = NewsUpdate::where('created_by',$created_by)->orderBy('id','desc')->limit(5)->get();
            $dashboard['notification_count']= Notification::where('user_id',$userId)->where('is_seen','0')->count();
             $dashboard['app_settings'] = AppSetting::where('id',1)->first();
             $dashboard['app_settings']['message'] = '';
             $dashboard['app_settings']['name'] = @$getLogo->name;
             if($dashboard['app_settings']['force_update'] == 1){
                $dashboard['app_settings']['message'] = $dashboard['app_settings']['force_update_message'];
            }   
            if($dashboard['app_settings']['is_maintenance'] == 1){
                $dashboard['app_settings']['message'] = $dashboard['app_settings']['is_maintenance_message'];
            }  
           


            return response()->json(['status' => true, 'message' => 'Dashboard Data','data'=>$dashboard]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);        
        }  
    }





       // vehicle api methods
     // This method use for added Vehicle 
    public function addVisitor(Request $request){ 
        try {  
            $data = $request->all();
            $userId = $request->user()->id; 
            $validator = Validator::make($request->all(), [
                'name'              => 'required|max:45', 
                'mobile_number'     => 'required',
                'image'=>'nullable',
                'document'=>'nullable',
            ]);
            if ($validator->fails()){
                $error = $this->validationHandle($validator->messages()); 
                return response()->json(['status' => false, 'message' => $error],403);  
            }else{ 
                
                $formData = $request->except('profile_picture');
                $formData['user_id'] = $userId;

                if($request->file('image')!==null){
                    $destinationPath = '/uploads/visitor/';
                    $responseData = Uploader::doUpload($request->file('image'),$destinationPath,true);    
                    if($responseData['status']=="true"){ 
                        $formData['image'] = $responseData['file']; 
                    }                             
                } 


                if($request->file('document')!==null){
                    $destinationPath = '/uploads/visitor/document/';
                    $responseData = Uploader::doUpload($request->file('document'),$destinationPath,true);    
                    if($responseData['status']=="true"){ 
                        $formData['document'] = $responseData['file']; 
                    }                             
                }   
                $familyMember = Visitor::create($formData);   

                if($familyMember){         
                    return response()->json(['status' => true, 'message' => 'Visitor added successfully.','data'=>$familyMember]);
                }else{
                    return response()->json(['status' => false, 'message' => 'Unknown error accured while updating information.','data'=>$data]);
                }
            }
        }catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);          
        } 
    }


     // This method use for update Vehicle
    public function updateVisitor(Request $request){ 
        try {  
            $data = $request->all();
            $userId = $request->user()->id; 
            $validator = Validator::make($request->all(), [
                'visitor_id'         => 'required', 
               'name'              => 'required|max:45', 
                'mobile_number'     => 'required',
                'image'=>'nullable',
                'document'=>'nullable',
            ]);
            if ($validator->fails()){
                $error = $this->validationHandle($validator->messages()); 
                return response()->json(['status' => false, 'message' => $error],403);  
            }else{ 
                
                $formData = $request->except(['image','document','visitor_id']);
                $formData['user_id'] = $userId;
              if($request->file('image')!==null){
                    $destinationPath = '/uploads/visitor/';
                    $responseData = Uploader::doUpload($request->file('image'),$destinationPath,true);    
                    if($responseData['status']=="true"){ 
                        $formData['image'] = $responseData['file']; 
                    }                             
                } 


                if($request->file('document')!==null){
                    $destinationPath = '/uploads/visitor/document/';
                    $responseData = Uploader::doUpload($request->file('document'),$destinationPath,true);    
                    if($responseData['status']=="true"){ 
                        $formData['document'] = $responseData['file']; 
                    }                             
                }
                $vehicle = Visitor::where('id',$request->visitor_id)->update($formData);    
                $getVisitor = Visitor::where('id',$request->visitor_id)->first();
                return response()->json(['status' => true, 'message' => 'Visitor updated successfully.','data'=>$getVisitor]);
            }
        }catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);         
        } 
    }

    // delete Vehicle
    public function deleteVisitor(Request $request){ 
        try {  
            $data = $request->all();
            $userId = $request->user()->id; 
            $validator = Validator::make($request->all(), [
                'visitor_id'         => 'required', 
               
            ]);
            if ($validator->fails()){
                $error = $this->validationHandle($validator->messages()); 
                return response()->json(['status' => false, 'message' => $error],403);  
            }else{ 
                
               
                $vehicle = Visitor::where('id',$request->visitor_id)->delete();    

                if($vehicle){         
                    return response()->json(['status' => true, 'message' => 'Visitor deleted successfully.','data'=>[]]);
                }else{
                    return response()->json(['status' => false, 'message' => 'Unknown error accured while delete information.','data'=>[]]);
                }
            }
        }catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]]);         
        } 
    }


    public function getVisitor(Request $request){  
        try{
            $created_by = $request->user()->created_by;
            $userId = $request->user()->id; 

            $members = Visitor::where('user_id',$userId)->orderBy('id','desc')->paginate(10);
            // if(count($members)>0){
                return response()->json(['status' => true, 'message' => 'Visitor list','data'=>$members]);
            // }else{
            //     return response()->json(['status' => false, 'message' => 'Data Not Found','data'=>[]]);   
            // } 
        } catch (\Exception $e) {
           return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);         
        }  
    }



     protected function updateDeviceToken(Request $request){
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                
                'device_type'   => 'required|in:IOS,ANDROID',
                'device_token'  => 'required',
                'device_unique_id'=>'nullable',
                'device_os'=>'nullable',
            ]);
            if ($validator->fails()) {
                $error = $this->validationHandle($validator->messages()); 
                return response()->json(['status' => false, 'message' => $error],403); 
            } else {            
                
                    $userId = $request->user()->id; 

                   UserDevices::deviceHandle([
                        "id"       =>  $userId,
                        "device_type"   =>  $data['device_type'],
                        "device_token"  =>  $data['device_token'],
                    ]);

                   return response()->json(['status' => true, 'message' => 'Device token updated successfully','data'=>[]]);   
                
            }
        }catch (\Exception $e) { 
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);  
        }  
    }

    public function deleteAccount(Request $request){
        try{
            $token = JWTAuth::getToken();
                    $userId = $request->user()->id; 

             $delete = User::where('id','=',$userId)->delete(); 
            if ($token) {
                JWTAuth::setToken($token)->invalidate();
            }

            return response()->json(['status' => true, 'message' => 'Your profile deleted successfully','data'=>[]],401);  
        }
        catch (\Exception $e) { 
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);   
        }
    }



     public function userAppSetting(Request $request){
        try {
                       
            
             $data = AppSetting::where('id',1)->first();
             $data->message = '';
            if($data->force_update == 1){
                $data->message = $data->force_update_message;
            }    
            if($data->is_maintenance == 1){
                $data->message = $data->is_maintenance_message;
            }          
                    
            return response()->json(['status' => 'true', 'message' => 'App Setting.','data'=>$data]);
            
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);  
        }
    }


    public function getPaymentCharges(Request $request){
        try {
                       
            
            $created_by = $request->user()->created_by;
            $data['charges_detail'] = $chargesDescrtiption =  SocietyCharges::where('society_id',$created_by)->where('status',1)->get();
              
               $data['qr_detail'] = User::where('id',$created_by)->select('name','profile_picture','mobile_number','qr_code','banking_name','upi_handle')->first(); 
            return response()->json(['status' => 'true', 'message' => 'Detial.','data'=>$data]);
            
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);  
        }
    }
    

    public function getQRDetail(Request $request){
        try {
                       
          
            $created_by = $request->user()->created_by;

            $paymentDetail = User::where('id',$created_by)->select('name','profile_picture','mobile_number','qr_code','banking_name','upi_handle')->first(); 
               
            return response()->json(['status' => 'true', 'message' => 'QR Detial.','data'=>$paymentDetail]);
            
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);  
        }
    }


     protected function uploadReceipt(Request $request){
        try {
            $created_by = $request->user()->created_by;
            $userID = $request->user()->id;
            $data = $request->all();
            $validator = Validator::make($data, [
                
                'receipt'   => 'required',
                'payment_type'  => 'required',
                // 'amount'=>'required'
               
            ]);
            if ($validator->fails()) {
                $error = $this->validationHandle($validator->messages()); 
                return response()->json(['status' => false, 'message' => $error],403); 
            } else {            
                
                    $formData['user_id']=$userID;
                    $formData['society_id']=$created_by;
                    $formData['payment_type']=$request->payment_type;
                    // $formData['amount']=$request->amount;
                    if($request->file('receipt')!==null){
                        $destinationPath = '/uploads/receipt/';
                        $responseData = Uploader::doUpload($request->file('receipt'),$destinationPath,true);    
                        if($responseData['status']=="true"){ 
                            $formData['receipt'] = $responseData['file']; 
                        }                             
                    }
                   $transaction = Transaction::create($formData);

                   return response()->json(['status' => true, 'message' => 'Receipt uploaded successfully','data'=>$transaction]);   
                
            }
        }catch (\Exception $e) { 
           return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);  
        }  
    }

public function getMyTransaction(Request $request){
        try {
                       
          
            $created_by = $request->user()->created_by;
            $userID = $request->user()->id;

            $paymentDetail = Transaction::where('user_id',$userID)->where('society_id',$created_by)->orderBy('id','desc')->paginate(15); 
               
            return response()->json(['status' => 'true', 'message' => 'Transaction List.','data'=>$paymentDetail]); 
            
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]],403);  
        }
    }


     public function sendVerificationEmail(Request $request){
        try {

            $userID = $request->user()->id;

            $data = $request->all(); 
            $validator = Validator::make($data, ['email' => 'required|unique:users|email']);
            if($validator->fails()){
                $error = $this->validationHandle($validator->messages()); 
                return response()->json(['status' => false, 'message' => $error],403); 
            }else{ 
                if(isset($request->otp)){
                    $checkOtp = Otp::where('email',$request->email)->where('otp',$request->otp)->first();
                   if($checkOtp){
                         User::where('id',$userID)->update(['email'=>$request->email]);
                        return response()->json(['status' => true, 'message' => 'Otp Verified.','data'=>$data]);
                   }
                   else{
                     return response()->json(['status' => false, 'message' => 'Invalid otp!'],401); 
                   }
                }
                $user = User::where('email',$data['email'])->where('role',1)->first();
                    if($user){
                        return response()->json(['status' => false, 'message' => 'Email alrady exist.'],404); 
                    }
                    else{
                       
                            $otp = mt_rand(100000, 999999);
                            // $otp = 1234;
                            // $data['otp'] = $otp;
                         $email_data['otp']         = $otp;

                            $data['email'] = $request->email;
                            Otp::where('email',$request->email)->delete();
                            Otp::create(['email'=>$request->email,'otp'=>$otp,'type'=>'Forgot Password']);
                       Email::send('email-verification',$email_data,$request->get('email'),'Verification Email');     
                            return response()->json(['status' => true, 'message' => 'We have emailed an otp for verification email!','data'=>$data]); 
                        
                    }
            }
        }catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(),'data'=>[]]); 
        }
    }
}
