<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
     public $table = 'family_members';
    public $timestamps = false;
    // public $table = 'news';

    protected $fillable = [
        'user_id','name','country_code','mobile_number','relation','date_of_birth','education','gender','profile_picture','blood_group'
    ];





   
}
