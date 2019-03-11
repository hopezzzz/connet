<?php
namespace App\Helpers;

use DOMDocument;
use DB;
use Mail;
use URL;
use App\Services\lib\xmlapi;
use App\Services\emailExtract\EmailParser;
Use App\Model\Parsetag;
use App\Model\Campaign;
use App\BillingType;


class GlobalFunctions {
	public static function generateRef($length = 10)
	{
		return $randomStrings = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	}
	public static function getCurrentUrl(){
		$current_url = url()->current();
		$base_url = url('/');
		return substr($current_url,strlen($base_url));
	}
	//function to send mail
	public static function sendmail($from,$fromname,$to,$toname,$subject,$data,$mailview){
		Mail::send($mailview, $data, function($message) use ($from,$fromname,$to,$toname,$subject){
           $message->from($from,$fromname);
           $message->to($to,$toname);
           $message->subject($subject);
        });
        $response = 1;
        if(Mail::failures()){
        	$response = 0;
        }
        return $response;
	}

	public static function fetchCurrencyDropdown()
	{
		$regions = DB::table('countries')->select('currencyName', 'countryId','currencyCode','currencySymbol')->get()->toArray();
		return $regions;
	}

        public static function RemoveSpecialChapr($value){
		$title = str_replace( array('\'', '"', ',' , ';', '<', '>','*','^','&iexcl;', '¡', '&cent;', '¢', '&pound;', '£',
        '&curren;', '¤', '&yen;', '¥', '&brvbar;', '¦', '&sect;', '§',
        '&uml;', '¨', '&copy;', '©', '&ordf;', 'ª', '&laquo;', '«',
        '&not;', '¬', '&shy;', '­', '&reg;', '®', '&macr;', '¯',
        '&deg;', '°', '&plusmn;', '±', '&sup2;', '²', '&sup3;', '³',
        '&acute;', '´', '&micro;', 'µ', '&para;', '¶', '&middot;', '·',
        '&cedil;', '¸', '&sup1;', '¹', '&ordm;', 'º', '&raquo;', '»',
        '&frac14;', '¼', '&frac12;', '½', '&frac34;', '¾', '&iquest;', '¿',
        '&Agrave;', 'À', '&Aacute;', 'Á', '&Acirc;', 'Â', '&Atilde;', 'Ã',
        '&Auml;', 'Ä', '&Aring;', 'Å', '&AElig;', 'Æ', '&Ccedil;', 'Ç',
        '&Egrave;', 'È', '&Eacute;', 'É', '&Ecirc;', 'Ê', '&Euml;', 'Ë',
        '&Igrave;', 'Ì', '&Iacute;', 'Í', '&Icirc;', 'Î', '&Iuml;', 'Ï',
        '&ETH;', 'Ð', '&Ntilde;', 'Ñ', '&Ograve;', 'Ò', '&Oacute;', 'Ó',
        '&Ocirc;', 'Ô', '&Otilde;', 'Õ', '&Ouml;', 'Ö', '&times;', '×',
        '&Oslash;', 'Ø', '&Ugrave;', 'Ù', '&Uacute;', 'Ú', '&Ucirc;', 'Û',
        '&Uuml;', 'Ü', '&Yacute;', 'Ý', '&THORN;', 'Þ', '&szlig;', 'ß',
        '&agrave;', 'à', '&aacute;', 'á', '&acirc;', 'â', '&atilde;', 'ã',
        '&auml;', 'ä', '&aring;', 'å', '&aelig;', 'æ', '&ccedil;', 'ç',
        '&egrave;', 'è', '&eacute;', 'é', '&ecirc;', 'ê', '&euml;', 'ë',
        '&igrave;', 'ì', '&iacute;', 'í', '&icirc;', 'î', '&iuml;', 'ï',
        '&eth;', 'ð', '&ntilde;', 'ñ', '&ograve;', 'ò', '&oacute;', 'ó',
        '&ocirc;', 'ô', '&otilde;', 'õ', '&ouml;', 'ö', '&divide;', '÷',
        '&oslash;', 'ø', '&ugrave;', 'ù', '&uacute;', 'ú', '&ucirc;', 'û',
        '&uuml;', 'ü', '&yacute;', 'ý', '&thorn;', 'þ', '&yuml;', 'ÿ',
        '&OElig;', 'Œ', '&oelig;', 'œ', '&Scaron;', 'Š', '&scaron;', 'š',
        '&Yuml;', 'Ÿ', '&fnof;', 'ƒ', '&circ;', 'ˆ', '&tilde;', '˜',
        '&Gamma;', 'Γ', '&Delta;', 'Δ', '&Theta;', 'Θ',
        '&Lambda;', 'Λ', '&Mu;', '&Xi;', 'Ξ', '&Pi;', 'Π',
        '&Sigma;', 'Σ',
        '&Phi;', 'Φ', '&Psi;', 'Ψ', '&Omega;', 'Ω',
        '&alpha;', 'α', '&beta;', 'β', '&gamma;', 'γ', '&delta;', 'δ',
        '&epsilon;', 'ε', '&zeta;', 'ζ', '&eta;', 'η', '&theta;', 'θ',
        '&iota;', 'ι', '&kappa;', 'κ', '&lambda;', 'λ', '&mu;', 'μ',
        '&nu;', 'ν', '&xi;', 'ξ', '&omicron;', 'ο', '&pi;', 'π',
        '&rho;', 'ρ', '&sigmaf;', 'ς', '&sigma;', 'σ', '&tau;', 'τ',
        '&upsilon;', 'υ', '&phi;', 'φ', '&chi;', 'χ', '&psi;', 'ψ',
        '&omega;', 'ω', '&thetasym;', 'ϑ', '&upsih;', 'ϒ', '&piv;', 'ϖ',
        '&ensp;', ' ', '&emsp;', ' ', '&thinsp;', ' ', '&zwnj;', '‌',
        '&zwj;', '‍', '&lrm;', '‎', '&rlm;', '‏', '&ndash;', '–',
        '&mdash;', '—', '&lsquo;', '‘', '&rsquo;', '’', '&sbquo;', '‚',
        '&ldquo;', '“', '&rdquo;', '”', '&bdquo;', '„', '&dagger;', '†',
        '&Dagger;', '‡', '&bull;', '•', '&hellip;', '…', '&permil;', '‰',
        '&prime;', '′', '&Prime;', '″', '&lsaquo;', '‹', '&rsaquo;', '›',
        '&oline;', '‾', '&frasl;', '⁄', '&euro;', '€', '&image;', 'ℑ',
        '&weierp;', '℘', '&real;', 'ℜ', '&trade;', '™', '&alefsym;', 'ℵ',
        '&larr;', '←', '&uarr;', '↑', '&rarr;', '→', '&darr;', '↓',
        '&harr;', '↔', '&crarr;', '↵', '&lArr;', '⇐', '&uArr;', '⇑',
        '&rArr;', '⇒', '&dArr;', '⇓', '&hArr;', '⇔', '&forall;', '∀',
        '&part;', '∂', '&exist;', '∃', '&empty;', '∅', '&nabla;', '∇',
        '&isin;', '∈', '&notin;', '∉', '&ni;', '∋', '&prod;', '∏',
        '&sum;', '∑', '&minus;', '−', '&lowast;', '∗', '&radic;', '√',
        '&prop;', '∝', '&infin;', '∞', '&ang;', '∠', '&and;', '∧', '&int;', '∫',
        '&there4;', '∴', '&sim;', '∼', '&cong;', '≅', '&asymp;', '≈',
        '&ne;', '≠', '&equiv;', '≡', '&le;', '≤', '&ge;', '≥',
        '&sub;', '⊂', '&sup;', '⊃', '&nsub;', '⊄', '&sube;', '⊆',
        '&supe;', '⊇', '&oplus;', '⊕', '&otimes;', '⊗', '&perp;', '⊥',
        '&lceil;', '⌈', '&rceil;', '⌉', '&lfloor;', '⌊',
        '&rfloor;', '⌋', '&lang;', '〈', '&rang;', '〉', '&loz;', '◊',
        '&spades;', '♠', '&clubs;', '♣', '&hearts;', '♥', '&diams;', '♦'
    ), '', $value);

return $title;
	}



	/**
 * Performs an Express Checkout NVP API operation as passed in $action.
 *
 * Although the PayPal Standard API provides no facility for cancelling a subscription, the PayPal
 * Express Checkout  NVP API can be used.
 */
	public static function change_subscription_status( $profile_id, $action ) {

    $api_request = 	'USER=' . urlencode( 'oneway_api1.gmail.com' )
								.  '&PWD=' . urlencode( 'XJ3436KW96J2SUDL' )
								.  '&SIGNATURE=' . urlencode( 'AMXCGZ8uAG4ciodg1czREN6T8zWvAMWdhlN-ETYn2qhJZVX9jkrl32-I' )
                .  '&VERSION=76.0'
                .  '&METHOD=ManageRecurringPaymentsProfileStatus'
                .  '&PROFILEID=' . urlencode( $profile_id )
                .  '&ACTION=' . urlencode( $action )
                .  '&NOTE=' . urlencode( 'Profile cancelled at store' );

								// echo $api_request;

    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, 'https://api-3t.sandbox.paypal.com/nvp' ); // For live transactions, change to 'https://api-3t.paypal.com/nvp'
    curl_setopt( $ch, CURLOPT_VERBOSE, 1 );

    // Uncomment these to turn off server and peer verification
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt( $ch, CURLOPT_POST, 1 );

