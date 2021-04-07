<?php
namespace OMCore;
/*
 $sys  = new OMSystemParameter();

 $sys->webmaster;
 $sys->webmaster();
*/
//////////////////////////
class OMSetting
{
	private static $om_setting = null;
 	public static function singleton()
    {
		if ( self::$om_setting == null ) self::$om_setting = new OMSystemParameter();
		return self::$om_setting;
    }


	public static function val($name) {
		return $me = self::singleton()->$name;
	}


}

class OMSystemParameter
{
	private $cache_name = "sysparam";
	private $config = array();
	function __construct() {
		$dc = @file_get_contents(ROOT_DIR . "stocks/data_cache/".$this->cache_name.".cache");
		$this->config = @unserialize($dc);
	}

	function __get($name) {
		if( isset($this->config[$name])){
			return $this->config[$name];
		}
		return $name;
	}

}


?>