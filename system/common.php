<?php
session_start(); 
ini_set('display_errors', 1);
ini_set('memory_limit', -1);
error_reporting(E_ALL);
use OMCore\OM;

date_default_timezone_set("Asia/Bangkok");
if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}
define('KEY_ENCODE_SS', 'IFF@#*id09');

if (!defined('ROOT_DIR')) {
	$__file__ = explode("system" . DS, __FILE__);
	define('ROOT_DIR', $__file__[0]);
	define('SYS_DIR', ROOT_DIR . 'system/');
	define('TMPL_DIR', ROOT_DIR . 'template_layout/');
	define('CTRL_DIR', ROOT_DIR . 'controllers/');
}
require ROOT_DIR . 'wcm/setting.php';
require ROOT_DIR . 'system/configs/config.php';
require ROOT_DIR . 'system/lib/global_lib.php';

use OMCore\OMDb;
$page_title = "NameNuT";
$page_desc = "NameNuT";

// $lang = "";
// if(LANG == "tha")
// {
//   $lang = ROOT_DIR . 'language/th.php';
// }
// else if(LANG == "en")
// {
//   $lang = ROOT_DIR . 'language/en.php';
// }
// else
// {
//   $lang = ROOT_DIR . 'language/en.php';
// }
//   var_dump($lang);
//   exit();
// require $lang;

function bypassLogin($username, $password) {

	$ch = curl_init();
	$curlConfig = array(
		CURLOPT_URL => WEB_META_BASE_URL . "login/index.php",
		CURLOPT_POST => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POSTFIELDS => array(
			"cmd" => "login",
			"username" => $username,
			"password" => $password,
		),
	);

	curl_setopt_array($ch, $curlConfig);
	$result = json_decode(curl_exec($ch), true);
	curl_close($ch);

	$_SESSION["OM_USER"] = $result['data'];
}

function numberFormat($data) {
	return number_format($data, 0, '.', ',');
}

function getAllCampaign() {
	$DB = OMDb::singleton();
	$all_campaign_list = array();
	$sql = "select campaign_name,campaign_id from campaign_list";
	$ds = null;
	$res = $DB->query($ds, $sql, null, 0, -1, "ASSOC");
	foreach ($ds as $key => $value) {
		$all_campaign_list['campaign_' . $value['campaign_id']] = $value;
	}
	return $all_campaign_list;
}

function getAllEvent() {
	$DB = OMDb::singleton();
	$all_event_list = array();
	$sql = "select event_campaign_id,event_name,a.campaign_id,b.campaign_name from event_campaign_mapping a left join campaign_list b on a.campaign_id = b.campaign_id";
	$ds = null;
	$res = $DB->query($ds, $sql, null, 0, -1, "ASSOC");
	foreach ($ds as $key => $value) {
		$all_event_list['event_' . $value['event_campaign_id']] = $value;
	}
	return $all_event_list;
}

function humanTiming($time) {

	$time = time() - $time; // to get the time since that moment

	$tokens = array(
		31536000 => 'year',
		2592000 => 'month',
		604800 => 'week',
		86400 => 'day',
		3600 => 'hour',
		60 => 'minute',
		1 => 'second',
	);

	foreach ($tokens as $unit => $text) {
		if ($time < $unit) {
			continue;
		}

		$numberOfUnits = floor($time / $unit);
		return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '');
	}

	return "0 second";
}

function includeIfExists($file) {
	return file_exists($file) ? include $file : false;
}

if ((!$loader = includeIfExists(__DIR__ . '/../vendor/autoload.php')) && (!$loader = includeIfExists(__DIR__ . '/../../../autoload.php'))) {
	echo 'You must set up the project dependencies, run the following commands:' . PHP_EOL .
		'curl -sS https://getcomposer.org/installer | php' . PHP_EOL .
		'php composer.phar install' . PHP_EOL;
	exit(1);
}

$PAGE_VAR["js"] = array();
//$PAGE_VAR["css"] = array("site");

function clearOldFileAtPath($folderPath, $older_day) {
	if (file_exists($folderPath)) {
		foreach (new DirectoryIterator($folderPath) as $fileInfo) {
			if ($fileInfo->isDot()) {
				continue;
			}
			if (time() - $fileInfo->getCTime() >= $older_day * 24 * 60 * 60) {
				unlink($fileInfo->getRealPath());
			}
		}
	}
}

