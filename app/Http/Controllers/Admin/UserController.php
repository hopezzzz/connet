<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\GlobalFunctions as Helpers;
Use App\RoleUser;
use Crypt;
use App\User;
use App\Model\UserPlan;
use App\Model\Campaign;
use App\Model\Parsetag;
use Session;
use Redirect;
use URL;
class UserController extends Controller
{
	public $url;
    public $admintemplatename;
    public $currentUrl;
    public function __construct()
    {
        $this->url=url(request()->route()->getPrefix());

        $this->admintemplatename = config('app.admintemplatename');
        $this->currentUrl = Helpers::getCurrentUrl();
        $this->middleware('guest', ['except' => ['logout','getCustomerList','viewCustomerDetail']]);
    }

	public function index()
	{

		return view($this->admintemplatename.'.users.login');
	}

	public function login(Request $request)
    {
	   	$credentials = $request->only('email', 'password');
	   	$authSuccess = Auth::attempt($credentials);
	   	if($authSuccess)
	   	{
            $role = Auth::User()->roles()->first()->id;
            if($role!=1)
            {
                $response['success'] = false;
                $response['error_message'] = "Either username or password is incorrect !!!";
                Auth::logout();
            }
            else
            {
    		   	$request->session()->regenerate();
    		   	$response['success'] = true;
    		   	$response['url'] = URL('admin/home');
    		   	$response['delayTime'] = 1000;
    		   	$response['success_message'] = "Login successful !!!";
    		   	return response($response);
            }
	   	}else{
		   	$response['success'] = false;
		   	$response['error_message'] = "Either username or password is incorrect !!!";
	   	}
	   	return response($response);
   	}

   	public function getCustomerList(Request $request)
    {
        $customers = User::where('register_step',3)->orderBy('id','DESC')->paginate(10);
        if ($request->ajax())
        {

			$page =  ( $request['page'] - 1 ) * 10;
			if ( isset($_GET['searchKey'] ) && trim($_GET['searchKey'] ) !='') {
				$searchKey = $request->get('searchKey');
                $customers=User::where(function($c) use($searchKey){ $c->where('firstName', 'like', '%'.$searchKey.'%')->orWhere('lastName', 'like', '%'.$searchKey.'%'); })->where('register_step',3)->orderBy('id','DESC')->paginate(10);
   			}
			return view($this->admintemplatename.'/customers/records_list',['url'=>$this->url,'current_url'=>$this->currentUrl,'customers'=>$customers,'page'=>$page]);
			}

        else
        {
            return view($this->admintemplatename.'/customers/index',['url'=>$this->url,'current_url'=>$this->currentUrl,'customers'=>$customers]);
        }
   	}
 
    public function getOffCustomerList(Request $request)
    {
        $customers = User::where('register_step','<',3)->orderBy('id','DESC')->paginate(10);
        if($request->ajax())
        {
            $page =  ( $request['page'] - 1 ) * 10;
            if ( isset($_GET['searchKey'] ) && trim($_GET['searchKey'] ) !='') {
                $searchKey = $request->get('searchKey');
                $customers=User::where(function($c) use($searchKey){ $c->where('firstName', 'like', '%'.$searchKey.'%')->orWhere('lastName', 'like', '%'.$searchKey.'%'); })->where('register_step','<',3)->orderBy('id','DESC')->paginate(10);
                }
                return view($this->admintemplatename.'/customers/records_list_off',['url'=>$this->url,'current_url'=>$this->currentUrl,'customers'=>$customers,'page'=>$page]);
                }

        else
        {
            return view($this->admintemplatename.'/customers/offCustomer',['url'=>$this->url,'current_url'=>$this->currentUrl,'customers'=>$customers]);
        }
    }

   	public function viewCustomerDetail(Request $request,$id)
    {

		$user_id = Crypt::decrypt($id);
        $userData = User::where('id',$user_id)->first();
        return view($this->admintemplatename.'/customers/viewCustomerDetail',['url'=>$this->url,'current_url'=>$this->currentUrl,'userData'=>$userData]);
   	}
    public function viewCustomerCampaign(Request $request,$id)
    {

        $user_id = Crypt::decrypt($id);
        $userData = Campaign::with('coutry_details')->where('user_id',$user_id)->orderBy('id','DESC');
	    $userDetail = User::where('id',$user_id)->first();
        // dd($userData->get()->toArray());
        if($userData->count() > 0){
            return view($this->admintemplatename.'/customers/viewCustomerCampaign',['url'=>$this->url,'current_url'=>$this->currentUrl,'userCamp'=>$userData->paginate(10),'userDetail'=>$userDetail]);
        }else{
            $response['success'] = false;
            $response['delayTime'] = 1000;
            $response['error_message'] = "No campaign record found !!!";
            $request->session()->put('error', json_encode($response));
            $data = $request->session()->all();
            // echo URL::previous();
            // die;
            return Redirect::back();
        }
    }
	public function viewCampaignDetail(Request $request, $id){
		$campID = Crypt::decrypt($id);
		$userCamp = Campaign::where('id',$campID)->first();
		return view($this->admintemplatename.'/customers/viewCampaignDetail',['url'=>$this->url,'current_url'=>$this->currentUrl,'userCamp'=>$userCamp]);
	}

}
