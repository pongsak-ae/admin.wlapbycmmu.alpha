<?php
require_once("PEAR/set_path.php");

require_once("Mail.php");
require_once('Mail/mime.php');

class OMMailer {

	private $_SmtpServerHostname = "localhost";
    private $_SmtpServerPort = 25;
    private $_SmtpServerUserName = "";
    private $_SmtpServerPassword = "";
    private $_OrganizationName = "";
    private $_TextEncoding = null;
    public $LastErrorMessage = "";


	public function OMMailer() {
        $this->_TextEncoding = "UTF-8";
        $this->_SmtpServerHostname = WCMSetting::$SMTP_SERVER_HOSTNAME;
        $this->_SmtpServerPort = WCMSetting::$SMTP_SERVER_PORT;
        $this->_SmtpServerUserName = WCMSetting::$SMTP_SERVER_USERNAME;
        $this->_SmtpServerPassword = WCMSetting::$SMTP_SERVER_PASSWORD;
        if($this->_SmtpServerPassword != "" && WCMSetting::$PASSWORD_ENCRYPT){
			$decrypt = new OMCrypto(WCMSetting::$ENCRYPT_INIT_KEY, WCMSetting::$ENCRYPT_INIT_VECTOR);
			$this->_SmtpServerPassword = $decrypt->Decrypt($this->_SmtpServerPassword);
		}
    }
	public function sendMail($data, &$result) {
		$result = array();
		$from_email = isset($data['from_email'])?$data['from_email']:'';
		$from_name = isset($data['from_name'])?$data['from_name']:'';
		$to_email = isset($data['to_email'])?$data['to_email']:'';
		$to_name = isset($data['to_name'])?$data['to_name']:'';
		$text = isset($data['body'])?$data['body']:'';
		$html =  isset($data['body_html'])?$data['body_html']:'';
		$crlf = "\n";

		$message = new Mail_mime();
		if ($html != "") $message->setHTMLBody($html);
		$message->setTXTBody($text);


		$params = array (
							'text_encoding'=>'base64',
							'html_encoding'=>'base64',
							'head_encoding'=>'base64',
							'head_charset'=>$this->_TextEncoding,
							'text_charset'=>$this->_TextEncoding,
							'html_charset'=>$this->_TextEncoding
						);

		$body = $message->get($params);
		$extraheaders = array(
								'From'=>($from_name!="")?($from_name . ' <' . $from_email . '>'):$from_email,
								'To'=>($to_name!="")?($to_name . ' <' . $to_email . '>'):$to_email,
								'Subject'=>isset($data['subject'])?$data['subject']:''
							  );
		$headers = $message->headers($extraheaders);


		$smtpinfo["host"] = $this->_SmtpServerHostname;
		$smtpinfo["port"] = $this->_SmtpServerPort;
		if (isset($this->_SmtpServerUserName) && $this->_SmtpServerUserName != "") {
			$smtpinfo["auth"] = true;
			$smtpinfo["username"] = $this->_SmtpServerUserName;
			$smtpinfo["password"] = $this->_SmtpServerPassword;
		} else {
			$smtpinfo["auth"] = false;
			$smtpinfo["username"] = "";
			$smtpinfo["password"] = "";
		}

		$mail =& Mail::factory("smtp", $smtpinfo);


		$sendresult = $mail->send($to_email, $headers, $body);


		if (PEAR::isError($sendresult)) {

			$this->LastErrorMessage =  $sendresult->getMessage();
			$result["status"] = "ERROR";
			return false;
		} else {
			$result["status"] = "OK";
			return true;
		}
	}
}
?>