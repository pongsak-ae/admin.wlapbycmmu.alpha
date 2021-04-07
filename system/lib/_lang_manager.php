<?php

include("alert_msg_config.php");
include("display_txt_config.php");



function getAlertMessage($keys=array()){

	$r = array();

	$current_lang = getCurrentLang();
	$ALERT_MSG = json_decode(ALERT_MSG,true);

	$global_data = $ALERT_MSG["GLOBAL"][$current_lang];
	$r["GLOBAL"] = $global_data;

	if (count($keys) > 0) {

		foreach ($keys as $k => $v) {

			if (isset($ALERT_MSG[$v])) {
				$data = $ALERT_MSG[$v][$current_lang];
				$r[$v] = $data;
			}
		}

	}

	return $r;
}

function getDisplayText($keys=array()){

	$r = array();

	$current_lang = getCurrentLang();
	$DISPLAY_TEXT = json_decode(DISPLAY_TEXT,true);

	$global_data = $DISPLAY_TEXT["GLOBAL"][$current_lang];
	$r["GLOBAL"] = $global_data;

	if (count($keys) > 0) {

		foreach ($keys as $k => $v) {

			if (isset($DISPLAY_TEXT[$v])) {
				$data = $DISPLAY_TEXT[$v][$current_lang];
				$r[$v] = $data;
			}
			
		}

	}

	return $r;
}

function getCurrentLang(){

	$current_lang_key = "CURRENT_LANG";
	$current_lang = "TH";

	if (isset($_COOKIE["CURRENT_LANG"])) {
		$current_lang = $_COOKIE["CURRENT_LANG"];
	}

	return $current_lang;

}

function setLang($lang){

	$current_lang_key = "CURRENT_LANG";

	setcookie($current_lang_key, $lang);
}


?>