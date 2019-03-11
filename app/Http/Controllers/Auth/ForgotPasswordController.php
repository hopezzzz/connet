<?php
namespace App\Http\Controllers\Auth;
use Password;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    public function sendResetLinkEmail(Request $request)
    {
      $response = '';
      $rules = array(
        'email' => 'required|email'
      );
      $validator = Validator::make($request->all() , $rules);
      // if the validator fails, redirect back to the form
      if ($validator->fails())
      {
        $errors             	 = $validator->errors();
        $response['success']     = false;
        $response['formErrors']  = true;
        $response['errors']      = $errors;
      }
      else
      {
        $response =  Password::sendResetLink($request->only('email'), function($m)
        {
          $m->subject($this->getEmailSubject());
        });

        switch ($response)
        {
          case PasswordBroker::RESET_LINK_SENT:
          $responses['success']  = true;
          $responses['delayTime']       = '2000';
          // $responses['url']  = 'login';
          $responses['success_message'] = "A password link has been sent to your email address.";
          return response($responses);

          case PasswordBroker::INVALID_USER:
          $responses['success']  = false;
          $responses['delayTime']       = '2000';
          $responses['error_message'] = "We can't find a user with that email address.";
          return response($responses);
        }
      }
      return response($responses);

    }


}
