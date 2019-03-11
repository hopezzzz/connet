<?php
namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\GlobalFunctions as Helpers;
Use App\Model\Campaign;
Use App\Model\Country;
Use App\Model\Contact;
Use App\Model\Parsetag;
Use App\Model\CampaignsContacts;
Use App\Model\ParseEmail;
Use App\Model\ApiLeads;
Use App\User;
use Crypt;
use Curl;
use DB;

class CampaignController extends Controller
{
    public function __construct()
    {
      $this->url=url(request()->route()->getPrefix());
      $this->currentUrl = Helpers::getCurrentUrl();
      $this->customertemplatename = config('app.customertemplatename');
      $this->admintemplatename = config('app.admintemplatename');
      $this->domainEmail = env('WEBSITE_MAIL_DOMAIN_EMAIL');
      $this->domain = env('WEBSITE_MAIL_DOMAIN');

    }
    public function index(Request $request)
    {
      $camps = Campaign::where('is_deleted',0)->where('user_id',Auth()->user()->id)->orderBy('id','DESC')->paginate(10);
      if ($request->Ajax()) {
        $page =  ( $request['page'] - 1 ) * 10;
        if ( isset($_GET['searchKey']) ) {
          $searchKey = $request->get('searchKey');
          $camps  = Campaign::where('is_deleted',0)
          ->where(function($query) use ($searchKey)
          {
           $query->where('title', 'like', '%' . $searchKey . '%')
           ->orWhere('email', 'like', '%' . $searchKey . '%');
          })->where('user_id', \Auth::user()->id)
          ->orderBy('id','DESC')->paginate(10);
        }
        return view($this->customertemplatename.'/campaign/recordListAjax',['page'=> $page, 'url'=>$this->url,'current_url'=>$this->currentUrl,'camps'=>$camps]);
      }else{
        return view($this->customertemplatename.'/campaign/index',['url'=>$this->url,'current_url'=>$this->currentUrl,'camps'=>$camps]);
      }
    }
    public function addCampaignView()
    {
      $campArr = array();
      $codeList = Country::select(\DB::raw("CONCAT(countryName,' - (',countryPhoneCode,')') AS country"),'countryId')->where('countryPhoneCode','!=','')->orderBy('country')->get()->pluck('country','countryId')->toArray();
      $codeList = array_prepend($codeList,'Select Country Code','');
      $countryList = Country::pluck('countryName','countryId')->prepend('Select Country','');
      $campArr['contactCount'] = Contact::where('user_id',\Auth::user()->id)->get()->count();
      $campArr['campContacts'] = 0;
      $campArr['exContacts'] = $campArr['contactCount'];
      return view($this->customertemplatename.'/campaign/add_campaign',['countryCode'=>$codeList,'url'=>$this->url,'current_url'=>$this->currentUrl,'countryList'=>$countryList , 'campData' => $campArr]);
    }
    public function editCampaignView(Request $request,$campId)
    {
      $campId       = Crypt::decrypt($campId);
      $countryList  = Country::orderBy('countryName','ASC')->pluck('countryName','countryId')->prepend('Select Country','');
      $campData     = Campaign::with('campaigns_contacts.contact','campaigns_tags')->where('id',$campId)->first();
      $country      = Country::select('countryName')->where('countryId',$campData->country_id)->first();
      $codeList = Country::select(\DB::raw("CONCAT(countryName,' - (',countryPhoneCode,')') AS country"),'countryId')->where('countryPhoneCode','!=','')->orderBy('country')->get()->pluck('country','countryId')->toArray();
      $codeList = array_prepend($codeList,'Select Country Code','');
      if(is_null($country) ){
            $response['success']       = false;
            $response['delayTime']     = 1000;
            $response['error_message'] = "Something went wrong please try again !!!";
            // $request->session()->put('errors', json_encode($response));
            // $data = $request->session()->all();
      }else{
        $country = $country->toArray();
      }
      $contactCount = Contact::where('user_id',\Auth::user()->id)->get()->count();
      $campContactCount = CampaignsContacts::where('campId',$campId)->get()->count();
      $c = Contact::whereDoesntHave('campaigns',function($r) use($campId){
        $r->where('campId',$campId);
      });
      $exContCount = $c->where('user_id',\Auth::id())->where('is_deleted',0)->count();
      $viewArr = array(
                  'campaignsTitle'      =>  $campData->title,
                  'campaignTemplate'    =>  $campData->template,
                  'campaignCountry'     =>  $campData->country_id,
                  'email'               =>  $campData->email,
                  'testMail'            =>  $campData->testMail,
                  'campaignPhone'       =>  $campData->phone,
                  'step'                =>  $campData->step,
                  'api_dept_id'         =>  $campData->api_dept_id,
                  'parserOutput'        =>  $campData->parserOutput,
                  'delayTime'           =>  $campData->delayTime,
                  'campaignCountryName' =>  $country['countryName'],
                  'campaigns_tags'      =>  $campData->campaigns_tags,
                  'available_days'      =>  json_decode($campData->available_days),
                  'campId'              =>  $campData->id,
                  'availHoursFrom'      =>  json_decode($campData->available_hours)->from,
                  'availHoursTo'        =>  json_decode($campData->available_hours)->to,
                  'breakHoursFrom'      =>  json_decode($campData->break_hours)->from,
                  'breakHoursTo'        =>  json_decode($campData->break_hours)->to,
                  'campaignContact'     =>  $campData->campaigns_contacts,
                  'campaignTags'        =>  $campData->campaigns_tags,
                  'contactCount'        =>  $contactCount,
                  'campContacts'        =>  $campContactCount,
                  'exContacts'           =>  $exContCount
                );
               // echo '<pre>'; print_r($viewArr);die;
      return view($this->customertemplatename.'/campaign/add_campaign',['countryCode'=>  $codeList,'url'=>$this->url,'current_url'=>$this->currentUrl,'countryList'=>$countryList,'campData'=>$viewArr]);
    }

