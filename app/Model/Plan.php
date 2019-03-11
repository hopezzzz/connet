<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table 	  = 'plans';
    protected $fillable = ['name','description','minutes_per_month','leads_per_month','features'];

    public function plan_prices()
    {
		return $this->hasMany('\App\Model\PlanPrice','plan_id','id');
	}
	public function plan_price_single()
    {
		return $this->hasOne('\App\Model\PlanPrice','plan_id','id');
	}
    public function billing_Type(){
      return $this->belongsTo('\App\BillingType','billingType','id');
    }

}
