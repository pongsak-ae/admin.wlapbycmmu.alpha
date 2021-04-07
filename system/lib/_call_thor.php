<?php

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
			
			// var_dump($param);
			$data_string = json_encode($param);
			// var_dump($param);
			// exit();

			$ch = curl_init('http://tr4ns4.tr4ns.com/mCardServer1.05/mCardService');
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

?>

