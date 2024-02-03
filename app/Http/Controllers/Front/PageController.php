<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Lib\Helper;
use Session;
use DataTables;
use Validator;
use Auth;

class PageController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth.user'); 
    }

    public function add(Request $request,$id=null){
        if($id){
            $title = "Edit Page";
            $breadcrumbs = [
                ['name'=>'Pages','relation'=>'link','url'=>route('society.pages.index')],
                ['name'=>'Edit page','relation'=>'Current','url'=>'']
            ];
        }else{
            $title = "Add New page";
            $breadcrumbs = [
                ['name'=>'Pages','relation'=>'link','url'=>route('society.pages.index')],
                ['name'=>'Add New Page','relation'=>'Current','url'=>'']
            ];
        }
        $data = ($id)?Page::find($id):array();
        if($request->ajax() && $request->isMethod('post')){
            try {
                $validator = Validator::make($request->all(), [
                    'title'             => 'required|max:255',     
                    'slug'              => 'required|max:255|unique:pages,slug,'.$id,     
                    'content'           => 'required', 
                    'meta_title'        => 'nullable|max:255',
                    'meta_description'  => 'nullable|max:1500'
                ]);
                if($validator->fails()){
                    return response()->json(array('errors' => $validator->messages()), 422);
                }else{
                    $formData = $request->except(['slug']);
                    if($id){ 
                        $checkpage = Page::where('id',$id)->where('society_id',Auth::guard('web')->user()->id)->count();
                        if($checkpage > 0){
                            $data->update($formData);
                            Session::flash('success','Page updated successfully'); 
                        } 
                        else{
                         return ['status' => 'false', 'message' => 'Page not found'];

                        }
                        
                    }else{
                        $formData = $request->all();

                        $formData['society_id'] = Auth::guard('web')->user()->id;
                        $formData['formData'] = $request->slug;
                        Page::create($formData);
                        Session::flash('success','Page created successfully');
                    }
                    return ['status' => 'true', 'message' => 'Records updated successfully'];
                }
            } catch (\Exception $e) {
                return ['status' => 'false', 'message' => $e->getMessage()];
            }            
        } 
        return view('society-front/pages/add',compact('id','data','title','breadcrumbs'));
    }

    public function index(){ 
        $title = "Pages";
        $breadcrumbs = [ 
            ['name'=>'Pages','relation'=>'Current','url'=>'']
        ];
        return view('society-front/pages/index',compact('title','breadcrumbs'));
    }

    public function datatables()
    {
        $pages = Page::select(['id', 'title', 'slug', 'status'])->where('society_id',Auth::guard('web')->user()->id)->get();

        return DataTables::of($pages)
            ->addColumn('action', function ($page) {
                return '<a href="'.route('society.pages.add',$page->id).'" class="btn btn-xs btn-primary"><i class="fas fa-edit"></i> Edit</a>&nbsp;<a href="'.route('society.pages.view',$page->slug).'" class="btn btn-xs btn-primary"><i class="fas fa-eye"></i> View</a>';
            })
            ->editColumn('status',function($page){
                return Helper::getStatus($page->status,$page->id,route('society.pages.status'));
            })   
            ->rawColumns(['status','action'])
            ->make(true);
    }

    public function status(Request $request)
    {
        $id = $request->id; 
        $row = Page::whereId($id)->first();
        $row->status = $row->status=='1'?'0':'1';
        $row->save(); 
        return Helper::getStatus($row->status,$id,route('society.pages.status')); 
    } 

    public function view(Request $request,$slug){
        $page = Page::where('slug',$slug)->where('status',1)->first();
        if($page){
            return view('society-front/pages/view',compact('page'));
        }else{
            return abort(404);
        }
    } 
}
