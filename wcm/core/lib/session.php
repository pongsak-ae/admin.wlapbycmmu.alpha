<?php
//require_once('all.php');
class OMSession
{

	// VAR
	private $_Cryptor = null;
	private $_Cookie = null;
	private $_CookieDb = null;
	private $_DB = null;
	private $_PermissionList = array();
	private $_ActiveUsername = "";
	private $_ActiveUserId = 0;
	private $_ActivePassword = "";
	private $_ActiveSessionCode = "";
	private $_DbTable_User = "wcm_user";
	private $_DbTable_Permission = "wcm_permission";
	private $_DbTable_User_Permission = "wcm_user_permission";
	private $_DbTable_Role = "wcm_role";
	private $_DbTable_Role_Permission = "wcm_role_permission";
	private $_COOKIE_LOGIN_TIMEOUT = 0;
	//private $_CONTEXT_CURRENT_OBJECT_KEY = "OM_CURRENT_OMSession";
	static $_CONTEXT_CURRENT_OBJECT_KEY = "OM_CURRENT_OMSession";
	private $_EMPTY_SESSION_CODE = "OM_EMPTY_SESSION_CODE";
	private $_datetime_converter = null;

	private $_Custom_Login  = null;
	private $_Custom_CheckPermission  = null;
	private $_Custom_Login_Result = null;

	// Constructor
	function __construct($crypto, $database) {
		$this->_COOKIE_LOGIN_TIMEOUT = WCMSetting::$COOKIE_LOGIN_TIMEOUT;
		$GLOBALS[self::$_CONTEXT_CURRENT_OBJECT_KEY] = $this;

		$this->_Cryptor = $crypto;
		$this->_DB = $database;
		$this->_Cookie = new OMSecureCookie($this->_Cryptor);
		$this->_datetime_converter = new OMDateTimeConverter();
		$this->_PermissionList = array();

	}
	// Properties Handle
	function __get($name) {
		switch ($name) {
			case 'Username':
				return $this->_ActiveUsername;
				break;
			case 'UserId':
				return $this->_ActiveUserId;
				break;
			case 'SessionCode':
				return $this->_ActiveSessionCode;
				break;
			case 'Current':
				return $GLOBALS[self::$_CONTEXT_CURRENT_OBJECT_KEY];
				break;
			case 'SecureCookie':
				return $this->_Cookie;
				break;
			case 'CookieDb':
				return $this->_CookieDb;
				break;
			case 'PermissionList':
				return $this->_PermissionList;
				break;
			case 'CustomLoginResult':
				return $this->_Custom_Login_Result;
				break;
		}
		user_error("Invalid property: " . __CLASS__ . "->" . $name);
	}
	function __set($name, $value) {
		switch ($name) {
			case 'CustomLogin':
				$this->_Custom_Login = $value;
				return;
				break;
			case 'CustomCheckPermission':
				$this->_Custom_CheckPermission = $value;
				return;
				break;
		}
		user_error("Can't set property: " . __CLASS__ . "->" . $name);
	}

	static function Current() {
		if (isset($GLOBALS[self::$_CONTEXT_CURRENT_OBJECT_KEY])) {
			return $GLOBALS[self::$_CONTEXT_CURRENT_OBJECT_KEY];
		} else {
			return null;
		}
	}

