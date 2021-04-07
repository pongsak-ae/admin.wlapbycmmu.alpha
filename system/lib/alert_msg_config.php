<?php


$ALERT_MSG = array();

/*
|-----------------------------------------------------------------------------
|  GLOBAL MESSAGE 
|-----------------------------------------------------------------------------
*/
	$ALERT_MSG["GLOBAL"]["TH"]["ALERT_TITLE"]= "แจ้งเตือน";
	$ALERT_MSG["GLOBAL"]["EN"]["ALERT_TITLE"] = "Alert title";

	$ALERT_MSG["GLOBAL"]["TH"]["REQUIRED_FIELD"]= "กรุณากรอกข้อมูลให้ครบถ้วน";
	$ALERT_MSG["GLOBAL"]["EN"]["REQUIRED_FIELD"] = "Please input required field";

	$ALERT_MSG["GLOBAL"]["TH"]["TRY_AGAIN"]= "กรุณากรอกลองใหม่อีกครั้ง";
	$ALERT_MSG["GLOBAL"]["EN"]["TRY_AGAIN"] = "Please try again";

	$ALERT_MSG["GLOBAL"]["TH"]["MISSING_PARAMETER"]= "กรุณาใส่ข้อมูลให้ครบ";
	$ALERT_MSG["GLOBAL"]["EN"]["MISSING_PARAMETER"] = "required field missing";

	$ALERT_MSG["GLOBAL"]["TH"]["CLOSE_BTN"]= "ปิด";
	$ALERT_MSG["GLOBAL"]["EN"]["CLOSE_BTN"] = "close";

/*
|-----------------------------------------------------------------------------
|  LOGIN MESSAGE
|-----------------------------------------------------------------------------
*/
	$ALERT_MSG["LOGIN"]["TH"]["ERR_CODE_3"]= "ไม่มีผู้ใช้งานนี้ในระบบ";
	$ALERT_MSG["LOGIN"]["EN"]["ERR_CODE_3"] = "Login does not exist under the program";

	$ALERT_MSG["LOGIN"]["TH"]["ERR_CODE_4"]= "รหัสผ่านไม่ถูกต้อง";
	$ALERT_MSG["LOGIN"]["EN"]["ERR_CODE_4"] = "Invalid password supplied for the login";


/*
|-----------------------------------------------------------------------------
|  CUSTOM_FIELD
|-----------------------------------------------------------------------------
*/
	$ALERT_MSG["CUSTOM_FIELD"]["TH"]["ERR_CODE_1"]= "กรุณากรอกข้อมูลให้ครบ";
	$ALERT_MSG["CUSTOM_FIELD"]["EN"]["ERR_CODE_1"] = "Missing some parameter";

	$ALERT_MSG["CUSTOM_FIELD"]["TH"]["ERR_CODE_2"]= "กรุณากรอก ตัวเลือกให้ครบ";
	$ALERT_MSG["CUSTOM_FIELD"]["EN"]["ERR_CODE_2"] = "Missing choice";

	$ALERT_MSG["CUSTOM_FIELD"]["TH"]["ERR_CODE_3"]= "กรุณาใส่ชนิดของฟิลด์ให้ถูกต้อง";
	$ALERT_MSG["CUSTOM_FIELD"]["EN"]["ERR_CODE_3"] = "Incorrect custom field type";

	$ALERT_MSG["CUSTOM_FIELD"]["TH"]["ERR_CODE_4"]= "ชื่อฟิลซ้ำ";
	$ALERT_MSG["CUSTOM_FIELD"]["EN"]["ERR_CODE_4"] = "Duplicate field data [ custom field name / custom field key]";

	$ALERT_MSG["CUSTOM_FIELD"]["TH"]["ERR_CODE_5"]= "ไม่พบ ข้อมูล";
	$ALERT_MSG["CUSTOM_FIELD"]["EN"]["ERR_CODE_5"] = "No custom field data";

	$ALERT_MSG["CUSTOM_FIELD"]["TH"]["ERR_CODE_6"]= "ไม่พบ ไอดี";
	$ALERT_MSG["CUSTOM_FIELD"]["EN"]["ERR_CODE_6"] = "Missing custom field id";

	$ALERT_MSG["CUSTOM_FIELD"]["TH"]["ERR_CODE_7"]= "กรุณาใส่คีย์ให้ถูกต้อง";
	$ALERT_MSG["CUSTOM_FIELD"]["EN"]["ERR_CODE_7"] = "Incorrect custom field key";

