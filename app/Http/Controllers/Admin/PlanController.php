<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\GlobalFunctions as Helpers;
Use App\Model\Plan;
Use App\Model\PlanPrice;
use DB;
use Crypt;
use Curl;
use App\Model\Country;
class PlanController extends Controller
{
    public $url;
    public $admintemplatename;
    public $currentUrl;
    public function __construct()
    {
        $this->url=url(request()->route()->getPrefix());
        $this->admintemplatename = config('app.admintemplatename');
        $this->currentUrl = Helpers::getCurrentUrl();

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $plans    = Plan::where('is_deleted',0)->orderBy('id','DESC')->paginate(10);

        if ($request->ajax())
        {
            $page =  ( $request['page'] - 1 ) * 10;
            if ( isset($_GET['searchKey']) ) {
              $plans    = Plan::where('is_deleted',0)->where('name', 'like', '%' . $request->get('searchKey') . '%')->orderBy('id','DESC')->paginate(10);
            }
            return view($this->admintemplatename.'/plans/records_list',['url'=>$this->url,'current_url'=>$this->currentUrl,'plans'=>$plans,'page'=>$page]);
        }
        else
        {
            return view($this->admintemplatename.'/plans/index',['url'=>$this->url,'current_url'=>$this->currentUrl,'plans'=>$plans]);
        }
    }
    //load Add Plan View
    public function addNewPlanView()
    {
        $planData = array();
        $planData['record_id'] = "";
        $planData['form_action'] = $this->admintemplatename.'/save-plan';
        $planData['plan_detail'] = "";
        // $currency = \App\Model\Country::pluck('countryName','currencyName','countryId')->toArray();
        $currency = array();
        $currency = Country::select(DB::raw("CONCAT(currencyName,' - (',countryName,')') AS currency"),'countryId')->where('currencyName','!=','')->get()->pluck('currency','countryId')->toArray();
        $currency =
        array_map('trim',$currency);
        asort($currency);
        $currency                 = array_prepend($currency,'Select Currency','0');
        $planData['currency']     = $currency;
        $planData['billingType']  = Helpers::billingPlan();;
        return view($this->admintemplatename.'/plans/addUpdate',['url'=>$this->url,'current_url'=>$this->currentUrl,'planData'=>$planData]);
    }
    //load Edit Plan View
    public function editPlanView(Request $request,$id)
    {
        $plan_id = Crypt::decrypt($id);
        $planData['record_id'] = $id;
        $planData['form_action'] = $this->admintemplatename.'/update-plan';
        $planData['plan_detail'] = Plan::where('id',$plan_id)->first();
        // $currency = \App\Model\Country::pluck('currencyName','countryId')->toArray();
        $currency = Country::select(DB::raw("CONCAT(currencyName,' - (',countryName,')') AS currency"),'countryId')->where('currencyName','!=','')->get()->pluck('currency','countryId')->toArray();
        $currency=array_map('trim',$currency);
        asort($currency);
        $currency = array_prepend($currency,'Select Currency','0');
        $planData['currency']=$currency;
        $planData['billingType']  = Helpers::billingPlan();

        return view($this->admintemplatename.'/plans/addUpdate',['url'=>$this->url,'current_url'=>$this->currentUrl,'planData'=>$planData]);
    }
    //load View Plan View
    public function viewPlan(Request $request,$id)
    {
        $plan_id = Crypt::decrypt($id);
        $planData = Plan::where('id',$plan_id)->first();
        return view($this->admintemplatename.'/plans/viewPlan',['url'=>$this->url,'current_url'=>$this->currentUrl,'planData'=>$planData]);
    }

    //load Price Row View
    public function addPriceAJAX(Request $request)
    {
        if($request->ajax()){
          $currency = array();
          $currency = Country::select(DB::raw("CONCAT(currencyName,' - (',countryName,')') AS currency"),'countryId')->where('currencyName','!=','')->get()->pluck('currency','countryId')->toArray();
          $currency=array_map('trim',$currency);
          asort($currency);
          $currency = array_prepend($currency,'Select Currency','0');
          return view($this->admintemplatename.'/plans/addPrice',['currencyList'=>$currency]);
        }
    }

