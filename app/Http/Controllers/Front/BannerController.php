<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Lib\Helper;
use Session;
use DataTables;
use Validator;
use Auth;
use App\Lib\Uploader;

class BannerController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth.user'); 
    }

    public function add(Request $request,$id = null){
        if($id){
            $title = "Edit Banners";
            $breadcrumbs = [
                ['name'=>'Banners','relation'=>'link','url'=>route('society.banners.index')],
                ['name'=>'Edit Banners','relation'=>'Current','url'=>'']
            ];
        }else{
            $title = "Add New Banners";
            $breadcrumbs = [
                ['name'=>'Banners','relation'=>'link','url'=>route('society.banners.index')],
                ['name'=>'Add New Banners','relation'=>'Current','url'=>'']
            ];
        }
       
        $data = ($id)?Banner::find($id):array();
        if($request->ajax() && $request->isMethod('post')){
            try {
                if($id){
                    $validator = Validator::make($request->all(), [
                        'banner'             => 'nullable',     
                   
                    ]);    
                }
                else{
                    $validator = Validator::make($request->all(), [
                    'banner'             => 'required',     
                   
                ]);
                }
                
                if($validator->fails()){
                    return response()->json(array('errors' => $validator->messages()), 422);
                }else{
                    $formData['society_id'] = Auth::guard('web')->user()->id;
                     if($request->file('banner')!==null){
                        // This code use for profile picture upload
                        $destinationPath = '/uploads/banners/';
                        $responseData = Uploader::doBannerUpload($request->file('banner'),$destinationPath,true); 
                        if($responseData['status']=="true"){
                            $formData['banner'] = $responseData['file'];
                            // $formData['thumb'] = $responseData['thumb'];
                        }                             
                    }
                    if($id){
                        $data->update($formData);
                        Session::flash('success','Records updated successfully.');

                        return ['status' => 'true', 'message' => 'Records updated successfully'];
                    } 
                    else{
                        Banner::create($formData);
                        Session::flash('success','Banner uploaded successfully.');

                        return ['status' => 'true', 'message' => 'Banner uploaded successfully'];

                    }
                }
            } catch (\Exception $e) {
                return ['status' => 'false', 'message' => $e->getMessage()];
            }            
        } 
        return view('society-front/banners/add',compact('id','data','title','breadcrumbs'));
    }

    public function index(){ 
        $title = "Banners";
        $breadcrumbs = [ 
            ['name'=>'Banners','relation'=>'Current','url'=>'']
        ];
        return view('society-front/banners/index',compact('title','breadcrumbs'));
    }

    public function datatables()
    {
        $banners = Banner::select(['id', 'banner','status'])->where('society_id',Auth::guard('web')->user()->id)->get();

        return DataTables::of($banners)
            ->addColumn('action', function ($page) {
                return '<a href="'.route('society.banners.add',$page->id).'" class="btn btn-xs btn-primary"><i class="fas fa-edit"></i> Edit</a>';
            })
            ->editColumn('status',function($page){
                return Helper::getStatus($page->status,$page->id,route('society.banners.status'));
            })  
             ->editColumn('banner',function($page){
                return Helper::getImage($page->banner,70);
            }) 
            ->rawColumns(['status','action','banner'])
            ->make(true);
    }

    public function status(Request $request)
    {
        $id = $request->id; 
        $row = Banner::whereId($id)->first();
        $row->status = $row->status=='1'?'0':'1';
        $row->save(); 
        return Helper::getStatus($row->status,$id,route('society.banners.status')); 
    } 

    public function view(Request $request,$slug){
        $page = Banner::where('slug',$slug)->where('status',1)->first();
        if($page){
            return view('society-front/banners/view',compact('page'));
        }else{
            return abort(404);
        }
    } 
}
