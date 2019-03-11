<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\lib\xmlapi;
// use App\Services\cPanel;
// use App\Services\emailAccount;
use App\Services\emailExtract\EmailParser;
use App\Helpers\GlobalFunctions as Helpers;
Use App\Model\Campaign;
Use App\Model\ParseEmail;
Use App\Model\Parsetag;
Use App\Model\UserPayment;
Use App\Model\Calllog;
Use App\User;
use Curl;
use URL;
use DateTime;
use Crypt;
use Cpanel;
use DB;

class EmailextractController extends Controller
{
    public function __construct()
    {
      $this->domainEmail = config('app.domainEmail');
      $this->domain = config('app.domain');
    }

    public function createEmails(Request $request)
    {
        $xmlapi = new xmlapi("cashmann.co.uk");
        $xmlapi->password_auth("onewayit","Cash@1234");
        $xmlapi->set_debug(1);

        $result = $xmlapi->api2_query('onewayit','Email', 'addforward',
                                    array(
                                          'domain'          => 'cashmann.co.uk',
                                          'email'           => '01pawg@cashmann.co.uk',
                                          'fwdopt'          => 'fwd',
                                          'fwdemail'        => 'phoneconnect@cashmann.co.uk',
                                        )
                                     );
    }
    public function deleteEmails(Request $request)
    {
    $hostname = '{mail.'.env('WEBSITE_MAIL_DOMAIN').':143/imap/notls}INBOX';
    $username = env('WEBSITE_FWD_MAIL');
    $password = trim(env('WEBSITE_FWD_PASS'));
    $inbox = imap_open($hostname,$username ,$password) or die('Cannot connect to Gmail: ' . imap_last_error());
    $emails = imap_search($inbox,'SEEN');
    if($emails)
    {
        $output = '';rsort($emails);
        foreach($emails as $email_number)
        {
              $overview = imap_fetch_overview($inbox,$email_number,0);
              imap_delete($inbox,$overview[0]->msgno);
        }
    }
      imap_expunge($inbox);
      imap_close($inbox);
    }

    public function gettags(Request $request)
    {
        $tags  = Parsetag::where('tagName','LIKE',"%{$request->get('q')}%")->where('campId',$request->get('campId'))->get();

        return response($tags);exit;
    }

    public function readEmails(Request $request)
    {

      $readMail = Helpers::readEmails(trim($this->domain),trim(env('WEBSITE_FWD_MAIL')),trim(env('WEBSITE_FWD_PASS')));
      if (!empty($readMail))
      {
        foreach ($readMail as $mail)
        {
          $to     = trim($mail['to']);
          $body   = trim($mail['body']);
          $originalLead = $body;
          $emails = Campaign::where('is_deleted',0)->where('email',$to)->where('step',4)->first();
          if($emails)
          {
            // echo '<pre>'; print_r($emails); die;
            $timezone = Helpers::getRegion($emails->coutry_details->countryShort);
            reset($timezone);
            $region = key($timezone);
            $timezone = Helpers::get_time_zone($emails->coutry_details->countryShort,$region);
            if ($timezone) {
                date_default_timezone_set($timezone);
            }
            //Find time when webmail received
            $dateW = new \DateTime($mail['date'], new \DateTimeZone('UTC'));
            $dateW->setTimezone(new \DateTimeZone($timezone));
            $webmailTime = $dateW->format('Y-m-d H:i:s');
            $logData['campaign_email'] = $to;
            $logData['webmail_received'] = $webmailTime;
            $logData['mail_read'] = date('Y-m-d H:i:s');
            //insert log and get log id
            $logID = Calllog::insertGetId($logData);

            preg_match_all('/{(.*?)}/', $emails->template, $matches);
            $parsingTerms     = $matches[1];
            $replacements     = array();
            $emailLines       = explode("\n", $body);
            $emailLines       = array_map('trim',$emailLines);
            $emailLines       = array_filter($emailLines);
            $emailLines       = array_values($emailLines);
            $template         = $emails->template;
            $camp             = Parsetag::where('campId',$emails->id)->get();
            $parseArr         = array();

            foreach ($camp as $kkeys => $c)
            {
              $line           = (isset($emailLines[$c->indexRow])) ? Helpers::RemoveSpecialChapr($emailLines[$c->indexRow]) : '';
              $parseContent   = substr($line,$c->positionStart);
              if (trim(strtolower($c->tagName)) == 'message')
              {
				$message = array_slice($emailLines,$c->indexRow);
				if (isset($camp[$kkeys+1]))
                {
					$message = array_slice($emailLines,$c->indexRow,$camp[$kkeys+1]->indexRow);
				}
				$parseContent = implode(' ', $message);
				$parseContent = substr($parseContent,$c->positionStart);
			  }
              $parseArr[trim($c->tagName)] = trim($parseContent);
            }

            $arrLower    = array_change_key_case($parseArr,CASE_LOWER);
            $phone       = ltrim($arrLower['phone'],'0');

            //   echo '<pre>'.$phone;
            // print_r($arrLower);
            // die;
            foreach($parseArr as $k => $val)
            {
              $search    = '{'.$k.'}';
              $parseMail = str_replace($search, $val, $template);
              $template  = $parseMail;
            }
            $callScript  = $parseMail;
            if ($emails->user_detail->status==1){ $status = 0; }else{ $status = 2; }
            if(empty($phone)){ $status    = 3; }
            if($emails->status==0){ $status    = 2; }//Inactive Campaign
            $expectedCall = date('Y-m-d H:i:s');
            $pos = strpos($emails->delayTime, ':');
            if ($pos !== false)
            {
              $calldelayTime = explode(':', $emails->delayTime);
              $calldelay = '+'.$calldelayTime[0].' Hour '.$calldelayTime[1].' minutes '.$calldelayTime[2].' seconds';
              // echo "$calldelay <br>";
              $expectedCall =  date('Y-m-d H:i:s',strtotime($calldelay));
              // echo $expectedCall;die;
            }

            $callScript = strip_tags(trim(preg_replace('/\s\s+/', ' ', $callScript)));
            $emailParser = array(
                'campaignEmail' =>  $emails->email,
                'mobileNo'      =>  $phone,
                'callScript'    =>  Helpers::RemoveSpecialChapr($callScript),
                'original_lead' =>  $originalLead,
                'expectedCall'  =>  $expectedCall,
                'status'        =>  $status,
                'created_at'    =>  date('Y-m-d H:i:s'),
                'updated_at'    =>  date('Y-m-d H:i:s')
            );
            $insert = ParseEmail:: insertGetId($emailParser);
            Calllog::where('id',$logID)->update(['mail_parsed'=>date('Y-m-d H:i:s'),'script_id'=>$insert]);
            if ($insert) {
              $responseInsert = array('success' => true , 'success_message' => 'Records Insert successfully.');
            }else{
              $responseInsert = array('success' => false , 'error_message' => 'Something went wrong please try again.');
            }
          }
        }
      }
      self::makeCalls();
      if (!empty($responseInsert)) {
        return response($responseInsert);
      }else{
        $respons = array('success' => false , 'error_message' => 'No New Unread Email Found.');
        return response($respons);
      }

    }