function genNewFileName($filename) {
	$ext = pathinfo($filename, PATHINFO_EXTENSION);
	return uniqid() . "." . $ext;
}


function genAccessToken($username) {
	$time = strtotime(date('Y-m-d H:i:s'));
	$characters = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
	$out = "";
	for ($i = 0; $i < 5; $i++) {
		$rand_num = rand(0, 60);
		$alpha_out = $characters[$rand_num];
		$out = $out . $alpha_out;
	}
	$token = md5($username . "_" . $time . "_" . $out);
	return $token;
}

function randomAPIKey() {
	$api_key = base64_encode(rand(100000, 999999) . uniqid() . rand(100000, 999999));

	$api_key = str_replace(array("=="), array(""), $api_key);

	return $api_key;
}


function generateTransactionId() {
	$date = new DateTime();
	return (($date->format('U') * 1000) + mt_rand(0, 999));
}

// function validateRefToken($ref_token){

//     $param = array();
//     $param["command"] = "invite_friend.validate_referrer_token";
//     $param["ref_token"] = $ref_token;
//     $output = json_decode(OM::cURL('',WEB_META_BASE_API,$param), true);

//     if ($output["status"] == "200") {
//         return true;
//     }else{
//         return false;
//     }
// }

function generateEventKey() {
	$DB = OMDb::singleton();

	$check_status = false;
	// while ($check_status == false) {
	$event_key = base64_encode(randomChar(3) . rand(100000, 999999) . uniqid() . rand(100000, 999999) . randomChar(3));
	$event_key = str_replace(array("=="), array(""), $event_key);
	$sql = "select event_campaign_id from event_campaign_mapping where event_key = @event_key";
	$sql_param = array();
	$sql_param['event_key'] = $event_key;
	$ds = null;
	$res = $DB->query($ds, $sql, $sql_param, 0, -1, "ASSOC");
	if ($res > 0) {
		return false;
	}
	// }

	return $event_key;
}

function randomChar($size) {
	$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	$result = '';
	for ($i = 0; $i < $size; $i++) {
		$result .= $characters[mt_rand(0, 61)];
	}
	return $result;
}

function generateUQID() {
	$unique_id = uniqid();
	$start_rand = genUQ(9);
	$end_rand = genUQ(10);
	$get_uq = $start_rand . $unique_id . $end_rand;
	return $get_uq;
}


function genUQ($length) {
	$characters = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "+", "/");
	$out = "";
	for ($i = 0; $i < $length; $i++) {
		$rand_num = rand(0, 63);
		$alpha_out = $characters[$rand_num];
		$out = $out . $alpha_out;
	}
	return $out;
}

function convertTime($time) {
	$tmp_time = explode("-", $time);
	if (count($tmp_time) != 2) {
		return $time;
	}
	$date = explode("/", trim($tmp_time[0]));
	if (count($date) != 3) {
		return $time;
	}
	$date = $date[2] . "-" . $date[1] . "-" . $date[0];
	$time = trim($tmp_time[1]) . ":00";
	return $date . " " . $time;
}

function FunctionName (int $a, int $b){
return $a * $b;
}




function date_re_format_toSQL($date_start){

		$date = str_replace('/', '-', $date_start);
		return (date('Y-m-d H:i:s', strtotime($date)));
}


function genPassword_Auto($length=8) {
    $chars = array_merge(range(0,9), range('a','z'),range('A','Z'),['!','@','#','$','%','&','*','?']);
    //$chars = array_merge(range(0,1), range('A','Z'));
    shuffle($chars);
    $code = implode(array_slice($chars, 0, $length));
    $arr = array($code);
    $password = implode("",$arr);
	return $password;
}
function genSession($data) {
 // Remove the base64 encoding from our key
    $encryption_key = base64_decode("session_spiderfeed_cheese");
    // Generate an initialization vector
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    // Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
    // The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
    return base64_encode($encrypted . '::' . $iv);

}

function truewallet_secretkey() {
	$length_key1 = 4;
	$length_key2 = 2;
	$length_key3 = 4;
    $chars1 = array_merge(range(0,9), range('A','Z'));
    $chars2 = array_merge(range(0,9), range('A','F'));
    $chars3 = array_merge(range(0,9), range('A','Z'));

    //$chars1 = array_merge(range(0,9), range('a','z'),range('A','Z'));
    shuffle($chars1);
    shuffle($chars2);
    shuffle($chars3);
    $code1 = implode(array_slice($chars1, 0, $length_key1));
    $arr1 = array($code1);
    $key1 = implode("",$arr1);
    $code2 = implode(array_slice($chars2, 0, $length_key2));
    $arr2 = array($code2);
    $key2 = implode("",$arr2);
    $code3 = implode(array_slice($chars3, 0, $length_key3));
    $arr3 = array($code3);
    $key3 = implode("",$arr3);
	return $key1."".date('Y')."".$key2."".date('m')."".$key3;
}

