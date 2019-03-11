<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\GlobalFunctions as Helpers;
use App\User;
use App\Model\ParseEmail;
use DB;
use Curl;
use Crypt;
use Hash;
use Auth;
Use App\Model\Campaign;
Use App\Model\Country;
Use App\Model\Contact;
Use App\Model\CampaignsContacts;
Use App\Model\CallSetting;
class DashboardController extends Controller
{

  	public function __construct()
  	{
  		$this->customertemplatename = config('app.customertemplatename');
  	}
    public function index()
    {

      $chartData = Helpers::charts();
  		return view($this->customertemplatename.'/dashboard.index',['chart'=>$chartData]);
    }

    public function getCharts(Request $request)
    {

      if ($request->ajax()) {

        if (trim($request->get('startDate')) == '' && trim($request->get('endDate')) == '' ) {
          $startDate = date('Y-m-01');
          $endDate  = date('Y-m-t');
        }
        else{
          $startDate = date('Y-m-d',strtotime($request->get('startDate')));
          $endDate  = date('Y-m-d',strtotime($request->get('endDate')));
        }


        $result = Helpers::chartsAjax($startDate,$endDate);

        $leadCount = 0;
        $data['camp_chart'] = [];
				foreach ($result['result'] as  $value)
        {
            if (\DateTime::createFromFormat('Y-m-d G:i:s', $value->month) !== FALSE) {
              $data['camp_chart'][] = array('monthname' => date('m-d-Y',strtotime($value->month)) , 'lead_count' => $value->count);
            }else{
              $data['camp_chart'][] = array('monthname' => $value->month , 'lead_count' => $value->count);
            }
						  $leadCount += $value->count;
        }
        $data['lead_count']  = $leadCount;
      }
      $data['campChart'] = $result['campChart'];
      return response($data) ;
    }


    public function reporting(Request $request)
    {
      $filter   = $request->get('filter');
      $tab      = $request->get('tab');
      $dateFrom = str_replace('/','-',$request->get('date_from'));
      $dateTo   = str_replace('/','-',$request->get('date_to'));
      $uid = Auth::user()->id;
      $l = ParseEmail::whereHas('campaign_details',function($q){
        $q->where('user_id',\Auth::id());
      });
      if(isset($filter) && !empty($filter)){
        switch($filter){
          case 'placed':
            $l->where('status',1);
          break;
          case 'unplaced':
            $l->where('status',2);
          break;
          case 'invalid':
            $l->where(function($l){
              $l->where('status',3)->orWhere('status',4);
            });
          break;
          case 'all':
            $l->where('status','!=',0);
          break;
        }
      }
      else
      {
        $l->where('status','!=',0);
      }
      if(!empty($dateFrom)){
        $dtf = explode("-", $dateFrom);
        $date1 = $dtf[2]."-".$dtf[0]."-".$dtf[1];
        $l->where('created_at','>=',$date1);
      }
      if(!empty($dateTo)){
        $dtt = explode("-", $dateTo);
        $date2 = $dtt[2]."-".$dtt[0]."-".$dtt[1];
        $l->where('created_at','<=',$date2);
      }
      $leads = $l->orderBy('id','DESC')->paginate(10,['*'],'leads');

      if ($request->ajax()) {
        $page =  ( $request['leads'] - 1 ) * 2;
        return view($this->customertemplatename.'/recordListAjax',['page'=> $page,'leads'=>$leads ]);
      } else {
        if(empty($dateFrom) && empty($dateFrom))
        {
          $path = env('API_BASEPATH').'department/';
        }
        else
        {
          $path = env('API_BASEPATH').'department/?start_date='.$dateFrom.'&end_date='.$dateTo;
        }
        $userEmail = \Auth::user()->email;
        $userPass = Crypt::decrypt(\Auth::user()->enc_password);
        //consumed minutes API
        $data = Curl::to($path)
        ->withContentType('application/json')
        ->withOption('USERPWD', $userEmail . ":" . $userPass)
        ->get();
        $data = json_decode($data);
        return view($this->customertemplatename.'/reporting',['data'=>$data,'leads'=>$leads,'tab'=>$tab,'filter'=>$filter]);
      }
  	}

    public function settingsView()
    {
      
      $stripe = \Stripe::make(env('STRIPE_SECRET'),env('STRIPE_VERSION'));
      $card =  $stripe->customers()->find(\Auth::user()->stripe_cust_id);
      $output = array();
      if (!empty($card['sources'])) {
          $output = $card['sources'];
      }
      $callSetting = array();
      $callSetting = CallSetting::where('user_id',Auth::user()->id)->first();
      if(!$callSetting)
      {
        $callSetting = CallSetting::whereHas('user.roles',function($q){
          $q->where('name','admin');
        })->first();
      }
      return view($this->customertemplatename.'/settings',['output' => $output,'callSetting'=>$callSetting]);
    }

