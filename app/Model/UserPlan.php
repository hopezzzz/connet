<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserPlan extends Model
{
  protected $table 	  = 'user_plans';
  protected $fillable = ['user_id','plan_id'];

  public function users(){
    return $this->belongsTo('App\User','user_id','id');
  }
  public function plans(){
    return $this->belongsTo('App\Model\Plan','plan_id','id');
  }
  public function user_payments(){
    return $this->hasMany('App\Model\UserPayment','custom','user_id');
  }
  public function user_latest_payment(){
    return $this->hasOne('App\Model\UserPayment','custom','user_id')->where('type','subscription')->orderBy('id','DESC');
  }
  // public function stripePlans(){
  //   return $this->belongsTo('App\Model\UserPayment','user_id','custom');
  // }
}
