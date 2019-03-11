<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|

*/
$this->frontendtemplatename = config('app.frontendtemplatename');
Auth::routes();
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('/', function () {
  if(Auth::check()) {
    $role = Auth::User()->roles()->first()->name;
    if(Auth::check() && $role=='customer'){
      return redirect('customer/dashboard');
    }
    if(Auth::check() && $role=='admin'){
      return redirect('admin/home');
    }
  }else{
    return view($this->frontendtemplatename.'.login.index');
  }
});
Route::get('/customer', function () {
  if(Auth::check()) {
    $role = Auth::User()->roles()->first()->name;
    if(Auth::check() && $role=='customer'){
      return redirect('customer/dashboard');
    }else{
      return redirect('/');
    }
  }
});

Route::get('/admin', 'Admin\UserController@index');
Route::get('/admin/login', 'Admin\UserController@index');
Route::post('/admin/user/login', 'Admin\UserController@login');


//Admin as admin user
Route::group(['namespace' => 'Admin','prefix' => 'admin','middleware' => ['auth','role:admin']], function(){
  //Dashboard
  Route::resource('/home', 'DashboardController');
  //Regions
  Route::resource('regions', 'RegionController');
  Route::post('add-region', 'RegionController@saveRegion');
  Route::post('get-region-data', 'RegionController@RegionDetail');
  //Plans
  Route::resource('plans', 'PlanController');
  Route::get('add-new-plan', 'PlanController@addNewPlanView');
  Route::post('add-price', 'PlanController@addPriceAJAX');
  Route::post('save-plan', 'PlanController@savePlan');
  Route::get('edit-plan/{id}', 'PlanController@editPlanView');
  Route::post('update-plan', 'PlanController@updatePlan');
  Route::get('view-plan/{id}', 'PlanController@viewPlan');
  //Customers
  Route::get('customers', 'UserController@getCustomerList');
  Route::get('view-customer/{id}', 'UserController@viewCustomerDetail');
  Route::get('customer-campaign/{id}', 'UserController@viewCustomerCampaign');
  Route::get('campaign-detail/{id}', 'UserController@viewCampaignDetail');
  Route::get('off-customers', 'UserController@getOffCustomerList');
  //Reporting
  Route::get('reporting', 'DashboardController@reporting');
  Route::get('campaign-reports/{id}', 'DashboardController@campaignReports');
  Route::get('lead-reports/{id}', 'DashboardController@leadReports');
  //Email Templates
  Route::resource('email-templates', 'TemplateController');
  Route::get('add-template', 'TemplateController@addTemplateView');
  Route::get('edit-template/{id}', 'TemplateController@editTemplateView');
  Route::get('view-template/{id}', 'TemplateController@viewTemplate');
  Route::post('save-template', 'TemplateController@saveTemplate');
  Route::get('check-template','TemplateController@checkTemplate');
  Route::get('call-setting', 'DashboardController@callSettingsView');
  Route::post('call-setting', 'DashboardController@callSettings');

});
//Customer
Route::group(['namespace' => 'Customer','prefix' => 'customer','middleware' => ['auth','role:customer']], function(){
  //Customer Dashboard
  Route::resource('dashboard', 'DashboardController');
  Route::post('get-charts', 'DashboardController@getCharts');
  Route::resource('purchase', 'PurchaseController');
  Route::get('plans', 'PurchaseController@plansListing');
  //Campaign Routes
  Route::resource('campaigns', 'CampaignController');
  Route::get('add-new-campaign', 'CampaignController@addCampaignView');
  Route::post('add-campaign-steps', 'CampaignController@addCampaignData');
  Route::get('edit-campaign/{id}', 'CampaignController@editCampaignView');
  Route::get('view-campaign/{id}', 'CampaignController@viewCampaign');
  Route::get('campaign-leads/{id}', 'CampaignController@campaignLeadsCustomer');
  Route::post('fetch-campaign-email', 'CampaignController@fetchCampaignEmail');
  //Contacts Routes
  Route::resource('contacts', 'ContactController');
  Route::get('add-contact', 'ContactController@addContactView');
  Route::post('save-contact', 'ContactController@saveContact');
  Route::get('edit-contact/{id}', 'ContactController@editContactView');
  Route::get('get-contacts', 'ContactController@getContacts');
  Route::post('remove-contacts', 'ContactController@removeContacts');

  Route::get('reporting', 'DashboardController@reporting');
  Route::get('get-campaignPhone', 'CampaignController@campaignPhone');
  Route::post('add-contact-to-campaign', 'CampaignController@addPhoneCampaign');
  Route::post('remove-campaign-contact', 'CampaignController@removePhoneCampaign');
  Route::get('setting', 'DashboardController@settingsView');
  Route::post('change-email', 'DashboardController@changeEmail');
  Route::post('change-password', 'DashboardController@changePassword');
  Route::get('profile', 'DashboardController@profileView');
  Route::get('edit-profile', 'DashboardController@editProfileView');
  Route::post('update-profile', 'DashboardController@updateProfile');
  Route::post('save-card', 'DashboardController@saveCard');
  Route::post('delete-card', 'DashboardController@deleteCard');
  Route::post('call-setting', 'DashboardController@callSettings');
});

