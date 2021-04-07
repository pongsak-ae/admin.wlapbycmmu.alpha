<?php
namespace OMCore;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

Class OMLog
{
	//type [ ERROR, WARNING, INFO ]
	public static function write($data, $type = "ERROR"){
		$debug = debug_backtrace()[0];
		$file = str_replace(ROOT_DIR, '', $debug["file"]);
		$line = $debug["line"];

		$path = ROOT_DIR."stocks/logs/";
		$dateNow = date("Ymd");

		if (!is_dir($path)) {
	    	mkdir($path,0755,true);
		}

		$arrLog = array("Line"=>$line);

		$log = new Logger($file);
		$log->pushHandler(new StreamHandler($path.$type."_".$dateNow.'.log', Logger::DEBUG));
		if($type == "WARNING"){
			$log->addWarning($data,$arrLog);
		}else if($type == "INFO"){
			$log->addInfo($data,$arrLog);
		}else{
			$log->addError($data,$arrLog);
		}
	}

}


?>