<?php

namespace App\Http\Controllers;
use App\Helpers\GlobalFunctions as Helpers;
use Illuminate\Http\Request;
use App\User;
use App\RoleUser;
use DB;
use Crypt;
use Curl;
use Auth;


class CommonController extends Controller
{
    public function deleteRecord(Request $request){
    	if($request->ajax()){
    		$post = $request->all();
    		$record_id = $post['id'];

    		$table = Crypt::decrypt($post['table']);
            $byePass = false;
            switch($table)
            {
                case 'plans':
                    $api = DB::table('plans')->select('api_package_id')->where('id',$record_id)->first();
                    $result = Curl::to(env('API_BASEPATH').'packages/'.$api->api_package_id.'/')
                    ->withOption('USERPWD', env('ADMIN_USER'). ":" .env('ADMIN_PASS'))
                    ->delete();
                    DB::table('plans')->where('id',$record_id)->update(['api_package_id'=>0]);
                break;
                case 'campaigns':
                    $api = DB::table('campaigns')->select('*')->where('id',$record_id)->first();
                    $campContact = DB::table('campaigns_contacts')->select('api_phone_id')->where('campId',$record_id)->get();
                    if (count($campContact) > 0) {
                      foreach ($campContact as $key => $value) {
                        $phone_res = Curl::to(env('API_BASEPATH').'phone/'.$value->api_phone_id.'/')
                        ->withContentType('application/json')
                        ->withOption('USERPWD', Auth::user()->email . ":" . Crypt::decrypt(Auth::user()->enc_password))
                        ->asJson()->delete();
                      }
                      $campContact = DB::table('campaigns_contacts')->where('campId',$record_id)->delete();

                      // /nyrpe0@cashmann.co.uk
                    }
                    $result = Curl::to(env('API_BASEPATH').'department/'.$api->api_dept_id.'/')
                    ->withContentType('application/json')
                    ->withOption('USERPWD', Auth::user()->email . ":" . Crypt::decrypt(Auth::user()->enc_password))
                    ->delete();
                    $byePass = true;
                    $deleteEmail = Helpers::deleteEmail($api->email);
                break;
                case 'contacts':
                    $result = DB::table('campaigns_contacts')->select('id','api_phone_id')->where('custId',$record_id)->get();
                    foreach($result as $res)
                    {
                        $result = Curl::to(env('API_BASEPATH').'phone/'.$res->api_phone_id.'/')
                        ->withContentType('application/json')
                        ->withOption('USERPWD', Auth::user()->email . ":" . Crypt::decrypt(Auth::user()->enc_password))
                        ->delete();
                        DB::table('campaigns_contacts')->where('id',$res->id)->delete();
                    }
                    $byePass = true;
                break;
                case 'users':
                    $byePass = true;
                break;

            }
            if($byePass)
            {
                $res = DB::table($table)->where('id',$record_id)->delete();
            }
            else
            {
                if($table !='parsetags') {
                  $res = DB::table($table)->where('id',$record_id)->update(['is_deleted'=>1]);
                }else{
                  $res = DB::table('parsetags')->where('id',$record_id)->delete();
                  $res = true;
                }
            }
    		if($res){
    			$response['success'] = true;
    			$response['success_message'] = "Record deleted successfully !";
    		}else{
    			$response['success'] = false;
    			$response['error_message'] = "Error occurred while deleting record !";
    		}
    	}
    	return response($response);
    }
    public function unsubscribePlan(Request $request){


    	if($request->ajax()){
    		$post                 = $request->all();
    		$subsId               = $post['subsId'];
        $stripe               = \Stripe::make(env('STRIPE_SECRET'),env('STRIPE_VERSION'));

        $stripe_cust_id       = Auth::user()->stripe_cust_id;
        $customer = $stripe->customers()->find($stripe_cust_id);
        if (empty($customer['subscriptions']['data'])) {
          $response['error_message']  = "You have no active subscription. you can not perform this action.";
          $response['success'] = false;
          return response($response);die;
        }

        $isFound = false;
        $subscriptions = $customer['subscriptions']['data'];
        foreach ($subscriptions as $key => $value) {
          if ($value['id'] == $subsId) {
            $subsId = $value['id'];
            $isFound = true;
            break;
          }
        }

        if (!$isFound) {
          $response['error_message']  = "You have no active subscription. you can not perform this action.";
          $response['success'] = false;
          return response($response);die;
        }

        try {
          if ($post['period'] == 0) {

            $subscription       = $stripe->subscriptions()->cancel($stripe_cust_id, $subsId);
            $response['success_message']  = "Your subscription unsubscribed successfully !";
            $canceled_at = 1;
            $up = ['status'=>'Canceled','canceled_at'=>$canceled_at,'cancelDate'=>$subscription['canceled_at']];
            $canceled_at_message = 'Subscrition cancelled at '.gmdate(' d-m-Y', ($subscription['canceled_at']));
          }
          else if ($post['period'] == 1) {
            $subscriptioncheck = $stripe->subscriptions()->find($stripe_cust_id, $subsId);
            if (!empty($subscriptioncheck)) {
              if (trim($subscriptioncheck['canceled_at']) != '') {
                $response['success_message']  = "Your subscription unsubscribed successfully! subscription canceled at ".gmdate(' d-m-Y', ($subscriptioncheck['canceled_at']));
                $response['success']          = true;
                $response['canceled_at']      = 2;
                return response($response);die;
              }
            }
            $subscription = $stripe->subscriptions()->update($stripe_cust_id, $subsId,[
                'cancel_at_period_end' => true,
            ]);
            $canceled_at = 2;
            $up = ['canceled_at'=>$canceled_at,'cancelDate'=>$subscription['current_period_end']];
            $canceled_at_message = 'Subscription will be cancelled at the end of current period on '.gmdate(' d-m-Y', ($subscription['current_period_end']));
            $response['success_message']  = "Your subscription unsubscribed successfully! subscription canceled at ".gmdate('d-m-Y', ($subscription['current_period_end']));
          }
          else{
            $response['success'] = false;
            $response['error_message'] =  'Something went wrong please try again..';
          }
          if (!empty($subscription)) {
              $res                                  = DB::table('user_payments')->where('sub_id',$subsId)->update($up);
              $response['success']                  = true;
              $response['canceled_at']              = $canceled_at;
              $response['canceled_at_message']      = $canceled_at_message;
              $response['subsId']                   = $subsId;
          }
        } catch (\Exception $e) {
          $response['success'] = false;
          $response['error_message'] =  $e->getMessage();
        }
    	}
      else{
        $response['success'] = false;
        $response['error_message'] = 'Invalid Request';
      }
    	return response($response);
    }
    public function changeStatus(Request $request){
        $record_id = $request->post('id');
        $table     = Crypt::decrypt($request->post('table'));
        $status    = $request->post('status');
        if($status==1){
            $sts = 0;
        }else{
            $sts = 1;
        }
        $result = DB::table($table)->where('id',$record_id)->update(['status'=>$sts]);
        if($result){
            $response['success'] = true;
            $response['success_message'] = 'Record status changed successfully !!!';
        }else{
            $response['success'] = true;
            $response['success_message'] = 'Record status changed successfully !!!';
        }
        return response($response);
    }
    public function setUserStatus()
    {
        $users=User::whereHas('roles',function($q){
              $q->where('name','customer');
        });
        $user_data = $users->get();

        //$users = RoleUser::get();
        echo '<pre>';
        print_r($user_data->toArray());
        die;
    }
    public function testForm(){
        return view('welcome');
    }
    public function testFormSubmit(Request $request)
    {
        $post = array_except($request->all(),'_token');
        $data['name'] = $post['name'];
        $data['email'] = $post['email'];
        $data['phone'] = $post['phone'];
        $data['txtmessage'] = $post['message'];
        // echo '<pre>'; print_r($data);
        //  die;
        $res = Helpers::sendmail(env('MAIL_FROM'),env('APP_NAME'),$post['mailto'],$post['name'],'Test Mail - Direct Connect',$data,'emails.testMail');
        if($res){
            echo 'Mail Sent !!!';
        }else{
            echo 'Failed to send mail !!!';
        }
    }
}
