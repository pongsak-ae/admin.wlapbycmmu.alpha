<?php
namespace OMCore;

use Cache_Lite;

Class OMCacheLite{
	private static $_cObj = null;
	private static $_LifTime = 29030400;

	public static function singketon(){
		if(self::$_cObj == null){
			 $options = array(
			    'cacheDir' => ROOT_DIR.'stocks/cache/',
			    'lifeTime' => self::$_LifTime
			);

			self::$_cObj = new Cache_Lite($options);

		}
		return  self::$_cObj;
	}

	public static function set($key, $data){
		$DObj = self::singketon();
		self::delete($key);
		if (!$DObj->get($key)) {
			$DObj->save($data);
		}
		return $data;
	}
	public static function get($key){
		$_obj = self::check($key);
		return $_obj;
	}
	public static function check($key){
		$DObj = self::singketon();
		if ($data = $DObj->get($key)) {
			return $data;
		}
		return "";
	}
	public static function delete($key){
		$DObj = self::singketon();
		return $DObj->remove($key);
	}
}
?>