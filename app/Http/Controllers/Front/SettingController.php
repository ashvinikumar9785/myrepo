<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Session;
use App\Lib\Uploader;
use DataTables;
class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.user'); 
    }

    public function add(Request $request,$id=null){
        if($id){
            $title = "Edit Setting";
            $breadcrumbs = [
                ['name'=>'Settings','relation'=>'link','url'=>route('admin.settings.index')],
                ['name'=>'Edit Setting','relation'=>'Current','url'=>'']
            ];
        }else{
            $title = "Add Setting";
            $breadcrumbs = [
                ['name'=>'Settings','relation'=>'link','url'=>route('admin.settings.index')],
                ['name'=>'Add Setting','relation'=>'Current','url'=>'']
            ];
        }
        $data = ($id)?Setting::findorfail($id):array();
        if($request->ajax() && $request->isMethod('post')){
            try {
                $validator = Setting::validate($request->all(),$id);
                if($validator->fails()){
                    return response()->json(array('errors' => $validator->messages()), 422);
                }else{
                    $formData = $request->except('value');
                    if($request->field_type=="image"){
                        if ($request->hasFile('value')) {
                            if(substr($request->file('value')->getMimeType(), 0, 5) == 'image') { 
                                $path = "/uploads/settings/";
                                $responseData =  Uploader::doUpload($request->file('value'),$path);
                                $formData['value'] = $responseData['file'];
                            }else{
                                return ['status' => 'false', 'message' => 'The file must be an image'];
                                exit();
                            }
                        }
                    }else{
                        $formData['value'] = $request->get('value');
                    }
                    if($id){                        
                        $data->update($formData);
                        Session::flash('success','Setting updated successfully');
                    }else{
                        Setting::create($formData);
                        Session::flash('success','Setting created successfully');
                    }
                    session()->put('SiteValue',[]);
                    return ['status' => 'true', 'message' => 'Records updated successfully']; 
                }
            } catch (\Exception $e) {
                return ['status' => 'false', 'message' => $e->getMessage()];
            }            
        } 
        return view('admin/settings/add',compact('id','data','title','breadcrumbs'));
    }

    public function index(){ 
        $title = "Settings";
        $breadcrumbs = [ 
            ['name'=>'Settings','relation'=>'Current','url'=>'']
        ];
        return view('admin/settings/index',compact('title','breadcrumbs'));
    }

    public function datatables()
    {
        $settings = Setting::select(['id', 'field_title', 'field_name', 'field_type', 'value'])->get(); 
        return DataTables::of($settings)
        ->addColumn('action', function ($setting) {
            return '<a href="'.route('admin.settings.add',$setting->id).'" class="btn btn-xs btn-primary"><i class="fas fa-edit"></i> Edit</a>';
        })
        ->editColumn('value',function($setting){
            if($setting->field_type=='image'){
                return '<img src="'.url($setting->value).'" style="max-height:100px;" />';
            }else{
                return $setting->value;
            }
        })   
        ->rawColumns(['value','action'])
        ->make(true);
    }
}