    public function changeEmail(Request $request)
    {
      $email = $request->post('email');
      $result = User::where('email',$email)->count();
      if($result==0)
      {
        $res = User::where('email',\Auth::user()->email)->update(['email'=>$email]);
        if($res)
        {
          $response['success'] = true;
          $response['success_message'] = 'Email address changed successfully !';
          $response['delayTime'] = 2000;
        }
        else
        {
          $response['success'] = false;
          $response['error_message'] = 'Error occured while updating email address !';
          $response['delayTime'] = 2000;
        }
      }
      else
      {
        $response['success']    = false;
        $response['formErrors'] = true;
        $response['errors'] = array('email'=>'Email address already exists. Please enter a unique email address.');
      }
      return response($response);
    }

    public function changePassword(Request $request)
    {
      $oldPass = $request->post('old_pass');
      $newPass = $request->post('new_pass');
      $newPass2 = $request->post('new_pass2');

      $val = \Validator::make($request->all(),[
        'old_pass'    => 'required',
        'new_pass'    => 'required|min:5',
        'new_pass2'    => 'required|min:5',
      ]);
      if($val->fails()){
        $errors                = $val->errors();
        $response['success']   = false;
        $response['formErrors']= true;
        $response['errors']    = $errors;
        return response($response);die;
      }

      $user = User::select('password')->where('id',\Auth::user()->id)->first();
      $oldPassDB = $user->password;
      if (Hash::check($oldPass, $oldPassDB))
      {
        if($newPass==$newPass2)
        {
          $password = bcrypt($newPass);
          $res = User::where('id',\Auth::user()->id)->update(['password'=>$password]);
          if($res)
          {
            $response['success']         = true;
            $response['success_message'] = 'Password updated successfully !';
            $response['delayTime']       = 2000;
          }
          else
          {
            $response['success']        = false;
            $response['error_message']  = 'Error occured while updating password !';
            $response['delayTime']      = 2000;
          }
        }
        else
        {
          $response['success']        = false;
          $response['error_message']  = 'Password not matched !!!';
          $response['delayTime']      = 5000;
        }
      }
      else
      {
        $response['success']    = false;
        $response['error_message'] = 'You entered wrong old password !!!';
        $response['delayTime'] = 5000;
      }
      return response($response);
    }

    public function profileView()
    {
      $data = User::where('id',\Auth::user()->id)->first();
      return view($this->customertemplatename.'/profile',['data'=>$data]);
    }

    public function editProfileView()
    {
      $data = User::where('id',\Auth::user()->id)->first();
      $data = array(
        'fname'       => $data->firstName,
        'lname'       => $data->lastName,
        'phone'       => $data->phoneNo,
        'companyName' => $data->companyName,
        'companyUrl'  => $data->companyUrl
      );
      return view($this->customertemplatename.'/edit-profile',['data'=>$data]);
    }

    public function updateProfile(Request $request)
    {
      $data = array(
        'firstName'   => $request->post('fname'),
        'lastName'    => $request->post('lname'),
        'phoneNo'     => $request->post('phone'),
        'companyName' => $request->post('companyName'),
        'companyUrl'  => $request->post('companyUrl')
      );
      $result = User::where('id',\Auth::user()->id)->update($data);
      if($result)
      {
        $response['success']         = true;
        $response['success_message'] = 'User details updated successfully !';
        $response['delayTime']       = 2000;
        $response['url']             = 'profile';
      }else{
        $response['success']         = false;
        $response['error_message']   = 'Error occured while updating details !';
        $response['delayTime']       = 5000;
      }
      return response($response);
    }

