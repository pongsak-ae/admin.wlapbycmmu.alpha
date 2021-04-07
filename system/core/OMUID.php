<?php
namespace OMCore;

class OMUID{

	public static function uid(){
		if(self::check()){
			$uid = OMCookie::get("uid");
		}else{
			self::update();
		}
		return $uid;
	}


	public static function reset(){
		// setcookie("uid","", time()-1);
		return self::update();
	}

	private static function check(){
		return OMCookie::has("uid");
	}

	private static function save($uid){
		OMCookie::set("uid",$uid, UID_LIFE_TIME);
	}

	public static function update($uidGen = ""){
		if(isset($uidGen) && $uidGen != ""){
			$uid = $uidGen;
		}else{
			$time = microtime(true);
			$uid =  base_convert($time, 10, 36);
		}
		$data = self::save($uid);
		return $uid;
	}

}

?>