<?php

namespace OMCore;

class OMACL {

	function __construct() {

	}

	public function checkIntranet($ip_address = null) {
		if ($ip_address == null) {
			$ip_address = OMNetwork::GetRemoteIP();
		}

		$pattern = '/(^127\.0\.0\.1)|(^10\.)|(^172\.1[6-9]\.)|(^172\.2[0-9]\.)|(^172\.3[0-1]\.)|(^192\.168\.)|localhost/';
		$status = preg_match($pattern, $ip_address);
		if($status){
			return true; // is internal
		}else{
			return false; // is external
		}
    }

    public function getACLByIntranet($status) {
    	$param = "";

    	if($status){
			$param = USER_INTRANET;
    	}else{
			$param = USER_GUEST;
    	}

    	$ds = OMDb::table("acl")->where("title",$param);
    	// echo $ds->__toString();

    	if($ds != null){
			return $ds[0];
		}else{
			return null;
		}

    }

    public function getACLByLdap($param) {

    	$param = json_decode($param,true);

    	$username = OMDb::table("acl_child")->where(array("check_type" => "USERNAME", "department" => @$param["username"]));
    	$department = OMDb::table("acl_child")->where(array("check_type" => "DEPARTMENT", "department" => @$param["department"]));
    	$company = OMDb::table("acl_child")->where(array("check_type" => "COMPANY", "department" => @$param["company"]));
    	$jobtitle = OMDb::table("acl_child")->where(array("check_type" => "JOBTITLE", "department" => @$param["title"]));



    	if(count($username) > 0 || count($department) > 0 || count($company) > 0 || count($jobtitle) > 0){
    		$acl = null;
    		$obj_parent_id = null;
    		if(count($username) > 0){
    			$obj_parent_id = $username;
    		}elseif(count($department) > 0){
    			$obj_parent_id = $department;
    		}elseif(count($company) > 0){
    			$obj_parent_id = $company;
    		}elseif(count($jobtitle) > 0){
    			$obj_parent_id = $jobtitle;
    		}

    		$acl = OMDb::table("acl")->select("*")->where("acl_id",$obj_parent_id[0]["obj_parent_id"]);


    		return $acl[0];

    	}else{

    		$acl = OMDb::table("acl")->select("*")->where("title",USER_INTRANET);

    		return $acl[0];
    	}

    	// echo count($acl_child);





  //   	if($ds != null){
		// 	return $ds[0];
		// }else{
		// 	return null;
		// }

  //   	$ds = OMDb::table("member")->select("acl.*")
  //   	->join("join acl_child on member.member_type = acl_child.department")
  //   	->join("join acl on acl.acl_id = acl_child.obj_parent_id")
  //   	->where("member.member_type != ?", USER_INTERNET)
  //   	->and("member.email",$email);

  //   	if($ds != null){
		// 	return $ds[0];
		// }else{
		// 	return null;
		// }

    }

    public function getACLByMember($email = "") {

    	$ds = OMDb::table("member")->select("acl.*")
    	->join("join acl_child on member.member_type = acl_child.department")
    	->join("join acl on acl.acl_id = acl_child.obj_parent_id")
    	->where(array("member.email"=>$email,"member_type"=>USER_INTERNET));
    	// echo $ds->__toString();

    	if($ds != null){
			return $ds[0];
		}else{
			return null;
		}

    }

    public function getACLAuthMember($username,$password){

    	$ds = OMDb::table("member")
    	->select("*")
    	->where("email",$username)
    	->where("member_type",USER_INTERNET)
    	->where("password",md5($password))
    	->where("verify","T")
    	->count();

    	if($ds > 0 ){
			return true;
		}else{
			return false;
		}
    }

    public function getCategoryByACLId($acl_id){

		$ds = OMDb::table("category")
		->select("category.category_id,category.obj_path_id,category.path")
		->join("JOIN category_permission ON category.category_id = category_permission.obj_parent_id")
		->where(
		        array(
		              "category_permission.acl"=>$acl_id,
		              "category.obj_lang"=>strtoupper(LANG)
		              ));

		if($ds != null){
			return $ds;
		}else{
			return null;
		}
    }

