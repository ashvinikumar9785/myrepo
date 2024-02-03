<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use App\Lib\Helper;
use App\Lib\Uploader;
use Session;
use DataTables;
use Validator; 
use Str; 
use Auth;
use Excel;
use App\Imports\UsersImport;
use Illuminate\Support\Facades\Hash;
use App\Exports\UsersExport;

class SocietyMemberController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth.user'); 
    }

    public function add(Request $request,$id=null){  
        if($id){
            $title = "Edit Society Member";
            $breadcrumbs = [
                ['name'=>'Society Member','relation'=>'link','url'=>route('front.society-member.index')],
                ['name'=>'Edit Society Member','relation'=>'Current','url'=>'']
            ];
        }else{
            $title = "Add New Society Member";
            $breadcrumbs = [
                ['name'=>'Society Member','relation'=>'link','url'=>route('front.society-member.index')],
                ['name'=>'Add New Society Member','relation'=>'Current','url'=>'']
            ];
        }
        $data = ($id)?User::find($id):array();
        if($request->ajax() && $request->isMethod('post')){
            try {
                if(isset($request->import_file)){
                    $path = $request->file('import_file');

                    $data = Excel::import(new UsersImport, $path);


                    if($data){
                        return ["status"=>"true","message"=>"Imported successfully"];    
                    }
                    else{
                        return ["status"=>"false","message"=>"Record not updated."]; 
                    } 
                }
                $rules = [
                    'name'              => 'required',
                    'email'             => 'nullable|email|unique:users,email,'.$id,          
                    'mobile_number'             => 'required|integer|unique:users,mobile_number,'.$id,          
                    'image'             => 'nullable|image',
                    'address'       => 'required|max:1500',
                    'pin_code'       => 'required|min:4|max:6',
                    'owner_type'       => 'required',
                    
                   // 'password' =>'required|min:8|max:20',
                   //  'confirm_password'=>'required|min:8|max:45|same:password', 
                ];
                if(!$id){
                    $rules['user_password']  = 'required|min:8|max:20|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x]).*$/';
                    // $rules['confirm_password']  = 'required|min:8|max:45|same:user_password';
                }
                // else{
                //     $rules['user_password']  = 'required|min:8|max:20|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x]).*$/';
                //     $rules['confirm_password']  = 'nullable|min:8|max:45|same:user_password';
                // }
                $validator = Validator::make($request->all(), $rules);
                if($validator->fails()){
                    return response()->json(array('errors' => $validator->messages()), 422);
                }else{
                    $formData = $request->except('image');
                    $formData['created_by'] = Auth::guard('web')->user()->id;
                    $formData['slug'] = Str::slug($request->get('name'));
                    if($request->file('image')!==null){
                        // This code use for profile picture upload
                        $destinationPath = '/uploads/society-member/';
                        $responseData = Uploader::doUpload($request->file('image'),$destinationPath,true); 
                        if($responseData['status']=="true"){
                            $formData['profile_picture'] = $responseData['file'];
                            // $formData['thumb'] = $responseData['thumb'];
                        }                             
                    } 
                    if($id){     
                        if(isset($request->user_password)){
                            $formData['password'] =  Hash::make($request->get('user_password'));
                        }                   
                        $data->update($formData);
                        Session::flash('success','Member updated successfully.');
                    }else{
                        $formData['password'] =  Hash::make($request->get('user_password'));
                        $formData['role'] = 1;
                        User::create($formData);
                        Session::flash('success','Member created successfully.');
                    }
                    return ['status' => 'true', 'message' => 'Records updated successfully.'];
                }
            } catch (\Exception $e) {
                return ['status' => false, 'message' => $e->getMessage()];
            } 
        } 
        return view('society-front/society-member/add',compact('id','data','title','breadcrumbs'));
    }

    public function index(){ 
        
        $ajaxUrll = route('front.society-member.add');
        $title = "Society Member";
        $breadcrumbs = [ 
            ['name'=>'Society Member','relation'=>'Current','url'=>'']
        ];
        return view('society-front/society-member/index',compact('title','breadcrumbs','ajaxUrll'));
    }

    public function datatables()
    {
        $pages = User::where('role',1)->where('created_by',Auth::guard('web')->user()->id)->get();
        return DataTables::of($pages)
            ->addColumn('action', function ($page) {
                return '<a href="'.route('front.society-member.view',$page->id).'" class="btn btn-xs btn-primary"><i class="fas fa-eye"></i> View</a>&nbsp;<a href="'.route('front.society-member.add',$page->id).'" class="btn btn-xs btn-primary"><i class="fas fa-edit"></i> Edit</a>&nbsp;<a data-link="'.route('front.society-member.delete').'" id="delete_'.$page->id.'" onclick="confirm_delete('.$page->id.')" href="javascript:void(0)" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i> Delete</a>';
            })
            ->editColumn('status',function($page){
                return Helper::getStatus($page->status,$page->id,route('front.society-member.status'));
            })
            ->editColumn('image',function($page){
                return Helper::getImage($page->image,70);
            })
            ->rawColumns(['status','action','image'])
            ->make(true);
    }

    public function status(Request $request)
    {
        if($request->ajax() && $request->isMethod('post')){
            $id = $request->id; 
            $row = User::whereId($id)->first();
            $row->status = $row->status=='1'?'0':'1';
            $row->save(); 
            return Helper::getStatus($row->status,$id,route('front.society-member.status'));
        }
    }  

    public function delete(Request $request)
    {
        if($request->ajax() && $request->isMethod('post')){
            $id = $request->id;
            try{
                $delete = User::where('id','=',$id)->delete();   
                // $delete = Feedback::where('user_id','=',$id)->forceDelete();   
                if($delete){
                    return ["status"=>"true","message"=>"Record Deleted."]; 
                }else{
                    return ["status"=>"false","message"=>"Could not deleted Record."]; 
                } 
            }catch(\Exception $e){
                return ["status"=>"false","message"=>$e->getMessage()];   
            }
        }
    }


     public function view($id){
        $data = User::where('id',$id)->with('country')->first();
        // dd($data->family_member);
        if($data){
            $title = "Profile - " .$data->name;
            $breadcrumbs = [ 
                ['name'=>"Users",'relation'=>'link','url'=>route('front.society-member.index')],
                ['name'=>$title,'relation'=>'Current','url'=>'']
            ];
            return view('society-front/society-member/view',compact('title','breadcrumbs','data'));
        }else{
            return abort(404);
        }
    }


     public function importExvel(Request $request)
    {
        if($request->ajax() && $request->isMethod('post')){
            try{
                $path = $request->file('import_file');

                $data = Excel::import(new UsersImport, $path);


                if($data){
                    return ["status"=>"true","message"=>"Imp."];    
                }
                else{
                    return ["status"=>"false","message"=>"Record not updated."]; 
                }    
            }
            catch(\Exception $e){
                return ["status"=>"false","message"=>$e->getMessage()];   
            }
            
        }
    }  


     public function export() 
    {
        $data = User::select('name','email','mobile_number')->get();
        return Excel::download($data, 'members.xlsx');
    }
}
