<?php
/*!
  * NameNuT (nuattawoot@cheesemobile.com)
  */
class OMLANG {
	private	$lang;
	function __construct($lang = null) {
		$this->lang = $lang;
	}
	public function FNC_GET_LANG() {
		if($this->lang == "tha"){
			$lang = ROOT_DIR . 'language/th.php';
		}else{
			$lang = ROOT_DIR . 'language/en.php';
		}
		// require $lang;
		return $lang;
	}
}

?>

