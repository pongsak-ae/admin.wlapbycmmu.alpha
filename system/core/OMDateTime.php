<?php
namespace OMCore;

use DateTime;

class OMDateTime {
	private $_UTime = 0;
	private $_PhpDateTime = null;
	private $_isPhpDateTimeClass = false;
	function phpStrFTimeFormat ($format) {
		$wcm_format_list = array("yyyy", "MM", "dd", "HH", "mm", "ss");
		$php_format_list = array("%Y", "%m", "%d", "%H", "%M", "%S");
		$out = str_replace($wcm_format_list, $php_format_list, $format);
		return $out;
	}
	function phpDateTimeFormat ($format) {
		$wcm_format_list = array("yyyy", "MM", "dd", "HH", "mm", "ss");
		$php_format_list = array("Y", "m", "d", "H", "i", "s");
		$out = str_replace($wcm_format_list, $php_format_list, $format);
		return $out;
	}
	function __construct($stime, $format = null) {
		$this->_isPhpDateTimeClass = class_exists('DateTime');
		if ($format != null) {
			$phpfm = $this->phpStrFTimeFormat($format);


			if ($this->_isPhpDateTimeClass) {
				$a = custom_strptime($stime, $phpfm);
				if ($a != FALSE) {
					$this->_PhpDateTime = new DateTime();
					$this->_PhpDateTime->setDate($a["tm_year"]+1900, $a["tm_mon"]+1, $a["tm_mday"]);
					$this->_PhpDateTime->setTime($a["tm_hour"], $a["tm_min"], $a["tm_sec"]);
					return;
				}
			} else {
				if (version_compare(PHP_VERSION, '5.2.0', '<')) {
					$a = custom_strptime($stime, $phpfm);

				} else {
					$a = strptime($stime, $phpfm);
				}
				if ($a != FALSE) {
					$d = mktime($a["tm_hour"], $a["tm_min"], $a["tm_sec"], $a["tm_mon"]+1, $a["tm_mday"], $a["tm_year"]+1900);
					$this->_UTime = $d;
					return;

				}
			}
		}

		if ($this->_isPhpDateTimeClass) {
			try {
				$this->_PhpDateTime = new DateTime($stime);
			} catch (Exception $e) {
				$this->_PhpDateTime = new DateTime();
			}
		} else {
			$this->_UTime = strtotime($stime);
		}

	}
	function __get($Name) {
        switch ($Name) {
            case 'UTime':
				if ($this->_isPhpDateTimeClass) {
					if ($this->_PhpDateTime != null) {
						return $this->_PhpDateTime->format("U");
					} else {
						return 0;
					}
				} else {
					return $this->_UTime;
				}
				break;
			case 'DateTime':
				return $this->_PhpDateTime;
				break;
        }
        user_error("Invalid property: " . __CLASS__ . "->" . $Name);
    }
	function toString() {
		//return Date("c", $this->_UTime); /* BUG mysql 5.1 win not accept ISO 8601 */
		if ($this->_isPhpDateTimeClass) {
			if ($this->_PhpDateTime != null) {
				return $this->_PhpDateTime->format("Y-m-d\TH:i:s");
			} else {
				return "";
			}
		} else {
			return Date("Y-m-d\TH:i:s", $this->_UTime);
		}
	}
	function __toString() {
		return $this->toString();
	}
	function toDbString() {
		return $this->toString();
	}
	function AddSeconds($secs) {
		if ($this->_isPhpDateTimeClass) {
			$this->_PhpDateTime->modify($secs . " seconds");
		} else {
			$this->_UTime += $secs;
		}
		return $this;
	}
	function AddDays($days) {
		if ($this->_isPhpDateTimeClass) {
			$this->_PhpDateTime->modify($days . " days");
		} else {
			$this->_UTime += $days * 60 * 60 * 24;
		}
		return $this;
	}

	static function Now() {
		return new OMDateTime("now");
	}
}


