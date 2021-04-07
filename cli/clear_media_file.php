<?php

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}
if (!defined('ROOT_DIR')) {
	$__file__ = explode( "cli".DS , __FILE__ );
    define('ROOT_DIR', $__file__[0] );
}
require ROOT_DIR . 'system/common.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

use OMCore\OMDb;

$ConnectionString = WCMSetting::$DEFAULT_DATABASE_CONNECTION_STRING;
$connect = explode("::",$ConnectionString);

$dbname = $connect[1];

$DB = OMCore\OMDb::singleton();
$TABLE_NAME = "Tables_in_" . $dbname;
$total = $DB->query($dtTable , "show TABLES  WHERE $TABLE_NAME like '%_media_file';");
 if($total > 0){

    	foreach ($dtTable as $row) {
    		echo "\n" . $row["$TABLE_NAME"] . " Start clear";
    		echo ($row["$TABLE_NAME"] . " Start clear");
			$rs = OMDb::table($row["$TABLE_NAME"])->where("upload_type = 'DELETE' OR ref_id = '0'")->delete();
			if($rs === false){
				echo "Error: check log files ";
				echo ("Error: check log files ");
				exit();
			}


    		$tblname = str_replace("_media_file" , "" ,$row["$TABLE_NAME"]);
    		if($tblname == "financial_market"){
    			continue;
    		}
    		$ids_active = OMDb::table($tblname."_draft")->select($tblname . "_id")->where("obj_status='active'");//->result_array();
    		if(count($ids_active) > 0 ){



				$sql = "SELECT uuname FROM `${tblname}_media_file` m left join  ${tblname}_draft d on (m.ref_id = d.${tblname}_id) where d.obj_status='deleted'   or d.obj_state='deleted'";
	    		$rs = OMDb::singleton()->query($ds ,$sql );
	    		foreach ($ds as $media) {
					$rs = OMDb::table($row["$TABLE_NAME"])->where("uuname = '" .$media['uuname']. "'" )->delete();
					if($rs === false){
						echo "Error: check log files ";
						echo ("Error: check log files ");
						exit();
					}
	    		}
    		}else{
		    	echo ("Not found table :".$tblname . "_draft");
    		}

    	}

    }else{
    	echo ("Not found");
    }


?>