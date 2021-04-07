<?php
require_once 'system/common.php';
// header("Content-Type: text/plain");
$_controllerPath = ROOT_DIR . "controllers/" . OMCore\OMRoute::path() . '.php';
// $_controllerPath = ROOT_DIR ."controllers/". $_GET['f'] . ".php";
// var_dump(OMRoute::args());
if (is_file($_controllerPath)) {
	require $_controllerPath;
} else {
	echo "\nAjax request not found";
}

?>