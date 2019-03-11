<?php
namespace App\Http\Controllers;
require_once app_path().'/Services/braintree/lib/Braintree.php';
require_once app_path().'/Services/PayPal-PHP-SDK/autoload.php';
// require_once app_path().'/Services/html2text/autoload.php';
require_once app_path().'/Services/html2textt/autoload.php';

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Region;
use App\Model\PlanPrice;
use App\Model\Country;
use App\Model\UserPlan;
use App\Model\Parsetag;
use App\Model\Campaign;
use App\Model\Plan;
use App\Role;
use DB;
use App\Model\UserPayment;
use App\User;
use Validator;
use Crypt;
use Session;
use Hash;
use Curl;
use App\Helpers\GlobalFunctions as Helpers;
use Illuminate\Validation\Rule;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Cartalyst\Stripe\Api\Charges;
use Stripe\Error\Card;
Use App\Model\ParseEmail;

class UserController extends Controller
{

		public function __construct()
		{
			$this->domainEmail = config('app.domainEmail');
			$this->domain = config('app.domain');
			$this->frontendtemplatename = config('app.frontendtemplatename');
		}


	public function index(Request $request)
	{
	    $r=Region::where('is_deleted',0);
	    $regions = $r->whereHas('plan_prices',function($plan_price){
	    	$plan_price->where('is_deleted',0)->whereHas('plans',function($q){
	    		$q->where('is_deleted',0);
	    	});
	    })->pluck('name','id')->prepend('Please Select','');
		return view($this->frontendtemplatename.'.registration.index',['regions'=>$regions]);
	}

	//Ravinder Kaur 01-08-2018
	public function getRegionPlansByCountryId(Request $request)
	{
		if($request->ajax())
		{
		 	$postData = array_except($request->all(), ['_token']);
			if(!empty($postData))
			{
				$regionId 			= 	$postData['regionId'];
				$resultData  		= 	Region::where('id',$regionId)->first();

				if(!empty($resultData))
				{
					$currency 	 		= $resultData->currency;
				    $pd=PlanPrice::where('is_deleted',0)->where('currency_id',$currency)->whereHas('plans',function($d){
				    	$d->where('is_deleted',0);
				    });
					$pd->with('plans','plan_currency');
					$plansData = $pd->get();
					$html['html'] 	= view($this->frontendtemplatename.'.registration.plans',compact('plansData'))->render();
					echo json_encode($html);exit;
				}
			}
		}
	}
	public function getRegionPlansByCountryIdWithParam($id)
	{
		$regionId 			= $id;
		$resultData  		= 	Region::where('id',$regionId)->first();

		if(!empty($resultData))
		{
			$currency 	 					= 	$resultData->currency;
			$plansData 						= 	PlanPrice::with('plans')->where('currency_id',$currency)->where('is_deleted',0)->get();
			$html['html'] 				= 	view($this->frontendtemplatename.'.registration.plans',compact('plansData'))->render();
			echo json_encode($html);exit;
		}
	}

	public function checkUserEmailExists(Request $request)
	{
		if($request->ajax())
		{
		 	$postData = array_except($request->all(), ['_token']);
			if(!empty($postData))
			{
				$Email 				= 	$postData['Email'];
				$resultData 		= 	\App\Model\Country::checkUserEmail($Email);
				$exists 				= 	2;
				if(!empty($resultData[0]))
				{
					$exists = 1;
				}
				else
				{
					$result = Curl::to(env('API_BASEPATH').'clientCreate/?email='.$postData['Email'])
					->withContentType('application/json')
					->withOption('USERPWD', env('ADMIN_USER') . ":" . env('ADMIN_PASS'))
					->returnResponseObject()->asJson()->get();
                    if($result->content->meta->total_count > 0)
                    {
                    	$exists = 1;
                    }
				}
				$html['html'] = $exists;
				echo json_encode($html);exit;
			}
		}
	}

	public function getPlanInfoWithClientID(Request $request, $id = null)
	{
		if($request->ajax())
		{
			 	$postData = array_except($request->all(), ['_token']);
				if(!empty($postData))
				{
					$user_id 			=  	$postData['userID'];;
					$plan_id 			= 	$postData['planid'];;
					$payId 				= 	$postData['payId'];;
					$dt						=		UserPlan::where('user_id',$user_id)->where('plan_id',$plan_id);
					$dt->with(['users.region.countries']);
					$currency = (int)$dt->first()->users->region->currency;
					$dt->with(['plans.plan_prices'=>function($p) use ($currency){
						$p->select('*')->where('currency_id',$currency);
					}]);
					$payMentData=$dt->first();
					if(!empty($payMentData))
					{
						$html['html'] 				= 	view($this->frontendtemplatename.'.registration.payment',compact('payMentData'))->render();
						echo json_encode($html);exit;
					}
				}
		}else{

		}
	}

