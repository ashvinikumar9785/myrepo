<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use App\Models\Event;
use App\Models\EventImage;

use App\Lib\Helper;
use App\Lib\Uploader;
use Session;
use DataTables;
use Validator; 
use Str; 
use Auth;
use Illuminate\Support\Facades\Cache;


use Illuminate\Support\Facades\Hash;

class EventController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth.user'); 
    }

    public function add(Request $request,$id=null){  
                    
                   

        if($id){
            $title = "Edit Events";
            $breadcrumbs = [
                ['name'=>'Events','relation'=>'link','url'=>route('front.events.index')],
                ['name'=>'Edit Events','relation'=>'Current','url'=>'']
            ];
        }else{
            $title = "Add Events";
            $breadcrumbs = [
                ['name'=>'Events','relation'=>'link','url'=>route('front.events.index')],
                ['name'=>'Add Events','relation'=>'Current','url'=>'']
            ];
        }
        $data = ($id)?Event::find($id):array();
        if($request->ajax() && $request->isMethod('post')){

            try {

                $rules = [
                    'title'              => 'required',
                    'description'        => 'required|max:500',
                    'date'        => 'required',
                    'time'        => 'required',
                    'time'        => 'required',
                    'drive_link'             => 'nullable',          
                    'type'             => 'required',          
                    'image'             => 'nullable|array',
                    'address'             => 'required',
                    'banner'             => 'nullable',
                    'tithi'=>'required',
                    
                   ];
                if(!$id){
                    $rules['banner']  = 'required';
                }
                $validator = Validator::make($request->all(), $rules);
                if($validator->fails()){
                    return response()->json(array('errors' => $validator->messages()), 422);
                }else{
                    if($request->image && count($request->image)>5){
                        return ['status' => false, 'message' => "You can uploads six images."];

                    }
                    $formData = $request->except('image','banner');
                    $formData['society_id'] = Auth::guard('web')->user()->id;
                    if($request->banner) {
                        $destinationPath = '/uploads/events/banner';
                        $responseData = Uploader::doUpload($request->banner,$destinationPath,true,time().rand(10,100)); 
                        if($responseData['status']=="true"){
                            $formData['banner'] = $responseData['file'];
                        }  
                                                 
                    }
                    if($id){     
                        $data->update($formData);
                            $event_id = $id;
                       
                        Session::flash('success','Event updated successfully.');
                         $msg = 'Event created successfully.';

                    }else{
                        $dataEvent = Event::create($formData);
                         $msg = 'Event created successfully.';
                        $event_id = $dataEvent->id;
                        $type = 'EVENT';
                        $message = $request->title." New Event added.";
                        $fcmData = ['event_id'=>$event_id,'type'=>'EVENT'];
                        $getUser = User::where('status',1)->get();
                        if($getUser && count($getUser)>0){
                            foreach($getUser as $user){
                                $this->sentNotification($user->id,$type,$message,$event_id,$fcmData);
                            }
                        }
                         
                        Session::flash('success','Event created successfully.');
                    }
                  if($request->image && count($request->image)>0){
                        $destinationPath = '/uploads/events/';
                        foreach($request->image as $image){
                            if($image){
                                $responseData = Uploader::doUpload($image,$destinationPath,true,time().rand(10,100)); 
                                if($responseData['status']=="true"){
                                    $iamgeData['image'] = $responseData['file'];
                                    $iamgeData['event_id'] = $event_id;
                                    EventImage::create($iamgeData);
                                }   
                            }
                               
                        }
                                                 
                    }
                        return ['status' => 'true', 'message' => $msg];

                }
            } catch (\Exception $e) {
                return ['status' => false, 'message' => $e->getMessage()];
            } 
        } 
        return view('society-front/events/add',compact('id','data','title','breadcrumbs'));
    }

    public function index(){ 

       

        $title = 'Event';
        $items = ['id'=>'sdfdsf','name'=>"sdfdsf"];
       
        $breadcrumbs = [ 
            ['name'=>'Events','relation'=>'Current','url'=>'']
        ];
        return view('society-front/events/index',compact('title','breadcrumbs'));
    }

    public function datatables()
    {
        $pages = Event::where('society_id',Auth::guard('web')->user()->id)->get();
        return DataTables::of($pages)
            ->addColumn('action', function ($page) {
                return '<a href="'.route('front.events.view',$page->id).'" class="btn btn-xs btn-primary"><i class="fas fa-eye"></i> View</a>&nbsp;<a href="'.route('front.events.add',$page->id).'" class="btn btn-xs btn-primary"><i class="fas fa-edit"></i> Edit</a>&nbsp;<a data-link="'.route('front.events.delete').'" id="delete_'.$page->id.'" onclick="confirm_delete('.$page->id.')" href="javascript:void(0)" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i> Delete</a>';
            })
            ->editColumn('status',function($page){
                return Helper::getStatus($page->status,$page->id,route('front.events.status'));
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
            $row = Event::whereId($id)->first();
            $row->status = $row->status=='1'?'0':'1';
            $row->save(); 
            return Helper::getStatus($row->status,$id,route('front.events.status'));
        }
    }  

    public function delete(Request $request)
    {
        if($request->ajax() && $request->isMethod('post')){
            $id = $request->id;
            try{
                $delete = Event::where('id','=',$id)->delete();   
                EventImage::where('event_id','=',$id)->delete();   
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

     public function imagedelete(Request $request,$id)
    {
            try{
                $delete = EventImage::where('id','=',$id)->delete();   
                if($delete){
                    return redirect(route('front.events.index'));
                }else{
                    return redirect(route('front.events.index'));
                } 
            }catch(\Exception $e){
                return redirect(route('front.events.index'));
            }
    }

     public function view($id){
        $data = Event::where('id',$id)->first();
        if($data){
            $title = "Event - " .$data->type;
            $breadcrumbs = [ 
                ['name'=>"Users",'relation'=>'link','url'=>route('front.events.index')],
                ['name'=>$title,'relation'=>'Current','url'=>'']
            ];
            return view('society-front/events/view',compact('title','breadcrumbs','data'));
        }else{
            return abort(404);
        }
    }
}
