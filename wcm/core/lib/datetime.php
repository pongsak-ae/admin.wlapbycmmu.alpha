<?php
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
				if (trim($stime) != '') {
					$this->_PhpDateTime = new DateTime($stime);				
				} else {
					$this->_PhpDateTime = new DateTime('1000-01-01 00:00:00');				
				}
			} catch (Exception $e) {
				$this->_PhpDateTime = new DateTime('1000-01-01 00:00:00');
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
		'unparsed' => $sDate,
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

?>