    public function makeCalls()
    {
      $responseArray = array('success' => false, 'error_message' => 'No Pending call Scripts');
      $callScripts = ParseEmail::with('campaign_details.coutry_details','campaign_details.user_detail')->where('status', 0)->get();
      $validCalls = 0;
      if($callScripts->count() != 0 )
      {
          foreach ($callScripts as $key => $callScript)
          {
            $userEmail         = $callScript->campaign_details->user_detail->email;
            $userPass          = Crypt::decrypt($callScript->campaign_details->user_detail->enc_password);
            $path              = env('API_BASEPATH').'department/';
            //consumed minutes API
            $data              = Curl::to($path)
            ->withContentType('application/json')
            ->withOption('USERPWD', $userEmail . ":" . $userPass)
            ->get();
            $data              = json_decode($data);
            $totalSeconds      = 0;
            foreach ($data->objects as $key => $objects) {
              $totalSeconds += $objects->total_called_seconds;
            }
            $totalMin          = $totalSeconds / 60 ;
            $minutes_per_month = $callScript->campaign_details->user_detail->user_plan->plans->minutes_per_month;
            $plan_billing_type = $callScript->campaign_details->user_detail->user_plan->plans->billingType;
            if ( $plan_billing_type == 3 && $totalMin >= $minutes_per_month)
            {
                // user mintue consumed
                $userupdate = User::where('email',$userEmail)->update(['status' => 2 ]);
                $responseArray = array('success' => false, 'error_message' => 'User is inactive...');
            }
            elseif ($callScript->campaign_details->user_detail->status == 1)
            {
                $timezone = Helpers::getRegion($callScript->campaign_details->coutry_details->countryShort);
                reset($timezone);
                $region = key($timezone);
                $timezone = Helpers::get_time_zone($callScript->campaign_details->coutry_details->countryShort,$region);
                if ($timezone) {
                    date_default_timezone_set($timezone);
                }
                $data = array(
                  "customer_phone" => $callScript->campaign_details->coutry_details->countryPhoneCode.$callScript->mobileNo,
                  "message"        => $callScript->callScript,
                  "department_id"  => $callScript->campaign_details->api_dept_id
                );
                $validCalls = 0;
                if (in_array(strtolower(date("D",strtotime('now'))),json_decode($callScript->campaign_details->available_days)) )
                {
                  $availablehours = json_decode($callScript->campaign_details->available_hours);
                  $current_time = date("H:i:s");            // getting current time
                  $current_date_time = date("Y-m-d H:i:s"); // getting current time
                  $startTime = $availablehours->from;       // getting start from time
                  // plus delay time in start time
                  $endTime = $availablehours->to;           // getting end time
                  $break_hours    = json_decode($callScript->campaign_details->break_hours);
                  $breakStartTime = $break_hours->from;     // getting start time
                  $breakEndTime   = $break_hours->to;       // getting end time
                  if($startTime == '00:00:00' && $endTime == '00:00:00' )
                  {
                    if ($current_time > $breakStartTime && $current_time < $breakEndTime){
                      $validCalls = 0;
                    }else{
                      if ($current_date_time  > $callScript->expectedCall)
                      {
                        $validCalls = 1;
                      }
                    }
                  }
                  elseif ($current_time > $startTime && $current_time < $endTime)
                  {
                    if ($current_time > $breakStartTime && $current_time < $breakEndTime){
                      $validCalls = 0;
                    }else{
                      if ($current_date_time  > $callScript->expectedCall)
                      {
                        $validCalls = 1;
                      }
                    }
                  }
                }
                // Check if vaildCalls

                if($validCalls)
                {
                    $response = Curl::to(env('API_BASEPATH').'call/')
                    ->withContentType('application/json')
                    ->withOption('USERPWD',  $userEmail . ":" . $userPass)
                    ->returnResponseObject()
                    ->withData($data)
                    ->asJson()
                    ->post();

                    if (isset( $response->content->status ) && $response->content->status == 1)
                    {
                      $responseArray = array('success' => true , 'success_message' => $response->content->message);
                      ParseEmail::where('id',$callScript->id)->update(["status"=>1,"lead_id" =>$response->content->id]);
                    }
                   	if($response->status == 400)
                    {
                   		$responseArray = array('success' => false , 'error_message' => '400 error occured');
                   		ParseEmail::where('id',$callScript->id)->update(["status"=>4]);
                   	}
                    if($response->status == 500)
                    {
                      $responseArray = array('success' => false , 'error_message' => '500 error occured');
                      ParseEmail::where('id',$callScript->id)->update(["status"=>4]);
                    }
                    Calllog::where('script_id',$callScript->id)->update(['api_hit'=>date('Y-m-d H:i:s')]);
                }
                else
                {
                  $responseArray = array('success' => false, 'error_message' => 'Something went wrong please try again..');
                }
            }
            else
            {
                  $responseArray = array('success' => false, 'error_message' => 'No Pending call Scripts');
            }
          } // end foreach
      }
      else{
        $responseArray = array('success' => false, 'error_message' => 'No Pending call Scripts');
      }
      return response($responseArray);exit;
    }
    public function testMail(Request $request)
    {
      $campaign = Campaign::where('email',$request->input('email'))->first();
      if (!empty($campaign)) {
        $createEmail = Helpers::createEmail($campaign->email,$campaign->password);
        if (!$createEmail->cpanelresult->data[0]->result) {
          $response = array('success' => true, 'success_message' => 'Account verified successfully' );
        }else{
          $response = array('success' => false, 'error_message' => 'Something went wrong please try again' );
        }
      }else {
        $response = array('success' => false, 'error_message' => 'This email address does not exists in database !' );
      }
      return response($response);exit;
    }

