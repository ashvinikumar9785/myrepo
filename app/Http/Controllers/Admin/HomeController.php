<?php

namespace App\Http\Controllers\Admin;

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

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $title = "Dashboard";
        $users_count = User::count();
        $categories_count = Category::count();
        return view('admin.home.index',compact('title','users_count','categories_count'));  
    }

    public function profile(Request $request){ 
        $title = "Profile";
        $breadcrumbs = [
            ['name'=>'Profile','relation'=>'Current','url'=>'']
        ];
        $data = Admin::find(Auth::guard('admin')->user()->id);
        if($request->ajax() && $request->isMethod('post')){
            try {
                $validator = Validator::make($request->all(), [
                    'name'              => 'required|max:45',     
                    'email'             => 'required|email|unique:admins,email,'.$data->id, 
                    'profile_picture'     => 'nullable|image'
                ]);
                if($validator->fails()){
                    return response()->json(array('errors' => $validator->messages()), 422);
                }else{
                    $formData = [
                        'name'      =>      $request->get('name'),
                        'email'     =>      $request->get('email'),
                    ];
                    if ($request->hasFile('profile_picture')) {
                        if($request->file('profile_picture')->isValid()) { 
                            $path = "/uploads/users/";
                            $responseData =  Uploader::doUpload($request->file('profile_picture'),$path,false);
                            $formData['profile_picture'] = $responseData['file'];
                        }
                    } 
                    $data->update($formData);
                    return ['status'=>'true','message'=>__("Profile updated successfully.")];
                }
            } catch (\Exception $e) {
                return ['status'=>'false','message'=>$e->getMessage()];
            }
        }
        return view('admin.home.profile',compact('title','breadcrumbs','data'));
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
                    $data = Admin::find(Auth::guard('admin')->user()->id);
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
        return view('admin.home.change_password',compact('title','breadcrumbs'));
    }

    function toggleSidebar(Request $request){
        if($request->ajax() && $request->isMethod('get')){
            try {
                $sidebar_style = Auth::guard('admin')->user()->sidebar_style;
                $sidebar_style = ($sidebar_style=='MINI')?'FULL':'MINI';
                Admin::where('id',Auth::guard('admin')->id())->update(['sidebar_style'=>$sidebar_style]);
                return ['status'=>'true','message'=>'Settings updated'];
            } catch (\Exception $e) {
                return ['status'=>'false','message'=>$e->getMessage()];
            }

        }
    }
}