	//Monthly Payment
	public function payPalMonthlyRedirect(Request $request,$amount,$planname,$userid){
		$amountt 					= Crypt::decrypt($amount);
		$plannames 				= Crypt::decrypt($planname);
		$paypal_url 			= 'https://www.sandbox.paypal.com/cgi-bin/webscr';
	  $cancel_return 		= url('cancel-payment');
	  $success_return 	= url('success-payment');
	  $businessEmail	 	= "oneway@gmail.com";
    //paypal call this file for ipn
  //  $notify_url = "http://".$_SERVER['HTTP_HOST'].'/paypal-ipn-php/ipn.php';

	  ?>
	  <form   name="paypalsubmit" action = "<?php echo $paypal_url; ?>" method = "post" target = "_top">
	  <input 	type="hidden" name="cmd" value="_xclick-subscriptions">
	  <input 	type="hidden" name="business" value="<?php echo $businessEmail; ?>">
	  <input 	type="hidden" name="item_name" value="<?php echo $plannames; ?> ">
	  <input 	type="hidden" name="no_note" value="1">
	  <input 	type="hidden" name="src" value="1">
	  <input 	type="hidden" name="a3" value="<?php echo $amountt; ?>">
	  <input 	type="hidden" name="p3" value="1">
	  <input 	type="hidden" name="t3" value="M">
	  <input 	type="hidden" name="custom" value="<?php echo $userid; ?>" id="customerid">
	  <input 	type="hidden" name="currency_code" value="USD">
	  <input 	type="hidden" name="cancel_return" value="<?php echo $cancel_return ?>">
	  <input 	type="hidden" name="return" value="<?php echo $success_return; ?>">
	  <input 	type="hidden" name="notify_url" value="http://cashmann.co.uk/v1/paypal.php">
	  <input 	type="hidden" name="bn" value="PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHostedGuest">
	  </form>
		<script type="text/javascript">
	  document.paypalsubmit.submit();
	  </script>
	  <?php
	}

	public function cancelPayment()
	{
		echo '<h1>Welcome, User</h1>';
		echo '<h2>For some reason payment has been cancelled known to you.</h2>';
	}

	public function successPayment(Request $request)
	{
		$paymentResponse = array('success' => false, 'playments' => 'failed','error_message' => 'invalid User or user not found on this branch..' );
	  	$raw_post_data 	= file_get_contents('php://input');
	    $raw_post_data 	= (json_decode($raw_post_data));
	    $userId 		= User::where('stripe_cust_id',$raw_post_data->data->object->customer)->first();
			if ($userId)
			{
				$userPayments = UserPayment::where('custom',$userId->id)->count();
				$stripe['amount']           = $raw_post_data->data->object->total / 100;
			    $stripe['amount_paid']      = $raw_post_data->data->object->amount_paid / 100;
			    $stripe['invoice_id']       = $raw_post_data->data->object->number;
			    $stripe['customer_id']      = $raw_post_data->data->object->customer ;
			    $stripe['custom']      		  = $userId->id ;
			    $stripe['charge_id']        = $raw_post_data->data->object->charge ;
			    $stripe['currency']         = $raw_post_data->data->object->currency ;
			    $stripe['invloice_pdf']     = $raw_post_data->data->object->invoice_pdf ;
			    $stripe['plan_id']          = $raw_post_data->data->object->lines->data[0]->plan->id ;
			    $stripe['product_id']       = $raw_post_data->data->object->lines->data[0]->plan->product ;
			    $stripe['qty']              = $raw_post_data->data->object->lines->data[0]->quantity ;
			    $stripe['sub_id']           = $raw_post_data->data->object->lines->data[0]->subscription ;
			    $stripe['sub_item_id']      = $raw_post_data->data->object->lines->data[0]->subscription_item ;
			    $stripe['type']             = $raw_post_data->data->object->lines->data[0]->type ;
			    $stripe['startDate']        = $raw_post_data->data->object->lines->data[0]->period->start ;
			    $stripe['endDate']          = $raw_post_data->data->object->lines->data[0]->period->end ;
			    $stripe['date']        			= $raw_post_data->data->object->date;
			    $stripe['due_date']         = $raw_post_data->data->object->due_date ;
					$stripe['payment_status']   = $raw_post_data->data->object->paid ;
			    $stripe['completePayment']  = json_encode($raw_post_data) ;
			    $stripe['status']           = 'Active';
			    $ss = UserPayment::insert($stripe);
			    if($userPayments > 0)
			    {
			    	//Plan Upgrade / Downgrade Mail
			    }
			    else
			    {
			    	//Send Mail to New User
			    	$name = $userId->firstName.' '.$userId->lastName;
				    $patterns = $replacements = $emailstring = array();
					$templates     = DB::table('templates')->where('id',1)->first();
					$string        = $templates->content;
					$patterns 	   = array('/{CustomerName}/');
					$replacements  = array($name);
					$title 		   = 'Welcome - Direct Connect';
					$emailstring   = Helpers::emailReplacement($patterns, $replacements, $string);
					$emailstring['title'] = $title;
					Helpers::sendmail(env('MAIL_FROM'),env('APP_NAME'),$userId->email,$name,$title,$emailstring,'emails.email');
			    }
				User::where('stripe_cust_id',$raw_post_data->data->object->customer)->update(['status'=>1]);
				$paymentResponse = array('success' => true, 'playments' => 'recived','success_message' => 'Payment recived successfully..' );
			}
			// self::checkBalanceUser();
      return response( $paymentResponse );exit;
	}

