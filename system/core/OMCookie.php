<?php
namespace OMCore;

class OMCookie
{
 	public static function has($CookieName) {
		return isset($_COOKIE[$CookieName]);
	}
	public static function get($CookieName) {
		if(!self::has($CookieName)) return '';
		$crypto = new OMCrypto();
		// var_dump($crypto->Decrypt($_COOKIE[$CookieName]));
		return $crypto->Decrypt($_COOKIE[$CookieName]);
	}
	public static function set($CookieName, $value, $ExpiredSecond,  $path = '/') {

		if(empty($ExpiredSecond)){
			$ExpiredSecond = null;
		}else{
			$ExpiredSecond = intval($ExpiredSecond);
		}
		$crypto = new OMCrypto();
		$result = setcookie ($CookieName,  $crypto->Encrypt($value),$ExpiredSecond, $path , COOKIE_DOMAIN ,false,true);

		if(!$result){
			print( "Error: cookies unable to setup.");
			exit();
		}
		return $result;
	}
}
?>