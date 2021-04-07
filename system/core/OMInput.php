<?php
namespace OMCore;

Class OMInput{
	private static $_inputBox = array();

	public static function setInput($input = array()){
		foreach ($input as $valIn) {
			$attr = "";
			if(isset($valIn["attr"]) && $valIn["attr"] != ""){
				foreach ($valIn["attr"] as $key => $value) {
					$attr .= $key."='".$value."'";
				}
			}
			if(self::checkInput($valIn["type"])){
				self::$_inputBox[$valIn["name"]] = "<input type='".$valIn["type"]."' id='".$valIn["name"]."' name='".$valIn["name"]."' value='".$valIn["value"]."' ".$attr." >";
			}else if($valIn["type"] == "area"){
				self::$_inputBox[$valIn["name"]] = "<textarea id='".$valIn["name"]."' name='".$valIn["name"]."' ".$attr.">".$valIn["value"]."</textarea>";
			}else if($valIn["type"] == "select"){
				self::$_inputBox[$valIn["name"]] = "<select id='".$valIn["name"]."' name='".$valIn["name"]."' ".$attr.">".$valIn["value"]."</select>";
			}
		}
	}
	public static function getInput($name){
		return self::$_inputBox[$name];
	}

	private static function checkInput($type){
		$inputType = array("text", "hidden", "password");
		if(in_array($type, $inputType)){
			return true;
		}
		return false;
	}

	// how to use

	// $data = array();

	// $data[0]["type"] = "text";
	// $data[0]["name"] = "password";
	// $data[0]["value"] =	"";
	// $data[0]["attr"] =	array("readonly"=>"readonly","placeholder"=>"hello");

	// $data[1]["type"] = "hidden";
	// $data[1]["name"] = "hahah";
	// $data[1]["value"] =	"";

	// $data[2]["type"] = "area";
	// $data[2]["name"] = "area";
	// $data[2]["value"] =	"aa";

	// $data[3]["type"] = "select";
	// $data[3]["name"] = "select";
	// $data[3]["value"] =	"<option value='0'>All</option><option value='1'>aaaa</option>";

	// OMInput::setInput($data);
	// print(OMInput::getInput("password"));
	// print(OMInput::getInput("hahah"));
	// print(OMInput::getInput("area"));
	// print(OMInput::getInput("select"));
}
?>