	public function subscriptionCancelled(Request $request)
	{
	    $raw_post_data 	= file_get_contents('php://input');
	    $raw_post_data 	= (json_decode($raw_post_data));

	    $subscription['id'] = $raw_post_data->data->object->id;
	    $subscription['customer'] = $raw_post_data->data->object->customer;

	    $updateArray = array(
	        'status' => 'Canceled',
	        'cancelDate'=>$raw_post_data->data->object->canceled_at
	    );
// 		\DB::enableQueryLog();
			$UserPaymentup = UserPayment::where('sub_id',$raw_post_data->data->object->id)->update($updateArray);
			if ($UserPaymentup) {
				$res = array('success' => true, 'playments' => 'updated','success_message' => 'User Payment Updated successfully..' );
			}else{
				$res = array('success' => false, 'playments' => 'failed','error_message' => 'User Payment not updated..' );
			}
    //   echo '<pre>'; print_r(\DB::getQueryLog());

			$userUpdate = User::where('stripe_cust_id',$raw_post_data->data->object->customer)->update(['status' => '9']);
			if ($userUpdate) {
				$res1 = array('success' => true, 'userUpdate' => 'updated','user_success_message' => 'User Updated successfully..' );
			}else{
				$res1 = array('success' => false, 'playments' => 'failed','user_error_message' => 'User not updated..' );
			}
			$user = User::where('stripe_cust_id',$raw_post_data->data->object->customer)->first();
			if ($user) {
				// $plan_prices = PlanPrice::where('stripe_plan_id',$raw_post_data->data->object->items->data[0]->plan->id)->first();
	    	$name 								= $user->firstName.' '.$user->lastName;
	    	$planName 						= $user->user_plan->plans->name;
	    	$regionName 					= $user->region->name;
		    $patterns 						= $replacements = $emailstring = array();
				$templates     				= DB::table('templates')->where('id',2)->first();
				$string        				= $templates->content;
				$patterns 	   				= array('/{CustomerName}/','/{PlanName}/','/{RegionSelected}/');
				$replacements  				= array($name,$planName,$regionName);
				$title 		   					= 'Plan Cancellation - Direct Connect';
				$emailstring   				= Helpers::emailReplacement($patterns, $replacements, $string);
				$emailstring['title'] = $title;

				// if ($plan_prices) {
				// 	UserPlan::where('user_id',$user->id)->where('plan_id',$plan_prices->plan_id)->delete();
				// }
				//Send Mail to Client
				Helpers::sendmail(env('MAIL_FROM'),env('APP_NAME'),$user->email,$name,$title,$emailstring,'emails.email');
			}
     $responseArray = array_merge($res ,$res1 );
		 return response( $responseArray );
	}


