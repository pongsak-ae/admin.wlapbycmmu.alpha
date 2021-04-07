<?php

use OMCore\OMDb;

function getRole() {
	$param_sql = array();

	$DB = OMDb::singleton();
	$dt = null;
	$str_sql = "SELECT * FROM user_role JOIN user_role_permission ON user_role.user_role_id  = user_role_permission.user_role_id JOIN user_permission ON user_role_permission.user_permission_id = user_permission.user_permission_id ORDER BY user_role.user_role_name,user_permission.user_permission_name ASC";

	$dt = null;
	$r = $DB->query($dt,$str_sql,null,0,-1,"ASSOC");
	if ($r <= 0) {
		return array();
	} else {
		$list_role = array();
		$_last_role = "";
		for ($i=0;$i<count($dt);$i++) {
			if ($_last_role != $dt[$i]["user_role_id"]) {
				$obj_role = array();
				$obj_role["user_role_id"] = $dt[$i]["user_role_id"];
				$obj_role["user_role_name"] = $dt[$i]["user_role_name"];
				
				$obj_role["permission_list"] = array();
				$obj_role["permission_list"][$dt[$i]["user_permission_key"]] = $dt[$i]["user_permission_name"];


				$list_role[] = $obj_role;

				$_last_role = $dt[$i]["user_role_id"];
			} else {
				$list_role[count($list_role)-1]["permission_list"][$dt[$i]["user_permission_key"]] = $dt[$i]["user_permission_name"];
			}
		}
		return $list_role;
	}
}

function userLogin($username,$password) {
	$data_user = getUserData($username,$password);
	$res = array();
	if (count($data_user) == 1) {
		$res["status"] = 'success';
		$res["data"] = $data_user[0];
		$_SESSION["OM_USER"] = $data_user[0];
	} else {
		$res["status"] = 'error';
	}

	return $res;
}

function userLogout() {
	unset($_SESSION["OM_USER"]);
	$res = array();
	$res["status"] = 'success';
	return $res;
}