    public function addCampaignData(Request $request)
    {
      $userId = \Auth::user()->id;
      $post = $request->except(['_token']);
      $chkId = '';

      $whereData = [
        ['title', '=', $_POST['campaignsTitle'] ] ,
        ['user_id', '=', $userId ]
      ];

      if(!empty($post['campId'])){
          $chkId = Campaign::where('id',$post['campId'])->first()->step;
          $whereData = [
            ['id', '!=', $_POST['campId'] ],
            ['title', '=', $_POST['campaignsTitle'] ],
            ['user_id', '=', $userId ]
          ];

      }
      $checkExits = Helpers::checkExits('campaigns',$whereData);
      if($checkExits == 1){
         $response['success'] = false;
         $response['error_message']    = 'This Campaign name already exists !';
         $response['delayTime']        = 2000;
         return response($response);exit;
      }
      $val = \Validator::make($request->all(),[
        'campaignsTitle'    => 'required'
      ]);
      if($val->fails()){
        $errors                = $val->errors();
        $response['success']   = false;
        $response['formErrors']= true;
        $response['errors']    = $errors;
      }
      else
      {
        switch($post['step'])
        {
          case 'step1':
            if(empty($post['campId'])){
              $email   =  strtolower(Helpers::generateRef(10));
              $pass    =  strtolower(Helpers::generateRef(15));
              $webMail =  Helpers::createEmail($email,$pass);
              if($webMail->cpanelresult->data[0]->result==1)
              {
                $data1                        = array('title'=>$post['campaignsTitle'],'email'=>$email.$this->domainEmail,'password'=>$pass,'user_id' => $userId);
                $campId                       = Campaign::insertGetId($data1);
                Campaign::where('id',$campId)->update(array('step'=>1));
                $response['success']          = true;
                $response['campId']           = $campId;
                $response['success_message']  = 'Campaign step 1 created successfully !!!';
                $response['elementShow']      = '.to_display';
                $response['campEmail']        = $email.$this->domainEmail;
                $response['delayTime']        = 2000;
                if(trim($chkId) != 4 ){
                  $response['step']            =  1;
                }
              }
              else
              {
                $response['success'] = false;
                $response['error_message']    = 'Error occured while creating webmail !';
                $response['delayTime']        = 2000;
              }
            }else{
              $result = Campaign::where('id',$post['campId'])
              ->update(array('title'=>$post['campaignsTitle']));
              $campTags = Parsetag::where('campId',$post['campId'])->get();
              if(empty($campTags->toArray())){
                $response['error_message']    = 'Please set parse tags first then try next.';
                $response['delayTime']        = 2000;
                return response($response); exit;
              }

              $findTag = array_column($campTags->toArray(), 'tagName');
              $findTag = array_map('strtolower', $findTag);
              if (!in_array("phone", $findTag)){
              $response['success']          = false;
                $response['error_message']    = 'Phone Tag Is Required.';
                $response['delayTime']        = 2000;
                return response($response);exit;
              }

              $tags ='<div class="clearfix mb-3">';
              if($campTags){
                foreach($campTags as $campTag)
                {
                  $tags .= '<span style="cursor:pointer;" class="badge badge-pill badge-primary mr-1 tagSpan" data-val="{'.$campTag->tagName.'}">'.$campTag->tagName.'</span>';
                }
              }
               $tags .= '</div>';
              if($result){
                $response['success']          = true;
                $response['campaign']          = true;
                $response['success_message']  = 'Campaign step 1 updated successfully !';
                $response['elementShow']      = '.to_display';
                $response['delayTime']        = 2000;
                $response['toCampTags']         = $tags;
              }else{
                $response['success']          = false;
                $response['error_message']    = 'Error occured while updating step 1 !';
                $response['delayTime']        = 2000;
              }
            }
          break;
          case 'step2':
            if($post['campId']!=0 && !empty($post['campId'])){
              if(trim($chkId) != 4 ){ $ii = 2;
              }else{ $ii = 4;}

              $result = Campaign::where('id',$post['campId'])
              ->update(array('template'=>$post['campaignTemplate'],'step'=>$ii));
              if($result){
                $response['success'] = true;
                $response['campaign']          = true;
                $response['success_message'] = 'Campaign step 2 created successfully !';
                $response['delayTime'] = 2000;
                if(trim($chkId) != 4 ){
                 $response['step']            =  2;
                }
              }else{
                $response['success'] = false;
                $response['error_message'] = 'Error occured while creating step 2 !';
                $response['delayTime'] = 2000;
              }
            }
          break;
          case 'step3':
            $count = CampaignsContacts::where('campId',$post['campId'])->get()->count();
            if($count > 0)
            {
              if(trim($chkId) != 4 ){ $ii = 3;
              }else{ $ii = 4;}

              $result = Campaign::where('id',$post['campId'])->update(['step'=>$ii]);
              if($result)
              {
                  $response['success'] = true;
                  $response['campaign']          = true;
                  $response['success_message'] = 'Campaign step 3 created successfully !';
                  $response['stepssss']            =  $ii;
                  $response['chkId']            =  $chkId;
                  if(trim($chkId) != 4 ){
                   $response['step']            =  3;
                  }
              }
              else
              {
                $response['success'] = false;
                $response['error_message'] = 'Error occured while creating step 3 !';
                $response['delayTime'] = 2000;
              }
            }
            else
            {
              $response['success'] = false;
              $response['error_message'] = 'Please add atleast 1 contact to this campaign !';
              $response['delayTime'] = 2000;
              //$response['delayTimewwww'] = 2000;
            }
          break;
          case 'step4':
            if($post['campId']!=0 && !empty($post['campId'])){
              if ( ($post['breakHoursTo'] == $post['availHoursTo'] ) && ($post['availHoursFrom'] == $post['breakHoursFrom'])) {

                  $response['success'] = false;
                  // $response['formErrors'] = true;
                  $response['error_message'] = 'Break Hours Can not be same as Available Hours' ;
                  return response($response);
              }
              if ( !isset($post['availDays']) ) {
                  $response['success'] = false;
                  // $response['formErrors'] = true;
                  $response['error_message'] = 'Please select available days.' ;
                  return response($response);
              }

              $campaignemails = Campaign::where('id',$post['campId'])->first();
              $parserOutput = Helpers::parserOut($campaignemails->testMail, $campaignemails->template,$campaignemails->id);

              $availHours = array('from'=>$post['availHoursFrom'],'to'=>$post['availHoursTo']);
              $availHours = json_encode($availHours);
              $breakHours = array('from'=>$post['breakHoursFrom'],'to'=>$post['breakHoursTo']);
              $breakHours = json_encode($breakHours);
              $days = json_encode($post['availDays']);
              $result = Campaign::where('id',$post['campId'])
              ->update(array(
                'country_id'=>$post['campaignCountry'],
                'available_days'=>$days,
                'available_hours'=>$availHours,
                'break_hours'=>$breakHours,
                'delayTime'=>$post['delayTime'],
                'step'=>4,
                'parserOutput'   => $parserOutput
              ));
              if($result){
                $response['success'] = true;
                $response['campaign']          = true;
                $response['success_message'] = 'Campaign step 4 created successfully !';
                $response['step']      = 4;
                $day = '';
                $dayArr = array('mon'=>'Monday','tue'=>'Tuesday','wed'=>'Wednesday','thu'=>'Thursday','fri'=>'Friday','sat'=>'Saturday','sun'=>'Sunday');
                foreach($post['availDays'] as $days){
                   $day .= $dayArr[$days].', ';
                }
                $day = rtrim($day,", ");
                $countryName = Country::where('countryId',$post['campaignCountry'])->first();
                $response['delayTime'] = 2000;
                $campContact = CampaignsContacts::where('campId',$post['campId'])->get();
                $response['responseData'] = '<div class="col-md-12">
                <table class="table campTable">
                    <thead>
                        <tr>
                            <th style="width:20% !important"></th>
                            <th style="width:80%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="details-user"><b>Campaign Title :</b></span></td>
                            <td><span class="details_now">  '. $post['campaignsTitle'] .'  </span></td>
                        </tr>

                        <tr>
                            <td><span class="details-user"><b>Campaign Template :</b></span></td>
                            <td><span class="details_now">  '. trim(preg_replace('/\s\s+/', ' ', $post['campaignTemplate']))  .'  </span></td>
                        </tr>

                       <tr>
                            <td><span class="details-user"><b>Parse Outout :</b></span></td>
                            <td><span class="details_now">  '. trim(preg_replace('/\s\s+/', ' ', $parserOutput ))  .'  </span></td>
                        </tr>

                        <tr>
                            <td><span class="details-user"><b>Country :</b></span></td>
                            <td><span class="details_now">  '.  $countryName->countryName .'  </span></td>
                        </tr>
                        <tr><td><span class="details-user"><b>Available Days :</b></span></td>
                            <td><span class="details_now">'.$day.'</span> </td>
                        </tr>
                        <tr>
                            <td><span class="details-user"><b>Available Hours :</b></span></td>
                            <td>
                            <span class="details_now">
                              '.$post['availHoursFrom'] .' - '. $post['availHoursTo'] .'
                              <br>

                            </span>
                            </td>
                        </tr>
                        <tr>
                           <td><span class="details-user"><b>Call Delay Time :</b></span></td>
                           <td><span class="details_now">'.$post['delayTime'].'</td>
                        </tr>

                        <tr class="d-none"><td><span class="details-user"><b>Break Hours :</b></span></td>
                            <td><span class="details_now">'. $post['breakHoursFrom'] .' - '. $post['breakHoursTo'] .'</span> </td>
                        </tr>
                        <tr><td><span class="details-user"><b>Campaign Contacts :</b></span></td><td></td>
                        </tr>
                    </tbody>
                </table>
                <div class="col-md-12">
                <table class="table table-striped">
                <thead>
                  <tr><th>Sr</th><th>Name</th><th>Phone Number</th></tr>
                </thead>
                <tbody>
                ';
                $i = 0;
                foreach($campContact as $ct)
                {
                  $i++;
                  $response['responseData'] .= '<tr><td>'.$i.'</td><td>'.$ct->contact->name.'</td><td>'.$ct->contact->contact.'</td></tr>';
                }
                $response['responseData']   .= '</tbody></table></div></div>';
              }else{
                $response['success']        = false;
                $response['error_message']  = 'Error occured while creating step 4 !';
                $response['delayTime']      = 2000;
              }
            }
          break;
        }
        $pkg = Campaign::where('step',4)->where('id',$post['campId'])->first();
        $userEmail = \Auth::user()->email;
        $userPass = Crypt::decrypt(\Auth::user()->enc_password);
        if(isset($pkg))
        {
          if($pkg->api_dept_id==0)
          {
            $data = array(
              'name'      => $post['campaignsTitle'],
              'user_email'=> \Auth::user()->email,
              'is_active' => "true"
            );
            $api_result = Curl::to(env('API_BASEPATH').'department/')
            ->withContentType('application/json')
            ->withOption('USERPWD', $userEmail. ":" .$userPass)
            ->returnResponseObject()
            ->withData($data)->asJson()->post();

            if($api_result->status==201)
            {
              Campaign::where('id',$post['campId'])->update(['api_dept_id'=>$api_result->content->id]);
              $addFwdEmail = Campaign::where('id',$post['campId'])->first();
              Helpers::addFwdEmail($addFwdEmail->email);
              $campCont = CampaignsContacts::where('campId',$post['campId'])->get();
              foreach ($campCont as $cc) {
                $code = $cc->contact->country->countryPhoneCode;
                $code = ltrim($code,'+');
                $phoneNumber = $code.''.$cc->contact->contact;
                $name = $cc->contact->name;
                $contact_email = $cc->contact->email;
                $location = $cc->contact->dept;
                //Set Phone
                $data = array(
                  "department_id" =>  $api_result->content->id,
                  "email"         =>  $userEmail,
                  "number"        =>  $phoneNumber,
                  "name"          =>  $name,
                  "email_id"      =>  $contact_email,
                  "location"      =>  $location
                );
                $phone_res = Curl::to(env('API_BASEPATH').'phone/')
                ->withContentType('application/json')
                ->withOption('USERPWD', $userEmail. ":" .$userPass)
                ->returnResponseObject()
                ->withData($data)->asJson()->post();
                //Update table
                CampaignsContacts::where('id',$cc->id)
                ->update(['api_phone_id'=>$phone_res->content->id]);
              }
            }
          }
          if($pkg->api_dept_id!=0)
          {
            $data = array(
              'name'=>$post['campaignsTitle']
            );
            $api_result = Curl::to(env('API_BASEPATH').'department/'.$pkg->api_dept_id.'/')
            ->withContentType('application/json')
            ->withOption('USERPWD', env('ADMIN_USER'). ":" .env('ADMIN_PASS'))
            ->returnResponseObject()
            ->withData($data)->asJson()->patch();
            $campCont = CampaignsContacts::where('campId',$post['campId'])->get();
            foreach ($campCont as $cc) {
              if($cc->api_phone_id==0)
              {
                $code = $cc->contact->country->countryPhoneCode;
                $code = ltrim($code,'+');
                $phoneNumber = $code.''.$cc->contact->contact;
                $name = $cc->contact->name;
                $contact_email = $cc->contact->email;
                $location = $cc->contact->dept;
                //Set Phone
                $data = array(
                  "department_id" =>  $pkg->api_dept_id,
                  "email"         =>  $userEmail,
                  "number"        =>  $phoneNumber,
                  "name"          =>  $name,
                  "email_id"      =>  $contact_email,
                  "location"      =>  $location
                );
                $phone_res = Curl::to(env('API_BASEPATH').'phone/')
                ->withContentType('application/json')
                ->withOption('USERPWD', $userEmail. ":" .$userPass)
                ->returnResponseObject()
                ->withData($data)->asJson()->post();
                //Update table
                CampaignsContacts::where('id',$cc->id)
                ->update(['api_phone_id'=>$phone_res->content->id]);
              }
            }
            //die;
          }
        }
      }
      return response($response);exit;
    }

