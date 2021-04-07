<?php
namespace OMCore;

use PHPMailer;
use WCMSetting;
// include "vendor/phpmailer/phpmailer/class.phpmailer.php";
class OMMail extends PHPMailer
{
	private $smarty = null;
	public function __construct($exceptions = false) {
		$this->exceptions = ($exceptions == true);

		$this->IsSMTP();
		$this->Host = WCMSetting::$SMTP_SERVER_HOSTNAME; 			// specify main and backup server
		$this->Username =  WCMSetting::$SMTP_SERVER_USERNAME;  // SMTP username
		if($this->Username == ""){
			$this->SMTPAuth = false;     		// turn on SMTP authentication
		}else{
			$this->SMTPAuth = true;     		// turn on SMTP authentication
		}
		$this->SMTPSecure = 'tls';
		$this->Password =  WCMSetting::$SMTP_SERVER_PASSWORD; 	// SMTP password
		$this->Port = WCMSetting::$SMTP_SERVER_PORT; 			// SMTP password
		$this->Timeout = 300;
		$this->SMTPKeepAlive = true;		// use with $this->SmtpClose();
		// $this->IsHTML(true);
		$this->Mailer   = "smtp";
		$this->CharSet = "UTF-8";

	}
	public function sendMail(){
		return $this->Send();
	}

}
?>