    // Set the API parameters for this transaction
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $api_request );

    // Request response from PayPal
    $response = curl_exec( $ch );

    // If no response was received from PayPal there is no point parsing the response
    if( ! $response )
        die( 'Calling PayPal to change_subscription_status failed: ' . curl_error( $ch ) . '(' . curl_errno( $ch ) . ')' );

    curl_close( $ch );

    // An associative array is more usable than a parameter string
    parse_str( $response, $parsed_response );
    return $parsed_response;
		}


    public static function createEmail($email, $password)
    {
        $dd           = new xmlapi(env('WEBSITE_MAIL_DOMAIN')); // server
        $account      = env('WEBSITE_MAIL_USER'); // Need to Change.
        $domain       = env('WEBSITE_MAIL_DOMAIN'); // Need to Change.
        $account_pass = env('WEBSITE_MAIL_PASS'); // Need to Change.
        $ip           = env('WEBSITE_MAIL_DOMAIN'); //your server's IP
        $dd->password_auth($account, $account_pass); //the server login info for the user you want to create the emails under
        $dd->set_output('json');
        $params   = array(
            "domain" => $domain,
            "email" => $email,
            "password" => $password,
            "quota" => 25
        ); //quota is in MB
        $addEmail = $dd->api2_query($account, "Email", "addpop", $params);
        return (json_decode($addEmail));
    }


		public static function addFwdEmail($email)
		{
			$domain       = env('WEBSITE_MAIL_DOMAIN'); // Need to Change.
			$account      = env('WEBSITE_MAIL_USER');   // Need to Change.
			$account_pass = env('WEBSITE_MAIL_PASS');   // Need to Change.
			$fwdMail 			= env('WEBSITE_FWD_MAIL');    // Need to Change.
			$xmlapi 			= new xmlapi($domain);
			$xmlapi->set_output("json");
			$xmlapi->password_auth($account,$account_pass);
			$xmlapi->set_debug(1);
			$fwdArr = array('domain' => $domain,'email' => $email,'fwdopt' => 'fwd','fwdemail'=> $fwdMail);
			$result = $xmlapi->api2_query($account,'Email', 'addforward',$fwdArr);
			return true;
		}
		public static function deleteEmail($email)
		{
			$domain       = env('WEBSITE_MAIL_DOMAIN'); // Need to Change.
			$account      = env('WEBSITE_MAIL_USER');   // Need to Change.
			$account_pass = env('WEBSITE_MAIL_PASS');   // Need to Change.
			$fwdMail 			= env('WEBSITE_FWD_MAIL');    // Need to Change.
			$xmlapi 			= new xmlapi($domain);
			$xmlapi->set_output("json");
			$xmlapi->password_auth($account,$account_pass);
			$xmlapi->set_debug(1);
			$delpopArr = 	array('domain'=> $domain,'email'=> $email);
			$delforwardArr = array('email'=>$email,'emaildest'  =>$fwdMail);
			$result = $xmlapi->api2_query('onewayit','Email', 'delpop',$delpopArr);


			$result = $xmlapi->api2_query('onewayit','Email', 'delforward',$delforwardArr);
			return true;
		}


    public static function readEmails($server, $email, $password)
    {
        require_once app_path().'/Services/html2textt/autoload.php';
        $readMail = new EmailParser($server, $email, $password);
        $dd       = $readMail->getContent();

        $body     = array();
				$i = 0;
        foreach ($dd as $key => $value) {
            $to =  explode(PHP_EOL,$value->raw);
            foreach($to as $toVal)
            {
                if(strpos($toVal, 'Envelope-to:') !== false)
                {
                    $top = explode('Envelope-to:',$toVal);
                    $to = trim($top[1]);
                    break;
                }
            }
            $to = explode(',',$to)[0];
            $to = filter_var($to, FILTER_SANITIZE_STRING);
            if(trim($value->html) !=''){
              $body[$i]['body'] = \Html2Text\Html2Text::convert($value->html);
              $body[$i]['to'] = $to;
              $body[$i]['date'] = $value->date;
            }
						else
						{
							if(trim($value->body) !=''){
									$body[$i]['body'] = $value->body;
									$body[$i]['to'] = $to;
									$body[$i]['date'] = $value->date;
							}
						}

            $i++;
        }
        return $body;
    }

    public static function parserOut($temp,$template,$id){
        require_once app_path().'/Services/html2textt/autoload.php';
        $temp = \Html2Text\Html2Text::convert($temp);
            preg_match_all('/{(.*?)}/', $template, $matches);
            $parsingTerms     = $matches[1];
            $replacements     = array();

            $emailLines       = explode("\n", $temp);
            $emailLines = array_filter($emailLines);
            $emailLines = array_values($emailLines);
            $template         = $template;
            $camp             = Parsetag::where('campId',$id)->get();
            $parseArr         = array();
            foreach ($camp as $key => $c)
						{
              $line                  = Self::RemoveSpecialChapr($emailLines[$c->indexRow]);;
              $parseContent          = substr($line,$c->positionStart);
							if (trim(strtolower($c->tagName)) == 'message')
							{
								$message = array_slice($emailLines,$c->indexRow);
								if (isset($camp[$key+1])){ $message = array_slice($emailLines,$c->indexRow,$camp[$key+1]->indexRow);}
								$parseContent = implode(' ', $message);
								$parseContent          = substr($parseContent,$c->positionStart);
							}
              $parseArr[$c->tagName] = trim($parseContent);
            }
            $arrLower = array_change_key_case($parseArr,CASE_LOWER);
						if (isset($arrLower['phone'])) {
							$parseArr['phone'] = ltrim($arrLower['phone'],'0');
						}
						if (isset($arrLower['Phone'])) {
							$parseArr['Phone'] = ltrim($arrLower['phone'],'0');
						}
            foreach($parseArr as $k => $val)
            {
              $search = '{'.$k.'}';
              $parseMail = str_replace($search, $val, $template);
              $template = $parseMail;
            }
            $callScript = $parseMail;
            return $callScript;

    }

    public static function get_time_zone($country, $region)
    {
        switch ($country) {
            case "US":
                switch ($region) {
                    case "AL":
                        $timezone = "America/Chicago";
                        break;
                    case "AK":
                        $timezone = "America/Anchorage";
                        break;
                    case "AZ":
                        $timezone = "America/Phoenix";
                        break;
                    case "AR":
                        $timezone = "America/Chicago";
                        break;
                    case "CA":
                        $timezone = "America/Los_Angeles";
                        break;
                    case "CO":
                        $timezone = "America/Denver";
                        break;
                    case "CT":
                        $timezone = "America/New_York";
                        break;
                    case "DE":
                        $timezone = "America/New_York";
                        break;
                    case "DC":
                        $timezone = "America/New_York";
                        break;
                    case "FL":
                        $timezone = "America/New_York";
                        break;
                    case "GA":
                        $timezone = "America/New_York";
                        break;
                    case "HI":
                        $timezone = "Pacific/Honolulu";
                        break;
                    case "ID":
                        $timezone = "America/Denver";
                        break;
                    case "IL":
                        $timezone = "America/Chicago";
                        break;
                    case "IN":
                        $timezone = "America/Indianapolis";
                        break;
                    case "IA":
                        $timezone = "America/Chicago";
                        break;
                    case "KS":
                        $timezone = "America/Chicago";
                        break;
                    case "KY":
                        $timezone = "America/New_York";
                        break;
                    case "LA":
                        $timezone = "America/Chicago";
                        break;
                    case "ME":
                        $timezone = "America/New_York";
                        break;
                    case "MD":
                        $timezone = "America/New_York";
                        break;
                    case "MA":
                        $timezone = "America/New_York";
                        break;
                    case "MI":
                        $timezone = "America/New_York";
                        break;
                    case "MN":
                        $timezone = "America/Chicago";
                        break;
                    case "MS":
                        $timezone = "America/Chicago";
                        break;
                    case "MO":
                        $timezone = "America/Chicago";
                        break;
                    case "MT":
                        $timezone = "America/Denver";
                        break;
                    case "NE":
                        $timezone = "America/Chicago";
                        break;
                    case "NV":
                        $timezone = "America/Los_Angeles";
                        break;
                    case "NH":
                        $timezone = "America/New_York";
                        break;
                    case "NJ":
                        $timezone = "America/New_York";
                        break;
                    case "NM":
                        $timezone = "America/Denver";
                        break;
                    case "NY":
                        $timezone = "America/New_York";
                        break;
                    case "NC":
                        $timezone = "America/New_York";
                        break;
                    case "ND":
                        $timezone = "America/Chicago";
                        break;
                    case "OH":
                        $timezone = "America/New_York";
                        break;
                    case "OK":
                        $timezone = "America/Chicago";
                        break;
                    case "OR":
                        $timezone = "America/Los_Angeles";
                        break;
                    case "PA":
                        $timezone = "America/New_York";
                        break;
                    case "RI":
                        $timezone = "America/New_York";
                        break;
                    case "SC":
                        $timezone = "America/New_York";
                        break;
                    case "SD":
                        $timezone = "America/Chicago";
                        break;
                    case "TN":
                        $timezone = "America/Chicago";
                        break;
                    case "TX":
                        $timezone = "America/Chicago";
                        break;
                    case "UT":
                        $timezone = "America/Denver";
                        break;
                    case "VT":
                        $timezone = "America/New_York";
                        break;
                    case "VA":
                        $timezone = "America/New_York";
                        break;
                    case "WA":
                        $timezone = "America/Los_Angeles";
                        break;
                    case "WV":
                        $timezone = "America/New_York";
                        break;
                    case "WI":
                        $timezone = "America/Chicago";
                        break;
                    case "WY":
                        $timezone = "America/Denver";
                        break;
                }
                break;
            case "CA":
                switch ($region) {
                    case "AB":
                        $timezone = "America/Edmonton";
                        break;
                    case "BC":
                        $timezone = "America/Vancouver";
                        break;
                    case "MB":
                        $timezone = "America/Winnipeg";
                        break;
                    case "NB":
                        $timezone = "America/Halifax";
                        break;
                    case "NL":
                        $timezone = "America/St_Johns";
                        break;
                    case "NT":
                        $timezone = "America/Yellowknife";
                        break;
                    case "NS":
                        $timezone = "America/Halifax";
                        break;
                    case "NU":
                        $timezone = "America/Rankin_Inlet";
                        break;
                    case "ON":
                        $timezone = "America/Rainy_River";
                        break;
                    case "PE":
                        $timezone = "America/Halifax";
                        break;
                    case "QC":
                        $timezone = "America/Montreal";
                        break;
                    case "SK":
                        $timezone = "America/Regina";
                        break;
                    case "YT":
                        $timezone = "America/Whitehorse";
                        break;
                }
                break;
            case "AU":
                switch ($region) {
                    case "01":
                        $timezone = "Australia/Canberra";
                        break;
                    case "02":
                        $timezone = "Australia/NSW";
                        break;
                    case "03":
                        $timezone = "Australia/North";
                        break;
                    case "04":
                        $timezone = "Australia/Queensland";
                        break;
                    case "05":
                        $timezone = "Australia/South";
                        break;
                    case "06":
                        $timezone = "Australia/Tasmania";
                        break;
                    case "07":
                        $timezone = "Australia/Victoria";
                        break;
                    case "08":
                        $timezone = "Australia/West";
                        break;
                }
                break;
            case "AS":
                $timezone = "US/Samoa";
                break;
            case "CI":
                $timezone = "Africa/Abidjan";
                break;
            case "GH":
                $timezone = "Africa/Accra";
                break;
            case "DZ":
                $timezone = "Africa/Algiers";
                break;
            case "ER":
                $timezone = "Africa/Asmera";
                break;
            case "ML":
                $timezone = "Africa/Bamako";
                break;
            case "CF":
                $timezone = "Africa/Bangui";
                break;
            case "GM":
                $timezone = "Africa/Banjul";
                break;
            case "GW":
                $timezone = "Africa/Bissau";
                break;
            case "CG":
                $timezone = "Africa/Brazzaville";
                break;
            case "BI":
                $timezone = "Africa/Bujumbura";
                break;
            case "EG":
                $timezone = "Africa/Cairo";
                break;
            case "MA":
                $timezone = "Africa/Casablanca";
                break;
            case "GN":
                $timezone = "Africa/Conakry";
                break;
            case "SN":
                $timezone = "Africa/Dakar";
                break;
            case "DJ":
                $timezone = "Africa/Djibouti";
                break;
            case "SL":
                $timezone = "Africa/Freetown";
                break;
            case "BW":
                $timezone = "Africa/Gaborone";
                break;
            case "ZW":
                $timezone = "Africa/Harare";
                break;
            case "ZA":
                $timezone = "Africa/Johannesburg";
                break;
            case "UG":
                $timezone = "Africa/Kampala";
                break;
            case "SD":
                $timezone = "Africa/Khartoum";
                break;
            case "RW":
                $timezone = "Africa/Kigali";
                break;
            case "NG":
                $timezone = "Africa/Lagos";
                break;
            case "GA":
                $timezone = "Africa/Libreville";
                break;
            case "TG":
                $timezone = "Africa/Lome";
                break;
            case "AO":
                $timezone = "Africa/Luanda";
                break;
            case "ZM":
                $timezone = "Africa/Lusaka";
                break;
            case "GQ":
                $timezone = "Africa/Malabo";
                break;
            case "MZ":
                $timezone = "Africa/Maputo";
                break;
            case "LS":
                $timezone = "Africa/Maseru";
                break;
            case "SZ":
                $timezone = "Africa/Mbabane";
                break;
            case "SO":
                $timezone = "Africa/Mogadishu";
                break;
            case "LR":
                $timezone = "Africa/Monrovia";
                break;
            case "KE":
                $timezone = "Africa/Nairobi";
                break;
            case "TD":
                $timezone = "Africa/Ndjamena";
                break;
            case "NE":
                $timezone = "Africa/Niamey";
                break;
            case "MR":
                $timezone = "Africa/Nouakchott";
                break;
            case "BF":
                $timezone = "Africa/Ouagadougou";
                break;
            case "ST":
                $timezone = "Africa/Sao_Tome";
                break;
            case "LY":
                $timezone = "Africa/Tripoli";
                break;
            case "TN":
                $timezone = "Africa/Tunis";
                break;
            case "AI":
                $timezone = "America/Anguilla";
                break;
            case "AG":
                $timezone = "America/Antigua";
                break;
            case "AW":
                $timezone = "America/Aruba";
                break;
            case "BB":
                $timezone = "America/Barbados";
                break;
            case "BZ":
                $timezone = "America/Belize";
                break;
            case "CO":
                $timezone = "America/Bogota";
                break;
            case "VE":
                $timezone = "America/Caracas";
                break;
            case "KY":
                $timezone = "America/Cayman";
                break;
            case "CR":
                $timezone = "America/Costa_Rica";
                break;
            case "DM":
                $timezone = "America/Dominica";
                break;
            case "SV":
                $timezone = "America/El_Salvador";
                break;
            case "GD":
                $timezone = "America/Grenada";
                break;
            case "FR":
                $timezone = "Europe/Paris";
                break;
            case "GP":
                $timezone = "America/Guadeloupe";
                break;
            case "GT":
                $timezone = "America/Guatemala";
                break;
            case "GY":
                $timezone = "America/Guyana";
                break;
            case "CU":
                $timezone = "America/Havana";
                break;
            case "JM":
                $timezone = "America/Jamaica";
                break;
            case "BO":
                $timezone = "America/La_Paz";
                break;
            case "PE":
                $timezone = "America/Lima";
                break;
            case "NI":
                $timezone = "America/Managua";
                break;
            case "MQ":
                $timezone = "America/Martinique";
                break;
            case "UY":
                $timezone = "America/Montevideo";
                break;
            case "MS":
                $timezone = "America/Montserrat";
                break;
            case "BS":
                $timezone = "America/Nassau";
                break;
            case "PA":
                $timezone = "America/Panama";
                break;
            case "SR":
                $timezone = "America/Paramaribo";
                break;
            case "PR":
                $timezone = "America/Puerto_Rico";
                break;
            case "KN":
                $timezone = "America/St_Kitts";
                break;
            case "LC":
                $timezone = "America/St_Lucia";
                break;
            case "VC":
                $timezone = "America/St_Vincent";
                break;
            case "HN":
                $timezone = "America/Tegucigalpa";
                break;
            case "YE":
                $timezone = "Asia/Aden";
                break;
            case "JO":
                $timezone = "Asia/Amman";
                break;
            case "TM":
                $timezone = "Asia/Ashgabat";
                break;
            case "IQ":
                $timezone = "Asia/Baghdad";
                break;
            case "BH":
                $timezone = "Asia/Bahrain";
                break;
            case "AZ":
                $timezone = "Asia/Baku";
                break;
            case "TH":
                $timezone = "Asia/Bangkok";
                break;
            case "LB":
                $timezone = "Asia/Beirut";
                break;
            case "KG":
                $timezone = "Asia/Bishkek";
                break;
            case "BN":
                $timezone = "Asia/Brunei";
                break;
            case "IN":
                $timezone = "Asia/Calcutta";
                break;
            case "MN":
                $timezone = "Asia/Choibalsan";
                break;
            case "LK":
                $timezone = "Asia/Colombo";
                break;
            case "BD":
                $timezone = "Asia/Dhaka";
                break;
            case "AE":
                $timezone = "Asia/Dubai";
                break;
            case "TJ":
                $timezone = "Asia/Dushanbe";
                break;
            case "HK":
                $timezone = "Asia/Hong_Kong";
                break;
            case "TR":
                $timezone = "Asia/Istanbul";
                break;
            case "IL":
                $timezone = "Asia/Jerusalem";
                break;
            case "AF":
                $timezone = "Asia/Kabul";
                break;
            case "PK":
                $timezone = "Asia/Karachi";
                break;
            case "NP":
                $timezone = "Asia/Katmandu";
                break;
            case "KW":
                $timezone = "Asia/Kuwait";
                break;
            case "MO":
                $timezone = "Asia/Macao";
                break;
            case "PH":
                $timezone = "Asia/Manila";
                break;
            case "OM":
                $timezone = "Asia/Muscat";
                break;
            case "CY":
                $timezone = "Asia/Nicosia";
                break;
            case "KP":
                $timezone = "Asia/Pyongyang";
                break;
            case "QA":
                $timezone = "Asia/Qatar";
                break;
            case "MM":
                $timezone = "Asia/Rangoon";
                break;
            case "SA":
                $timezone = "Asia/Riyadh";
                break;
            case "KR":
                $timezone = "Asia/Seoul";
                break;
            case "SG":
                $timezone = "Asia/Singapore";
                break;
            case "TW":
                $timezone = "Asia/Taipei";
                break;
            case "GE":
                $timezone = "Asia/Tbilisi";
                break;
            case "BT":
                $timezone = "Asia/Thimphu";
                break;
            case "JP":
                $timezone = "Asia/Tokyo";
                break;
            case "LA":
                $timezone = "Asia/Vientiane";
                break;
            case "AM":
                $timezone = "Asia/Yerevan";
                break;
            case "BM":
                $timezone = "Atlantic/Bermuda";
                break;
            case "CV":
                $timezone = "Atlantic/Cape_Verde";
                break;
            case "FO":
                $timezone = "Atlantic/Faeroe";
                break;
            case "IS":
                $timezone = "Atlantic/Reykjavik";
                break;
            case "GS":
                $timezone = "Atlantic/South_Georgia";
                break;
            case "SH":
                $timezone = "Atlantic/St_Helena";
                break;
            case "CL":
                $timezone = "Chile/Continental";
                break;
            case "NL":
                $timezone = "Europe/Amsterdam";
                break;
            case "AD":
                $timezone = "Europe/Andorra";
                break;
            case "GR":
                $timezone = "Europe/Athens";
                break;
            case "YU":
                $timezone = "Europe/Belgrade";
                break;
            case "DE":
                $timezone = "Europe/Berlin";
                break;
            case "SK":
                $timezone = "Europe/Bratislava";
                break;
            case "BE":
                $timezone = "Europe/Brussels";
                break;
            case "RO":
                $timezone = "Europe/Bucharest";
                break;
            case "HU":
                $timezone = "Europe/Budapest";
                break;
            case "DK":
                $timezone = "Europe/Copenhagen";
                break;
            case "IE":
                $timezone = "Europe/Dublin";
                break;
            case "GI":
                $timezone = "Europe/Gibraltar";
                break;
            case "FI":
                $timezone = "Europe/Helsinki";
                break;
            case "SI":
                $timezone = "Europe/Ljubljana";
                break;
            case "GB":
                $timezone = "Europe/London";
                break;
            case "LU":
                $timezone = "Europe/Luxembourg";
                break;
            case "MT":
                $timezone = "Europe/Malta";
                break;
            case "BY":
                $timezone = "Europe/Minsk";
                break;
            case "MC":
                $timezone = "Europe/Monaco";
                break;
            case "NO":
                $timezone = "Europe/Oslo";
                break;
            case "CZ":
                $timezone = "Europe/Prague";
                break;
            case "LV":
                $timezone = "Europe/Riga";
                break;
            case "IT":
                $timezone = "Europe/Rome";
                break;
            case "SM":
                $timezone = "Europe/San_Marino";
                break;
            case "BA":
                $timezone = "Europe/Sarajevo";
                break;
            case "MK":
                $timezone = "Europe/Skopje";
                break;
            case "BG":
                $timezone = "Europe/Sofia";
                break;
            case "SE":
                $timezone = "Europe/Stockholm";
                break;
            case "EE":
                $timezone = "Europe/Tallinn";
                break;
            case "AL":
                $timezone = "Europe/Tirane";
                break;
            case "LI":
                $timezone = "Europe/Vaduz";
                break;
            case "VA":
                $timezone = "Europe/Vatican";
                break;
            case "AT":
                $timezone = "Europe/Vienna";
                break;
            case "LT":
                $timezone = "Europe/Vilnius";
                break;
            case "PL":
                $timezone = "Europe/Warsaw";
                break;
            case "HR":
                $timezone = "Europe/Zagreb";
                break;
            case "IR":
                $timezone = "Asia/Tehran";
                break;
            case "MG":
                $timezone = "Indian/Antananarivo";
                break;
            case "CX":
                $timezone = "Indian/Christmas";
                break;
            case "CC":
                $timezone = "Indian/Cocos";
                break;
            case "KM":
                $timezone = "Indian/Comoro";
                break;
            case "MV":
                $timezone = "Indian/Maldives";
                break;
            case "MU":
                $timezone = "Indian/Mauritius";
                break;
            case "YT":
                $timezone = "Indian/Mayotte";
                break;
            case "RE":
                $timezone = "Indian/Reunion";
                break;
            case "FJ":
                $timezone = "Pacific/Fiji";
                break;
            case "TV":
                $timezone = "Pacific/Funafuti";
                break;
            case "GU":
                $timezone = "Pacific/Guam";
                break;
            case "NR":
                $timezone = "Pacific/Nauru";
                break;
            case "NU":
                $timezone = "Pacific/Niue";
                break;
            case "NF":
                $timezone = "Pacific/Norfolk";
                break;
            case "PW":
                $timezone = "Pacific/Palau";
                break;
            case "PN":
                $timezone = "Pacific/Pitcairn";
                break;
            case "CK":
                $timezone = "Pacific/Rarotonga";
                break;
            case "WS":
                $timezone = "Pacific/Samoa";
                break;
            case "KI":
                $timezone = "Pacific/Tarawa";
                break;
            case "TO":
                $timezone = "Pacific/Tongatapu";
                break;
            case "WF":
                $timezone = "Pacific/Wallis";
                break;
            case "TZ":
                $timezone = "Africa/Dar_es_Salaam";
                break;
            case "VN":
                $timezone = "Asia/Phnom_Penh";
                break;
            case "KH":
                $timezone = "Asia/Phnom_Penh";
                break;
            case "CM":
                $timezone = "Africa/Lagos";
                break;
            case "DO":
                $timezone = "America/Santo_Domingo";
                break;
            case "ET":
                $timezone = "Africa/Addis_Ababa";
                break;
            case "FX":
                $timezone = "Europe/Paris";
                break;
            case "HT":
                $timezone = "America/Port-au-Prince";
                break;
            case "CH":
                $timezone = "Europe/Zurich";
                break;
            case "AN":
                $timezone = "America/Curacao";
                break;
            case "BJ":
                $timezone = "Africa/Porto-Novo";
                break;
            case "EH":
                $timezone = "Africa/El_Aaiun";
                break;
            case "FK":
                $timezone = "Atlantic/Stanley";
                break;
            case "GF":
                $timezone = "America/Cayenne";
                break;
            case "IO":
                $timezone = "Indian/Chagos";
                break;
            case "MD":
                $timezone = "Europe/Chisinau";
                break;
            case "MP":
                $timezone = "Pacific/Saipan";
                break;
            case "MW":
                $timezone = "Africa/Blantyre";
                break;
            case "NA":
                $timezone = "Africa/Windhoek";
                break;
            case "NC":
                $timezone = "Pacific/Noumea";
                break;
            case "PG":
                $timezone = "Pacific/Port_Moresby";
                break;
            case "PM":
                $timezone = "America/Miquelon";
                break;
            case "PS":
                $timezone = "Asia/Gaza";
                break;
            case "PY":
                $timezone = "America/Asuncion";
                break;
            case "SB":
                $timezone = "Pacific/Guadalcanal";
                break;
            case "SC":
                $timezone = "Indian/Mahe";
                break;
            case "SJ":
                $timezone = "Arctic/Longyearbyen";
                break;
            case "SY":
                $timezone = "Asia/Damascus";
                break;
            case "TC":
                $timezone = "America/Grand_Turk";
                break;
            case "TF":
                $timezone = "Indian/Kerguelen";
                break;
            case "TK":
                $timezone = "Pacific/Fakaofo";
                break;
            case "TT":
                $timezone = "America/Port_of_Spain";
                break;
            case "VG":
                $timezone = "America/Tortola";
                break;
            case "VI":
                $timezone = "America/St_Thomas";
                break;
            case "VU":
                $timezone = "Pacific/Efate";
                break;
            case "RS":
                $timezone = "Europe/Belgrade";
                break;
            case "ME":
                $timezone = "Europe/Podgorica";
                break;
            case "AX":
                $timezone = "Europe/Mariehamn";
                break;
            case "GG":
                $timezone = "Europe/Guernsey";
                break;
            case "IM":
                $timezone = "Europe/Isle_of_Man";
                break;
            case "JE":
                $timezone = "Europe/Jersey";
                break;
            case "BL":
                $timezone = "America/St_Barthelemy";
                break;
            case "MF":
                $timezone = "America/Marigot";
                break;
            case "AR":
                switch ($region) {
                    case "01":
                        $timezone = "America/Argentina/Buenos_Aires";
                        break;
                    case "02":
                        $timezone = "America/Argentina/Catamarca";
                        break;
                    case "03":
                        $timezone = "America/Argentina/Tucuman";
                        break;
                    case "04":
                        $timezone = "America/Argentina/Rio_Gallegos";
                        break;
                    case "05":
                        $timezone = "America/Argentina/Cordoba";
                        break;
                    case "06":
                        $timezone = "America/Argentina/Tucuman";
                        break;
                    case "07":
                        $timezone = "America/Argentina/Buenos_Aires";
                        break;
                    case "08":
                        $timezone = "America/Argentina/Buenos_Aires";
                        break;
                    case "09":
                        $timezone = "America/Argentina/Tucuman";
                        break;
                    case "10":
                        $timezone = "America/Argentina/Jujuy";
                        break;
                    case "11":
                        $timezone = "America/Argentina/San_Luis";
                        break;
                    case "12":
                        $timezone = "America/Argentina/La_Rioja";
                        break;
                    case "13":
                        $timezone = "America/Argentina/Mendoza";
                        break;
                    case "14":
                        $timezone = "America/Argentina/Buenos_Aires";
                        break;
                    case "15":
                        $timezone = "America/Argentina/San_Luis";
                        break;
                    case "16":
                        $timezone = "America/Argentina/Buenos_Aires";
                        break;
                    case "17":
                        $timezone = "America/Argentina/Salta";
                        break;
                    case "18":
                        $timezone = "America/Argentina/San_Juan";
                        break;
                    case "19":
                        $timezone = "America/Argentina/San_Luis";
                        break;
                    case "20":
                        $timezone = "America/Argentina/Rio_Gallegos";
                        break;
                    case "21":
                        $timezone = "America/Argentina/Buenos_Aires";
                        break;
                    case "22":
                        $timezone = "America/Argentina/Catamarca";
                        break;
                    case "23":
                        $timezone = "America/Argentina/Ushuaia";
                        break;
                    case "24":
                        $timezone = "America/Argentina/Tucuman";
                        break;
                }
                break;
            case "BR":
                switch ($region) {
                    case "01":
                        $timezone = "America/Rio_Branco";
                        break;
                    case "02":
                        $timezone = "America/Maceio";
                        break;
                    case "03":
                        $timezone = "America/Sao_Paulo";
                        break;
                    case "04":
                        $timezone = "America/Manaus";
                        break;
                    case "05":
                        $timezone = "America/Bahia";
                        break;
                    case "06":
                        $timezone = "America/Fortaleza";
                        break;
                    case "07":
                        $timezone = "America/Sao_Paulo";
                        break;
                    case "08":
                        $timezone = "America/Sao_Paulo";
                        break;
                    case "11":
                        $timezone = "America/Campo_Grande";
                        break;
                    case "13":
                        $timezone = "America/Belem";
                        break;
                    case "14":
                        $timezone = "America/Cuiaba";
                        break;
                    case "15":
                        $timezone = "America/Sao_Paulo";
                        break;
                    case "16":
                        $timezone = "America/Belem";
                        break;
                    case "17":
                        $timezone = "America/Recife";
                        break;
                    case "18":
                        $timezone = "America/Sao_Paulo";
                        break;
                    case "20":
                        $timezone = "America/Fortaleza";
                        break;
                    case "21":
                        $timezone = "America/Sao_Paulo";
                        break;
                    case "22":
                        $timezone = "America/Recife";
                        break;
                    case "23":
                        $timezone = "America/Sao_Paulo";
                        break;
                    case "24":
                        $timezone = "America/Porto_Velho";
                        break;
                    case "25":
                        $timezone = "America/Boa_Vista";
                        break;
                    case "26":
                        $timezone = "America/Sao_Paulo";
                        break;
                    case "27":
                        $timezone = "America/Sao_Paulo";
                        break;
                    case "28":
                        $timezone = "America/Maceio";
                        break;
                    case "29":
                        $timezone = "America/Sao_Paulo";
                        break;
                    case "30":
                        $timezone = "America/Recife";
                        break;
                    case "31":
                        $timezone = "America/Araguaina";
                        break;
                }
                break;
            case "CD":
                switch ($region) {
                    case "02":
                        $timezone = "Africa/Kinshasa";
                        break;
                    case "05":
                        $timezone = "Africa/Lubumbashi";
                        break;
                    case "06":
                        $timezone = "Africa/Kinshasa";
                        break;
                    case "08":
                        $timezone = "Africa/Kinshasa";
                        break;
                    case "10":
                        $timezone = "Africa/Lubumbashi";
                        break;
                    case "11":
                        $timezone = "Africa/Lubumbashi";
                        break;
                    case "12":
                        $timezone = "Africa/Lubumbashi";
                        break;
                }
                break;
            case "CN":
                switch ($region) {
                    case "01":
                        $timezone = "Asia/Shanghai";
                        break;
                    case "02":
                        $timezone = "Asia/Shanghai";
                        break;
                    case "03":
                        $timezone = "Asia/Shanghai";
                        break;
                    case "04":
                        $timezone = "Asia/Shanghai";
                        break;
                    case "05":
                        $timezone = "Asia/Harbin";
                        break;
                    case "06":
                        $timezone = "Asia/Chongqing";
                        break;
                    case "07":
                        $timezone = "Asia/Shanghai";
                        break;
                    case "08":
                        $timezone = "Asia/Harbin";
                        break;
                    case "09":
                        $timezone = "Asia/Shanghai";
                        break;
                    case "10":
                        $timezone = "Asia/Shanghai";
                        break;
                    case "11":
                        $timezone = "Asia/Chongqing";
                        break;
                    case "12":
                        $timezone = "Asia/Shanghai";
                        break;
                    case "13":
                        $timezone = "Asia/Urumqi";
                        break;
                    case "14":
                        $timezone = "Asia/Chongqing";
                        break;
                    case "15":
                        $timezone = "Asia/Chongqing";
                        break;
                    case "16":
                        $timezone = "Asia/Chongqing";
                        break;
                    case "18":
                        $timezone = "Asia/Chongqing";
                        break;
                    case "19":
                        $timezone = "Asia/Harbin";
                        break;
                    case "20":
                        $timezone = "Asia/Harbin";
                        break;
                    case "21":
                        $timezone = "Asia/Chongqing";
                        break;
                    case "22":
                        $timezone = "Asia/Harbin";
                        break;
                    case "23":
                        $timezone = "Asia/Shanghai";
                        break;
                    case "24":
                        $timezone = "Asia/Chongqing";
                        break;
                    case "25":
                        $timezone = "Asia/Shanghai";
                        break;
                    case "26":
                        $timezone = "Asia/Chongqing";
                        break;
                    case "28":
                        $timezone = "Asia/Shanghai";
                        break;
                    case "29":
                        $timezone = "Asia/Chongqing";
                        break;
                    case "30":
                        $timezone = "Asia/Chongqing";
                        break;
                    case "31":
                        $timezone = "Asia/Chongqing";
                        break;
                    case "32":
                        $timezone = "Asia/Chongqing";
                        break;
                    case "33":
                        $timezone = "Asia/Chongqing";
                        break;
                }
                break;
            case "EC":
                switch ($region) {
                    case "01":
                        $timezone = "Pacific/Galapagos";
                        break;
                    case "02":
                        $timezone = "America/Guayaquil";
                        break;
                    case "03":
                        $timezone = "America/Guayaquil";
                        break;
                    case "04":
                        $timezone = "America/Guayaquil";
                        break;
                    case "05":
                        $timezone = "America/Guayaquil";
                        break;
                    case "06":
                        $timezone = "America/Guayaquil";
                        break;
                    case "07":
                        $timezone = "America/Guayaquil";
                        break;
                    case "08":
                        $timezone = "America/Guayaquil";
                        break;
                    case "09":
                        $timezone = "America/Guayaquil";
                        break;
                    case "10":
                        $timezone = "America/Guayaquil";
                        break;
                    case "11":
                        $timezone = "America/Guayaquil";
                        break;
                    case "12":
                        $timezone = "America/Guayaquil";
                        break;
                    case "13":
                        $timezone = "America/Guayaquil";
                        break;
                    case "14":
                        $timezone = "America/Guayaquil";
                        break;
                    case "15":
                        $timezone = "America/Guayaquil";
                        break;
                    case "17":
                        $timezone = "America/Guayaquil";
                        break;
                    case "18":
                        $timezone = "America/Guayaquil";
                        break;
                    case "19":
                        $timezone = "America/Guayaquil";
                        break;
                    case "20":
                        $timezone = "America/Guayaquil";
                        break;
                    case "22":
                        $timezone = "America/Guayaquil";
                        break;
                }
                break;
            case "ES":
                switch ($region) {
                    case "07":
                        $timezone = "Europe/Madrid";
                        break;
                    case "27":
                        $timezone = "Europe/Madrid";
                        break;
                    case "29":
                        $timezone = "Europe/Madrid";
                        break;
                    case "31":
                        $timezone = "Europe/Madrid";
                        break;
                    case "32":
                        $timezone = "Europe/Madrid";
                        break;
                    case "34":
                        $timezone = "Europe/Madrid";
                        break;
                    case "39":
                        $timezone = "Europe/Madrid";
                        break;
                    case "51":
                        $timezone = "Africa/Ceuta";
                        break;
                    case "52":
                        $timezone = "Europe/Madrid";
                        break;
                    case "53":
                        $timezone = "Atlantic/Canary";
                        break;
                    case "54":
                        $timezone = "Europe/Madrid";
                        break;
                    case "55":
                        $timezone = "Europe/Madrid";
                        break;
                    case "56":
                        $timezone = "Europe/Madrid";
                        break;
                    case "57":
                        $timezone = "Europe/Madrid";
                        break;
                    case "58":
                        $timezone = "Europe/Madrid";
                        break;
                    case "59":
                        $timezone = "Europe/Madrid";
                        break;
                    case "60":
                        $timezone = "Europe/Madrid";
                        break;
                }
                break;
            case "GL":
                switch ($region) {
                    case "01":
                        $timezone = "America/Thule";
                        break;
                    case "02":
                        $timezone = "America/Godthab";
                        break;
                    case "03":
                        $timezone = "America/Godthab";
                        break;
                }
                break;
            case "ID":
                switch ($region) {
                    case "01":
                        $timezone = "Asia/Pontianak";
                        break;
                    case "02":
                        $timezone = "Asia/Makassar";
                        break;
                    case "03":
                        $timezone = "Asia/Jakarta";
                        break;
                    case "04":
                        $timezone = "Asia/Jakarta";
                        break;
                    case "05":
                        $timezone = "Asia/Jakarta";
                        break;
                    case "06":
                        $timezone = "Asia/Jakarta";
                        break;
                    case "07":
                        $timezone = "Asia/Jakarta";
                        break;
                    case "08":
                        $timezone = "Asia/Jakarta";
                        break;
                    case "09":
                        $timezone = "Asia/Jayapura";
                        break;
                    case "10":
                        $timezone = "Asia/Jakarta";
                        break;
                    case "11":
                        $timezone = "Asia/Pontianak";
                        break;
                    case "12":
                        $timezone = "Asia/Makassar";
                        break;
                    case "13":
                        $timezone = "Asia/Makassar";
                        break;
                    case "14":
                        $timezone = "Asia/Makassar";
                        break;
                    case "15":
                        $timezone = "Asia/Jakarta";
                        break;
                    case "16":
                        $timezone = "Asia/Makassar";
                        break;
                    case "17":
                        $timezone = "Asia/Makassar";
                        break;
                    case "18":
                        $timezone = "Asia/Makassar";
                        break;
                    case "19":
                        $timezone = "Asia/Pontianak";
                        break;
                    case "20":
                        $timezone = "Asia/Makassar";
                        break;
                    case "21":
                        $timezone = "Asia/Makassar";
                        break;
                    case "22":
                        $timezone = "Asia/Makassar";
                        break;
                    case "23":
                        $timezone = "Asia/Makassar";
                        break;
                    case "24":
                        $timezone = "Asia/Jakarta";
                        break;
                    case "25":
                        $timezone = "Asia/Pontianak";
                        break;
                    case "26":
                        $timezone = "Asia/Pontianak";
                        break;
                    case "30":
                        $timezone = "Asia/Jakarta";
                        break;
                    case "31":
                        $timezone = "Asia/Makassar";
                        break;
                    case "33":
                        $timezone = "Asia/Jakarta";
                        break;
                }
                break;
            case "KZ":
                switch ($region) {
                    case "01":
                        $timezone = "Asia/Almaty";
                        break;
                    case "02":
                        $timezone = "Asia/Almaty";
                        break;
                    case "03":
                        $timezone = "Asia/Qyzylorda";
                        break;
                    case "04":
                        $timezone = "Asia/Aqtobe";
                        break;
                    case "05":
                        $timezone = "Asia/Qyzylorda";
                        break;
                    case "06":
                        $timezone = "Asia/Aqtau";
                        break;
                    case "07":
                        $timezone = "Asia/Oral";
                        break;
                    case "08":
                        $timezone = "Asia/Qyzylorda";
                        break;
                    case "09":
                        $timezone = "Asia/Aqtau";
                        break;
                    case "10":
                        $timezone = "Asia/Qyzylorda";
                        break;
                    case "11":
                        $timezone = "Asia/Almaty";
                        break;
                    case "12":
                        $timezone = "Asia/Qyzylorda";
                        break;
                    case "13":
                        $timezone = "Asia/Aqtobe";
                        break;
                    case "14":
                        $timezone = "Asia/Qyzylorda";
                        break;
                    case "15":
                        $timezone = "Asia/Almaty";
                        break;
                    case "16":
                        $timezone = "Asia/Aqtobe";
                        break;
                    case "17":
                        $timezone = "Asia/Almaty";
                        break;
                }
                break;
            case "MX":
                switch ($region) {
                    case "01":
                        $timezone = "America/Mexico_City";
                        break;
                    case "02":
                        $timezone = "America/Tijuana";
                        break;
                    case "03":
                        $timezone = "America/Hermosillo";
                        break;
                    case "04":
                        $timezone = "America/Merida";
                        break;
                    case "05":
                        $timezone = "America/Mexico_City";
                        break;
                    case "06":
                        $timezone = "America/Chihuahua";
                        break;
                    case "07":
                        $timezone = "America/Monterrey";
                        break;
                    case "08":
                        $timezone = "America/Mexico_City";
                        break;
                    case "09":
                        $timezone = "America/Mexico_City";
                        break;
                    case "10":
                        $timezone = "America/Mazatlan";
                        break;
                    case "11":
                        $timezone = "America/Mexico_City";
                        break;
                    case "12":
                        $timezone = "America/Mexico_City";
                        break;
                    case "13":
                        $timezone = "America/Mexico_City";
                        break;
                    case "14":
                        $timezone = "America/Mazatlan";
                        break;
                    case "15":
                        $timezone = "America/Chihuahua";
                        break;
                    case "16":
                        $timezone = "America/Mexico_City";
                        break;
                    case "17":
                        $timezone = "America/Mexico_City";
                        break;
                    case "18":
                        $timezone = "America/Mazatlan";
                        break;
                    case "19":
                        $timezone = "America/Monterrey";
                        break;
                    case "20":
                        $timezone = "America/Mexico_City";
                        break;
                    case "21":
                        $timezone = "America/Mexico_City";
                        break;
                    case "22":
                        $timezone = "America/Mexico_City";
                        break;
                    case "23":
                        $timezone = "America/Cancun";
                        break;
                    case "24":
                        $timezone = "America/Mexico_City";
                        break;
                    case "25":
                        $timezone = "America/Mazatlan";
                        break;
                    case "26":
                        $timezone = "America/Hermosillo";
                        break;
                    case "27":
                        $timezone = "America/Merida";
                        break;
                    case "28":
                        $timezone = "America/Monterrey";
                        break;
                    case "29":
                        $timezone = "America/Mexico_City";
                        break;
                    case "30":
                        $timezone = "America/Mexico_City";
                        break;
                    case "31":
                        $timezone = "America/Merida";
                        break;
                    case "32":
                        $timezone = "America/Monterrey";
                        break;
                }
                break;
            case "MY":
                switch ($region) {
                    case "01":
                        $timezone = "Asia/Kuala_Lumpur";
                        break;
                    case "02":
                        $timezone = "Asia/Kuala_Lumpur";
                        break;
                    case "03":
                        $timezone = "Asia/Kuala_Lumpur";
                        break;
                    case "04":
                        $timezone = "Asia/Kuala_Lumpur";
                        break;
                    case "05":
                        $timezone = "Asia/Kuala_Lumpur";
                        break;
                    case "06":
                        $timezone = "Asia/Kuala_Lumpur";
                        break;
                    case "07":
                        $timezone = "Asia/Kuala_Lumpur";
                        break;
                    case "08":
                        $timezone = "Asia/Kuala_Lumpur";
                        break;
                    case "09":
                        $timezone = "Asia/Kuala_Lumpur";
                        break;
                    case "11":
                        $timezone = "Asia/Kuching";
                        break;
                    case "12":
                        $timezone = "Asia/Kuala_Lumpur";
                        break;
                    case "13":
                        $timezone = "Asia/Kuala_Lumpur";
                        break;
                    case "14":
                        $timezone = "Asia/Kuala_Lumpur";
                        break;
                    case "15":
                        $timezone = "Asia/Kuching";
                        break;
                    case "16":
                        $timezone = "Asia/Kuching";
                        break;
                }
                break;
            case "NZ":
                switch ($region) {
                    case "85":
                        $timezone = "Pacific/Auckland";
                        break;
                    case "E7":
                        $timezone = "Pacific/Auckland";
                        break;
                    case "E8":
                        $timezone = "Pacific/Auckland";
                        break;
                    case "E9":
                        $timezone = "Pacific/Auckland";
                        break;
                    case "F1":
                        $timezone = "Pacific/Auckland";
                        break;
                    case "F2":
                        $timezone = "Pacific/Auckland";
                        break;
                    case "F3":
                        $timezone = "Pacific/Auckland";
                        break;
                    case "F4":
                        $timezone = "Pacific/Auckland";
                        break;
                    case "F5":
                        $timezone = "Pacific/Auckland";
                        break;
                    case "F7":
                        $timezone = "Pacific/Chatham";
                        break;
                    case "F8":
                        $timezone = "Pacific/Auckland";
                        break;
                    case "F9":
                        $timezone = "Pacific/Auckland";
                        break;
                    case "G1":
                        $timezone = "Pacific/Auckland";
                        break;
                    case "G2":
                        $timezone = "Pacific/Auckland";
                        break;
                    case "G3":
                        $timezone = "Pacific/Auckland";
                        break;
                }
                break;
            case "PT":
                switch ($region) {
                    case "02":
                        $timezone = "Europe/Lisbon";
                        break;
                    case "03":
                        $timezone = "Europe/Lisbon";
                        break;
                    case "04":
                        $timezone = "Europe/Lisbon";
                        break;
                    case "05":
                        $timezone = "Europe/Lisbon";
                        break;
                    case "06":
                        $timezone = "Europe/Lisbon";
                        break;
                    case "07":
                        $timezone = "Europe/Lisbon";
                        break;
                    case "08":
                        $timezone = "Europe/Lisbon";
                        break;
                    case "09":
                        $timezone = "Europe/Lisbon";
                        break;
                    case "10":
                        $timezone = "Atlantic/Madeira";
                        break;
                    case "11":
                        $timezone = "Europe/Lisbon";
                        break;
                    case "13":
                        $timezone = "Europe/Lisbon";
                        break;
                    case "14":
                        $timezone = "Europe/Lisbon";
                        break;
                    case "16":
                        $timezone = "Europe/Lisbon";
                        break;
                    case "17":
                        $timezone = "Europe/Lisbon";
                        break;
                    case "18":
                        $timezone = "Europe/Lisbon";
                        break;
                    case "19":
                        $timezone = "Europe/Lisbon";
                        break;
                    case "20":
                        $timezone = "Europe/Lisbon";
                        break;
                    case "21":
                        $timezone = "Europe/Lisbon";
                        break;
                    case "22":
                        $timezone = "Europe/Lisbon";
                        break;
                }
                break;
            case "RU":
                switch ($region) {
                    case "01":
                        $timezone = "Europe/Volgograd";
                        break;
                    case "02":
                        $timezone = "Asia/Irkutsk";
                        break;
                    case "03":
                        $timezone = "Asia/Novokuznetsk";
                        break;
                    case "04":
                        $timezone = "Asia/Novosibirsk";
                        break;
                    case "05":
                        $timezone = "Asia/Vladivostok";
                        break;
                    case "06":
                        $timezone = "Europe/Moscow";
                        break;
                    case "07":
                        $timezone = "Europe/Volgograd";
                        break;
                    case "08":
                        $timezone = "Europe/Samara";
                        break;
                    case "09":
                        $timezone = "Europe/Moscow";
                        break;
                    case "10":
                        $timezone = "Europe/Moscow";
                        break;
                    case "11":
                        $timezone = "Asia/Irkutsk";
                        break;
                    case "13":
                        $timezone = "Asia/Yekaterinburg";
                        break;
                    case "14":
                        $timezone = "Asia/Irkutsk";
                        break;
                    case "15":
                        $timezone = "Asia/Anadyr";
                        break;
                    case "16":
                        $timezone = "Europe/Samara";
                        break;
                    case "17":
                        $timezone = "Europe/Volgograd";
                        break;
                    case "18":
                        $timezone = "Asia/Krasnoyarsk";
                        break;
                    case "20":
                        $timezone = "Asia/Irkutsk";
                        break;
                    case "21":
                        $timezone = "Europe/Moscow";
                        break;
                    case "22":
                        $timezone = "Europe/Volgograd";
                        break;
                    case "23":
                        $timezone = "Europe/Kaliningrad";
                        break;
                    case "24":
                        $timezone = "Europe/Volgograd";
                        break;
                    case "25":
                        $timezone = "Europe/Moscow";
                        break;
                    case "26":
                        $timezone = "Asia/Kamchatka";
                        break;
                    case "27":
                        $timezone = "Europe/Volgograd";
                        break;
                    case "28":
                        $timezone = "Europe/Moscow";
                        break;
                    case "29":
                        $timezone = "Asia/Novokuznetsk";
                        break;
                    case "30":
                        $timezone = "Asia/Vladivostok";
                        break;
                    case "31":
                        $timezone = "Asia/Krasnoyarsk";
                        break;
                    case "32":
                        $timezone = "Asia/Omsk";
                        break;
                    case "33":
                        $timezone = "Asia/Yekaterinburg";
                        break;
                    case "34":
                        $timezone = "Asia/Yekaterinburg";
                        break;
                    case "35":
                        $timezone = "Asia/Yekaterinburg";
                        break;
                    case "36":
                        $timezone = "Asia/Anadyr";
                        break;
                    case "37":
                        $timezone = "Europe/Moscow";
                        break;
                    case "38":
                        $timezone = "Europe/Volgograd";
                        break;
                    case "39":
                        $timezone = "Asia/Krasnoyarsk";
                        break;
                    case "40":
                        $timezone = "Asia/Yekaterinburg";
                        break;
                    case "41":
                        $timezone = "Europe/Moscow";
                        break;
                    case "42":
                        $timezone = "Europe/Moscow";
                        break;
                    case "43":
                        $timezone = "Europe/Moscow";
                        break;
                    case "44":
                        $timezone = "Asia/Magadan";
                        break;
                    case "45":
                        $timezone = "Europe/Samara";
                        break;
                    case "46":
                        $timezone = "Europe/Samara";
                        break;
                    case "47":
                        $timezone = "Europe/Moscow";
                        break;
                    case "48":
                        $timezone = "Europe/Moscow";
                        break;
                    case "49":
                        $timezone = "Europe/Moscow";
                        break;
                    case "50":
                        $timezone = "Asia/Yekaterinburg";
                        break;
                    case "51":
                        $timezone = "Europe/Moscow";
                        break;
                    case "52":
                        $timezone = "Europe/Moscow";
                        break;
                    case "53":
                        $timezone = "Asia/Novosibirsk";
                        break;
                    case "54":
                        $timezone = "Asia/Omsk";
                        break;
                    case "55":
                        $timezone = "Europe/Samara";
                        break;
                    case "56":
                        $timezone = "Europe/Moscow";
                        break;
                    case "57":
                        $timezone = "Europe/Samara";
                        break;
                    case "58":
                        $timezone = "Asia/Yekaterinburg";
                        break;
                    case "59":
                        $timezone = "Asia/Vladivostok";
                        break;
                    case "60":
                        $timezone = "Europe/Kaliningrad";
                        break;
                    case "61":
                        $timezone = "Europe/Volgograd";
                        break;
                    case "62":
                        $timezone = "Europe/Moscow";
                        break;
                    case "63":
                        $timezone = "Asia/Yakutsk";
                        break;
                    case "64":
                        $timezone = "Asia/Sakhalin";
                        break;
                    case "65":
                        $timezone = "Europe/Samara";
                        break;
                    case "66":
                        $timezone = "Europe/Moscow";
                        break;
                    case "67":
                        $timezone = "Europe/Samara";
                        break;
                    case "68":
                        $timezone = "Europe/Volgograd";
                        break;
                    case "69":
                        $timezone = "Europe/Moscow";
                        break;
                    case "70":
                        $timezone = "Europe/Volgograd";
                        break;
                    case "71":
                        $timezone = "Asia/Yekaterinburg";
                        break;
                    case "72":
                        $timezone = "Europe/Moscow";
                        break;
                    case "73":
                        $timezone = "Europe/Samara";
                        break;
                    case "74":
                        $timezone = "Asia/Krasnoyarsk";
                        break;
                    case "75":
                        $timezone = "Asia/Novosibirsk";
                        break;
                    case "76":
                        $timezone = "Europe/Moscow";
                        break;
                    case "77":
                        $timezone = "Europe/Moscow";
                        break;
                    case "78":
                        $timezone = "Asia/Yekaterinburg";
                        break;
                    case "79":
                        $timezone = "Asia/Irkutsk";
                        break;
                    case "80":
                        $timezone = "Asia/Yekaterinburg";
                        break;
                    case "81":
                        $timezone = "Europe/Samara";
                        break;
                    case "82":
                        $timezone = "Asia/Irkutsk";
                        break;
                    case "83":
                        $timezone = "Europe/Moscow";
                        break;
                    case "84":
                        $timezone = "Europe/Volgograd";
                        break;
                    case "85":
                        $timezone = "Europe/Moscow";
                        break;
                    case "86":
                        $timezone = "Europe/Moscow";
                        break;
                    case "87":
                        $timezone = "Asia/Novosibirsk";
                        break;
                    case "88":
                        $timezone = "Europe/Moscow";
                        break;
                    case "89":
                        $timezone = "Asia/Vladivostok";
                        break;
                }
                break;
            case "UA":
                switch ($region) {
                    case "01":
                        $timezone = "Europe/Kiev";
                        break;
                    case "02":
                        $timezone = "Europe/Kiev";
                        break;
                    case "03":
                        $timezone = "Europe/Uzhgorod";
                        break;
                    case "04":
                        $timezone = "Europe/Zaporozhye";
                        break;
                    case "05":
                        $timezone = "Europe/Zaporozhye";
                        break;
                    case "06":
                        $timezone = "Europe/Uzhgorod";
                        break;
                    case "07":
                        $timezone = "Europe/Zaporozhye";
                        break;
                    case "08":
                        $timezone = "Europe/Simferopol";
                        break;
                    case "09":
                        $timezone = "Europe/Kiev";
                        break;
                    case "10":
                        $timezone = "Europe/Zaporozhye";
                        break;
                    case "11":
                        $timezone = "Europe/Simferopol";
                        break;
                    case "13":
                        $timezone = "Europe/Kiev";
                        break;
                    case "14":
                        $timezone = "Europe/Zaporozhye";
                        break;
                    case "15":
                        $timezone = "Europe/Uzhgorod";
                        break;
                    case "16":
                        $timezone = "Europe/Zaporozhye";
                        break;
                    case "17":
                        $timezone = "Europe/Simferopol";
                        break;
                    case "18":
                        $timezone = "Europe/Zaporozhye";
                        break;
                    case "19":
                        $timezone = "Europe/Kiev";
                        break;
                    case "20":
                        $timezone = "Europe/Simferopol";
                        break;
                    case "21":
                        $timezone = "Europe/Kiev";
                        break;
                    case "22":
                        $timezone = "Europe/Uzhgorod";
                        break;
                    case "23":
                        $timezone = "Europe/Kiev";
                        break;
                    case "24":
                        $timezone = "Europe/Uzhgorod";
                        break;
                    case "25":
                        $timezone = "Europe/Uzhgorod";
                        break;
                    case "26":
                        $timezone = "Europe/Zaporozhye";
                        break;
                    case "27":
                        $timezone = "Europe/Kiev";
                        break;
                }
                break;
            case "UZ":
                switch ($region) {
                    case "01":
                        $timezone = "Asia/Tashkent";
                        break;
                    case "02":
                        $timezone = "Asia/Samarkand";
                        break;
                    case "03":
                        $timezone = "Asia/Tashkent";
                        break;
                    case "06":
                        $timezone = "Asia/Tashkent";
                        break;
                    case "07":
                        $timezone = "Asia/Samarkand";
                        break;
                    case "08":
                        $timezone = "Asia/Samarkand";
                        break;
                    case "09":
                        $timezone = "Asia/Samarkand";
                        break;
                    case "10":
                        $timezone = "Asia/Samarkand";
                        break;
                    case "12":
                        $timezone = "Asia/Samarkand";
                        break;
                    case "13":
                        $timezone = "Asia/Tashkent";
                        break;
                    case "14":
                        $timezone = "Asia/Tashkent";
                        break;
                }
                break;
            case "TL":
                $timezone = "Asia/Dili";
                break;
            case "PF":
                $timezone = "Pacific/Marquesas";
                break;
            case "SX":
                $timezone = "America/Curacao";
                break;
            case "BQ":
                $timezone = "America/Curacao";
                break;
            case "CW":
                $timezone = "America/Curacao";
                break;
        }
        return $timezone;
    }

    public static function getRegion($countryCode)
    {
        $GEOIP_REGION_NAME = array(
            'AD' => array(
                '02' => 'Canillo',
                '03' => 'Encamp',
                '04' => 'La Massana',
                '05' => 'Ordino',
                '06' => 'Sant Julia de Loria',
                '07' => 'Andorra la Vella',
                '08' => 'Escaldes-Engordany'
            ),
            'AE' => array(
                '01' => 'Abu Dhabi',
                '02' => 'Ajman',
                '03' => 'Dubai',
                '04' => 'Fujairah',
                '05' => 'Ras Al Khaimah',
                '06' => 'Sharjah',
                '07' => 'Umm Al Quwain'
            ),
            'AF' => array(
                '01' => 'Badakhshan',
                '02' => 'Badghis',
                '03' => 'Baghlan',
                '05' => 'Bamian',
                '06' => 'Farah',
                '07' => 'Faryab',
                '08' => 'Ghazni',
                '09' => 'Ghowr',
                10 => 'Helmand',
                11 => 'Herat',
                13 => 'Kabol',
                14 => 'Kapisa',
                17 => 'Lowgar',
                18 => 'Nangarhar',
                19 => 'Nimruz',
                23 => 'Kandahar',
                24 => 'Kondoz',
                26 => 'Takhar',
                27 => 'Vardak',
                28 => 'Zabol',
                29 => 'Paktika',
                30 => 'Balkh',
                31 => 'Jowzjan',
                32 => 'Samangan',
                33 => 'Sar-e Pol',
                34 => 'Konar',
                35 => 'Laghman',
                36 => 'Paktia',
                37 => 'Khowst',
                38 => 'Nurestan',
                39 => 'Oruzgan',
                40 => 'Parvan',
                41 => 'Daykondi',
                42 => 'Panjshir'
            ),
            'AG' => array(
                '01' => 'Barbuda',
                '03' => 'Saint George',
                '04' => 'Saint John',
                '05' => 'Saint Mary',
                '06' => 'Saint Paul',
                '07' => 'Saint Peter',
                '08' => 'Saint Philip',
                '09' => 'Redonda'
            ),
            'AL' => array(
                40 => 'Berat',
                41 => 'Diber',
                42 => 'Durres',
                43 => 'Elbasan',
                44 => 'Fier',
                45 => 'Gjirokaster',
                46 => 'Korce',
                47 => 'Kukes',
                48 => 'Lezhe',
                49 => 'Shkoder',
                50 => 'Tirane',
                51 => 'Vlore'
            ),
            'AM' => array(
                '01' => 'Aragatsotn',
                '02' => 'Ararat',
                '03' => 'Armavir',
                '04' => 'Geghark\'unik\'',
                '05' => 'Kotayk\'',
                '06' => 'Lorri',
                '07' => 'Shirak',
                '08' => 'Syunik\'',
                '09' => 'Tavush',
                10 => 'Vayots\' Dzor',
                11 => 'Yerevan'
            ),
            'AO' => array(
                '01' => 'Benguela',
                '02' => 'Bie',
                '03' => 'Cabinda',
                '04' => 'Cuando Cubango',
                '05' => 'Cuanza Norte',
                '06' => 'Cuanza Sul',
                '07' => 'Cunene',
                '08' => 'Huambo',
                '09' => 'Huila',
                12 => 'Malanje',
                13 => 'Namibe',
                14 => 'Moxico',
                15 => 'Uige',
                16 => 'Zaire',
                17 => 'Lunda Norte',
                18 => 'Lunda Sul',
                19 => 'Bengo',
                20 => 'Luanda'
            ),
            'AR' => array(
                '01' => 'Buenos Aires',
                '02' => 'Catamarca',
                '03' => 'Chaco',
                '04' => 'Chubut',
                '05' => 'Cordoba',
                '06' => 'Corrientes',
                '07' => 'Distrito Federal',
                '08' => 'Entre Rios',
                '09' => 'Formosa',
                10 => 'Jujuy',
                11 => 'La Pampa',
                12 => 'La Rioja',
                13 => 'Mendoza',
                14 => 'Misiones',
                15 => 'Neuquen',
                16 => 'Rio Negro',
                17 => 'Salta',
                18 => 'San Juan',
                19 => 'San Luis',
                20 => 'Santa Cruz',
                21 => 'Santa Fe',
                22 => 'Santiago del Estero',
                23 => 'Tierra del Fuego',
                24 => 'Tucuman'
            ),
            'AT' => array(
                '01' => 'Burgenland',
                '02' => 'Karnten',
                '03' => 'Niederosterreich',
                '04' => 'Oberosterreich',
                '05' => 'Salzburg',
                '06' => 'Steiermark',
                '07' => 'Tirol',
                '08' => 'Vorarlberg',
                '09' => 'Wien'
            ),
            'AU' => array(
                '01' => 'Australian Capital Territory',
                '02' => 'New South Wales',
                '03' => 'Northern Territory',
                '04' => 'Queensland',
                '05' => 'South Australia',
                '06' => 'Tasmania',
                '07' => 'Victoria',
                '08' => 'Western Australia'
            ),
            'AZ' => array(
                '01' => 'Abseron',
                '02' => 'Agcabadi',
                '03' => 'Agdam',
                '04' => 'Agdas',
                '05' => 'Agstafa',
                '06' => 'Agsu',
                '07' => 'Ali Bayramli',
                '08' => 'Astara',
                '09' => 'Baki',
                10 => 'Balakan',
                11 => 'Barda',
                12 => 'Beylaqan',
                13 => 'Bilasuvar',
                14 => 'Cabrayil',
                15 => 'Calilabad',
                16 => 'Daskasan',
                17 => 'Davaci',
                18 => 'Fuzuli',
                19 => 'Gadabay',
                20 => 'Ganca',
                21 => 'Goranboy',
                22 => 'Goycay',
                23 => 'Haciqabul',
                24 => 'Imisli',
                25 => 'Ismayilli',
                26 => 'Kalbacar',
                27 => 'Kurdamir',
                28 => 'Lacin',
                30 => 'Lankaran',
                31 => 'Lerik',
                32 => 'Masalli',
                33 => 'Mingacevir',
                34 => 'Naftalan',
                35 => 'Naxcivan',
                36 => 'Neftcala',
                37 => 'Oguz',
                38 => 'Qabala',
                39 => 'Qax',
                40 => 'Qazax',
                41 => 'Qobustan',
                42 => 'Quba',
                43 => 'Qubadli',
                44 => 'Qusar',
                45 => 'Saatli',
                46 => 'Sabirabad',
                48 => 'Saki',
                49 => 'Salyan',
                50 => 'Samaxi',
                51 => 'Samkir',
                52 => 'Samux',
                53 => 'Siyazan',
                54 => 'Sumqayit',
                56 => 'Susa',
                57 => 'Tartar',
                58 => 'Tovuz',
                59 => 'Ucar',
                60 => 'Xacmaz',
                61 => 'Xankandi',
                62 => 'Xanlar',
                63 => 'Xizi',
                64 => 'Xocali',
                65 => 'Xocavand',
                66 => 'Yardimli',
                68 => 'Yevlax',
                69 => 'Zangilan',
                70 => 'Zaqatala',
                71 => 'Zardab'
            ),
            'BA' => array(
                '01' => 'Federation of Bosnia and Herzegovina',
                '03' => 'Brcko District',
                '02' => 'Republika Srpska'
            ),
            'BB' => array(
                '01' => 'Christ Church',
                '02' => 'Saint Andrew',
                '03' => 'Saint George',
                '04' => 'Saint James',
                '05' => 'Saint John',
                '06' => 'Saint Joseph',
                '07' => 'Saint Lucy',
                '08' => 'Saint Michael',
                '09' => 'Saint Peter',
                10 => 'Saint Philip',
                11 => 'Saint Thomas'
            ),
            'BD' => array(
                81 => 'Dhaka',
                82 => 'Khulna',
                83 => 'Rajshahi',
                84 => 'Chittagong',
                85 => 'Barisal',
                86 => 'Sylhet',
                87 => 'Rangpur'
            ),
            'BE' => array(
                '01' => 'Antwerpen',
                '03' => 'Hainaut',
                '04' => 'Liege',
                '05' => 'Limburg',
                '06' => 'Luxembourg',
                '07' => 'Namur',
                '08' => 'Oost-Vlaanderen',
                '09' => 'West-Vlaanderen',
                10 => 'Brabant Wallon',
                11 => 'Brussels Hoofdstedelijk Gewest',
                12 => 'Vlaams-Brabant',
                13 => 'Flanders',
                14 => 'Wallonia'
            ),
            'BF' => array(
                15 => 'Bam',
                19 => 'Boulkiemde',
                20 => 'Ganzourgou',
                21 => 'Gnagna',
                28 => 'Kouritenga',
                33 => 'Oudalan',
                34 => 'Passore',
                36 => 'Sanguie',
                40 => 'Soum',
                42 => 'Tapoa',
                44 => 'Zoundweogo',
                45 => 'Bale',
                46 => 'Banwa',
                47 => 'Bazega',
                48 => 'Bougouriba',
                49 => 'Boulgou',
                50 => 'Gourma',
                51 => 'Houet',
                52 => 'Ioba',
                53 => 'Kadiogo',
                54 => 'Kenedougou',
                55 => 'Komoe',
                56 => 'Komondjari',
                57 => 'Kompienga',
                58 => 'Kossi',
                59 => 'Koulpelogo',
                60 => 'Kourweogo',
                61 => 'Leraba',
                62 => 'Loroum',
                63 => 'Mouhoun',
                64 => 'Namentenga',
                65 => 'Naouri',
                66 => 'Nayala',
                67 => 'Noumbiel',
                68 => 'Oubritenga',
                69 => 'Poni',
                70 => 'Sanmatenga',
                71 => 'Seno',
                72 => 'Sissili',
                73 => 'Sourou',
                74 => 'Tuy',
                75 => 'Yagha',
                76 => 'Yatenga',
                77 => 'Ziro',
                78 => 'Zondoma'
            ),
            'BG' => array(
                33 => 'Mikhaylovgrad',
                38 => 'Blagoevgrad',
                39 => 'Burgas',
                40 => 'Dobrich',
                41 => 'Gabrovo',
                42 => 'Grad Sofiya',
                43 => 'Khaskovo',
                44 => 'Kurdzhali',
                45 => 'Kyustendil',
                46 => 'Lovech',
                47 => 'Montana',
                48 => 'Pazardzhik',
                49 => 'Pernik',
                50 => 'Pleven',
                51 => 'Plovdiv',
                52 => 'Razgrad',
                53 => 'Ruse',
                54 => 'Shumen',
                55 => 'Silistra',
                56 => 'Sliven',
                57 => 'Smolyan',
                58 => 'Sofiya',
                59 => 'Stara Zagora',
                60 => 'Turgovishte',
                61 => 'Varna',
                62 => 'Veliko Turnovo',
                63 => 'Vidin',
                64 => 'Vratsa',
                65 => 'Yambol'
            ),
            'BH' => array(
                '01' => 'Al Hadd',
                '02' => 'Al Manamah',
                '05' => 'Jidd Hafs',
                '06' => 'Sitrah',
                '08' => 'Al Mintaqah al Gharbiyah',
                '09' => 'Mintaqat Juzur Hawar',
                10 => 'Al Mintaqah ash Shamaliyah',
                11 => 'Al Mintaqah al Wusta',
                12 => 'Madinat',
                13 => 'Ar Rifa',
                14 => 'Madinat Hamad',
                15 => 'Al Muharraq',
                16 => 'Al Asimah',
                17 => 'Al Janubiyah',
                18 => 'Ash Shamaliyah',
                19 => 'Al Wusta'
            ),
            'BI' => array(
                '02' => 'Bujumbura',
                '09' => 'Bubanza',
                10 => 'Bururi',
                11 => 'Cankuzo',
                12 => 'Cibitoke',
                13 => 'Gitega',
                14 => 'Karuzi',
                15 => 'Kayanza',
                16 => 'Kirundo',
                17 => 'Makamba',
                18 => 'Muyinga',
                19 => 'Ngozi',
                20 => 'Rutana',
                21 => 'Ruyigi',
                22 => 'Muramvya',
                23 => 'Mwaro'
            ),
            'BJ' => array(
                '07' => 'Alibori',
                '08' => 'Atakora',
                '09' => 'Atlanyique',
                10 => 'Borgou',
                11 => 'Collines',
                12 => 'Kouffo',
                13 => 'Donga',
                14 => 'Littoral',
                15 => 'Mono',
                16 => 'Oueme',
                17 => 'Plateau',
                18 => 'Zou'
            ),
            'BM' => array(
                '01' => 'Devonshire',
                '03' => 'Hamilton',
                '04' => 'Paget',
                '05' => 'Pembroke',
                '06' => 'Saint George',
                '07' => 'Saint George\'s',
                '08' => 'Sandys',
                '09' => 'Smiths',
                10 => 'Southampton',
                11 => 'Warwick'
            ),
            'BN' => array(
                '07' => 'Alibori',
                '08' => 'Belait',
                '09' => 'Brunei and Muara',
                10 => 'Temburong',
                11 => 'Collines',
                12 => 'Kouffo',
                13 => 'Donga',
                14 => 'Littoral',
                15 => 'Tutong',
                16 => 'Oueme',
                17 => 'Plateau',
                18 => 'Zou'
            ),
            'BO' => array(
                '01' => 'Chuquisaca',
                '02' => 'Cochabamba',
                '03' => 'El Beni',
                '04' => 'La Paz',
                '05' => 'Oruro',
                '06' => 'Pando',
                '07' => 'Potosi',
                '08' => 'Santa Cruz',
                '09' => 'Tarija'
            ),
            'BR' => array(
                '01' => 'Acre',
                '02' => 'Alagoas',
                '03' => 'Amapa',
                '04' => 'Amazonas',
                '05' => 'Bahia',
                '06' => 'Ceara',
                '07' => 'Distrito Federal',
                '08' => 'Espirito Santo',
                11 => 'Mato Grosso do Sul',
                13 => 'Maranhao',
                14 => 'Mato Grosso',
                15 => 'Minas Gerais',
                16 => 'Para',
                17 => 'Paraiba',
                18 => 'Parana',
                20 => 'Piaui',
                21 => 'Rio de Janeiro',
                22 => 'Rio Grande do Norte',
                23 => 'Rio Grande do Sul',
                24 => 'Rondonia',
                25 => 'Roraima',
                26 => 'Santa Catarina',
                27 => 'Sao Paulo',
                28 => 'Sergipe',
                29 => 'Goias',
                30 => 'Pernambuco',
                31 => 'Tocantins'
            ),
            'BS' => array(
                '05' => 'Bimini',
                '06' => 'Cat Island',
                10 => 'Exuma',
                13 => 'Inagua',
                15 => 'Long Island',
                16 => 'Mayaguana',
                18 => 'Ragged Island',
                22 => 'Harbour Island',
                23 => 'New Providence',
                24 => 'Acklins and Crooked Islands',
                25 => 'Freeport',
                26 => 'Fresh Creek',
                27 => 'Governor\'s Harbour',
                28 => 'Green Turtle Cay',
                29 => 'High Rock',
                30 => 'Kemps Bay',
                31 => 'Marsh Harbour',
                32 => 'Nichollstown and Berry Islands',
                33 => 'Rock Sound',
                34 => 'Sandy Point',
                35 => 'San Salvador and Rum Cay'
            ),
            'BT' => array(
                '05' => 'Bumthang',
                '06' => 'Chhukha',
                '07' => 'Chirang',
                '08' => 'Daga',
                '09' => 'Geylegphug',
                10 => 'Ha',
                11 => 'Lhuntshi',
                12 => 'Mongar',
                13 => 'Paro',
                14 => 'Pemagatsel',
                15 => 'Punakha',
                16 => 'Samchi',
                17 => 'Samdrup',
                18 => 'Shemgang',
                19 => 'Tashigang',
                20 => 'Thimphu',
                21 => 'Tongsa',
                22 => 'Wangdi Phodrang'
            ),
            'BW' => array(
                '01' => 'Central',
                '03' => 'Ghanzi',
                '04' => 'Kgalagadi',
                '05' => 'Kgatleng',
                '06' => 'Kweneng',
                '08' => 'North-East',
                '09' => 'South-East',
                10 => 'Southern',
                11 => 'North-West'
            ),
            'BY' => array(
                '01' => 'Brestskaya Voblasts\'',
                '02' => 'Homyel\'skaya Voblasts\'',
                '03' => 'Hrodzyenskaya Voblasts\'',
                '04' => 'Minsk',
                '05' => 'Minskaya Voblasts\'',
                '06' => 'Mahilyowskaya Voblasts\'',
                '07' => 'Vitsyebskaya Voblasts\''
            ),
            'BZ' => array(
                '01' => 'Belize',
                '02' => 'Cayo',
                '03' => 'Corozal',
                '04' => 'Orange Walk',
                '05' => 'Stann Creek',
                '06' => 'Toledo'
            ),
            'CA' => array(
                'AB' => 'Alberta',
                'BC' => 'British Columbia',
                'MB' => 'Manitoba',
                'NB' => 'New Brunswick',
                'NL' => 'Newfoundland',
                'NS' => 'Nova Scotia',
                'NT' => 'Northwest Territories',
                'NU' => 'Nunavut',
                'ON' => 'Ontario',
                'PE' => 'Prince Edward Island',
                'QC' => 'Quebec',
                'SK' => 'Saskatchewan',
                'YT' => 'Yukon Territory'
            ),
            'CD' => array(
                '01' => 'Bandundu',
                '02' => 'Equateur',
                '04' => 'Kasai-Oriental',
                '05' => 'Katanga',
                '06' => 'Kinshasa',
                '08' => 'Bas-Congo',
                '09' => 'Orientale',
                10 => 'Maniema',
                11 => 'Nord-Kivu',
                12 => 'Sud-Kivu'
            ),
            'CF' => array(
                '01' => 'Bamingui-Bangoran',
                '02' => 'Basse-Kotto',
                '03' => 'Haute-Kotto',
                '04' => 'Mambere-Kadei',
                '05' => 'Haut-Mbomou',
                '06' => 'Kemo',
                '07' => 'Lobaye',
                '08' => 'Mbomou',
                '09' => 'Nana-Mambere',
                11 => 'Ouaka',
                12 => 'Ouham',
                13 => 'Ouham-Pende',
                14 => 'Cuvette-Ouest',
                15 => 'Nana-Grebizi',
                16 => 'Sangha-Mbaere',
                17 => 'Ombella-Mpoko',
                18 => 'Bangui'
            ),
            'CG' => array(
                '01' => 'Bouenza',
                '04' => 'Kouilou',
                '05' => 'Lekoumou',
                '06' => 'Likouala',
                '07' => 'Niari',
                '08' => 'Plateaux',
                10 => 'Sangha',
                11 => 'Pool',
                12 => 'Brazzaville',
                13 => 'Cuvette',
                14 => 'Cuvette-Ouest'
            ),
            'CH' => array(
                '01' => 'Aargau',
                '02' => 'Ausser-Rhoden',
                '03' => 'Basel-Landschaft',
                '04' => 'Basel-Stadt',
                '05' => 'Bern',
                '06' => 'Fribourg',
                '07' => 'Geneve',
                '08' => 'Glarus',
                '09' => 'Graubunden',
                10 => 'Inner-Rhoden',
                11 => 'Luzern',
                12 => 'Neuchatel',
                13 => 'Nidwalden',
                14 => 'Obwalden',
                15 => 'Sankt Gallen',
                16 => 'Schaffhausen',
                17 => 'Schwyz',
                18 => 'Solothurn',
                19 => 'Thurgau',
                20 => 'Ticino',
                21 => 'Uri',
                22 => 'Valais',
                23 => 'Vaud',
                24 => 'Zug',
                25 => 'Zurich',
                26 => 'Jura'
            ),
            'CI' => array(
                74 => 'Agneby',
                75 => 'Bafing',
                76 => 'Bas-Sassandra',
                77 => 'Denguele',
                78 => 'Dix-Huit Montagnes',
                79 => 'Fromager',
                80 => 'Haut-Sassandra',
                81 => 'Lacs',
                82 => 'Lagunes',
                83 => 'Marahoue',
                84 => 'Moyen-Cavally',
                85 => 'Moyen-Comoe',
                86 => 'N\'zi-Comoe',
                87 => 'Savanes',
                88 => 'Sud-Bandama',
                89 => 'Sud-Comoe',
                90 => 'Vallee du Bandama',
                91 => 'Worodougou',
                92 => 'Zanzan'
            ),
            'CL' => array(
                '01' => 'Valparaiso',
                '02' => 'Aisen del General Carlos Ibanez del Campo',
                '03' => 'Antofagasta',
                '04' => 'Araucania',
                '05' => 'Atacama',
                '06' => 'Bio-Bio',
                '07' => 'Coquimbo',
                '08' => 'Libertador General Bernardo O\'Higgins',
                14 => 'Los Lagos',
                10 => 'Magallanes y de la Antartica Chilena',
                11 => 'Maule',
                12 => 'Region Metropolitana',
                15 => 'Tarapaca',
                16 => 'Arica y Parinacota',
                17 => 'Los Rios'
            ),
            'CM' => array(
                '04' => 'Est',
                '05' => 'Littoral',
                '07' => 'Nord-Ouest',
                '08' => 'Ouest',
                '09' => 'Sud-Ouest',
                10 => 'Adamaoua',
                11 => 'Centre',
                12 => 'Extreme-Nord',
                13 => 'Nord',
                14 => 'Sud'
            ),
            'CN' => array(
                '01' => 'Anhui',
                '02' => 'Zhejiang',
                '03' => 'Jiangxi',
                '04' => 'Jiangsu',
                '05' => 'Jilin',
                '06' => 'Qinghai',
                '07' => 'Fujian',
                '08' => 'Heilongjiang',
                '09' => 'Henan',
                10 => 'Hebei',
                11 => 'Hunan',
                12 => 'Hubei',
                13 => 'Xinjiang',
                14 => 'Xizang',
                15 => 'Gansu',
                16 => 'Guangxi',
                18 => 'Guizhou',
                19 => 'Liaoning',
                20 => 'Nei Mongol',
                21 => 'Ningxia',
                22 => 'Beijing',
                23 => 'Shanghai',
                24 => 'Shanxi',
                25 => 'Shandong',
                26 => 'Shaanxi',
                28 => 'Tianjin',
                29 => 'Yunnan',
                30 => 'Guangdong',
                31 => 'Hainan',
                32 => 'Sichuan',
                33 => 'Chongqing'
            ),
            'CO' => array(
                '01' => 'Amazonas',
                '02' => 'Antioquia',
                '03' => 'Arauca',
                '04' => 'Atlantico',
                '08' => 'Caqueta',
                '09' => 'Cauca',
                10 => 'Cesar',
                11 => 'Choco',
                12 => 'Cordoba',
                14 => 'Guaviare',
                15 => 'Guainia',
                16 => 'Huila',
                17 => 'La Guajira',
                19 => 'Meta',
                20 => 'Narino',
                21 => 'Norte de Santander',
                22 => 'Putumayo',
                23 => 'Quindio',
                24 => 'Risaralda',
                25 => 'San Andres y Providencia',
                26 => 'Santander',
                27 => 'Sucre',
                28 => 'Tolima',
                29 => 'Valle del Cauca',
                30 => 'Vaupes',
                31 => 'Vichada',
                32 => 'Casanare',
                33 => 'Cundinamarca',
                34 => 'Distrito Especial',
                35 => 'Bolivar',
                36 => 'Boyaca',
                37 => 'Caldas',
                38 => 'Magdalena'
            ),
            'CR' => array(
                '01' => 'Alajuela',
                '02' => 'Cartago',
                '03' => 'Guanacaste',
                '04' => 'Heredia',
                '06' => 'Limon',
                '07' => 'Puntarenas',
                '08' => 'San Jose'
            ),
            'CU' => array(
                '01' => 'Pinar del Rio',
                '02' => 'Ciudad de la Habana',
                '03' => 'Matanzas',
                '04' => 'Isla de la Juventud',
                '05' => 'Camaguey',
                '07' => 'Ciego de Avila',
                '08' => 'Cienfuegos',
                '09' => 'Granma',
                10 => 'Guantanamo',
                11 => 'La Habana',
                12 => 'Holguin',
                13 => 'Las Tunas',
                14 => 'Sancti Spiritus',
                15 => 'Santiago de Cuba',
                16 => 'Villa Clara'
            ),
            'CV' => array(
                '01' => 'Boa Vista',
                '02' => 'Brava',
                '04' => 'Maio',
                '05' => 'Paul',
                '07' => 'Ribeira Grande',
                '08' => 'Sal',
                10 => 'Sao Nicolau',
                11 => 'Sao Vicente',
                13 => 'Mosteiros',
                14 => 'Praia',
                15 => 'Santa Catarina',
                16 => 'Santa Cruz',
                17 => 'Sao Domingos',
                18 => 'Sao Filipe',
                19 => 'Sao Miguel',
                20 => 'Tarrafal'
            ),
            'CY' => array(
                '01' => 'Famagusta',
                '02' => 'Kyrenia',
                '03' => 'Larnaca',
                '04' => 'Nicosia',
                '05' => 'Limassol',
                '06' => 'Paphos'
            ),
            'CZ' => array(
                52 => 'Hlavni mesto Praha',
                78 => 'Jihomoravsky kraj',
                79 => 'Jihocesky kraj',
                80 => 'Vysocina',
                81 => 'Karlovarsky kraj',
                82 => 'Kralovehradecky kraj',
                83 => 'Liberecky kraj',
                84 => 'Olomoucky kraj',
                85 => 'Moravskoslezsky kraj',
                86 => 'Pardubicky kraj',
                87 => 'Plzensky kraj',
                88 => 'Stredocesky kraj',
                89 => 'Ustecky kraj',
                90 => 'Zlinsky kraj'
            ),
            'DE' => array(
                '01' => 'Baden-Wurttemberg',
                '02' => 'Bayern',
                '03' => 'Bremen',
                '04' => 'Hamburg',
                '05' => 'Hessen',
                '06' => 'Niedersachsen',
                '07' => 'Nordrhein-Westfalen',
                '08' => 'Rheinland-Pfalz',
                '09' => 'Saarland',
                10 => 'Schleswig-Holstein',
                11 => 'Brandenburg',
                12 => 'Mecklenburg-Vorpommern',
                13 => 'Sachsen',
                14 => 'Sachsen-Anhalt',
                15 => 'Thuringen',
                16 => 'Berlin'
            ),
            'DJ' => array(
                '01' => 'Ali Sabieh',
                '04' => 'Obock',
                '05' => 'Tadjoura',
                '06' => 'Dikhil',
                '07' => 'Djibouti',
                '08' => 'Arta'
            ),
            'DK' => array(
                17 => 'Hovedstaden',
                18 => 'Midtjylland',
                19 => 'Nordjylland',
                20 => 'Sjelland',
                21 => 'Syddanmark'
            ),
            'DM' => array(
                '02' => 'Saint Andrew',
                '03' => 'Saint David',
                '04' => 'Saint George',
                '05' => 'Saint John',
                '06' => 'Saint Joseph',
                '07' => 'Saint Luke',
                '08' => 'Saint Mark',
                '09' => 'Saint Patrick',
                10 => 'Saint Paul',
                11 => 'Saint Peter'
            ),
            'DO' => array(
                '01' => 'Azua',
                '02' => 'Baoruco',
                '03' => 'Barahona',
                '04' => 'Dajabon',
                34 => 'Distrito Nacional',
                '06' => 'Duarte',
                '08' => 'Espaillat',
                '09' => 'Independencia',
                10 => 'La Altagracia',
                11 => 'Elias Pina',
                12 => 'La Romana',
                14 => 'Maria Trinidad Sanchez',
                15 => 'Monte Cristi',
                16 => 'Pedernales',
                35 => 'Peravia',
                18 => 'Puerto Plata',
                19 => 'Salcedo',
                20 => 'Samana',
                21 => 'Sanchez Ramirez',
                23 => 'San Juan',
                24 => 'San Pedro De Macoris',
                25 => 'Santiago',
                26 => 'Santiago Rodriguez',
                27 => 'Valverde',
                28 => 'El Seibo',
                29 => 'Hato Mayor',
                30 => 'La Vega',
                31 => 'Monsenor Nouel',
                32 => 'Monte Plata',
                33 => 'San Cristobal',
                36 => 'San Jose de Ocoa',
                37 => 'Santo Domingo'
            ),
            'DZ' => array(
                '01' => 'Alger',
                '03' => 'Batna',
                '04' => 'Constantine',
                '06' => 'Medea',
                '07' => 'Mostaganem',
                '09' => 'Oran',
                10 => 'Saida',
                12 => 'Setif',
                13 => 'Tiaret',
                14 => 'Tizi Ouzou',
                15 => 'Tlemcen',
                18 => 'Bejaia',
                19 => 'Biskra',
                20 => 'Blida',
                21 => 'Bouira',
                22 => 'Djelfa',
                23 => 'Guelma',
                24 => 'Jijel',
                25 => 'Laghouat',
                26 => 'Mascara',
                27 => 'M\'sila',
                29 => 'Oum el Bouaghi',
                30 => 'Sidi Bel Abbes',
                31 => 'Skikda',
                33 => 'Tebessa',
                34 => 'Adrar',
                35 => 'Ain Defla',
                36 => 'Ain Temouchent',
                37 => 'Annaba',
                38 => 'Bechar',
                39 => 'Bordj Bou Arreridj',
                40 => 'Boumerdes',
                41 => 'Chlef',
                42 => 'El Bayadh',
                43 => 'El Oued',
                44 => 'El Tarf',
                45 => 'Ghardaia',
                46 => 'Illizi',
                47 => 'Khenchela',
                48 => 'Mila',
                49 => 'Naama',
                50 => 'Ouargla',
                51 => 'Relizane',
                52 => 'Souk Ahras',
                53 => 'Tamanghasset',
                54 => 'Tindouf',
                55 => 'Tipaza',
                56 => 'Tissemsilt'
            ),
            'EC' => array(
                '01' => 'Galapagos',
                '02' => 'Azuay',
                '03' => 'Bolivar',
                '04' => 'Canar',
                '05' => 'Carchi',
                '06' => 'Chimborazo',
                '07' => 'Cotopaxi',
                '08' => 'El Oro',
                '09' => 'Esmeraldas',
                10 => 'Guayas',
                11 => 'Imbabura',
                12 => 'Loja',
                13 => 'Los Rios',
                14 => 'Manabi',
                15 => 'Morona-Santiago',
                17 => 'Pastaza',
                18 => 'Pichincha',
                19 => 'Tungurahua',
                20 => 'Zamora-Chinchipe',
                22 => 'Sucumbios',
                23 => 'Napo',
                24 => 'Orellana'
            ),
            'EE' => array(
                '01' => 'Harjumaa',
                '02' => 'Hiiumaa',
                '03' => 'Ida-Virumaa',
                '04' => 'Jarvamaa',
                '05' => 'Jogevamaa',
                '06' => 'Kohtla-Jarve',
                '07' => 'Laanemaa',
                '08' => 'Laane-Virumaa',
                '09' => 'Narva',
                10 => 'Parnu',
                11 => 'Parnumaa',
                12 => 'Polvamaa',
                13 => 'Raplamaa',
                14 => 'Saaremaa',
                15 => 'Sillamae',
                16 => 'Tallinn',
                17 => 'Tartu',
                18 => 'Tartumaa',
                19 => 'Valgamaa',
                20 => 'Viljandimaa',
                21 => 'Vorumaa'
            ),
            'EG' => array(
                '01' => 'Ad Daqahliyah',
                '02' => 'Al Bahr al Ahmar',
                '03' => 'Al Buhayrah',
                '04' => 'Al Fayyum',
                '05' => 'Al Gharbiyah',
                '06' => 'Al Iskandariyah',
                '07' => 'Al Isma\'iliyah',
                '08' => 'Al Jizah',
                '09' => 'Al Minufiyah',
                10 => 'Al Minya',
                11 => 'Al Qahirah',
                12 => 'Al Qalyubiyah',
                13 => 'Al Wadi al Jadid',
                14 => 'Ash Sharqiyah',
                15 => 'As Suways',
                16 => 'Aswan',
                17 => 'Asyut',
                18 => 'Bani Suwayf',
                19 => 'Bur Sa\'id',
                20 => 'Dumyat',
                21 => 'Kafr ash Shaykh',
                22 => 'Matruh',
                23 => 'Qina',
                24 => 'Suhaj',
                26 => 'Janub Sina\'',
                27 => 'Shamal Sina\'',
                28 => 'Al Uqsur'
            ),
            'ER' => array(
                '01' => 'Anseba',
                '02' => 'Debub',
                '03' => 'Debubawi K\'eyih Bahri',
                '04' => 'Gash Barka',
                '05' => 'Ma\'akel',
                '06' => 'Semenawi K\'eyih Bahri'
            ),
            'ES' => array(
                '07' => 'Islas Baleares',
                27 => 'La Rioja',
                29 => 'Madrid',
                31 => 'Murcia',
                32 => 'Navarra',
                34 => 'Asturias',
                39 => 'Cantabria',
                51 => 'Andalucia',
                52 => 'Aragon',
                53 => 'Canarias',
                54 => 'Castilla-La Mancha',
                55 => 'Castilla y Leon',
                56 => 'Catalonia',
                57 => 'Extremadura',
                58 => 'Galicia',
                59 => 'Pais Vasco',
                60 => 'Comunidad Valenciana'
            ),
            'ET' => array(
                44 => 'Adis Abeba',
                45 => 'Afar',
                46 => 'Amara',
                47 => 'Binshangul Gumuz',
                48 => 'Dire Dawa',
                49 => 'Gambela Hizboch',
                50 => 'Hareri Hizb',
                51 => 'Oromiya',
                52 => 'Sumale',
                53 => 'Tigray',
                54 => 'YeDebub Biheroch Bihereseboch na Hizboch'
            ),
            'FI' => array(
                '01' => 'Aland',
                '06' => 'Lapland',
                '08' => 'Oulu',
                13 => 'Southern Finland',
                14 => 'Eastern Finland',
                15 => 'Western Finland'
            ),
            'FJ' => array(
                '01' => 'Central',
                '02' => 'Eastern',
                '03' => 'Northern',
                '04' => 'Rotuma',
                '05' => 'Western'
            ),
            'FM' => array(
                '01' => 'Kosrae',
                '02' => 'Pohnpei',
                '03' => 'Chuuk',
                '04' => 'Yap'
            ),
            'FR' => array(
                97 => 'Aquitaine',
                98 => 'Auvergne',
                99 => 'Basse-Normandie',
                'A1' => 'Bourgogne',
                'A2' => 'Bretagne',
                'A3' => 'Centre',
                'A4' => 'Champagne-Ardenne',
                'A5' => 'Corse',
                'A6' => 'Franche-Comte',
                'A7' => 'Haute-Normandie',
                'A8' => 'Ile-de-France',
                'A9' => 'Languedoc-Roussillon',
                'B1' => 'Limousin',
                'B2' => 'Lorraine',
                'B3' => 'Midi-Pyrenees',
                'B4' => 'Nord-Pas-de-Calais',
                'B5' => 'Pays de la Loire',
                'B6' => 'Picardie',
                'B7' => 'Poitou-Charentes',
                'B8' => 'Provence-Alpes-Cote d\'Azur',
                'B9' => 'Rhone-Alpes',
                'C1' => 'Alsace'
            ),
            'GA' => array(
                '01' => 'Estuaire',
                '02' => 'Haut-Ogooue',
                '03' => 'Moyen-Ogooue',
                '04' => 'Ngounie',
                '05' => 'Nyanga',
                '06' => 'Ogooue-Ivindo',
                '07' => 'Ogooue-Lolo',
                '08' => 'Ogooue-Maritime',
                '09' => 'Woleu-Ntem'
            ),
            'GB' => array(
                'A1' => 'Barking and Dagenham',
                'A2' => 'Barnet',
                'A3' => 'Barnsley',
                'A4' => 'Bath and North East Somerset',
                'Z5' => 'Bedfordshire',
                'A6' => 'Bexley',
                'A7' => 'Birmingham',
                'A8' => 'Blackburn with Darwen',
                'A9' => 'Blackpool',
                'B1' => 'Bolton',
                'B2' => 'Bournemouth',
                'B3' => 'Bracknell Forest',
                'B4' => 'Bradford',
                'B5' => 'Brent',
                'B6' => 'Brighton and Hove',
                'B7' => 'Bristol',
                'B8' => 'Bromley',
                'B9' => 'Buckinghamshire',
                'C1' => 'Bury',
                'C2' => 'Calderdale',
                'C3' => 'Cambridgeshire',
                'C4' => 'Camden',
                'C5' => 'Cheshire',
                'C6' => 'Cornwall',
                'C7' => 'Coventry',
                'C8' => 'Croydon',
                'C9' => 'Cumbria',
                'D1' => 'Darlington',
                'D2' => 'Derby',
                'D3' => 'Derbyshire',
                'D4' => 'Devon',
                'D5' => 'Doncaster',
                'D6' => 'Dorset',
                'D7' => 'Dudley',
                'D8' => 'Durham',
                'D9' => 'Ealing',
                'E1' => 'East Riding of Yorkshire',
                'E2' => 'East Sussex',
                'E3' => 'Enfield',
                'E4' => 'Essex',
                'E5' => 'Gateshead',
                'E6' => 'Gloucestershire',
                'E7' => 'Greenwich',
                'E8' => 'Hackney',
                'E9' => 'Halton',
                'F1' => 'Hammersmith and Fulham',
                'F2' => 'Hampshire',
                'F3' => 'Haringey',
                'F4' => 'Harrow',
                'F5' => 'Hartlepool',
                'F6' => 'Havering',
                'F7' => 'Herefordshire',
                'F8' => 'Hertford',
                'F9' => 'Hillingdon',
                'G1' => 'Hounslow',
                'G2' => 'Isle of Wight',
                'G3' => 'Islington',
                'G4' => 'Kensington and Chelsea',
                'G5' => 'Kent',
                'G6' => 'Kingston upon Hull',
                'G7' => 'Kingston upon Thames',
                'G8' => 'Kirklees',
                'G9' => 'Knowsley',
                'H1' => 'Lambeth',
                'H2' => 'Lancashire',
                'H3' => 'Leeds',
                'H4' => 'Leicester',
                'H5' => 'Leicestershire',
                'H6' => 'Lewisham',
                'H7' => 'Lincolnshire',
                'H8' => 'Liverpool',
                'H9' => 'London',
                'I1' => 'Luton',
                'I2' => 'Manchester',
                'I3' => 'Medway',
                'I4' => 'Merton',
                'I5' => 'Middlesbrough',
                'I6' => 'Milton Keynes',
                'I7' => 'Newcastle upon Tyne',
                'I8' => 'Newham',
                'I9' => 'Norfolk',
                'J1' => 'Northamptonshire',
                'J2' => 'North East Lincolnshire',
                'J3' => 'North Lincolnshire',
                'J4' => 'North Somerset',
                'J5' => 'North Tyneside',
                'J6' => 'Northumberland',
                'J7' => 'North Yorkshire',
                'J8' => 'Nottingham',
                'J9' => 'Nottinghamshire',
                'K1' => 'Oldham',
                'K2' => 'Oxfordshire',
                'K3' => 'Peterborough',
                'K4' => 'Plymouth',
                'K5' => 'Poole',
                'K6' => 'Portsmouth',
                'K7' => 'Reading',
                'K8' => 'Redbridge',
                'K9' => 'Redcar and Cleveland',
                'L1' => 'Richmond upon Thames',
                'L2' => 'Rochdale',
                'L3' => 'Rotherham',
                'L4' => 'Rutland',
                'L5' => 'Salford',
                'L6' => 'Shropshire',
                'L7' => 'Sandwell',
                'L8' => 'Sefton',
                'L9' => 'Sheffield',
                'M1' => 'Slough',
                'M2' => 'Solihull',
                'M3' => 'Somerset',
                'M4' => 'Southampton',
                'M5' => 'Southend-on-Sea',
                'M6' => 'South Gloucestershire',
                'M7' => 'South Tyneside',
                'M8' => 'Southwark',
                'M9' => 'Staffordshire',
                'N1' => 'St. Helens',
                'N2' => 'Stockport',
                'N3' => 'Stockton-on-Tees',
                'N4' => 'Stoke-on-Trent',
                'N5' => 'Suffolk',
                'N6' => 'Sunderland',
                'N7' => 'Surrey',
                'N8' => 'Sutton',
                'N9' => 'Swindon',
                'O1' => 'Tameside',
                'O2' => 'Telford and Wrekin',
                'O3' => 'Thurrock',
                'O4' => 'Torbay',
                'O5' => 'Tower Hamlets',
                'O6' => 'Trafford',
                'O7' => 'Wakefield',
                'O8' => 'Walsall',
                'O9' => 'Waltham Forest',
                'P1' => 'Wandsworth',
                'P2' => 'Warrington',
                'P3' => 'Warwickshire',
                'P4' => 'West Berkshire',
                'P5' => 'Westminster',
                'P6' => 'West Sussex',
                'P7' => 'Wigan',
                'P8' => 'Wiltshire',
                'P9' => 'Windsor and Maidenhead',
                'Q1' => 'Wirral',
                'Q2' => 'Wokingham',
                'Q3' => 'Wolverhampton',
                'Q4' => 'Worcestershire',
                'Q5' => 'York',
                'Q6' => 'Antrim',
                'Q7' => 'Ards',
                'Q8' => 'Armagh',
                'Q9' => 'Ballymena',
                'R1' => 'Ballymoney',
                'R2' => 'Banbridge',
                'R3' => 'Belfast',
                'R4' => 'Carrickfergus',
                'R5' => 'Castlereagh',
                'R6' => 'Coleraine',
                'R7' => 'Cookstown',
                'R8' => 'Craigavon',
                'R9' => 'Down',
                'S1' => 'Dungannon',
                'S2' => 'Fermanagh',
                'S3' => 'Larne',
                'S4' => 'Limavady',
                'S5' => 'Lisburn',
                'S6' => 'Derry',
                'S7' => 'Magherafelt',
                'S8' => 'Moyle',
                'S9' => 'Newry and Mourne',
                'T1' => 'Newtownabbey',
                'T2' => 'North Down',
                'T3' => 'Omagh',
                'T4' => 'Strabane',
                'T5' => 'Aberdeen City',
                'T6' => 'Aberdeenshire',
                'T7' => 'Angus',
                'T8' => 'Argyll and Bute',
                'T9' => 'Scottish Borders',
                'U1' => 'Clackmannanshire',
                'U2' => 'Dumfries and Galloway',
                'U3' => 'Dundee City',
                'U4' => 'East Ayrshire',
                'U5' => 'East Dunbartonshire',
                'U6' => 'East Lothian',
                'U7' => 'East Renfrewshire',
                'U8' => 'Edinburgh',
                'U9' => 'Falkirk',
                'V1' => 'Fife',
                'V2' => 'Glasgow City',
                'V3' => 'Highland',
                'V4' => 'Inverclyde',
                'V5' => 'Midlothian',
                'V6' => 'Moray',
                'V7' => 'North Ayrshire',
                'V8' => 'North Lanarkshire',
                'V9' => 'Orkney',
                'W1' => 'Perth and Kinross',
                'W2' => 'Renfrewshire',
                'W3' => 'Shetland Islands',
                'W4' => 'South Ayrshire',
                'W5' => 'South Lanarkshire',
                'W6' => 'Stirling',
                'W7' => 'West Dunbartonshire',
                'W8' => 'Eilean Siar',
                'W9' => 'West Lothian',
                'X1' => 'Isle of Anglesey',
                'X2' => 'Blaenau Gwent',
                'X3' => 'Bridgend',
                'X4' => 'Caerphilly',
                'X5' => 'Cardiff',
                'X6' => 'Ceredigion',
                'X7' => 'Carmarthenshire',
                'X8' => 'Conwy',
                'X9' => 'Denbighshire',
                'Y1' => 'Flintshire',
                'Y2' => 'Gwynedd',
                'Y3' => 'Merthyr Tydfil',
                'Y4' => 'Monmouthshire',
                'Y5' => 'Neath Port Talbot',
                'Y6' => 'Newport',
                'Y7' => 'Pembrokeshire',
                'Y8' => 'Powys',
                'Y9' => 'Rhondda Cynon Taff',
                'Z1' => 'Swansea',
                'Z2' => 'Torfaen',
                'Z3' => 'Vale of Glamorgan',
                'Z4' => 'Wrexham',
                'Z6' => 'Central Bedfordshire',
                'Z7' => 'Cheshire East',
                'Z8' => 'Cheshire West and Chester',
                'Z9' => 'Isles of Scilly'
            ),
            'GD' => array(
                '01' => 'Saint Andrew',
                '02' => 'Saint David',
                '03' => 'Saint George',
                '04' => 'Saint John',
                '05' => 'Saint Mark',
                '06' => 'Saint Patrick'
            ),
            'GE' => array(
                '01' => 'Abashis Raioni',
                '02' => 'Abkhazia',
                '03' => 'Adigenis Raioni',
                '04' => 'Ajaria',
                '05' => 'Akhalgoris Raioni',
                '06' => 'Akhalk\'alak\'is Raioni',
                '07' => 'Akhalts\'ikhis Raioni',
                '08' => 'Akhmetis Raioni',
                '09' => 'Ambrolauris Raioni',
                10 => 'Aspindzis Raioni',
                11 => 'Baghdat\'is Raioni',
                12 => 'Bolnisis Raioni',
                13 => 'Borjomis Raioni',
                14 => 'Chiat\'ura',
                15 => 'Ch\'khorotsqus Raioni',
                16 => 'Ch\'okhatauris Raioni',
                17 => 'Dedop\'listsqaros Raioni',
                18 => 'Dmanisis Raioni',
                19 => 'Dushet\'is Raioni',
                20 => 'Gardabanis Raioni',
                21 => 'Gori',
                22 => 'Goris Raioni',
                23 => 'Gurjaanis Raioni',
                24 => 'Javis Raioni',
                25 => 'K\'arelis Raioni',
                26 => 'Kaspis Raioni',
                27 => 'Kharagaulis Raioni',
                28 => 'Khashuris Raioni',
                29 => 'Khobis Raioni',
                30 => 'Khonis Raioni',
                31 => 'K\'ut\'aisi',
                32 => 'Lagodekhis Raioni',
                33 => 'Lanch\'khut\'is Raioni',
                34 => 'Lentekhis Raioni',
                35 => 'Marneulis Raioni',
                36 => 'Martvilis Raioni',
                37 => 'Mestiis Raioni',
                38 => 'Mts\'khet\'is Raioni',
                39 => 'Ninotsmindis Raioni',
                40 => 'Onis Raioni',
                41 => 'Ozurget\'is Raioni',
                42 => 'P\'ot\'i',
                43 => 'Qazbegis Raioni',
                44 => 'Qvarlis Raioni',
                45 => 'Rust\'avi',
                46 => 'Sach\'kheris Raioni',
                47 => 'Sagarejos Raioni',
                48 => 'Samtrediis Raioni',
                49 => 'Senakis Raioni',
                50 => 'Sighnaghis Raioni',
                51 => 'T\'bilisi',
                52 => 'T\'elavis Raioni',
                53 => 'T\'erjolis Raioni',
                54 => 'T\'et\'ritsqaros Raioni',
                55 => 'T\'ianet\'is Raioni',
                56 => 'Tqibuli',
                57 => 'Ts\'ageris Raioni',
                58 => 'Tsalenjikhis Raioni',
                59 => 'Tsalkis Raioni',
                60 => 'Tsqaltubo',
                61 => 'Vanis Raioni',
                62 => 'Zestap\'onis Raioni',
                63 => 'Zugdidi',
                64 => 'Zugdidis Raioni'
            ),
            'GH' => array(
                '01' => 'Greater Accra',
                '02' => 'Ashanti',
                '03' => 'Brong-Ahafo',
                '04' => 'Central',
                '05' => 'Eastern',
                '06' => 'Northern',
                '08' => 'Volta',
                '09' => 'Western',
                10 => 'Upper East',
                11 => 'Upper West'
            ),
            'GL' => array(
                '01' => 'Nordgronland',
                '02' => 'Ostgronland',
                '03' => 'Vestgronland'
            ),
            'GM' => array(
                '01' => 'Banjul',
                '02' => 'Lower River',
                '03' => 'Central River',
                '04' => 'Upper River',
                '05' => 'Western',
                '07' => 'North Bank'
            ),
            'GN' => array(
                '01' => 'Beyla',
                '02' => 'Boffa',
                '03' => 'Boke',
                '04' => 'Conakry',
                '05' => 'Dabola',
                '06' => 'Dalaba',
                '07' => 'Dinguiraye',
                '09' => 'Faranah',
                10 => 'Forecariah',
                11 => 'Fria',
                12 => 'Gaoual',
                13 => 'Gueckedou',
                15 => 'Kerouane',
                16 => 'Kindia',
                17 => 'Kissidougou',
                18 => 'Koundara',
                19 => 'Kouroussa',
                21 => 'Macenta',
                22 => 'Mali',
                23 => 'Mamou',
                25 => 'Pita',
                27 => 'Telimele',
                28 => 'Tougue',
                29 => 'Yomou',
                30 => 'Coyah',
                31 => 'Dubreka',
                32 => 'Kankan',
                33 => 'Koubia',
                34 => 'Labe',
                35 => 'Lelouma',
                36 => 'Lola',
                37 => 'Mandiana',
                38 => 'Nzerekore',
                39 => 'Siguiri'
            ),
            'GQ' => array(
                '03' => 'Annobon',
                '04' => 'Bioko Norte',
                '05' => 'Bioko Sur',
                '06' => 'Centro Sur',
                '07' => 'Kie-Ntem',
                '08' => 'Litoral',
                '09' => 'Wele-Nzas'
            ),
            'GR' => array(
                '01' => 'Evros',
                '02' => 'Rodhopi',
                '03' => 'Xanthi',
                '04' => 'Drama',
                '05' => 'Serrai',
                '06' => 'Kilkis',
                '07' => 'Pella',
                '08' => 'Florina',
                '09' => 'Kastoria',
                10 => 'Grevena',
                11 => 'Kozani',
                12 => 'Imathia',
                13 => 'Thessaloniki',
                14 => 'Kavala',
                15 => 'Khalkidhiki',
                16 => 'Pieria',
                17 => 'Ioannina',
                18 => 'Thesprotia',
                19 => 'Preveza',
                20 => 'Arta',
                21 => 'Larisa',
                22 => 'Trikala',
                23 => 'Kardhitsa',
                24 => 'Magnisia',
                25 => 'Kerkira',
                26 => 'Levkas',
                27 => 'Kefallinia',
                28 => 'Zakinthos',
                29 => 'Fthiotis',
                30 => 'Evritania',
                31 => 'Aitolia kai Akarnania',
                32 => 'Fokis',
                33 => 'Voiotia',
                34 => 'Evvoia',
                35 => 'Attiki',
                36 => 'Argolis',
                37 => 'Korinthia',
                38 => 'Akhaia',
                39 => 'Ilia',
                40 => 'Messinia',
                41 => 'Arkadhia',
                42 => 'Lakonia',
                43 => 'Khania',
                44 => 'Rethimni',
                45 => 'Iraklion',
                46 => 'Lasithi',
                47 => 'Dhodhekanisos',
                48 => 'Samos',
                49 => 'Kikladhes',
                50 => 'Khios',
                51 => 'Lesvos'
            ),
            'GT' => array(
                '01' => 'Alta Verapaz',
                '02' => 'Baja Verapaz',
                '03' => 'Chimaltenango',
                '04' => 'Chiquimula',
                '05' => 'El Progreso',
                '06' => 'Escuintla',
                '07' => 'Guatemala',
                '08' => 'Huehuetenango',
                '09' => 'Izabal',
                10 => 'Jalapa',
                11 => 'Jutiapa',
                12 => 'Peten',
                13 => 'Quetzaltenango',
                14 => 'Quiche',
                15 => 'Retalhuleu',
                16 => 'Sacatepequez',
                17 => 'San Marcos',
                18 => 'Santa Rosa',
                19 => 'Solola',
                20 => 'Suchitepequez',
                21 => 'Totonicapan',
                22 => 'Zacapa'
            ),
            'GW' => array(
                '01' => 'Bafata',
                '02' => 'Quinara',
                '04' => 'Oio',
                '05' => 'Bolama',
                '06' => 'Cacheu',
                '07' => 'Tombali',
                10 => 'Gabu',
                11 => 'Bissau',
                12 => 'Biombo'
            ),
            'GY' => array(
                10 => 'Barima-Waini',
                11 => 'Cuyuni-Mazaruni',
                12 => 'Demerara-Mahaica',
                13 => 'East Berbice-Corentyne',
                14 => 'Essequibo Islands-West Demerara',
                15 => 'Mahaica-Berbice',
                16 => 'Pomeroon-Supenaam',
                17 => 'Potaro-Siparuni',
                18 => 'Upper Demerara-Berbice',
                19 => 'Upper Takutu-Upper Essequibo'
            ),
            'HN' => array(
                '01' => 'Atlantida',
                '02' => 'Choluteca',
                '03' => 'Colon',
                '04' => 'Comayagua',
                '05' => 'Copan',
                '06' => 'Cortes',
                '07' => 'El Paraiso',
                '08' => 'Francisco Morazan',
                '09' => 'Gracias a Dios',
                10 => 'Intibuca',
                11 => 'Islas de la Bahia',
                12 => 'La Paz',
                13 => 'Lempira',
                14 => 'Ocotepeque',
                15 => 'Olancho',
                16 => 'Santa Barbara',
                17 => 'Valle',
                18 => 'Yoro'
            ),
            'HR' => array(
                '01' => 'Bjelovarsko-Bilogorska',
                '02' => 'Brodsko-Posavska',
                '03' => 'Dubrovacko-Neretvanska',
                '04' => 'Istarska',
                '05' => 'Karlovacka',
                '06' => 'Koprivnicko-Krizevacka',
                '07' => 'Krapinsko-Zagorska',
                '08' => 'Licko-Senjska',
                '09' => 'Medimurska',
                10 => 'Osjecko-Baranjska',
                11 => 'Pozesko-Slavonska',
                12 => 'Primorsko-Goranska',
                13 => 'Sibensko-Kninska',
                14 => 'Sisacko-Moslavacka',
                15 => 'Splitsko-Dalmatinska',
                16 => 'Varazdinska',
                17 => 'Viroviticko-Podravska',
                18 => 'Vukovarsko-Srijemska',
                19 => 'Zadarska',
                20 => 'Zagrebacka',
                21 => 'Grad Zagreb'
            ),
            'HT' => array(
                '03' => 'Nord-Ouest',
                '06' => 'Artibonite',
                '07' => 'Centre',
                '09' => 'Nord',
                10 => 'Nord-Est',
                11 => 'Ouest',
                12 => 'Sud',
                13 => 'Sud-Est',
                14 => 'Grand\' Anse',
                15 => 'Nippes'
            ),
            'HU' => array(
                '01' => 'Bacs-Kiskun',
                '02' => 'Baranya',
                '03' => 'Bekes',
                '04' => 'Borsod-Abauj-Zemplen',
                '05' => 'Budapest',
                '06' => 'Csongrad',
                '07' => 'Debrecen',
                '08' => 'Fejer',
                '09' => 'Gyor-Moson-Sopron',
                10 => 'Hajdu-Bihar',
                11 => 'Heves',
                12 => 'Komarom-Esztergom',
                13 => 'Miskolc',
                14 => 'Nograd',
                15 => 'Pecs',
                16 => 'Pest',
                17 => 'Somogy',
                18 => 'Szabolcs-Szatmar-Bereg',
                19 => 'Szeged',
                20 => 'Jasz-Nagykun-Szolnok',
                21 => 'Tolna',
                22 => 'Vas',
                39 => 'Veszprem',
                24 => 'Zala',
                25 => 'Gyor',
                26 => 'Bekescsaba',
                27 => 'Dunaujvaros',
                28 => 'Eger',
                29 => 'Hodmezovasarhely',
                30 => 'Kaposvar',
                31 => 'Kecskemet',
                32 => 'Nagykanizsa',
                33 => 'Nyiregyhaza',
                34 => 'Sopron',
                35 => 'Szekesfehervar',
                36 => 'Szolnok',
                37 => 'Szombathely',
                38 => 'Tatabanya',
                40 => 'Zalaegerszeg',
                41 => 'Salgotarjan',
                42 => 'Szekszard',
                43 => 'Erd'
            ),
            'ID' => array(
                '01' => 'Aceh',
                '02' => 'Bali',
                '03' => 'Bengkulu',
                '04' => 'Jakarta Raya',
                '05' => 'Jambi',
                '07' => 'Jawa Tengah',
                '08' => 'Jawa Timur',
                10 => 'Yogyakarta',
                11 => 'Kalimantan Barat',
                12 => 'Kalimantan Selatan',
                13 => 'Kalimantan Tengah',
                14 => 'Kalimantan Timur',
                15 => 'Lampung',
                17 => 'Nusa Tenggara Barat',
                18 => 'Nusa Tenggara Timur',
                21 => 'Sulawesi Tengah',
                22 => 'Sulawesi Tenggara',
                24 => 'Sumatera Barat',
                26 => 'Sumatera Utara',
                28 => 'Maluku',
                29 => 'Maluku Utara',
                30 => 'Jawa Barat',
                31 => 'Sulawesi Utara',
                32 => 'Sumatera Selatan',
                33 => 'Banten',
                34 => 'Gorontalo',
                35 => 'Kepulauan Bangka Belitung',
                36 => 'Papua',
                37 => 'Riau',
                38 => 'Sulawesi Selatan',
                39 => 'Irian Jaya Barat',
                40 => 'Kepulauan Riau',
                41 => 'Sulawesi Barat'
            ),
            'IE' => array(
                '01' => 'Carlow',
                '02' => 'Cavan',
                '03' => 'Clare',
                '04' => 'Cork',
                '06' => 'Donegal',
                '07' => 'Dublin',
                10 => 'Galway',
                11 => 'Kerry',
                12 => 'Kildare',
                13 => 'Kilkenny',
                14 => 'Leitrim',
                15 => 'Laois',
                16 => 'Limerick',
                18 => 'Longford',
                19 => 'Louth',
                20 => 'Mayo',
                21 => 'Meath',
                22 => 'Monaghan',
                23 => 'Offaly',
                24 => 'Roscommon',
                25 => 'Sligo',
                26 => 'Tipperary',
                27 => 'Waterford',
                29 => 'Westmeath',
                30 => 'Wexford',
                31 => 'Wicklow'
            ),
            'IL' => array(
                '01' => 'HaDarom',
                '02' => 'HaMerkaz',
                '03' => 'HaZafon',
                '04' => 'Hefa',
                '05' => 'Tel Aviv',
                '06' => 'Yerushalayim'
            ),
            'IN' => array(
                '01' => 'Andaman and Nicobar Islands',
                '02' => 'Andhra Pradesh',
                '03' => 'Assam',
                '05' => 'Chandigarh',
                '06' => 'Dadra and Nagar Haveli',
                '07' => 'Delhi',
                '09' => 'Gujarat',
                10 => 'Haryana',
                11 => 'Himachal Pradesh',
                12 => 'Jammu and Kashmir',
                13 => 'Kerala',
                14 => 'Lakshadweep',
                16 => 'Maharashtra',
                17 => 'Manipur',
                18 => 'Meghalaya',
                19 => 'Karnataka',
                20 => 'Nagaland',
                21 => 'Orissa',
                22 => 'Puducherry',
                23 => 'Punjab',
                24 => 'Rajasthan',
                25 => 'Tamil Nadu',
                26 => 'Tripura',
                28 => 'West Bengal',
                29 => 'Sikkim',
                30 => 'Arunachal Pradesh',
                31 => 'Mizoram',
                32 => 'Daman and Diu',
                33 => 'Goa',
                34 => 'Bihar',
                35 => 'Madhya Pradesh',
                36 => 'Uttar Pradesh',
                37 => 'Chhattisgarh',
                38 => 'Jharkhand',
                39 => 'Uttarakhand'
            ),
            'IQ' => array(
                '01' => 'Al Anbar',
                '02' => 'Al Basrah',
                '03' => 'Al Muthanna',
                '04' => 'Al Qadisiyah',
                '05' => 'As Sulaymaniyah',
                '06' => 'Babil',
                '07' => 'Baghdad',
                '08' => 'Dahuk',
                '09' => 'Dhi Qar',
                10 => 'Diyala',
                11 => 'Arbil',
                12 => 'Karbala\'',
                13 => 'At Ta\'mim',
                14 => 'Maysan',
                15 => 'Ninawa',
                16 => 'Wasit',
                17 => 'An Najaf',
                18 => 'Salah ad Din'
            ),
            'IR' => array(
                '01' => 'Azarbayjan-e Bakhtari',
                '03' => 'Chahar Mahall va Bakhtiari',
                '04' => 'Sistan va Baluchestan',
                '05' => 'Kohkiluyeh va Buyer Ahmadi',
                '07' => 'Fars',
                '08' => 'Gilan',
                '09' => 'Hamadan',
                10 => 'Ilam',
                11 => 'Hormozgan',
                29 => 'Kerman',
                13 => 'Bakhtaran',
                15 => 'Khuzestan',
                16 => 'Kordestan',
                35 => 'Mazandaran',
                18 => 'Semnan Province',
                34 => 'Markazi',
                36 => 'Zanjan',
                22 => 'Bushehr',
                23 => 'Lorestan',
                25 => 'Semnan',
                26 => 'Tehran',
                28 => 'Esfahan',
                30 => 'Khorasan',
                40 => 'Yazd',
                32 => 'Ardabil',
                33 => 'East Azarbaijan',
                37 => 'Golestan',
                38 => 'Qazvin',
                39 => 'Qom',
                41 => 'Khorasan-e Janubi',
                42 => 'Khorasan-e Razavi',
                43 => 'Khorasan-e Shemali',
                44 => 'Alborz'
            ),
            'IS' => array(
                '03' => 'Arnessysla',
                '05' => 'Austur-Hunavatnssysla',
                '06' => 'Austur-Skaftafellssysla',
                '07' => 'Borgarfjardarsysla',
                '09' => 'Eyjafjardarsysla',
                10 => 'Gullbringusysla',
                15 => 'Kjosarsysla',
                17 => 'Myrasysla',
                20 => 'Nordur-Mulasysla',
                21 => 'Nordur-Tingeyjarsysla',
                23 => 'Rangarvallasysla',
                28 => 'Skagafjardarsysla',
                29 => 'Snafellsnes- og Hnappadalssysla',
                30 => 'Strandasysla',
                31 => 'Sudur-Mulasysla',
                32 => 'Sudur-Tingeyjarsysla',
                34 => 'Vestur-Bardastrandarsysla',
                35 => 'Vestur-Hunavatnssysla',
                36 => 'Vestur-Isafjardarsysla',
                37 => 'Vestur-Skaftafellssysla',
                38 => 'Austurland',
                39 => 'Hofuoborgarsvaoio',
                40 => 'Norourland Eystra',
                41 => 'Norourland Vestra',
                42 => 'Suourland',
                43 => 'Suournes',
                44 => 'Vestfiroir',
                45 => 'Vesturland'
            ),
            'IT' => array(
                '01' => 'Abruzzi',
                '02' => 'Basilicata',
                '03' => 'Calabria',
                '04' => 'Campania',
                '05' => 'Emilia-Romagna',
                '06' => 'Friuli-Venezia Giulia',
                '07' => 'Lazio',
                '08' => 'Liguria',
                '09' => 'Lombardia',
                10 => 'Marche',
                11 => 'Molise',
                12 => 'Piemonte',
                13 => 'Puglia',
                14 => 'Sardegna',
                15 => 'Sicilia',
                16 => 'Toscana',
                17 => 'Trentino-Alto Adige',
                18 => 'Umbria',
                19 => 'Valle d\'Aosta',
                20 => 'Veneto'
            ),
            'JM' => array(
                '01' => 'Clarendon',
                '02' => 'Hanover',
                '04' => 'Manchester',
                '07' => 'Portland',
                '08' => 'Saint Andrew',
                '09' => 'Saint Ann',
                10 => 'Saint Catherine',
                11 => 'Saint Elizabeth',
                12 => 'Saint James',
                13 => 'Saint Mary',
                14 => 'Saint Thomas',
                15 => 'Trelawny',
                16 => 'Westmoreland',
                17 => 'Kingston'
            ),
            'JO' => array(
                '02' => 'Al Balqa\'',
                '09' => 'Al Karak',
                12 => 'At Tafilah',
                15 => 'Al Mafraq',
                16 => 'Amman',
                17 => 'Az Zaraqa',
                18 => 'Irbid',
                19 => 'Ma\'an',
                20 => 'Ajlun',
                21 => 'Al Aqabah',
                22 => 'Jarash',
                23 => 'Madaba'
            ),
            'JP' => array(
                '01' => 'Aichi',
                '02' => 'Akita',
                '03' => 'Aomori',
                '04' => 'Chiba',
                '05' => 'Ehime',
                '06' => 'Fukui',
                '07' => 'Fukuoka',
                '08' => 'Fukushima',
                '09' => 'Gifu',
                10 => 'Gumma',
                11 => 'Hiroshima',
                12 => 'Hokkaido',
                13 => 'Hyogo',
                14 => 'Ibaraki',
                15 => 'Ishikawa',
                16 => 'Iwate',
                17 => 'Kagawa',
                18 => 'Kagoshima',
                19 => 'Kanagawa',
                20 => 'Kochi',
                21 => 'Kumamoto',
                22 => 'Kyoto',
                23 => 'Mie',
                24 => 'Miyagi',
                25 => 'Miyazaki',
                26 => 'Nagano',
                27 => 'Nagasaki',
                28 => 'Nara',
                29 => 'Niigata',
                30 => 'Oita',
                31 => 'Okayama',
                32 => 'Osaka',
                33 => 'Saga',
                34 => 'Saitama',
                35 => 'Shiga',
                36 => 'Shimane',
                37 => 'Shizuoka',
                38 => 'Tochigi',
                39 => 'Tokushima',
                40 => 'Tokyo',
                41 => 'Tottori',
                42 => 'Toyama',
                43 => 'Wakayama',
                44 => 'Yamagata',
                45 => 'Yamaguchi',
                46 => 'Yamanashi',
                47 => 'Okinawa'
            ),
            'KE' => array(
                '01' => 'Central',
                '02' => 'Coast',
                '03' => 'Eastern',
                '05' => 'Nairobi Area',
                '06' => 'North-Eastern',
                '07' => 'Nyanza',
                '08' => 'Rift Valley',
                '09' => 'Western'
            ),
            'KG' => array(
                '01' => 'Bishkek',
                '02' => 'Chuy',
                '03' => 'Jalal-Abad',
                '04' => 'Naryn',
                '08' => 'Osh',
                '06' => 'Talas',
                '07' => 'Ysyk-Kol',
                '09' => 'Batken'
            ),
            'KH' => array(
                29 => 'Batdambang',
                '02' => 'Kampong Cham',
                '03' => 'Kampong Chhnang',
                '04' => 'Kampong Speu',
                '05' => 'Kampong Thum',
                '06' => 'Kampot',
                '07' => 'Kandal',
                '08' => 'Koh Kong',
                '09' => 'Kracheh',
                10 => 'Mondulkiri',
                22 => 'Phnum Penh',
                12 => 'Pursat',
                13 => 'Preah Vihear',
                14 => 'Prey Veng',
                15 => 'Ratanakiri Kiri',
                16 => 'Siem Reap',
                17 => 'Stung Treng',
                18 => 'Svay Rieng',
                19 => 'Takeo',
                23 => 'Ratanakiri',
                25 => 'Banteay Meanchey',
                28 => 'Preah Seihanu',
                30 => 'Pailin'
            ),
            'KI' => array(
                '01' => 'Gilbert Islands',
                '02' => 'Line Islands',
                '03' => 'Phoenix Islands'
            ),
            'KM' => array(
                '01' => 'Anjouan',
                '02' => 'Grande Comore',
                '03' => 'Moheli'
            ),
            'KN' => array(
                '01' => 'Christ Church Nichola Town',
                '02' => 'Saint Anne Sandy Point',
                '03' => 'Saint George Basseterre',
                '04' => 'Saint George Gingerland',
                '05' => 'Saint James Windward',
                '06' => 'Saint John Capisterre',
                '07' => 'Saint John Figtree',
                '08' => 'Saint Mary Cayon',
                '09' => 'Saint Paul Capisterre',
                10 => 'Saint Paul Charlestown',
                11 => 'Saint Peter Basseterre',
                12 => 'Saint Thomas Lowland',
                13 => 'Saint Thomas Middle Island',
                15 => 'Trinity Palmetto Point'
            ),
            'KP' => array(
                '01' => 'Chagang-do',
                '03' => 'Hamgyong-namdo',
                '06' => 'Hwanghae-namdo',
                '07' => 'Hwanghae-bukto',
                '08' => 'Kaesong-si',
                '09' => 'Kangwon-do',
                11 => 'P\'yongan-bukto',
                12 => 'P\'yongyang-si',
                13 => 'Yanggang-do',
                14 => 'Namp\'o-si',
                15 => 'P\'yongan-namdo',
                17 => 'Hamgyong-bukto',
                18 => 'Najin Sonbong-si'
            ),
            'KR' => array(
                '01' => 'Cheju-do',
                '03' => 'Cholla-bukto',
                '05' => 'Ch\'ungch\'ong-bukto',
                '06' => 'Kangwon-do',
                10 => 'Pusan-jikhalsi',
                11 => 'Seoul-t\'ukpyolsi',
                12 => 'Inch\'on-jikhalsi',
                13 => 'Kyonggi-do',
                14 => 'Kyongsang-bukto',
                15 => 'Taegu-jikhalsi',
                16 => 'Cholla-namdo',
                17 => 'Ch\'ungch\'ong-namdo',
                18 => 'Kwangju-jikhalsi',
                19 => 'Taejon-jikhalsi',
                20 => 'Kyongsang-namdo',
                21 => 'Ulsan-gwangyoksi'
            ),
            'KW' => array(
                '01' => 'Al Ahmadi',
                '02' => 'Al Kuwayt',
                '05' => 'Al Jahra',
                '07' => 'Al Farwaniyah',
                '08' => 'Hawalli',
                '09' => 'Mubarak al Kabir'
            ),
            'KY' => array(
                '01' => 'Creek',
                '02' => 'Eastern',
                '03' => 'Midland',
                '04' => 'South Town',
                '05' => 'Spot Bay',
                '06' => 'Stake Bay',
                '07' => 'West End',
                '08' => 'Western'
            ),
            'KZ' => array(
                '01' => 'Almaty',
                '02' => 'Almaty City',
                '03' => 'Aqmola',
                '04' => 'Aqtobe',
                '05' => 'Astana',
                '06' => 'Atyrau',
                '07' => 'West Kazakhstan',
                '08' => 'Bayqonyr',
                '09' => 'Mangghystau',
                10 => 'South Kazakhstan',
                11 => 'Pavlodar',
                12 => 'Qaraghandy',
                13 => 'Qostanay',
                14 => 'Qyzylorda',
                15 => 'East Kazakhstan',
                16 => 'North Kazakhstan',
                17 => 'Zhambyl'
            ),
            'LA' => array(
                '01' => 'Attapu',
                '02' => 'Champasak',
                '03' => 'Houaphan',
                '04' => 'Khammouan',
                '05' => 'Louang Namtha',
                '07' => 'Oudomxai',
                '08' => 'Phongsali',
                '09' => 'Saravan',
                10 => 'Savannakhet',
                11 => 'Vientiane',
                13 => 'Xaignabouri',
                14 => 'Xiangkhoang',
                17 => 'Louangphrabang'
            ),
            'LB' => array(
                '08' => 'Beqaa',
                '02' => 'Al Janub',
                '09' => 'Liban-Nord',
                '04' => 'Beyrouth',
                '05' => 'Mont-Liban',
                '06' => 'Liban-Sud',
                '07' => 'Nabatiye',
                10 => 'Aakk',
                11 => 'Baalbek-Hermel'
            ),
            'LC' => array(
                '01' => 'Anse-la-Raye',
                '02' => 'Dauphin',
                '03' => 'Castries',
                '04' => 'Choiseul',
                '05' => 'Dennery',
                '06' => 'Gros-Islet',
                '07' => 'Laborie',
                '08' => 'Micoud',
                '09' => 'Soufriere',
                10 => 'Vieux-Fort',
                11 => 'Praslin'
            ),
            'LI' => array(
                '01' => 'Balzers',
                '02' => 'Eschen',
                '03' => 'Gamprin',
                '04' => 'Mauren',
                '05' => 'Planken',
                '06' => 'Ruggell',
                '07' => 'Schaan',
                '08' => 'Schellenberg',
                '09' => 'Triesen',
                10 => 'Triesenberg',
                11 => 'Vaduz',
                21 => 'Gbarpolu',
                22 => 'River Gee'
            ),
            'LK' => array(
                29 => 'Central',
                30 => 'North Central',
                32 => 'North Western',
                33 => 'Sabaragamuwa',
                34 => 'Southern',
                35 => 'Uva',
                36 => 'Western',
                37 => 'Eastern',
                38 => 'Northern'
            ),
            'LR' => array(
                '01' => 'Bong',
                12 => 'Grand Cape Mount',
                20 => 'Lofa',
                13 => 'Maryland',
                '07' => 'Monrovia',
                '09' => 'Nimba',
                10 => 'Sino',
                11 => 'Grand Bassa',
                14 => 'Montserrado',
                17 => 'Margibi',
                18 => 'River Cess',
                19 => 'Grand Gedeh',
                21 => 'Gbarpolu',
                22 => 'River Gee'
            ),
            'LS' => array(
                10 => 'Berea',
                11 => 'Butha-Buthe',
                12 => 'Leribe',
                13 => 'Mafeteng',
                14 => 'Maseru',
                15 => 'Mohales Hoek',
                16 => 'Mokhotlong',
                17 => 'Qachas Nek',
                18 => 'Quthing',
                19 => 'Thaba-Tseka'
            ),
            'LT' => array(
                56 => 'Alytaus Apskritis',
                57 => 'Kauno Apskritis',
                58 => 'Klaipedos Apskritis',
                59 => 'Marijampoles Apskritis',
                60 => 'Panevezio Apskritis',
                61 => 'Siauliu Apskritis',
                62 => 'Taurages Apskritis',
                63 => 'Telsiu Apskritis',
                64 => 'Utenos Apskritis',
                65 => 'Vilniaus Apskritis'
            ),
            'LU' => array(
                '01' => 'Diekirch',
                '02' => 'Grevenmacher',
                '03' => 'Luxembourg'
            ),
            'LV' => array(
                '01' => 'Aizkraukles',
                '02' => 'Aluksnes',
                '03' => 'Balvu',
                '04' => 'Bauskas',
                '05' => 'Cesu',
                '07' => 'Daugavpils',
                '08' => 'Dobeles',
                '09' => 'Gulbenes',
                10 => 'Jekabpils',
                11 => 'Jelgava',
                12 => 'Jelgavas',
                13 => 'Jurmala',
                14 => 'Kraslavas',
                15 => 'Kuldigas',
                16 => 'Liepaja',
                17 => 'Liepajas',
                18 => 'Limbazu',
                19 => 'Ludzas',
                20 => 'Madonas',
                21 => 'Ogres',
                22 => 'Preilu',
                23 => 'Rezekne',
                24 => 'Rezeknes',
                25 => 'Riga',
                26 => 'Rigas',
                27 => 'Saldus',
                28 => 'Talsu',
                29 => 'Tukuma',
                30 => 'Valkas',
                31 => 'Valmieras',
                33 => 'Ventspils'
            ),
            'LY' => array(
                '03' => 'Al Aziziyah',
                '05' => 'Al Jufrah',
                '08' => 'Al Kufrah',
                13 => 'Ash Shati\'',
                30 => 'Murzuq',
                34 => 'Sabha',
                41 => 'Tarhunah',
                42 => 'Tubruq',
                45 => 'Zlitan',
                47 => 'Ajdabiya',
                48 => 'Al Fatih',
                49 => 'Al Jabal al Akhdar',
                50 => 'Al Khums',
                51 => 'An Nuqat al Khams',
                52 => 'Awbari',
                53 => 'Az Zawiyah',
                54 => 'Banghazi',
                55 => 'Darnah',
                56 => 'Ghadamis',
                57 => 'Gharyan',
                58 => 'Misratah',
                59 => 'Sawfajjin',
                60 => 'Surt',
                61 => 'Tarabulus',
                62 => 'Yafran'
            ),
            'MA' => array(
                45 => 'Grand Casablanca',
                46 => 'Fes-Boulemane',
                47 => 'Marrakech-Tensift-Al Haouz',
                48 => 'Meknes-Tafilalet',
                49 => 'Rabat-Sale-Zemmour-Zaer',
                50 => 'Chaouia-Ouardigha',
                51 => 'Doukkala-Abda',
                52 => 'Gharb-Chrarda-Beni Hssen',
                53 => 'Guelmim-Es Smara',
                54 => 'Oriental',
                55 => 'Souss-Massa-Dr',
                56 => 'Tadla-Azilal',
                57 => 'Tanger-Tetouan',
                58 => 'Taza-Al Hoceima-Taounate',
                59 => 'La'
            ),
            'MC' => array(
                '01' => 'La Condamine',
                '02' => 'Monaco',
                '03' => 'Monte-Carlo'
            ),
            'MD' => array(
                51 => 'Gagauzia',
                57 => 'Chisinau',
                58 => 'Stinga Nistrului',
                59 => 'Anenii Noi',
                60 => 'Balti',
                61 => 'Basarabeasca',
                62 => 'Bender',
                63 => 'Briceni',
                64 => 'Cahul',
                65 => 'Cantemir',
                66 => 'Calarasi',
                67 => 'Causeni',
                68 => 'Cimislia',
                69 => 'Criuleni',
                70 => 'Donduseni',
                71 => 'Drochia',
                72 => 'Dubasari',
                73 => 'Edinet',
                74 => 'Falesti',
                75 => 'Floresti',
                76 => 'Glodeni',
                77 => 'Hincesti',
                78 => 'Ialoveni',
                79 => 'Leova',
                80 => 'Nisporeni',
                81 => 'Ocnita',
                82 => 'Orhei',
                83 => 'Rezina',
                84 => 'Riscani',
                85 => 'Singerei',
                86 => 'Soldanesti',
                87 => 'Soroca',
                88 => 'Stefan-Voda',
                89 => 'Straseni',
                90 => 'Taraclia',
                91 => 'Telenesti',
                92 => 'Ungheni'
            ),
            'MG' => array(
                '01' => 'Antsiranana',
                '02' => 'Fianarantsoa',
                '03' => 'Mahajanga',
                '04' => 'Toamasina',
                '05' => 'Antananarivo',
                '06' => 'Toliara'
            ),
            'MK' => array(
                '01' => 'Aracinovo',
                '02' => 'Bac',
                '03' => 'Belcista',
                '04' => 'Berovo',
                '05' => 'Bistrica',
                '06' => 'Bitola',
                '07' => 'Blatec',
                '08' => 'Bogdanci',
                '09' => 'Bogomila',
                10 => 'Bogovinje',
                11 => 'Bosilovo',
                12 => 'Brvenica',
                'C8' => 'Cair',
                14 => 'Capari',
                'C9' => 'Caska',
                16 => 'Cegrane',
                17 => 'Centar',
                18 => 'Centar Zupa',
                19 => 'Cesinovo',
                20 => 'Cucer-Sandevo',
                'D2' => 'Debar',
                22 => 'Delcevo',
                23 => 'Delogozdi',
                'D3' => 'Demir Hisar',
                25 => 'Demir Kapija',
                26 => 'Dobrusevo',
                27 => 'Dolna Banjica',
                28 => 'Dolneni',
                29 => 'Dorce Petrov',
                30 => 'Drugovo',
                31 => 'Dzepciste',
                32 => 'Gazi Baba',
                33 => 'Gevgelija',
                'D4' => 'Gostivar',
                35 => 'Gradsko',
                36 => 'Ilinden',
                37 => 'Izvor',
                'D5' => 'Jegunovce',
                39 => 'Kamenjane',
                40 => 'Karbinci',
                41 => 'Karpos',
                'D6' => 'Kavadarci',
                43 => 'Kicevo',
                44 => 'Kisela Voda',
                45 => 'Klecevce',
                46 => 'Kocani',
                47 => 'Konce',
                48 => 'Kondovo',
                49 => 'Konopiste',
                50 => 'Kosel',
                51 => 'Kratovo',
                52 => 'Kriva Palanka',
                53 => 'Krivogastani',
                54 => 'Krusevo',
                55 => 'Kuklis',
                56 => 'Kukurecani',
                'D7' => 'Kumanovo',
                58 => 'Labunista',
                59 => 'Lipkovo',
                60 => 'Lozovo',
                61 => 'Lukovo',
                62 => 'Makedonska Kamenica',
                'D8' => 'Makedonski Brod',
                64 => 'Mavrovi Anovi',
                65 => 'Meseista',
                66 => 'Miravci',
                67 => 'Mogila',
                68 => 'Murtino',
                69 => 'Negotino',
                70 => 'Negotino-Polosko',
                71 => 'Novaci',
                72 => 'Novo Selo',
                73 => 'Oblesevo',
                'E2' => 'Ohrid',
                75 => 'Orasac',
                76 => 'Orizari',
                77 => 'Oslomej',
                78 => 'Pehcevo',
                79 => 'Petrovec',
                80 => 'Plasnica',
                81 => 'Podares',
                'E3' => 'Prilep',
                83 => 'Probistip',
                84 => 'Radovis',
                85 => 'Rankovce',
                86 => 'Resen',
                87 => 'Rosoman',
                88 => 'Rostusa',
                89 => 'Samokov',
                90 => 'Saraj',
                91 => 'Sipkovica',
                92 => 'Sopiste',
                93 => 'Sopotnica',
                94 => 'Srbinovo',
                95 => 'Staravina',
                96 => 'Star Dojran',
                97 => 'Staro Nagoricane',
                98 => 'Stip',
                'E6' => 'Struga',
                'E7' => 'Strumica',
                'A2' => 'Studenicani',
                'A3' => 'Suto Orizari',
                'A4' => 'Sveti Nikole',
                'A5' => 'Tearce',
                'E8' => 'Tetovo',
                'A7' => 'Topolcani',
                'E9' => 'Valandovo',
                'A9' => 'Vasilevo',
                'F1' => 'Veles',
                'B2' => 'Velesta',
                'B3' => 'Vevcani',
                'B4' => 'Vinica',
                'B5' => 'Vitoliste',
                'B6' => 'Vranestica',
                'B7' => 'Vrapciste',
                'B8' => 'Vratnica',
                'B9' => 'Vrutok',
                'C1' => 'Zajas',
                'C2' => 'Zelenikovo',
                'C3' => 'Zelino',
                'C4' => 'Zitose',
                'C5' => 'Zletovo',
                'C6' => 'Zrnovci',
                'E5' => 'Dojran',
                'F2' => 'Aerodrom'
            ),
            'ML' => array(
                '01' => 'Bamako',
                '03' => 'Kayes',
                '04' => 'Mopti',
                '05' => 'Segou',
                '06' => 'Sikasso',
                '07' => 'Koulikoro',
                '08' => 'Tombouctou',
                '09' => 'Gao',
                10 => 'Kidal'
            ),
            'MM' => array(
                '01' => 'Rakhine State',
                '02' => 'Chin State',
                '03' => 'Irrawaddy',
                '04' => 'Kachin State',
                '05' => 'Karan State',
                '06' => 'Kayah State',
                '07' => 'Magwe',
                '08' => 'Mandalay',
                '09' => 'Pegu',
                10 => 'Sagaing',
                11 => 'Shan State',
                12 => 'Tenasserim',
                13 => 'Mon State',
                14 => 'Rangoon',
                17 => 'Yangon'
            ),
            'MN' => array(
                '01' => 'Arhangay',
                '02' => 'Bayanhongor',
                '03' => 'Bayan-Olgiy',
                '05' => 'Darhan',
                '06' => 'Dornod',
                '07' => 'Dornogovi',
                '08' => 'Dundgovi',
                '09' => 'Dzavhan',
                10 => 'Govi-Altay',
                11 => 'Hentiy',
                12 => 'Hovd',
                13 => 'Hovsgol',
                14 => 'Omnogovi',
                15 => 'Ovorhangay',
                16 => 'Selenge',
                17 => 'Suhbaatar',
                18 => 'Tov',
                19 => 'Uvs',
                20 => 'Ulaanbaatar',
                21 => 'Bulgan',
                22 => 'Erdenet',
                23 => 'Darhan-Uul',
                24 => 'Govisumber',
                25 => 'Orhon'
            ),
            'MO' => array(
                '01' => 'Ilhas',
                '02' => 'Macau'
            ),
            'MR' => array(
                '01' => 'Hodh Ech Chargui',
                '02' => 'Hodh El Gharbi',
                '03' => 'Assaba',
                '04' => 'Gorgol',
                '05' => 'Brakna',
                '06' => 'Trarza',
                '07' => 'Adrar',
                '08' => 'Dakhlet Nouadhibou',
                '09' => 'Tagant',
                10 => 'Guidimaka',
                11 => 'Tiris Zemmour',
                12 => 'Inchiri'
            ),
            'MS' => array(
                '01' => 'Saint Anthony',
                '02' => 'Saint Georges',
                '03' => 'Saint Peter'
            ),
            'MU' => array(
                12 => 'Black River',
                13 => 'Flacq',
                14 => 'Grand Port',
                15 => 'Moka',
                16 => 'Pamplemousses',
                17 => 'Plaines Wilhems',
                18 => 'Port Louis',
                19 => 'Riviere du Rempart',
                20 => 'Savanne',
                21 => 'Agalega Islands',
                22 => 'Cargados Carajos',
                23 => 'Rodrigues'
            ),
            'MV' => array(
                '01' => 'Seenu',
                '05' => 'Laamu',
                30 => 'Alifu',
                31 => 'Baa',
                32 => 'Dhaalu',
                33 => 'Faafu',
                34 => 'Gaafu Alifu',
                35 => 'Gaafu Dhaalu',
                36 => 'Haa Alifu',
                37 => 'Haa Dhaalu',
                38 => 'Kaafu',
                39 => 'Lhaviyani',
                40 => 'Maale',
                41 => 'Meemu',
                42 => 'Gnaviyani',
                43 => 'Noonu',
                44 => 'Raa',
                45 => 'Shaviyani',
                46 => 'Thaa',
                47 => 'Vaavu'
            ),
            'MW' => array(
                '02' => 'Chikwawa',
                '03' => 'Chiradzulu',
                '04' => 'Chitipa',
                '05' => 'Thyolo',
                '06' => 'Dedza',
                '07' => 'Dowa',
                '08' => 'Karonga',
                '09' => 'Kasungu',
                11 => 'Lilongwe',
                12 => 'Mangochi',
                13 => 'Mchinji',
                15 => 'Mzimba',
                16 => 'Ntcheu',
                17 => 'Nkhata Bay',
                18 => 'Nkhotakota',
                19 => 'Nsanje',
                20 => 'Ntchisi',
                21 => 'Rumphi',
                22 => 'Salima',
                23 => 'Zomba',
                24 => 'Blantyre',
                25 => 'Mwanza',
                26 => 'Balaka',
                27 => 'Likoma',
                28 => 'Machinga',
                29 => 'Mulanje',
                30 => 'Phalombe'
            ),
            'MX' => array(
                '01' => 'Aguascalientes',
                '02' => 'Baja California',
                '03' => 'Baja California Sur',
                '04' => 'Campeche',
                '05' => 'Chiapas',
                '06' => 'Chihuahua',
                '07' => 'Coahuila de Zaragoza',
                '08' => 'Colima',
                '09' => 'Distrito Federal',
                10 => 'Durango',
                11 => 'Guanajuato',
                12 => 'Guerrero',
                13 => 'Hidalgo',
                14 => 'Jalisco',
                15 => 'Mexico',
                16 => 'Michoacan de Ocampo',
                17 => 'Morelos',
                18 => 'Nayarit',
                19 => 'Nuevo Leon',
                20 => 'Oaxaca',
                21 => 'Puebla',
                22 => 'Queretaro de Arteaga',
                23 => 'Quintana Roo',
                24 => 'San Luis Potosi',
                25 => 'Sinaloa',
                26 => 'Sonora',
                27 => 'Tabasco',
                28 => 'Tamaulipas',
                29 => 'Tlaxcala',
                30 => 'Veracruz-Llave',
                31 => 'Yucatan',
                32 => 'Zacatecas'
            ),
            'MY' => array(
                '01' => 'Johor',
                '02' => 'Kedah',
                '03' => 'Kelantan',
                '04' => 'Melaka',
                '05' => 'Negeri Sembilan',
                '06' => 'Pahang',
                '07' => 'Perak',
                '08' => 'Perlis',
                '09' => 'Pulau Pinang',
                11 => 'Sarawak',
                12 => 'Selangor',
                13 => 'Terengganu',
                14 => 'Kuala Lumpur',
                15 => 'Labuan',
                16 => 'Sabah',
                17 => 'Putrajaya'
            ),
            'MZ' => array(
                '01' => 'Cabo Delgado',
                '02' => 'Gaza',
                '03' => 'Inhambane',
                11 => 'Maputo',
                '05' => 'Sofala',
                '06' => 'Nampula',
                '07' => 'Niassa',
                '08' => 'Tete',
                '09' => 'Zambezia',
                10 => 'Manica'
            ),
            'NA' => array(
                '01' => 'Bethanien',
                '02' => 'Caprivi Oos',
                '03' => 'Boesmanland',
                '04' => 'Gobabis',
                '05' => 'Grootfontein',
                '06' => 'Kaokoland',
                '07' => 'Karibib',
                '08' => 'Keetmanshoop',
                '09' => 'Luderitz',
                10 => 'Maltahohe',
                11 => 'Okahandja',
                12 => 'Omaruru',
                13 => 'Otjiwarongo',
                14 => 'Outjo',
                15 => 'Owambo',
                16 => 'Rehoboth',
                17 => 'Swakopmund',
                18 => 'Tsumeb',
                20 => 'Karasburg',
                21 => 'Windhoek',
                22 => 'Damaraland',
                23 => 'Hereroland Oos',
                24 => 'Hereroland Wes',
                25 => 'Kavango',
                26 => 'Mariental',
                27 => 'Namaland',
                28 => 'Caprivi',
                29 => 'Erongo',
                30 => 'Hardap',
                31 => 'Karas',
                32 => 'Kunene',
                33 => 'Ohangwena',
                34 => 'Okavango',
                35 => 'Omaheke',
                36 => 'Omusati',
                37 => 'Oshana',
                38 => 'Oshikoto',
                39 => 'Otjozondjupa'
            ),
            'NE' => array(
                '01' => 'Agadez',
                '02' => 'Diffa',
                '03' => 'Dosso',
                '04' => 'Maradi',
                '08' => 'Niamey',
                '06' => 'Tahoua',
                '07' => 'Zinder'
            ),
            'NG' => array(
                '05' => 'Lagos',
                11 => 'Federal Capital Territory',
                16 => 'Ogun',
                21 => 'Akwa Ibom',
                22 => 'Cross River',
                23 => 'Kaduna',
                24 => 'Katsina',
                25 => 'Anambra',
                26 => 'Benue',
                27 => 'Borno',
                28 => 'Imo',
                29 => 'Kano',
                30 => 'Kwara',
                31 => 'Niger',
                32 => 'Oyo',
                35 => 'Adamawa',
                36 => 'Delta',
                37 => 'Edo',
                39 => 'Jigawa',
                40 => 'Kebbi',
                41 => 'Kogi',
                42 => 'Osun',
                43 => 'Taraba',
                44 => 'Yobe',
                45 => 'Abia',
                46 => 'Bauchi',
                47 => 'Enugu',
                48 => 'Ondo',
                49 => 'Plateau',
                50 => 'Rivers',
                51 => 'Sokoto',
                52 => 'Bayelsa',
                53 => 'Ebonyi',
                54 => 'Ekiti',
                55 => 'Gombe',
                56 => 'Nassarawa',
                57 => 'Zamfara'
            ),
            'NI' => array(
                '01' => 'Boaco',
                '02' => 'Carazo',
                '03' => 'Chinandega',
                '04' => 'Chontales',
                '05' => 'Esteli',
                '06' => 'Granada',
                '07' => 'Jinotega',
                '08' => 'Leon',
                '09' => 'Madriz',
                10 => 'Managua',
                11 => 'Masaya',
                12 => 'Matagalpa',
                13 => 'Nueva Segovia',
                14 => 'Rio San Juan',
                15 => 'Rivas',
                16 => 'Zelaya',
                17 => 'Autonoma Atlantico Norte',
                18 => 'Region Autonoma Atlantico Sur'
            ),
            'NL' => array(
                '01' => 'Drenthe',
                '02' => 'Friesland',
                '03' => 'Gelderland',
                '04' => 'Groningen',
                '05' => 'Limburg',
                '06' => 'Noord-Brabant',
                '07' => 'Noord-Holland',
                '09' => 'Utrecht',
                10 => 'Zeeland',
                11 => 'Zuid-Holland',
                15 => 'Overijssel',
                16 => 'Flevoland'
            ),
            'NO' => array(
                '01' => 'Akershus',
                '02' => 'Aust-Agder',
                '04' => 'Buskerud',
                '05' => 'Finnmark',
                '06' => 'Hedmark',
                '07' => 'Hordaland',
                '08' => 'More og Romsdal',
                '09' => 'Nordland',
                10 => 'Nord-Trondelag',
                11 => 'Oppland',
                12 => 'Oslo',
                13 => 'Ostfold',
                14 => 'Rogaland',
                15 => 'Sogn og Fjordane',
                16 => 'Sor-Trondelag',
                17 => 'Telemark',
                18 => 'Troms',
                19 => 'Vest-Agder',
                20 => 'Vestfold'
            ),
            'NP' => array(
                '01' => 'Bagmati',
                '02' => 'Bheri',
                '03' => 'Dhawalagiri',
                '04' => 'Gandaki',
                '05' => 'Janakpur',
                '06' => 'Karnali',
                '07' => 'Kosi',
                '08' => 'Lumbini',
                '09' => 'Mahakali',
                10 => 'Mechi',
                11 => 'Narayani',
                12 => 'Rapti',
                13 => 'Sagarmatha',
                14 => 'Seti'
            ),
            'NR' => array(
                '01' => 'Aiwo',
                '02' => 'Anabar',
                '03' => 'Anetan',
                '04' => 'Anibare',
                '05' => 'Baiti',
                '06' => 'Boe',
                '07' => 'Buada',
                '08' => 'Denigomodu',
                '09' => 'Ewa',
                10 => 'Ijuw',
                11 => 'Meneng',
                12 => 'Nibok',
                13 => 'Uaboe',
                14 => 'Yaren'
            ),
            'NZ' => array(
                10 => 'Chatham Islands',
                'E7' => 'Auckland',
                'E8' => 'Bay of Plenty',
                'E9' => 'Canterbury',
                'F1' => 'Gisborne',
                'F2' => 'Hawke\'s Bay',
                'F3' => 'Manawatu-Wanganui',
                'F4' => 'Marlborough',
                'F5' => 'Nelson',
                'F6' => 'Northland',
                'F7' => 'Otago',
                'F8' => 'Southland',
                'F9' => 'Taranaki',
                'G1' => 'Waikato',
                'G2' => 'Wellington',
                'G3' => 'West Coast'
            ),
            'OM' => array(
                '01' => 'Ad Dakhiliyah',
                '02' => 'Al Batinah',
                '03' => 'Al Wusta',
                '04' => 'Ash Sharqiyah',
                '05' => 'Az Zahirah',
                '06' => 'Masqat',
                '07' => 'Musandam',
                '08' => 'Zufar'
            ),
            'PA' => array(
                '01' => 'Bocas del Toro',
                '02' => 'Chiriqui',
                '03' => 'Cocle',
                '04' => 'Colon',
                '05' => 'Darien',
                '06' => 'Herrera',
                '07' => 'Los Santos',
                '08' => 'Panama',
                '09' => 'San Blas',
                10 => 'Veraguas'
            ),
            'PE' => array(
                '01' => 'Amazonas',
                '02' => 'Ancash',
                '03' => 'Apurimac',
                '04' => 'Arequipa',
                '05' => 'Ayacucho',
                '06' => 'Cajamarca',
                '07' => 'Callao',
                '08' => 'Cusco',
                '09' => 'Huancavelica',
                10 => 'Huanuco',
                11 => 'Ica',
                12 => 'Junin',
                13 => 'La Libertad',
                14 => 'Lambayeque',
                15 => 'Lima',
                16 => 'Loreto',
                17 => 'Madre de Dios',
                18 => 'Moquegua',
                19 => 'Pasco',
                20 => 'Piura',
                21 => 'Puno',
                22 => 'San Martin',
                23 => 'Tacna',
                24 => 'Tumbes',
                25 => 'Ucayali'
            ),
            'PG' => array(
                '01' => 'Central',
                '02' => 'Gulf',
                '03' => 'Milne Bay',
                '04' => 'Northern',
                '05' => 'Southern Highlands',
                '06' => 'Western',
                '07' => 'North Solomons',
                '08' => 'Chimbu',
                '09' => 'Eastern Highlands',
                10 => 'East New Britain',
                11 => 'East Sepik',
                12 => 'Madang',
                13 => 'Manus',
                14 => 'Morobe',
                15 => 'New Ireland',
                16 => 'Western Highlands',
                17 => 'West New Britain',
                18 => 'Sandaun',
                19 => 'Enga',
                20 => 'National Capital'
            ),
            'PH' => array(
                '01' => 'Abra',
                '02' => 'Agusan del Norte',
                '03' => 'Agusan del Sur',
                '04' => 'Aklan',
                '05' => 'Albay',
                '06' => 'Antique',
                '07' => 'Bataan',
                '08' => 'Batanes',
                '09' => 'Batangas',
                10 => 'Benguet',
                11 => 'Bohol',
                12 => 'Bukidnon',
                13 => 'Bulacan',
                14 => 'Cagayan',
                15 => 'Camarines Norte',
                16 => 'Camarines Sur',
                17 => 'Camiguin',
                18 => 'Capiz',
                19 => 'Catanduanes',
                20 => 'Cavite',
                21 => 'Cebu',
                22 => 'Basilan',
                23 => 'Eastern Samar',
                24 => 'Davao',
                25 => 'Davao del Sur',
                26 => 'Davao Oriental',
                27 => 'Ifugao',
                28 => 'Ilocos Norte',
                29 => 'Ilocos Sur',
                30 => 'Iloilo',
                31 => 'Isabela',
                32 => 'Kalinga-Apayao',
                33 => 'Laguna',
                34 => 'Lanao del Norte',
                35 => 'Lanao del Sur',
                36 => 'La Union',
                37 => 'Leyte',
                38 => 'Marinduque',
                39 => 'Masbate',
                40 => 'Mindoro Occidental',
                41 => 'Mindoro Oriental',
                42 => 'Misamis Occidental',
                43 => 'Misamis Oriental',
                44 => 'Mountain',
                'H3' => 'Negros Occidental',
                46 => 'Negros Oriental',
                47 => 'Nueva Ecija',
                48 => 'Nueva Vizcaya',
                49 => 'Palawan',
                50 => 'Pampanga',
                51 => 'Pangasinan',
                53 => 'Rizal',
                54 => 'Romblon',
                55 => 'Samar',
                56 => 'Maguindanao',
                57 => 'North Cotabato',
                58 => 'Sorsogon',
                59 => 'Southern Leyte',
                60 => 'Sulu',
                'N3' => 'Surigao del Norte',
                62 => 'Surigao del Sur',
                63 => 'Tarlac',
                'P1' => 'Zambales',
                65 => 'Zamboanga del Norte',
                66 => 'Zamboanga del Sur',
                67 => 'Northern Samar',
                68 => 'Quirino',
                69 => 'Siquijor',
                70 => 'South Cotabato',
                71 => 'Sultan Kudarat',
                72 => 'Tawitawi',
                'A1' => 'Angeles',
                'A2' => 'Bacolod',
                'A3' => 'Bago',
                'A4' => 'Baguio',
                'A5' => 'Bais',
                'A6' => 'Basilan City',
                'A7' => 'Batangas City',
                'A8' => 'Butuan',
                'A9' => 'Cabanatuan',
                'B1' => 'Cadiz',
                'B2' => 'Cagayan de Oro',
                'B3' => 'Calbayog',
                'B4' => 'Caloocan',
                'B5' => 'Canlaon',
                'B6' => 'Cavite City',
                'B7' => 'Cebu City',
                'B8' => 'Cotabato',
                'B9' => 'Dagupan',
                'C1' => 'Danao',
                'C2' => 'Dapitan',
                'C3' => 'Davao City',
                'C4' => 'Dipolog',
                'C5' => 'Dumaguete',
                'C6' => 'General Santos',
                'C7' => 'Gingoog',
                'C8' => 'Iligan',
                'C9' => 'Iloilo City',
                'D1' => 'Iriga',
                'D2' => 'La Carlota',
                'D3' => 'Laoag',
                'D4' => 'Lapu-Lapu',
                'D5' => 'Legaspi',
                'D6' => 'Lipa',
                'D7' => 'Lucena',
                'D8' => 'Mandaue',
                'D9' => 'Manila',
                'E1' => 'Marawi',
                'E2' => 'Naga',
                'E3' => 'Olongapo',
                'E4' => 'Ormoc',
                'E5' => 'Oroquieta',
                'E6' => 'Ozamis',
                'E7' => 'Pagadian',
                'E8' => 'Palayan',
                'E9' => 'Pasay',
                'F1' => 'Puerto Princesa',
                'F2' => 'Quezon City',
                'F3' => 'Roxas',
                'F5' => 'San Carlos',
                'F6' => 'San Jose',
                'F7' => 'San Pablo',
                'F8' => 'Silay',
                'F9' => 'Surigao',
                'G1' => 'Tacloban',
                'G2' => 'Tagaytay',
                'G3' => 'Tagbilaran',
                'G4' => 'Tangub',
                'G5' => 'Toledo',
                'G6' => 'Trece Martires',
                'P2' => 'Zamboanga',
                'G8' => 'Aurora',
                'H2' => 'Quezon',
                'H9' => 'Biliran',
                'I6' => 'Compostela Valley',
                'I7' => 'Davao del Norte',
                'J3' => 'Guimaras',
                'J4' => 'Himamaylan',
                'J7' => 'Kalinga',
                'K1' => 'Las Pinas',
                'K5' => 'Malabon',
                'K6' => 'Malaybalay',
                'L4' => 'Muntinlupa',
                'L5' => 'Navotas',
                'L7' => 'Paranaque',
                'L9' => 'Passi',
                'M5' => 'San Jose del Monte',
                'M6' => 'San Juan',
                'M8' => 'Santiago',
                'M9' => 'Sarangani',
                'N1' => 'Sipalay'
            ),
            'PK' => array(
                '01' => 'Federally Administered Tribal Areas',
                '02' => 'Balochistan',
                '03' => 'North-West Frontier',
                '04' => 'Punjab',
                '05' => 'Sindh',
                '06' => 'Azad Kashmir',
                '07' => 'Northern Areas',
                '08' => 'Islamabad'
            ),
            'PL' => array(
                72 => 'Dolnoslaskie',
                73 => 'Kujawsko-Pomorskie',
                74 => 'Lodzkie',
                75 => 'Lubelskie',
                76 => 'Lubuskie',
                77 => 'Malopolskie',
                78 => 'Mazowieckie',
                79 => 'Opolskie',
                80 => 'Podkarpackie',
                81 => 'Podlaskie',
                82 => 'Pomorskie',
                83 => 'Slaskie',
                84 => 'Swietokrzyskie',
                85 => 'Warminsko-Mazurskie',
                86 => 'Wielkopolskie',
                87 => 'Zachodniopomorskie'
            ),
            'PS' => array(
                'GZ' => 'Gaza',
                'WE' => 'West Bank'
            ),
            'PT' => array(
                '02' => 'Aveiro',
                '03' => 'Beja',
                '04' => 'Braga',
                '05' => 'Braganca',
                '06' => 'Castelo Branco',
                '07' => 'Coimbra',
                '08' => 'Evora',
                '09' => 'Faro',
                10 => 'Madeira',
                11 => 'Guarda',
                13 => 'Leiria',
                14 => 'Lisboa',
                16 => 'Portalegre',
                17 => 'Porto',
                18 => 'Santarem',
                19 => 'Setubal',
                20 => 'Viana do Castelo',
                21 => 'Vila Real',
                22 => 'Viseu',
                23 => 'Azores'
            ),
            'PY' => array(
                '01' => 'Alto Parana',
                '02' => 'Amambay',
                '04' => 'Caaguazu',
                '05' => 'Caazapa',
                '06' => 'Central',
                '07' => 'Concepcion',
                '08' => 'Cordillera',
                10 => 'Guaira',
                11 => 'Itapua',
                12 => 'Misiones',
                13 => 'Neembucu',
                15 => 'Paraguari',
                16 => 'Presidente Hayes',
                17 => 'San Pedro',
                19 => 'Canindeyu',
                22 => 'Asuncion',
                23 => 'Alto Paraguay',
                24 => 'Boqueron'
            ),
            'QA' => array(
                '01' => 'Ad Dawhah',
                '02' => 'Al Ghuwariyah',
                '03' => 'Al Jumaliyah',
                '04' => 'Al Khawr',
                '05' => 'Al Wakrah Municipality',
                '06' => 'Ar Rayyan',
                '08' => 'Madinat ach Shamal',
                '09' => 'Umm Salal',
                10 => 'Al Wakrah',
                11 => 'Jariyan al Batnah',
                12 => 'Umm Sa\'id'
            ),
            'RO' => array(
                '01' => 'Alba',
                '02' => 'Arad',
                '03' => 'Arges',
                '04' => 'Bacau',
                '05' => 'Bihor',
                '06' => 'Bistrita-Nasaud',
                '07' => 'Botosani',
                '08' => 'Braila',
                '09' => 'Brasov',
                10 => 'Bucuresti',
                11 => 'Buzau',
                12 => 'Caras-Severin',
                13 => 'Cluj',
                14 => 'Constanta',
                15 => 'Covasna',
                16 => 'Dambovita',
                17 => 'Dolj',
                18 => 'Galati',
                19 => 'Gorj',
                20 => 'Harghita',
                21 => 'Hunedoara',
                22 => 'Ialomita',
                23 => 'Iasi',
                25 => 'Maramures',
                26 => 'Mehedinti',
                27 => 'Mures',
                28 => 'Neamt',
                29 => 'Olt',
                30 => 'Prahova',
                31 => 'Salaj',
                32 => 'Satu Mare',
                33 => 'Sibiu',
                34 => 'Suceava',
                35 => 'Teleorman',
                36 => 'Timis',
                37 => 'Tulcea',
                38 => 'Vaslui',
                39 => 'Valcea',
                40 => 'Vrancea',
                41 => 'Calarasi',
                42 => 'Giurgiu',
                43 => 'Ilfov'
            ),
            'RS' => array(
                '01' => 'Kosovo',
                '02' => 'Vojvodina'
            ),
            'RU' => array(
                '01' => 'Adygeya',
                '02' => 'Aginsky Buryatsky AO',
                '03' => 'Gorno-Altay',
                '04' => 'Altaisky krai',
                '05' => 'Amur',
                '06' => 'Arkhangel\'sk',
                '07' => 'Astrakhan\'',
                '08' => 'Bashkortostan',
                '09' => 'Belgorod',
                10 => 'Bryansk',
                11 => 'Buryat',
                12 => 'Chechnya',
                13 => 'Chelyabinsk',
                14 => 'Chita',
                15 => 'Chukot',
                16 => 'Chuvashia',
                17 => 'Dagestan',
                18 => 'Evenk',
                19 => 'Ingush',
                20 => 'Irkutsk',
                21 => 'Ivanovo',
                22 => 'Kabardin-Balkar',
                23 => 'Kaliningrad',
                24 => 'Kalmyk',
                25 => 'Kaluga',
                26 => 'Kamchatka',
                27 => 'Karachay-Cherkess',
                28 => 'Karelia',
                29 => 'Kemerovo',
                30 => 'Khabarovsk',
                31 => 'Khakass',
                32 => 'Khanty-Mansiy',
                33 => 'Kirov',
                34 => 'Komi',
                36 => 'Koryak',
                37 => 'Kostroma',
                38 => 'Krasnodar',
                39 => 'Krasnoyarsk',
                40 => 'Kurgan',
                41 => 'Kursk',
                42 => 'Leningrad',
                43 => 'Lipetsk',
                44 => 'Magadan',
                45 => 'Mariy-El',
                46 => 'Mordovia',
                47 => 'Moskva',
                48 => 'Moscow City',
                49 => 'Murmansk',
                50 => 'Nenets',
                51 => 'Nizhegorod',
                52 => 'Novgorod',
                53 => 'Novosibirsk',
                54 => 'Omsk',
                55 => 'Orenburg',
                56 => 'Orel',
                57 => 'Penza',
                58 => 'Perm\'',
                59 => 'Primor\'ye',
                60 => 'Pskov',
                61 => 'Rostov',
                62 => 'Ryazan\'',
                63 => 'Sakha',
                64 => 'Sakhalin',
                65 => 'Samara',
                66 => 'Saint Petersburg City',
                67 => 'Saratov',
                68 => 'North Ossetia',
                69 => 'Smolensk',
                70 => 'Stavropol\'',
                71 => 'Sverdlovsk',
                72 => 'Tambovskaya oblast',
                73 => 'Tatarstan',
                74 => 'Taymyr',
                75 => 'Tomsk',
                76 => 'Tula',
                77 => 'Tver\'',
                78 => 'Tyumen\'',
                79 => 'Tuva',
                80 => 'Udmurt',
                81 => 'Ul\'yanovsk',
                83 => 'Vladimir',
                84 => 'Volgograd',
                85 => 'Vologda',
                86 => 'Voronezh',
                87 => 'Yamal-Nenets',
                88 => 'Yaroslavl\'',
                89 => 'Yevrey',
                90 => 'Permskiy Kray',
                91 => 'Krasnoyarskiy Kray',
                92 => 'Kamchatskiy Kray',
                93 => 'Zabaykal\'skiy Kray'
            ),
            'RW' => array(
                '01' => 'Butare',
                '06' => 'Gitarama',
                '07' => 'Kibungo',
                12 => 'Kigali',
                11 => 'Est',
                13 => 'Nord',
                14 => 'Ouest',
                15 => 'Sud'
            ),
            'SA' => array(
                '02' => 'Al Bahah',
                '05' => 'Al Madinah',
                '06' => 'Ash Sharqiyah',
                '08' => 'Al Qasim',
                10 => 'Ar Riyad',
                11 => 'Asir Province',
                13 => 'Ha\'il',
                14 => 'Makkah',
                15 => 'Al Hudud ash Shamaliyah',
                16 => 'Najran',
                17 => 'Jizan',
                19 => 'Tabuk',
                20 => 'Al Jawf'
            ),
            'SB' => array(
                '03' => 'Malaita',
                '06' => 'Guadalcanal',
                '07' => 'Isabel',
                '08' => 'Makira',
                '09' => 'Temotu',
                10 => 'Central',
                11 => 'Western',
                12 => 'Choiseul',
                13 => 'Rennell and Bellona'
            ),
            'SC' => array(
                '01' => 'Anse aux Pins',
                '02' => 'Anse Boileau',
                '03' => 'Anse Etoile',
                '04' => 'Anse Louis',
                '05' => 'Anse Royale',
                '06' => 'Baie Lazare',
                '07' => 'Baie Sainte Anne',
                '08' => 'Beau Vallon',
                '09' => 'Bel Air',
                10 => 'Bel Ombre',
                11 => 'Cascade',
                12 => 'Glacis',
                14 => 'Grand\' Anse',
                15 => 'La Digue',
                16 => 'La Riviere Anglaise',
                17 => 'Mont Buxton',
                18 => 'Mont Fleuri',
                19 => 'Plaisance',
                20 => 'Pointe La Rue',
                21 => 'Port Glaud',
                22 => 'Saint Louis',
                23 => 'Takamaka'
            ),
            'SD' => array(
                27 => 'Al Wusta',
                28 => 'Al Istiwa\'iyah',
                29 => 'Al Khartum',
                30 => 'Ash Shamaliyah',
                31 => 'Ash Sharqiyah',
                32 => 'Bahr al Ghazal',
                33 => 'Darfur',
                34 => 'Kurdufan',
                35 => 'Upper Nile',
                40 => 'Al Wahadah State',
                44 => 'Central Equatoria State',
                49 => 'Southern Darfur',
                50 => 'Southern Kordofan',
                52 => 'Kassala',
                53 => 'River Nile',
                55 => 'Northern Darfur'
            ),
            'SE' => array(
                '02' => 'Blekinge Lan',
                '03' => 'Gavleborgs Lan',
                '05' => 'Gotlands Lan',
                '06' => 'Hallands Lan',
                '07' => 'Jamtlands Lan',
                '08' => 'Jonkopings Lan',
                '09' => 'Kalmar Lan',
                10 => 'Dalarnas Lan',
                12 => 'Kronobergs Lan',
                14 => 'Norrbottens Lan',
                15 => 'Orebro Lan',
                16 => 'Ostergotlands Lan',
                18 => 'Sodermanlands Lan',
                21 => 'Uppsala Lan',
                22 => 'Varmlands Lan',
                23 => 'Vasterbottens Lan',
                24 => 'Vasternorrlands Lan',
                25 => 'Vastmanlands Lan',
                26 => 'Stockholms Lan',
                27 => 'Skane Lan',
                28 => 'Vastra Gotaland'
            ),
            'SH' => array(
                '01' => 'Ascension',
                '02' => 'Saint Helena',
                '03' => 'Tristan da Cunha'
            ),
            'SI' => array(
                '01' => 'Ajdovscina Commune',
                '02' => 'Beltinci Commune',
                '03' => 'Bled Commune',
                '04' => 'Bohinj Commune',
                '05' => 'Borovnica Commune',
                '06' => 'Bovec Commune',
                '07' => 'Brda Commune',
                '08' => 'Brezice Commune',
                '09' => 'Brezovica Commune',
                11 => 'Celje Commune',
                12 => 'Cerklje na Gorenjskem Commune',
                13 => 'Cerknica Commune',
                14 => 'Cerkno Commune',
                15 => 'Crensovci Commune',
                16 => 'Crna na Koroskem Commune',
                17 => 'Crnomelj Commune',
                19 => 'Divaca Commune',
                20 => 'Dobrepolje Commune',
                22 => 'Dol pri Ljubljani Commune',
                24 => 'Dornava Commune',
                25 => 'Dravograd Commune',
                26 => 'Duplek Commune',
                27 => 'Gorenja vas-Poljane Commune',
                28 => 'Gorisnica Commune',
                29 => 'Gornja Radgona Commune',
                30 => 'Gornji Grad Commune',
                31 => 'Gornji Petrovci Commune',
                32 => 'Grosuplje Commune',
                34 => 'Hrastnik Commune',
                35 => 'Hrpelje-Kozina Commune',
                36 => 'Idrija Commune',
                37 => 'Ig Commune',
                38 => 'Ilirska Bistrica Commune',
                39 => 'Ivancna Gorica Commune',
                40 => 'Izola-Isola Commune',
                42 => 'Jursinci Commune',
                44 => 'Kanal Commune',
                45 => 'Kidricevo Commune',
                46 => 'Kobarid Commune',
                47 => 'Kobilje Commune',
                49 => 'Komen Commune',
                50 => 'Koper-Capodistria Urban Commune',
                51 => 'Kozje Commune',
                52 => 'Kranj Commune',
                53 => 'Kranjska Gora Commune',
                54 => 'Krsko Commune',
                55 => 'Kungota Commune',
                57 => 'Lasko Commune',
                61 => 'Ljubljana Urban Commune',
                62 => 'Ljubno Commune',
                64 => 'Logatec Commune',
                66 => 'Loski Potok Commune',
                68 => 'Lukovica Commune',
                71 => 'Medvode Commune',
                72 => 'Menges Commune',
                73 => 'Metlika Commune',
                74 => 'Mezica Commune',
                76 => 'Mislinja Commune',
                77 => 'Moravce Commune',
                78 => 'Moravske Toplice Commune',
                79 => 'Mozirje Commune',
                80 => 'Murska Sobota Urban Commune',
                81 => 'Muta Commune',
                82 => 'Naklo Commune',
                83 => 'Nazarje Commune',
                84 => 'Nova Gorica Urban Commune',
                86 => 'Odranci Commune',
                87 => 'Ormoz Commune',
                88 => 'Osilnica Commune',
                89 => 'Pesnica Commune',
                91 => 'Pivka Commune',
                92 => 'Podcetrtek Commune',
                94 => 'Postojna Commune',
                97 => 'Puconci Commune',
                98 => 'Race-Fram Commune',
                99 => 'Radece Commune',
                'A1' => 'Radenci Commune',
                'A2' => 'Radlje ob Dravi Commune',
                'A3' => 'Radovljica Commune',
                'A6' => 'Rogasovci Commune',
                'A7' => 'Rogaska Slatina Commune',
                'A8' => 'Rogatec Commune',
                'B1' => 'Semic Commune',
                'B2' => 'Sencur Commune',
                'B3' => 'Sentilj Commune',
                'B4' => 'Sentjernej Commune',
                'B6' => 'Sevnica Commune',
                'B7' => 'Sezana Commune',
                'B8' => 'Skocjan Commune',
                'B9' => 'Skofja Loka Commune',
                'C1' => 'Skofljica Commune',
                'C2' => 'Slovenj Gradec Urban Commune',
                'C4' => 'Slovenske Konjice Commune',
                'C5' => 'Smarje pri Jelsah Commune',
                'C6' => 'Smartno ob Paki Commune',
                'C7' => 'Sostanj Commune',
                'C8' => 'Starse Commune',
                'C9' => 'Store Commune',
                'D1' => 'Sveti Jurij Commune',
                'D2' => 'Tolmin Commune',
                'D3' => 'Trbovlje Commune',
                'D4' => 'Trebnje Commune',
                'D5' => 'Trzic Commune',
                'D6' => 'Turnisce Commune',
                'D7' => 'Velenje Urban Commune',
                'D8' => 'Velike Lasce Commune',
                'E1' => 'Vipava Commune',
                'E2' => 'Vitanje Commune',
                'E3' => 'Vodice Commune',
                'E5' => 'Vrhnika Commune',
                'E6' => 'Vuzenica Commune',
                'E7' => 'Zagorje ob Savi Commune',
                'E9' => 'Zavrc Commune',
                'F1' => 'Zelezniki Commune',
                'F2' => 'Ziri Commune',
                'F3' => 'Zrece Commune',
                'F4' => 'Benedikt Commune',
                'F5' => 'Bistrica ob Sotli Commune',
                'F6' => 'Bloke Commune',
                'F7' => 'Braslovce Commune',
                'F8' => 'Cankova Commune',
                'F9' => 'Cerkvenjak Commune',
                'G1' => 'Destrnik Commune',
                'G2' => 'Dobje Commune',
                'G3' => 'Dobrna Commune',
                'G4' => 'Dobrova-Horjul-Polhov Gradec Commune',
                'G5' => 'Dobrovnik-Dobronak Commune',
                'G6' => 'Dolenjske Toplice Commune',
                'G7' => 'Domzale Commune',
                'G8' => 'Grad Commune',
                'G9' => 'Hajdina Commune',
                'H1' => 'Hoce-Slivnica Commune',
                'H2' => 'Hodos-Hodos Commune',
                'H3' => 'Horjul Commune',
                'H4' => 'Jesenice Commune',
                'H5' => 'Jezersko Commune',
                'H6' => 'Kamnik Commune',
                'H7' => 'Kocevje Commune',
                'H8' => 'Komenda Commune',
                'H9' => 'Kostel Commune',
                'I1' => 'Krizevci Commune',
                'I2' => 'Kuzma Commune',
                'I3' => 'Lenart Commune',
                'I4' => 'Lendava-Lendva Commune',
                'I5' => 'Litija Commune',
                'I6' => 'Ljutomer Commune',
                'I7' => 'Loska Dolina Commune',
                'I8' => 'Lovrenc na Pohorju Commune',
                'I9' => 'Luce Commune',
                'J1' => 'Majsperk Commune',
                'J2' => 'Maribor Commune',
                'J3' => 'Markovci Commune',
                'J4' => 'Miklavz na Dravskem polju Commune',
                'J5' => 'Miren-Kostanjevica Commune',
                'J6' => 'Mirna Pec Commune',
                'J7' => 'Novo mesto Urban Commune',
                'J8' => 'Oplotnica Commune',
                'J9' => 'Piran-Pirano Commune',
                'K1' => 'Podlehnik Commune',
                'K2' => 'Podvelka Commune',
                'K3' => 'Polzela Commune',
                'K4' => 'Prebold Commune',
                'K5' => 'Preddvor Commune',
                'K6' => 'Prevalje Commune',
                'K7' => 'Ptuj Urban Commune',
                'K8' => 'Ravne na Koroskem Commune',
                'K9' => 'Razkrizje Commune',
                'L1' => 'Ribnica Commune',
                'L2' => 'Ribnica na Pohorju Commune',
                'L3' => 'Ruse Commune',
                'L4' => 'Salovci Commune',
                'L5' => 'Selnica ob Dravi Commune',
                'L6' => 'Sempeter-Vrtojba Commune',
                'L7' => 'Sentjur pri Celju Commune',
                'L8' => 'Slovenska Bistrica Commune',
                'L9' => 'Smartno pri Litiji Commune',
                'M1' => 'Sodrazica Commune',
                'M2' => 'Solcava Commune',
                'M3' => 'Sveta Ana Commune',
                'M4' => 'Sveti Andraz v Slovenskih goricah Commune',
                'M5' => 'Tabor Commune',
                'M6' => 'Tisina Commune',
                'M7' => 'Trnovska vas Commune',
                'M8' => 'Trzin Commune',
                'M9' => 'Velika Polana Commune',
                'N1' => 'Verzej Commune',
                'N2' => 'Videm Commune',
                'N3' => 'Vojnik Commune',
                'N4' => 'Vransko Commune',
                'N5' => 'Zalec Commune',
                'N6' => 'Zetale Commune',
                'N7' => 'Zirovnica Commune',
                'N8' => 'Zuzemberk Commune',
                'N9' => 'Apace Commune',
                'O1' => 'Cirkulane Commune',
                'O2' => 'Gorje',
                'O3' => 'Kostanjevica na Krki',
                'O4' => 'Log-Dragomer',
                'O5' => 'Makole',
                'O6' => 'Mirna',
                'O7' => 'Mokronog-Trebelno',
                'O8' => 'Poljcane',
                'O9' => 'Recica ob Savinji',
                'P1' => 'Rence-Vogrsko',
                'P2' => 'Sentrupert',
                'P3' => 'Smarjesk Toplice',
                'P4' => 'Sredisce ob Dravi',
                'P5' => 'Straza',
                'P7' => 'Sveti Jurij v Slovenskih Goricah'
            ),
            'SK' => array(
                '01' => 'Banska Bystrica',
                '02' => 'Bratislava',
                '03' => 'Kosice',
                '04' => 'Nitra',
                '05' => 'Presov',
                '06' => 'Trencin',
                '07' => 'Trnava',
                '08' => 'Zilina'
            ),
            'SL' => array(
                '01' => 'Eastern',
                '02' => 'Northern',
                '03' => 'Southern',
                '04' => 'Western Area'
            ),
            'SM' => array(
                '01' => 'Acquaviva',
                '02' => 'Chiesanuova',
                '03' => 'Domagnano',
                '04' => 'Faetano',
                '05' => 'Fiorentino',
                '06' => 'Borgo Maggiore',
                '07' => 'San Marino',
                '08' => 'Monte Giardino',
                '09' => 'Serravalle'
            ),
            'SN' => array(
                '01' => 'Dakar',
                '03' => 'Diourbel',
                '05' => 'Tambacounda',
                '07' => 'Thies',
                '09' => 'Fatick',
                10 => 'Kaolack',
                11 => 'Kolda',
                12 => 'Ziguinchor',
                13 => 'Louga',
                14 => 'Saint-Louis',
                15 => 'Matam'
            ),
            'SO' => array(
                '01' => 'Bakool',
                '02' => 'Banaadir',
                '03' => 'Bari',
                '04' => 'Bay',
                '05' => 'Galguduud',
                '06' => 'Gedo',
                '07' => 'Hiiraan',
                '08' => 'Jubbada Dhexe',
                '09' => 'Jubbada Hoose',
                10 => 'Mudug',
                18 => 'Nugaal',
                12 => 'Sanaag',
                13 => 'Shabeellaha Dhexe',
                14 => 'Shabeellaha Hoose',
                20 => 'Woqooyi Galbeed',
                19 => 'Togdheer',
                21 => 'Awdal',
                22 => 'Sool'
            ),
            'SR' => array(
                10 => 'Brokopondo',
                11 => 'Commewijne',
                12 => 'Coronie',
                13 => 'Marowijne',
                14 => 'Nickerie',
                15 => 'Para',
                16 => 'Paramaribo',
                17 => 'Saramacca',
                18 => 'Sipaliwini',
                19 => 'Wanica'
            ),
            'SS' => array(
                '01' => 'Central Equatoria',
                '02' => 'Eastern Equatoria',
                '03' => 'Jonglei',
                '04' => 'Lakes',
                '05' => 'Northern Bahr el Ghazal',
                '06' => 'Unity',
                '07' => 'Upper Nile',
                '08' => 'Warrap',
                '09' => 'Western Bahr el Ghazal',
                10 => 'Western Equatoria'
            ),
            'ST' => array(
                '01' => 'Principe',
                '02' => 'Sao Tome'
            ),
            'SV' => array(
                '01' => 'Ahuachapan',
                '02' => 'Cabanas',
                '03' => 'Chalatenango',
                '04' => 'Cuscatlan',
                '05' => 'La Libertad',
                '06' => 'La Paz',
                '07' => 'La Union',
                '08' => 'Morazan',
                '09' => 'San Miguel',
                10 => 'San Salvador',
                11 => 'Santa Ana',
                12 => 'San Vicente',
                13 => 'Sonsonate',
                14 => 'Usulutan'
            ),
            'SY' => array(
                '01' => 'Al Hasakah',
                '02' => 'Al Ladhiqiyah',
                '03' => 'Al Qunaytirah',
                '04' => 'Ar Raqqah',
                '05' => 'As Suwayda\'',
                '06' => 'Dar',
                '07' => 'Dayr az Zawr',
                '08' => 'Rif Dimashq',
                '09' => 'Halab',
                10 => 'Hamah',
                11 => 'Hims',
                12 => 'Idlib',
                13 => 'Dimashq',
                14 => 'Tartus'
            ),
            'SZ' => array(
                '01' => 'Hhohho',
                '02' => 'Lubombo',
                '03' => 'Manzini',
                '04' => 'Shiselweni',
                '05' => 'Praslin'
            ),
            'TD' => array(
                '01' => 'Batha',
                '02' => 'Biltine',
                '03' => 'Borkou-Ennedi-Tibesti',
                '04' => 'Chari-Baguirmi',
                '05' => 'Guera',
                '06' => 'Kanem',
                '07' => 'Lac',
                '08' => 'Logone Occidental',
                '09' => 'Logone Oriental',
                10 => 'Mayo-Kebbi',
                11 => 'Moyen-Chari',
                12 => 'Ouaddai',
                13 => 'Salamat',
                14 => 'Tandjile'
            ),
            'TG' => array(
                22 => 'Centrale',
                23 => 'Kara',
                24 => 'Maritime',
                25 => 'Plateaux',
                26 => 'Savanes'
            ),
            'TH' => array(
                '01' => 'Mae Hong Son',
                '02' => 'Chiang Mai',
                '03' => 'Chiang Rai',
                '04' => 'Nan',
                '05' => 'Lamphun',
                '06' => 'Lampang',
                '07' => 'Phrae',
                '08' => 'Tak',
                '09' => 'Sukhothai',
                10 => 'Uttaradit',
                11 => 'Kamphaeng Phet',
                12 => 'Phitsanulok',
                13 => 'Phichit',
                14 => 'Phetchabun',
                15 => 'Uthai Thani',
                16 => 'Nakhon Sawan',
                17 => 'Nong Khai',
                18 => 'Loei',
                20 => 'Sakon Nakhon',
                73 => 'Nakhon Phanom',
                22 => 'Khon Kaen',
                23 => 'Kalasin',
                24 => 'Maha Sarakham',
                25 => 'Roi Et',
                26 => 'Chaiyaphum',
                27 => 'Nakhon Ratchasima',
                28 => 'Buriram',
                29 => 'Surin',
                30 => 'Sisaket',
                31 => 'Narathiwat',
                32 => 'Chai Nat',
                33 => 'Sing Buri',
                34 => 'Lop Buri',
                35 => 'Ang Thong',
                36 => 'Phra Nakhon Si Ayutthaya',
                37 => 'Saraburi',
                38 => 'Nonthaburi',
                39 => 'Pathum Thani',
                40 => 'Krung Thep',
                41 => 'Phayao',
                42 => 'Samut Prakan',
                43 => 'Nakhon Nayok',
                44 => 'Chachoengsao',
                74 => 'Prachin Buri',
                46 => 'Chon Buri',
                47 => 'Rayong',
                48 => 'Chanthaburi',
                49 => 'Trat',
                50 => 'Kanchanaburi',
                51 => 'Suphan Buri',
                52 => 'Ratchaburi',
                53 => 'Nakhon Pathom',
                54 => 'Samut Songkhram',
                55 => 'Samut Sakhon',
                56 => 'Phetchaburi',
                57 => 'Prachuap Khiri Khan',
                58 => 'Chumphon',
                59 => 'Ranong',
                60 => 'Surat Thani',
                61 => 'Phangnga',
                62 => 'Phuket',
                63 => 'Krabi',
                64 => 'Nakhon Si Thammarat',
                65 => 'Trang',
                66 => 'Phatthalung',
                67 => 'Satun',
                68 => 'Songkhla',
                69 => 'Pattani',
                70 => 'Yala',
                75 => 'Ubon Ratchathani',
                72 => 'Yasothon',
                76 => 'Udon Thani',
                77 => 'Amnat Charoen',
                78 => 'Mukdahan',
                79 => 'Nong Bua Lamphu',
                80 => 'Sa Kaeo',
                81 => 'Bueng Kan'
            ),
            'TJ' => array(
                '01' => 'Kuhistoni Badakhshon',
                '02' => 'Khatlon',
                '03' => 'Sughd',
                '04' => 'Dushanbe',
                '05' => 'Nohiyahoi Tobei Jumhuri'
            ),
            'TL' => array(
                '06' => 'Dili'
            ),
            'TM' => array(
                '01' => 'Ahal',
                '02' => 'Balkan',
                '03' => 'Dashoguz',
                '04' => 'Lebap',
                '05' => 'Mary'
            ),
            'TN' => array(
                '02' => 'Kasserine',
                '03' => 'Kairouan',
                '06' => 'Jendouba',
                10 => 'Qafsah',
                14 => 'El Kef',
                15 => 'Al Mahdia',
                16 => 'Al Munastir',
                17 => 'Bajah',
                18 => 'Bizerte',
                19 => 'Nabeul',
                22 => 'Siliana',
                23 => 'Sousse',
                27 => 'Ben Arous',
                28 => 'Madanin',
                29 => 'Gabes',
                31 => 'Kebili',
                32 => 'Sfax',
                33 => 'Sidi Bou Zid',
                34 => 'Tataouine',
                35 => 'Tozeur',
                36 => 'Tunis',
                37 => 'Zaghouan',
                38 => 'Aiana',
                39 => 'Manouba'
            ),
            'TO' => array(
                '01' => 'Ha',
                '02' => 'Tongatapu',
                '03' => 'Vava'
            ),
            'TR' => array(
                '02' => 'Adiyaman',
                '03' => 'Afyonkarahisar',
                '04' => 'Agri',
                '05' => 'Amasya',
                '07' => 'Antalya',
                '08' => 'Artvin',
                '09' => 'Aydin',
                10 => 'Balikesir',
                11 => 'Bilecik',
                12 => 'Bingol',
                13 => 'Bitlis',
                14 => 'Bolu',
                15 => 'Burdur',
                16 => 'Bursa',
                17 => 'Canakkale',
                19 => 'Corum',
                20 => 'Denizli',
                21 => 'Diyarbakir',
                22 => 'Edirne',
                23 => 'Elazig',
                24 => 'Erzincan',
                25 => 'Erzurum',
                26 => 'Eskisehir',
                28 => 'Giresun',
                31 => 'Hatay',
                32 => 'Mersin',
                33 => 'Isparta',
                34 => 'Istanbul',
                35 => 'Izmir',
                37 => 'Kastamonu',
                38 => 'Kayseri',
                39 => 'Kirklareli',
                40 => 'Kirsehir',
                41 => 'Kocaeli',
                43 => 'Kutahya',
                44 => 'Malatya',
                45 => 'Manisa',
                46 => 'Kahramanmaras',
                48 => 'Mugla',
                49 => 'Mus',
                50 => 'Nevsehir',
                52 => 'Ordu',
                53 => 'Rize',
                54 => 'Sakarya',
                55 => 'Samsun',
                57 => 'Sinop',
                58 => 'Sivas',
                59 => 'Tekirdag',
                60 => 'Tokat',
                61 => 'Trabzon',
                62 => 'Tunceli',
                63 => 'Sanliurfa',
                64 => 'Usak',
                65 => 'Van',
                66 => 'Yozgat',
                68 => 'Ankara',
                69 => 'Gumushane',
                70 => 'Hakkari',
                71 => 'Konya',
                72 => 'Mardin',
                73 => 'Nigde',
                74 => 'Siirt',
                75 => 'Aksaray',
                76 => 'Batman',
                77 => 'Bayburt',
                78 => 'Karaman',
                79 => 'Kirikkale',
                80 => 'Sirnak',
                81 => 'Adana',
                82 => 'Cankiri',
                83 => 'Gaziantep',
                84 => 'Kars',
                85 => 'Zonguldak',
                86 => 'Ardahan',
                87 => 'Bartin',
                88 => 'Igdir',
                89 => 'Karabuk',
                90 => 'Kilis',
                91 => 'Osmaniye',
                92 => 'Yalova',
                93 => 'Duzce'
            ),
            'TT' => array(
                '01' => 'Arima',
                '02' => 'Caroni',
                '03' => 'Mayaro',
                '04' => 'Nariva',
                '05' => 'Port-of-Spain',
                '06' => 'Saint Andrew',
                '07' => 'Saint David',
                '08' => 'Saint George',
                '09' => 'Saint Patrick',
                10 => 'San Fernando',
                11 => 'Tobago',
                12 => 'Victoria'
            ),
            'TW' => array(
                '01' => 'Fu-chien',
                '02' => 'Kao-hsiung',
                '03' => 'T\'ai-pei',
                '04' => 'T\'ai-wan'
            ),
            'TZ' => array(
                '02' => 'Pwani',
                '03' => 'Dodoma',
                '04' => 'Iringa',
                '05' => 'Kigoma',
                '06' => 'Kilimanjaro',
                '07' => 'Lindi',
                '08' => 'Mara',
                '09' => 'Mbeya',
                10 => 'Morogoro',
                11 => 'Mtwara',
                12 => 'Mwanza',
                13 => 'Pemba North',
                14 => 'Ruvuma',
                15 => 'Shinyanga',
                16 => 'Singida',
                17 => 'Tabora',
                18 => 'Tanga',
                19 => 'Kagera',
                20 => 'Pemba South',
                21 => 'Zanzibar Central',
                22 => 'Zanzibar North',
                23 => 'Dar es Salaam',
                24 => 'Rukwa',
                25 => 'Zanzibar Urban',
                26 => 'Arusha',
                27 => 'Manyara'
            ),
            'UA' => array(
                '01' => 'Cherkas\'ka Oblast\'',
                '02' => 'Chernihivs\'ka Oblast\'',
                '03' => 'Chernivets\'ka Oblast\'',
                '04' => 'Dnipropetrovs\'ka Oblast\'',
                '05' => 'Donets\'ka Oblast\'',
                '06' => 'Ivano-Frankivs\'ka Oblast\'',
                '07' => 'Kharkivs\'ka Oblast\'',
                '08' => 'Khersons\'ka Oblast\'',
                '09' => 'Khmel\'nyts\'ka Oblast\'',
                10 => 'Kirovohrads\'ka Oblast\'',
                11 => 'Krym',
                12 => 'Kyyiv',
                13 => 'Kyyivs\'ka Oblast\'',
                14 => 'Luhans\'ka Oblast\'',
                15 => 'L\'vivs\'ka Oblast\'',
                16 => 'Mykolayivs\'ka Oblast\'',
                17 => 'Odes\'ka Oblast\'',
                18 => 'Poltavs\'ka Oblast\'',
                19 => 'Rivnens\'ka Oblast\'',
                20 => 'Sevastopol\'',
                21 => 'Sums\'ka Oblast\'',
                22 => 'Ternopil\'s\'ka Oblast\'',
                23 => 'Vinnyts\'ka Oblast\'',
                24 => 'Volyns\'ka Oblast\'',
                25 => 'Zakarpats\'ka Oblast\'',
                26 => 'Zaporiz\'ka Oblast\'',
                27 => 'Zhytomyrs\'ka Oblast\''
            ),
            'UG' => array(
                26 => 'Apac',
                28 => 'Bundibugyo',
                29 => 'Bushenyi',
                30 => 'Gulu',
                31 => 'Hoima',
                33 => 'Jinja',
                36 => 'Kalangala',
                37 => 'Kampala',
                38 => 'Kamuli',
                39 => 'Kapchorwa',
                40 => 'Kasese',
                41 => 'Kibale',
                42 => 'Kiboga',
                43 => 'Kisoro',
                45 => 'Kotido',
                46 => 'Kumi',
                47 => 'Lira',
                50 => 'Masindi',
                52 => 'Mbarara',
                56 => 'Mubende',
                58 => 'Nebbi',
                59 => 'Ntungamo',
                60 => 'Pallisa',
                61 => 'Rakai',
                65 => 'Adjumani',
                66 => 'Bugiri',
                67 => 'Busia',
                69 => 'Katakwi',
                70 => 'Luwero',
                71 => 'Masaka',
                72 => 'Moyo',
                73 => 'Nakasongola',
                74 => 'Sembabule',
                76 => 'Tororo',
                77 => 'Arua',
                78 => 'Iganga',
                79 => 'Kabarole',
                80 => 'Kaberamaido',
                81 => 'Kamwenge',
                82 => 'Kanungu',
                83 => 'Kayunga',
                84 => 'Kitgum',
                85 => 'Kyenjojo',
                86 => 'Mayuge',
                87 => 'Mbale',
                88 => 'Moroto',
                89 => 'Mpigi',
                90 => 'Mukono',
                91 => 'Nakapiripirit',
                92 => 'Pader',
                93 => 'Rukungiri',
                94 => 'Sironko',
                95 => 'Soroti',
                96 => 'Wakiso',
                97 => 'Yumbe'
            ),
            'US' => array(
                'AA' => 'Armed Forces Americas',
                'AE' => 'Armed Forces Europe',
                'AK' => 'Alaska',
                'AL' => 'Alabama',
                'AP' => 'Armed Forces Pacific',
                'AR' => 'Arkansas',
                'AS' => 'American Samoa',
                'AZ' => 'Arizona',
                'CA' => 'California',
                'CO' => 'Colorado',
                'CT' => 'Connecticut',
                'DC' => 'District of Columbia',
                'DE' => 'Delaware',
                'FL' => 'Florida',
                'FM' => 'Federated States of Micronesia',
                'GA' => 'Georgia',
                'GU' => 'Guam',
                'HI' => 'Hawaii',
                'IA' => 'Iowa',
                'ID' => 'Idaho',
                'IL' => 'Illinois',
                'IN' => 'Indiana',
                'KS' => 'Kansas',
                'KY' => 'Kentucky',
                'LA' => 'Louisiana',
                'MA' => 'Massachusetts',
                'MD' => 'Maryland',
                'ME' => 'Maine',
                'MH' => 'Marshall Islands',
                'MI' => 'Michigan',
                'MN' => 'Minnesota',
                'MO' => 'Missouri',
                'MP' => 'Northern Mariana Islands',
                'MS' => 'Mississippi',
                'MT' => 'Montana',
                'NC' => 'North Carolina',
                'ND' => 'North Dakota',
                'NE' => 'Nebraska',
                'NH' => 'New Hampshire',
                'NJ' => 'New Jersey',
                'NM' => 'New Mexico',
                'NV' => 'Nevada',
                'NY' => 'New York',
                'OH' => 'Ohio',
                'OK' => 'Oklahoma',
                'OR' => 'Oregon',
                'PA' => 'Pennsylvania',
                'PW' => 'Palau',
                'RI' => 'Rhode Island',
                'SC' => 'South Carolina',
                'SD' => 'South Dakota',
                'TN' => 'Tennessee',
                'TX' => 'Texas',
                'UT' => 'Utah',
                'VA' => 'Virginia',
                'VI' => 'Virgin Islands',
                'VT' => 'Vermont',
                'WA' => 'Washington',
                'WI' => 'Wisconsin',
                'WV' => 'West Virginia',
                'WY' => 'Wyoming'
            ),
            'UY' => array(
                '01' => 'Artigas',
                '02' => 'Canelones',
                '03' => 'Cerro Largo',
                '04' => 'Colonia',
                '05' => 'Durazno',
                '06' => 'Flores',
                '07' => 'Florida',
                '08' => 'Lavalleja',
                '09' => 'Maldonado',
                10 => 'Montevideo',
                11 => 'Paysandu',
                12 => 'Rio Negro',
                13 => 'Rivera',
                14 => 'Rocha',
                15 => 'Salto',
                16 => 'San Jose',
                17 => 'Soriano',
                18 => 'Tacuarembo',
                19 => 'Treinta y Tres'
            ),
            'UZ' => array(
                '01' => 'Andijon',
                '02' => 'Bukhoro',
                '03' => 'Farghona',
                '04' => 'Jizzakh',
                '05' => 'Khorazm',
                '06' => 'Namangan',
                '07' => 'Nawoiy',
                '08' => 'Qashqadaryo',
                '09' => 'Qoraqalpoghiston',
                10 => 'Samarqand',
                11 => 'Sirdaryo',
                12 => 'Surkhondaryo',
                14 => 'Toshkent',
                15 => 'Jizzax'
            ),
            'VC' => array(
                '01' => 'Charlotte',
                '02' => 'Saint Andrew',
                '03' => 'Saint David',
                '04' => 'Saint George',
                '05' => 'Saint Patrick',
                '06' => 'Grenadines'
            ),
            'VE' => array(
                '01' => 'Amazonas',
                '02' => 'Anzoategui',
                '03' => 'Apure',
                '04' => 'Aragua',
                '05' => 'Barinas',
                '06' => 'Bolivar',
                '07' => 'Carabobo',
                '08' => 'Cojedes',
                '09' => 'Delta Amacuro',
                11 => 'Falcon',
                12 => 'Guarico',
                13 => 'Lara',
                14 => 'Merida',
                15 => 'Miranda',
                16 => 'Monagas',
                17 => 'Nueva Esparta',
                18 => 'Portuguesa',
                19 => 'Sucre',
                20 => 'Tachira',
                21 => 'Trujillo',
                22 => 'Yaracuy',
                23 => 'Zulia',
                24 => 'Dependencias Federales',
                25 => 'Distrito Federal',
                26 => 'Vargas'
            ),
            'VN' => array(
                '01' => 'An Giang',
                '03' => 'Ben Tre',
                '05' => 'Cao Bang',
                '09' => 'Dong Thap',
                13 => 'Hai Phong',
                20 => 'Ho Chi Minh',
                21 => 'Kien Giang',
                23 => 'Lam Dong',
                24 => 'Long An',
                30 => 'Quang Ninh',
                32 => 'Son La',
                33 => 'Tay Ninh',
                34 => 'Thanh Hoa',
                35 => 'Thai Binh',
                37 => 'Tien Giang',
                39 => 'Lang Son',
                43 => 'Dong Nai',
                44 => 'Ha Noi',
                45 => 'Ba Ria-Vung Tau',
                46 => 'Binh Dinh',
                47 => 'Binh Thuan',
                49 => 'Gia Lai',
                50 => 'Ha Giang',
                52 => 'Ha Tinh',
                53 => 'Hoa Binh',
                54 => 'Khanh Hoa',
                55 => 'Kon Tum',
                58 => 'Nghe An',
                59 => 'Ninh Binh',
                60 => 'Ninh Thuan',
                61 => 'Phu Yen',
                62 => 'Quang Binh',
                63 => 'Quang Ngai',
                64 => 'Quang Tri',
                65 => 'Soc Trang',
                66 => 'Thua Thien-Hue',
                67 => 'Tra Vinh',
                68 => 'Tuyen Quang',
                69 => 'Vinh Long',
                70 => 'Yen Bai',
                71 => 'Bac Giang',
                72 => 'Bac Kan',
                73 => 'Bac Lieu',
                74 => 'Bac Ninh',
                75 => 'Binh Duong',
                76 => 'Binh Phuoc',
                77 => 'Ca Mau',
                78 => 'Da Nang',
                79 => 'Hai Duong',
                80 => 'Ha Nam',
                81 => 'Hung Yen',
                82 => 'Nam Dinh',
                83 => 'Phu Tho',
                84 => 'Quang Nam',
                85 => 'Thai Nguyen',
                86 => 'Vinh Phuc',
                87 => 'Can Tho',
                88 => 'Dac Lak',
                89 => 'Lai Chau',
                90 => 'Lao Cai',
                91 => 'Dak Nong',
                92 => 'Dien Bien',
                93 => 'Hau Giang'
            ),
            'VU' => array(
                '05' => 'Ambrym',
                '06' => 'Aoba',
                '07' => 'Torba',
                '08' => 'Efate',
                '09' => 'Epi',
                10 => 'Malakula',
                11 => 'Paama',
                12 => 'Pentecote',
                13 => 'Sanma',
                14 => 'Shepherd',
                15 => 'Tafea',
                16 => 'Malampa',
                17 => 'Penama',
                18 => 'Shefa'
            ),
            'WS' => array(
                '02' => 'Aiga-i-le-Tai',
                '03' => 'Atua',
                '04' => 'Fa',
                '05' => 'Gaga',
                '06' => 'Va',
                '07' => 'Gagaifomauga',
                '08' => 'Palauli',
                '09' => 'Satupa',
                10 => 'Tuamasaga',
                11 => 'Vaisigano'
            ),
            'YE' => array(
                '01' => 'Abyan',
                '02' => 'Adan',
                '03' => 'Al Mahrah',
                '04' => 'Hadramawt',
                '05' => 'Shabwah',
                24 => 'Lahij',
                20 => 'Al Bayda\'',
                '08' => 'Al Hudaydah',
                21 => 'Al Jawf',
                10 => 'Al Mahwit',
                11 => 'Dhamar',
                22 => 'Hajjah',
                23 => 'Ibb',
                14 => 'Ma\'rib',
                15 => 'Sa\'dah',
                16 => 'San\'a\'',
                25 => 'Taizz',
                18 => 'Ad Dali',
                19 => 'Amran'
            ),
            'ZA' => array(
                '01' => 'North-Western Province',
                '02' => 'KwaZulu-Natal',
                '03' => 'Free State',
                '05' => 'Eastern Cape',
                '06' => 'Gauteng',
                '07' => 'Mpumalanga',
                '08' => 'Northern Cape',
                '09' => 'Limpopo',
                10 => 'North-West',
                11 => 'Western Cape'
            ),
            'ZM' => array(
                '01' => 'Western',
                '02' => 'Central',
                '03' => 'Eastern',
                '04' => 'Luapula',
                '05' => 'Northern',
                '06' => 'North-Western',
                '07' => 'Southern',
                '08' => 'Copperbelt',
                '09' => 'Lusaka'
            ),
            'ZW' => array(
                '01' => 'Manicaland',
                '02' => 'Midlands',
                '03' => 'Mashonaland Central',
                '04' => 'Mashonaland East',
                '05' => 'Mashonaland West',
                '06' => 'Matabeleland North',
                '07' => 'Matabeleland South',
                '08' => 'Masvingo',
                '09' => 'Bulawayo',
                10 => 'Harare'
            )
        );

				return $GEOIP_REGION_NAME[$countryCode];
    }

    public static function isValid($value)
    {
        try {
            // Validate the value...
        } catch (Exception $e) {
            report($e);

            return false;
        }
    }

    public static function checkExits($tableName,$where)
    {
        $recordExits = DB::table("$tableName")->where($where)->first();
        if (!empty($recordExits)){
            return 1;
        }else{
            return 0;
        }
    }

		public static function getCurrency($currency_id)
    {
        $recordExits = DB::table('countries')->select('currencyCode')->where([ 'countryId' => $currency_id])->first();
      	return $recordExits->currencyCode;
    }



	public static function paginate($item_per_page, $current_page, $total_records, $total_pages, $page_url,  $prev_url , $next_url, $campId)
    {
        $res = Campaign::where('id',$campId)->first();
        $dept = urlencode($res->title);
        //Pagination
        $adjacents = 2;
        $total_pages = $total_records;
        $limit = 10;                 //how many items to show per page
        $page = $current_page+1;
        if($page){
            $start = ($page - 1) * $limit;      //first item to display on this page
        }else{
            $start = 0;               //if no page var is given, set start to 0
        }
        /* Setup page vars for display. */
        if ($page == 0) $page = 1;          //if no page var is given, default to 1.
        $lastpage = ceil($total_pages/$limit);
        $lpm1 = $lastpage - 1;            //last page minus 1
        $pagination = "";
        if($lastpage > 1)
        {
            $pagination .= "<ul class=\"pagination\" id=\"custom_paginate\">";
            //previous button
            if ($page > 1){
                $pagination.= '<li><a href="javascript:void(0);" data-id="'.$campId.'" data-url="http://188.166.208.37'.$prev_url.'">«</a></li>';
            }else{
                $pagination.= '<li><span class=\"disabled\">«</span></li>';
            }
            //pages
            if ($lastpage < 7 + ($adjacents * 2)) //not enough pages to breaking it
            {
                for ($counter = 1; $counter <= $lastpage; $counter++)
                {
                    $i = $counter-1;
                    if ($counter == $page){
                        $pagination.= "<li><span class=\"current\">$counter</span></li>";
                    }else{
                        $pagination.= '<li><a href="javascript:void(0);" data-id="'.$campId.'" data-url="http://188.166.208.37'.$page_url.'?limit='.$item_per_page.'&offset='.$i * $item_per_page.'&department__name='.$dept.'">'.$counter.'</a></li>';
                    }
                }
            }
            elseif($lastpage > 5 + ($adjacents * 2))  //enough pages to hide some
            {
                //close to beginning; only hide later pages
                if($page < 1 + ($adjacents * 2))
                {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                    {
                        $i = $counter-1;
                        if ($counter == $page){
                            $pagination.= "<li><span class=\"current\">$counter</span></li>";
                        }
                        else
                        {
                            $pagination.= '<li><a href="javascript:void(0);" data-id="'.$campId.'" data-url="http://188.166.208.37'.$page_url.'?limit='.$item_per_page.'&offset='.$i * $item_per_page.'&department__name='.$dept.'">'.$counter.'</a></li>';
                        }
                    }
                    $pm1 = $total_pages-1;
                    $pagination.= "...";
                    $pagination.= '<li><a href="javascript:void(0);" data-id="'.$campId.'" data-url="http://188.166.208.37'.$page_url.'?limit='.$item_per_page.'&offset='.$pm1 * $item_per_page.'&department__name='.$dept.'">'.$lpm1.'</a></li>';
                    $pagination.= '<li><a href="javascript:void(0);" data-id="'.$campId.'" data-url="http://188.166.208.37'.$page_url.'?limit='.$item_per_page.'&offset='.$total_pages * $item_per_page.'&department__name='.$dept.'">'.$lastpage.'</a></li>';
                }
                //in middle; hide some front and some back
                elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                {
                    $pagination.= '<li><a href="javascript:void(0);" data-id="'.$campId.'" data-url="http://188.166.208.37'.$page_url.'?limit='.$item_per_page.'&offset='.(1) * $item_per_page.'&department__name='.$dept.'">1</a></li>';
                    $pagination.= '<li><a href="javascript:void(0);" data-id="'.$campId.'" data-url="http://188.166.208.37'.$page_url.'?limit='.$item_per_page.'&offset='.(2) * $item_per_page.'&department__name='.$dept.'">2</a></li>';
                    $pagination.= "...";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                    {
                        $i = $counter-1;
                        if ($counter == $page){
                            $pagination.= "<li><span class=\"current\">$counter</span></li>";
                        }
                        else
                        {
                            $pagination.= '<li><a href="javascript:void(0);" data-id="'.$campId.'" data-url="http://188.166.208.37'.$page_url.'?limit='.$item_per_page.'&offset='.$i * $item_per_page.'&department__name='.$dept.'">'.$counter.'</a></li>';
                        }
                    }
                    $pagination.= "...";
                    $pagination.= '<li><a href="javascript:void(0);" data-id="'.$campId.'" data-url="http://188.166.208.37'.$page_url.'?limit='.$item_per_page.'&offset='.$pm1 * $item_per_page.'&department__name='.$dept.'">'.$lpm1.'</a></li>';
                    $pagination.= '<li><a href="javascript:void(0);" data-id="'.$campId.'" data-url="http://188.166.208.37'.$page_url.'?limit='.$item_per_page.'&offset='.$total_pages * $item_per_page.'&department__name='.$dept.'">'.$lastpage.'</a></li>';
                }
                //close to end; only hide early pages
                else
                {
                    $pagination.= '<li><a href="javascript:void(0);" data-id="'.$campId.'" data-url="http://188.166.208.37'.$page_url.'?limit='.$item_per_page.'&offset='.(1) * $item_per_page.'&department__name='.$dept.'">1</a></li>';
                    $pagination.= '<li><a href="javascript:void(0);" data-id="'.$campId.'" data-url="http://188.166.208.37'.$page_url.'?limit='.$item_per_page.'&offset='.(2) * $item_per_page.'&department__name='.$dept.'">2</a></li>';
                    $pagination.= "...";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                    {
                        $i = $counter-1;
                        if ($counter == $page){
                            $pagination.= "<li><span class=\"current\">$counter</span></li>";
                        }
                        else
                        {
                            $pagination.= '<li><a href="javascript:void(0);" data-id="'.$campId.'" data-url="http://188.166.208.37'.$page_url.'?limit='.$item_per_page.'&offset='.$i * $item_per_page.'&department__name='.$dept.'">'.$counter.'</a></li>';
                        }
                    }
                }
            }
            //next button
            if ($page < $counter - 1){
              $pagination.= '<li><a href="javascript:void(0);" data-id="'.$campId.'" data-url="http://188.166.208.37'.$next_url.'">»</a></li>';
            }
            else{
              $pagination.= "<li><span class=\"disabled\">»</span></li>";
            }
            $pagination.= "</ul>\n";
        }
        return $pagination;
    }



    public static function charts(){
        $userID = \Auth::user()->id;
				DB::enableQueryLog();
        $leadChart = DB::select('SELECT YEAR(created_at) as year, MONTH(created_at) as month, COUNT(id) AS count FROM parse_emails where campaignEmail IN (select email from campaigns where user_id = '.$userID.') GROUP BY YEAR(created_at), MONTH(created_at) ORDER BY YEAR(created_at) DESC, MONTH(created_at) LIMIT 5');

        $campChart = DB::select('select c.title, COUNT(c.title) as count from parse_emails p, campaigns c where p.campaignEmail=c.email and c.user_id = '.$userID.' GROUP BY c.title');
        $result['lead_chart'] = $leadChart;
        $result['camp_chart'] = $campChart;
        return $result;
    }
    public static function chartsAjax($startDate,$endDate){
        $userID = \Auth::user()->id;


				$date1 = $startDate;
				$date2 = $endDate;
				$diff = abs(strtotime($date2) - strtotime($date1));

				$years = floor($diff / (365*60*60*24));
				$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
				$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
				// DB::enableQueryLog();
				$resultss = array();
				if ($days > 0 && $days < 31 && ($months == 0 && $years == 0)  ) {
				$resultss = DB::select('select created_at as month , count(id) as count from parse_emails where campaignEmail IN (select email from campaigns where user_id = '.$userID.') AND (created_at BETWEEN "'.$startDate.'" AND "'.$endDate.'") GROUP BY CAST(created_at AS DATE)  ORDER BY created_at ASC');
				}
				if ($months != 0 && $years == 0) {
					$resultss = DB::select('select monthname(created_at) as month , count(id) as count from parse_emails where campaignEmail IN (select email from campaigns where user_id = '.$userID.') AND (created_at BETWEEN "'.$startDate.'" AND "'.$endDate.'") group by monthname(created_at) ORDER BY created_at ASC');
				}
				if ($years != 0) {
					$resultss = DB::select('select year(created_at) as month , count(id) as count from parse_emails where campaignEmail IN (select email from campaigns where user_id = '.$userID.') AND (created_at BETWEEN "'.$startDate.'" AND "'.$endDate.'") group by year(created_at) ORDER BY created_at ASC');
				}
				$campChart = DB::select('select c.title, COUNT(c.title) as count from parse_emails p, campaigns c where p.campaignEmail=c.email and c.user_id = "'.$userID.'" AND (p.created_at BETWEEN "'.$startDate.'" AND "'.$endDate.'") GROUP BY c.title');
				$records = array('result' => $resultss,'campChart'=>$campChart );
        return ( $records );;
    }

    public static function emailReplacement($patterns, $replacements, $string){
			$temp['content'] = preg_replace($patterns, $replacements, $string);
			return $temp;
    }

	public static function billingPlan(){
        $types = BillingType::where('status',1)->where('is_deleted',0)->get()->pluck('planType','id')->toArray();;
				$type=array_map('trim',$types);
        asort($type);
        $types = array_prepend($types,'Select Billing Type','0');
        $billingPlan = $types;
				if (!empty($type)) {return $billingPlan;}else{  $types = array_prepend(array(),'Select Billing Type','0');
        return $billingPlan = $types;}
    }

}
?>
