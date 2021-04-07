<?php

use OMCore\OMDb;
// var_dump(WEB_META_BASE_URL);

$HOME_URL = WEB_META_BASE_URL"home";

$ALL_KEY = array();
$KEY = array('key' => 'HOME_TOP', 'url' => $HOME_URL);
$ALL_KEY[] = $KEY;
$KEY = array('key' => 'HOME_BOTTOM', 'url' => $HOME_URL);
$ALL_KEY[] = $KEY;


$DB = OMDb::singleton();

for ($i=0; $i < count($ALL_KEY) ; $i++) { 
	
	$key = $ALL_KEY[$i]["key"];
	$sql_params["global_key"] = $key;
	$sql_params["global_content_url"] = $key;

	$r1 = $DB->executeUpdate("global_content",1,$sql_params);
	$r2 = $DB->executeUpdate("global_content_draft",1,$sql_params);
}



?>