Route::get('admin/campaign-leads/{id}', '\App\Http\Controllers\Customer\CampaignController@campaignLeadsCustomer');
Route::get('get-lead-data/{id}', '\App\Http\Controllers\Customer\CampaignController@getLeadDetails');
Route::get('export-lead-data/{id}', '\App\Http\Controllers\Customer\CampaignController@exportLeadData');

Route::get('sign-up', 'UserController@index');
Route::post('registeration', 'UserController@saveClient');
Route::post('get-region-plans', 'UserController@getRegionPlansByCountryId');
Route::post('check-user-email', 'UserController@checkUserEmailExists');
Route::post('get-plain-info', 'UserController@getPlanInfoWithClientID');
Route::get('redirect-paypal/{amount}/{planname}/{userid}', 'UserController@payPalMonthlyRedirect');
Route::get('cancel-payment', 'UserController@cancelPayment');
Route::any('success-payment', 'UserController@successPayment');
Route::any('customer-update-webhook', 'UserController@webHookCustomer');
Route::any('subscription-cancelled', 'UserController@subscriptionCancelled');
Route::get('save-card', 'UserController@saveCreditCard');
Route::any('ipn', 'UserController@paypalIPN'); //Ravinder Kaur 04-08-2018
//Route::get('dashboard', 'CustomerController'); //Ravinder Kaur 04-08-2018
Route::get('re-sign-up/{id}','UserController@reSignUpUser');

//Logout
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
//Delete Route
Route::post('delete-record', 'CommonController@deleteRecord');
Route::post('change-status', 'CommonController@changeStatus');
Route::post('unsubscribePlan', 'CommonController@unsubscribePlan');
Route::post('add-new-tag', 'UserController@addNewTagAjax');
Route::post('delete-tag', 'UserController@deleteTagAjax');
Route::any('mailcheck', 'UserController@check_mail');
Route::post('sufflePlan', 'UserController@sufflePlan');

//EmailextractController Route
Route::get('create-emails', 'EmailextractController@createEmails');
Route::get('delete-emails', 'EmailextractController@deleteEmails');
Route::any('read-emails', 'EmailextractController@readEmails');
Route::any('make-calls', 'EmailextractController@makeCalls');
Route::any('auto-renewel', 'EmailextractController@makeCalls');
Route::any('customer/test-mail', 'EmailextractController@testMail');
Route::any('checkCampaings', 'EmailextractController@checkCampaings');
Route::any('gettags', 'EmailextractController@gettags');
Route::any('plan-expire-reminder', 'EmailextractController@expireReminder');
Route::post('leads-api-ajax', '\App\Http\Controllers\Customer\CampaignController@leadsApiAjax');
Route::post('call-rating', '\App\Http\Controllers\Customer\CampaignController@callRating');

Route::get('check-user-balance', '\App\Http\Controllers\UserController@checkBalanceUser');
Route::get('recharge-credit', '\App\Http\Controllers\UserController@rechargeCreditAmount');
Route::post('recharge-credit', '\App\Http\Controllers\UserController@rechargeCreditManually');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('test-form', 'CommonController@testForm');
Route::post('test-form-submit', 'CommonController@testFormSubmit');
