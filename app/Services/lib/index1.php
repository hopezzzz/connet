<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

/*		$mbox =
		$inbox = imap_open("{mail.1wayit.com:110/pop3}notls", "pardeep@1wayit.com", "Pardeep@786") or die('Cannot connect to Gmail: ' . imap_last_error());
		// $inbox = imap_open($this->imapPath, "benamazon1wayit@gmail.com", "Preet@786") or die('Cannot connect to Gmail: ' . imap_last_error());
		$emails = imap_search($inbox, 'UNSEEN');
		if (is_array($emails) || is_object($emails)) {
				rsort($emails);
				foreach ($emails as $key => $mail) {
						$headerInfo = imap_headerinfo($inbox, $mail);
						//  $emailFrom['fromName'] = $headerInfo->from[0]->personal;
						$emailFrom['fromName'] = "";
						$emailFrom['from'] = $headerInfo->to[0]->mailbox . '@' . $headerInfo->to[0]->host;
						$emailFrom['purchaseOrder'] = date('Y-m-d H:i:s', strtotime($headerInfo->Date));
						$message = imap_fetchbody($inbox, $mail, 2);
						if (empty($message)) {
								$message = imap_fetchbody($inbox, $mail, 1);
						}
						$message = quoted_printable_decode($message);
						if (!empty($message)) {
								$result['success'] = true;
								$result['message'] = $message;
								$result['emailFrom'] = $emailFrom;
							} else {
									$result['success'] = true;
									$result['message'] = 'No email data found';
							}
				}
		} else {
				$result['success'] = true;
				$result['message'] = 'No new emails found';
		}
		imap_expunge($inbox);
		imap_close($inbox);

header('Content-Type: application/json');
echo json_encode($result);
exit;
*/

  /*  class Email_reader {

        // imap server connection
        public $conn;

        // inbox storage and inbox message count
        private $inbox;
        private $msg_cnt;

        // email login credentials
        private $server = '1wayit.com';
        private $user   = 'Pardeep@1wayit.com';
        private $pass   = 'Pardeep@786';
        private $port   = 143; // adjust according to server settings

        // connect to the server and get the inbox emails
        function __construct() {
            $this->connect();
            $this->inbox();
        }

        // close the server connection
        function close() {
            $this->inbox = array();
            $this->msg_cnt = 0;

            imap_close($this->conn);
        }

        // open the server connection
        // the imap_open function parameters will need to be changed for the particular server
        // these are laid out to connect to a Dreamhost IMAP server
        function connect() {
            $this->conn = imap_open('{'.$this->server.'/notls}', $this->user, $this->pass);
        }

        // move the message to a new folder
        function move($msg_index, $folder='INBOX.Processed') {
            // move on server
            imap_mail_move($this->conn, $msg_index, $folder);
            imap_expunge($this->conn);

            // re-read the inbox
            $this->inbox();
        }

        // get a specific message (1 = first email, 2 = second email, etc.)
        function get($msg_index=NULL) {
            if (count($this->inbox) <= 0) {
                return array();
            }
            elseif ( ! is_null($msg_index) && isset($this->inbox[$msg_index])) {
                return $this->inbox[$msg_index];
            }

            return $this->inbox[0];
        }

        // read the inbox
        function inbox() {
            $this->msg_cnt = imap_num_msg($this->conn);

            $in = array();
            for($i = 1; $i <= $this->msg_cnt; $i++) {
                $in[] = array(
                    'index'     => $i,
                    'header'    => imap_headerinfo($this->conn, $i),
                    'body'      => imap_body($this->conn, $i),
                    'structure' => imap_fetchstructure($this->conn, $i)
                );
            }

            $this->inbox = $in;
        }

    }

		$new = new Email_reader();
		$data = $new->inbox();
		print_r($data );die;*/


/*
		$emailAddress = 'pardeep@1wayit.com'; // Full email address
    $emailPassword = 'Pardeep@786';        // Email password
    $domainURL = '1wayit.com';              // Your websites domain
    $useHTTPS = true;                       // Depending on how your cpanel is set up, you may be using a secure connection and you may not be. Change this from true to false as needed for your situation

    /* BEGIN MESSAGE COUNT CODE *

    $inbox = imap_open('{'.$domainURL.':143/notls}INBOX',$emailAddress,$emailPassword) or die('Cannot connect to domain:' . imap_last_error());
    $oResult = imap_search($inbox, 'UNSEEN');

    if(empty($oResult))
        $nMsgCount = 0;
    else
        $nMsgCount = count($oResult);


				echo "<pre>";print_r($inbox);die;
				foreach ($oResult as $key => $mail) {
						$headerInfo = imap_headerinfo($inbox, $mail);
						//  $emailFrom['fromName'] = $headerInfo->from[0]->personal;
						$emailFrom['fromName'] = "";
						$emailFrom['from'] = $headerInfo->to[0]->mailbox . '@' . $headerInfo->to[0]->host;
						$emailFrom['purchaseOrder'] = date('Y-m-d H:i:s', strtotime($headerInfo->Date));
						$message = imap_fetchbody($inbox, $mail, 2);
						if (empty($message)) {
								$message = imap_fetchbody($inbox, $mail, 1);
						}

						$message = quoted_printable_decode($message);
						if (!empty($message)) {

								$result['success'] = true;
								$result['message'] = 'Data updated successfully.';
								$result['messags'] = $message;
							} else {
									$result['success'] = true;
									$result['message'] = 'No email data found';
							}
				}

    imap_close($inbox);

    echo('<p>You have '.$nMsgCount.' unread messages.</p>');
		echo "<pre>";print_r($result);echo "<pre>";
    /* END MESSAGE COUNT CODE *

    echo('<a href="http'.($useHTTPS ? 's' : '').'://'.$domainURL.':'.($useHTTPS ? '2096' : '2095').'/login/?user='.$emailAddress.'&pass='.$emailPassword.'&failurl=http://'.$domainURL.'" target="_blank">Click here to open your inbox.</a>');
*/


