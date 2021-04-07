<?php
header("Content-type: application/x-javascript; charset=utf-8");
// header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
// header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

$root_dir = realpath("../");

include $root_dir . '/system/common.php';
$current_path =  isset($_GET['p'])?$_GET['p']:"";


	readfile('../js/core/swfobject/swfobject.js');
	echo "\n";
	readfile('../js/core/ajax.js');
	echo "\n";
	readfile('../js/core/dimScreen.js');
	echo "\n";
	readfile('../js/core/util.js');
	echo "\n";
	readfile('../js/core/jquery.placeholder.js');
	echo "\n";
	readfile('../js/common.js');


if( isset($_GET['f'])){
	$files = explode(',', $_GET['f']);
	foreach ($files as $key ) {
		echo "\n";
		$fileJs = $root_dir.'/js/'.$key.'.js';
		if(is_file($fileJs))
			readfile($fileJs);
		else echo 'alert("'.$fileJs.' not found");';
	}
}
?>