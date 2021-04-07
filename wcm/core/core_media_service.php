<?php
	require_once('../core/lib/all.php');

    function buildTree(&$tree, $virtual_folder, $folder_id) {
        $i = 0;
		$j = 0;
		$aPath = explode('/', $virtual_folder);
		$parentfolder=&$tree;
		$newfolder=&$parentfolder;
		for ($i = 1; $i< count($aPath)-1; $i++) {
			for($j=0; $j < count($parentfolder); $j++) {
				if (!isset($parentfolder[$j])) {
					$parentfolder = array();
				}
				if ($parentfolder[$j]["name"] == $aPath[$i]) {
					if (!isset($parentfolder[$j]["sub"]) || $parentfolder[$j]["sub"]==null) {
						$parentfolder[$j]["sub"] = array();
					}
					$newfolder = &$parentfolder[$j]["sub"];
				}
			}
			$parentfolder = &$newfolder;
		}

		$parentfolder[count($parentfolder)] = array("name"=>$aPath[count($aPath)-1], "id"=>$folder_id);
    }

    function getVirtualFolder($DB, $media_file_id) {
        $r=0;
        $dt=null;
        $virtual_folder = "/";

        $sql = "select * from media_file where media_file_id = @media_file_id";
        $r = $DB->query($dt, $sql, array("@media_file_id"=>$media_file_id));
        if ($r>0) {
            $virtual_folder = $dt[0]["virtual_folder"];
        }
        return $virtual_folder;
    }

	$cmd = OMStringUtils::REQ("cmd");

	if(!$session->checkSession("only")) {
		if($cmd == "form"){
			echo "";
		} else if($cmd == "upload"){
			echo "<script language=\"javascript\">";
			echo "parent.OMB.onUploadError('Session timeout. Please refresh your page again.'); ";
			echo "</script>";
		} else {
			echo OMJson::encode(array("status"=>"ERROR","message"=>"Session timeout. Please refresh your page again."));
		}
		exit();
		return;
	}

	if($cmd == "delete" || $cmd == "deletefolder" || $cmd == "renamefolder" || $cmd == "createfolder"){
		if (!$SESSION->checkPermission("MEDIA_LIBRARY_MANAGE","only")){
			echo OMJson::encode(array("status"=>"ERROR","message"=>"You have not permision to do this function."));
			exit();
			return;
		}
	}

	if($cmd == "upload"){
		if (!$SESSION->checkPermission("MEDIA_LIBRARY_MANAGE","only")){
			echo "<script language=\"javascript\">";
			echo "parent.OMB.onUploadError('You have not permision to do this function.'); ";
			echo "</script>";
			exit();
			return;
        }
	}

	$UPLOADER = new OMUpload($DB);
    $dt=null;
    $r=0;
	$r2=0;

	if ($cmd == "gettree") {
        $tree = array( );
        $i=0;
        $vf="";
        $folder_id=0;
        $r = $DB->query($dt, "select * from media_file where module_name = 'WYSIWYG' and  module_type = 'FOLDER' order by virtual_folder");
        if ($r>0) {
            for($i=0;$i<count($dt);$i++) {
                $vf = $dt[$i]["virtual_folder"];
                $folder_id = $dt[$i]["media_file_id"];
                buildTree($tree, $vf, $folder_id);
            }
        }
		$result = array("status"=>"OK", "tree"=>$tree);
		echo OMJson::encode($result);
	}
	if ($cmd == "getfiles") {
        $i = 0;
        $parentid = 0;
		$files = array();
        $parentid = OMValidate::tryParseLong(OMStringUtils::GET("id") , 0);
        $module_name = htmlspecialchars(OMStringUtils::GET("module_name"), ENT_QUOTES);
        $content_id = htmlspecialchars(OMStringUtils::GET("content_id"), ENT_QUOTES);
        $temp_id = htmlspecialchars(OMStringUtils::GET("temp_id"), ENT_QUOTES);
        $lang = htmlspecialchars(OMStringUtils::GET("lang"), ENT_QUOTES);

        $whereSelect = "ref_id = @ref_id";
        if($temp_id != ""){
        	$whereSelect = "temp_name = @temp_id";
        }

        $r = $DB->query($dt, "select * from ".$module_name."_media_file where upload_type = 'WYSIWYG' and lang = @lang and ".$whereSelect, array("ref_id"=>$content_id, "temp_id"=>$temp_id, "lang"=> $lang));
		if ($r>0) {
                        foreach($dt as $dt_value) {

                                $media_filename = OMImage::readFileName($dt_value["uuname"], $dt_value["original_name"],"w78x78",$module_name);
                                $media_original_filename = OMImage::readFileName($dt_value["uuname"], $dt_value["original_name"],"o0x0",$module_name);

				$files[count($files)] = array(
									"id"=>$dt_value["uuname"],
									"name"=>$dt_value["original_name"],
									"ext"=>$UPLOADER->GetExtension($dt_value["original_name"]),
									"filename"=>$media_filename,
									"module_name"=>$module_name,
									"media_original_filename"=> $media_original_filename
									);

            }
        }
		$result = array();
		$result["status"] = "OK";
		$result["files"] = $files;
		$result["d"] = $DB->LastSQLQueryString;
		echo OMJson::encode($result);
	}
	if ($cmd == "delete") {
		$result = array();

		$ids = explode(',', OMStringUtils::_TRIMPOST("i"));
		$sql_param = array();
		$i=0;
		$result["status"] = "OK";
		if (count($ids) > 0) {
			for ($i=0;$i<count($ids);$i++) {
				list($media_file_id,$module_name) =  explode("|", $ids[$i]);
				$sql_param["@media_file_id"]= $media_file_id;
				$r = $DB->execute("DELETE FROM ".$module_name."_media_file  where upload_type = 'WYSIWYG' and (upload_type='WYSIWYG') and uuname = @media_file_id", $sql_param);
				if ($r>0) {
				} else {
					$result["status"] = "ERROR";
					$result["message"] = "Fatal error: Unable to delete some media files.";
				}
			}
		} else {
			$result["status"] = "ERROR";
			$result["message"] = "Fatal error: Unable to delete the media file.";
		}
		echo OMJson::encode($result);
	}
	if ($cmd == "deletefolder") {
		$result = array();
		$folderid  = OMValidate::tryParseLong(OMStringUtils::POST("id"), 0);
		$sql_param = array();
		if ($folderid > 0) {
			$r = $DB->query($dt, "select * from media_file where module_name = 'WYSIWYG' and module_type='FOLDER' and media_file_id = @media_file_id ", array("@media_file_id"=>$folderid));
			if ($r>0) {
				$virtual_folder = $DB->getString($dt, 0, "virtual_folder");
				$sql_param = array();
				$sql_param["@virtual_folder"] = $virtual_folder;
				$r2 = $DB->execute("update media_file set module_type=concat('DELETE_', module_type) where module_name = 'WYSIWYG' and (module_type='EXTRA' or module_type='FOLDER') and (virtual_folder = @virtual_folder or virtual_folder like concat(@virtual_folder, '/%'))", $sql_param);
				if ($r2>0) {
					$result["status"] = "OK";
				} else {
					$result["status"] = "ERROR";
					$result["message"] = "Fatal error: Unable to delete folder.";
				}
			} else {
				$result["status"] = "ERROR";
				$result["message"] = "Unable to delete folder because the folder does not exist now.";
			}
		} else {
			$result["status"] = "ERROR";
			$result["message"] = "Unable to delete folder because the folder does not exist now.";
		}
		echo OMJson::encode($result);
	}
	if ($cmd == "renamefolder") {
		$result = array();
		$folderid  = OMValidate::tryParseLong(OMStringUtils::POST("id") , 0);
		$sFolderName = OMStringUtils::POST("foldername");
		$sql_param = array();
		if ($folderid > 0 && $sFolderName != "") {
			$r = $DB->query($dt, "select * from media_file where module_name = 'WYSIWYG' and module_type='FOLDER' and media_file_id = @media_file_id ", array("@media_file_id"=>$folderid));
			if ($r>0) {
				$virtual_folder = $DB->getString($dt, 0, "virtual_folder");
				$parent_path = "";
				$new_virtual_folder = "";
				$j;
				$j = strrpos($virtual_folder,'/');
				if ($j>=0) {
					$parent_path = substr($virtual_folder, 0, $j);
				} else {
					$parent_path = "";
				}
				$new_virtual_folder = $parent_path . "/" . $sFolderName;

				$sql_param = array();
				$sql_param["module_name"] = "WYSIWYG";
				$sql_param["module_type"] = "FOLDER";
				$sql_param["virtual_folder"] = $new_virtual_folder;
				$r2 = $DB->query($dt, "select * from media_file where module_name = @module_name and module_type = @module_type and virtual_folder = @virtual_folder", $sql_param);
				if ($r2 > 0) {
					$result["status"] = "ERROR";
					$result["message"] = "Unable to rename folder because the new name already be used by other.<br/>" . "select * from media_file where module_name = @module_name and module_type = @module_type and virtual_folder = @virtual_folder" . "<br/>" . OMJson::encode($sql_param) . "<br/>num_rows = " . $r2;
				} else {
					$sql_param = array();
					$sql_param["@virtual_folder"] = $virtual_folder;
					$sql_param["@new_virtual_folder"] = $new_virtual_folder;

					$r2 = $DB->execute("update media_file set virtual_folder = concat(@new_virtual_folder, SUBSTRING(virtual_folder, CHAR_LENGTH(@virtual_folder)+1,CHAR_LENGTH(virtual_folder))) where module_name = 'WYSIWYG' and (module_type='EXTRA' or module_type='FOLDER') and (virtual_folder = @virtual_folder or virtual_folder like concat(@virtual_folder, '/%'))", $sql_param);
					if ($r2>0) {
						$result["status"] = "OK";
					} else {
						$result["status"] = "ERROR";
						$result["message"] = "Fatal error: Unable to rename folder.";
						$result["debug"] = $DB->LastErrorMessage;
					}
				}



			} else {
				$result["status"] = "ERROR";
				$result["message"] = "Folder does not exists";
			}
		} else {
			$result["status"] = "ERROR";
			$result["message"] = "Folder does not exists";
		}
		echo OMJson::encode($result);
	}
    if ($cmd == "createfolder") {
        $parentid = 0;

        $bValidFolder = true;
        $newid = 0 ;
        $sParentFolder = "/";
        $sVirtualFolder = "";
        $sFolderName =OMStringUtils::POST("foldername") ;

        $result = array();

        $parentid = OMValidate::tryParseLong(OMStringUtils::POST("parentid"),0);
        if ($bValidFolder) {
            $bValidFolder = OMValidate::validateString($sFolderName, 1, 32, "alphanumeric_th");
            if (!$bValidFolder) {
                $result["status"] = "ERROR";
                $result["message"] = "Unable to create new folder because the illegal charactors was found in folder name.";
            }
        }

        if ($bValidFolder) {
            $sql = "select * from media_file where media_file_id = @media_file_id and module_type = 'FOLDER'";
            if ($parentid == 0) {
                $bValidFolder &= true;
                $sParentFolder = "/";
            } else {
                $r = $DB->query($dt, $sql, array("@media_file_id"=>$parentid));
                if ($r > 0) {
                    $sParentFolder = $dt[0]["virtual_folder"];
                    $bValidFolder &= true;
                } else {
                    $bValidFolder = false;
                    $result["status"] = "ERROR";
                    $result["message"] = "Unable to create new folder because the parent's folder does not exist now.";
                }
            }
        }

        if ($bValidFolder) {
            if (substr($sParentFolder,-1) == "/") {
                $sVirtualFolder = $sParentFolder . $sFolderName;
            } else {
                $sVirtualFolder = $sParentFolder . "/" .  $sFolderName;
            }
        }
        if ($bValidFolder) {
            $p = array();
            if ($UPLOADER->createVirtualFolder("WYSIWYG", "extra/", $sVirtualFolder, $newid)) {
                $result["status"] = "OK";
                $result["folderid"] = $newid;
                $result["virtual_folder"] = $sVirtualFolder;
            } else {
                $result["status"] = "ERROR";
                if($UPLOADER->LastErrorMessage == "DUP") {
                	$result["message"] = "Unable to create new folder because the new name already be used by other.";
                } else {
                	$result["message"] = "Fatal error: Unable to create new folder.";
                }
            }
        }

        echo OMJson::encode($result);

    }
    if ($cmd =="form") {
		$frmSrc ="";
		$frmSrc = "<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n";
		$frmSrc .= "<head>\r\n";
		$frmSrc .= "	<meta content=\"no-cache\" http-equiv=\"Cache-Control\">\r\n";
		$frmSrc .= "	<meta content=\"no-cache\" http-equiv=\"Pragma\">\r\n";
		$frmSrc .= "	<meta content=\"0\" http-equiv=\"Expires\">\r\n";
		$frmSrc .= "	<meta content=\"text/html; charset=UTF-8\" http-equiv=\"Content-Type\"> \r\n";
		$frmSrc .= "	<title>File uploader</title>\r\n";
		$frmSrc .= "	<style type=\"text/css\">\r\n";
		$frmSrc .= "		body,td,th {\r\n";
		$frmSrc .= "			font-family: Arial, Tahoma, Verdana, Helvetica, sans-serif;\r\n";
		$frmSrc .= "			font-size: 11px;\r\n";
		$frmSrc .= "			color: #333333;\r\n";
		$frmSrc .= "		}\r\n";
		$frmSrc .= "	</style>\r\n";
		$frmSrc .= "	<script type=\"text/javascript\">\r\n";
		$frmSrc .= "	<!--\r\n";
		$frmSrc .= "		function runUpload(obj) {\r\n";
		$frmSrc .= "			var support_type = \"jpg,png,gif,3gp,7z,aac,ai,avi,doc,docx,flac,flv,gz,mdb,mkv,mid,midi,mov,mp3,mp4,odp,ods,odt,pdf,psd,rar,txt,rm,swf,wav,wmv,xml,xls,xlsx,zip\".toLowerCase();\r\n";
		$frmSrc .= "			var support_typeArray = support_type.split(\",\");\r\n";
		$frmSrc .= "			filename = obj.value.toLowerCase();\r\n";
		$frmSrc .= "			if (checkValidateBrowse(filename,support_type)){\r\n";
		$frmSrc .= "				if(filename.length > 25) {\r\n";
		$frmSrc .= "					filename = filename.substring(0,17) + \"...\" + filename.substring(filename.length-9,filename.length);\r\n";
		$frmSrc .= "				}\r\n";
		$frmSrc .= "				document.getElementById('frm_parentid').value = parent.OMB.ActiveFolderId;\r\n";
		$frmSrc .= "				parent.OMB.onStartUploading(filename);\r\n";
		$frmSrc .= "				document.forms[\"fileUploader\"].submit();\r\n";
		$frmSrc .= "				return true;\r\n";
		$frmSrc .= "			} else {\r\n";
		$frmSrc .= "				obj.value = \"\";\r\n";
		$frmSrc .= "				return false;\r\n";
		$frmSrc .= "			}\r\n";
		$frmSrc .= "		}		\r\n";
		$frmSrc .= "		function checkValidateBrowse(filename,support_type){\r\n";
		$frmSrc .= "			var txtValue = filename;			\r\n";
		$frmSrc .= "			var support_typeArray = support_type.split(\",\");			\r\n";
		$frmSrc .= "			var file_extension = txtValue.substring((txtValue.lastIndexOf(\".\")+1))\r\n";
		$frmSrc .= "			var valid_extension = false;			\r\n";
		$frmSrc .= "			for (var i = 0; i < support_typeArray.length ; i ++ ) {\r\n";
		$frmSrc .= "				if (support_typeArray[i] == file_extension) return true;\r\n";
		$frmSrc .= "			}\r\n";
		$frmSrc .= "			return false;\r\n";
		$frmSrc .= "		}\r\n";
		$frmSrc .= "	//-->\r\n";
		$frmSrc .= "	</script>\r\n";
		$frmSrc .= "</head>\r\n";
		$frmSrc .= "<body style=\"padding: 0px; margin: 0px;\">\r\n";
		$frmSrc .= "<form enctype=\"multipart/form-data\" action=\"core_media_service.php\" method=\"post\" id=\"fileUploader\">\r\n";
		$frmSrc .= "<div class=\"field\">\r\n";
		$frmSrc .= "<input type=\"hidden\" name=\"cmd\" value=\"upload\">\r\n";
		$frmSrc .= "<input type=\"hidden\" name=\"module_name\" value=". htmlspecialchars(OMStringUtils::GET("module_name") , ENT_QUOTES).">\r\n";
		$frmSrc .= "<input type=\"hidden\" name=\"content_id\" value=". htmlspecialchars(OMStringUtils::GET("content_id") , ENT_QUOTES).">\r\n";
		$frmSrc .= "<input type=\"hidden\" name=\"temp_id\" value=".htmlspecialchars(OMStringUtils::GET("temp_id"), ENT_QUOTES).">\r\n";
		$frmSrc .= "<input type=\"hidden\" name=\"lang\" value=".htmlspecialchars(OMStringUtils::GET("lang"), ENT_QUOTES).">\r\n";
		$frmSrc .= "<input type=\"hidden\" name=\"parentid\" id=\"frm_parentid\" value=\"0\" >\r\n";
		$frmSrc .= "<input type=\"file\" style=\"width: 280px; padding: 0pt; margin: 0pt;\" onchange=\"runUpload(this)\" id=\"fieldfile\" name=\"fieldfile\">\r\n";
		$frmSrc .= "</div>\r\n";
		$frmSrc .= "</form>\r\n";
		$frmSrc .= "</body></html>";
		echo $frmSrc;
	}
    if ($cmd == "upload") {
        if (count($_FILES) > 0 ) {
            //storeTempUploadFile
            $newid=0;
			$parentid=0;
			$original_file=array();
            $parentid = OMValidate::tryParseLong(OMStringUtils::POST("parentid"), 0);
            $module_name = OMStringUtils::POST("module_name");
            $content_id = OMStringUtils::POST("content_id");
            $temp_id = OMStringUtils::POST("temp_id");
            $lang = OMStringUtils::POST("lang");

            $newid = $UPLOADER->storeTempUploadFile($_FILES["fieldfile"], $original_file, $module_name , $content_id, $temp_id);
            if(!$newid){
            	echo "<script language=\"javascript\">";
            	echo "parent.OMB.onUploadError('Fatal error: Unable to store your upload file.'); ";
            	echo "</script>";
            	exit();
            }
            $virtual_folder = "/";
            $sql = "select uuname  from ".$module_name."_media_file where uuname = @uuname";
            $r = $DB->query($dt, $sql, array("@uuname"=>$original_file["media_file_id"]));
            if ($r>0) {
	            if (isset($original_file["media_file_id"]) && $original_file["media_file_id"] != "") {
	                if ($UPLOADER->storeMediaFile($original_file["media_file_id"], $module_name, "WYSIWYG", $content_id, "publish", "", $lang)) {
	                    echo "<script language=\"javascript\">";
	                    echo "parent.OMB.onUploadCompleted();";
	                    echo "</script>";
	                } else {
	                    echo "<script language=\"javascript\">";
	                    echo "parent.OMB.onUploadError('Fatal error: Unable to store your upload file.');";
	                    echo "</script>";
	                }
	            } else {
	                echo "<script language=\"javascript\">";
	                echo "parent.OMB.onUploadError('Fatal error: Unable to store your upload file.'); ";
	                echo "</script>";
	            }
            } else {
	            echo "<script language=\"javascript\">";
	            echo "parent.OMB.onUploadError('Fatal error: Unable to store your upload file.'); ";
	            echo "</script>";
	        }

        } else {
            echo "<script language=\"javascript\">";
            echo "parent.OMB.onUploadError('Fatal error: Unable to store your upload file.'); ";
            echo "</script>";
        }

    }
?>