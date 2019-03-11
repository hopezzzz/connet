<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\GlobalFunctions as Helpers;
use App\Model\Contact;
use App\Model\Country;
use App\Model\Campaign;
use App\Model\CampaignsContacts;
use App\Model\Plan;
use Crypt;
use DB;
use Curl;

class ContactController extends Controller
{
    public function __construct()
    {
      $this->url=url(request()->route()->getPrefix());
      $this->currentUrl = Helpers::getCurrentUrl();
      $this->customertemplatename = config('app.customertemplatename');
    }

    public function index(Request $request)
    {
      $contacts = Contact::where('is_deleted',0)->where('user_id',Auth()->user()->id)->orderBy('id','DESC')->paginate(10);
      if($request->Ajax()) {
        $page =  ( $request['page'] - 1 ) * 10;
        if ( isset($_GET['searchKey']) ) {
          $searchKey = $request->get('searchKey');
          $contacts  = Contact::where('is_deleted',0)
          ->where(function($query) use ($searchKey){
            $query->where('name', 'like', '%' . $searchKey . '%')
            ->orWhere('contact', 'like', '%' . $searchKey . '%');
          })->where('user_id', \Auth::user()->id)
          ->orderBy('id','DESC')->paginate(10);
        }
        return view($this->customertemplatename.'/contacts/recordListAjax',['page'=> $page, 'url'=>$this->url,'current_url'=>$this->currentUrl,'contacts'=>$contacts]);
      }else{
        return view($this->customertemplatename.'/contacts/index',['url'=>$this->url,'current_url'=>$this->currentUrl,'contacts'=>$contacts]);
      }
    }
    public function addContactView(Request $request)
    {
      // $dd = $request->session();
      // echo "<pre>";print_r($dd->toArray());
      // die;
      $count = Contact::where('user_id',\Auth::user()->id)->count();
      $contactLimit = \Auth::user()->user_plan->plans->no_of_contacts;
      if($count < $contactLimit)
      {
        $codeList = Country::select(DB::raw("CONCAT(countryName,' - (',countryPhoneCode,')') AS country"),'countryId')->where('countryPhoneCode','!=','')->orderBy('country')->get()->pluck('country','countryId')->toArray();
        $codeList = array_prepend($codeList,'Select Country Code','');
        return view($this->customertemplatename.'/contacts/contactForm',['url'=>$this->url,'current_url'=>$this->currentUrl,'countryCode'=>$codeList]);
      }
      else
      {
        $response['success'] = false;
        $response['delayTime'] = 3000;
        $response['error_message'] = "You have already reached to your contact's maximum limit !!!";
        $request->session()->put('error', json_encode($response));
        $data = $request->session()->all();
        return \Redirect::back();
      }
    }
    public function saveContact(Request $request)
    {
      $count = Contact::where('user_id',\Auth::user()->id)->count();
      $contactLimit = \Auth::user()->user_plan->plans->no_of_contacts;
      
      $postData = array_except($request->all(), ['_token']);
      $daysArr = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
      $days = array();
      for($i=0;$i<=6;$i++){$days[] = array('from'=>$postData['dayFrom'][$i],'to' => $postData['dayTo'][$i],'status'=> $postData['dayStatus'][$i]);}
      $days = json_encode($days);
      $id       = null;
      if(!isset($postData['recordId']) && $postData['recordId']=="")
      {
        if($count  >= $contactLimit  )
        {
          $response['success'] = false;
          $response['delayTime'] = 3000;
          $response['error_message'] = "You have already reached to your contact's maximum limit !!!";
          return response( $response );
          exit;
        }
      }
      if(isset($postData['recordId']) && $postData['recordId']!="")
      {
        $id  =  \Crypt::decrypt($postData['recordId']);
        $whereData = [
            ['contact', $_POST['number']],
            ['country_id',$_POST['code']],
            ['id',  '!=', $id ],
            ['user_id',\Auth::user()->id],
        ];
        $contacts = Helpers::checkExits('contacts',$whereData);
      }else{
        $whereData = [
            ['contact', $_POST['number']],
            ['country_id',$_POST['code']],
            ['is_deleted', '=', 0],
            ['user_id',\Auth::user()->id],
        ];
        $contacts = Helpers::checkExits('contacts',$whereData);
      }
      if($contacts){
        $response['success']    = false;
        $response['formErrors'] = true;
        $response['errors']     = array('number' => 'This number already exists!');
        return response($response);die;
      }
      if(ctype_digit($postData['number']))
      {
        $data = array(
            'name'        => $postData['name'],
            'country_id'  => $postData['code'],
            'email'       => $postData['email'],
            'role'        => $postData['role'],
            'dept'        => $postData['dept'],
            'contact'     => $postData['number'],
            'schedule'    => $days
        );

        if(isset($postData['recordId']) && $postData['recordId']!="")
        {
          $id     = Crypt::decrypt($postData['recordId']);
          $res   = Contact::where('id',$id)->first();
          foreach($res->campaigns as $camp)
          {
            if($camp->campId !=0 )
            {
              $cr = Campaign::where('id',$camp->campId)->first();
              $userEmail      = $cr->user_detail->email;
              $userPass       = Crypt::decrypt($cr->user_detail->enc_password);
              $country        = Country::where('countryId',$postData['code'])->first();
              $phoneID        = $camp->api_phone_id;
              $code           = $country->countryPhoneCode;
              $code           = ltrim($code,'+');
              $phoneNumber    = $code.''.$postData['number'];
              $name           = $postData['name'];
              $contact_email  = $postData['email'];
              $location       = $postData['dept'];
              //Set Phone
              $apiData = array(
                "email"       =>  $userEmail,
                "number"      =>  $phoneNumber,
                "name"        =>  $name,
                "email_id"    =>  $contact_email,
                "location"    =>  $location
              );
              $phone_res = Curl::to(env('API_BASEPATH').'phone/'.$phoneID.'/')
              ->withContentType('application/json')
              ->withOption('USERPWD', $userEmail. ":" .$userPass)
              ->returnResponseObject()
              ->withData($apiData)->asJson()->patch();
            }
          }
          $result = Contact::where('id',$id)->update($data);
          if($result)
          {
            if(isset($_POST['frontreq'])){
              $response['custRef']       =  array('custRef' =>  $result , 'customerName' => $postData['name']);
              $response['modelhide']     = '#add-new-tag';
              $response['updateContact']     = true;
              $response['updateRecord']     = true;
              $response['data']     = array('id'=>$id, 'name' => $postData['name'], 'contact' => $postData['number'] );
            }else{
               $response['url']           = $this->url.'/contacts';
            }
            $response['success']         = true;
            $response['delayTime']       = 1000;
            $response['success_message'] = 'Contact updated successfully !';
          }
          else
          {
            $response['success']    = false;
            $response['success_message'] = 'Error occured while updating contact !';
          }
        }
        else
        {
          $data['user_id'] = \Auth::user()->id;
          $result          = Contact::insertGetId($data);
          if($result)
          {
            $response['success']          = true;
            if(isset($_POST['frontreq'])){
              $response['custRef']       =  array('custRef' =>  $result , 'customerName' => $postData['name']);
              $response['modelhide']     = '#add-new-tag';
            }else{
               $response['url']           = $this->url.'/contacts';
            }
            $response['delayTime']       = 1000;
            $response['success_message'] = 'Contact added successfully !';
            $count = Contact::where('user_id',\Auth::user()->id)->count();
            $contactLimit = \Auth::user()->user_plan->plans->no_of_contacts;
            if($count==$contactLimit)
            {
              $response['hideElement'] = '.add_Contact';
            }
          }
          else
          {
            $response['success']          = false;
            $response['success_message']  = 'Error occured while saving contact !';
          }
        }
      }
      else
      {
        $response['success']    = false;
        $response['formErrors'] = true;
        $response['errors'] = array('number'=>'Please enter a valid phone number.');
      }
      return response($response);
    }
    public function editContactView(Request $request,$id)
    {
      $recordId = Crypt::decrypt($id);
      $codeList = Country::select(DB::raw("CONCAT(countryName,' - (',countryPhoneCode,')') AS country"),'countryId')->where('countryPhoneCode','!=','')->orderBy('country')->get()->pluck('country','countryId')->toArray();
      $codeList = array_prepend($codeList,'Select Country Code','');
      $data = Contact::where('id',$recordId)->first();
      $contactData = array('email'=>$data->email,'role'=>$data->role,'dept'=>$data->dept,'name'=>$data->name,'code'=>$data->country_id,'number'=>$data->contact,'schedule'=>json_decode($data->schedule) );
      if ($request->ajax()) {
      return view($this->customertemplatename.'/contacts/campaign-contact',['url'=>$this->url,'current_url'=>$this->currentUrl,'countryCode'=>$codeList,'recordId'=>$id,'data'=>$contactData]);
      }else{
        return view($this->customertemplatename.'/contacts/contactForm',['url'=>$this->url,'current_url'=>$this->currentUrl,'countryCode'=>$codeList,'recordId'=>$id,'data'=>$contactData]);
      }
    }