	public function saveClient( Request $request )
  	{
        if($request->ajax())
        {
			$postDatas = array_except($request->all(), ['_token']);
			if($postDatas['userIDCheck'] == "")
			{
				$validator = Validator::make($request->all(), [
						'firstName' 		 	=> 'required',
						'email' 		 	 		=> 'required|unique:users',
						'phoneNo'  			 	=> 'min:10',
						'password'  	    => 'required|min:6',
						'confirmPassword' => 'required|min:6|same:password',
						'companyUrl'		 	=> ['nullable', 'regex:/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/'],
				]);
			}
			else
			{
				$validator = Validator::make($request->all(), [
						'firstName' 		 => 'required',
						'email'     => 'required', Rule::unique('users', 'email')->ignore($postDatas['userIDCheck']),
						'email' 		 	 	 => 'required',
						'phoneNo'  			 => 'min:6',
						'password'  	   => 'required|min:6',
						'confirmPassword'=> 'required|min:6|same:password',
						'companyUrl'		 => ['nullable', 'regex:/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/'],
				]);
			}
			if($validator->fails())
			{
				$errors 	 = $validator->errors()->messages();
				$finalErrors = array();
				foreach ( $errors as $key=>$message )
				{
					$finalErrors[$key] = $message[0];
				}
				$response['success']    = false;
              	$response['formErrors'] = true;
              	$response['tabForm']    = true;
              	$response['errors']     = $finalErrors;
		    }
			else
			{
	      $postData = array_except($request->all(), ['_token']);
				if(!empty($postData))
				{
					if($postData['formStep'] == 'step1')
					{
						$fstsp = $userID = "";
						if($postData['userIDCheck'] != "")
						{
							$userIds = $postData['userIDCheck'];
							$userUpdate = array(
								'firstName'		=>	$postData['firstName'],
								'lastName'		=>	$postData['lastName'],
								'email'			=>	$postData['email'],
								'password'		=> 	Hash::make($postData['password']),
								'enc_password'  => 	Crypt::encrypt($postData['password']),
								'phoneNo'		=>	$postData['phoneNo'],
								'companyName'	=>	$postData['companyName'],
								'companyUrl'	=>	$postData['companyUrl'],
								'country'		=>	$postData['country']
							);
							$user = User::where('id', '=',$userIds);
							$user->update($userUpdate);
							$userID	 	= 	$userIds;
							$fstsp 		=  "First step updated successfully.";
						}
						else
						{
							$pwd = $postData['password'];
							$postData['password'] = Hash::make($postData['password']);
							$postData['enc_password'] = Crypt::encrypt($pwd);
							$result   = User::create($postData);
				           	if($result->wasRecentlyCreated)
			    	   		{
								$userID	 =	User::orderBy('id', 'desc')->first()->id;
								$stepUser = array('register_step' => 1);
							 	$user = User::where('id', '=',$userID);
								$user->update($stepUser);
								$role=Role::where('name','customer')->first();
								DB::table('role_user')->insert(array('role_id'=>$role->id,'user_id'=>$userID));
								$fstsp = "First step created successfully.";
			    	   		}
						}
						$response['stepType']        	=  'firstStep';
						$response['userID']        		=  $userID;
						$response['success']         	=  true;
						$response['success_message'] 	=  $fstsp;
						return response($response);
					}
					if($postData['formStep'] == 'step2')
					{
						if(isset($postData['choosePlanId']) && !empty($postData['userIDCheck']))
						{
							$plan = "";
							$userId = $postData['userIDCheck'];
							$planAddData = array( 'plan_id'	=>$postData['choosePlanId'],'user_id'	=>$userId);
							$getAlreadyObtainPlain = UserPlan::where('user_id',$userId)->get()->toArray();
							if(!empty($getAlreadyObtainPlain))
							{
								$userPlan = UserPlan::where('user_id', '=',$userId);
								$userPlan->update($planAddData);
								$plan = "Second step updated successfully.";
							}
							else
							{
								$result   = UserPlan::create($planAddData);
		           				if($result->wasRecentlyCreated)
		    	   				{
											$response['lastCreatedId']	 =	UserPlan ::orderBy('id', 'desc')->first()->id;
											$stepUser = array('register_step' => 2);
										 	$user = User::where('id', '=',$userId);
										 	$user->update($stepUser);
										 	$plan = "Second step created successfully.";
			    	   			}
							}
							$response['stepType']        	=  'secondStep';
							$response['userID']        		=  $userId;
							$response['planID']        		=  $postData['choosePlanId'];
							$response['payId']						=	 $postData['payId'];
							$response['success']          =  true;
							$response['success_message']  =  $plan;
							return response($response);
						}
						else if (isset($postData['countTotalPlans']) && $postData['countTotalPlans'] > 0)
						{
							$response['success']         	=  false;
							$response['error_message'] 		=  "Please choose any plan first.";
						}
						else
						{
							$response['success']         	=  false;
							$response['error_message'] 		=  "No plans found..";
						}
						return response($response);
					}
					if($postData['formStep'] == 'step3')
					{

						$userId = $postData['userIDCheck'];

						$stripe = \Stripe::make(env('STRIPE_SECRET'),env('STRIPE_VERSION'));
						$exp = explode('/', $postData['ccExpiryMonth']);
						$userId = $postData['userIDCheck'];
						$getAlreadyObtainPlain = PlanPrice::with('plans','plan_currency')->where('stripe_plan_id',$postData['payId'])->first();
						// echo "<pre>";
						// echo $getAlreadyObtainPlain->plans->billingType;
						// print_r($getAlreadyObtainPlain->toArray());
						// die;
						if (!$getAlreadyObtainPlain) {
							$error['success'] 	 = false;
							$error['error_message']     = 'Something went wrong with your selected plan please chose another one try again..';
							return response($error);die;
						}
						try {
							$token = $stripe->tokens()->create([
							'card' => [
										'name'      => $postData['cardHolderName'],
										'number'    => preg_replace('/\s+/', '', $postData['cardNumber']),
										'exp_month' => trim($exp[0]),
										'cvc'       => $postData['cvvNumber'],
										'exp_year'  => trim($exp[1]),
									],
							]);
              if (!isset($token['id'])){
				$error['success'] 	 = false;
				$error['error_message']     = 'The Stripe Token was not generated correctly';
              }else{
				try {
						$stripe_cust_id = User::where('id', '=',$userId)->first();
						if (!empty($stripe_cust_id) && trim($stripe_cust_id->$stripe_cust_id) !='') {
							$customer['id'] = $stripe_cust_id->$stripe_cust_id;
						}else{
							$customer = $stripe->customers()->create([
							    'email' => $postData['email'],
							    'source' => $token['id'],
							]);
							if (!empty($customer)) {
								$stepUser = array('stripe_cust_id'=> $customer['id']);
								$user = User::where('id', '=',$userId);
								$user->update($stepUser);
							}
						}
						try {
								// Code for trail package where no renew only one time payment
								if ($getAlreadyObtainPlain->plans->billingType == 3) {
									$subscription = $stripe->subscriptions()->create( $customer['id'], [
										'plan' => $getAlreadyObtainPlain->stripe_plan_id,
										'cancel_at_period_end' => true,
								]);
							}else
							{
								$subscription = $stripe->subscriptions()->create( $customer['id'],['plan' => $getAlreadyObtainPlain->stripe_plan_id]);
							/**
							 * Code for pay per mintue plan where extra payement like Mobile Charges
							 * eg. Plan Subscrition $49
							 * and for account bal is $150
							 * call charges deducted from $150
							 **/
							if ($getAlreadyObtainPlain->plans->billingType == 2)
							{
									try {
										$charge = $stripe->charges()->create([
								    'customer' => $customer['id'],
								    'currency' => $getAlreadyObtainPlain->plan_currency->currencyCode,
								    'amount'   => $getAlreadyObtainPlain->credit,
										]);
										if (!empty($charge)) {
											$stepUser = array(
												'accout_bal'		=> $getAlreadyObtainPlain->credit,
												'balanceAmount' => $getAlreadyObtainPlain->credit
											);
											$user = User::where('id', '=',$userId);
											$user->update($stepUser);
											$stripeCharge['amount']           = $charge['amount'] / 100;
											$stripeCharge['amount_paid']      = $charge['amount'] / 100;
											$stripeCharge['invoice_id']       = $charge['balance_transaction'];
											$stripeCharge['customer_id']      = $charge['customer'];
											$stripeCharge['custom']      			= $userId ;
											$stripeCharge['charge_id']        = $charge['id'];
											$stripeCharge['currency']         = $charge['currency'];
											$stripeCharge['invloice_pdf']     = '';
											$stripeCharge['plan_id']          = '';
											$stripeCharge['product_id']       = '';
											$stripeCharge['qty']              = '';
											$stripeCharge['sub_id']           = '';
											$stripeCharge['sub_item_id']      = '';
											$stripeCharge['type']             = 'charge';
											$stripeCharge['startDate']        = $charge['created'];
											$stripeCharge['endDate']          = $charge['created'];
											$stripeCharge['date']        			= $charge['created'];
											$stripeCharge['due_date']         = $charge['created'];
											$stripeCharge['payment_status']   = $charge['paid'] ;
											$stripeCharge['completePayment']  = json_encode($charge) ;
											$stripeCharge['status']           = 'succeeded';
											$ss = UserPayment::insert($stripeCharge);
										}
								}catch (\Exception $e) {
									$str = $e->getMessage();
									$error['success'] 	 			  = false;
									$error['error_message']     = $str;
								}
							}

						} // end else
						if (!empty($subscription)) {
							$response['stepType']        	=  'fourthStep';
							$response['success']          =  true;
							$response['success_message']  =  'Payment Success';
							$stepUser = array('register_step' => 3 , 'stripe_cust_id'=> $customer['id']);
							$user 		= User::where('id', '=',$userId);
							$user->update($stepUser);
						}
						} catch (\Exception $e) {
							$str = $e->getMessage();
							$error['success'] 	 = false;
							$error['error_message']     = $str;
						}
				} catch (\Exception $e) {
					$str = $e->getMessage();
					$error['success'] 	 = false;
					$error['error_message']     = $str;
				}
              }
            } catch (Exception $e) {
                $str = $e->getMessage();
								$error['success'] 	 = false;
								$error['error_message']     = $str;
            } catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {
                $str = (string) $e->getMessage();
                if (trim(strtolower("Your card's security code is invalid.")) == trim(strtolower($str)) ) {
                 $str = 'Your CVC number is incorrect.';
								 $array  = array('cvvNumber' => $str, );
							 }else if(trim(strtolower("Your card number is incorrect.")) == trim(strtolower($str))){
								 $array  = array('cardNumber' => $str, );
							 }else{
								  $array  = array('ccExpiryMonth' => $str, );
							 }
								$error['formErrors'] = true;
								$error['success'] 	 = false;
								$error['errors']     = $array;
            } catch(\Cartalyst\Stripe\Exception\MissingParameterException $e) {
                $str = $e->getMessage();
								$error['success'] 	 = false;
								$error['error_message']     = $str;
            }
            catch(\Stripe\Error\Card $e) {
              // Since it's a decline, \Stripe\Error\Card will be caught
							  $str = $e->getMessage();
								$error['success'] 	 = false;
								$error['error_message']     = $str;
            } catch (\Stripe\Error\RateLimit $e) {
              // Too many requests made to the API too quickly
                $str = $e->getMessage();
								$error['success'] 	 = false;
								$error['error_message']     = $str;
            } catch (\Stripe\Error\InvalidRequest $e) {
              // Invalid parameters were supplied to Stripe's API
                $str = $e->getMessage();
								$error['success'] 	 = false;
								$error['error_message']     = $str;
            } catch (\Stripe\Error\Authentication $e) {
              // Authentication with Stripe's API failed
              // (maybe you changed API keys recently)
              $str = $e->getMessage();
							$error['success'] 	 = false;
							$error['error_message']     = $str;
            } catch (\Stripe\Error\ApiConnection $e) {
              // Network communication with Stripe failed
                $str = $e->getMessage();
            } catch (\Stripe\Error\Base $e) {
              // Display a very generic error to the user, and maybe send
              // yourself an email
                $str = $e->getMessage();
								$error['success'] 	 				= false;
								$error['error_message']     = $str;
            } catch (Exception $e) {
              // Something else happened, completely unrelated to Stripe
              $str = $e->getMessage();
							$error['success'] 	 				= false;
							$error['error_message']     = $str;
            }

						if (isset($error['success']) && !$error['success']) {
							return response($error,200);
						}
					}
				}
				else
				{
					$response['success']       = false;
					$response['error_message'] = "Something went wrong. Please try again.";
				}
			}
	   	  return response($response);
      }
  }

