<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\GlobalFunctions as Helpers;
use App\Model\Template;
use Crypt;

class TemplateController extends Controller
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

    public function index(Request $request)
    {
        $templates    = Template::orderBy('id','DESC')->paginate(10);

        if ($request->ajax())
        {
            $page =  ( $request['page'] - 1 ) * 10;
            if ( isset($_GET['searchKey']) ) {
              $templates    = Template::where('title', 'like', '%' . $request->get('searchKey') . '%')->orderBy('id','DESC')->paginate(10);
              // die('asdfadf');
            }
            return view($this->admintemplatename.'/templates/records_list',['url'=>$this->url,'current_url'=>$this->currentUrl,'templates'=>$templates,'page'=>$page]);
        }
        else
        {
            return view($this->admintemplatename.'/templates/index',['url'=>$this->url,'current_url'=>$this->currentUrl,'templates'=>$templates]);
        }
    }
    public function addTemplateView(Request $request)
    {
        $data['record_id'] = "";
        return view($this->admintemplatename.'/templates/addUpdate',['url'=>$this->url,'current_url'=>$this->currentUrl,'data'=>$data]);
    }
    public function editTemplateView(Request $request,$id)
    {
        $tempId = Crypt::decrypt($id);
        $data['record_id'] = $tempId;
        $res = Template::where('id',$tempId)->first();
        $data['name']=$res->title;
        $data['content']=$res->content;
        return view($this->admintemplatename.'/templates/addUpdate',['url'=>$this->url,'current_url'=>$this->currentUrl,'data'=>$data]);
    }
    public function viewTemplate(Request $request,$id)
    {
        $tempId = Crypt::decrypt($id);
        $data = Template::where('id',$tempId)->first();
        return view($this->admintemplatename.'/templates/view',['url'=>$this->url,'current_url'=>$this->currentUrl,'data'=>$data]);
    }
    public function saveTemplate(Request $request){
        $postData = array_except($request->all(), ['_token']);
        if(empty($postData['content']))
        {
            $response['success'] = false;
            $response['formErrors'] = true;
            $response['errors']     = array('content' => 'This field is required.');
        }
        else
        {
            $data = array('title'=>$postData['name'],'content'=>$postData['content']);
            if(isset($postData['record_id']) && $postData['record_id']!=''){
                $result = Template::where('id',$postData['record_id'])->update($data);
                if($result){
                    $response['success'] = true;
                    $response['success_message'] = 'Template updated successfully !';
                    $response['url'] = $this->url.'/email-templates';
                    $response['delayTime'] = 1000;
                }
            }else{
                $result = Template::insert($data);
                if($result){
                    $response['success'] = true;
                    $response['success_message'] = 'Template created successfully !';
                    $response['url'] = $this->url.'/email-templates';
                    $response['delayTime'] = 1000;
                }
            }
        }
        return response($response);
    }
    public function checkTemplate(){
        $data = Template::where('id',1)->first();
        $replaceArr = array(
            '{client_name}' => ucwords(\Auth::user()->firstName.' '.\Auth::user()->lastName)
        );
        $content = $data->content;
        foreach ($replaceArr as $key => $value) {
          $content = str_replace($key, $value, $content);  
        }
        $mailData['title'] = 'Welcome to Direct Connect';
        $mailData['content'] = $content;
        $r = Helpers::sendmail(env('MAIL_FROM'),env('APP_NAME'),'ranjan29@yopmail.com','Ranjan',$mailData['title'],$mailData,'emails.mail'); 
    }
}
