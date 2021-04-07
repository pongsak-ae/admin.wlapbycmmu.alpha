<?php
	require_once('../core/lib/all.php');
	$command = OMStringUtils::_TRIMPOST("command");
	$loginname =OMStringUtils::_TRIMPOST("loginname");
	$password = OMStringUtils::_TRIMPOST("password");
	$new_password1 = OMStringUtils::_TRIMPOST("new_password1");
	$new_password2 = OMStringUtils::_TRIMPOST("new_password2");
	$submit_change_passwd = OMStringUtils::_TRIMPOST("submit_change_passwd");
	$PageReferer = OMStringUtils::_TRIMPOST("refer");

	if($command == "login") {
		if($loginname == "") {
			header('Location: ../core/core_signin.php?err=username&refer=' . $PageReferer);
		} else {
			$temp_user_id = $session->checkLogin($loginname, $password);
			if($temp_user_id != 0 ) {
			        $session->renewSessionCode();
			        $session->saveSessionCookie();
					OMLog::Current()->writeActionLog("Sign-in successful by \"" . $loginname . "\"", "Information", "System", "Authenticate", "");
					if (isset(WCMSetting::$CLEAR_LAST_SEARCH) && WCMSetting::$CLEAR_LAST_SEARCH == true) {
						$DB->execute("delete from wcm_user_cookie where user_id = @user_id and cookie_name like 'now_%'", array("user_id"=>$temp_user_id));
					}
				if($PageReferer != "" && strpos(PageReferer,"core_no_permission.php") == FALSE){
					header('Location: ' . $PageReferer);
				} else {
					header('Location: ../dashboard/dashboard.php');
				}
			} else {

				if(! empty($session->CustomLoginResult["msg"]) ){
					$session->SecureCookie->setValue("emsg", $session->CustomLoginResult["msg"]);
					header('Location: ../core/core_signin.php?err=emsg&refer=' . $PageReferer);
					exit();
				}
				OMLog::Current()->writeActionLog("Sign-in failed by \"" . $loginname . "\"", "Warning", "System", "Authenticate", "The username and password are invalided.");
				header('Location: ../core/core_signin.php?err=login_fail&refer=' . $PageReferer);
			}
		}
	} else if($command == "change_passwd") {
		if($submit_change_passwd == "Cancel") {
			header('Location: ../core/core_signin.php?refer=' . $PageReferer);
		} else if($loginname == "") {
			header('Location: ../core/core_signin.php?command=change_passwd&err=username&refer=' . $PageReferer);
		} else if($new_password1 != $new_password2) {
			header('Location: ../core/core_signin.php?command=change_passwd&err=mismatch&refer=' . $PageReferer);
		} else if($new_password1 == "") {
			header('Location: ../core/core_signin.php?command=change_passwd&err=passwd&refer=' . $PageReferer);
		} else if($submit_change_passwd == "Change") {
			if(OMSession::Current()->changeUserPassword($loginname, $password, $new_password1)) {
				OMLog::Current()->writeActionLog("Password was changed by \"" . $loginname . "\"", "Information", "System", "Update", "");
				header('Location: ../core/core_signin.php?err=change_success&refer=' . $PageReferer);
			} else {
				OMLog::Current()->writeActionLog("Failed to change password for \"" . $loginname . "\"", "Warning", "System", "Update", "The username and old password are invalided.");
				header('Location: ../core/core_signin.php?command=change_passwd&err=user_passwd&refer=' . $PageReferer);
			}
		} else {
			header('Location: ../core/core_signin.php?command=change_passwd&refer=' . $PageReferer);
		}
	} else {
		echo "Unknow command"; exit();
		header('Location: ../core/core_signin.php');
	}
?>