	public function login(Request $request)
	{
	   	$credentials = $request->only('email', 'password');
	   	$authSuccess = Auth::attempt($credentials, $request->has('remember'));
	   	if($authSuccess)
	   	{
		   	$request->session()->regenerate();
		   	$response['success'] = true;
		   	$response['url'] = URL('admin/plans');
		   	$response['delayTime'] = 1000;
		   	$response['success_message'] = "Login successful !!!";
		   	return response($response);
	   	}else{
		   	$response['success'] = false;
		   	$response['error_message'] = "Either username or password is incorrect !!!";
	   	}
	   	return response($response);
   }


	public function addNewTagAjax(Request $request)
	{
		if ($request->Ajax()) {

			if($request->has('tagRef')){
			$validator = \Validator::make($request->all(), [
					'tagName' => 'required',
			]);
			}else{
				$validator = \Validator::make($request->all(), [
					'tagName' => 'required',
					'positionEnd' => 'required',
			]);
			}
			if($validator->fails()){
				$errors = $validator->errors()->messages();
				if (isset($errors['tagName'])) {
					$response['error_message']     =  $errors['tagName'];
				}else{
				    $response['error_message']     =  'Something went wrong while creating this tag. Please remove this tag and create again !';
				}
				$response['success']    = false;
				$response['formErrors'] = true;
				// if(isset(var))

 				$response['delayTime'] = 5000;
				return response($response);exit;
			}
			if ($request->has('tagRef') && trim($request->input('tagRef')) !='' ) {
			$whereData = [
						 	['id', '!=', $_POST['tagRef'] ] ,
						 	['tagName', '=', $_POST['tagName'] ],
                         	['campId', '=', $_POST['campId'] ]

			];
			$insertTag = array(
						 'tagName' 			 => trim($request->input('tagName')),
						 'id'   	 => trim($request->input('tagRef')),
					 	);

			$parseTagExit = Helpers::checkExits('parsetags',$whereData);
			}else{
			 	$whereData = [
						['tagName', '=', $_POST['tagName'] ] ,
						['campId', '=', $_POST['campId'] ]

				];

				$insertTag = array(
						'tagName' 			 => trim($request->input('tagName')),
						'status'   		   => 1,
						'addedBy'   		 => \Auth::user()->id,
						'indexRow'   	   => trim($request->input('indexRow')),
						'positionStart'  => trim($request->input('positionStart')),
						'campId'   		   => trim($request->input('campId')),
						'positionEnd'    => trim($request->input('positionEnd')),
				);

			 	$parseTagExit = Helpers::checkExits('parsetags',$whereData);
		 	}
		 // die('asdfsad');
		 	if ($parseTagExit) {
				$response['success']    = false;
				$response['formErrors'] = true;
				$response['error_message']     =  'Tag Name already exits';
				return response($response);exit;
		 	}

			  if ($request->has('tagRef') && trim($request->input('tagRef')) !='' ) {
					$getTag = Parsetag::where('id',$request->input('tagRef'))->first();
					$campaignTemplate = Campaign::where('id',$getTag->campId)->first();
					$newTemplate = array('{'.$getTag->tagName.'}' => $request->input('tagName') );
					$finalTemp = preg_replace(array_keys($newTemplate), array_values($newTemplate), $campaignTemplate->template);

					$parseTag = Parsetag::updateOrCreate(['id'=> $request->input('tagRef')],$insertTag);
					$camp = Campaign::where('id',$getTag->campId)->update(['template' => $finalTemp]);
					$message = 'Record updated successfully.';
					$response['template'] = $finalTemp;
					$response['newTag']   = $request->input('tagName');
					$response['refId']    = $request->input('tagRef');
				}else{
					$parseTag = Parsetag::insertGetId($insertTag);
					$message = 'Record inserted successfully.';
				}

			 if ($parseTag) {
				 $response['success'] 				 	= true;
				 $response['success_message'] 	= $message;
				 $response['newTag']            = $request->input('tagName');
				 $response['refId'] 	          = $parseTag;
				 $response['modelhide'] 			 	= '#add-new-tag';
				 // $response['url']								= url('admin/tags');
				 if($request->has('frontEnd'))
				 {
					 $response['tagRequest'] 			= $insertTag['tagName'];
					 $response['delayTime'] 			= '2000';
				 }else{
					 $response['delayTime'] 			= '2000';
				 }
				 $response['modelhide'] 			 	= '#add-new-tag';
			 }else{
				 $response['success'] 				  = false;
				 $response['success_message']   = 'Something went wrong please try again.';
			 }
			 return response($response);exit;
		 }

	 }