    public function getContacts(Request $request)
    {
      if ($request->ajax()) {
        $id = \Auth()->user()->id;
        $campId =$request->get('campId');
        $c = Contact::whereDoesntHave('campaigns',function($r) use($campId){
          $r->where('campId',$campId);
        });
        $Contact = $c->where('user_id',\Auth::id())->where('is_deleted',0)->get();
        
        // $Contact = \DB::table("contacts")->select('contacts.id','contacts.*')
        // ->leftJoin('campaigns_contacts', 'campaigns_contacts.custId' ,'=' ,'contacts.id')
        // ->whereNotIn('contacts.id',function($query) use ($campId)
        // {
        //     $query->select('custId')->from('campaigns_contacts')->where('campId',$campId);
        // })->where('user_id', \Auth::user()->id)
        // ->where('is_deleted', 0)
        // ->groupBy('contacts.id');
        // $Contact= $Contact->get();
        return view($this->customertemplatename.'/contacts/contacts',['contacts'=>$Contact]);
      }
    }
    //Remove contacts while plan downgrades
    public function removeContacts(Request $request)
    {
      $userEmail = \Auth::user()->email;
      $userPass  = Crypt::decrypt(\Auth::user()->enc_password);
      $postData = array_except($request->all(), ['_token']);
      $plan = Plan::where('id',$postData['planId'])->first();
      if(!empty($postData['removeContact']))
      {
        if(count($postData['removeContact']) <= $plan->no_of_contacts)
        {
          $contactData = Contact::where('user_id',\Auth::user()->id)->get();
          foreach ($contactData as $contact)
          {
            if(!in_array($contact->id, $postData['removeContact']))
            {
              $campConts = CampaignsContacts::where('custId',$contact->id)->get();
              foreach($campConts as $cc)
              {
                $result = Curl::to(env('API_BASEPATH').'phone/'.$cc->api_phone_id.'/')
                ->withContentType('application/json')
                ->withOption('USERPWD', $userEmail . ":" . $userPass)
                ->delete();
                CampaignsContacts::where('id',$cc->id)->delete();
              }
              Contact::where('id',$contact->id)->delete();
            }
          }
          $response['success'] = true;
          $response['success_message'] = 'Contacts removed successfully !!!';
        }
        else
        {
          $response['success'] = false;
          $response['error_message'] = 'You can select maximum up to '.$plan->no_of_contacts.' contacts !';
        }
        $camps = Campaign::where('user_id',\Auth::user()->id)->get();
        foreach ($camps as $camp)
        {
          $count = CampaignsContacts::where('campId',$camp->id)->get()->count();
          if($count == 0)
          {
            Campaign::where('id',$camp->id)->update(['step'=>3]);
          }
        }
      }
      else
      {
        $response['success']       = false;
        $response['error_message'] = 'Please select contact(s) to submit !';
      }
      return response($response);
    }
}
