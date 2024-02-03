<?php
namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Hash;
use Auth;

class UsersImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {  
            $emailCount = 0; 
            if(isset($row[1])){
                $emailCount = User::where('email',$row[1])->count();
            }
            $mobileCount = 0; 
            if(isset($row[2])){
                $mobileCount = User::where('mobile_number',$row[2])->count();
            }
            if($emailCount == 0 && $mobileCount == 0){
                User::create([
                    'name' => $row[0],
                    'email' => ($emailCount == 0 && $row[1])?$row[1]:'',
                    'mobile_number' => ($mobileCount == 0 && $row[2])?$row[2]:'',
                    'address' => (isset($row[3]))?$row[3]:'',
                    'pin_code' => (isset($row[4]))?$row[4]:'',
                    'created_by'=> Auth::guard('web')->user()->id,
                    'password'=> (isset($row[5]))?Hash::make($row[5]):Hash::make('Cvs@1234#'),
                    'blood_group' => (isset($row[6]))?$row[6]:'',
                    'owner_type' => (isset($row[7]))?$row[7]:'',


                ]);
            }
            
        }
    }
}