    public function campaignLeadsList(Request $request, $id){
      $id = Crypt::decrypt($id);
      $res = Campaign::where('id',$id)->first();
      $userEmail = $res->user_detail->email;
      $userPass = Crypt::decrypt($res->user_detail->enc_password);
      $dept = urlencode($res->title);
      $path = env('API_BASEPATH').'calllog/?department__name='.$dept.'&limit=10&offset=0';
      $response = Curl::to($path)
      ->withContentType('application/json')
      ->withOption('USERPWD', $userEmail . ":" . $userPass)
      ->get();
      $ds = json_decode($response);
      $item_per_page  = $ds->meta->limit;
      $current_page   = $ds->meta->offset / $ds->meta->limit;
      $total_records  = $ds->meta->total_count;
      $total_pages    = $ds->meta->total_count / $ds->meta->limit;
      $page_url       = '/api/v1/calllog/';
      $prev_url       = $ds->meta->previous;;
      $next_url       = $ds->meta->next;

      $pagination = Helpers::paginate($item_per_page, $current_page, $total_records, $total_pages, $page_url,  $prev_url , $next_url, $id);

      return view($this->admintemplatename.'/customers/campaignLeadsList',['url'=>$this->url,'current_url'=>$this->currentUrl,'leadsData'=>json_decode($response),'campData'=>$res,'pagination'=>$pagination]);
    }
    public function campaignLeadsCustomer(Request $request, $id)
    {
      $role = \Auth::User()->roles()->first()->name;
      $id = Crypt::decrypt($id);
      $res = Campaign::where('id',$id)->first();
      $userEmail = $res->user_detail->email;
      $userPass = Crypt::decrypt($res->user_detail->enc_password);
      $dept = urlencode($res->title);
      $path = env('API_BASEPATH').'calllog/?department__name='.$dept;
      $response = Curl::to($path)
      ->withContentType('application/json')
      ->withOption('USERPWD', $userEmail . ":" . $userPass)
      ->get();
      $ds = json_decode($response);
      $count = $ds->meta->total_count;
      //hitting again API to get all the leads data
      $path = env('API_BASEPATH').'calllog/?department__name='.$dept.'&limit='.$count.'&offset=0';
      $response = Curl::to($path)
      ->withContentType('application/json')
      ->withOption('USERPWD', $userEmail . ":" . $userPass)
      ->get();
      $ds = json_decode($response);
      $leadArr = json_decode(json_encode($ds->objects), true);
      array_multisort(array_column($leadArr, "id"), SORT_ASC, $leadArr);
      $dbLeads = ApiLeads::where('campaign_id',$id)->orderBy('lead_id','DESC')->first();
      if($dbLeads)
      {
        $key = array_search($dbLeads->lead_id, array_column($leadArr, 'id'));
        $newLeadsArr = array_slice($leadArr,$key+1);
        foreach ($newLeadsArr as $value)
        {
          $leadData = array(
            'lead_id'     => $value['id'],
            'campaign_id' => $id,
            'agent'       => $value['agent'],
            'call_length' => $value['call_length'],
            'cost'        => $value['cost'],
            'department'  => $value['department'],
            'recording'   => $value['recording'],
            'resource_uri'=> $value['resource_uri'],
            'sc1'         => $value['sc1'],
            'sc2'         => $value['sc2'],
            'startdate'   => date("Y-m-d H:i:s", strtotime($value['startdate'])),
            'ticket_id'   => $value['ticket_id'],
            'transcripts' => $value['transcripts'],
            'whisper'     => $value['whisper'],
            'call_status' => $value['call_status']
          );
          ApiLeads::insert($leadData);
        }
      }
      else
      {
        if(!empty($leadArr))
        {
          foreach ($leadArr as $value) {
            $leadData = array(
              'lead_id'     => $value['id'],
              'campaign_id' => $id,
              'agent'       => $value['agent'],
              'call_length' => $value['call_length'],
              'cost'        => $value['cost'],
              'department'  => $value['department'],
              'recording'   => $value['recording'],
              'resource_uri'=> $value['resource_uri'],
              'sc1'         => $value['sc1'],
              'sc2'         => $value['sc2'],
              'startdate'   => date("Y-m-d H:i:s", strtotime($value['startdate'])),
              'ticket_id'   => $value['ticket_id'],
              'transcripts' => $value['transcripts'],
              'whisper'     => $value['whisper'],
              'call_status' => $value['call_status']
            );
            ApiLeads::insert($leadData);
          }
        }
      }
      $dateFrom = $request->get('date_from');
      if($dateFrom!='')
      {
        $df       = explode('/', $dateFrom);
        $dateFrom = $df[2].'-'.$df[0].'-'.$df[1];
      }
      $dateTo   = $request->get('date_to');
      if($dateTo!='')
      {
        $dt       = explode('/', $dateTo);
        $dateTo = $dt[2].'-'.$dt[0].'-'.$dt[1];
        $dateTo = date('Y-m-d', strtotime("+1 day", strtotime($dateTo)));
      }
      $l = ApiLeads::where('campaign_id',$id);
      if($dateFrom!='' && $dateTo=='')
      {
        $l->where('startdate','>=',$dateFrom);
      }
      elseif($dateFrom=='' && $dateTo!='')
      {
        $l->where('startdate','<=',$dateTo);
      }
      elseif($dateFrom!='' && $dateTo!='')
      {
        $l->where('startdate','>=',$dateFrom)->where('startdate','<=',$dateTo);
      }
      $leads = $l->orderBy('lead_id','DESC')->paginate(10);

      if ($request->Ajax())
      {
        $page =  ( $request['page'] - 1 ) * 10;
        if ( isset($_GET['searchKey']) )
        {
          $searchKey = $request->get('searchKey');
          $leads  = ApiLeads::where(function($query) use ($searchKey)
          {
            $query->where('whisper', 'like', '%' . $searchKey . '%')
            ->orWhere('lead_id', 'like', '%' . $searchKey . '%')
            ->orWhere('agent', 'like', '%' . $searchKey . '%');
          })->where('campaign_id',$id)->orderBy('id','DESC')->paginate(10);
        }
        if($role == 'admin')
        {
          return view($this->admintemplatename.'/customers/campleads',['page'=> $page,'url'=>$this->url,'current_url'=>$this->currentUrl,'leadsData'=>$leads,'campData'=>$res]);
        }
        else
        {
          return view($this->customertemplatename.'/campaign/leadsListAjax',['page'=> $page, 'url'=>$this->url,'current_url'=>$this->currentUrl,'leadsData'=>$leads]);
        }
      }
      else
      {
        if($role == 'admin')
        {
          return view($this->admintemplatename.'/customers/campaignLeadsList',['url'=>$this->url,'current_url'=>$this->currentUrl,'leadsData'=>$leads,'campData'=>$res]);
        }
        else
        {
          return view($this->customertemplatename.'/campaign/leads',['url'=>$this->url,'current_url'=>$this->currentUrl,'leadsData'=>$leads,'campData'=>$res]);
        }
      }

      // $dateFrom = $request->get('date_from');
      // if($dateFrom!='')
      // {
      //   $df       = explode('/', $dateFrom);
      //   $dateFrom = $df[2].'-'.$df[0].'-'.$df[1];
      // }
      // $dateTo   = $request->get('date_to');
      // if($dateTo!='')
      // {
      //   $dt       = explode('/', $dateTo);
      //   $dateTo = $dt[2].'-'.$dt[0].'-'.$dt[1];
      //   $dateTo = date('Y-m-d', strtotime("+1 day", strtotime($dateTo)));
      // }
      // $id = Crypt::decrypt($id);
      // $res = Campaign::where('id',$id)->first();
      // $userEmail = $res->user_detail->email;
      // $userPass = Crypt::decrypt($res->user_detail->enc_password);
      // $dept = urlencode($res->title);
      // if($dateFrom!='' && $dateTo==''){
      //   $path = env('API_BASEPATH').'calllog/?startdate__gte='.$dateFrom.'&department__name='.$dept.'&limit=10&offset=0';
      // }elseif($dateFrom=='' && $dateTo!=''){
      //   $path = env('API_BASEPATH').'calllog/?startdate__lte='.$dateTo.'&department__name='.$dept.'&limit=10&offset=0';
      // }elseif($dateFrom!='' && $dateTo!=''){
      //   $path = env('API_BASEPATH').'calllog/?startdate__gte='.$dateFrom.'&startdate__lte='.$dateTo.'&department__name='.$dept.'&limit=10&offset=0';
      // }else{
      //   $path = env('API_BASEPATH').'calllog/?department__name='.$dept.'&limit=10&offset=0';
      // }
      // $response = Curl::to($path)
      // ->withContentType('application/json')
      // ->withOption('USERPWD', $userEmail . ":" . $userPass)
      // ->get();
      // $ds = json_decode($response);

      // $item_per_page  = $ds->meta->limit;
      // $current_page   = $ds->meta->offset / $ds->meta->limit;
      // $total_records  = $ds->meta->total_count;
      // $total_pages    = $ds->meta->total_count / $ds->meta->limit;
      // $page_url       = '/api/v1/calllog/';
      // $prev_url       =  $ds->meta->previous;;
      // $next_url       = $ds->meta->next;
      // $pagination = Helpers::paginate($item_per_page, $current_page, $total_records, $total_pages, $page_url,  $prev_url , $next_url, $id);

      // return view($this->customertemplatename.'/campaign/leads',['url'=>$this->url,'current_url'=>$this->currentUrl,'leadsData'=>json_decode($response),'campData'=>$res,'pagination'=>$pagination]);
    }

