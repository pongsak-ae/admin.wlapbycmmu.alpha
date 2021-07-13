<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json');
use OMCore\OMDb;
$DB = OMDb::singleton();
//$log = new OMCore\OMLog;

$response = array();
$today = date("Y-m-d H:i:s");
$exp = strtotime('+30 days', strtotime($today));
$expires = date('Y-m-d H:i:s', $exp);

$sql = "SELECT course_id, course_no, course_name, course_detail, course_datetime, course_place, course_price, course_startdate, course_enddate, course_schedule
        FROM course WHERE course_active = '1' limit 1";
$sql_param = array();
$ds = null;
$res = $DB->query($ds, $sql, $sql_param, 0, -1, "ASSOC");
$result = array();
foreach($ds as $v) {
        $result[] = array(
                'course_id' => $v['course_id'],
                'course_no' => $v['course_no'],
                'course_name' => $v['course_name'],
                'course_detail' => base64_decode($v['course_detail']),
                'course_datetime' => $v['course_datetime'],
                'course_place' => $v['course_place'],
                'course_price' => $v['course_price'],
                'course_startdate' => $v['course_startdate'],
                'course_enddate' => $v['course_enddate'],
                'course_schedule' => WEB_META_BASE_URL.'images/table/'.$v['course_schedule']
        );
}
$response['data'] = $result;
//echo json_encode($response, JSON_UNESCAPED_SLASHES);

echo json_encode($result);
?>