<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\GlobalFunctions as Helpers;
use App\Model\UserPlan;
use App\Model\Plan;
use App\Model\Region;
use App\Model\Campaign;
use App\Model\CallSetting;
use App\User;
use Crypt;
use Curl;

class DashboardController extends Controller
{

    public $url;
    public $prefix;
    public $currentUrl;
    public $admintemplatename;
    public function __construct()
    {
          $this->prefix='admin';
          $this->currentUrl = Helpers::getCurrentUrl();
          $this->url=url(request()->route()->getPrefix());
          $this->admintemplatename = config('app.admintemplatename');
          $this->perPage = 10;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currentUrl = Helpers::getCurrentUrl();
        $records = array();
        $customers = UserPlan::get()->count();
        $records['customers'] = $customers;
        $plans = Plan::where('is_deleted',0)->get()->count();
        $records['plans'] = $plans;
        $regions = Region::where('is_deleted',0)->get()->count();
        $records['regions'] = $regions;
        $campaigns = Campaign::where('is_deleted',0)->get()->count();
        $records['campaigns'] = $campaigns;
        return view($this->prefix.'/dashboard/index',['url'=>$this->url,'current_url'=>$currentUrl,'records'=>$records]);
    }
    public function reporting(Request $request)
    {
      $customers = User::where('register_step',3)->orderBy('id','DESC')->paginate($this->perPage);
      if ($request->ajax())
      {

        $page =  ( $request['page'] - 1 ) * $this->perPage;
          return view($this->admintemplatename.'/reporting/records_list',['page'=>$page,'url'=>$this->url,'current_url'=>$this->currentUrl,'customers'=>$customers,'page'=>$page]);
      }
      else
      {
          return view($this->admintemplatename.'/reporting/index',['url'=>$this->url,'current_url'=>$this->currentUrl,'customers'=>$customers]);
      }
    }
    public function campaignReports(Request $request,$userId)
    {
      $userId = Crypt::decrypt($userId);
      $dateFrom = str_replace('/','-',$request->get('date_from'));
      $dateTo = str_replace('/','-',$request->get('date_to'));
      if(empty($dateFrom) && empty($dateFrom))
      {
        $path = env('API_BASEPATH').'department/';
      }
      else
      {
        $path = env('API_BASEPATH').'department/?start_date='.$dateFrom.'&end_date='.$dateTo;
      }
      $user = User::where('id',$userId)->first();
      $userEmail = $user->email;
      $userPass = Crypt::decrypt($user->enc_password);
      // $userEmail = 'pardeep@gmail.com';
      // $userPass = '123456';
      $data = Curl::to($path)
      ->withContentType('application/json')
      ->withOption('USERPWD', $userEmail . ":" . $userPass)
      ->get();
      $data = json_decode($data);
      // echo '<pre>'; print_r($data); die;
      return view($this->admintemplatename.'/reporting/campaignReports',['data'=>$data,'current_url'=>$this->currentUrl,'userID'=>$userId]);
    }
    public function leadReports(Request $request,$userId)
    {
      $userId = Crypt::decrypt($userId);
      $dateFrom = str_replace('/','-',$request->get('date_from'));
      $dateTo = str_replace('/','-',$request->get('date_to'));
      if(empty($dateFrom) && empty($dateFrom))
      {
        $path = env('API_BASEPATH').'department/';
      }
      else
      {
        $path = env('API_BASEPATH').'department/?start_date='.$dateFrom.'&end_date='.$dateTo;
      }
      $user = User::where('id',$userId)->first();
      $userEmail = $user->email;
      $userPass = Crypt::decrypt($user->enc_password);
      $data = Curl::to($path)
      ->withContentType('application/json')
      ->withOption('USERPWD', $userEmail . ":" . $userPass)
      ->get();
      $data = json_decode($data);
      return view($this->admintemplatename.'/reporting/leadReports',['data'=>$data,'current_url'=>$this->currentUrl,'userID'=>$userId,'url'=>$this->url]);
    }
    //Call Settings
    public function callSettingsView()
    {
      $callSetting = CallSetting::where('user_id',\Auth::user()->id)->first();
      return view($this->admintemplatename.'/dashboard/settings',['current_url'=>$this->currentUrl,'callSetting'=>$callSetting]);
    }
    public function callSettings(Request $request)
    {
      $boolArr = array('',true,false);
      $post = array_except($request->all(),['_token']);
      $user = User::where('id',\Auth::user()->id)->first();
      $post = $postData = $post;
      $postData['user_id'] = $user->id;
      if(isset($post['call_recording_display'])){
        $post['call_recording_display'] = $boolArr[$post['call_recording_display']];
      }
      if(isset($post['call_announcement_email'])){
        $post['call_announcement_email'] = $boolArr[$post['call_announcement_email']];
      }
      $rCount = CallSetting::where('user_id',$user->id)->count();
      if($rCount==0){
        CallSetting::insert($postData);
        $response['success']=true;
        $response['success_message']="Call settings added successfully.";
      }else{
        CallSetting::where('user_id',$user->id)->update($postData);
        $response['success']=true;
        $response['success_message']="Call settings updated successfully.";
      }
      $request->session()->flash('error',json_encode($response));
      return \Redirect::back();
    }

}
