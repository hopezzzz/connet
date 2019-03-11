<?php

namespace App\Model;
use DB;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
	protected $table 	= 'countries';
  protected $fillable = ['countryId','countryShort','countryName','flag','status','countryPhoneCode','currencyName','currencyCode','currencySymbol'];

	/*public static function fetchRecords()
	{
		$users = User::where('votes', '>', 100)->simplePaginate(15);
	}*/
	

	public static function checkUserEmail($email)
	{
		$Data = DB::table('users')
				->select('email')
				->where('email', '=', $email)
				->where('register_step', '=', 3)
				->get();
		return $Data;
	}

}
