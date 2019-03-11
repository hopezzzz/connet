<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstName','lastName', 'verified','email', 'password','enc_password','phoneNo','companyName','companyUrl','country',
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
     * App\Role relation.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    public function plans()
    {
        return $this->belongsToMany('App\Model\Plan');
    }
     public function verifyUser()
    {
        return $this->hasOne('App\VerifyUser');
    }
    public function region(){
        return $this->belongsTo('App\Model\Region','country','id');
    }
    public function user_plan(){
        return $this->hasOne('App\Model\UserPlan','user_id','id');
    }
    public function user_payments(){
        return $this->hasMany('App\Model\UserPayment','custom','id');
    }
    public function user_campaigns(){
        return $this->hasMany('App\Model\Campaign');
    }
    public function coutry_details()
    {
          return $this->belongsTo('App\Model\Country','country','countryId');
    }
    public function user_contacts()
    {
        return $this->hasMany('\App\Model\Contact','user_id','id');
    }
    public function call_settings(){
        return $this->belongsTo('App\Model\CallSetting','id','user_id');
    }
}
