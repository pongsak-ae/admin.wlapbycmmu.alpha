<?php
namespace OMCore;

class OMImage {
 	static $RESIZE_METHOD_NORMAL = "normal";
	static $RESIZE_METHOD_INDEX = "index";

	public static function ResizeImage($source_filename, $target_filename, $new_width, $new_height, $target_format, $resize_mode, $options = null) {
		$resizeMethod = self::$RESIZE_METHOD_NORMAL;
		$bg_image = "";
		$new_image = null;
		$bg_color = "";

		if ($options != null) {
				/*
		    if ($options["bgcolor"] != null ) {
			bg_color = (Color)options["bgcolor"];
		    }
				*/
		    if (isset($options["bgimage"])) {
			$bg_image = $options["bgimage"];
		    }
		}

		if (self::isImageFile($source_filename) == false && self::isURLFile($source_filename) == false) {
		    return true;
		}
		if (!file_exists($source_filename) && self::isURLFile($source_filename) == false) {
			return false;
		}

		$image_info = getimagesize($source_filename);
		$new_ratio = 0;
		$input_ratio = 0;
		$output_ratio = 0;
		$org_width = $image_info[0];
		$org_height = $image_info[1];
		$output_width = $new_width;
		$output_height = $new_height;
		$src_format = substr(strtolower($source_filename), strrpos($source_filename,'.') + 1);
		$dst_format = substr(strtolower($target_filename), strrpos($target_filename,'.') + 1);
		$image_info = getimagesize($source_filename);

		$input_rectangle = array();
		$output_rectangle = array();
		$input_crop_rectangle = array();
		$output_crop_rectangle = array();

		$image_type = $image_info[2];

		if($image_type == IMAGETYPE_JPEG ) {
			$inputImage = imagecreatefromjpeg($source_filename);
		} else if( $image_type == IMAGETYPE_GIF ) {
			$inputImage = imagecreatefromgif($source_filename);
		} else if( $image_type == IMAGETYPE_PNG ) {
			$inputImage = imagecreatefrompng($source_filename);
		}

		if ($resize_mode == "original" || ($org_width == $new_width & $org_height == $new_height & $src_format == $dst_format)) {
			if ($source_filename != $target_filename) {
				copy($source_filename, $target_filename);
			}
			return true;
		} else {

			$input_ratio = $org_width / $org_height; // Original Ratio
			$output_rectangle = array(0, 0, $output_width, $output_height);
			$output_crop_rectangle = $output_rectangle;
			$input_rectangle = array(0, 0, $org_width, $org_height);
			$input_crop_rectangle = $input_rectangle;
			$new_ratio = $new_width / $new_height; // Target Ratio
			$output_ratio = $new_ratio;

			if ($resize_mode == "scale") {
				$output_ratio = $input_ratio; // Output ratio must be same as original ratio
				if ($new_ratio < $input_ratio) {
					$output_width = $new_width;
					$output_height = round($output_width / $output_ratio);
				} else {
					$output_height = $new_height;
					$output_width = round($output_height * $output_ratio);
				}
				$output_rectangle = array(0, 0, $output_width, $output_height);
				$output_crop_rectangle = $output_rectangle;
			}

			if ($resize_mode == "scaledown") {
				$output_ratio = $input_ratio; // Output ratio will be same as original ratio
				if ($new_ratio < $input_ratio) {
					$output_width = ($org_width > $new_width) ? $new_width : $org_width;
					$output_height = round($output_width / $output_ratio);
				} else {
					$output_height = ($org_height > $new_height) ? $new_height : $org_height;
					$output_width = round($output_height * $output_ratio);
				}
				$output_rectangle = array(0, 0, $output_width, $output_height);
				$output_crop_rectangle = $output_rectangle;
			}
			if ($resize_mode == "fixwidth") {
				$output_ratio = $input_ratio;
				$output_width = $new_width;
				$output_height = round($output_width / $output_ratio);
				$output_rectangle = array(0, 0, $output_width, $output_height);
				$output_crop_rectangle = $output_rectangle;
			}
			if ($resize_mode == "fixheight") {
				$output_ratio = $input_ratio;
				$output_height = $new_height;
				$output_width = round($output_height * $output_ratio);
				$output_rectangle = array(0, 0, $output_width, $output_height);
				$output_crop_rectangle = $output_rectangle;
			}
			if ($resize_mode == "crop") {
				$offset_x = 0;
				$offset_y = 0;
				$crop_width = 0;
				$crop_height = 0;
				if ($output_ratio < $input_ratio) {
					$crop_width = round($org_height * $output_ratio);
					$crop_height = $org_height;
					$offset_x = round(($org_width - $crop_width) / 2);
					$offset_y = 0;
				} else {
					$crop_width = $org_width;
					$crop_height = round($org_width / $output_ratio);
					$offset_x = 0;
					$offset_y = round(($org_height - $crop_height) / 2);
				}
				$input_crop_rectangle = array($offset_x, $offset_y, $crop_width, $crop_height);
			}
			if ($resize_mode == "letterbox") {
				$offset_x = 0;
				$offset_y = 0;
				$crop_width = 0;
				$crop_height = 0;
				if ($new_ratio < $input_ratio) {
					$crop_width = $new_width;
					$crop_height = round($new_width / $input_ratio);
					$offset_x = 0;
					$offset_y = round(($new_height - $crop_height) / 2);
				} else {
					$crop_width = round($new_height * $input_ratio);
					$crop_height = $new_height;
					$offset_x = round(($new_width - $crop_width) / 2);
					$offset_y = 0;
				}
				$output_crop_rectangle = array($offset_x, $offset_y, $crop_width, $crop_height);
			}
				/*
				PixelFormat inputPixelFormat = inputBitmap.PixelFormat;
				PixelFormat operatePixelFormat = inputPixelFormat;
				PixelFormat targetPixelFormat = inputPixelFormat;
				Bitmap newbmp = null;
				switch (inputPixelFormat) {
					case PixelFormat.Format8bppIndexed:
						operatePixelFormat = PixelFormat.Format8bppIndexed;
						targetPixelFormat = PixelFormat.Format8bppIndexed;
						resizeMethod = ResizeMethod.IndexedResize;
						break;
					case PixelFormat.Format32bppRgb:
						operatePixelFormat = inputPixelFormat;
						targetPixelFormat = inputPixelFormat;
						break;
					case PixelFormat.Format32bppArgb:
						operatePixelFormat = inputPixelFormat;
						targetPixelFormat = inputPixelFormat;
						break;
					default:
						operatePixelFormat = PixelFormat.Format24bppRgb;
						targetPixelFormat = PixelFormat.Format24bppRgb;
						break;
				}
				*/

			if ($resizeMethod == self::$RESIZE_METHOD_NORMAL) {
				$new_image = imagecreatetruecolor($output_width, $output_height);

				if ( ($image_type == IMAGETYPE_GIF) || ($image_type == IMAGETYPE_PNG) ) {
					$trnprt_indx = imagecolortransparent($inputImage);

					if ($trnprt_indx >= 0 && $trnprt_indx < imagecolorstotal($inputImage)) {
						$trnprt_color    = imagecolorsforindex($inputImage, $trnprt_indx);
						$trnprt_indx    = imagecolorallocate($new_image, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
						imagefill($new_image, 0, 0, $trnprt_indx);
						imagecolortransparent($new_image, $trnprt_indx);
					} elseif ($image_type == IMAGETYPE_PNG) {
						imagealphablending($new_image, false);
						$color = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
						imagefill($new_image, 0, 0, $color);
						imagesavealpha($new_image, true);
					}
				} else {
					imagefill($new_image, 0, 0, imagecolorallocate($new_image, 255, 255, 255));
				}

				if ($resize_mode == "original") {
					imagecopyresampled($new_image, $inputImage, $output_rectangle[0], $output_rectangle[1], $input_crop_rectangle[0], $input_crop_rectangle[1], $output_rectangle[2], $output_rectangle[3], $input_crop_rectangle[2], $input_crop_rectangle[3]);
				} else if ($resize_mode == "stretch" || $resize_mode == "scale" || $resize_mode == "scaledown" || $resize_mode == "fixwidth" || $resize_mode == "fixheight") {
					imagecopyresampled($new_image, $inputImage, $output_rectangle[0], $output_rectangle[1], $input_crop_rectangle[0], $input_crop_rectangle[1], $output_rectangle[2], $output_rectangle[3], $input_crop_rectangle[2], $input_crop_rectangle[3]);
				} else if ($resize_mode == "crop") {
					imagecopyresampled($new_image, $inputImage, $output_rectangle[0], $output_rectangle[1], $input_crop_rectangle[0], $input_crop_rectangle[1], $output_rectangle[2], $output_rectangle[3], $input_crop_rectangle[2], $input_crop_rectangle[3]);
				} else if ($resize_mode == "letterbox") {
					//SolidBrush brush = new SolidBrush(bg_color);
					//newg.FillRectangle(brush, output_rectangle);
					if ($bg_image != null && bg_image != "" && file_exists($bg_image)) {
						/*try {
							System.Drawing.Image bgBitmap = Bitmap.FromFile(bg_image, true);
							if (bgBitmap != null) {
								newg.DrawImage(bgBitmap, new RectangleF(new PointF(0, 0), newbmp.Size), new RectangleF(new PointF(0, 0), bgBitmap.Size), GraphicsUnit.Pixel);
							} else {
								throw new Exception("Unable to open bgimage");
							}
						} catch (Exception ex) {
							throw ex;
						}*/
					} else {
						/*if (bg_image != null && bg_image != "") {
							throw new Exception("Unable to open bgimage = " + bg_image);
						}
						*/
					}
					imagecopyresampled($new_image, $inputImage, $output_crop_rectangle[0], $output_crop_rectangle[1], $input_crop_rectangle[0], $input_crop_rectangle[1], $output_crop_rectangle[2], $output_crop_rectangle[3], $input_crop_rectangle[2], $input_crop_rectangle[3]);
				}

			} /* else if (resizeMethod == ResizeMethod.IndexedResize) {
				if (src_format == "gif" && dst_format == "gif") {
					if (resize_mode == "letterbox" || resize_mode == "crop" || resize_mode == "stretch" || resize_mode == "stretch" || resize_mode == "scale" || resize_mode == "scaledown" || resize_mode == "fixwidth" || resize_mode == "fixheight") {
						IndexedResize(out newbmp, (Bitmap)inputBitmap, output_rectangle, output_crop_rectangle,input_rectangle, input_crop_rectangle);
					} else {
						return false;
					}
				}
			} else {
				return false;
			}
			*/
			if ($new_image != null) {
				switch (strtoupper($target_format)) {
					case "GIF":
						imagegif($new_image, $target_filename);
						break;
					case "PNG":
						imagepng($new_image, $target_filename);
						break;
					default:
						imagejpeg($new_image, $target_filename, 100);
						break;
				}

				if (file_exists($source_filename)) {
				}
				return true;
			} else {
				return false;
			}

		}


		return true;
	}

	public static function isImageFile($filename) {
		$img_ext = array("gif"=>"gif", "jpeg"=>"jpg", "jpg"=>"jpg", "png"=>"png", "bmp"=>"bmp");
		$fileext;
		if (strrpos($filename,'.') >= 0) {
			$fileext = substr(strtolower($filename), strrpos($filename,'.') + 1);
			return (array_key_exists($fileext, $img_ext));
		} else {
		return false;
		}
	}
	public static function isURLFile($filename) {
		preg_match('~^(?:f|ht)tps?://~i',$filename, $matches);
		if(isset($matches[0]) && $matches[0] != ""){
			return true;
		}else{
			return false;
		}
	}

	public static function uuname(){
		$microTime = round(microtime(true)*1000);
		$randomString = rand(100,999);
		$base = $microTime.$randomString;
		$baseUname = base_convert( $base ,10, 36 );
		return $baseUname;
	}

	public static function readFileName($file_name,$file_original_name,$path = "",$module){
		if( isset($file_name) && $file_name != ""){
			$chkFile = explode(".", $file_name);
			if(count($chkFile) > 1){
				return "stocks/media/".$file_name;
			}else{
				$hasFile = str_split($file_name, 4);
				$has_folder = str_split($hasFile[1], 2);
				$fo_name = str_replace(" ", "_", $file_original_name);
				if($path == ""){
					$path = "/";
				}else{
					$path = "/".$path."/";
				}
				return "stocks/".$module.$path.$has_folder[0]."/".$has_folder[1]."/".$file_name."/".$fo_name;
			}
		}else{
			return false;
		}
	}

	public static function insertFile($file,$table_name,$upload_type = "WEB",$ref_id = 0){
		GLOBAL $LOG;
		if(isset($file)){
			$param["uuname"] = self::uuname();
			$param["data"] = file_get_contents($file['tmp_name']);
			$param["upload_date"] = date("Y-m-d H:i:s");
			$param["original_name"] = $file['name'];
			$param["upload_type"] = $upload_type;
			$param["content_type"] = $file['type'];
			$param["ref_id"] = $ref_id;
			$rw = OMDb::table($table_name)->insert($param);
			return $rw;
		}else{
			$LOG->addError('Cannot recieve file!');
			exit();
		}
	}


}
?>