/*
|-----------------------------------------------------------------------------
|  OVERVIEW
|-----------------------------------------------------------------------------
*/
	$ALERT_MSG["OVERVIEW"]["TH"]["ERR_CODE_1"]= "กรุณากรอกข้อมูลให้ครบ";
	$ALERT_MSG["OVERVIEW"]["EN"]["ERR_CODE_1"] = "Missing some parameter";

	$ALERT_MSG["OVERVIEW"]["TH"]["ERR_CODE_2"]= "เงื่อนไขไม่ถูกต้อง";
	$ALERT_MSG["OVERVIEW"]["EN"]["ERR_CODE_2"] = "Match rule not found";

	$ALERT_MSG["OVERVIEW"]["TH"]["ERR_CODE_3"]= "ชื่อซ้ำ";
	$ALERT_MSG["OVERVIEW"]["EN"]["ERR_CODE_3"] = "Duplicate smart list name";

	$ALERT_MSG["OVERVIEW"]["TH"]["ERR_CODE_4"]= "กรุณากรอกข้อมูลวันสิ้นสุดแคมเปญให้ถูกต้อง";
	$ALERT_MSG["OVERVIEW"]["EN"]["ERR_CODE_4"] = "Campaign end date can not be less than start date";


/*
|-----------------------------------------------------------------------------
|  CAMPAIGN
|-----------------------------------------------------------------------------
*/
	$ALERT_MSG["CAMPAIGN"]["TH"]["ERR_CODE_1"]= "กรุณากรอกข้อมูลให้ครบ";
	$ALERT_MSG["CAMPAIGN"]["EN"]["ERR_CODE_1"] = "Missing some parameter";

	$ALERT_MSG["CAMPAIGN"]["TH"]["ERR_CODE_2"]= "กรุณากรอกข้อมูลวันสิ้นสุดแคมเปญให้ถูกต้อง";
	$ALERT_MSG["CAMPAIGN"]["EN"]["ERR_CODE_2"] = "Campaign end date can not be less than start date";

	$ALERT_MSG["CAMPAIGN"]["TH"]["ERR_CODE_3"]= "รูปแบบวันที่ผิด";
	$ALERT_MSG["CAMPAIGN"]["EN"]["ERR_CODE_3"] = "Date format is wrong";

	$ALERT_MSG["CAMPAIGN"]["TH"]["ERR_CODE_4"]= "ชื่อแคมเปญซ้ำ";
	$ALERT_MSG["CAMPAIGN"]["EN"]["ERR_CODE_4"] = "Duplicate campaign name";

	$ALERT_MSG["CAMPAIGN"]["TH"]["ERR_CODE_5"]= "ไม่พบข้อมูล";
	$ALERT_MSG["CAMPAIGN"]["EN"]["ERR_CODE_5"] = "No data";

/*
|-----------------------------------------------------------------------------
|  CAMPAIGN DETAIL
|-----------------------------------------------------------------------------
*/
	$ALERT_MSG["CAMPAIGN_DETAIL"]["TH"]["ERR_CODE_1"]= "กรุณากรอกข้อมูลให้ครบ";
	$ALERT_MSG["CAMPAIGN_DETAIL"]["EN"]["ERR_CODE_1"] = "Missing some parameter";

	$ALERT_MSG["CAMPAIGN_DETAIL"]["TH"]["ERR_CODE_2"]= "ไม่พบเหตการณ์ชนิดนี้";
	$ALERT_MSG["CAMPAIGN_DETAIL"]["EN"]["ERR_CODE_2"] = "Can not find this event type";

	$ALERT_MSG["CAMPAIGN_DETAIL"]["TH"]["ERR_CODE_3"]= "ชื่อเหตุการณ์ซ้ำ";
	$ALERT_MSG["CAMPAIGN_DETAIL"]["EN"]["ERR_CODE_3"] = "Duplicate event name";

	$ALERT_MSG["CAMPAIGN_DETAIL"]["TH"]["ERR_CODE_4"]= "ไม่สามารถเชื่อมต่อฐานข้อมูลได้";
	$ALERT_MSG["CAMPAIGN_DETAIL"]["EN"]["ERR_CODE_4"] = "DB Error";

	$ALERT_MSG["CAMPAIGN_DETAIL"]["TH"]["ERR_CODE_5"]= "ไม่พบข้อมูล";
	$ALERT_MSG["CAMPAIGN_DETAIL"]["EN"]["ERR_CODE_5"] = "Data not found";

	$ALERT_MSG["CAMPAIGN_DETAIL"]["TH"]["ERR_CODE_6"]= "ไม้สามารถเปลี่ยนคีย์ได้ กรุณาลองใหม่อีกครั้ง";
	$ALERT_MSG["CAMPAIGN_DETAIL"]["EN"]["ERR_CODE_6"] = "Can not change key at this time.Please try again later";



//var_dump(json_encode($ALERT_MSG));
define("ALERT_MSG", json_encode($ALERT_MSG));


?>