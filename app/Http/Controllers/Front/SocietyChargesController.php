<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SocietyCharges;
use App\Lib\Helper;
use Session;
use DataTables;
use Validator;
use Auth;
use App\Lib\Uploader;

class SocietyChargesController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth.user'); 
    }

    public function add(Request $request,$id = null){
        if($id){
            $title = "Edit Society Charges";
            $breadcrumbs = [
                ['name'=>'Society Charges','relation'=>'link','url'=>route('society.society-charges.index')],
                ['name'=>'Edit Society Charges','relation'=>'Current','url'=>'']
            ];
        }else{
            $title = "Add New Society Charges";
            $breadcrumbs = [
                ['name'=>'Society Charges','relation'=>'link','url'=>route('society.society-charges.index')],
                ['name'=>'Add New Society Charges','relation'=>'Current','url'=>'']
            ];
        }
       
        $data = ($id)?SocietyCharges::find($id):array();
        if($request->ajax() && $request->isMethod('post')){
            try {
                $rules = [
                    'title'              => 'required',
                    'description'              => 'required',
                    'amount'              => 'required',
                   
                   // 'password' =>'required|min:8|max:20',
                   //  'confirm_password'=>'required|min:8|max:45|same:password', 
                ];
                if($id){
                    $rules['image']  = 'nullable';  
                }
                else{
                $rules['image']  = 'required';  

                }
                $validator = Validator::make($request->all(), $rules);
                
                if($validator->fails()){
                    return response()->json(array('errors' => $validator->messages()), 422);
                }else{
                    $formData = $request->except('image');
                    $formData['society_id'] = Auth::guard('web')->user()->id;
                     if($request->file('image')!==null){
                        // This code use for profile picture upload
                        $destinationPath = '/uploads/society-charges/';
                        $responseData = Uploader::doBannerUpload($request->file('image'),$destinationPath,true); 
                        if($responseData['status']=="true"){
                            $formData['image'] = $responseData['file'];
                            // $formData['thumb'] = $responseData['thumb'];
                        }                             
                    }
                    if($id){
                        $data->update($formData);
                        Session::flash('success','Records updated successfully.');

                        return ['status' => 'true', 'message' => 'Records updated successfully'];
                    } 
                    else{
                        SocietyCharges::create($formData);
                        Session::flash('success','Society Charges added successfully.');

                        return ['status' => 'true', 'message' => 'Society Charges added successfully'];

                    }
                }
            } catch (\Exception $e) {
                return ['status' => 'false', 'message' => $e->getMessage()];
            }            
        } 
        return view('society-front/society-charges/add',compact('id','data','title','breadcrumbs'));
    }

    public function index(){ 
        $title = "SocietyCharges";
        $breadcrumbs = [ 
            ['name'=>'Society Charges','relation'=>'Current','url'=>'']
        ];
        return view('society-front/society-charges/index',compact('title','breadcrumbs'));
    }

    public function datatables()
    {
        $societycharges = SocietyCharges::select(['id', 'title','amount','status'])->where('society_id',Auth::guard('web')->user()->id)->get();

        return DataTables::of($societycharges)
            ->addColumn('action', function ($page) {
                return '<a href="'.route('society.society-charges.add',$page->id).'" class="btn btn-xs btn-primary"><i class="fas fa-edit"></i> Edit</a>';
            })
            ->editColumn('status',function($page){
                return Helper::getStatus($page->status,$page->id,route('society.society-charges.status'));
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
        $row = SocietyCharges::whereId($id)->first();
        $row->status = $row->status=='1'?'0':'1';
        $row->save(); 
        return Helper::getStatus($row->status,$id,route('society.society-charges.status')); 
    } 

    public function view(Request $request,$slug){
        $page = SocietyCharges::where('slug',$slug)->where('status',1)->first();
        if($page){
            return view('society-front/society-charges/view',compact('page'));
        }else{
            return abort(404);
        }
    } 
}
