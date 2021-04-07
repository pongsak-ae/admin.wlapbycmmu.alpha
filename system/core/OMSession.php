<?php
namespace OMCore;

class OMSession {
	function __construct() {

	}
	public function getSession(){

		$_SESSION['loggedin'] = true;
		$_SESSION['accesstoken'] = genAccessToken($username);
		$_SESSION['employee_token_key']= $sql_param_Insert['employee_token_key'];
		$_SESSION['employee_id'] = safeDecrypt($ds[0]['employee_id'],WCMSetting::$ENCRYPT_EMPLOYEE);
		$_SESSION['last_activity'] =  time();
		$_SESSION['expire_time'] = 86400 * 30;
		$_SESSION['status'] = $ds[0]['SysGroupName'];

		return $_SESSION;
	}
	public function checkStatus($status){
		if($_SESSION['status'] == $status){
			$status = 'true';
		}else{
			$status = 'false';
		}
		return $status;
	}
	public function checkMenu($page){

		if(($page == 'form_job') && (($_SESSION['status'] != 'Sale') && ($_SESSION['status'] != 'Administrator') && ($_SESSION['status'] != 'Manager'))){
			$status = 'false';
		}elseif(($page == 'payment') && (($_SESSION['status'] != 'Officer') && ($_SESSION['status'] != 'Administrator') && ($_SESSION['status'] != 'Manager'))){
			$status = 'false';
		}elseif(($page == 'postcode') && (($_SESSION['status'] != 'Officer') && ($_SESSION['status'] != 'Administrator') && ($_SESSION['status'] != 'Manager'))){
			$status = 'false';
		}elseif(($page == 'stock_product') && (($_SESSION['status'] != 'Officer') && ($_SESSION['status'] != 'Administrator') && ($_SESSION['status'] != 'Manager'))){
			$status = 'false';
		}elseif(($page == 'support-chat') && (($_SESSION['status'] != 'Officer') && ($_SESSION['status'] != 'Administrator') && ($_SESSION['status'] != 'Manager'))){
			$status = 'false';
		}else{
			$status = 'true';
		}
		return $status;
	}
	public function checkPage($page){
		if(($page == 'form_job') && (($_SESSION['status'] != 'Sale') && ($_SESSION['status'] != 'Administrator') && ($_SESSION['status'] != 'Manager'))){
			header("Location: ".WEB_META_BASE_URL."index");
		}elseif(($page == 'payment') && (($_SESSION['status'] != 'Officer') && ($_SESSION['status'] != 'Administrator') && ($_SESSION['status'] != 'Manager'))){
			header("Location: ".WEB_META_BASE_URL."index");
		}elseif(($page == 'postcode') && (($_SESSION['status'] != 'Officer') && ($_SESSION['status'] != 'Administrator') && ($_SESSION['status'] != 'Manager'))){
			header("Location: ".WEB_META_BASE_URL."index");
		}elseif(($page == 'stock_product') && (($_SESSION['status'] != 'Officer') && ($_SESSION['status'] != 'Administrator') && ($_SESSION['status'] != 'Manager'))){
			header("Location: ".WEB_META_BASE_URL."index");
		}elseif(($page == 'support-chat') && (($_SESSION['status'] != 'Officer') && ($_SESSION['status'] != 'Administrator') && ($_SESSION['status'] != 'Manager'))){
			header("Location: ".WEB_META_BASE_URL."index");
		}
		return 0;
	}
}

?>