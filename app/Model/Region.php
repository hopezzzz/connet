<?php

namespace App\Model;
use DB; //Ravinder 1 august use This funciton (use DB or \in front of DB)
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
	protected $table 	= 'regions';
    protected $fillable = ['name','currency'];

	/*public static function fetchRecords()
	{
		$users = User::where('votes', '>', 100)->simplePaginate(15);
	}*/
	public function  plan_prices()
	{
		return $this->belongsTo('\App\Model\PlanPrice','currency','currency_id');
	}

	public function  countries()
	{
		return $this->belongsTo('\App\Model\Country','currency','countryId');
	}

	//Ravinder Kaur 01-08-2018
	public static function getRegionCurrency($regionId = null)
	{
		$regionData = DB::table('regions')
							 ->select('currency')
							 ->where('id', '=', $regionId)
							 ->get();
		return $regionData;
	}
}
