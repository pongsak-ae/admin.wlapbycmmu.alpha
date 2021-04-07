<?php
class OMDateTimeConverter {
	//TODO: Non en_US culture
	private $_DefaultDateTimeFormat = "yyyy-MM-dd";
	private $_DefaultCultureInfoFormat = "en_US";

	function __construct($datetime_format = "yyyy-MM-dd", $culture_info_format = "en_US") {
		$this->_DefaultDateTimeFormat = $datetime_format;
		$this->_DefaultCultureInfoFormat = $culture_info_format;
	}
	function toString($datetime, $format=null, $culture=null) {
		//TODO:
		if ($format == null) $format = $this->_DefaultDateTimeFormat;
		if ($culture == null) $culture = $this->_DefaultCultureInfoFormat;
		$phpformat = self::phpDateTimeFormat($format);

		if (get_class($datetime) == "OMDateTime") {
			if ($datetime->DateTime != null) {
				return $datetime->DateTime->format($phpformat);
			} else {
				return  date($phpformat, $datetime->UTime);
			}
		} else {
			$dt = new OMDateTime($datetime);
			return date($phpformat, $dt->UTime);
		}


	}
	function toDateTime($datetime_str, $datetime_format=null, $culture_info_format="en_US") {
		if ($datetime_format == null) {
			return new OMDateTime($datetime_str, $this->_DefaultDateTimeFormat);
		} else {
			//return "BUGBUG";
			return new OMDateTime($datetime_str, $datetime_format);
		}
	}

	static function phpDateTimeFormat($format) {
		$wcm_format_list = array("yyyy", "MM", "dd", "HH", "mm", "ss");
		$php_format_list = array("Y", "m", "d", "H", "i", "s");
		$out = str_replace($wcm_format_list, $php_format_list, $format);
		return $out;
	}
}
?>