    public function getWebcategoriesByACLId($acl_id){

		$ds = OMDb::table("webcategories")
		->select("webcategories.webcategories_id,webcategories.obj_path_id,webcategories.path")
		->join("JOIN webcategories_permission ON webcategories.webcategories_id = webcategories_permission.obj_parent_id")
		->where(
		        array(
		              "webcategories_permission.acl"=>$acl_id,
		              "webcategories.obj_lang"=>strtoupper(LANG)
		              ));

		if($ds != null){
			return $ds;
		}else{
			return null;
		}
    }

    public function getObjPathID($category_id){

		$ds = OMDb::table("category")
		->select("obj_path_id")
		->where(array("category_id"=>$category_id));

		if($ds != null){
			return $ds[0]["obj_path_id"];
		}else{
			return null;
		}

	}

	public function getObjPathIDWebcategories($webcategories_id){

		$ds = OMDb::table("webcategories")
		->select("obj_path_id")
		->where(array("webcategories_id"=>$webcategories_id));

		if($ds != null){
			return $ds[0]["obj_path_id"];
		}else{
			return null;
		}

	}

    public function getCategoryForACL($acl_id,$tablename = "",$select = "*",$where_more = "") { //  get Category all feild only
    	$DB = OMDb::singleton();
    	$param = array();

    	$now = date("Y-m-d H:i:s");

		$ds = $this->getCategoryByACLId($acl_id);
		$join = "";

		if($ds != null){
			$where = "";
			$where_a = array();
			$param = array();
			foreach ($ds as $key => $value) {
				$where_a[] = " category.obj_path_id LIKE '%".$value["obj_path_id"]."%' ";
			}

			$where = implode("OR", $where_a);

			if($where != ""){
				$where = "AND (".$where.")";
			}

			if($where_more != ""){
				$where .= " AND ".$where_more;
			}

			$where .= " AND category.obj_lang = '".strtoupper(LANG)."' ";

			if($tablename != ""){

				if($select != $tablename."*"){

					$select = explode(",", $select);
					foreach ($select as $select_key => $select_value) {
						$select[$select_key] = $tablename.".".$select_value;
					}
					$select = implode(",", $select);
				}

				$join = "JOIN ".$tablename." ON ".$tablename.".category = category.category_id ";

			}

			$sql = "SELECT ".$select." FROM category  ".$join." WHERE 1=1 ".$where."  ORDER BY category.priority asc , category.obj_path_label asc ";
			$rs = $DB->query($ds,$sql,null,0,-1,"ASSOC");

			if($rs > 0){
				return $ds;
			}else{
				return null;
			}
		}else{
			return null;
		}
    }

    public function getListContent($acl_id,$content_id = 0,$field = null,$where_more = "") {

    	if($field == null){
    		$field = "document.document_id,document.title";
    	}else{
    		if(count($field) > 0){
    			foreach ($field as $field_key => $field_value) {
    				$field[$field_key] = "document.".$field_value;
    			}
    		}
    		$field = implode(",", $field);
    	}

    	$DB = OMDb::singleton();
    	$param = array();

    	$now = date("Y-m-d H:i:s");

		$ds = $this->getCategoryByACLId($acl_id);

		if($ds != null){
			$where = "";
			$where_a = array();
			$param = array();
			foreach ($ds as $key => $value) {

				$where_a[] = " category.obj_path_id LIKE '%".$value["obj_path_id"]."%' ";

			}

			$where = implode("OR", $where_a);

			if($where != ""){
				$where = "AND (".$where.")";
			}

			if($content_id != 0){
				$where .= "AND document_id = ".$content_id;
			}

			if($where_more != ""){
				$where .= " ".$where_more." ";
			}

			$where .= " AND category.obj_lang = '".strtoupper(LANG)."' AND document.obj_lang = '".strtoupper(LANG)."'";

			$sql = "SELECT ".$field." FROM category JOIN document ON document.category = category.category_id WHERE '".$now."' >= document.valid_date ".$where."  ORDER BY document.valid_date DESC ";
			$rs = $DB->query($ds,$sql,null,0,-1,"ASSOC");
			if($rs > 0){
				return $ds;
			}else{
				return null;
			}
		}else{
			return null;
		}
    }

