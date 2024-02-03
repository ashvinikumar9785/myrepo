<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\FeedbackImage;
use App\Models\User;
use App\Lib\Helper;
use App\Lib\Uploader;
use Session;
use DataTables;
use Validator; 
use Str; 
use Auth;

use Illuminate\Support\Facades\Hash;

class FeedBackController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth.user'); 
    }

    public function add(Request $request,$id){  
        if($id){
            $title = "News and updates";
            $breadcrumbs = [
                ['name'=>'News and updates','relation'=>'link','url'=>route('front.suggestion-feedback.index')],
                ['name'=>'Edit News and updates','relation'=>'Current','url'=>'']
            ];
        }else{
            $title = "Add News and updates";
            $breadcrumbs = [
                ['name'=>'News and updates','relation'=>'link','url'=>route('front.suggestion-feedback.index')],
                ['name'=>'Add News and updates','relation'=>'Current','url'=>'']
            ];
        }
        $data = ($id)?Feedback::find($id):array();
        if($request->ajax() && $request->isMethod('post')){
            try {
                $rules = [
                    'title'              => 'required',
                    'description'       => 'required|max:1500',                    
                ];
                $validator = Validator::make($request->all(), $rules);
                if($validator->fails()){
                    return response()->json(array('errors' => $validator->messages()), 422);
                }else{
                    $formData = $request->except('image');
                    $formData['created_by'] = Auth::guard('web')->user()->id;
                    if($request->file('image')!==null){
                        // This code use for profile picture upload
                        $destinationPath = '/uploads/suggestion-feedback/';
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
                        
                        NewsUpdate::create($formData);
                        Session::flash('success','News created successfully.');
                    }
                    return ['status' => 'true', 'message' => 'Records updated successfully.'];
                }
            } catch (\Exception $e) {
                return ['status' => false, 'message' => $e->getMessage()];
            } 
        } 
        return view('society-front/suggestion-feedback/add',compact('id','data','title','breadcrumbs'));
    }

    public function index(){ 
        $title = "News and updates";
        $breadcrumbs = [ 
            ['name'=>'News and updates','relation'=>'Current','url'=>'']
        ];
        return view('society-front/suggestion-feedback/index',compact('title','breadcrumbs'));
    }

    public function datatables()
    {
        $pages = Feedback::where('society_id',Auth::guard('web')->user()->id)->get();
        return DataTables::of($pages)
            ->addColumn('action', function ($page) {
               return '<a href="'.route('front.suggestion-feedback.view',$page->id).'" class="btn btn-xs btn-primary"><i class="fas fa-eye"></i> View</a>&nbsp;<a data-link="'.route('front.suggestion-feedback.delete').'" id="delete_'.$page->id.'" onclick="confirm_delete('.$page->id.')" href="javascript:void(0)" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i> Delete</a>';
            })
           
            ->rawColumns(['status','action','image'])
            ->make(true);
    }



    public function feedBackDelete(Request $request)
    {
        if($request->ajax() && $request->isMethod('post')){
            $id = $request->id;
            try{
                $delete = Feedback::where('id','=',$id)->delete();   
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

    
    public function updateFeedbackstatus(Request $request,$id)
    {
        if($request->ajax() && $request->isMethod('post')){
            try{
                $id = $id; 
                $row = Feedback::whereId($id)->first();
                if(!$request->status || $request->status == ''){
                    return ["status"=>"false","message"=>"Status is required."]; 

                }
                 if(!$request->comment || $request->comment == ''){
                    return ["status"=>"false","message"=>"Comment is required."]; 

                }
                $row->status = $request->status;
                $row->comment = $request->comment;
                if($row->save()){
                    return ["status"=>"true","message"=>"Record updated."];    
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




    public function view($id){
        $data = Feedback::where('id',$id)->with('user')->first();
        // return $data->image;
        $ajaxUrll = '';
        if($data){
                $ajaxUrll = route('front.suggestion-feedback.status',$id);

            $title = "Feedback/Suggestion";
            $breadcrumbs = [ 
                ['name'=>"Feedback/Suggestio",'relation'=>'link','url'=>route('admin.users.index')],
                ['name'=>$title,'relation'=>'Current','url'=>'']
            ];
            return view('society-front/suggestion-feedback/add',compact('title','breadcrumbs','data','ajaxUrll'));
        }else{
            return abort(404);
        }
    }
}
