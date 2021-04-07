<?php

require("alert_msg_config.php");
require("display_txt_config.php");


use OMCore\OM;
use OMCore\OMMail;

function testFunc(){
	return "###";
}

function get_global_content($key){

	$param = array();
	$param["command"] = "global_content.get";
	$param["key"] = $key;
	$param["lang"] = getCurrentLang();
	$output = json_decode(OM::cURL('',WEB_META_BASE_API,$param), true);
	return $output;

}

function getAlertMessage($keys=array()){

	$r = array();

	$current_lang = getCurrentLang();
	$ALERT_MSG = json_decode(ALERT_MSG,true);

	$global_data = $ALERT_MSG["GLOBAL"][$current_lang];
	$r["GLOBAL"] = $global_data;

	if (count($keys) > 0) {

		foreach ($keys as $k => $v) {

			if (isset($ALERT_MSG[$v])) {
				$data = $ALERT_MSG[$v][$current_lang];
				$r[$v] = $data;
			}
		}

	}

	return $r;
}

function getDisplayText($keys=array()){

	$r = array();

	$current_lang = getCurrentLang();
	$DISPLAY_TEXT = json_decode(DISPLAY_TEXT,true);

	$global_data = $DISPLAY_TEXT["GLOBAL"][$current_lang];
	$r["GLOBAL"] = $global_data;

	if (count($keys) > 0) {

		foreach ($keys as $k => $v) {

			if (isset($DISPLAY_TEXT[$v])) {
				$data = $DISPLAY_TEXT[$v][$current_lang];
				$r[$v] = $data;
			}
			
		}

	}

	return $r;
}

function getCurrentLang(){

	$current_lang_key = "CURRENT_LANG";
	$current_lang = "TH";

	if (isset($_COOKIE["CURRENT_LANG"])) {
		$current_lang = $_COOKIE["CURRENT_LANG"];
	}

	return $current_lang;

}

function setLang($_lang){

	$current_lang_key = "CURRENT_LANG";
	if (!isset($_lang)) {
		$current_lang = $_COOKIE["CURRENT_LANG"];
		$lang = "TH";
		if ($current_lang != "TH") {
			$lang = "EN";
		}

	}else{
		$lang = $_lang;
	}
	
	setcookie($current_lang_key, $lang);
	return array('status' => 'success');
}

function CALL_API_THOR($param=NULL){
	if(!empty($param)){
		
		$param["programID"] = PROGRAM_ID;
		if(setSite($param["requestType"])){
			$param["siteNo"] = SITE_NO;
			$param["sitePass"] = SITE_PASS;
		}

		if(check_requestType($param["requestType"])){
			if(isset($_SESSION['member']) && !empty($_SESSION['member'])) {
				$param["login"] = $_SESSION['member']['username'];
				// $param["password"] = $_SESSION['member']['password'];
			}else{
				return array("responseCode"=>"550","description"=>"Permission denied");
			}
		}
		


		$data_string = json_encode($param);
		$url = 'https://tr4ns4.tr4ns.com/mCardServer1.05b/mCardService';

		// echo "URL Request : <br/><br/>".$url;
		// echo "<br/><br/><br/>";
		// echo "Request Parameter :";
		// echo "<br/><br/>";
		// echo $data_string;
		// echo "<br/><br/><br/>";
		
		//$url = 'http://tr4ns4.tr4ns.com/mCardServer1.05/mCardService';
		

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		 	'Content-Type: application/json',
		    'Content-Length: ' . strlen($data_string)
		));
		$output = curl_exec($ch);
		return $output;
	}

	return array("responseCode"=>"602","description"=>"require parameter missing");

}

function check_requestType($cmd){
		$req_type_arr = array('CarrotSetSurveyQuestion','CarrotAddCard','CarrotRemoveCard','CarrotTransactionHistory','CarrotMobileRedeemedItem','CarrotUpdate','CarrotExpiryPoint','CarrotChangePassword','UpdateProfileImage');
		return in_array($cmd, $req_type_arr) ? true : false;
}

function setSite($cmd){
	$not_setSite = array("CarrotLogin","CarrotMobileRedeemedItem","CarrotChangePassword","CarrotForgottenPassword");
	return in_array($cmd, $not_setSite) ? false : true;
}

function send_mail($param,$form){

	$mail = new OMMail();
	// $to_email = "niphon@ifirstfix.com";
	// $to_name = "Niphon";

	$to_email = $param["to_email"];
	$to_name = $param["to_name"];


	$mail->Sender = "info@ifirstfix.com";
	$mail->From = "info@ifirstfix.com";
	$mail->FromName = "Carrot Reward";

	$mail->AddAddress($to_email ,$to_name);
	$mail->Subject = $form["subject"];

	$body = "<img src=\"" . WEB_META_BASE_URL . "images/logo.gif\"><br/><br/>";
	$body .= nl2br($form["body"] . "<br/>");
	// $body .= "<img src=\"" . WEB_META_BASE_URL . "/images/logo.gif\"><br/><br/>";

	$mail->Body = $body;
	$mail->AltBody = $body;

	if(!$mail->sendMail()){
		return array("status"=>"error","message"=>$mail->ErrorInfo);
	}

	$mail->SmtpClose();
	
	return array("status"=>"success","message"=>"");

}

function validateRefToken($ref_token){

	$param = array();
	$param["command"] = "invite_friend.validate_referrer_token";
	$param["ref_token"] = $ref_token;
	$output = json_decode(OM::cURL('',WEB_META_BASE_API,$param), true);

	if ($output["status"] == "200") {
		return true;
	}else{
		return false;
	}
}	

function getBestRedeemPointReward($param_point){
		var_dump($param_point);
	if(count($param_point) > 1){
		foreach ($param_point as $key => $value) {
			if($value["rule"] != "default"){
				// var_dump($value);

			}
		}
	}





}




?>