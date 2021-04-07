<?php
namespace OMCore;

use Cache_Lite;

Class OMDict{

	private static $_text = "";
	private static $_scope = "*";
	private static $_options = array(
		    'cacheDir' => ROOT_DIR.'stocks/cache/',
		    'lifeTime' => 29030400
		);


	public static function getDict($text,$scope = "*"){
		self::$_text = $text;
		self::$_scope = $scope;

		$chkD = self::checkDict();
		if($chkD){
			return self::genDict($chkD);
		}else{
			return self::genDict(self::resetDict());
		}
	}

	public static function setDict($key,$data){
		$cacheLite = new Cache_Lite(self::$_options);
		if (!$cacheLite->get($key)) {
			$cacheLite->save($data);
		}
		return $data;
		// exit();
	}

	public static function genDict($DData){
		if(!isset(self::$_scope) && self::$_scope == ""){
			self::$_scope = OMRoute::path();
		}
		$dict = unserialize($DData);
		$scope_global = "*";
		$target_language = self::getLang();
		if(isset($dict[self::$_text])) {
			if (isset($dict[self::$_text][$target_language][self::$_scope])) {
				return $dict[self::$_text][$target_language][self::$_scope];
			}else if (isset($dict[self::$_text][$target_language][$scope_global])) {
				return $dict[self::$_text][$target_language][$scope_global];
			} else {
				return self::$_text;
			}
		} else {
			return self::$_text;
		}
	}

	public static function removeDict($key){

		$cacheLite = new Cache_Lite(self::$_options);
		$cacheLite->remove($key);
	}

	public static function resetDict(){
		$dt = OMDb::table("translate");
		$dict = array();
		if (count($dt) > 0) {
			foreach($dt as $row) {
				if (!isset($dict[$row['translate_name']])) {
					$dict[$row['translate_name']] = array();
					$dict[$row['translate_name']][strtolower($row['obj_lang'])] = array();
				}
				$scope = trim($row['translate_scope']);
				if ($scope == '') {
					$scope = '*';
				}
				$dict[$row['translate_name']][strtolower($row['obj_lang'])][$scope] = $row['translate_text'];
			}
		}
		self::setDict("data_dict",serialize($dict));

		return serialize($dict);
	}

	private static function checkDict(){

		$cacheLite = new Cache_Lite(self::$_options);

		if($dict_cache = $cacheLite->get("data_dict")){
			return $dict_cache;
		}else{
			return false;
		}
	}

	private static function getLang(){
		$default_lang = "eng";
		if (defined('LANG')) {
			$target_language = LANG;
		} else {
			$target_language = $default_lang;
		}

		return $target_language;
	}

}
?>