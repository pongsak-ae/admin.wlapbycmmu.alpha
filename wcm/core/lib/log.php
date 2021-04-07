<?php
class OMLog {
	static $_CONTEXT_CURRENT_OBJECT_KEY = "OM_CURRENT_OMLog";
	private $_DB = null;
	function __construct($log_mode="", $log_level="all", $log_folder="", $DB=null, $DatabaseTableName="wcm_action_log", $rotate_mode="", $log_file_datetime_converter=null) {
		$this->_DB = $DB;
		$GLOBALS[self::$_CONTEXT_CURRENT_OBJECT_KEY] = $this;		
	}	
	static function Current() {
		return $GLOBALS[self::$_CONTEXT_CURRENT_OBJECT_KEY];
	}
	function writeActionLog($log_message = "", $write_case = "", $module_name = "", $action_type = "", $additional_message = "", $transaction_session = "", $user_id = 0, $ipaddress = null, $ref_id = 0, $ref_lang = "", $ref_rev = 1, $table_name = "wcm_action_log") {
		$rs;
		$now_date = OMDateTime::Now();
		
		if ($ipaddress == null) {
			$ipaddress = "0.0.0.0";
		}
		if ($transaction_session == null) {
			$transaction_session = "";
		}
		if ($user_id == null) {
			$user_id = OMSession::Current()->UserId;
			if ($user_id == 0) $user_id = 1;			
		}

		$p = array(
			"log_type"=>$write_case,
			"log_timestamp"=>$now_date,
			"transaction_session"=>$transaction_session,
			"log_message"=>$log_message,
			"module_name"=>$module_name,
			"action_type"=>$action_type,
			"additional_message"=>$additional_message,
			"user_id"=>$user_id,
			"ipaddress"=>$ipaddress,
			"ref_id"=>$ref_id,
			"ref_lang"=>$ref_lang,
			"ref_rev"=>$ref_rev
			);
		$rs = $this->_DB->executeInsert($table_name, $p);
		if ($rs > 0) {		
			return true;
		} else {			
			echo "ERROR write log" .  $this->_DB->LastErrorMessage;
			exit();
			return false;
		}
	}
}
?>