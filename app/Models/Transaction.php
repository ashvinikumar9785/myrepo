<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public $timestamps = false;
     public $table = 'transactions';
     protected $appends = ['transaction_type','transaction_type_value'];


    protected $fillable = [
        'society_id','user_id','transaction_id','receipt','amount','status','payment_type'
    ];


    public function getTransactionTypeAttribute(){
         $payment_type = ($this->payment_type)?explode(',', $this->payment_type):[];
         // $ids_to_filter = explode(',', $payment_type);

          
          // return $offer;
        return SocietyCharges::whereIn('id',$payment_type)->get();
        // ->whereRaw("find_in_set('".$search_id."',posts.tag_id)")

    }


    public function user(){
       return $this->belongsTo('App\Models\User','user_id');
    }



     public function getTransactionTypeValueAttribute(){
         $payment_type = ($this->payment_type)?explode(',', $this->payment_type):[];
         // $ids_to_filter = explode(',', $payment_type);

          
          // return $offer;
        $data =  SocietyCharges::whereIn('id',$payment_type)->pluck('title')->toArray();
        return implode(',', $data);
        // ->whereRaw("find_in_set('".$search_id."',posts.tag_id)")

    }

}
