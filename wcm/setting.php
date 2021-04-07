<?php
class WCMSetting {
	//---------- Section 0: Client optional configuration --------
	//  This section reserved for client configuration
	//-------------------------------------------------------------------
	static $WINDOWS_TITLE = "wlapbycmmu";
	static $SYSTEM_TITLE_LINE1_1 = "wlapbycmmu";
	static $SYSTEM_TITLE_LINE1_2 = "System";
	static $SYSTEM_TITLE_LINE2_1 = "domainname.com";
	static $SYSTEM_TITLE_LINE2_2 = "website";
	static $HIDE_BRAND = true;
	static $ENCRYPT_EMPLOYEE = "KEYEMPLOYEE";
	static $ENCRYPT_LOGIN = "KEYEMPLOYEE";
	//---------- Section 1: Database and Host configuration -------- rjxP4Vdm7pzPigR7Ryq87Q==  mysql

	static $DEFAULT_DATABASE_CONNECTION_STRING = "43.229.79.168::wlap::minroot::H3c2/TkbDwThTFQz/eKwq2CVJfAGgMi9WimIMEtGHCg=::mysql";

	//---------- Section 1.1: Revision configuration --------
	static $MAX_SAVE_REVISION = 10;

	//---------- Section 2.1: Domain and folder configuration --------
	static $WEB_BASE_URL = "http://wlapbycmmu.alpha";
	static $ROOT_WCM_FOLDER = "../";
	static $STOCKS_FOLDER = "../../stocks/";

	//---------- Section 2.2: Folder configuration (Part 2) --------
	// Please do not change this section if you do not sure about it
	//-------------------------------------------------------------------
	static $CORE_IMAGE_FOLDER = "../core/images/";

	//---------- Section 3: Log configuration --------
	static $LOG_DEBUG_MODE = "both"; // 1) both = log in file and database, 2) file = log in file only, 3) database = log in database only
	static $LOG_DEBUG_CASE = "warning"; // 1) warning = log warning and error transaction, 2) error = log only error transaction, 3) none = do not log
	static $LOG_DEBUG_ROTATE = "daily"; // 1) monthly, 2) daily

	//---------- Section 4: Date & time format --------
	// Please do not change this section if you do not sure about it
	//-------------------------------------------------------------------
	static $DATETIME_FORMAT_IN_LOG_FILE = "yyyy-MM-dd HH:mm:ss";
	static $DATETIME_FORMAT_IN_DATABASE = "yyyyMMdd HH:mm:ss";
	static $DATETIME_FORMAT_IN_UI = "dd/MM/yyyy HH:mm";
	static $DATE_FORMAT_IN_UI = "dd/MM/yyyy";
	static $CULTUREINFO_FORMAT = "en-US";

	//---------- Section 5: Security configuration --------
	// We highly recommended to change this section before the launch website
	//-------------------------------------------------------------------
	static $ENCRYPT_INIT_KEY = "13579defabc12345";
	static $ENCRYPT_INIT_VECTOR = "de12fa890c79b387";
	static $COOKIE_LOGIN_TIMEOUT = 604800; //in seconds

	//---------- Section 6: General configuration --------
	// We highly recommended to change this section before the launch website
	//-------------------------------------------------------------------
	static $ADMIN_EMAIL_ADDRESS = "info@wlapbycmmu.com";
	static $FORGOT_PASSWORD_EMAIL_SUBJECT = "Please help me, I lost my password.";
	static $SYSTEM_EMAIL_ADDRESS = "info@wlapbycmmu.com";
	static $SYSTEM_EMAIL_NAME = "WCM";
	static $SMTP_SERVER_HOSTNAME = "mail.wlapbycmmu.com";
	static $SMTP_SERVER_PORT = "25";
	static $SMTP_SERVER_USERNAME = "info@wlapbycmmu.com";
	static $SMTP_SERVER_PASSWORD = "nut8829NUT";
	static $EMAIL_NOTIFY_BASE_URL = "";
	static $PREFIX_NOTIFY_EMAIL_SUBJECT = "[WCM]";

	//----------- Section 7: web.config reference -------------
	// Please do not change this section if you do not sure about it
	//-------------------------------------------------------------------

	static $LDAP_SETTING_SERVER = "127.0.0.1";
	static $LDAP_SETTING_PORT = "389";
	static $LDAP_SETTING_BASE_DN = "OU=sms,OU=Domain Controllers,DC=scbeic,DC=alpha";
	static $LDAP_SETTING_ACCOUNT_SUFFIX = "@scbeic.alpha";

	static $CLEAR_LAST_SEARCH = false;
}
?>