function my_encrypt($data, $key) {
    // Remove the base64 encoding from our key
    $encryption_key = base64_decode("session_ifirstfix");
    // Generate an initialization vector
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    // Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
    // The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
    return base64_encode($encrypted . '::' . $iv);
}

	/**
	 * Returns decrypted original string
	 */
function my_decrypt($data, $key) {
    // Remove the base64 encoding from our key
    $encryption_key = base64_decode("session_ifirstfix");
    // To decrypt, split the encrypted data from our IV - our unique separator used was "::"
    list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
   
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
}

function Session_Encrypt($data,$key){

    $encrypted_string=openssl_encrypt($data,"AES-128-ECB",$key);
   
   	return $encrypted_string;
}

function Session_Decrypt($data,$key){   
    $decrypted_string=openssl_decrypt($data,"AES-128-ECB",$key);
    return $decrypted_string;
}


function checkPassword($User,$Password) {
	$Password = base64_encode($Password);
	$User = base64_encode($User);
	return base64_encode("ifirst".$User.$Password."fix");
}



function safeEncrypt($message,$key)
{

    $encrypted_string=openssl_encrypt($message,"AES-128-ECB",$key);
   
   	return $encrypted_string;
}

function safeDecrypt($message, $key)
{   
    $decrypted_string=openssl_decrypt($message,"AES-128-ECB",$key);
    return $decrypted_string;
}


function Send_email($subject,$from,$fromName,$body) {
			require_once("vendor/phpmailer/phpmailer/class.phpmailer.php");
			$mail = new PHPMailer();
			// $body = 'ifirstfix';
			$mail->IsSMTP();  // telling the class to use SMTP
			$mail->SMTPDebug  = SMTPDebug;
			$mail->SMTPAuth   = SMTPAuth;
			$mail->Port=Port;
			$mail->Host = Host;
			$mail->Username   = email_Username;
			$mail->Password   = email_Password;
			$mail->AddReplyTo(From,FromName);
			$mail->From       = AddReplyTo_From/*$from;*/;
			$mail->FromName   = AddReplyTo_FromName/* $fromName; */;
			$mail->CharSet = "utf-8"; //ตั้งเป็น UTF-8 เพื่อให้อ่านภาษาไทยได้
			$mail->AddAddress($from,$fromName);
			$mail->Subject  = $subject; // 'ifirstfix Register';
			$mail->WordWrap   = 80; // set word wrap
			$mail->MsgHTML($body);

		  if ( !$mail->Send() ) {
		  	// var_dump($mail->ErrorInfo);
		  	// exit();
		  	return "Mailer Error: " .$mail->ErrorInfo;
		  } else {
		    return  "true";
		  } 
}


function HTTPPost($url, array $params) {
		$response = array();
		$ch = curl_init();
		$query = http_build_query($params);
		curl_setopt($ch, CURLOPT_URL, $url);
		//------------------------------- ลบได้
		curl_setopt($ch, CURLOPT_SSLVERSION, 6);
		curl_setopt($ch, CURLOPT_ENCODING , "gzip");
		//------------------------------- 
		curl_setopt($ch, CURLOPT_USERAGENT,'okhttp/3.8.0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 40000); //-- ค่าเดิม10
		// $httpCode = curl_getinfo($ch , CURLINFO_HTTP_CODE); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch); 
		curl_close($ch);

		$final_result = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );	  
	    
		$response = array();
        if(count($final_result) >= 1){
					$response =  $final_result;
					$response['status'] = 'true';
					$response['code'] = '200';
  		}else{
  					$response['data'] = '';
  					$response['status'] = 'false';
					$response['code'] = '400';
  		}
        return $response;
}



function rm_dup_array($dep) {
	$array = explode(",", $dep);
    return implode(',', array_keys(array_flip($array)));
}