function checkPermission($permission_key) {

	if(isset($_SESSION["OM_USER"])){
		$arr_permission = $_SESSION["OM_USER"]["permission_list"];
		if(isset($_SESSION["OM_USER"]["permission_list"][$permission_key])){
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function deleteUser($user_id) {
	$DB = OMDb::singleton();

	$str_sql = "SELECT * FROM user WHERE user_role_id = 1 ";

	$dt = null;
	$r = $DB->query($dt,$str_sql,null,0,-1,"ASSOC");
	if ($r == 1) {
		if ($dt[0]["user_id"] == $user_id) {
			$res = array();
			$res["status"] = "error";
			$res["error_msg"] = "CANNOT_DELETE";
			return $res;
		}
	}
	$dt = null;
	$str_sql = "DELETE FROM user WHERE user_id = @user_id ";
	$param_sql = array();
	$param_sql["user_id"] = $user_id;

	$res = array();
	$dt = null;
	$r = $DB->execute($str_sql,$param_sql);

	$res = array();
	$res["status"] = "success";
	return $res;
}

function updateUser($param=array()) {
	$res = array();

	if (isset($param["user_id"]) && isset($param["password"]) && isset($param["user_role_id"])) {
		$DB = OMDb::singleton();

		$content_id = -1;
		$param_sql = array();
		$param_sql["user_id"] = $param["user_id"];
		$param_sql["password"] = md5($param["password"]);
		$param_sql["user_role_id"] = $param["user_role_id"];
		$param_sql["modify_date"] = date("Y-m-d H:i:s");
		$r = $DB->executeUpdate("user",1,$param_sql);

		if ($r == 1) {
			$res["status"] = "success";
		} else {
			$res["status"] = "error";
			$res["error_msg"] = "CANNOT_CREATE_USER";
		}

		if (isset($_SESSION["OM_USER"])) {
      		if ($_SESSION["OM_USER"]["user_id"] == $param["user_id"]) {
      			userLogin($_SESSION["OM_USER"]["username"],$param["password"]);
      		}
  		}

	} else {
		$res["status"] = "error";
		$res["error_msg"] = "MISSING_PARAMETER";
	}
	return $res;
}

function createUser($param=array()) {
	$res = array();

	if (isset($param["username"]) && isset($param["password"]) && isset($param["user_role_id"])) {
		$DB = OMDb::singleton();

		$dt = null;
		$str_sql = "SELECT * FROM user WHERE username = @username ";
		$param_sql = array();
		$param_sql["username"] = $param["username"];

		$res = array();
		$dt = null;
		$r = $DB->query($dt,$str_sql,$param_sql,0,-1,"ASSOC");
		if ($r == 0) {
			$content_id = -1;
			$param_sql = array();
			$param_sql["username"] = $param["username"];
			$param_sql["password"] = md5($param["password"]);
			$param_sql["user_role_id"] = $param["user_role_id"];
			$param_sql["create_date"] = date("Y-m-d H:i:s");
			$DB->executeInsert("user",$param_sql,$content_id);

			if ($content_id != -1) {
				$res["status"] = "success";
			} else {
				$res["status"] = "error";
				$res["error_msg"] = "CANNOT_CREATE_USER";
			}
		} else {
			$res["status"] = "error";
			$res["error_msg"] = "USERNAME_DUPLICATE";
		}
	} else {
		$res["status"] = "error";
		$res["error_msg"] = "MISSING_PARAMETER";
	}
	return $res;
}

function getUserData($username=null,$password=null,$user_id=0) {
	$param_sql = array();

	$list_user = array();

	$DB = OMDb::singleton();
	$dt = null;
	$str_sql = "SELECT user.*,user_role.user_role_name,user_permission.user_permission_key,user_permission.user_permission_name ";
	$str_sql.= "FROM user ";
	$str_sql.= "JOIN user_role ON user.user_role_id = user_role.user_role_id ";
	$str_sql.= "JOIN user_role_permission ON user_role.user_role_id  = user_role_permission.user_role_id ";
	$str_sql.= "JOIN user_permission ON user_role_permission.user_permission_id = user_permission.user_permission_id ";
	if (isset($username) && isset($password)) {
		$str_sql.= "WHERE username = @username and password = @password ";
		$param_sql["username"] = $username;
		$param_sql["password"] = md5($password);
	} else if ($user_id != -1 && $user_id != 0) {
		$str_sql.= "WHERE user_id = @user_id ";
		$param_sql["user_id"] = $user_id;
	} else if ($user_id == -1) {
		$obj_user = array();
		$obj_user["user_id"] = -1;
		$obj_user["username"] = "System";
		$obj_user["user_role_id"] = null;
		$obj_user["user_role_name"] = null;
		$obj_user["create_date"] = null;
		
		$obj_user["permission_list"] = array();

		$list_user[] = $obj_user;

		return $list_user;
	} else {
		$str_sql.= "order by user.username,user_permission.user_permission_name  asc ";
	}
	$res = array();
	$dt = null;
	$r = $DB->query($dt,$str_sql,$param_sql,0,-1,"ASSOC");
	if ($r == 0) {
		return array();
	} else {
		$_last_username = "";
		for ($i=0;$i<count($dt);$i++) {
			if ($_last_username != $dt[$i]["username"]) {
				$obj_user = array();
				$obj_user["user_id"] = $dt[$i]["user_id"];
				$obj_user["username"] = $dt[$i]["username"];
				$obj_user["user_role_id"] = $dt[$i]["user_role_id"];
				$obj_user["user_role_name"] = $dt[$i]["user_role_name"];
				$obj_user["create_date"] = $dt[$i]["create_date"];
				
				$obj_user["permission_list"] = array();
				$obj_user["permission_list"][$dt[$i]["user_permission_key"]] = $dt[$i]["user_permission_name"];


				$list_user[] = $obj_user;

				$_last_username = $dt[$i]["username"];
			} else {
				$list_user[count($list_user)-1]["permission_list"][$dt[$i]["user_permission_key"]] = $dt[$i]["user_permission_name"];
			}
		}
		return $list_user;
	}
}

?>