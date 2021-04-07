<?php
namespace OMCore;

// use MONOLOG;

class OMPage {
	public $breadcrumb = "";
	public $activeMenu = "";
	public $sharedImage;
	public $var = array();
	function getVar($name) {
		if(empty($this->var[$name])){
			return OMSetting::val($name);
		}
		return $this->var[$name];
	}
	function setBreadcrumb($breadcrumb) {
		$i =0 ;
		foreach ($breadcrumb as $key => $item) {
			if($i != 0){
				$this->breadcrumb .= " / ";
			}
			if($i == count($breadcrumb)-1){
				$this->breadcrumb .= "<span>".OM::TrimWithDot($key,100)."</span>";
			}else{
				$this->breadcrumb .= "<a href=".$item." >".OM::TrimWithDot($key,100)."</a>";
			}
			$i++;
		}
	}
	function getTitle($title,$subTitle = "",$elm = "") {
		$newTitle = $title;
		if($subTitle != "" && $subTitle != $elm){
			$newTitle = $title." :: ".$subTitle;
		}
		return $newTitle;
	}

	function setActiveMenu($title) {
		$this->activeMenu = $title;
	}

	function merge_media($mediatype) {
		global $PAGE_VAR;
		$joinStr = (empty($PAGE_VAR[$mediatype]) ? "":"?f=".implode(',', $PAGE_VAR[$mediatype]));

		if($mediatype == 'js' && OMRoute::dir() != "/"){

			$joinStr .= (($joinStr != "")?"&":"?") . "p=" . OMRoute::dir();
		}
		return $joinStr;
	}
	function omroute($var) {
		return OMRoute::$var();
	}
	function stocks($path) {
		if( is_file(ROOT_DIR . $path) ){
			return WEB_META_BASE_URL . $path;
		}
		return WEB_META_BASE_URL . "stocks/" . $path;
	}
	function addSharedImage($path) {
		$this->sharedImage[] = $path;
	}
	function dict($name,$scope = "") {
		return OMDict::getDict($name,$scope);
	}

}

?>