    //Save plan to database
    public function savePlan(Request $request)
    {
        if($request->ajax())
        {
            $currency_id = array();
            $arrayName = array();
            $postData = array_except($request->all(), ['_token']);
            $whereData = [
                  ['name', $_POST['name']]
            ];
            $errFlag = false;
            $checkPlan = Helpers::checkExits('plans',$whereData);
            if($checkPlan){
                $response['success']    = false;
                $response['formErrors'] = true;
                $response['errors']     = array('name' => 'This plan name already exists. Please enter a unique name of plan !');
                $errFlag=true;
            }
            if(!ctype_digit($postData['minutes_per_month']))
            {
                $response['success']    = false;
                $response['formErrors'] = true;
                if($errFlag){
                    $response['errors']['minutes_per_month'] =  'Please enter a valid numeric value.';
                }else{
                    $errFlag=true;
                    $response['errors']     = array('minutes_per_month' => 'Please enter a valid numeric value.');
                }
            }
            if(!ctype_digit($postData['leads_per_month']))
            {
                $response['success']    = false;
                $response['formErrors'] = true;
                if($errFlag){
                    $response['errors']['leads_per_month']     = 'Please enter a valid numeric value.';
                }else{
                    $errFlag=true;
                    $response['errors'] = array('leads_per_month' => 'Please enter a valid numeric value.');
                }
            }

            foreach($postData['price'] as $index => $price){
                if(!is_numeric($price)){
                    if($errFlag){
                      $response['errors']['price['.$index.']']     = 'Enter a valid amount.';
                    }else{
                        $response['errors']     = array('price['.$index.']' => 'Enter a valid amount.');
                        $errFlag=true;
                    }
                }
                if($errFlag){
                    $response['success']    = false;
                    $response['formErrors'] = true;
                }
            }

            foreach($postData['currency_id'] as $index => $currency){
                if($currency==0){
                    if($errFlag){
                      $response['errors']['currency_id['.$index.']']     = 'Select a valid currency.';
                    }else{
                        $response['errors']     = array('currency_id['.$index.']' => 'Select a valid currency.');
                        $errFlag=true;
                    }
                }
                if($errFlag){
                    $response['success']    = false;
                    $response['formErrors'] = true;
                }
            }
            if($errFlag==false)
            {
                $data = array(
                    "name"      =>$postData['name'],
                    "price"     =>"1000", //Price has nothing to do in API
                    "minutes"   =>$postData['minutes_per_month'],
                    "duration"  =>$postData['duration'],
                    "register"  =>"1"
                );
                $result = Curl::to(env('API_BASEPATH').'packages/')
                ->withContentType('application/json')
                ->withOption('USERPWD', env('ADMIN_USER'). ":" .env('ADMIN_PASS'))
                ->returnResponseObject()
                ->withData($data)->asJson()->post();
                if($result->status==201)
                {
                    if ($postData['billingType'] == 3){$postData['no_of_contacts'] = 10;}
                    $planData = array(
                        'api_package_id'    => $result->content->id,
                        'name'              => $postData['name'],
                        'description'       => $postData['description'],
                        'minutes_per_month' => $postData['minutes_per_month'],
                        'leads_per_month'   => $postData['leads_per_month'],
                        'duration'          => $postData['duration'],
                        'billingType'       => $postData['billingType'],
                        'no_of_contacts'    => (trim($postData['no_of_contacts']) !='') ? $postData['no_of_contacts'] : 0  ,
                        'features'          => $postData['features']
                    );
                    $plan_id = Plan::insertGetId($planData);
                    $stripe = \Stripe::make(env('STRIPE_SECRET'),env('STRIPE_VERSION'));
                    if($plan_id > 0)
                    {
                      $product = $stripe->products()->create(array(
                        "name" => $postData['name'],
                        "type" => "service",
                      ));
                        $id = $product['id'];
                        $upPlan = Plan::where('id', $plan_id)->update(['stripeProId' => $id]);
                        $i=0;
                        foreach($postData['price'] as $price)
                        {

                          $stripPlanId = 'plan_'.Helpers::generateRef();
                          $plan = $stripe->plans()->create(array(
                              "nickname"          => $postData['name'],
                              "id"                => $stripPlanId,
                              "product"           => $id,
                              "amount"            => $price,
                              "currency"          => Helpers::getCurrency($postData['currency_id'][$i]),
                              "interval"          => "month",
                              "interval_count"    => $postData['duration'],
                              "usage_type"        => "licensed",
                            ));
                            PlanPrice::insert(array('stripe_plan_id'=> $stripPlanId, 'plan_id'=>$plan_id,'amount'=>$price,'currency_id'=>$postData['currency_id'][$i],'credit' =>$postData['credit'][$i],'per_min_cost' =>$postData['per_min_cost'][$i] ));
                            $i++;
                        }
                        $response['success'] = true;
                        $response['success_message'] = 'Plan added successfully !';
                        $response['url'] = $this->url.'/plans';
                        $response['delayTime'] = 1000;
                    }
                    else
                    {
                        $response['success'] = false;
                        $response['error_message'] = 'Error occured while inserting record !!!';
                    }
                }
                if($result->status==400)
                {
                    $response['success'] = false;
                    $response['error_message'] = 'This plan name already exists on server !';
                }

            }
            return response($response);
        }
    }
    //Update plan to database
    public function updatePlan(Request $request)
    {
      $stripe = \Stripe::make(env('STRIPE_SECRET'),env('STRIPE_VERSION'));
        if($request->ajax())
        {
            $currency_id = array();
            $arrayName = array();

            // Checking plan exits or not
            $whereData = [
                ['name', $_POST['name']],
                ['id',  '!=', Crypt::decrypt($request->input('record_id'))],
            ];
            $checkPlan = Helpers::checkExits('plans',$whereData);
            if($checkPlan){
                $response['success']    = false;
                $response['formErrors'] = true;
                $response['errors']     = array('name' => 'This plan name already exists. Please enter a unique name of plan !');
                return response($response);die;
            }
            // **********************************
            $postData = array_except($request->all(), ['_token']);
            if(!empty($postData['price'])){
                foreach ($postData['price'] as $key => $value) {
                    if ($value == '') {
                        $arrayName['price['.$key.']'] =  'This field is required';
                    }
                }
                if (!empty($arrayName)) {
                    $output['success'] = false;
                    $output['formErrors'] = true;
                }
            }
            if(!empty($postData['currency_id'])){
                foreach ($postData['currency_id'] as $key => $value) {
                    if ($value == 0 ) {
                    $currency_id['currency_id['.$key.']'] =  'This field is required';
                    }
                }
                if (!empty($currency_id)) {
                    $output['success'] = false;
                    $output['formErrors'] = true;

                }
            }
            if(!empty($arrayName) || !empty($currency_id)){
                $output['errors'] = array_merge($arrayName,$currency_id);
                return response($output);
            }else{
                if(!empty($postData['priceID'])){
                    $delArr     = explode(',',$postData['deletePrice']);
                    $updateArr  = array_diff($postData['priceID'], $delArr);
                    $record_id  = Crypt::decrypt($postData['record_id']);
                    $planData   = array(
                        'name'              =>  $postData['name'],
                        'description'       =>  $postData['description'],
                        'minutes_per_month' => $postData['minutes_per_month'],
                        'leads_per_month'   => $postData['leads_per_month'],
                        'duration'          => $postData['duration'],
                        'billingType'       => $postData['billingType'],
                        'no_of_contacts'       => (trim($postData['no_of_contacts']) !='') ? $postData['no_of_contacts'] : 0  ,
                        'features'          => $postData['features']
                    );
                    DB::beginTransaction();
                    if(Plan::where('id',$record_id)->update($planData))
                    {

                        foreach($delArr as $deleteId){
                            $stripePricePlan = PlanPrice::where('id',$deleteId)->first();
                            if ($stripePricePlan) {
                              $plan = $stripe->plans()->delete($stripePricePlan->stripe_plan_id);
                            }
                            PlanPrice::where('id',$deleteId)->delete();
                        }
                        for($a=0;$a<count($postData['price']);$a++)
                        {
                            if(isset($postData['priceID'][$a]) && $postData['priceID'][$a]!=''){
                                $planPrice = array(
                                    'amount'        => $postData['price'][$a],
                                    'currency_id'   => $postData['currency_id'][$a],
                                    'credit'        => $postData['credit'][$a],
                                    'per_min_cost'  => $postData['per_min_cost'][$a]
                                );
                                PlanPrice::where('id',$postData['priceID'][$a])->update($planPrice);
                            }else{
                              // Generate Plan Prices ID For Stripe
                              $stripPlanId = 'plan_'.Helpers::generateRef();

                              $planPrice = array(
                                  'plan_id'        => $record_id,
                                  'amount'         => $postData['price'][$a],
                                  'currency_id'    => $postData['currency_id'][$a],
                                  'credit'         => $postData['credit'][$a],
                                  'per_min_cost'   => $postData['per_min_cost'][$a],
                                  'stripe_plan_id' => $stripPlanId
                              );
                              PlanPrice::insert($planPrice);
                              $plan = Plan::where('id',$record_id)->first();

                              // Create Plan Prices
                              $plan = $stripe->plans()->create(array(
                                    "nickname"       => $postData['name'],
                                    "id"             => $stripPlanId,
                                    "product"        => $plan->stripeProId,
                                    "amount"         =>  $postData['price'][$a],
                                    "currency"       => Helpers::getCurrency($postData['currency_id'][$a]),
                                    "interval"       => "month",
                                    "interval_count" => $postData['duration'],
                                    "usage_type"     => "licensed",
                            ));

                            }
                        }
                        DB::commit();
                        $api = Plan::select('api_package_id')
                                ->where('id',$record_id)->first();
                        $data = array(
                            "name"      =>$postData['name'],
                            "price"     =>"1000", //Price has nothing to do in API
                            "minutes"   =>$postData['minutes_per_month'],
                            "duration"  =>$postData['duration'],
                            "register"  =>"1"
                        );
                        if($api->api_package_id !=0)
                        {
                            $result = Curl::to(env('API_BASEPATH').'packages/'.$api->api_package_id.'/')
                            ->withContentType('application/json')
                            ->withOption('USERPWD', env('ADMIN_USER'). ":" .env('ADMIN_PASS'))
                            ->returnResponseObject()
                            ->withData($data)->asJson()->patch();
                        }
                        else
                        {
                            $result = Curl::to(env('API_BASEPATH').'packages/')
                            ->withContentType('application/json')
                            ->withOption('USERPWD', env('ADMIN_USER'). ":" .env('ADMIN_PASS'))
                            ->returnResponseObject()
                            ->withData($data)->asJson()->post();
                            if($result->status==201)
                            {
                                Plan::where('id',$record_id)
                                ->update(array('api_package_id'=>$result->content->id));
                            }
                        }
                        $response['success'] = true;
                        $response['success_message'] = 'Plan updated successfully !';
                        $response['url'] = $this->url.'/plans';
                        $response['delayTime'] = 1000;
                    }
                    else
                    {
                        DB::rollback();
                        $response['success'] = false;
                        $response['error_message'] = 'Error occured while updating record !!!';
                    }
                }else{
                    $response['success'] = false;
                    $response['error_message'] = 'Error occured while updating record !!!';
                }

            }
            return response($response);
        }
    }
    public function checkPlanName(Request $request)
    {
        $planName = $request->input('plan_name');
        echo response($planName);
    }
}
