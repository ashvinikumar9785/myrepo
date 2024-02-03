<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NewsUpdate;
use App\Models\User;
use App\Lib\Helper;
use App\Lib\Uploader;
use Session;
use DataTables;
use Validator; 
use Str; 
use Auth;

use Illuminate\Support\Facades\Hash;

class NewsUpdateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.user'); 
    }

    public function add(Request $request,$id=null){  
        if($id){
            $title = "News and updates";
            $breadcrumbs = [
                ['name'=>'News and updates','relation'=>'link','url'=>route('front.news-updates.index')],
                ['name'=>'Edit News and updates','relation'=>'Current','url'=>'']
            ];
        }else{
            $title = "Add News and updates";
            $breadcrumbs = [
                ['name'=>'News and updates','relation'=>'link','url'=>route('front.news-updates.index')],
                ['name'=>'Add News and updates','relation'=>'Current','url'=>'']
            ];
        }
        $data = ($id)?NewsUpdate::find($id):array();
        if($request->ajax() && $request->isMethod('post')){
            try {
                $rules = [
                    'title'              => 'required|max:255',
                    'description'       => 'required',                    
                ];
                $validator = Validator::make($request->all(), $rules);
                if($validator->fails()){
                    return response()->json(array('errors' => $validator->messages()), 422);
                }else{
                    $formData = $request->except('image');
                    $formData['created_by'] = Auth::guard('web')->user()->id;
                    if($request->file('image')!==null){
                        // This code use for profile picture upload
                        $destinationPath = '/uploads/news-updates/';
                        $responseData = Uploader::doUpload($request->file('image'),$destinationPath,true); 
                        if($responseData['status']=="true"){
                            $formData['image'] = $responseData['file'];
                            // $formData['thumb'] = $responseData['thumb'];
                        }                             
                    } 
                    if($id){                        
                        $data->update($formData);
                        Session::flash('success','News updated successfully.');
                    }else{
                        
                        $newsAdded = NewsUpdate::create($formData);
                        $event_id = $newsAdded->id;
                        $type = 'NEWS';
                        $message = "News updated from CVS group." .$request->title;
                        $fcmData = ['news_id'=>@$newsAdded->id,'type'=>'NEWS'];
                        $getUser = User::where('status',1)->get();
                        if($getUser && count($getUser)>0){
                            foreach($getUser as $user){
                                $this->sentNotification($user->id,$type,$message,$event_id,$fcmData);
                            }
                        }
                        Session::flash('success','News created successfully.');
                    }
                    return ['status' => 'true', 'message' => 'Records updated successfully.'];
                }
            } catch (\Exception $e) {
                return ['status' => false, 'message' => $e->getMessage()];
            } 
        } 
        return view('society-front/news-updates/add',compact('id','data','title','breadcrumbs'));
    }

    public function index(){ 
        $title = "News and updates";
        $breadcrumbs = [ 
            ['name'=>'News and updates','relation'=>'Current','url'=>'']
        ];
        return view('society-front/news-updates/index',compact('title','breadcrumbs'));
    }

    public function datatables()
    {
        $pages = NewsUpdate::where('created_by',Auth::guard('web')->user()->id)->get();
        return DataTables::of($pages)
            ->addColumn('action', function ($page) {
                return '<a href="'.route('front.news-updates.add',$page->id).'" class="btn btn-xs btn-primary"><i class="fas fa-edit"></i> Edit</a>&nbsp;<a data-link="'.route('front.news-updates.delete').'" id="delete_'.$page->id.'" onclick="confirm_delete('.$page->id.')" href="javascript:void(0)" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i> Delete</a>';
            })
            ->editColumn('status',function($page){
                return Helper::getStatus($page->status,$page->id,route('front.news-updates.status'));
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
            $row = NewsUpdate::whereId($id)->first();
            $row->status = $row->status=='1'?'0':'1';
            $row->save(); 
            return Helper::getStatus($row->status,$id,route('front.news-updates.status'));
        }
    }  

    public function delete(Request $request)
    {
        if($request->ajax() && $request->isMethod('post')){
            $id = $request->id;
            try{
                $delete = NewsUpdate::where('id','=',$id)->delete();   
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
