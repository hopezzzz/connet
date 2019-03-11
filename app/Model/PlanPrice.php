<?php

namespace App\Model;
use DB;
use Illuminate\Database\Eloquent\Model;

class PlanPrice extends Model
{
    protected $table 	= 'plan_prices';
    protected $fillable = ['plan_id','amount','currency_id'];

    public function plan_currency()
    {
		    return $this->belongsTo('\App\Model\Country','currency_id','countryId');
	  }

    public function plans()
    {
		    return $this->belongsTo('\App\Model\Plan','plan_id','id');
	  }

    //Ravinder Kaur 01-08-2018
    public static function getPlansOfCountry($countryId = null)
    {
      $plansData =    DB::table('plan_prices')
                      ->join('plans', 'plans.id', '=', 'plan_prices.plan_id')
                      ->select('plans.id', 'plans.name', 'plans.description',
                      'plans.minutes_per_month', 'plans.leads_per_month',
                      'plan_prices.currency_id','plan_prices.amount', 'plan_prices.id as planId')
                      ->where('plan_prices.currency_id', '=', $countryId)
                      ->groupBy('plan_prices.plan_id')
                      ->get();
      return $plansData;
    }
}
