<?php
    require_once('system/library/OMLANG.php');
    $obj = new OMLANG(LANG); $lang =  $obj->FNC_GET_LANG(); require $lang;

	$OMPage = new OMCore\OMPage();
    $OMRoute = new OMCore\OMRoute();
	ob_start();
    require $_controllerPath;
    $HTML_CONTENT = ob_get_contents();
    ob_end_clean();

	$theme_dir = "default";
    if(isset($theme)){
    	$theme_dir = TMPL_DIR . $theme ."/";
    }else{
    	$theme_dir = TMPL_DIR . $theme_dir ."/";
    }

include TMPL_DIR . 'core/html.tpl';