	public function deleteTagAjax(Request $request)
	{
		if ($request->Ajax()) {
			 if ($request->has('tagRef') && trim($request->input('tagRef')) !='' ) {
				 $getTag = Parsetag::where('id',$request->input('tagRef'))->first();
				 // print_r($getTag->toArray());die;
				 $campaignTemplate = Campaign::where('id',$getTag->campId)->first();
				 $newTemplate = array('{'.$getTag->tagName.'}' => $request->input('tagName') );
				 $finalTemp = str_replace('{'.$getTag->tagName.'}',"",$campaignTemplate->template);
				 $parseTag = Parsetag::where(['id'=> $request->input('tagRef')])->delete();
				 $camp = Campaign::where('id',$getTag->campId)->update(['template' => preg_replace("/ {2,}/", " ", $finalTemp)]);
				 $message = 'Record deleted successfully.';
			  if($camp)
				{
				 $response['template'] 				  = preg_replace("/ {2,}/", " ", $finalTemp);
				 $response['success'] 				 	= true;
				 $response['refId'] 				 	  = $request->input('tagRef');
				 $response['success_message'] 	= $message;
			  }
			  else
				{
				$response['success'] 				  = false;
				$response['success_message']   = 'Something went wrong please try again.';
			  }
			return response($response);exisettingsViewt;
		}
	}
	}

                            //
	public function saveCreditCard(Request $request)
	{
		require_once app_path().'/Services/html2textt/autoload.php';
		$temp = 'Hello';
		$temp = \Html2Text\Html2Text::convert($temp);
		$emailLines       = explode("\n", $temp);
		echo "<pre>";
		print_r($emailLines);
		echo $temp;die;

	}

	public function webHookCustomer(Request $request)
	{
		Helpers::addFwdEmail('6gnnavhzlp@directconnect.com');
		die;
		$readMail = Helpers::readEmails(trim($this->domain),trim('pqxko5fvwe@directconnect.com'),trim('viqnjb31daez4gg'));

		if (!empty($readMail)) {
			foreach ($readMail as $mail)
			{

				$to     = trim($mail['to']);
				$body   = trim($mail['body']);
				$to     = str_replace('"',"",$to);
				$to     = str_replace(' ','',$to);
				$to     = str_replace("\r", "", $to);
				$to     = str_replace("\n", "", $to);
				$to     = filter_var($to, FILTER_SANITIZE_STRING);

				$emails = Campaign::where('is_deleted',0)->where('email',$to)->where('step',4)->first();
				//echo '<pre>'; print_r($emails); die;
				$timezone = Helpers::getRegion($emails->coutry_details->countryShort);
				reset($timezone);
				$region = key($timezone);
				$timezone = Helpers::get_time_zone($emails->coutry_details->countryShort,$region);
				if ($timezone) {
						date_default_timezone_set($timezone);
				}
				if($emails)
				{
					preg_match_all('/{(.*?)}/', $emails->template, $matches);
					$parsingTerms     = $matches[1];
					$replacements     = array();
					$emailLines       = explode('/<br[^>]*>/i', $body);
					echo "<pre>";
					print_r($emailLines);
					die;
					$template         = $emails->template;
					$camp             = Parsetag::where('campId',$emails->id)->get();
					$parseArr         = array();
					foreach ($camp as $c)
					{
						$line           = (isset($emailLines[$c->indexRow])) ? Helpers::RemoveSpecialChapr($emailLines[$c->indexRow]) : '';
						$parseContent   = substr($line,$c->positionStart);
						if (trim($c->tagName) == 'message') {
							  $dd =  array_slice($emailLines,$c->indexRow);
								$parseContent = implode(' ', $dd);
						}
						$parseArr[trim($c->tagName)] = $parseContent;

					}
					$arrLower    = array_change_key_case($parseArr,CASE_LOWER);
					$phone       = ltrim($arrLower['phone'],'0');
					foreach($parseArr as $k => $val)
					{
						$search    = '{'.$k.'}';
						$parseMail = str_replace($search, $val, $template);
						$template  = $parseMail;
					}
					$callScript  = $parseMail;

					echo "$parseMail";

				}
			}
	}
  }