$emailAddress = 'pardeep@1wayit.com'; // Full email address
$emailPassword = 'Pardeep@786';        // Email password
$domainURL = '1wayit.com';              // Your websites domain
$useHTTPS = true;                       // Depending on how your cpanel is set up, you may be using a secure connection and you may not be. Change this from true to false as needed for your situation

/* BEGIN MESSAGE COUNT CODE */

$inbox = imap_open('{'.$domainURL.':143/notls}INBOX',$emailAddress,$emailPassword) or die('Cannot connect to domain:' . imap_last_error());
$emails = imap_search($inbox, 'UNSEEN');
if (is_array($emails) || is_object($emails)) {
		rsort($emails);
		foreach ($emails as $key => $mail) {
				$headerInfo = imap_headerinfo($inbox, $mail);
				$emailFrom['from'] = $headerInfo->to[0]->mailbox . '@' . $headerInfo->to[0]->host;
				$emailFrom['emailDate'] = date('Y-m-d H:i:s', strtotime($headerInfo->Date));
				$message = imap_fetchbody($inbox, $mail, 2);
				if (empty($message)) {
						$message = imap_fetchbody($inbox, $mail, 1);
				}
				$message = quoted_printable_decode($message);
				if (!empty($message)) {
					$result['message'] = readMessageFromEmail($message);
						if (strpos($message, 'Respected') !== false) {
								$result['success'] = $emailFrom;

						}
					} else {
							$result['success'] = true;
							$result['message'] = 'No email data found';
					}
		}
} else {
		$result['success'] = true;
		$result['message'] = 'No new emails found';
}
imap_expunge($inbox);
imap_close($inbox);


function readMessageFromEmail($value)
{
	$dom 											= new DOMDocument();
	$dom->loadHTML($value);
	$dom->preserveWhiteSpace 	= false;
	// echo $dom->saveXML();die;
	$hTwo 										= $dom->getElementsByTagName('div');
	$Header 									= $dom->getElementsByTagName('div');
	$aDataTableHeaderHTML 		= array();

	foreach ($Header as $key 	=> $NodeHeader) {
	echo "<pre>";	print_r($NodeHeader);die;
			if ($NodeHeader != '') {
					$items = $NodeHeader->getElementsByTagName('div');
					if (!empty(trim($items->item(0)->nodeValue))) {
							$aDataTableHeaderHTML[] = trim($items->item(0)->nodeValue);
					}
			}
	}
	echo "<pre>";print_r($aDataTableHeaderHTML);die;
	foreach ($aDataTableHeaderHTML as $val) {
			if (strpos(trim($val), 'Item #') !== false) {
					$arr = explode('Item #', $val);
					$arr1 = explode(' ', $arr[1]);
					if (!isset($arr1[1]) && !empty($arr[0])) {
							$products['name'][] = str_replace(array(',', '"', "'"), '', trim($arr[0]));
							$itemcheck = 1;
					}
			}
			if ($itemcheck == 2) {
					if (strpos(trim($val), 'Item #') !== false) {
							$products['itemid'][] = str_replace('Item #', '', trim($val));
					}
					if (strpos(trim($val), 'Quantity:') !== false) {
							$arr = explode('Quantity:', trim($val));
							if (isset($arr[1]) && !empty($arr[1])) {
									$products['qty'][] = trim($arr[1]);
							}
					}
					if (strpos(trim($val), 'You Paid:') !== false) {
							$itemPaid = 1;
					}
					if ($itemPaid == 2) {
							if (strpos(trim($val), 'Quantity:') !== false) {

							} else {
									$products['amount'][] = str_replace('$', '', trim($val));
							}
					}
					if ($itemPaid == 1) {
							$itemPaid = 2;
					} else {
							$itemPaid++;
					}
			}
			if ($itemcheck == 1) {
					$itemcheck = 2;
			}
	}

	foreach ($hTwo as $key1 => $trs) {
			//check conditions............
			if (strpos(trim($trs->nodeValue), 'ORDER NUMBER:') !== false) {
					$orderId = str_replace("ORDER NUMBER:", "", trim($trs->nodeValue));
			}

			if (strpos(trim($trs->nodeValue), 'ORDER DATE:') !== false) {
					$purchaseOrder = str_replace("ORDER DATE:", "", $trs->nodeValue);
			}

			if (strpos(trim($trs->nodeValue), 'ITEMS FOR DELIVERY') !== false) {
					$pro = $trs->nodeValue;
			}


			//end increment check........
	}
}
// echo "<pre>";print_r($result);die;
?>
