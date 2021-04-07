<?php

require  '../system/common.php';
use OMCore\OMDb;
use OMCore\OMImage;
use OMCore\OMRoute;
use OMCore\OM as OM;


$mode = OM::GET("m","0");
$width = OM::GET("w","0");
$height = OM::GET("h","0");
$file = OM::GET("f","0");
$module = OM::GET("mo","0");
$subfolderGen = OM::GET("sd","");
$file_old = $file;
if($file == ""){ OMRoute::notFound(); }
$file_extention = explode(".", $file);
$file_extention = strtolower(end($file_extention));
if( preg_match('/(php|shtml|htm|html|jsp|sh|asp|aspx|txt|cfm|cgi)$/', $file_extention) ){
	OMRoute::notFound();
}

$sub =  explode("/", $file , -1);
$file = explode("/", $file);
array_pop($file);
array_pop($sub);
$file = end($file);

$path = "";
if(count($sub) > 0){
	$path =   implode('/', $sub)."/";
}else{
	$path = $subfolderGen;
}

// $pattern = '/^\w{2}\/\w{2}\/$/';
// $matchRow = preg_match($pattern, $path, $matches, PREG_OFFSET_CAPTURE);
// if($matchRow <= 0){
// 	http_response_code(404);
// 	OMRoute::notFound();
// 	exit();
// }

$ds = OMDb::table($module."_media_file")->where("uuname",$file)->where("upload_type != ?","DELETE");

if(count($ds) <= 0 || $ds == null){
	OMRoute::notFound();
	exit();
}

$file_type = $ds[0]["content_type"];
$original_name = $ds[0]["original_name"];

$path = "";
$file_extention = explode(".", $original_name);
$file_extention = strtolower(end($file_extention));
$dir = ROOT_DIR."stocks/".$module."/".$mode.$width."x".$height."/";

if(count($sub) > 0){
	$path =   implode('/', $sub)."/";
}else{
	$path = $subfolderGen;
}
$original_file = $dir.$file_old;
$target_file = $dir.$file_old;
if( !is_dir($dir.$path.$file)){
	mkdir($dir.$path.$file, 0777,true);
}

$m = "original";
if($file_type == "image/jpeg" || $file_type = "image/png" || $file_type == "image/gif"){

	if ($mode == "c") {
		$m = "crop";
	} else if ($mode == "l") {
		$m = "letterbox";
	} else if ($mode == "w") {
		$m = "fixwidth";
	} else if ($mode == "h") {
		$m = "fixheight";
	} else if ($mode == "s") {
		$m = "scale";
	} else if ($mode == "d") {
		$m = "scaledown";
	} else if ($mode == "o") {
		$m = "original";
	} else {
		exit();
	}
	if( !is_dir($dir.$path.$file)){
		mkdir($dir.$path.$file, 0777,true);
		chmod($dir.$path.$file, 0777);
	}
	chmod($dir.$path.$file, 0777);
}else{
	$m = "original";
}

$s = false;
if($m != "original" || !file_exists($target_file)){
	file_put_contents($target_file, $ds[0]["data"]);
	$s = OMImage::ResizeImage($target_file,$target_file,$width,$height,$file_extention,$m,null);

}else{
	file_put_contents($target_file, $ds[0]["data"]);
}

header("Content-Type: " . $file_type);
$rf = readfile($target_file);

?>