<?php
namespace OMCore;

use Memcache;

Class OMMemcache{
	private static $cObj = null;

	private static function singleton(){
		if(self::$cObj == null){
			 self::$cObj = new Memcache;
			 self::$cObj->connect(MEMCACHE_HOST, MEMCACHE_PORT);
		}
		return  self::$cObj;
	}

	public static function set($name,$data,$time = 0){
		$_obj = self::singleton();
		$_obj->set($name, $data, 0, $time);
		return $data;
	}
	public static function get($name){
		$_obj = self::singleton();
		return $_obj->get($name);
	}
	public static function delete($name){
		$_obj = self::singleton();
		return $_obj->delete($name);
	}
}
?>