	function checkSession($deny_mode="only") {
		$cookie_username = $this->SecureCookie->getValue("wcm_user_c1");
		$cookie_password = $this->SecureCookie->getValue("wcm_user_c2");
		$cookie_datetime = $this->SecureCookie->getValue("wcm_user_c3");
		$cookie_session_code = $this->SecureCookie->getValue("wcm_user_c4");
		$last_access_date  = new OMDateTime($cookie_datetime);
		$last_access_date = $last_access_date->AddSeconds($this->_COOKIE_LOGIN_TIMEOUT);
		if ($last_access_date > OMDateTime::Now()) {
			$user_id = $this->checkLogin($cookie_username, $cookie_password, $cookie_session_code);
			if ($user_id > 0) {
				$this->_ActiveSessionCode = $cookie_session_code;
				$this->SecureCookie->setValue("wcm_user_c3", OMDateTime::Now()->toString(), $this->_COOKIE_LOGIN_TIMEOUT);
				return true;
			}
		}

		if (strtolower($deny_mode) != "only") {
			$this->denySession($deny_mode);
		}
		return false;
	}
	function checkLogin($username, $password, $session_code = "OM_EMPTY_SESSION_CODE") {
		if ($this->_Custom_Login != null && function_exists($this->_Custom_Login)) {
			$CustomLoginResult = "";
			$id = call_user_func_array($this->_Custom_Login,array($username, $password, $session_code , &$CustomLoginResult));
			$this->_Custom_Login_Result = $CustomLoginResult;
			// echo "[" .  $this->_Custom_Login_Result . "," . $CustomLoginResult . "]";
			if ($id > 0) {
				$this->_ActiveUserId = $id;
				$this->_CookieDb = new OMCookieDB($this->_DB, $this->_ActiveUserId);
				$this->_ActiveUsername = $username;
				$this->_ActivePassword = $password;
				$this->loadPermission();
				return $this->_ActiveUserId;
			} else {
				$this->_ActiveUserId = 0;
				$this->_CookieDb = null;
				$this->_ActiveUsername = "";
				$this->_ActivePassword = "";
				return 0;
			}
		}

		$dt="";
		$sql = "select user_id from " . $this->_DbTable_User . " where obj_status = 'active' and username = @username and password = @password ";
		if ($session_code != "OM_EMPTY_SESSION_CODE") {
			$sql .= " and session_code =@session_code ";
		}
		$r = $this->_DB->query($dt, $sql, array("@username"=>$username, "@password"=>OMCrypto::HashMD5($password), "@session_code"=>$session_code));
		if ($r > 0) {
			$this->_ActiveUserId = $dt[0]["user_id"];
			$this->_CookieDb = new OMCookieDB($this->_DB, $this->_ActiveUserId);
			$this->_ActiveUsername = $username;
			$this->_ActivePassword = $password;
			$this->loadPermission();
			return $this->_ActiveUserId;
		} else {
			$this->_ActiveUserId = 0;
			$this->_CookieDb = null;
			$this->_ActiveUsername = "";
			$this->_ActivePassword = "";
			return 0;
		}
	}
	function loadPermission() {
		$this->_PermissionList = array();
		$this->loadRolePermission();
		$this->loadIndividualPermission();
	}
	function loadRolePermission() {
		$dt = null;
		$sql = "select " . $this->_DbTable_Permission . ".permission_code " .
						"from " . $this->_DbTable_User_Permission . " " .
						"	inner join " . $this->_DbTable_Role . " on " . $this->_DbTable_Role . ".role_id = " . $this->_DbTable_User_Permission . ".object_id and " . $this->_DbTable_User_Permission . ".is_role='T' " .
						"	inner join " . $this->_DbTable_Role_Permission . " on " . $this->_DbTable_Role_Permission . ".role_id = " . $this->_DbTable_Role . ".role_id " .
						"	inner join " . $this->_DbTable_Permission . " on " . $this->_DbTable_Permission . ".permission_id = " . $this->_DbTable_Role_Permission . ".permission_id " .
						"where " . $this->_DbTable_User_Permission . ".user_id = @user_id ";
		$r = $this->_DB->query($dt, $sql, array("@user_id"=> $this->_ActiveUserId));
		if ($r > 0) {
			foreach ($dt as $row) {
				$this->_PermissionList[$row["permission_code"]] = "T";
			}
		}
	}
	function loadIndividualPermission() {
		$dt = null;
		$sql = "select " . $this->_DbTable_Permission . ".permission_code from " . $this->_DbTable_User_Permission . " inner join " . $this->_DbTable_Permission . " on " . $this->_DbTable_User_Permission . ".object_id = " . $this->_DbTable_Permission . ".permission_id where " . $this->_DbTable_User_Permission . ".user_id = @user_id and " . $this->_DbTable_User_Permission . ".is_role = 'F' ";
		$r = $this->_DB->query($dt, $sql, array("@user_id"=>$this->_ActiveUserId));
		if ($r > 0) {
			foreach ($dt as $row) {
				$this->_PermissionList[$row["permission_code"]] = "T";
			}
		}
	}
	function renewSessionCode() {
		$this->_ActiveSessionCode = OMStringUtils::randomStringGenerator(16) . $this->_datetime_converter->toString(OMDateTime::Now(), "yyyyMMdd");
		$this->_DB->executeUpdate($this->_DbTable_User, 1, array("user_id"=>$this->_ActiveUserId, "session_code"=>$this->_ActiveSessionCode));
	}
	function saveSessionCookie() {
		$this->SecureCookie->setValue("wcm_user_c1", $this->_ActiveUsername);
		$this->SecureCookie->setValue("wcm_user_c2", $this->_ActivePassword, $this->_COOKIE_LOGIN_TIMEOUT);
		$this->SecureCookie->setValue("wcm_user_c3", OMDateTime::Now()->toString(), $this->_COOKIE_LOGIN_TIMEOUT);
		$this->SecureCookie->setValue("wcm_user_c4", $this->_ActiveSessionCode, $this->_COOKIE_LOGIN_TIMEOUT);
	}
	function checkPermission($permission_code, $deny_mode='') {
		if ($this->_Custom_CheckPermission != null && function_exists($this->_Custom_CheckPermission)) {
			$permission_result = call_user_func_array($this->_Custom_CheckPermission,array($permission_code, $this));
			if ($permission_result == true) {
				return true;
			} else {
				return $this->denyPermission($permission_code, strtolower($deny_mode));
			}
		}

		if (isset($permission_code) && $permission_code != "" && isset($this->_PermissionList[$permission_code]) && $this->_PermissionList[$permission_code] == 'T') return true;
		return $this->denyPermission($permission_code, strtolower($deny_mode));
	}
	function denyPermission($permission_code, $deny_mode = "") {
		$PageReferer = "";
		$kn = $permission_code;
		$kg = "";
		$username = "";
		$dt = null;
		$r;
		$deny_mode = strtolower($deny_mode);
		if ($deny_mode == "redirect") {
			$username = "Anonymous";
			if (OMSession::Current() !=null) {
				$username = OMSession::Current()->Username;
			} else {

			}


			$sql = "select * from " . $this->_DbTable_Permission . " where  permission_code=@permission_code";
			$r = $this->_DB->query($dt, $sql, array("@permission_code"=>$permission_code));
			if ($r > 0) {
				$kg = $dt[0]["permission_group"];
			} else {
				$kg = "UNKNOWN";
			}

			$PageReferer = filter_input(INPUT_SERVER, 'REQUEST_URI' );

			OMLog::Current()->writeActionLog("Permission denied to call the \"" . $kn . "\" operation in \"" . $kg . "\" module",
											"Warning",
											"System",
											"Access",
											"\"" . $kn . "\" permission key in \"" . $kg . "\" module are required to perform the operation.");
		}
		if ($deny_mode == "redirect") {
			ob_clean();
			header("Location: ../core/core_no_permission.php?refer=" . rawurlencode($PageReferer) . "&kn=" . rawurlencode($kn) . "&kg=" . rawurlencode($kg));
			exit();
			return;
		}
		if ($deny_mode == "display") {
			echo "ACCESS DENIED";
		}
		if ($deny_mode == "redirectjs") {
			ob_clean();
			echo "<script language=\"javascript\">";
			echo "Redirect('../core/core_signin.php?refer=" . rawurlencode($PageReferer) . "&kn=" . rawurlencode($kn) . "&kg=" . rawurlencode($kg) . "');";
			echo "</script>";
			exit();
			return;
		}
		if ($deny_mode == "redirectjs_parent") {
			ob_clean();
			echo "<script language=\"javascript\">";
			echo "parent.Redirect('../core/core_signin.php?refer=" . rawurlencode($PageReferer) . "&kn=" . $rawurlencode($kn) . "&kg=" . $rawurlencode($kg) . "');";
			echo "</script>";
			exit();
			return;
		}
		return;
	}
	function denySession($deny_mode) {
		$PageReferer = "";
		$deny_mode = strtolower($deny_mode);
		$PageReferer = filter_input(INPUT_SERVER, 'REQUEST_URI' );
		if ($deny_mode == "redirect") {
			header('Location: ../core/core_signin.php?refer=' . rawurlencode($PageReferer) . '');
			exit();
			return;
		}
		if ($deny_mode == "display") {
			echo "ACCESS DENIED";
			return;
		}
		if ($deny_mode == "redirectjs") {
			echo "<script language=\"javascript\">";
			echo "Redirect('../core/core_signin.php?refer=" . rawurlencode($PageReferer) . "');";
			echo "</script>";
			exit();
		}
		if ($deny_mode == "redirectjs_parent") {

			echo "<script language=\"javascript\">";
			echo "parent.Redirect('../core/core_signin.php?refer=" . rawurlencode($PageReferer) . "');";
			echo "</script>";
			exit();
		}
		return;
	}

    function changeUserPassword($username, $password, $new_password) {
        $r = null;
        $p = array();
        $p["@username"] = $username;
        $p["@password"] = OMCrypto::HashMD5($password);
        $p["@new_password"] = OMCrypto::HashMD5($new_password);
        $sql = "update " . $this->_DbTable_User . " set password = @new_password where username = @username and password = @password";
        $r = $this->_DB->execute($sql, $p);
        if ($r > 0) {
            return true;
        } else {
            return false;
        }
    }
}
?>