    public function fetchCampaignEmail(Request $request)
    {
      $post = $request->all();;
      if ($request->ajax()) {

           $emails = Campaign::where('id',$post['refrence'])->first();
           $readMail = Helpers::readEmails(trim($this->domain),trim($emails->email),trim($emails->password));
           if(empty($readMail)){
             sleep(60);
             $readMail = Helpers::readEmails(trim($this->domain),trim($emails->email),trim($emails->password));
           }

          if (!empty($readMail)) {

            $response['success']         = true;
            $response['success_message'] = 'Test mail received successfully !!!';
            $mailHeader = '<div class="col-md-12 form-group text-left" style="font-size: 14px; color: red;">
            INSTRUCTIONS:<br>
1. Please highlight the form responses that you would like used in your call script. You will only need to highlight the response, not the field name.<br>
2. After highlighting the response, please create a "Tag Name" for this field.<br>
3. Please ensure you highlight the customers phone number, and call this tag "Phone" - This step is important as our system uses this tag to trigger the call to the customer.<br>
4. Please ensure you highlight the call summary, and call this tag "Message" - This step is important as our system uses this tag to read the full call summary  to the customer.<br>
5. Please highlight all relevant answers, then click "next".
            </div>';
            $mailHeader .= '<label for=""><b> Mail received from '.$emails->email.' </b></label><br><div id="textDescription">';
            $lines = explode(PHP_EOL, $readMail[0]['body']);
            $mailContent = '';
            foreach ($lines as $line) {
              if (trim($line) !='') {
                  $line = preg_replace("/\s/",' ',$line);
                  $line = \Html2Text\Html2Text::convert($line);
                  $mailContent .= '<p class="testMail">'.Helpers::RemoveSpecialChapr($line).'</p>';
              }
            }
            if(trim($mailContent) !=''){
              $mailHeader  .= $mailContent;
              $mailHeader .='</div>';
              $response['mailContent']     = $mailHeader;
              $result = Campaign::where('id',$post['refrence'])->update(array('testMail'=>$mailContent));
            }else{
              $response['success']         = false;
              $response['error_message']   = 'You sent an empty email please send sample email with content !!!';
            }

          }else{

               $response['success']         = false;
               $response['error_message']   = 'E-mail not received yet !!!';
          }
      }else{
        $response['success'] = false;
        $response['error_message'] = 'Invalid Request..';
      }
      return response($response);exit;
    }

