<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\SocietyCharges;
use App\Lib\Helper;
use App\Lib\Uploader;
use Session;
use DataTables;
use Validator; 
use Str; 
use Auth;

use Illuminate\Support\Facades\Hash;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.user'); 
    }

   
    public function index(){ 
        $count = Transaction::where('society_id',Auth::guard('web')->user()->id)->where('status','PENDING')->count();
        $title = "Transaction";
        $breadcrumbs = [ 
            ['name'=>'News and updates','relation'=>'Current','url'=>'']
        ];
        return view('society-front/transactions/index',compact('title','breadcrumbs','count'));
    }

    public function datatables()
    {
        $pages = Transaction::where('society_id',Auth::guard('web')->user()->id)->with(['user'])->get();
        return DataTables::of($pages)
            ->addColumn('action', function ($page) {
                return '<a data-link="'.route('front.transactions.delete').'" id="delete_'.$page->id.'" onclick="confirm_delete('.$page->id.')" href="javascript:void(0)" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i> Delete</a>';
            })
            ->editColumn('status',function($page){

                return $page->status;
            })
            ->editColumn('user_id',function($page){
                return (@$page->user['name'])?$page->user['name']:'N/A';
            })
             ->editColumn('amount',function($page){
                return SocietyCharges::whereIn('id',explode(',', $page->payment_type))->sum('amount');
            })
             ->editColumn('description',function($page){
                return $page->transaction_type_value;
            })
             ->editColumn('mark_as_complete',function($page){
                return '<a href="'.route('front.transactions.mark-complete',$page->id).'" class="btn btn-xs btn-primary"><i class="fas fa-right"></i> PAID</a>';
             })
            ->rawColumns(['status','action','user_id','amount','description','mark_as_complete'])
            ->make(true);
    }

    public function status(Request $request)
    {
        if($request->ajax() && $request->isMethod('post')){
            $id = $request->id; 
            $row = NewsUpdate::whereId($id)->first();
            $row->status = $row->status=='1'?'0':'1';
            $row->save(); 
            return Helper::getStatus($row->status,$id,route('front.transactions.status'));
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
    public function markComplete($id)
    {
       $page = Transaction::where('society_id',Auth::guard('web')->user()->id)->where('id',$id)->first();
       $page->status = 'PAID';
       $page->save();
       if($page){
        Session::flash('success','status changed successfully.');
            return redirect(route('front.transactions.index'));
        }else{
        Session::flash('success','status changed successfully.');
            
            return redirect(route('front.transactions.index'));
        } 
    }
}
