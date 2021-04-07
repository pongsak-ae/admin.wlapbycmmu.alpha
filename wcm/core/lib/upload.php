<?php
class OMUpload {
	private $_DB = null;
	private $_TMPFOLDER = "tmp/";
	private $_LastErrorMessage = "";

	function __construct($DB) {
		$this->_DB = $DB;
	}
	function __get($Name) {
        switch ($Name) {
            case 'LastErrorMessage':
                return $this->_LastErrorMessage;
                break;
        }
        user_error("Invalid property: " . __CLASS__ . "->" . $Name);
    }
	function GetExtension($filename) {
		return '.' . end(explode(".", $filename));
	}
	function isFlashFile($filename) {
		$img_ext = array("swf"=>"swf");
		$fileext;
		if (strrpos($filename,'.') >= 0) {
			$fileext = substr(strtolower($filename), strrpos($filename,'.') + 1);
			return (array_key_exists($fileext, $img_ext));
		} else {
			return false;
		}
	}
	function uuname(){
		$microTime = round(microtime(true)*1000);
		$randomString = rand(100,999);
		$base = $microTime.$randomString;
		$baseUname = base_convert( $base ,10, 36 );
		return $baseUname;
	}

	function storeTempUploadFile($PostFile, &$Info = null, $module_name, $ref_id = 0, $temp_id) {
		$newid = 0;
        $retr;
        $Info = array();
        $p = array();
        $ext="";
        $ext = $this->GetExtension($PostFile["name"]);
       if(isset($PostFile)){
			$p["uuname"] = $this->uuname();
			$p["data"] = file_get_contents($PostFile['tmp_name']);
			$p["upload_date"] = OMDateTime::Now();
			$p["original_name"] = $PostFile['name'];
			$p["upload_type"] = "TEMP";
			$p["state"] = "draft";
			$p["content_type"] = $PostFile["type"];
			$p["ref_id"] = intval($ref_id);
			$p["temp_name"] = $temp_id;
			$Info["original_filename"] = $p["original_name"];
        	$Info["original_file_ext"] = $ext;
        	$Info["original_file_mime"] = $PostFile["type"];
        	$Info["original_file_mime"] = $PostFile["size"];

	        $retr = $this->_DB->executeInsert($module_name."_media_file", $p);
	        if ($retr > 0) {
	        	$Info["media_file_id"] = $p["uuname"];
                $Info["media_filename"] = $p["uuname"];
	            return $retr;
	        } else {
	            return -1;
	        }
		}else{
			return false;
		}

	}

	function storeMediaFile($media_file_id, $module_name, $upload_type, $ref_id, $state="publish",$field_id = "", $lang = "") {

        $dt = null;
        $r=0;
        $bCT=false;
        $r = $this->_DB->query($dt, "select uuname from ".$module_name."_media_file where uuname = @uuname ", array("@uuname"=>$media_file_id,"@ref_id"=>$ref_id));
        if ($r > 0) {
            $p = array();
            $p["uuname"] = $dt[0]["uuname"];
            $p["field_id"] = $field_id;
			$p["upload_type"] = $upload_type;
			$p["state"] = $state;
			$p["lang"] = $lang;
			$p["ref_id"] = $ref_id;
            $this->_DB->executeUpdate($module_name."_media_file", 1, $p);
        } else {
            $this->_LastErrorMessage = "Media ID does not exists";
            return false;
        }
        return true;
	}

	function stateChangeFile($data){
		if(isset($data)){
			$this->storeMediaFile($data["photo_gen_current"], $data["module_name"], $data["upload_type"], $data["item_id"],$data["state"],$data["field_id"]);
		}
	}


	function deteleFile($param){
		if(isset($param)){
			$sql = "DELETE FROM " . $param["module_name"]. "_media_file" ." WHERE uuname != @uuname AND ref_id = @ref_id AND field_id = @field_id AND lang = @lang";
			$rs = $this->_DB->execute($sql, $param);
	        if ($rs > 0) {
	            return true;
	        } else {
	            return false;
	        }
		}
	}

	function createVirtualFolder($module_name, $foldername, $virtual_folder, &$createdid) {
        $retr = 0;
        $newid=0;
        $p = array();
        $dt = null;
        if ($virtual_folder ==  null || $virtual_folder == "") {
            $createdid = 0;
            $this->_LastErrorMessage = "EMPTY";
            return false;
        }
        $p = array();
        $p["module_name"] = $module_name;
        $p["module_type"] = "FOLDER";
        $p["virtual_folder"] = $virtual_folder;
        $retr = $this->_DB->query($dt, "select * from media_file where module_name = @module_name and module_type = @module_type and virtual_folder = @virtual_folder", $p);
        if ($retr > 0) {
            $createdid = 0;
            $this->_LastErrorMessage = "DUP";
            return false;
        }
        $p = array();
        $p["media_filename"] = ".";
        $p["original_filename"] = ".";
        $p["original_file_ext"] = "";
        $p["original_file_mime"] = "";
        $p["original_filesize"] = 0;
        $p["module_name"] = $module_name;
        $p["module_type"] = "FOLDER";
        $p["folder"] = $foldername;
        $p["virtual_folder"] = $virtual_folder;
        $p["width"] = 0;
        $p["height"] = 0;
        $p["extra_info"] = "";
        $p["uploaded_date"] = OMDateTime::Now();
        $retr = $this->_DB->executeInsert("media_file", $p, $newid);
        $createdid = 0;
        if ($retr > 0) {
            $createdid = $newid;
        } else {
            $this->_LastErrorMessage = $this->_DB->LastErrorMessage;
            return false;
        }
        return true;
    }

}
?>