    public function saveCard(Request $request)
    {
      $stripe = \Stripe::make(env('STRIPE_SECRET'),env('STRIPE_VERSION'));
      if ($request->ajax()) {
        // echo "<pre>";
        // print_r($_POST);
        // die;
        if (!isset($_POST['card'])) {
          $exp = explode('/', $request->input('newccExpiryMonth'));
          try {
            $token = $stripe->tokens()->create([
            'card' => [
                        'name'      => $request->input('newCardHolderName'),
                        'number'    => preg_replace('/\s+/', '', $request->input('newcardNumber')),
                        'exp_month' => trim($exp[0]),
                        'cvc'       => $request->input('cvvNumber'),
                        'exp_year'  => trim($exp[1]),
                      ],
            ]);
            $card = $stripe->cards()->create(\Auth::user()->stripe_cust_id, $token['id']);
            $response['success']         = true;
            $response['success_message']   = 'Card Details added successfully..';
            $response['cardId'] = $card['id'];
            $card =  $stripe->customers()->find(\Auth::user()->stripe_cust_id);
            $outputInput = '';
            if (!empty($card['sources'])) {
              foreach ($card['sources']['data'] as $key => $value) {
                $outputInput = '<div class="col-md-4">
                  <div class="form-group">
                    <label for="cc-number" class="control-label">Card Holder Name  <span class="star">*</span></label>
                    <input type="text" class="input-lg form-control" name="cardHolderName" value="'.$value["name"].'" placeholder="Card Holder Name" required="">
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label for="cc-number" class="control-label">Card number  <span class="star">*</span></label>
                    <input id="cc-number" type="tel" class="input-lg form-control cc-number mastercard identified" name="cardNumber"  value="XXXX XXXX XXXX '.$value["last4"].'"  autocomplete="cc-number" placeholder="•••• •••• •••• ••••" readonly="">
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label for="cc-exp" class="control-label">Card expiry  <span class="star">*</span></label>
                    <input id="cc-exp" type="tel" class="input-lg form-control cc-exp" name="ccExpiryMonth" autocomplete="cc-exp" value="'.$value["exp_month"].'/'.$value["exp_year"].'"  placeholder="•• / ••" readonly="">
                  </div>
                </div>';
              }
              $response['formInput'] = $outputInput;
            }

          } catch (Exception $e) {
              $str = $e->getMessage();
              $response['success'] 	 = false;
              $response['error_message']     = $str;
          } catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {
              $str = (string) $e->getMessage();
              if (trim(strtolower("Your card's security code is invalid.")) == trim(strtolower($str)) ) {
               $str = 'Your CVC number is incorrect.';
               $array  = array('cvvNumber' => $str, );
             }else if(trim(strtolower("Your card number is incorrect.")) == trim(strtolower($str))){
               $array  = array('newcardNumber' => $str, );
             }else{
                $array  = array('newccExpiryMonth' => $str, );
             }
              $response['formErrors'] = true;
              $response['success'] 	 = false;
              $response['errors']     = $array;
          }
        }else{
          try {
            $card = $stripe->cards()->update(\Auth::user()->stripe_cust_id, $request->input('card'), [
              'name'      => $request->input('cardHolderName'),
            ]);
            $response['success']         = true;
            $response['success_message']   = 'Card Details updated successfully..';
          } catch (\Exception $e) {
            $response['success']         = false;
            $response['error_message']   = $e->getMessage();
          }
        }
      }else{
        $response['success']         = false;
        $response['error_message']   = 'Error occured while updating details !';
      }
      return response($response);
    }
    public function deleteCard(Request $request)
    {

      $stripe = \Stripe::make(env('STRIPE_SECRET'),env('STRIPE_VERSION'));
      if ($request->ajax()) {
        try {
        $card = $stripe->cards()->delete(\Auth::user()->stripe_cust_id, $request->input('id'));
        $response['success']         = true;
        $response['success_message']   = 'Card deleted successfully..';
        } catch (\Exception $e) {
          $response['success']         = false;
          $response['error_message']   = $e->getMessage();
        }
      }else{
        $response['success']         = false;
        $response['error_message']   = 'Error occured while updating details !';
      }
      return response($response);
    }
    //Call Settings Save
    public function callSettings(Request $request)
    {
      $admin = CallSetting::whereHas('user.roles',function($q){
        $q->where('name','admin');
      })->first();
      $boolArr = array('',true,false);
      $post = array_except($request->all(),['_token']);

      if(!isset($post['call_recording_display'])){
        $post['call_recording_display'] = 1;
      }
      if(!isset($post['call_announcement_email'])){
        $post['call_announcement_email'] = 1;
      }

      foreach ($post as $key => $value) 
      {
        if(empty($value) || trim($value)=='')
        {
          $post[$key] = $admin->$key;
        }
      }
      if($post['call_recording_display']!=1){
        $post['call_annoucement'] = $admin->call_annoucement;
      }
      if($post['call_announcement_email']!=1){
        $post['email_subject']  = $admin->email_subject;
        $post['email_body']     = $admin->email_body;
      }
      $postData = $post;
      $postData['user_id'] = Auth::user()->id;
      if(isset($post['call_recording_display'])){
        $post['call_recording_display'] = $boolArr[$post['call_recording_display']];
      }
      if(isset($post['call_announcement_email'])){
        $post['call_announcement_email'] = $boolArr[$post['call_announcement_email']];
      }
      $rCount = CallSetting::where('user_id',Auth::user()->id)->count();
      $path = env('API_BASEPATH').'clientCreate/'.Auth::user()->api_client_id.'/';
      $result = Curl::to($path)
      ->withContentType('application/json')
      ->withOption('USERPWD', env('ADMIN_USER') . ":" . env('ADMIN_PASS'))
      ->returnResponseObject()
      ->withData($post)
      ->asJson()
      ->patch();
      if($result->status==202)
      {
        if($rCount==0){
          CallSetting::insert($postData);
        }else{
          CallSetting::where('user_id',Auth::user()->id)->update($postData);
        }
        $response['success']=true;
        $response['success_message']="Call settings updated successfully.";
      }
      elseif($result->status==500)
      {
        $response['success']=false;
        $response['delayTime'] = 5000;
        $response['error_message']="You need to fill all the fields to update call settings otherwise default settings will continue working !!!";
      }
      else
      {
        $response['success']=false;
        $response['error_message']="Error occured while updating call settings !!!";
      }
      return response($response);
    }
}
