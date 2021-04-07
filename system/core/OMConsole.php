<?php
namespace OMCore;

class OMConsole
{
	static $_code = 0;
    static function code() {
    	if(self::$_code == 0){
			self::$_code =  base_convert(microtime(true) , 10 ,35);
		}
		return self::$_code;
    }
    static function log($msg) {
    	if (!OM_DEBUG_ENABLED) return false;
		static $last_call = 0;
    	$session_code = self::code();

		$current_time = microtime(true);
		$baseName = end(explode('/', $_SERVER['SCRIPT_NAME']));
		$log_file = ROOT_DIR . "stocks/debug_logs/". $baseName ."-" . date('Y-m-d') . ".log";
		$fp = fopen($log_file, "a+");
		if ($fp) {
			if ($last_call != 0) {
				$diff = $current_time - $last_call;
			} else {
				$diff = 0;
			}
		//	fputs($fp, sprintf("%s (%f, D=%f): %s \n",date('Y-m-d H:i:s'),$current_time, $diff, $msg));
			fputs($fp, sprintf("%s %s (D=%f): %s \n",$session_code,date('Ymd H:i:s'), $diff, $msg));
			fflush($fp);
			fclose($fp);
		}
		$last_call = $current_time;
	}
}

?>