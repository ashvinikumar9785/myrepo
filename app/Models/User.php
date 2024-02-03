<?php

namespace App\Models;

//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject; 
use JWTAuth;
class User extends Authenticatable implements JWTSubject
{
    use Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $appends = ['family_member','society_detail','vehicle_list','visitor_list'];

    protected $fillable = [
        'name', 'email', 'password','city','country_id','profile_picture','mobile_number','dob','gender','user_type','social_type','social_id','token','status','notification','thumb','logo','description','created_by','address','pin_code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
     public function getJWTIdentifier()
    {
        return $this->getKey();
    }
     /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    public static function getProfile($id){
        return User::where('id','=',$id)->with(['country'])->first()->toArray();
    }

    public function country(){
       return $this->belongsTo('App\Models\Country','country_id');
    } 

    public function getFamilyMemberAttribute(){
        return FamilyMember::where('user_id',$this->id)->get();
    }

    public function getVisitorListAttribute(){
        return Visitor::where('user_id',$this->id)->get();
    }
    public function getVehicleListAttribute(){
        return Vehicle::where('user_id',$this->id)->get();
    }
    public function getSocietyDetailAttribute(){
        
        return User::select('name','profile_picture','mobile_number')->where('id',$this->created_by)->first();
    }

    public static function authorizeToken($request)
    {
        $credentials = $request;
        try {
            //DD(JWTAuth::attempt($credentials));
            //if (! $token = JWTAuth::attempt($credentials)) {
            if (! $token = JWTAuth::fromUser($credentials)) {
                return ['status'=>'false','error' => 'invalid_credentials','code'=>'401'];
            }
        } catch (JWTException $e) {
            return ['status'=>'false','error' => 'could_not_create_token','code'=>500];
        }
        return ['status'=>'true','token'=>$token,'code'=>200];
    }
}