	public function check_mail(){
		$patterns = $replacements = $emailstring = array();
		$templates     = DB::table('templates')->where('id',1)->first();
		$string        = $templates->content;
		$patterns 	   = array('/{CustomerName}/','/{PlanName}/');
		$replacements  = array('Pardeep','Test Plan');
		$emailstring   = Helpers::emailReplacement($patterns, $replacements, $string);
		$emailstring['title'] = 'Sign Up - PhoneConnect';
		// print_r($emailstring);
		// die;
		$dd = Helpers::sendmail(env('MAIL_FROM'),env('APP_NAME'),'ranjan.test@yopmail.com','RK','Signup - Direct Connect',$emailstring,'emails.email');
		print_r($dd);
	}


	public function sufflePlan(Request $request)
	{
		 $stripe = \Stripe::make(env('STRIPE_SECRET'),env('STRIPE_VERSION'));
		 $userId           = \Auth::user()->id;
		 $stripe_cust_id   = \Auth::user()->stripe_cust_id;
		 if ($request->ajax()) {
			$postData = array_except($request->all(), ['_token']);
			$planId     =   UserPlan::with('user_latest_payment')->where('user_id',$userId)->first();
			if($planId->plan_id == $postData['choosePlanId'] && $planId->user_latest_payment->status == 'Active'){
				$response['success'] = false;
				$response['error_message'] = 'You already subscribed this plan please try with new plan';
				return response( $response );exit;
			}
			else
			{
				$getAlreadyObtainPlain = PlanPrice::with('plans','plan_currency')->where('stripe_plan_id',$postData['payId'])->first();
			//	echo '<pre>';print_r($getAlreadyObtainPlain->toArray());die;
				if ($getAlreadyObtainPlain) {
					try {
						if (trim($postData['sub_id']) != '' )
						{
							$stripe_cust_id   = \Auth::user()->stripe_cust_id;
							$customer 				= $stripe->customers()->find($stripe_cust_id);
							$isFound 					= false;
							$subscriptions 		= $customer['subscriptions']['data'];
							foreach ($subscriptions as $key => $value)
							{
									 if ($value['id'] == $postData['sub_id'])
									 {
											 $postData['sub_id'] = $value['id'];
											 $isFound = true;
											 break;
									 }
							}
							if ($isFound) {
							 $subscription  = $stripe->subscriptions()->cancel($stripe_cust_id, $postData['sub_id']);
							}
							$res   				 = DB::table('user_payments')->where('sub_id',$postData['sub_id'])->update(['status'=>'Canceled']);
							$subscriptioncreate = $stripe->subscriptions()->create( $stripe_cust_id,['plan' => $getAlreadyObtainPlain->stripe_plan_id]);
							if (!empty($subscriptioncreate))
							{
									$response['success']          =  true;
									$response['submitDisabled']   =  true;
									$response['success_message']  =  'Payment has been processed successfully we will update your account in 5 to 10 mintues';
									$response['modelhide']  =  '#planDegrade';
									$response['sub_id']  =  $subscriptioncreate['id'];
									$stepUser = array('plan_id' => $postData['choosePlanId']);
									$user 		= UserPlan::where('user_id', '=',$userId);
									$user->update($stepUser);
									User::where('id', '=', $userId)->update(['status'=>1]);
							}
							else{
								$response['success']          =  false;
								$response['error_message']  =  'Something went wrong please try again.';
							}
						}
						else {
							$response['success']          =  false;
							$response['error_message']  =  'Please Select plan and submit request again.';
						}
					} catch (\Exception $e) {
						$response['success']          =  false;
						$response['error_message']  =  $e->getMessage();
					}

				}else{
					$response['success']          =  false;
					$response['error_message']  =  'Please Select plan and submit request again.';
				}
			}
		}else{
			$response['success']          =  false;
			$response['error_message']  =  'Something went wrong please try again.';
		}
		return response( $response );exit;
	}

