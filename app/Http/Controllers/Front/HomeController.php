<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Auth;
use Session;
use Validator;
use Hash;
use App\Lib\Uploader;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    { 
       $this->middleware('auth.user'); 
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $title = "Dashboard";
        $users_count = User::where('role',1)->where('created_by',Auth::guard('web')->user()->id)->count();
        $categories_count = Category::count();
        return view('society-front.home.index',compact('title','users_count','categories_count'));  
    }

    public function profile(Request $request){ 
        $title = "Profile";
        $breadcrumbs = [
            ['name'=>'Profile','relation'=>'Current','url'=>'']
        ];
        $data = User::find(Auth::guard('web')->user()->id);
        if($request->ajax() && $request->isMethod('post')){
            try {
                $validator = Validator::make($request->all(), [
                    'name'              => 'required|max:45',     
                    'email'             => 'required|email|unique:users,email,'.$data->id, 
                    'mobile_number'             => 'required|unique:users,mobile_number,'.$data->id, 
                    'profile_picture'     => 'nullable|image',
                    'banking_name'              => 'required',     
                    'upi_handle'              => 'required',     
                    'qr_code'              => 'nullable|image',     

                ]);
                if($validator->fails()){
                    return response()->json(array('errors' => $validator->messages()), 422);
                }else{
                     $formData = [
                        'name'      =>      $request->get('name'),
                        'email'     =>      $request->get('email'),
                        'mobile_number'     =>      $request->get('mobile_number'),
                        'banking_name'     =>      $request->get('banking_name'),
                        'upi_handle'     =>      $request->get('upi_handle'),
                    ];
                    if ($request->hasFile('profile_picture')) {
                        if($request->file('profile_picture')->isValid()) { 
                            $path = "/uploads/society-owner/";
                            $responseData =  Uploader::doUpload($request->file('profile_picture'),$path,false);
                            $formData['profile_picture'] = $responseData['file'];
                        }
                    } 
                    if ($request->hasFile('qr_code')) {
                        if($request->file('qr_code')->isValid()) { 
                            $path = "/uploads/society-owner/qr-code/";
                            $responseData =  Uploader::doUpload($request->file('qr_code'),$path,false);
                            $formData['qr_code'] = $responseData['file'];
                        }
                    } 
                    $data->update($formData);
                    return ['status'=>'true','message'=>__("Profile updated successfully.")];
                }
            } catch (\Exception $e) {
                return ['status'=>'false','message'=>$e->getMessage()];
            }
        }
        return view('society-front.home.profile',compact('title','breadcrumbs','data'));
    }

    public function changePassword(Request $request)
    { 
        $title = "Change Password";
        $breadcrumbs = [
            ['name'=>'Change Password','relation'=>'Current','url'=>'']
        ];
        if($request->ajax() && $request->isMethod('post')){
            try {
                $validator = Validator::make($request->all(), [
                    'current_password'      => 'required|max:45',     
                    'new_password'          => 'required|max:45|min:8|same:confirm_password',     
                    'confirm_password'      => 'required|max:45|min:8'
                ]);
                if($validator->fails()){
                    return response()->json(array('errors' => $validator->messages()), 422);
                }else{ 
                    $data = User::find(Auth::guard('web')->user()->id);
                    if(Hash::check($request->get('current_password'),$data->password)){
                        $data->update(['password'=>Hash::make($request->get('new_password'))]);
                        Session::flash('success',__("Password updated successfully."));
                        return ['status'=>'true','message'=>__("Password updated successfully.")];
                    }else{
                        return ['status'=>'false','message'=>__("Current password does't match.")];
                    }         
                }
            } catch (\Exception $e) {
                return ['status'=>'false','message'=>$e->getMessage()];
            }
        } 
        return view('society-front.home.change_password',compact('title','breadcrumbs'));
    }

    function toggleSidebar(Request $request){
        if($request->ajax() && $request->isMethod('get')){
            try {
                $sidebar_style = Auth::guard('web')->user()->sidebar_style;
                $sidebar_style = ($sidebar_style=='MINI')?'FULL':'MINI';
                Admin::where('id',Auth::guard('web')->id())->update(['sidebar_style'=>$sidebar_style]);
                return ['status'=>'true','message'=>'Settings updated'];
            } catch (\Exception $e) {
                return ['status'=>'false','message'=>$e->getMessage()];
            }

        }
    }
}