    public function campaignPhone(Request $request)
    {
      $searchKey = $request->get('searchKey');
      $campId = $request->get('campId');
      $search = \DB::table("contacts")->select('contacts.id',\DB::raw('CONCAT(name, " ( ", contact , " ) ") AS value'))
      ->leftJoin('campaigns_contacts', 'campaigns_contacts.custId' ,'=' ,'contacts.id')
      ->whereNotIn('contacts.id',function($query) use ($campId)
      {
               $query->select('custId')->from('campaigns_contacts')->where('campId',$campId);
       })
      ->where(function($query) use ($searchKey)
      {
       $query->where('name', 'like', '%' . $searchKey . '%')
       ->orWhere('contact', 'like', '%' . $searchKey . '%');
      })->where('user_id', \Auth::user()->id)
      ->where('is_deleted', 0)
      ->groupBy('contacts.id');
      $search= $search->get();
      if(count($search) > 0 ){
         $search = $search;
       }
      return response($search);exit;
    }

    public function addPhoneCampaign(Request $request)
    {
        $userEmail = \Auth::user()->email;
        $userId    = \Auth::user()->id;
        $userPass  = Crypt::decrypt(\Auth::user()->enc_password);
        $api_dept_id = $_POST['api_dept_id'];
        $row = '';
        if (isset($_POST['contactid']))
        {
          foreach ($_POST['contactid'] as $key => $value) {
          $postData = array('custId' =>  $value , 'campId' => $_POST['campId'] );
          $step = '';
          $whereData = [
            ['custId', '=', $value ],
            ['campId', '=', $_POST['campId'] ],
          ];

          $checkExits = Helpers::checkExits('campaigns_contacts', $whereData);
          // echo $checkExits;die;
          if (!$checkExits)
          {
            $insertId = CampaignsContacts::insertGetId($postData);
            $record   = CampaignsContacts::where('id',$insertId)->first();
            if(trim($api_dept_id) != 0 )
            {
              $campCont = CampaignsContacts::where('campId',$_POST['campId'])->get();
              foreach ($campCont as $cc)
              {
                if($cc->api_phone_id==0)
                {
                  $code = $cc->contact->country->countryPhoneCode;
                  $code = ltrim($code,'+');
                  $phoneNumber = $code.''.$cc->contact->contact;
                  $name = $cc->contact->name;
                  $contact_email = $cc->contact->email;
                  $location = $cc->contact->dept;
                  //Set Phone
                  $data = array(
                    "department_id" =>  $api_dept_id,
                    "email"         =>  $userEmail,
                    "number"        =>  $phoneNumber,
                    "name"          =>  $name,
                    "email_id"      =>  $contact_email,
                    "location"      =>  $location
                  );
                  $phone_res = Curl::to(env('API_BASEPATH').'phone/')
                  ->withContentType('application/json')
                  ->withOption('USERPWD', $userEmail. ":" .$userPass)
                  ->returnResponseObject()
                  ->withData($data)->asJson()->post();
                  //Update table
                  CampaignsContacts::where('id',$cc->id)
                  ->update(['api_phone_id'=>$phone_res->content->id]);
                  $affected = Campaign::where('id',$_POST['campId'])->where('country_id','!=',0)->update(array('step'=>4));
                  if($affected){
                  $step = 4;
                  }
                }
              }
            }
            $campConts = CampaignsContacts::where('campId',$_POST['campId'])->count();
            $contactLimit = \Auth::user()->user_plan->plans->no_of_contacts;
            $row .= '<tr id="tableRow_'.$record->custId.'"><td data-name="name">'.$record->contact->name.'</td><td data-name="contact">'.$record->contact->contact.'</td><td><a href="javascript:void(0)"><span class="removeContact" id="'.$record->id.'"><i class="fa fa-times" aria-hidden="true"></i></a></span> &nbsp; <span class="editContact" id="'.Crypt::encrypt($record->custId).'"><a href="javascript:void(0)"><i class="fa fa-edit" aria-hidden="true"></i></span></a></td></tr>';
            if($insertId!=0) {
              $res['success']         = true;
              $res['tableRow']        = $row;
              $res['success_message'] = 'Record Added successfully';
              $res['step']            = $step;
              $res['campContact']     = true;
              if($campConts == $contactLimit)
              {
                $res['hideElement'] = '.getContacts';
              }
            }else{
              $res['success']         = true;
              $res['error_message']   = 'Something went wrong';
            }
          }else{
            $res['success']         = false;
            $res['error_message']   = 'Contact already exists';
          }
        }
      }else{
        $res['success']         = false;
        $res['error_message']   = 'Please Select contact and try again!.';
      }

        return response($res);exit;
    }
    public function removePhoneCampaign(Request $request)
    {
        $contactLimit = \Auth::user()->user_plan->plans->no_of_contacts;
        $id = $request->post('id');
        $cc = CampaignsContacts::where('id',$id)->first();
        $campId = $cc->campId;
        if($cc->api_phone_id!=0)
        {
          $userEmail = \Auth::user()->email;
          $userPass = Crypt::decrypt(\Auth::user()->enc_password);
          $phone_res = Curl::to(env('API_BASEPATH').'phone/'.$cc->api_phone_id.'/')
          ->withContentType('application/json')
          ->withOption('USERPWD', $userEmail. ":" .$userPass)
          ->delete();
        }
        $delete = CampaignsContacts::where('id',$id)->delete();
        $count = CampaignsContacts::where('campId',$campId)->get()->count();
        if($count==0){
          Campaign::where('id',$campId)->update(['step'=>2]);
          $res['step'] = 2;
        }
        if ($delete) {
          $res['success'] = true;
          $res['success_message'] = 'Contact removed successfully !!!';
          if($count < $contactLimit)
          {
            $res['showElement'] = '.getContacts';
          }
        }else{
          $res['success'] = true;
          $res['error_message'] = 'Oops !!! Something went wrong.';
        }
      return response($res);exit;
    }

