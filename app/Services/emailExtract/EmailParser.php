<?php
namespace  App\Services\emailExtract;
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
require_once __DIR__ . '/vendor/autoload.php';
require_once('email_reader.php');
require_once('email_parser.php');
class EmailParser
{
    private $domainURL;
    private $username;
    private $password;
    private $mailbox;
    public function __construct($url, $user, $pass)
    {   
        $this->domainURL = $url;
        $this->username = $user;
        $this->password = $pass;
    }
    public function getContent()
    {
        $this->mailbox = '{'.$this->domainURL.':143/notls}INBOX';
        $reader = new \Email_Reader($this->mailbox, $this->username, $this->password );
        return $reader->get_unread();
    }
}


?>