    public function checkCampaings()
    {
      $user = User::has('user_campaigns')->with(['user_payments' => function($q){
        $q->orderBy('id','DESC')->first();
      }])->where('register_step',3)->where('status',1)->get();
      $path = env('API_BASEPATH').'department/';
      foreach ($user as $key => $value) {
        $expireDate = $value->user_payments[0]->endDate;
        // $expireDate = date('d-m-Y', $expireDate);
        // $expireDate = '12-12-2018';
        $userEmail = $value->email;
        $minutes_per_month = $value->user_plan->plans->minutes_per_month;
        $userPass = Crypt::decrypt($value->enc_password);
        //consumed minutes API
        $data = Curl::to($path)
        ->withContentType('application/json')
        ->withOption('USERPWD', $userEmail . ":" . $userPass)
        ->get();
        $data = json_decode($data);
        if (isset($data->objects ) ) {
          $totalSeconds      = 0;
          foreach ($data->objects as $key => $objects) {
            $totalSeconds += $objects->total_called_seconds;
          }
          $totalMin          = $totalSeconds / 60 ;
          if($totalMin >= $minutes_per_month){
             $consumed = 100;
          }else{
            $consumed = round(($totalMin*100)/$minutes_per_month);
          }
          $name     = $value->firstName.' '.$value->lastName;
          $planName = $value->user_plan->plans->name;
          if($consumed == 100 && $value->reminderStatus!=3)
          {
            $patterns = $replacements = $emailstring = array();
            $templates     = DB::table('templates')->where('id',6)->first();
            $string        = $templates->content;
            $patterns      = array('/{CustomerName}/','/{PlanName}/','/{ExpireDate}/');
            $replacements  = array($name,$planName,$expireDate);
            $emailstring   = Helpers::emailReplacement($patterns,$replacements,$string);
            $title         = 'Minutes Consumed - Direct Connect';
            $emailstring['title'] = $title;
            Helpers::sendmail(env('MAIL_FROM'),env('APP_NAME'),$value->email,$name,$title,$emailstring,'emails.email');
            User::where('id',$value->id)->update(['reminderStatus'=>3]);
          }
          else
          {
            $patterns = $replacements = $emailstring = array();
            $templates     = DB::table('templates')->where('id',4)->first();
            $string        = $templates->content;
            $patterns      = array('/{CustomerName}/','/{MinutesConsumed}/','/{PlanName}/','/{ExpireDate}/');
            $replacements  = array($name,$consumed,$planName,$expireDate);
            $emailstring   = Helpers::emailReplacement($patterns,$replacements,$string);
            $title         = 'Minutes Consumed - Direct Connect';
            $emailstring['title'] = $title;
            if($consumed >= 50 && $consumed < 80 && $value->reminderStatus!=1)
            {
              Helpers::sendmail(env('MAIL_FROM'),env('APP_NAME'),$value->email,$name,$title,$emailstring,'emails.email');
              User::where('id',$value->id)->update(['reminderStatus'=>1]);
            }
            elseif($consumed >= 80 && $consumed < 100 && $value->reminderStatus!=2)
            {
              Helpers::sendmail(env('MAIL_FROM'),env('APP_NAME'),$value->email,$name,$title,$emailstring,'emails.email');
              User::where('id',$value->id)->update(['reminderStatus'=>2]);
            }
          }
        }
      }
    }