    public function getListBySlugName($acl_id,$slugname,$outerWhere = "") {
    	$DB = OMDb::singleton();
    	$param = array();

    	$now = date("Y-m-d H:i:s");

		$ds = $this->getCategoryByACLId($acl_id);

		$select = "a.*";

		if($ds != null){
			$where = "";
			$where_a = array();
			$param = array();
			foreach ($ds as $key => $value) {

				$where_a[] = " c.obj_path_id LIKE '%".$value["obj_path_id"]."%' ";

			}

			$where = " c.slug_name like '%".$slugname."%'";
			$where_is = implode("OR", $where_a);


			if($where_is != ""){
				$where .= " AND (".$where_is.")";
			}

			if($outerWhere != ""){
				$where .= $outerWhere;
			}

			if($slugname != "calendar"){
				$where .= " '".$now."' >= a.valid_date ";
			}


			$where .= " AND c.obj_lang = '".strtoupper(LANG)."' AND a.obj_lang = '".strtoupper(LANG)."'";
			$sql = "SELECT ".$select." FROM category c JOIN document a ON a.category = c.category_id WHERE  ".$where."  ORDER BY a.valid_date DESC ";
			$rs = $DB->query($ds,$sql,null,0,-1,"ASSOC");

			if($rs > 0){
				return $ds;
			}else{
				return null;
			}
		}else{
			return null;
		}
    }


    public function getMembertypByEmail($email = ""){
    	if($email == USER_GUEST){
    		return USER_GUEST;
    	}
    	$ds = OMDb::table("member")->select("member_type")->where("email",$email);
    	if($ds != null){
			return $ds[0]["member_type"];
		}else{
			return null;
		}
    }

    public function getCategoryIDFormCookie(){
    	$acl_id = $this->getAclID();

		if($acl_id != null){
			$category_a = array();
			$category = $this->getCategoryByACLId($acl_id);
			$webcategories = $this->getWebcategoriesByACLId($acl_id);

			if(count($category) > 0 || count($webcategories) > 0){
				if(count($category) > 0){
					foreach ($category as $category_key => $category_value) {
						array_push($category_a, $this->getObjPathID($category_value["category_id"]));
					}
				}

				if(count($webcategories) > 0){
					foreach ($webcategories as $webcategories_key => $webcategories_value) {
						array_push($category_a, $this->getObjPathIDWebcategories($webcategories_value["webcategories_id"]));
					}
				}

				$category_a = implode(",", $category_a);

				return $category_a;

			}else{
				return null;
			}

		}else{
			return null;
		}

	}

	//  public function getCategoryIDFormCookie(){
 //    	$acl_id = $this->getAclID();

	// 	if($acl_id != null){
	// 		$category_a = array();
	// 		$category = $this->getCategoryByACLId($acl_id);

	// 		if(count($category) > 0 ){

	// 			foreach ($category as $key => $value) {
	// 				array_push($category_a, $this->getObjPathID($value["category_id"]));
	// 			}

	// 			$category_a = implode(",", $category_a);

	// 			return $category_a;

	// 		}else{
	// 			return null;
	// 		}

	// 	}else{
	// 		return null;
	// 	}

	// }

	public function getAclID(){
		$cookie = new OMCookie();
		$acl = new OMACL();
		$acl_data = array();
		$ip_address = OMNetwork::GetRemoteIP();

		if($acl->checkIntranet($ip_address)){
			$usernameldap = $cookie->get(COOKIE_USERNAME_LDAP);
			$acl_data = $this->getACLByLdap($usernameldap);
		}else{
			$email = $cookie->get(COOKIE_USERNAME);
			$eic_ldap = $this->getMembertypByEmail($email);

			if($eic_ldap == USER_INTERNET){
				$acl_data = $this->getACLByIntranet(true);
			}else{
				$acl_data = $this->getACLByIntranet(false);
			}

			// $ip_address = OMNetwork::GetRemoteIP();

			// if($this->checkIntranet($ip_address)){
			// 	$acl_data = $this->getACLByIntranet(true);
			// }else{
			// 	$acl_data = $this->getACLByIntranet(false);
			// }
		}

		if(!empty($acl_data)){
			return $acl_data["acl_id"];
		}else{
			return null;
		}

	}


}
?>