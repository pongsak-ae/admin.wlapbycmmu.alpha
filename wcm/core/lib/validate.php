<?php
class OMValidate {
	//TODO: Implementing
	static function validate() {
		return true;		
	}
	
	static function validateString($value, $minlen, $maxlen, $allow_charset) {
		if (!isset($value) || $value == null) return false;
		if ($maxlen <= 0) $maxlen = strlen($value);
		if (!(strlen($value) >= $minlen && strlen($value) <= $maxlen)) {
			return false;
		}
		if (!isset($allow_charset) || $allow_charset == null) $allow_charset = "";
		switch ($allow_charset) {
            case "numeric":
                $re = "/^\d+$/u";
                if (preg_match($re, $value)==FALSE) return false;
				break;
            case "alphanumeric":
                $re = "/^[a-zA-Z0-9]+$/u";
                if (preg_match($re, $value)==FALSE) return false;
				break;
            case "alphanumeric_th":
                $re = "/^[a-zA-Z0-9\x{0e00}-\x{0e7f}]+$/u";
                if (preg_match($re, $value)==FALSE) return false;
				break;	
			case "alpha":
			case "alphabetic":
				$re = "/^[a-zA-Z]+$/u";
				if (preg_match($re, $value)==FALSE) return false;
				break;
            case "alpha_th":
            case "alphabetic_th":
                $re = "/^[a-zA-Z\x{0e00}-\x{0e7f}]+$/u";
                if (preg_match($re, $value)==FALSE) return false;
				break;				
		}
		return true;
	}
	
	static function tryParseString($value, $defaultvalue="") {
        if ($value == null) {
            return $defaultvalue;
        }
        return $value;
    }
	
    static function tryParseInt($value, $defaultvalue) {
        return self::tryParseInt32($value, $defaultvalue);
    }
    static function tryParseInt32($value, $defaultvalue) {
        $ret=$value;
		/*
        if (value == null) {
            return defaultvalue;
        }
        if (!OMValidate.validateInteger(value)) {
            return defaultvalue;
        }        
        try {
            ret = Int32.Parse(value);
        } catch {
            ret = defaultvalue;
        }
		*/
        return $ret;
    }
    static function tryParseLong($value, $defaultvalue) {
        return self::tryParseInt64($value, $defaultvalue);
    }
    static function tryParseInt64($value, $defaultvalue) {
        $ret = $value;
		/*
        if (value == null) {
            return defaultvalue;
        }
        if (!OMValidate.validateIntegerIgnoreComma(value)) {
            return defaultvalue;
        }        
        try {
            ret = Int64.Parse(value);
        } catch {
            ret = defaultvalue;
        }
		*/
        return $ret;
    }
    static function tryParseDouble($value,$defaultvalue) {
        $ret = $value;
		/*
        if (value == null) {
            return defaultvalue;
        }
        if (!OMValidate.validateNumericIgnoreComma(value)) {
            return defaultvalue;
        }
        try {
            ret = Double.Parse(value);
        } catch {
            ret = defaultvalue;
        }
		*/
        return $ret;
    }
    static function tryParseDecimal($value, $defaultvalue) {
        $ret=$value;
		/*
        if (value == null) {
            return defaultvalue;
        }
        if (!OMValidate.validateNumericIgnoreComma(value)) {
            return defaultvalue;
        }
        try {
            ret = Decimal.Parse(value);
        } catch {
            ret = defaultvalue;
        }
		*/
        return $ret;
    }
}
?>