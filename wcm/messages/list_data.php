<?php
	require_once('../core/lib/all.php');
	if($SESSION->checkSession("only") ) { 
	
		$module_name = "wcm_message"; 
		$item_per_page = 20;

		$dtconv = new OMDateTimeConverter();
		$tb_data = new OMDataGrid();
		
		$folder = $SESSION->CookieDb->getCookie($module_name, "folder");

		if($folder == "i") {
			$tb_data->addHeaderColumn("is_read", "&#9679;", 8, "f1", "center", array("data_format"=>"html")); 
			$tb_data->addHeaderColumn("from_user_id", "From", 252, "f2", "left", array("data_format"=>"html"));
			$tb_data->addHeaderColumn("subject", "Subject", 450, "f3", "left", array("data_format"=>"html")); 
			$tb_data->addHeaderColumn("sent_date", "Received date", 120, "f4", "left", array("data_format"=>"html")); 
		} else if($folder == "s") {
			$tb_data->addHeaderColumn("is_read", "&#9679;", 8, "f1", "center", array("data_format"=>"html")); 
			$tb_data->addHeaderColumn("to_user_id", "To", 252, "f2", "left", array("data_format"=>"html"));
			$tb_data->addHeaderColumn("subject", "Subject", 450, "f3", "left", array("data_format"=>"html")); 
			$tb_data->addHeaderColumn("sent_date", "Sent date", 120, "f4", "left", array("data_format"=>"html"));
		} else if($folder == "t") {
			$tb_data->addHeaderColumn("is_read", "&#9679;", 8, "f1", "center", array("data_format"=>"html")); 
			$tb_data->addHeaderColumn("from_user_id", "From", 206, "f2", "left", array("data_format"=>"html"));
			$tb_data->addHeaderColumn("to_user_id", "To", 206, "f3", "left", array("data_format"=>"html"));
			$tb_data->addHeaderColumn("subject", "Subject", 290, "f4", "left", array("data_format"=>"html")); 
			$tb_data->addHeaderColumn("sent_date", "Date", 120, "f5", "left", array("data_format"=>"html")); 
		}

		$new_setting = array();
		$setting = $SESSION->CookieDb->getCookies($module_name);
		$sql_param = array();

		$i=0;
		$where_sql = "";
		if(isset($setting["now_keyword"]) && $setting["now_keyword"] != null) {
			if(trim($setting["now_keyword"]) != "") {
				$kw = $setting["now_keyword"];
				$where_sql .= OMTemplateWCM::createWhereFromFilter($sql_param, "keywordfield0", "a.from_user_id", "user" , "", $kw);
				$where_sql .= " or " . OMTemplateWCM::createWhereFromFilter($sql_param, "keywordfield1", "a.to_user_id", "user" , "", $kw);
				$where_sql .= " or " . OMTemplateWCM::createWhereFromFilter($sql_param, "keywordfield2", "a.subject", "text" , "", $kw);
				$where_sql .= " or " . OMTemplateWCM::createWhereFromFilter($sql_param, "keywordfield3", "a.body", "text" , "", $kw);
				$where_sql = " and (" . $where_sql . ") ";
			}
		}

		$where_oper = "";
		$where_sql2 = "";
		if(isset($setting["now_plist"]) && $setting["now_plist"] != null) {
			if(trim($setting["now_plist"]) != ""){
				$now_plist_arr = explode(',', $setting["now_plist"]);
				if($setting["now_mode"] == "all"){ $where_oper = " and "; } else { $where_oper = " or "; }
				for($i = 0; $i < count($now_plist_arr); $i++) {
					$p_f = $setting["now_p" . $now_plist_arr[$i] . "_f"];
					$p_o = $setting["now_p" . $now_plist_arr[$i] . "_o"];
					$p_v = $setting["now_p" . $now_plist_arr[$i] . "_v"];
					if($i != 0) { $where_sql2 .= $where_oper; }
					switch($p_f){
						case "sent_date": $where_sql2 .= OMTemplateWCM::createWhereFromFilter($sql_param, "field" . $i, "a.sent_date", "datetime", $p_o, $p_v); break;
						case "from_user_id": $where_sql2 .= OMTemplateWCM::createWhereFromFilter($sql_param, "field" . $i, "a.from_user_id", "user", $p_o, $p_v); break;
						case "to_user_id": $where_sql2 .= OMTemplateWCM::createWhereFromFilter($sql_param, "field" . $i, "a.to_user_id", "user", $p_o, $p_v); break;
						case "subject": $where_sql2 .= OMTemplateWCM::createWhereFromFilter($sql_param, "field" . $i, "a.subject", "text", $p_o, $p_v); break;
						case "body": $where_sql2 .= OMTemplateWCM::createWhereFromFilter($sql_param, "field" . $i, "a.body", "text", $p_o, $p_v); break;
					}
				}
				$where_sql2 = " and (" . $where_sql2 . ")";
			}
		}
		
		$orderby_sql = "";

        $extra_join_sql = "";
		$default_sort_field = "";
		$default_sort_mode = "";
		$default_sort_field = "sent_date";
		$default_sort_mode = "desc"; 

		if(!isset($setting["now_sort_f"]) || $setting["now_sort_f"] == null) {
			$orderby_sql = " order by " . $default_sort_field . " " . $default_sort_mode;
			$tb_data->setColumnSortMode($default_sort_field, $default_sort_mode);
			$new_setting["now_sort_f"] = $default_sort_field;
			$new_setting["now_sort_m"] = $default_sort_mode;
		} else {
			$now_sort_field = $setting["now_sort_f"];
			$now_sort_mode = $setting["now_sort_m"];
	
			if($default_sort_field != $now_sort_field) {
				$orderby_sql = " order by " . $now_sort_field . " " . $now_sort_mode . ", " . $default_sort_field . " " . $default_sort_mode;
                
				if ($now_sort_field == "from_user_id") {
					$orderby_sql = " order by from_user_label " . $now_sort_mode . ", " . $default_sort_field . " " . $default_sort_mode;
				} else if (now_sort_field == "to_user_id") {
					$orderby_sql = " order by to_user_label " . $now_sort_mode . ", " . $default_sort_field . " " . $default_sort_mode;
				}
				
			} else {
				$orderby_sql = " order by " . $now_sort_field . " " . $now_sort_mode;
			}
			$tb_data->setColumnSortMode($now_sort_field, $now_sort_mode);
			$new_setting["now_sort_f"] = $now_sort_field;
			$new_setting["now_sort_m"] = $now_sort_mode;
		}
	
		$current_page = 1;
		if($setting["now_page"] != null) {
			$current_page = $setting["now_page"];		
			if($current_page < 1){ $current_page = 1; }
		}
		$new_setting["now_page"] = $current_page;

		$where_folder = "(1=1)";
		if($folder == "i") { $where_folder = "(a.folder = 'i')"; }
		else if($folder == "s") { $where_folder = "(a.folder = 's')"; }
		else if($folder == "t") { $where_folder = "((a.folder = 'ti') or (a.folder = 'ts'))"; }

		$sql;
		$sql = "select a.msg_id, a.owner_user_id, a.from_user_id, a.to_user_id, a.folder, a.subject, a.request_type, a.is_read, a.sent_date, a.obj_status, concat(b.firstname , ' ' , b.lastname , ' (' , b.username , ')') as from_user_label, concat(c.firstname , ' ' , c.lastname  , ' (' , c.username , ')') as to_user_label from wcm_message as a left join wcm_user as b on b.user_id = a.from_user_id left join wcm_user as c on c.user_id = a.to_user_id where " . $where_folder . " and (a.owner_user_id = @owner_user_id) and (a.obj_status = @obj_status) " . $where_sql . $where_sql2 . $orderby_sql;
		$sql_param["@owner_user_id"] = $SESSION->UserId;
		$sql_param["@obj_status"] = "active";

		$numrows = 0;
		$num_allrows = 0;
		$start_index = 0;
		$dt = null;
		$start_index = ($current_page - 1)*$item_per_page;
	
		$numrows = $DB->query($dt, $sql, $sql_param, $start_index, $item_per_page);
		$num_allrows = $DB->RowCount;
	
		if($start_index >= $num_allrows) {
			$current_page = ceil($num_allrows/$item_per_page);
			$new_setting["now_page"] = $current_page;
			$start_index = ($current_page - 1) * $item_per_page;
			$numrows = $DB->query($dt, $sql, $sql_param, $start_index, $item_per_page);	
		}
		$sqls = $DB->LastSQLQueryString;
		for($i = 0; $i < $numrows; $i++) {
			$read_str = "";
			$tag_b1 = "";
			$tag_b2 = "";
			if(htmlspecialchars($DB->getString($dt,$i,"is_read")) == "F") { 
				$read_str = "&#9679;";
				$tag_b1 = "<strong>";
				$tag_b2 = "</strong>";
			}

			$tb_data->addRow(	"from_user_id", $tag_b1 . htmlspecialchars($DB->getString($dt,$i,"from_user_label")) . $tag_b2,
							"to_user_id", $tag_b1 . htmlspecialchars($DB->getString($dt,$i,"to_user_label")) . $tag_b2,
							"subject", $tag_b1 . htmlspecialchars($DB->getString($dt,$i,"subject")) . $tag_b2,
							"is_read", $read_str,
							"sent_date", $tag_b1 . htmlspecialchars($dtconv->toString($DB->getDateTime($dt,$i,"sent_date"),WCMSetting::$DATETIME_FORMAT_IN_UI)) . $tag_b2,
							"obj_id", $DB->getString($dt,$i,"msg_id"));
		}
	
		
		$SESSION->CookieDb->setCookies($module_name, $new_setting);
		$additional_param = array();
		$additional_param["Page"]["current"] = $current_page;
		$additional_param["Page"]["item_per_page"] = $item_per_page;
		$additional_param["Page"]["total_item"] = $num_allrows;
		$additional_param["Page"]["nocheckbox"] =  false;
		$additional_param["Page"]["customAddFunction"] = "javascript:composeMessage()";		

		$filter_param = array();
		if($SESSION->CookieDb->getCookie($module_name,"now_plist") != null){
			$plist = $SESSION->CookieDb->getCookie($module_name,"now_plist");
			if($plist != ""){
				$nowfilter = explode(' ', $plist);
				for($i=0;$i < count($nowfilter); $i++){
					$additional_param["Filter"][$i] = array("f"=>$SESSION->CookieDb->getCookie($module_name,"now_p" . $nowfilter[$i] . "_f"), "o"=> $SESSION->CookieDb->getCookie($module_name,"now_p" . $nowfilter[$i] . "_o"),"v"=>$SESSION->CookieDb->getCookie($module_name,"now_p" . $nowfilter[$i] . "_v"),"l"=>$SESSION->CookieDb->getCookie($module_name,"now_p" . $nowfilter[$i] . "_l"));
				}
			}
		}

		if($SESSION->CookieDb->getCookie($module_name,"now_mode") != null){ $additional_param["FilterMain"]["mode"] = $SESSION->CookieDb->getCookie($module_name,"now_mode"); }
		if($SESSION->CookieDb->getCookie($module_name,"now_keyword") != null){ $additional_param["FilterMain"]["keyword"] = $SESSION->CookieDb->getCookie($module_name,"now_keyword"); }
		if($SESSION->CookieDb->getCookie($module_name,"now_lastload") != null){ $additional_param["FilterMain"]["lastload"] = $SESSION->CookieDb->getCookie($module_name,"now_lastload"); }

		$debug = OMStringUtils::_TRIMGET("debug");
		if($debug == "SQL") {
			echo "SQL = " . $sql . "<br/><br/>";
			echo "SQLs = " . $sqls . "<br/><br/>";
			echo "Param = " . OMJson::encode($sql_param) . "<br/>";
			echo "numrows = " . $numrows . "<br/>";
			echo "Error = " . $DB->LastErrorMessage . "<br/><br/>";
		}
		echo $tb_data->generateJson($additional_param);
	} else {
		echo OMJson::encode(array("c"=>"ERR","e"=>"SESSION_TIMEOUT"));
	}
    
?>