    public function leadsApiAjax(request $request){
      $url = $request->post('data');
      $id  = $request->post('campId');
      $res = Campaign::where('id',$id)->first();
      $userEmail = $res->user_detail->email;
      $userPass = Crypt::decrypt($res->user_detail->enc_password);
      $response = Curl::to($url)
      ->withContentType('application/json')
      ->withOption('USERPWD', $userEmail . ":" . $userPass)
      ->get();
      $ds = json_decode($response);
      $item_per_page  = $ds->meta->limit;
      $current_page   = $ds->meta->offset / $ds->meta->limit;
      $total_records  = $ds->meta->total_count;
      $total_pages    = $ds->meta->total_count / $ds->meta->limit;
      $page_url       = '/api/v1/calllog/';
      $prev           =  $ds->meta->previous;;
      $next           = $ds->meta->next;
      $pagination = Helpers::paginate($item_per_page, $current_page, $total_records, floor($total_pages), $page_url , $prev , $next, $id);
      $index = $item_per_page*$current_page;
      return view($this->customertemplatename.'/campaign/leadsListAjax',['url'=>$this->url,'leadsData'=>json_decode($response),'pagination'=>$pagination,'index'=>$index]);
    }

    public function viewCampaign(Request $request, $id)
    {
      $campId = Crypt::decrypt($id);
      $campData = Campaign::where('id',$campId)->first();
      $viewArr = array(
                  'campaignsTitle'      =>  $campData->title,
                  'campaignTemplate'    =>  $campData->template,
                  'campaignCountry'     =>  $campData->country_id,
                  'email'               =>  $campData->email,
                  'testMail'            =>  $campData->testMail,
                  'campaignPhone'       =>  $campData->phone,
                  'step'                =>  $campData->step,
                  'parserOutput'         =>  $campData->parserOutput,
                  'delayTime'           =>  $campData->delayTime,
                  'campaignCountryName' =>  $campData->coutry_details->countryName,
                  'campaigns_tags'      =>  $campData->campaigns_tags,
                  'available_days'      =>  json_decode($campData->available_days),
                  'campId'              =>  $campData->id,
                  'availHoursFrom'      =>  json_decode($campData->available_hours)->from,
                  'availHoursTo'        =>  json_decode($campData->available_hours)->to,
                  'breakHoursFrom'      =>  json_decode($campData->break_hours)->from,
                  'breakHoursTo'        =>  json_decode($campData->break_hours)->to,
                  'campaignContact'     =>  $campData->campaigns_contacts,
                );
      return view($this->customertemplatename.'/campaign/view_campaign',['campData'=>$viewArr]);
    }

