<?php
mb_internal_encoding("UTF-8");


// Define WEB_META_BASE_URL
$HTTP_REFERER = isset($_SERVER["HTTP_REFERER"])?$_SERVER["HTTP_REFERER"]:'http://' . @$_SERVER["SERVER_NAME"] . '/';
list($HTTP_PROTOCAL) = explode(':', $HTTP_REFERER);
if(empty($HTTP_PROTOCAL)) $HTTP_PROTOCAL = 'http';

define("WEB_REWRITE_BASE",  "");
define("WEB_META_BASE_URL",  $HTTP_PROTOCAL."://" . @$_SERVER["SERVER_NAME"] .WEB_REWRITE_BASE. "/");

define("BASE_API",  "");
define("WEB_META_BASE_API",  BASE_API."v1/");
define("WEB_META_BASE_API_DOC",  BASE_API."doc/");

define("WEB_APP_CALL_API",  WEB_META_BASE_URL."service/call_api.php");
define("WEB_APP_SERVICE_API",  WEB_META_BASE_URL."service/");

define("WEB_INDEX_PAGE",  "index");

$build_version = "12";
define('BUILD_VERSION',$build_version);
$IS_ADMIN_MODE = false;

define("COOKIE_DOMAIN", "." . @$_SERVER["SERVER_NAME"] );
define("ENCRYPT_INIT_KEY", "13579defabc12345");
define("ENCRYPT_INIT_VECTOR", "de12fa890c79b387");
define("MONGO_HOST", "localhost");
define("MONGO_PORT", "27017");
define("MONGO_DB", "SCB15B-PROSPECT");
define("ENABLE_LANG", true);
define("UID_LIFE_TIME", time() + 10*60*1000);
define("LIMIT_EXPORT_DATA", 3);

// --------- CUSTOM LINE ---------- //
define("LINE_TOKEN","");


define('key_DB','CHM@#*db09');
// define('key_pitkbank','IFF@#*kb09');
// define('key_passid','IFF@#*pis9');
// --------- CUSTOM FIELD TYPE ---------- //
$all_custom_field_type = array();


$all_custom_field_type['single_line'] = "Short text";
$all_custom_field_type['paragraph_text'] = "Paragraph text";
$all_custom_field_type['number'] = "Number";
$all_custom_field_type['dropdown'] = "Drop down";
$all_custom_field_type['checkbox'] = "Checkbox";
$all_custom_field_type['datetime'] = "Date time";

define("ALL_CUSTOM_FIELD_TYPE", json_encode($all_custom_field_type));

$all_event = array();
$all_event['all_event'] = array("website","sms","email");
$all_event['event_mapping'] = array();
$all_event['event_mapping']['website'] = array("website"=>"");
$all_event['event_mapping']['sms'] = array("sms"=>"");
$all_event['event_mapping']['email'] = array("open_email"=>"Open email","click_email"=>"Click link");

define("ALL_EVENT_TYPE", json_encode($all_event));
// -------------------------------------- //


$FIX_CUSTOM_FIELD_CRITERIA = array();
$tmp_custom_field_criteria = array();
$tmp_custom_field_criteria['ref_id'] = "campaign_01";
$tmp_custom_field_criteria['name'] = "campaign name";
$tmp_custom_field_criteria['segment_type'] = "dropdown";
$tmp_custom_field_criteria['segment_value'] = "";
$FIX_CUSTOM_FIELD_CRITERIA['campaign_segment'][] = $tmp_custom_field_criteria;

// $tmp_custom_field_criteria = array();
// $tmp_custom_field_criteria['ref_id'] = "event_01";
// $tmp_custom_field_criteria['name'] = "campaign name";
// $tmp_custom_field_criteria['segment_type'] = "dropdown";
// $tmp_custom_field_criteria['segment_value'] = "";
// $FIX_CUSTOM_FIELD_CRITERIA['event_segment'][] = $tmp_custom_field_criteria;

$tmp_custom_field_criteria = array();
$tmp_custom_field_criteria['ref_id'] = "event_02";
$tmp_custom_field_criteria['name'] = "event name";
$tmp_custom_field_criteria['segment_type'] = "dropdown";
$tmp_custom_field_criteria['segment_value'] = "";
$FIX_CUSTOM_FIELD_CRITERIA['event_segment'][] = $tmp_custom_field_criteria;

$tmp_custom_field_criteria = array();
$tmp_custom_field_criteria['ref_id'] = "event_04";
$tmp_custom_field_criteria['name'] = "event date";
$tmp_custom_field_criteria['segment_type'] = "datetime";
$tmp_custom_field_criteria['segment_value'] = "";
$FIX_CUSTOM_FIELD_CRITERIA['event_segment'][] = $tmp_custom_field_criteria;


$tmp_custom_field_criteria = array();
$tmp_custom_field_criteria['ref_id'] = "score_01";
$tmp_custom_field_criteria['name'] = "score specify";
$tmp_custom_field_criteria['segment_type'] = "number";
$tmp_custom_field_criteria['segment_value'] = "";
$FIX_CUSTOM_FIELD_CRITERIA['score_segment'][] = $tmp_custom_field_criteria;

$tmp_custom_field_criteria = array();
$tmp_custom_field_criteria['ref_id'] = "score_02";
$tmp_custom_field_criteria['name'] = "score summary";
$tmp_custom_field_criteria['segment_type'] = "number";
$tmp_custom_field_criteria['segment_value'] = "";
$FIX_CUSTOM_FIELD_CRITERIA['score_segment'][] = $tmp_custom_field_criteria;

define('FIX_CUSTOM_FIELD_CRITERIA',json_encode($FIX_CUSTOM_FIELD_CRITERIA));

$ALL_FIXED_FIELD = array("campaign_01","event_02","event_04","score_01","score_02");
define('ALL_FIXED_FIELD',json_encode($ALL_FIXED_FIELD));

define('NO_EMAIL_FIELD_TEXT',"UNKNOWN");


?>