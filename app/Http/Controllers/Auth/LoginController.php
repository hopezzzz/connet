<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Validator;
use Session;
use Curl;
use DB;
use Crypt;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout(Request $request) {
        Auth::logout();
        return redirect('/login');
    }

    public function login(Request $request)
    {
        $rules = array(
          'email'    => 'required|email', // make sure the email is an actual email
          'password' => 'required'
        );
        $validator = Validator::make($request->all() , $rules);
        if ($validator->fails())
        {
          $errors                = $validator->errors();
          $response['success']     = false;
          $response['formErrors']  = true;
          $response['errors']      = $errors;
        }
        else
        {
            // $credentials = $request->only('email', 'password');
            $credentials = ['email' => $request->input('email'), 'password' => $request->input('password')];
            $authSuccess = Auth::attempt($credentials, $request->has('remember'));
            if($authSuccess  ) {    
                $role = Auth::user()->roles()->where('name','customer')->first()->name;
                if($role=='customer'){
                    $user_id = Auth::user()->id;
                    $step = Auth::user()->register_step;
                    if($step < 3)
                    {
                        $response['success'] = true;
                        $response['success_message'] = 'Your signup process is incomplete. We are redirecting you to complete your signup process before login !!!';
                        $response['url'] = url('re-sign-up/'.Crypt::encrypt($user_id));
                        $response['delayTime'] = 5000;
                        Auth::logout();
                        return response($response);
                    }
                    else
                    {
                        if(Auth::user()->api_client_id==0)
                        {
                            $planUser = Auth::user()->user_plan->plans;
                            $data = array(
                                'name'  => Auth::user()->firstName.' '.Auth::user()->lastName,
                                'email' => Auth::user()->email,
                                'password'   =>Crypt::decrypt(Auth::user()->enc_password),
                                'package_id' =>$planUser->api_package_id
                            );
                            $result = Curl::to(env('API_BASEPATH').'clientCreate/')
                            ->withContentType('application/json')
                            ->withOption('USERPWD', env('ADMIN_USER') . ":" . env('ADMIN_PASS'))
                            ->returnResponseObject()
                            ->withData($data)
                            ->asJson()
                            ->post();
                            if($result->status == 201)
                            {
                                DB::table('users')->where('id',$user_id)
                                ->update(['api_client_id'=>$result->content->id]);
                            }
                        }
                        $request->session()->regenerate();
                        $response['success']         = true;
                        $response['url']             = $role.'/dashboard';
                        $response['delayTime']       = '1000';
                        $response['success_message'] = 'Login successfully.';
                        return response($response);                        
                    }
                }
                else
                {
                    $response['success'] = false;
                    $response['delayTime']     = '2000';
                    $response['error_message'] = 'Email and password is not match.Please try again.';
                    return response($response);
                }
            }
            else
            {
                $response['success'] = false;
                $response['delayTime']     = '2000';
                $response['error_message'] = 'Email and password is not match.Please try again.';
                return response($response);
            }
        }
        return response($response);
    }

}
