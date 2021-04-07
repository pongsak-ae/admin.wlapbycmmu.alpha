<?php
class OMStringUtils {
	static function trim($data) {
		return trim($data);
	}

	static function POST($name, $default="",$validate_filter=NULL ) {
		if($validate_filter == NULL){
			$validate_filter =  FILTER_UNSAFE_RAW;
		}
		$ret = filter_input(INPUT_POST, $name ,$validate_filter);
		if(is_array($_POST[$name])){
			$ret = filter_input(INPUT_POST, $name ,FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
		}

		if( $ret === FALSE){
			header('HTTP/1.0 400 Invalid request');
			header('status: 400 Invalid request');
			exit();
		}elseif ($ret === NULL) {
			$ret = $default;
		}

		return $ret;
	}
	static function GET($name, $default="",$validate_filter = NULL ) {
		if($validate_filter == NULL){
			$validate_filter =  FILTER_UNSAFE_RAW;
		}
		$ret = filter_input(INPUT_GET, $name ,$validate_filter);
		if(is_array($_GET[$name])){
			$ret = filter_input(INPUT_GET, $name ,FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
		}

		if( $ret === FALSE){
			header('HTTP/1.0 400 Invalid request');
			header('status: 400 Invalid request');
			exit();
		}elseif ($ret === NULL) {
			$ret = $default;
		}
		return $ret;
	}
	static function REQ($name, $default="",$validate_filter = NULL ) {
		if(isset($_POST[$name])){
			return self::POST($name,$default,$validate_filter);
		}
		return self::GET($name,$default,$validate_filter);
	}
	static function SERVER($name, $default="",$validate_filter = NULL ) {
		if($validate_filter == NULL){
			$validate_filter =  FILTER_UNSAFE_RAW;
		}

		$ret = filter_input(INPUT_SERVER, $name ,$validate_filter);
		if(is_array($_SERVER[$name])){
			$ret = filter_input(INPUT_SERVER, $name ,FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
		}

		if( $ret === FALSE){
			header('HTTP/1.0 400 Invalid request');
			header('status: 400 Invalid request');
			exit();
		}elseif ($ret === NULL) {
			$ret = $default;
		}

		return $ret;
	}
	static function _TRIMPOST($name, $index=null) {
		$ret = self::POST($name,"");
		if ($index != null) {
			if (isset($ret[$index])) {
				$ret = self::trim($ret[$index]);
			}
		}
		return $ret;
	}
	static function _TRIMGET($name, $index=null) {
		$ret = self::GET($name,"");
		if ($index != null) {
			if (isset($ret[$index])) {
				$ret = self::trim($ret[$index]);
			}
		}
		return $ret;
	}
	static function randomStringGenerator($length=16) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		$string ='';

		for ($p = 0; $p < $length; $p++) {
			$string .= $characters[mt_rand(0, strlen($characters))];
		}
		return $string;
	}
	static function trimWithDot($data, $len = 100, $suffix = "...") {
        if (mb_strlen($data,'UTF-8') > $len) {
            return (mb_substr($data, 0, ($len - 2),'UTF-8') . $suffix);
        } else {
            return $data;
        }
    }
	static function getTextWidth($text, $fontsize = 9, $fontname = '../images/loma.ttf') {
		if (isset($_SERVER['OS']) && $_SERVER['OS'] == "Windows_NT") {
			$bbox = @imagettfbbox($fontsize, 0, "tahoma.ttf", $text);
		} else {
			if ($fontname == '../images/loma.ttf' && file_exists(dirname(__FILE__) . '/' . '../images/custom.ttf')) {
				$fontname = '../images/custom.ttf';
			}
			$bbox = @imagettfbbox($fontsize, 0, dirname(__FILE__) . '/' . $fontname, $text);
		}
		return abs($bbox[2] - $bbox[0]);
	}
	static function trimPixel($text, $max_width=500) {
		$r_len = mb_strlen($text,'UTF-8');
		$w = OMStringUtils::getTextWidth($text);
		if ($w <= $max_width) return $text;
		$r_len = floor(($max_width / $w) * $r_len * 0.80);
		while (OMStringUtils::getTextWidth(mb_substr($text,0,$r_len, 'UTF-8')) < $max_width) {
			$r_len++;
		}
		return (mb_substr($text, 0, $r_len-1, 'UTF-8'));
	}
	static function trimWithDotPixel($text, $max_width=500, $suffix = "...") {
		$sw = OMStringUtils::getTextWidth($suffix);
		$new_max_with = $max_width - $sw;
		if ($new_max_with < $sw) $new_max_with = $sw;
		$t = OMStringUtils::trimPixel($text, $new_max_with);
		if ($t != $text) $t .= $suffix;
		return  $t;
	}
    static function simple_strip_html_tag($s) {
		return preg_replace("/<(.|\n)*?>/", '', $s);
    }
    static function formatNumber($number, $p_len=2) {
		//TODO: Formating Number
		/*
        String format = "{0:#,0." + "".PadRight(p_len,'0') + "}";
        return String.Format(format, number);
		*/
		return  number_format($number, $p_len, '.', '') ;
    }
   static function mySqlLikeEscape($sql) {
        $tmp = $sql;
		//TODO: Based on MSSQL not sure what need to do in MySQL
		/*
		tmp = tmp.Replace("[", "[[]");
        tmp = tmp.Replace("_", "[_]");
        tmp = tmp.Replace("%", "[%]");
		*/

        return $tmp;
    }
    static function getStockFriendlyName($media_filename, $original_filename) {
	$output_filename = $media_filename;
        $original_filename_out  = str_replace(' ', '-', $original_filename);
	$media_filename_without_dot = str_replace('.', '',$media_filename);
	$hash_name = substr(md5($media_filename . $original_filename_out),0,2);
	$output_filename = $media_filename_without_dot . '/' . $hash_name . '/' . $original_filename_out;
	return $output_filename;
    }
}
?>