<?php
class OMNetwork {

    static function LongToIP($LongIP) {
		return long2ip($LongIP);
    }

    static function IPToLong($IP) {
		$long = ip2long($IP);
		if ($long == -1 || $long === FALSE) {
			return "-1";
		} else {
			return sprintf ("%u", $long);
		}
    }

    static function filter_ip($name) {

		$ret = filter_input(INPUT_SERVER, $name ,FILTER_VALIDATE_IP);
		if( $ret === FALSE){
			header('HTTP/1.0 400 Invalid request');
			header('status: 400 Invalid request');
			exit();
		}elseif ($ret === NULL) {
			$ret = '';
		}

		return $ret;
    }
    static function GetRemoteIP() {
    	$strIP = self::filter_ip("HTTP_CLIENTIP");
		if ($strIP != ''){
			return $strIP; // Only load balance
		}

    	$strIP = self::filter_ip("HTTP_X_FB_USER_REMOTE_ADDR");
		if ($strIP != ''){
			return $strIP; // Only facebook
		}
		$strIP = self::filter_ip("HTTP_X_FORWARDED_FOR");

		if ($strIP != "" && $strIP != "unknown" ){
			$forward_ip = explode(',' , $strIP );
			if(count($forward_ip) > 1){
				foreach ($forward_ip as $ip) {
					if (!preg_match('/(^127\.0\.0\.1)|(^10\.)|(^172\.1[6-9]\.)|(^172\.2[0-9]\.)|(^172\.3[0-1]\.)|(^192\.168\.)/', trim($ip)) ){
						return trim($ip);
					}
					$strIP = trim($ip);
				}
			}
			return $strIP;
		}

		return self::filter_ip("REMOTE_ADDR");
    }

}
?>
