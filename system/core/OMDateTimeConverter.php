<?php
namespace OMCore;

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
				return date($phpformat, $datetime->UTime);
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


	function GenDateTime($datetime_var /*timestamp*/ ,$datetime_format = WCM_SETTING_DATETIME_FORMAT_IN_DATABASE /*String*/, $culture_info_format = WCM_SETTING_CULTUREINFO_FORMAT /*String*/){
		if ($datetime_var == ""){
			return null;
		} else {
			$locale = setlocale(LC_ALL, WCM_SETTING_CULTUREINFO_FORMAT);
			return strftime($datetime_format,$datetime_var);
		}
	}

	function GenDateTimeObj($datetime_var /*String EN only*/, $culture_info_format = WCM_SETTING_CULTUREINFO_FORMAT /*String*/){ /* return timestamp*/
		if ($datetime_var == ""){
			return null;
		} else {
			return strtotime($datetime_var);
		}
	}
	function generate_month_th($month){
		$month_th[1]='??????????????????';
		$month_th[2]='??????????????????????????????';
		$month_th[3]='??????????????????';
		$month_th[4]='??????????????????';
		$month_th[5]='?????????????????????';
		$month_th[6]='????????????????????????';
		$month_th[7]='?????????????????????';
		$month_th[8]='?????????????????????';
		$month_th[9]='?????????????????????';
		$month_th[10]='??????????????????';
		$month_th[11]='???????????????????????????';
		$month_th[12]='?????????????????????';
		return $month_th[$month];
	}

	function generate_abbr_month_th($month){
		$month_th[1]='???.???.';
		$month_th[2]='???.???.';
		$month_th[3]='??????.???.';
		$month_th[4]='??????.???.';
		$month_th[5]='???.???.';
		$month_th[6]='??????.???.';
		$month_th[7]='???.???.';
		$month_th[8]='???.???.';
		$month_th[9]='???.???.';
		$month_th[10]='???.???.';
		$month_th[11]='???.???.';
		$month_th[12]='???.???.';
		return $month_th[$month];
	}

	function generate_abbr_weekday_th($weekday){
		$weekday_th[0]='??????';
		$weekday_th[1]='???';
		$weekday_th[2]='?????????';
		$weekday_th[3]='???';
		$weekday_th[4]='??????';
		$weekday_th[5]='???';
		$weekday_th[6]='???';
		return $weekday_th[$weekday];
	}
	function generate_full_weekday_th($weekday){
		$weekday_th[0]='?????????????????????';
		$weekday_th[1]='??????????????????';
		$weekday_th[2]='??????????????????';
		$weekday_th[3]='?????????';
		$weekday_th[4]='????????????????????????';
		$weekday_th[5]='???????????????';
		$weekday_th[6]='???????????????';
		return $weekday_th[$weekday];
	}

	function gen_date($date_db,$pattern="%d/%m/%Y",$lang="en"){
		if (strtotime($date_db) == -1){
			return "";
		}

		if($lang=="en"){
			// return	strftime($pattern, strtotime($date_db));
			return	date($pattern, strtotime($date_db));
		}else{
			$day = strftime("%d", strtotime($date_db));
			$month = intval(strftime("%m", strtotime($date_db)));
			$year = intval(strftime("%Y", strtotime($date_db)))+543;
			$weekday = intval(strftime("%w", strtotime($date_db)));
			$pattern_tmp = $pattern;
			$i=0;

			while(strpos($pattern_tmp,"%") !== false){
				$pattern_array[$i] = substr($pattern_tmp,strpos($pattern_tmp,"%"),2);
				$pattern_tmp = str_replace($pattern_array[$i],"", $pattern_tmp);
				$i++;
			}
			//var_dump($pattern_array);
			foreach($pattern_array as $value){
				if($value == '%a'){
					$value_return = $this->generate_abbr_weekday_th($weekday);
				}else if($value == '%A'){
					$value_return = $this->generate_full_weekday_th($weekday);
				}else if($value == '%b'){
					$value_return = $this->generate_abbr_month_th($month);
				}else if($value == '%B'){
					$value_return = $this->generate_month_th($month);
				}else if($value == '%y'){
					$value_return = substr($year,-2);
				}else if($value == '%Y'){
					$value_return = $year;
				}else{
					$value_return = strftime($value, strtotime($date_db));
				}
				$pattern = str_replace($value, $value_return, $pattern);
			}
			return $pattern;
		}
	}
}
?>