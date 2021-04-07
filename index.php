<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
// SET TIME SESSION
ini_set('session.gc_maxlifetime', 1800);
require_once 'system/common.php';
// OMCore\OMRoute::path();

$checkname = 0;
$OMRoute = new OMCore\OMRoute();
if(isset($_SESSION['loggedin'])){
	if($_SESSION['loggedin'] == true ){
		$checkname = 1;
	}
}

if (($checkname == 0) && ($OMRoute->path() != "login/index")) {
	header("Location: ".WEB_META_BASE_URL."en/login/");

} else {

	$_controllerPath = ROOT_DIR . "controllers/" . OMCore\OMRoute::path() . '.php';
	if (is_file($_controllerPath)) {
		include TMPL_DIR . 'core/master.tpl';
	} else {
		http_response_code(404);
		OMCore\OMRoute::notFound();
	}
}

?>