//oaid2000 check formate telephone
function check_tel($dep){
 $sub_tel_2 = substr($dep, 0, 2);

 if ($sub_tel_2 == "66") {
     if (preg_match("/^(6{2})(8|6|9{1})([0-9]){8}$/",$dep)) {
         return true;
     } else {
         return false;
     }
 }else{
     if (preg_match("/^(0{1})(8|6|9{1})([0-9]){8}$/",$dep)) {
         return true;
     } else {
         return false;
     }
 }
}

//oaid2000 convert 00 to 66
function convert_tel($dep){
	$sub_tel_1 = substr($dep, 0, 1);
	$sub_tel_2 = substr($dep, 0, 2);
 
	if ($sub_tel_2 == "66") {
		return $dep;
	}else{
		return preg_replace('/'.$sub_tel_1.'/', '66', $dep, 1);
	}
}

//oaid2000 check formate date
function check_date($dep){
    if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) (00|[0-9]|0[0-9]|1[0-9]|2[0-3]):([0-9]|[0-5][0-9])$/",$dep)) {
        return true;
    } else {
        return false;
    }
}

//oaid2000 check formate date
function check_msg_text($dep,$format=null){

	switch ($format) {
		case 'EU':
			    if (preg_match("/^[a-z0-9\A-Z\-\ \,\/\.\+\-\*\=\(\)\:\;\!\#\$\%\&\<\>\?\@\[\]\_\`\~\{\}\|]+$/",$dep)) {
			        return "E";
			    } else {
			        return "U";
			    }
			break;
		
		default:
		    if (preg_match("/^[a-z0-9\A-Z\-\ \,\/\.\+\-\*\=\(\)\:\;\!\#\$\%\&\<\>\?\@\[\]\_\`\~\{\}\|]+$/",$dep)) {
		        return true;
		    } else {
		        return false;
		    }
			break;
	}

}


//oaid2000 gen txidCM.7584.20200331053722.0859 "CM." & userid & getdateformat("yyyyMMddhhmmss") & "." & gettimeformat(Millisecond)[4ตำแหน่ง(ถ้ามี3ตำแหน่งให้เติม0ข้างหน้าให้ครบ4ตำแหน่ง)]
function format_datetime_ms($dep){
 $t = microtime(true);
 $micro = sprintf("%06d",($t - floor($t)) * 1000000);
 $d = new DateTime( date('Y-m-d H:i:s.'.$micro, $t) );

 return substr($d->format($dep),0,-2);
}


// oaid2000 Function to get the client IP address
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}


function getSESSION(){
	return safeDecrypt($_SESSION['employee_id'] ,WCMSetting::$ENCRYPT_EMPLOYEE);
}


function converse_filetext($file){
    $filename = file_get_contents($file);
    // $line = explode("\n", $filename);
    if (mb_check_encoding($filename, 'UTF-8')) {
        // header("Content-Type: text/html;charset=utf-8" );
        // $filename[0] = iconv("utf-8","tis-620",$filename[0]);
        // var_dump("if : ".$filename);
        return $filename;
        
    }else{
    	// var_dump($filename);
    	// header('Content-Type: text/html; charset=UTF-8');
		// header('Content-Type: text/html; charset=TIS-620');
		 // $filedata = iconv("utf-8","tis-620",$filename);
    	// $filedata =ConvertUTF8($filename);
		// var_dump($filedata);
    	return false;
        
    }
}


function mb_rawurlencode($url){
	$encoded='';
	$length=mb_strlen($url);
	for($i=0;$i<$length;$i++){
	$encoded.='%'.wordwrap(bin2hex(mb_substr($url,$i,1)),2,'%',true);
	}
	return $encoded;
}

//nut
function uploadFTP($destination_file,$source_file,$ftp_server,$ftp_user_name,$ftp_user_pass,$path_file = "/"){

	set_time_limit(3000);

	//set up basic connection
	// $ftp_server = "192.168.1.180";
	// $ftp_user_name = "user";
	// $ftp_user_pass = "nut8829nut";
	// $conn_id = ftp_connect($ftp_server);
	
	// $ftp_server = "192.168.0.21";
	// $ftp_user_name = "ftp@blocklist";
	// $ftp_user_pass = "123@ftp";
	$conn_id = ftp_connect($ftp_server);

	$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); 
	ftp_pasv($conn_id, true) or die("Unable switch to passive mode");
	ftp_chdir($conn_id,$path_file);
	
	if ((!$conn_id) || (!$login_result)) {
	 return "FTP connection has failed!";
	 exit;
	}

	$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);    

	if (!$upload) {
		ftp_close($conn_id); 
	 	return "FTP upload has failed!";
	}else{
		ftp_close($conn_id); 
		return true;
	}
}

