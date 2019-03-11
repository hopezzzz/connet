<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Region;
use App\Model\PlanPrice;
use App\Model\Country;
use App\Model\UserPlan;
use App\Model\Plan;
use App\Model\UserPayment;
use App\User;
use Auth;
use DB;
class PurchaseController extends Controller
{

  public function __construct()
  {
  		$this->customertemplatename = config('app.customertemplatename');
  }
  public function index(Request $request)
	{

      $perPage = 10;
      $userId          = Auth::user()->id;
      $stripe_cust_id  = Auth::user()->stripe_cust_id;
      $purchaseHistory = UserPayment::where('customer_id',$stripe_cust_id)->where('type','subscription')->orderBy('id','DESC')->groupBy('invoice_id')->paginate($perPage,['*'],'purchase');
      $charge          = UserPayment::where('customer_id',$stripe_cust_id)->where('type','charge')->orderBy('id','DESC')->paginate($perPage,['*'],'charge');
      // echo "<pre>";
      // print_r($purchaseHistory);
      $purchase = $charges= 0;
      if ($request->Ajax()) {
        $purchase  =  (isset($request['purchase']) ) ? (  $request['purchase'] - 1 ) * $perPage : 0;
        $charges   =  (isset($request['charge']) ) ? (  $request['charge'] - 1 ) * $perPage : 0;
        return view(	$this->customertemplatename.'/purchase.recordListAjax',['charges'=>$charges,'purchase'=> $purchase,'purchaseHistory'=>$purchaseHistory,'charge'=>$charge]);
      }else{
        return view(	$this->customertemplatename.'/purchase.index',['purchaseHistory'=>$purchaseHistory,'charge'=>$charge]);
      }

	}

  public function plansListing(Request $request)
  {
    $res = User::with('user_campaigns.campaigns_contacts')->where('id',\Auth::user()->id)->first();
    $addedCon = array();
    foreach($res->user_campaigns as $r){
      foreach ($r->campaigns_contacts as $v){
        $addedCon[] = $v->custId; 
      }
    }
    $addCon           = array_unique($addedCon);
    $userId           = Auth::user()->id;
    $stripe_cust_id   = Auth::user()->stripe_cust_id;
    $planId           = UserPlan::where('user_id',$userId)->first();
    $resultData  	    = Region::where('id',Auth::user()->country)->first();
  	if(!empty($resultData))
  	{
  		 $currency 	 					= 	$resultData->currency;
       // checking if user status is inactive then show all plans to user
       if(\Auth::user()->status ==9){
          $whereData = [ ['currency_id', '=', $currency ], ['is_deleted', '=', 0 ], ];
        }else{
          $whereData = [ ['currency_id', '=', $currency ], ['is_deleted', '=', 0 ], ['plan_id', '!=', $planId->plan_id ] ];
        }
  			$plansData 	 = 	PlanPrice::whereHas('plans', function ($query) {
            $query->where('billingType', '=', 2)->where('is_deleted','=',0);
        })->where($whereData)->get();
  	}
    // \DB::enableQueryLog();
    $userPlan = UserPayment::where('customer_id',$stripe_cust_id)->where('type','subscription')->orderBy('id','DESC')->first();
    // dd(\DB::getQueryLog());

    return view(	$this->customertemplatename.'/plansListing.index',['planId'=>$planId,'userPlan'=>$userPlan,'plansData'=>$plansData,'campContact'=>$addCon]);

  }
}
