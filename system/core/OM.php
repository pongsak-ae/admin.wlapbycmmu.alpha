<?php
namespace OMCore;

class OM {
	static function isAjaxCall($cmd="") {
		$isAjaxCall = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] =='XMLHttpRequest');
		return empty($cmd)?$isAjaxCall:(isset($_REQUEST[$cmd]) && $isAjaxCall);
	}
	static function json($data,$die=true) {
		if($die){
			echo json_encode($data);
			exit();
		}
		return json_encode($data);
	}
	static function redirect($uri,$relative=true,$replace = true,$code='302') {
		header("Location: ". (($relative)?WEB_META_BASE_URL:'') . $uri,$replace,$code);
		exit();
	}
	static function trim($data) {
		return trim($data);
	}
	static function POST($name, $default="") {
		$ret = $default;
		if (!empty($_POST[$name])) {
			$ret = $_POST[$name];
		}
		if(is_int($default)) $ret = intval($ret);
		return $ret;
	}
	static function GET($name, $default="") {
		$ret = $default;
		if (!empty($_GET[$name])) {
			$ret = $_GET[$name];
		}
		if(is_int($default)) $ret = intval($ret);
		return $ret;
	}
	static function intval($string, $concat = true) {
		$length = strlen($string);
		for ($i = 0, $int = '', $concat_flag = true; $i < $length; $i++) {
			if (is_numeric($string[$i]) && $concat_flag) {
				$int .= $string[$i];
			}elseif(!$concat && $concat_flag && strlen($int) > 0) {
				$concat_flag = false;
			}
		}

		return (int) $int;
	}

	static function strRandom($len = 32 ) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		$string ='';
		for ($p = 0; $p <= 4; $p++) {
			$string .= $characters[mt_rand(0, strlen($characters)-1)];
		}
		return $string;
	}
	static function strUnique($len = 1){
		return base_convert(self::nanotime(),10,36).self::strRandom($len);
	}
	static function nanotime(){
		list($usec, $sec) = explode(" ", microtime());
		return $sec.round($usec*1000000);
	}

	static function TrimWithDot($data /*String*/, $len /*Integer*/ = 100 , /*String */ $end = "..."){
		return mb_strimwidth($data, 0, $len, $end);
	}
	static function htmlDisplay($str,$displayLength=0,$end="..."){
		if($displayLength > 0 ){
			$str = self::TrimWithDot($str , $displayLength ,$end);
		}
		return htmlentities($str,ENT_QUOTES,"UTF-8");
	}
	static function cURL($mode=NULL,$url=NULL,$params=array(),$opts=array()){
		if(isset($mode) && $mode == "get"){
			$query_str = "";
			foreach ($params as $key => $value) {
				if($query_str != ""){
					$query_str .= "&";
				}
				$query_str .= $key."=".urlencode($value);
			}
			if($query_str != ""){
				$url .= "?".$query_str;
			}
		}else{
			$params = http_build_query($params);
			$opts[CURLOPT_POSTFIELDS] = $params;
		}
		
		// var_dump($params);
		$opts[CURLOPT_URL] = $url;
	    if (isset($opts[CURLOPT_HTTPHEADER])) {
	      $existing_headers = $opts[CURLOPT_HTTPHEADER];
	      $existing_headers[] = "Expect:";
	      $opts[CURLOPT_HTTPHEADER] = $existing_headers;
	    } else {
	      $opts[CURLOPT_HTTPHEADER] = array("Expect:","REQUESTFROM:127.0.0.1");
	    }
		$ch = curl_init();    // initialize curl handle
		curl_setopt_array($ch, $opts);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch); // run the whole process
		curl_close($ch);
		return $result;
	}
  /**
   * Makes an HTTP request. This method can be overridden by subclasses if
   * developers want to do fancier things or use something other than curl to
   * make the request.
   *
   * @param string $url The URL to make the request to
   * @param array $params The parameters to use for the POST body
   * @param CurlHandler $ch Initialized curl handle
   * @param isFileUploadSupport
   *
   * @return string The response text
   */
  function qsencode ($data) {
        $req = "";
        foreach ( $data as $key => $value )
                $req .= $key . '=' . urlencode( stripslashes($value) ) . '&';

        // Cut the last '&'
        $req=substr($req,0,strlen($req)-1);
        return $req;
}


function http($host, $path, $data =array(), $port = 80) {

        // $req = _recaptcha_qsencode ($data);
        $req = self::qsencode ($data);

        $http_request  = "POST $path HTTP/1.0\r\n";
        $http_request .= "Host: $host\r\n";
        $http_request .= "Content-Type: application/x-www-form-urlencoded;\r\n";
        $http_request .= "Content-Length: " . strlen($req) . "\r\n";
        $http_request .= "User-Agent: CAPTCHAs/PHP\r\n";
        $http_request .= "\r\n";
        $http_request .= $req;

        $response = '';
        if( false == ( $fs = @fsockopen($host, $port, $errno, $errstr, 10) ) ) {
                die ('Could not open socket');
        }

        fwrite($fs, $http_request);

        while ( !feof($fs) )
                $response .= fgets($fs, 1160); // One TCP-IP packet
        fclose($fs);
        $response = explode("\r\n\r\n", $response);
		 // $answers = explode ("\n", $response [1]);
        return $response [1];
}

 	static function request($url, $params = array(), $ch=null ) {
		if (!$ch) {
		  $ch = curl_init();
		}

		if(!empty($params) )
			$opts[CURLOPT_POSTFIELDS] = http_build_query($params, null, '&');
		$opts[CURLOPT_URL] = $url;


		curl_setopt_array($ch, $opts);
		$result = curl_exec($ch);

		curl_close($ch);
		return $result;
  	}


}

?>