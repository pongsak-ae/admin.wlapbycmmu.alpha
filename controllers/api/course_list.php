<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json');
use OMCore\OMDb;
$DB = OMDb::singleton();
//$log = new OMCore\OMLog;

$today = date("Y-m-d H:i:s");
$exp = strtotime('+30 days', strtotime($today));
$expires = date('Y-m-d H:i:s', $exp);

$sql = "SELECT 
        course.course_id, 
        course.course_no, 
        course.course_name, 
        course.course_datetime, 
        course.course_place, 
        course.course_price, 
        course.course_startdate, 
        course.course_enddate, 
        course.course_active,
        COUNT(customer.cus_id) AS course_customer,
        IFNULL(count_course_speaker.course_speaker,0) AS course_speaker
        FROM course LEFT JOIN customer ON course.course_id = customer.course_id AND customer.status = 'Approve'
        LEFT JOIN count_course_speaker ON course.course_id = count_course_speaker.course_id
        WHERE course.status = 'Y'
        GROUP BY course.course_id, 
        course.course_no, 
        course.course_name, 
        course.course_datetime, 
        course.course_place, 
        course.course_price, 
        course.course_startdate, 
        course.course_enddate, 
        course.course_active
        ORDER BY course.course_no desc";
$sql_param = array();
$ds = null;
$res = $DB->query($ds, $sql, $sql_param, 0, -1, "ASSOC");
$result = array();
foreach($ds as $v) {
        $result[] = array(
                'course_id' => $v['course_id'],
                'course_no' => $v['course_no'],
                'course_name' => $v['course_name'],
                'course_datetime' => $v['course_datetime'],
                'course_place' => $v['course_place'],
                'course_price' => $v['course_price'],
                'course_startdate' => $v['course_startdate'],
                'course_enddate' => $v['course_enddate'],
                'course_active' => $v['course_active'],
                'course_customer' => $v['course_customer'],
                'course_speaker' => $v['course_speaker']
        );
}

echo json_encode($result);
?>