    //Get Lead Details
    public function getLeadDetails($id)
    {
      $lead = ApiLeads::where('id',$id)->first();
      return view($this->customertemplatename.'/campaign/leadDetail',['lead'=>$lead]);
    }
    //Get Lead Data for exports
    public function exportLeadData($campID)
    {
      $res = Campaign::where('id',$campID)->first();
      $response = ApiLeads::with('parse_emails')->where('campaign_id',$campID)->orderBy('lead_id','DESC')->get();
      $exportArr = array();
      $i=0;
      foreach($response as $value)
      {
        $i++;
        $callScript = '';
        if(isset($value->parse_emails->callScript))
        {
          $callScript = $value->parse_emails->callScript;
        }
        $leadArr = array(
          'Sr'            => $i,
          'Lead ID'       => $value->lead_id,
          'Call Script'   => $callScript,
          'Agent Name'    => $value->agent,
          'Recording URL' => $value->recording,
          'Campaign Name' => $value->department,
          'Dated'     => date("m-d-Y h:i:s A", strtotime($value->startdate))
        );
        $exportArr[] = $leadArr;
      }
      $filename = 'Lead Report'.$campID.'.csv';
      self::exportData($exportArr,$filename);
    }

    public function exportData($assocDataArray, $fileName)
    {
      ob_start();
      ob_clean();
      header('Pragma: public');
      header('Expires: 0');
      header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
      header('Cache-Control: private', false);
      if (strpos($fileName, 'xls') !== false) {
          header("Content-Type: text/xls");
      } //strpos($fileName, 'xls') !== false
      else {
          header('Content-Type: text/csv');
      }
      header('Content-Disposition: attachment;filename=' . $fileName);
      if (isset($assocDataArray['0'])) {
          $fp = fopen('php://output', 'w');
          fputcsv($fp, array_keys($assocDataArray['0']));
          foreach ($assocDataArray AS $values) {
              fputcsv($fp, $values);
          } //$assocDataArray AS $values
          fclose($fp);
      } //isset($assocDataArray['0'])
      ob_flush();
    }

    public function callRating(Request $request)
    {
      $post = $request->except(['_token']);
      $leadID = Crypt::decrypt($post['id']);
      $rate   = $post['rate'];
      $res = ApiLeads::where('id',$leadID)->update(['rating'=>$rate]);
      if($res)
      {
        $response['success'] = true;
        $response['success_message'] = 'Call rating done successfully !!!';
        $response['delayTime'] = 1000;
        $response['data_id'] = $leadID;
      }
      else
      {
        $response['success'] = false;
        $response['error_message'] = 'Error occured while adding call rating !!!';
      }
      return response($response);
    }
}
