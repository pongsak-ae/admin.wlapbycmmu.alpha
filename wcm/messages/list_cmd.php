<?php
	require_once('../core/lib/all.php');
	if($SESSION->checkSession("only") ) { 
		
		$module_name = "wcm_message"; 
		
		$command = OMStringUtils::_TRIMPOST("c");
		$listname = OMStringUtils::_TRIMPOST("n");
	
		if($command == "savefilter") {
			if($listname == "") { 
				echo OMJson::encode(array("c"=>"ERR","e"=>"","m"=>""));
				exit(); 
			}
	
			$new_saved_list = "";
			$new_saved_id = "";
			$i=0;
	
			if($SESSION->CookieDb->getCookie($module_name, "saved_list") != null && $SESSION->CookieDb->getCookie($module_name, "saved_list") != "") {
				$list_index = explode(',', $SESSION->CookieDb->getCookie($module_name, "saved_list"));
				for($i = 0; $i < count($list_index); $i++){
					if($SESSION->CookieDb->getCookie($module_name,"saved_listname_" . $list_index[$i]) == $listname){
						$new_saved_id = $list_index[$i];
					}
				}
				if($new_saved_id != ""){ 
					$SESSION->CookieDb->deleteByPrefix($module_name, "saved_" . $new_saved_id . "_");
					$new_saved_list = $SESSION->CookieDb($module_name,"saved_list");
				} else {
					if(count($list_index) > 0) {
						$new_saved_id = $list_index[count($list_index) - 1] + 1;
						$new_saved_list = $SESSION->CookieDb->getCookie($module_name,"saved_list") . "," . $new_saved_id;
					}
				}
			} else {
				$new_saved_id = "1";
				$new_saved_list = "1";
			}
			$SESSION->CookieDb->setCookies($module_name, array("saved_list"=>$new_saved_list, ("saved_listname_" . $new_saved_id) => $listname, "now_lastload"=>$listname));
	
			if(OMStringUtils::_TRIMPOST("l") != null){
				$filter_list = explode(',', OMStringUtils::_TRIMPOST("l"));
				$filter = array();
	
				$j = 0;
				$new_plist = "";
				for($i = 0; $i < count($filter_list); $i++){
					if($filter_list[$i] != ""){
						$filter["saved_" . $new_saved_id . "_p" . $j . "_f"] = OMStringUtils::_TRIMPOST("p", $filter_list[$i] . "_f");
						$filter["saved_" . $new_saved_id . "_p" . $j . "_o"] = OMStringUtils::_TRIMPOST("p", $filter_list[$i] . "_o");
						$filter["saved_" . $new_saved_id . "_p" . $j . "_v"] = OMStringUtils::_TRIMPOST("p", $filter_list[$i] . "_v");
						$filter["saved_" . $new_saved_id . "_p" . $j . "_l"] = OMStringUtils::_TRIMPOST("p", $filter_list[$i] . "_l");
						if($new_plist != "") { $new_plist += ","; }
						$new_plist += $j;
						$j++;
					}
				}
				$filter["saved_" . $new_saved_id . "_plist"] = $new_plist;
				$filter["saved_" . $new_saved_id . "_mode"] = OMStringUtils::_TRIMPOST("m");
				$filter["saved_" . $new_saved_id . "_keyword"] = OMStringUtils::_TRIMPOST("k");
		
				$SESSION->CookieDb->setCookies($module_name, $filter);
			}			
			echo OMJson::encode(array("c"=>"OK","i"=>$new_saved_id));			
			
	
		} else if($command == "checksavefilter") {
			$i = 0;
			$found_dup = false;
			if($SESSION->CookieDb->getCookie($module_name, "saved_list") != null) {
				$list_index = explode(',', $SESSION->CookieDb->getCookie($module_name, "saved_list"));		
				for($i = 0;$i < count($list_index); $i++){
					if($SESSION->CookieDb->getCookie($module_name, "saved_listname_" . $list_index[$i]) == $listname){ $found_dup = true;}
				}
			}
	
			if($found_dup){
				echo OMJson::encode(array("c"=>"WAR","w"=>"DUP","m"=>""));
			} else {
				echo "{\"c\":\"OK\"}";
			}
		} else if($command == "nowfilter") {
            $old_filter = $SESSION->CookieDb->getCookieByPrefix($module_name, "now_");
			$SESSION->CookieDb->deleteByPrefix($module_name, "now_");
			$filter = array();
			$filter["now_mode"] = OMStringUtils::_TRIMPOST("m");
			$filter["now_keyword"] = OMStringUtils::_TRIMPOST("k");
			if(OMStringUtils::_TRIMPOST("cf") == "Y") { 
				$SESSION->CookieDb->delete($module_name, "now_lastload");
			} else {
				if(isset($old_filter["now_lastload"]) && $old_filter["now_lastload"] != null) { $filter["now_lastload"] = $old_filter["now_lastload"]; }
			}

			if(isset($_POST["l"])){
				$filter_list = explode(',', OMStringUtils::_TRIMPOST("l"));
				$i =0;
				$j = 0;
				$new_plist = "";
				for($i = 0; $i < count($filter_list); $i++){
					if($filter_list[$i] != ""){
						$filter["now_p" . $j . "_f"] = OMStringUtils::_TRIMPOST("p", $filter_list[$i] . "_f");
						$filter["now_p" . $j . "_o"] = OMStringUtils::_TRIMPOST("p", $filter_list[$i] . "_o");
						$filter["now_p" . $j . "_v"] = OMStringUtils::_TRIMPOST("p", $filter_list[$i] . "_v");
						$filter["now_p" . $j . "_l"] = OMStringUtils::_TRIMPOST("p", $filter_list[$i] . "_l");
						if($new_plist != "") { $new_plist .= ","; }
						$new_plist .= $j;
						$j++;
					}
				}
				$filter["now_plist"] = $new_plist;
				$SESSION->CookieDb->setCookies($module_name, $filter);
			}
			echo "{\"c\":\"OK\"}";
		} else if($command == "removefilter") {
			$newcookie = array();
			$filter_id = OMStringUtils::_TRIMPOST("i");
			
			$lastload = $SESSION->CookieDb->getCookie($module_name, "now_lastload");
			$target_name = $SESSION->CookieDb->getCookie($module_name, "saved_listname_" . $filter_id);
			$savedlist = $SESSION->CookieDb->getCookie($module_name, "saved_list");
			$news_savedlist = "";
			$savedlist_arr = explode(',', $savedlist);
			foreach($savedlist_arr as $id){
				if($id != $filter_id) {
					if($news_savedlist != "") { $news_savedlist .= ","; }
					$news_savedlist .= $id;
				}
			}
			$SESSION->CookieDb->deleteByPrefix($module_name, "saved_" . $filter_id . "_");
			$SESSION->CookieDb->delete($module_name, "saved_listname_" . $filter_id);
			$newcookie["saved_list"] = $news_savedlist;
			if($target_name == $lastload){ $SESSION->CookieDb->delete($module_name, "now_lastload"); }
			$SESSION->CookieDb->setCookies($module_name, $newcookie);
			echo "{\"c\":\"OK\"}";

		} else if($command == "loadsavedlist") {
			echo OMTemplateWCM::printFilterList($module_name);

		} else if ($command == "loadfilter") {
			$SESSION->CookieDb->deleteByPrefix($module_name, "now_");			
			$filter = array();
			$filter_id = OMStringUtils::_TRIMPOST("i");
	
			$saved_filter = $SESSION->CookieDb->getCookieByPrefix($module_name, "saved_" . $filter_id . "_");			
			$offset = strlen("saved_" . $filter_id . "_");
	
			$saved_filter_keys = array_keys($saved_filter);			
			for($i = 0; $i < count($saved_filter); $i++) {				
				$filter["now_" . substr($saved_filter_keys[$i], $offset)] = $saved_filter[$saved_filter_keys[$i]];
			}
			$filter["now_page"] = "1";
			$filter["now_lastload"] = $SESSION->CookieDb->getCookie($module_name, "saved_listname_" . $filter_id);
			$SESSION->CookieDb->setCookies($module_name, $filter);
			echo "{\"c\":\"OK\"}";
		} else if($command == "gotopage") {
			$page = OMStringUtils::_TRIMPOST("p");
			$SESSION->CookieDb->setCookie($module_name, "now_page", $page);
			echo "{\"c\":\"OK\"}";
		} else if($command == "sortby") {
			$field = OMStringUtils::_TRIMPOST("f");
			$mode = OMStringUtils::_TRIMPOST("m");
			$SESSION->CookieDb->setCookies($module_name, array("now_sort_f"=>$field, "now_sort_m"=>$mode, "now_page"=>"1"));
			echo "{\"c\":\"OK\"}";
		} else if($command == "getComposeForm") {
			$subject = OMStringUtils::_TRIMPOST("sj");
			$to_user_id = 1;
			$to_user_id = OMStringUtils::_TRIMPOST("to");
			
			$to_list = array();
			$to_select = "";
			$sql_params = array();
			$dt = null;
			$sql = "select * from wcm_user where obj_status = 'active'";
			$rs = 0;
			$rs = $DB->query($dt, $sql, $sql_params);
			$to_select .= "<select ref=\"" . $to_user_id . "\" id=\"composeToUserId\"><option value=\"\">--- Please select ---</option>";
			$from_txt = "Unknown";
			for($i = 0; $i < $rs; $i++){
				$checked_txt = ""; 
				if($to_user_id == $DB->getLong($dt,$i,"user_id")){
					$checked_txt = "selected=\"selected\"";
				}
				$to_select .= "<option value=\"" . $DB->getLong($dt,$i,"user_id") . "\" " . $checked_txt . " >" . htmlspecialchars($DB->getString($dt,$i,"firstname") . " " . $DB->getString($dt,$i,"lastname") . " (" . $DB->getString($dt,$i,"username") . ")") . "</option>";
				
				if($DB->getString($dt,$i,"username") == $SESSION->Username) { 
					$from_txt = htmlspecialchars($DB->getString($dt,$i,"firstname") . " " . $DB->getString($dt,$i,"lastname") . " (" . $DB->getString($dt,$i,"username") . ")"); 
				}
			}
			$to_select .= "</select>";
			
			$form_data = array();
			$form_data[0]["label"] = "From";
			$form_data[0]["data"] = $from_txt;
			$form_data[1]["label"] = "To";
			$form_data[1]["data"] = $to_select;
			$form_data[2]["label"] = "Subject";
			$form_data[2]["data"] = "<input type=\"text\" id=\"composeSubject\" maxwidth=\"100\" style=\"width:560px;\" value=\"" . $subject . "\" />";
			
			echo OMJson::encode(array("c"=>"OK","compose_form"=>OMWCMUtil::printMessage($form_data, "<textarea id=\"composeMessage\" rows=\"10\" cols=\"30\" style=\"width:626px; height:240px; border:0;\" placeholder=\"Type your message here...\"></textarea>")));
		} else if($command == "sendMessage") {
			$rs=null;
			$subject = OMStringUtils::_TRIMPOST("sj");
			$message = OMStringUtils::_TRIMPOST("msg");
			$msg_data = array();
			$sql_params = array();
			$dt = null;
			$sql = "select * from wcm_user where user_id = @user_id";
			$sql_params["@user_id"] = $SESSION->UserId;
			$to_user_id;
			if($DB->query($dt, $sql, $sql_params) > 0 && isset($_POST["to"])) {
				$to_user_id = OMStringUtils::_TRIMPOST("to");
				$mail_body_prefix = "Hi,\r\n\r\n" . $DB->getString($dt,0,"firstname") . " " . $DB->getString($dt,0,"lastname") . " (" . $DB->getString($dt,0,"username") . ") has sent you a message:\r\n\r\n";
				$mail_body_suffix = "\r\n\r\nPlease login to " . WCMSetting::$EMAIL_NOTIFY_BASE_URL . "/messages/list.php to view the full message detail.\r\n\r\nNotice: This email was automatically generated from the " . WCMSetting::$WINDOWS_TITLE;				
				$msg_data["from_user_id"] = OMSession::Current()->UserId;
				$msg_data["to_user_id"] = $to_user_id;
				$msg_data["subject"] = $subject;
				$msg_data["mail_subject"] = WCMSetting::$PREFIX_NOTIFY_EMAIL_SUBJECT . " " . $subject;
				$msg_data["body"] = $message;
				$msg_data["mail_body"] = $mail_body_prefix . $message . $mail_body_suffix;
				$body = $mail_body_prefix . $message . $mail_body_suffix;
				$body = str_replace("\r\n", "<br/>", $body);
				$msg_data["mail_body_html"] = "<html><body>" . $body . "</body></html>";
				$msg_data["request_type"] = "";
				$msg_data["module_name"] = "messages";
				$msg_data["item_id"] = 0;
				$msg_data["revision"] = 0;
				$msg_data["lang"] = "";
				OMPersonalMessage::sendMessage($DB, $msg_data, $rs);	
				echo OMJson::encode(array("c"=>"OK","new_message"=>OMTemplateWCM::checkNewMessage()));
			} else {
				echo OMJson::encode(array("c"=>"ERR","e"=>"SESSION_TIMEOUT"));
			}
		} else {		        
				echo OMJson::encode(array("c"=>"ERR","e"=>"INVALID_COMMAND","m"=>"Invalid command."));
		}
	} else {
		echo OMJson::encode(array("c"=>"ERR","e"=>"SESSION_TIMEOUT","m"=>""));
	}
?>