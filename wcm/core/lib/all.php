<?php
error_reporting(E_ERROR | E_PARSE | E_WARNING);
// error_reporting(E_ALL);
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
if (!defined('ROOT_DIR')) {
	$__file__ = explode( "wcm".DS , __FILE__ );
    define('ROOT_DIR', $__file__[0] );
}

require_once(dirname(__FILE__) . '/../../setting.php');
require_once('datetime.php');
require_once('datetimeconverter.php');
require_once('datagrid.php');
require_once('database.php');
require_once('crypto.php');
require_once('cookie.php');
require_once('json.php');
require_once('log.php');
require_once('session.php');
require_once('stringutils.php');
require_once('template.php');
require_once('upload.php');
require_once('wcmutil.php');
require_once('validate.php');
require_once('image.php');
require_once('message.php');
require_once('network.php');
require_once('mail.php');


$CRYPTO = new OMCrypto(WCMSetting::$ENCRYPT_INIT_KEY, WCMSetting::$ENCRYPT_INIT_VECTOR);

$DB = new OMDatabase(WCMSetting::$DEFAULT_DATABASE_CONNECTION_STRING);
$session = new OMSession($CRYPTO, $DB);
$SESSION = $session;
$log = new OMLog("BOTH", "ALL", "logs/", $DB, "wcm_log", "daily", new OMDateTimeConverter(WCMSetting::$DATETIME_FORMAT_IN_LOG_FILE, WCMSetting::$CULTUREINFO_FORMAT));

?>