	//Check Availabe Amount of User
	public function checkBalanceUser()
	{
		$u = User::has('user_plan');
		$users = $u->with('user_plan.plans.plan_prices')->where('status',1)->get();


		foreach($users as $user)
		{

			if ($user->user_plan->plans->billingType == 2) {
				foreach ($user->user_plan->plans->plan_prices as $price) {
					if($price->currency_id==$user->region->currency)
					{
						$pricePerMin = $price->per_min_cost;
					}
				}
				$perMinCost = $pricePerMin;
				$userEmail = $user->email;
				$userPass  = Crypt::decrypt($user->enc_password);
				$path = env('API_BASEPATH').'department/';
				$data = Curl::to($path)
				->withContentType('application/json')
				->withOption('USERPWD', $userEmail . ":" . $userPass)
				->get();
				$data = json_decode($data);
				if (isset($data->objects )){
					$totalSeconds      = 0;
					foreach ($data->objects as $key => $objects) {
						$totalSeconds += $objects->total_called_seconds;
					}
					$totalMin = $totalSeconds / 60 ;
				}

				//echo $totalMin. ' '.$user->minuteConsumed;die;

				if($totalMin >= $user->minuteConsumed)
				{
					$min = $totalMin-$user->minuteConsumed;
					$consumedAmount = $min*$perMinCost;
					$balanceAmount 	= $user->balanceAmount-$consumedAmount;
					$res = User::where('id',$user->id)
					->update(['balanceAmount'=>$balanceAmount,'minuteConsumed'=>$totalMin]);
					if($res)
					{
						if($balanceAmount < 10)
						{
							self::rechargeCreditAmount(0,$user->id);
							$u = User::where('id',$user->id)->first();
							$response['success']          =  true;
							$response['balanceAmount']    =  $u->balanceAmount;
							$response['success_message']  =  'Balance amount updated and recharged !!!';
						}
						else
						{
							$response['success']          =  true;
							$response['balanceAmount']    =  $balanceAmount;
							$response['success_message']  =  'Balance amount updated !!!';
						}
					}
					else
					{
						$response['success']          =  false;
						$response['success_message']  =  'Error occured while updating balance amount !!!';
					}
				}
				else
				{
					$response['success']          =  false;
					$response['success_message']  =  'No new minutes consumed !!!';
				}
			}else
			{
				$response['success']          =  false;
				$response['success_message']  =  'No new minutes consumed !!!';
			}

		}
		return response($response);
	}

	//Recharge Credit Amount
	public function rechargeCreditAmount($rAmount=0 , $user_id = null)
	{
			if ($user_id !='' ) {
				$userId = $user_id;
			}else{
				$userId = \Auth::user()->id;
			}
			$user = $user_data=User::where('id',$userId);
			$result=$user->whereHas('user_plan',function($user_plan){
				$user_plan->whereHas('plans',function($plans){
				 	$plans->Has('plan_prices');
				});
			});
      if($result->count()!=0)
	    {
		 	$country=$user_data->first()->region->currency;
	 		$userData=$user->with(['user_plan.plans.plan_prices'=>function($q)use($country){
	 		 	 $q->with('plan_currency')->where('currency_id',$country);
	 	  	}])->first();
	 	  	foreach($userData->user_plan->plans->plan_prices as $price)
			{
				$currencyCode = $price->plan_currency->currencyCode;
				$rechargeAmount = $price->credit;
			}
			if($rAmount!=0)
			{
				$rechargeAmount = $rAmount;
			}
	 	  	// $rechargeAmount = 150;
	 	  	$stripe = \Stripe::make(env('STRIPE_SECRET'),env('STRIPE_VERSION'));
				$charge = $stripe->charges()->create([
					'customer' => $userData->stripe_cust_id,
					'currency' => $currencyCode,
					'amount'   => $rechargeAmount,
				]);
				if(!empty($charge)){
					$fwdAmount = $userData->balanceAmount;
					$balanceAmount = $fwdAmount+$rechargeAmount;
					$stepUser = array(
						'accout_bal'	=> $rechargeAmount,
						'fwdAmount'		=> $fwdAmount,
						'balanceAmount' => $balanceAmount
					);
				$user = User::where('id', '=',$userId);
				$user->update($stepUser);
				//Stripe Data
				$stripeCharge['amount']           = $charge['amount'] / 100;
				$stripeCharge['amount_paid']      = $charge['amount'] / 100;
				$stripeCharge['invoice_id']       = $charge['balance_transaction'];
				$stripeCharge['customer_id']      = $charge['customer'];
				$stripeCharge['custom']      			= $userId ;
				$stripeCharge['charge_id']        = $charge['id'];
				$stripeCharge['currency']         = $charge['currency'];
				$stripeCharge['invloice_pdf']     = '';
				$stripeCharge['plan_id']          = '';
				$stripeCharge['product_id']       = '';
				$stripeCharge['qty']              = '';
				$stripeCharge['sub_id']           = '';
				$stripeCharge['sub_item_id']      = '';
				$stripeCharge['type']             = 'charge';
				$stripeCharge['startDate']        = $charge['created'];
				$stripeCharge['endDate']          = $charge['created'];
				$stripeCharge['date']        			= $charge['created'];
				$stripeCharge['due_date']         = $charge['created'];
				$stripeCharge['payment_status']   = $charge['paid'] ;
				$stripeCharge['completePayment']  = json_encode($charge) ;
				$stripeCharge['status']           = 'succeeded';
				$ss 															= UserPayment::insert($stripeCharge);
				$resp['success'] 									= true;
				$resp['success_message'] 					= 'Credit amount recharged successfully !!!';
			}
			else
			{
				$resp['success'] = false;
				$resp['success_message'] = 'Credit recharge failed !!!';
			}
		}
		else
		{
			$resp['success'] = false;
			$resp['success_message'] = 'Something went wrong please try again !!!';
		}
		return response($resp);
	}
	public function rechargeCreditManually(Request $request)
	{
		$credit = $request->post('rechargeAmt');
		if($credit!=0)
		{
			$result = self::rechargeCreditAmount($credit);
		}
		else
		{
			$result['success'] = false;
			$result['success_message'] = 'Something went wrong please try again !!!';
			$result = response( $result );
		}
		return $result;
	}
	public function reSignUpUser($id)
	{
		$id = Crypt::decrypt($id);
		$r=Region::where('is_deleted',0);
	    $regions = $r->whereHas('plan_prices',function($plan_price){
	    	$plan_price->where('is_deleted',0)->whereHas('plans',function($q){
	    		$q->where('is_deleted',0);
	    	});
	    })->pluck('name','id')->prepend('Please Select','');
	    $userData = User::where('id',$id)->first();
		return view($this->frontendtemplatename.'.registration.index_resignup',['regions'=>$regions,'user'=>$userData]);
	}

}
