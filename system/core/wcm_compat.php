<?php

	$WCM_PATH = realpath(dirname(__FILE__) . "/../../wcm") ;
	function checkClassExists($class , $path){
		if (!class_exists($class)) {
			include($path);
		}
	}
	checkClassExists("OMWCMUtil",$WCM_PATH . '/core/lib/wcmutil.php');
	checkClassExists("OMSession",$WCM_PATH . '/core/lib/session.php');
	checkClassExists("OMLog",$WCM_PATH . '/core/lib/log.php');

	checkClassExists("OMCrypto",$WCM_PATH . '/core/lib/crypto.php');
	checkClassExists("OMSecureCookie",$WCM_PATH . '/core/lib/cookie.php');
	checkClassExists("OMJson",$WCM_PATH . '/core/lib/json.php');

	$WCM_CRYPTO = new OMCrypto(WCMSetting::$ENCRYPT_INIT_KEY, WCMSetting::$ENCRYPT_INIT_VECTOR);
	if (OMSession::Current()==null) {
		$WCM_SESSION = new OMSession($WCM_CRYPTO, $DB);
	} else {
		$WCM_SESSION = OMSession::Current();
	}

	$WCM_LOG = new OMLog("BOTH", "ALL", "logs/", $DB, "wcm_log", "daily", new OMDateTimeConverter(WCMSetting::$DATETIME_FORMAT_IN_LOG_FILE, WCMSetting::$CULTUREINFO_FORMAT));
?>