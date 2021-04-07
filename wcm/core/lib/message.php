<?php
class OMPersonalMessage {
    static $MESSAGE_STATUS_ACTIVE = "active";
	
    static function sendMessage($DB, $data, &$result) {
        $result = array();
        $result2 = array();
        $sql_param = array();
        $nowDate = OMDateTime::Now();
        $rs = 0;
		$sql_param["owner_user_id"] = $data["to_user_id"];
		$sql_param["from_user_id"] = $data["from_user_id"];
		$sql_param["to_user_id"] = $data["to_user_id"];
		$sql_param["folder"] = "i";
		$sql_param["subject"] = $data["subject"];
		$sql_param["body"] = $data["body"];
		$sql_param["request_type"] = ($data["request_type"] != null) ? $data["request_type"] : "";
		$sql_param["module_name"] = ($data["module_name"] != null) ? $data["module_name"] : "messages";
		$sql_param["additional_message"] = (isset($data["additional_message"]) && $data["additional_message"] != null) ? $data["additional_message"] : "";
		$sql_param["item_id"] = ($data["item_id"] != null) ? $data["item_id"] : 0;
		$sql_param["revision"] = ($data["revision"] != null) ? $data["revision"] : 0;
		$sql_param["lang"] = ($data["lang"] != null) ? $data["lang"] : "";
		$sql_param["ipaddress"] = OMNetwork::GetRemoteIP();
		$sql_param["is_read"] = "F";
		$sql_param["sent_date"] = $nowDate;
		$sql_param["obj_status"] = self::$MESSAGE_STATUS_ACTIVE;
     
        if ($data["to_user_id"] <= 1) {
            $result["detail"] = "ERROR";
            $result["sql"] = "";
            $result["param"] = "";
            $result["error"] = "Unexpected userid";
            return false;
        }
        $rs = $DB->executeInsert("wcm_message", $sql_param);
        if ($rs > 0) {
            if ($data["from_user_id"] > 1) {
                $sql_param["owner_user_id"] = $data["from_user_id"];
                $sql_param["folder"] = "s";
                $sql_param["is_read"] = "T";
                $rs = $DB->executeInsert("wcm_message", $sql_param);
            }		
            self::sendNotifyMail($DB, $data, $result2);
        }
        if ($rs <= 0) {
            $result["detail"] = "Unable to submit message.";
            $result["sql"] = $DB->LastSQLQueryString;
            $result["param"] = $sql_param;
            $result["error"] = $DB->LastErrorMessage;
            return false;
        }
        $result["detail"] = "Success to submit message.";
        $result["sql"] = "";
        $result["param"] = "";
        $result["error"] = "";
        return true;
    }

    static function sendNotifyMail($DB, $data, &$result) {
        $dt = null;
        $r = 0;
        $sql_string = "";
        $sql_param = array();
        $maildata = array();
        $resultmail;
        $result = array();
        $sql_param = array();
        $sql_string = "select * from wcm_user where user_id = @user_id";
        $sql_param["@user_id"] = $data["to_user_id"];
        $r = $DB->query($dt, $sql_string, $sql_param);
        if ($r > 0) {
            $email = $DB->getString($dt, 0, "email");
            $username = $DB->getString($dt, 0, "username");
            if ($email != "") {
                $mailer = new OMMailer();
                $maildata["from_email"] = WCMSetting::$SYSTEM_EMAIL_ADDRESS;
                $maildata["from_name"] = WCMSetting::$SYSTEM_EMAIL_NAME;
                $maildata["to_email"] = $email;
                $maildata["to_name"] = $username;
                if (isset($data["mail_subject"]) && $data["mail_subject"] != "") {
                    $maildata["subject"] = $data["mail_subject"];
                } else {
                    $maildata["subject"] = $data["subject"];
                }
                if (isset($data["mail_body"]) && $data["mail_body"] != "") {
                    $maildata["body"] = $data["mail_body"];
                } else {
                    $maildata["body"] = $data["body"];
                }
                if (isset($data["mail_body_html"]) && $data["mail_body_html"] != "") {                    
                    $maildata["body_html"] = $data["mail_body_html"];
                } else {
                    $maildata["body_html"] = "";
                }
                if ($mailer->sendMail($maildata, $resultmail)) {                 
                    $result["error"] = "";
                } else {
                    $result["error"] = mailer.LastErrorMessage;
                }
            }
        } else {
            return false;
        }
        return true;
    }

}
?>
