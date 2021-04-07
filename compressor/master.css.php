<?php
header("Content-type: text/css");
$root_dir = realpath("../");
//readfile($root_dir.'/css/site.css');
//readfile($root_dir.'/css/bootstrap/bootstrap.min.css');

if( isset($_GET['f'])){
	$files = explode(',', $_GET['f']);
	foreach ($files as $key ) {
		readfile($root_dir.'/css/'.$key.'.css');
	}
}

?>
