<?php

namespace App\Http\Controllers\Admin;

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
use Illuminate\Support\Facades\Hash;

class SocietyOwnerController extends Controller
{
    public function __construct()
    {
        
    }

    public function add(Request $request,$id=null){  
        if($id){
            $title = "Edit Society Owner";
            $breadcrumbs = [
                ['name'=>'Society Owner','relation'=>'link','url'=>route('admin.society-owner.index')],
                ['name'=>'Edit Society Owner','relation'=>'Current','url'=>'']
            ];
        }else{
            $title = "Add New Society Owner";
            $breadcrumbs = [
                ['name'=>'Society Owner','relation'=>'link','url'=>route('admin.society-owner.index')],
                ['name'=>'Add New Society Owner','relation'=>'Current','url'=>'']
            ];
        }
        $data = ($id)?User::find($id):array();
        if($request->ajax() && $request->isMethod('post')){
            try {
                $rules = [
                    'name'              => 'required',
                    'email'             => 'required|email|unique:users,email,'.$id,          
                    'image'             => 'nullable|image',
                    'description'       => 'required|max:1500',
                    
                   'password' =>'required|min:8|max:20',
                    'confirm_password'=>'required|min:8|max:45|same:password', 
                ];
                $validator = Validator::make($request->all(), $rules);
                if($validator->fails()){
                    return response()->json(array('errors' => $validator->messages()), 422);
                }else{
                    $formData = $request->except('image');
                    
                    $formData['slug'] = Str::slug($request->get('name'));
                    if($request->file('image')!==null){
                        // This code use for profile picture upload
                        $destinationPath = '/uploads/society-owner/';
                        $responseData = Uploader::doUpload($request->file('image'),$destinationPath,true); 
                        if($responseData['status']=="true"){
                            $formData['logo'] = $responseData['file'];
                            // $formData['thumb'] = $responseData['thumb'];
                        }                             
                    } 
                    if($id){                        
                        $data->update($formData);
                        Session::flash('success','Category updated successfully.');
                    }else{
                        $formData['password'] =  Hash::make($request->get('password'));
                        $formData['role'] = 2;
                        User::create($formData);
                        Session::flash('success','Category created successfully.');
                    }
                    return ['status' => 'true', 'message' => 'Records updated successfully.'];
                }
            } catch (\Exception $e) {
                return ['status' => false, 'message' => $e->getMessage()];
            } 
        } 
        return view('admin/society-owner/add',compact('id','data','title','breadcrumbs'));
    }

    public function index(){ 
        $title = "Society Owner";
        $breadcrumbs = [ 
            ['name'=>'Society Owner','relation'=>'Current','url'=>'']
        ];
        return view('admin/society-owner/index',compact('title','breadcrumbs'));
    }

    public function datatables()
    {
        $pages = User::where('role',2)->get();
        return DataTables::of($pages)
            ->addColumn('action', function ($page) {
                return '<a href="'.route('admin.society-owner.add',$page->id).'" class="btn btn-xs btn-primary"><i class="fas fa-edit"></i> Edit</a>&nbsp;<a data-link="'.route('admin.society-owner.delete').'" id="delete_'.$page->id.'" onclick="confirm_delete('.$page->id.')" href="javascript:void(0)" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i> Delete</a>';
            })
            ->editColumn('status',function($page){
                return Helper::getStatus($page->status,$page->id,route('admin.society-owner.status'));
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
            $row = Category::whereId($id)->first();
            $row->status = $row->status=='1'?'0':'1';
            $row->save(); 
            return Helper::getStatus($row->status,$id,route('admin.society-owner.status'));
        }
    }  

    public function delete(Request $request)
    {
        if($request->ajax() && $request->isMethod('post')){
            $id = $request->id;
            try{
                $delete = User::where('id','=',$id)->delete();   
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
}
