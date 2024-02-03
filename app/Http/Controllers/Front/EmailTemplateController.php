<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Lib\Helper;
use Session;
use DataTables;
use Validator;
use Str;
class EmailTemplateController extends Controller
{
    public function __construct()
    {

    }

    public function add(Request $request,$id=null){
        if($id){
            $title = "Edit Email Template";
            $breadcrumbs = [
                ['name'=>'Pages','relation'=>'link','url'=>route('admin.emailtemplates.index')],
                ['name'=>'Edit Email Template','relation'=>'Current','url'=>'']
            ];
        }else{
            $title = "Add New Email Template";
            $breadcrumbs = [
                ['name'=>'Pages','relation'=>'link','url'=>route('admin.emailtemplates.index')],
                ['name'=>'Add New Email Template','relation'=>'Current','url'=>'']
            ];
        }
        $data = ($id)?EmailTemplate::find($id):array();
        if($request->ajax() && $request->isMethod('post')){
            try {
                $formData = $request->except('slug');
                $formData['slug'] = Str::slug($request->get('slug'));
                $validator = Validator::make($formData, [
                    'title'             => 'required|max:255',     
                    'keywords'          => 'required|max:255',     
                    'slug'              => 'required|max:255|unique:email_templates,slug,'.$id,     
                    'content'           => 'required',
                ]);
                if($validator->fails()){
                    return response()->json(array('errors' => $validator->messages()), 422);
                }else{ 
                    if($id){                        
                        $data->update($formData);
                        Session::flash('success','Email Template updated successfully');
                    }else{
                        EmailTemplate::create($formData);
                        Session::flash('success','Email Template created successfully');
                    }
                    return ['status' => 'true', 'message' => 'Records updated successfully'];                  
                }
            } catch (\Exception $e) {
                return ['status' => 'false', 'message' => $e->getMessage()];
            }            
        } 
        return view('admin/emailtemplates/add',compact('id','data','title','breadcrumbs'));
    }

    public function index(){ 
        $title = "Email Templates";
        $breadcrumbs = [ 
            ['name'=>'Email Templates','relation'=>'Current','url'=>'']
        ];
        return view('admin/emailtemplates/index',compact('title','breadcrumbs'));
    }

    public function datatables()
    {
        $emailtemplates = EmailTemplate::select(['id', 'title', 'slug', 'status','keywords'])->orderBy('id','desc')->get();

        return DataTables::of($emailtemplates)
            ->addColumn('action', function ($emailtemplate) {
                return '<a href="'.route('admin.emailtemplates.add',$emailtemplate->id).'" class="btn btn-xs btn-primary"><i class="fas fa-edit"></i> Edit</a>';
            })
            ->editColumn('status',function($emailtemplate){
                return Helper::getStatus($emailtemplate->status,$emailtemplate->id,route('admin.emailtemplates.status'));
            })   
            ->rawColumns(['status','action'])
            ->make(true);
    }

    public function status(Request $request)
    {
        $id = $request->id; 
        $row = EmailTemplate::whereId($id)->first();
        $row->status = $row->status=='1'?'0':'1';
        $row->save(); 
        return Helper::getStatus($row->status,$id,route('admin.emailtemplates.status')); 
    }  
}
