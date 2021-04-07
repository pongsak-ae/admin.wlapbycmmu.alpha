<?php
	require_once('../core/lib/all.php');
	
	if ($session->checkSession("only")) {
		$session->renewSessionCode();
		$loginname = $session->Username;
		$session->SecureCookie->setValue("wcm_user_c2","");
		OMLog::Current()->writeActionLog("Sign-out successful by \"" . $loginname . "\"", "Information", "System", "Authenticate", "");	
	} else {
	}
	header('Location: core_signin.php?' . uniqid());
?>