function custom_strptime($sDate, $sFormat) {
	$aResult = array (
		'tm_sec'   => 0,
		'tm_min'   => 0,
		'tm_hour'  => 0,
		'tm_mday'  => 1,
		'tm_mon'   => 0,
		'tm_year'  => 0,
		'tm_wday'  => 0,
		'tm_yday'  => 0,
		'unparsed' => $sDate
	);

	while($sFormat != "") {
		// ===== Search a %x element, Check the static string before the %x =====
		$nIdxFound = strpos($sFormat, '%');
		if($nIdxFound === false)

		{

			// There is no more format. Check the last static string.
			$aResult['unparsed'] = ($sFormat == $sDate) ? "" : $sDate;

			break;
		}

		$sFormatBefore = substr($sFormat, 0, $nIdxFound);
		$sDateBefore   = substr($sDate,   0, $nIdxFound);

		if($sFormatBefore != $sDateBefore) break;

		// ===== Read the value of the %x found =====
		$sFormat = substr($sFormat, $nIdxFound);
		$sDate   = substr($sDate,   $nIdxFound);

		$aResult['unparsed'] = $sDate;

		$sFormatCurrent = substr($sFormat, 0, 2);
		$sFormatAfter   = substr($sFormat, 2);

		$nValue = -1;
		$sDateAfter = "";

		switch($sFormatCurrent) {
			case '%S': // Seconds after the minute (0-59)

				sscanf($sDate, "%2d%[^\\n]", $nValue, $sDateAfter);

				if(($nValue < 0) || ($nValue > 59)) return false;

				$aResult['tm_sec']  = $nValue;
				break;

			// ----------
			case '%M': // Minutes after the hour (0-59)
				sscanf($sDate, "%2d%[^\\n]", $nValue, $sDateAfter);

				if(($nValue < 0) || ($nValue > 59)) return false;

				$aResult['tm_min']  = $nValue;
				break;

			// ----------
			case '%H': // Hour since midnight (0-23)
				sscanf($sDate, "%2d%[^\\n]", $nValue, $sDateAfter);

				if(($nValue < 0) || ($nValue > 23)) return false;

				$aResult['tm_hour']  = $nValue;
				break;

			// ----------
			case '%d': // Day of the month (1-31)
				sscanf($sDate, "%2d%[^\\n]", $nValue, $sDateAfter);

				if(($nValue < 1) || ($nValue > 31)) return false;

				$aResult['tm_mday']  = $nValue;
				break;

			// ----------
			case '%m': // Months since January (0-11)
				sscanf($sDate, "%2d%[^\\n]", $nValue, $sDateAfter);

				if(($nValue < 1) || ($nValue > 12)) return false;

				$aResult['tm_mon']  = ($nValue - 1);
				break;

			// ----------
			case '%Y': // Years since 1900
				sscanf($sDate, "%4d%[^\\n]", $nValue, $sDateAfter);

				if($nValue < 1900) return false;

				$aResult['tm_year']  = ($nValue - 1900);
				break;

			// ----------
			default:
				break 2; // Break Switch and while

		} // END of case format

		// ===== Next please =====
		$sFormat = $sFormatAfter;
		$sDate   = $sDateAfter;

		$aResult['unparsed'] = $sDate;

	} // END of while($sFormat != "")

	// ===== Create the other value of the result array =====
	$nParsedDateTimestamp = mktime($aResult['tm_hour'], $aResult['tm_min'], $aResult['tm_sec'],
							$aResult['tm_mon'] + 1, $aResult['tm_mday'], $aResult['tm_year'] + 1900);


	// Before PHP 5.1 return -1 when error
	if(($nParsedDateTimestamp === false)
	||($nParsedDateTimestamp === -1)) return false;


	$aResult['tm_wday'] = (int) strftime("%w", $nParsedDateTimestamp); // Days since Sunday (0-6)
	$aResult['tm_yday'] = (strftime("%j", $nParsedDateTimestamp) - 1); // Days since January 1 (0-365)

	return $aResult;


} // END of function

if(function_exists("strptime") == false) {
    function strptime($sDate, $sFormat) {
		return custom_strptime($sDate, $sFormat);
	}
} // END of if(function_exists("strptime") == false)

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
		$month_th[1]='มกราคม';
		$month_th[2]='กุมภาพันธ์';
		$month_th[3]='มีนาคม';
		$month_th[4]='เมษายน';
		$month_th[5]='พฤษภาคม';
		$month_th[6]='มิถุนายน';
		$month_th[7]='กรกฏาคม';
		$month_th[8]='สิงหาคม';
		$month_th[9]='กันยายน';
		$month_th[10]='ตุลาคม';
		$month_th[11]='พฤศจิกายน';
		$month_th[12]='ธันวาคม';
		return $month_th[$month];
	}

	function generate_abbr_month_th($month){
		$month_th[1]='ม.ค.';
		$month_th[2]='ก.พ.';
		$month_th[3]='มี.ค.';
		$month_th[4]='เม.ย.';
		$month_th[5]='พ.ค.';
		$month_th[6]='มิ.ย.';
		$month_th[7]='ก.ค.';
		$month_th[8]='ส.ค.';
		$month_th[9]='ก.ย.';
		$month_th[10]='ต.ค.';
		$month_th[11]='พ.ย.';
		$month_th[12]='ธ.ค.';
		return $month_th[$month];
	}

	function generate_abbr_weekday_th($weekday){
		$weekday_th[0]='อา';
		$weekday_th[1]='จ';
		$weekday_th[2]='อัง';
		$weekday_th[3]='พ';
		$weekday_th[4]='พฤ';
		$weekday_th[5]='ศ';
		$weekday_th[6]='ส';
		return $weekday_th[$weekday];
	}
	function generate_full_weekday_th($weekday){
		$weekday_th[0]='อาทิตย์';
		$weekday_th[1]='จันทร์';
		$weekday_th[2]='อังคาร';
		$weekday_th[3]='พุธ';
		$weekday_th[4]='พฤหัสบดี';
		$weekday_th[5]='ศุกร์';
		$weekday_th[6]='เสาร์';
		return $weekday_th[$weekday];
	}


	function gen_date($date_db,$pattern="%d/%m/%Y",$lang="en"){
		if (strtotime($date_db) == -1){
			return "";
		}

		if($lang=="en"){
			return	strftime($pattern, strtotime($date_db));
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
					$value_return = generate_abbr_weekday_th($weekday);
				}else if($value == '%A'){
					$value_return = generate_full_weekday_th($weekday);
				}else if($value == '%b'){
					$value_return = generate_abbr_month_th($month);
				}else if($value == '%B'){
					$value_return  =generate_month_th($month);
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


?>