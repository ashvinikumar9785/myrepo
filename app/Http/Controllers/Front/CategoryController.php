<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Lib\Helper;
use App\Lib\Uploader;
use Session;
use DataTables;
use Validator; 
use Str; 
class CategoryController extends Controller
{
    public function __construct()
    {
        
    }

    public function add(Request $request,$id=null){  
        if($id){
            $title = "Edit Category";
            $breadcrumbs = [
                ['name'=>'Categories','relation'=>'link','url'=>route('admin.categories.index')],
                ['name'=>'Edit Category','relation'=>'Current','url'=>'']
            ];
        }else{
            $title = "Add New Category";
            $breadcrumbs = [
                ['name'=>'Categories','relation'=>'link','url'=>route('admin.categories.index')],
                ['name'=>'Add New Category','relation'=>'Current','url'=>'']
            ];
        }
        $data = ($id)?Category::find($id):array();
        if($request->ajax() && $request->isMethod('post')){
            try {
                $rules = [
                    'title'             => 'required|max:70|unique:categories,title,'.$id,     
                    'image'             => 'nullable|image',
                    'description'       => 'nullable|max:1500',
                ];
                $validator = Validator::make($request->all(), $rules);
                if($validator->fails()){
                    return response()->json(array('errors' => $validator->messages()), 422);
                }else{
                    $formData = $request->except('image');
                    $formData['slug'] = Str::slug($request->get('title'));
                    if($request->file('image')!==null){
                        // This code use for profile picture upload
                        $destinationPath = '/uploads/categories/';
                        $responseData = Uploader::doUpload($request->file('image'),$destinationPath,true); 
                        if($responseData['status']=="true"){
                            $formData['image'] = $responseData['file'];
                            $formData['thumb'] = $responseData['thumb'];
                        }                             
                    } 
                    if($id){                        
                        $data->update($formData);
                        Session::flash('success','Category updated successfully.');
                    }else{
                        Category::create($formData);
                        Session::flash('success','Category created successfully.');
                    }
                    return ['status' => 'true', 'message' => 'Records updated successfully.'];
                }
            } catch (\Exception $e) {
                return ['status' => false, 'message' => $e->getMessage()];
            } 
        } 
        return view('admin/categories/add',compact('id','data','title','breadcrumbs'));
    }

    public function index(){ 
        $title = "Categories";
        $breadcrumbs = [ 
            ['name'=>'Categories','relation'=>'Current','url'=>'']
        ];
        return view('admin/categories/index',compact('title','breadcrumbs'));
    }

    public function datatables()
    {
        $pages = Category::select(['id', 'title', 'status','image'])->get();
        return DataTables::of($pages)
            ->addColumn('action', function ($page) {
                return '<a href="'.route('admin.categories.add',$page->id).'" class="btn btn-xs btn-primary"><i class="fas fa-edit"></i> Edit</a>&nbsp;<a data-link="'.route('admin.categories.delete').'" id="delete_'.$page->id.'" onclick="confirm_delete('.$page->id.')" href="javascript:void(0)" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i> Delete</a>';
            })
            ->editColumn('status',function($page){
                return Helper::getStatus($page->status,$page->id,route('admin.categories.status'));
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
            return Helper::getStatus($row->status,$id,route('admin.categories.status'));
        }
    }  

    public function delete(Request $request)
    {
        if($request->ajax() && $request->isMethod('post')){
            $id = $request->id;
            try{
                $delete = Category::where('id','=',$id)->delete();   
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