    public function expireReminder(){
      $user = User::has('user_payments')->where('register_step',3)->where('status',1)->get();
      foreach ($user as $value) {
        $pay = UserPayment::where('custom',$value->id)->orderBy('id','DESC')->first();
        // $value->user_plan->plans->name
        $date1 = new DateTime(date('Y-m-d'));
        $date2 = new DateTime(date('Y-m-d', $pay->endDate));
        $interval = $date1->diff($date2);
        $dateDiff = $interval->days;
        $name = $value->firstName.' '.$value->lastName;
        $expireDate = date('d-m-Y'. $pay->endDate);
        $planName = $value->user_plan->plans->name;
        if($dateDiff == 7 && $value->reminderStatus!=4)
        {
          $patterns = $replacements = $emailstring = array();
          $templates     = DB::table('templates')->where('id',3)->first();
          $string        = $templates->content;
          $patterns      = array('/{CustomerName}/','/{ExpireDate}/');
          $replacements  = array($name,$expireDate);
          $emailstring   = Helpers::emailReplacement($patterns,$replacements,$string);
          $title         = 'Plan Renewel - Direct Connect';
          $emailstring['title'] = $title;
          Helpers::sendmail(env('MAIL_FROM'),env('APP_NAME'),$value->email,$name,$title,$emailstring,'emails.email');
          User::where('id',$value->id)->update(['reminderStatus'=>4]);
        }elseif($dateDiff == 2 && $value->reminderStatus!=5){
          $patterns = $replacements = $emailstring = array();
          $templates     = DB::table('templates')->where('id',3)->first();
          $string        = $templates->content;
          $patterns      = array('/{CustomerName}/','/{ExpireDate}/');
          $replacements  = array($name,$expireDate);
          $emailstring   = Helpers::emailReplacement($patterns,$replacements,$string);
          $title         = 'Plan Renewel - Direct Connect';
          $emailstring['title'] = $title;
          Helpers::sendmail(env('MAIL_FROM'),env('APP_NAME'),$value->email,$name,$title,$emailstring,'emails.email');
          User::where('id',$value->id)->update(['reminderStatus'=>5]);
        }elseif($dateDiff == 0 && time() > $pay->endDate && $value->reminderStatus!=6){
          $patterns = $replacements = $emailstring = array();
          $templates     = DB::table('templates')->where('id',3)->first();
          $string        = $templates->content;
          $patterns      = array('/{CustomerName}/','/{PlanName}/');
          $replacements  = array($name,$planName);
          $emailstring   = Helpers::emailReplacement($patterns,$replacements,$string);
          $title         = 'Plan Expired - Direct Connect';
          $emailstring['title'] = $title;
          Helpers::sendmail(env('MAIL_FROM'),env('APP_NAME'),$value->email,$name,$title,$emailstring,'emails.email');
          User::where('id',$value->id)->update(['reminderStatus'=>6]);
        }
      }
    }
}