//
function getdata_txt_file($text){
	$dataArray = array();
	$text_lines = explode("\n",$text);

	foreach ($text_lines as $text_line) {
		$dataArray_foreach = array();
		$tel = substr($text_line, 0, 11);
		$txt = substr($text_line, 12);
		$dataArray_foreach['tel'] = $tel;
		$dataArray_foreach['txt'] = $txt;
		// $dataArray_foreach['oper'] = $txt;
		// $dataArray[]=$dataArray_foreach;
		array_push($dataArray, $dataArray_foreach);
	}

	return $dataArray;
} 



function getdata_txt_Excel($inputFileName){

    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);  
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);  
    $objReader->setReadDataOnly(true);  
    $objPHPExcel = $objReader->load($inputFileName);  
    $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
    // $highestRow = $objWorksheet->getHighestRow();
    // $highestColumn = $objWorksheet->getHighestColumn();
    // $headingsArray = $objWorksheet->rangeToArray('A1:'.$highestColumn.'1',null, true, true, true);
    // $headingsArray = $headingsArray[1];

    $result = [];
    $rows = $objWorksheet->getRowIterator();
    $rowIndex = 0;
	 foreach ($rows as $row) {
	     $cellIndex = 0;
	     $cellIterator = $row->getCellIterator();
	     $cellIterator->setIterateOnlyExistingCells(false);
	     $result[++$rowIndex] = [];
	     foreach ($cellIterator as $cell) {
	         $result[$rowIndex][++$cellIndex] = (string) $cell->getCalculatedValue();
	     }
	 }
    return $result;
}



function is_english($str){
	if (strlen($str) != strlen(utf8_decode($str))) {
		return false;
	} else {
		return true;
	}
}

//oaid2000
function text_english($str){
    if (preg_match("/^[a-z0-9\A-Z]+$/",$str)) {
        return true;
    } else {
        return false;
    }

}

function checkUsername($usename){
	$DB = OMDb::singleton();
	$sql = "SELECT username FROM users WHERE username = @usename LIMIT 1";
    $sql_param	= array();
    $sql_param['usename'] = $usename;
    $ds = null;
    $res = $DB->query($ds, $sql, $sql_param, 0, -1, "ASSOC");

    return $res;
}

function encrypt_params($plainText, $password, $iv){
	$method = 'aes-256-cbc';
	// Must be exact 32 chars (256 bit)
	$key = substr(hash('sha256', $password, true), 0, 32);
	// IV must be exact 16 chars (128 bit)
	$iv = substr(hash('sha256', $iv, true), 0, 16);
	return base64_encode(openssl_encrypt($plainText, $method, $key, OPENSSL_RAW_DATA, $iv));
}

function decrypt_params($encrypted, $password, $iv){
	$method = 'aes-256-cbc';
	// Must be exact 32 chars (256 bit)
	$key = substr(hash('sha256', $password, true), 0, 32);
	// IV must be exact 16 chars (128 bit)
	$iv = substr(hash('sha256', $iv, true), 0, 16);
	return openssl_decrypt(base64_decode($encrypted), $method, $key, OPENSSL_RAW_DATA, $iv);
}

function array_has_dupes($array) {
   // streamline per @Felix
   return count($array) !== count(array_unique($array));
}

function generate_menu(){

	$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : false;
	$DB = OMDb::singleton();
	$sql = "SELECT * FROM v_menu WHERE user_id = @user_id";
	$sql_param = array();
	$sql_param['user_id'] = safeDecrypt($user_id, WCMSetting::$ENCRYPT_EMPLOYEE);
	$ds = null;
	$res = $DB->query($ds, $sql, $sql_param, 0, -1, "ASSOC");
	
	if ($res > 0) {
		$menuList = array();
		$arr_menu = array();
		foreach($ds as $v){
			$arr_menu['menu_name'] = $v['menu_name'];
			$arr_menu['menu_href'] = $v['menu_path'];
			// $arr_menu['menu_icon'] = isset($v['menulabel_icon']) ? $v['menulabel_icon'] : null;

			$menuList[$v['menulabel_name']]['menu_icon'] = isset($v['menulabel_icon']) ? $v['menulabel_icon'] : null;
			$menuList[$v['menulabel_name']]['sub_menu'][] = $arr_menu;

		}

		return $menuList;
	} else {
		return null;
	}
}
?>