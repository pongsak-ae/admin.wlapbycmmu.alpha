<?php
namespace OMCore;

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

    static function GetRemoteIP() {
		if (isset($_SERVER["HTTP_CLIENTIP"])){
			return $_SERVER["HTTP_CLIENTIP"]; // Only load balance
		}

		if (isset($_SERVER["HTTP_X_FB_USER_REMOTE_ADDR"])){
			return $_SERVER["HTTP_X_FB_USER_REMOTE_ADDR"]; // Only facebook
		}

		if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
			$strIP = $_SERVER["HTTP_X_FORWARDED_FOR"];
			if ($strIP != "" && $strIP != "unknown" ){
				if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
					$forward_ip = explode(',' , $_SERVER['HTTP_X_FORWARDED_FOR']);
					foreach ($forward_ip as $ip) {
						if (!preg_match('/(^127\.0\.0\.1)|(^10\.)|(^172\.1[6-9]\.)|(^172\.2[0-9]\.)|(^172\.3[0-1]\.)|(^192\.168\.)/', trim($ip)) ){
							return trim($ip);
						}
					}
				}
			}
		}

		return $_SERVER["REMOTE_ADDR"];
		// return "203.0.0.10";

    }

}
?>
