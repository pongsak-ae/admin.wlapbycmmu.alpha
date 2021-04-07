<?php
	require_once('../core/lib/all.php');

	if($SESSION->checkSession("only") ) {
		$command = OMStringUtils::POST("c");
		$msg_id = OMStringUtils::POST("i",0);
		$read_flag = OMStringUtils::POST("f",'');
		$post_msg_list = OMStringUtils::POST("l",'');

		if ($command == "detail" && $msg_id != "") {
			$dtconv = new OMDateTimeConverter();
			$dt=null;
			$r;
			$msg = "";
			$sql_params = array();
			$sql = "select a.*, concat(b.firstname , ' ' , b.lastname , ' (' , b.username , ')') as from_user_label, concat(c.firstname , ' ' , c.lastname , ' (' , c.username , ')') as to_user_label from wcm_message as a left join wcm_user as b on a.from_user_id = b.user_id left join wcm_user as c on a.to_user_id = c.user_id where (a.msg_id = @msg_id) and (a.owner_user_id = @owner_user_id) ";

			$sql_params["@msg_id"] = $msg_id;
			$sql_params["@owner_user_id"] = $SESSION->UserId;

			$r = $DB->query($dt, $sql, $sql_params);
			if ($r>0 && $dt != null && count($dt)>0) {
				$result=null;
				OMWCMUtil::setMessageAsRead(array("0", $msg_id), $result);
				$form_data = array();
				$form_data[0]["label"] = "From";
				$form_data[0]["data"] = htmlspecialchars($DB->getString($dt,0,"from_user_label"));
				$form_data[1]["label"] = "To";
				$form_data[1]["data"] = htmlspecialchars($DB->getString($dt,0,"to_user_label"));
				$form_data[2]["label"] = "Date";
				$form_data[2]["data"] = htmlspecialchars($dtconv->toString($DB->getDateTime($dt,0,"sent_date"),WCMSetting::$DATETIME_FORMAT_IN_UI));
				$form_data[3]["label"] = "Subject";
				$form_data[3]["data"] = "<strong>" . htmlspecialchars($DB->getString($dt,0,"subject")) . "</strong>";

				echo OMJson::encode(array(
													"status"=>"OK",
													"body"=>OMWCMUtil::printMessage($form_data, htmlspecialchars($DB->getString($dt,0,"body"))),
													"subject"=>htmlspecialchars($DB->getString($dt,0,"subject")),
													"mod"=>$DB->getString($dt, 0, "module_name"),
													"rt"=>$DB->getString($dt, 0, "request_type"),
													"rid"=>$DB->getString($dt, 0, "item_id"),
													"rrev"=>$DB->getString($dt, 0, "revision"),
													"rlang"=>$DB->getString($dt, 0, "lang"),
													"new_message"=>OMTemplateWCM::checkNewMessage(),
													"msg_id"=>$msg_id,
													"from_user_id"=>$DB->getString($dt,0,"from_user_id")
													));

			} else {
				echo OMJson::encode(array("status"=>"ERROR","msg"=>"Unable to retrieve message detail. Please contact your system administrator to solve the problem."));
			}
		} else if ($command == "markas" &&  $post_msg_list!= "" && $read_flag != "") {
			$msg_id_list = array();


			$input_list = explode(',', $post_msg_list );
			$i = 0;
			foreach($input_list as $msg_id) {
				$msg_id_list[$i] = $msg_id;
				$i++;
			}
			$result = null;
			if($read_flag == "read" ) {
				OMWCMUtil::setMessageAsRead($msg_id_list, $result);
			} else {
				OMWCMUtil::setMessageAsUnread($msg_id_list, $result);
			}

			echo OMJson::encode(array("status"=>"OK","new_message"=>OMTemplateWCM::checkNewMessage()));

		} else if ($command == "movetodeleted" && $post_msg_list != "") {
			$msg_id_list = array();

			$input_list = explode(',', $post_msg_list);
			$i = 0;
			foreach($input_list as $msg_id) {
				$msg_id_list[$i] = $msg_id;
				$i++;
			}
			$result;
			OMWCMUtil::setMessageToDeleted($msg_id_list, $result);
			echo OMJson::encode(array("status"=>"OK","new_message"=>OMTemplateWCM::checkNewMessage()));
		} else if ($command == "movetotrash" && $post_msg_list != "") {
			$msg_id_list = array();

			$input_list = explode(',', $post_msg_list);
			$i = 0;
			foreach($input_list as $msg_id) {
				$msg_id_list[$i] = $msg_id;
				$i++;
			}
			$result=null;
			OMWCMUtil::setMessageToTrash($msg_id_list, $result);
			echo OMJson::encode(array("status"=>"OK","new_message"=>OMTemplateWCM::checkNewMessage()));

		} else if ($command == "putback" && $post_msg_list != "") {
			$msg_id_list = array();

			$input_list = explode(',', $post_msg_list);
			$i = 0;
			foreach($input_list as $msg_id) {
				$msg_id_list[$i] = $msg_id;
				$i++;
			}
			$result = null;
			OMWCMUtil::setMessagePutBack($msg_id_list, $result);
			echo OMJson::encode(array("status"=>"OK","new_message"=>OMTemplateWCM::checkNewMessage()));

		} else {
			echo OMJson::encode(array("status"=>"ERROR","message"=>"Invalided command. Please contact your system administrator to solve the problem."));
		}
	} else {
		echo OMJson::encode(array("status"=>"ERROR","message"=>"Session timeout. Please refresh your page to solve the problem."));
	}
?>