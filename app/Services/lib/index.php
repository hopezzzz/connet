
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once __DIR__.'/xmlapi.php'; // Need to Change the URL
$ip = 'cashmann.co.uk'; // Need to Change.
$account = "onewayit"; // Need to Change.
$domain = "cashmann.co.uk"; // Need to Change.
$account_pass = "Cash@1234"; // Need to Change.
$new_email = 'testmail';
$ip = "cashmann.co.uk"; //your server's IP
$xmlapi = new xmlapi($ip);
$xmlapi->password_auth("onewayit","Cash@1234"); //the server login info for the user you want to create the emails under
$xmlapi->set_output('json');

$params = array("domain"=>'cashmann.co.uk', "email"=>'newemailusername', "password"=>'newpassword', "quota"=>25); //quota is in MB
$addEmail = $xmlapi->api2_query("onewayit", "Email", "addpop", $params);
echo "<pre>";print_r($addEmail);die;
if($addEmail['cpanelresult']['data']['result']){
	echo "success";
}
else {
	echo "Error creating email account:\n".$addEmail['cpanelresult']['data']['reason'];
}


?>
