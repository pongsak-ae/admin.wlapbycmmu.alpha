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
if(isset($_GET['course_id'])){
        // ============== COURSE DETAIL ===================
        $sql = "SELECT course_name, course_detail, course_datetime, course_place, course_price, course_startdate, course_enddate, course_schedule
                FROM course WHERE course_id = @course_id";
        $sql_param = array();
        $sql_param['course_id'] = $_GET['course_id'];
        $ds = null;
        $res = $DB->query($ds, $sql, $sql_param, 0, -1, "ASSOC");
        $result = array();
        foreach($ds as $v) {
                $result['course_detail'] = array(
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
        // ============== COURSE SPEAKER ===================
        $sql_cs = "SELECT speaker_name, speaker_surname, speaker_position, speaker_company, speaker_image, speaker_stage
        FROM v_course_speaker WHERE course_id = @course_id order by speaker_stage";
        $sql_param_cs = array();
        $sql_param_cs['course_id'] = $_GET['course_id'];
        $ds_course_speaker = null;
        $res_course_speaker = $DB->query($ds_course_speaker, $sql_cs, $sql_param_cs, 0, -1, "ASSOC");
        
        if ($res_course_speaker > 0) {
                $course_speaker = array();
                foreach($ds_course_speaker as $v) {
                        $course_speaker[] = array(
                                'speaker_name' => $v['speaker_name'],
                                'speaker_surname' => $v['speaker_surname'],
                                'speaker_position' => $v['speaker_position'],
                                'speaker_company' => $v['speaker_company'],
                                'speaker_image' => WEB_META_BASE_URL.'images/speaker/'.$v['speaker_image'],
                                'speaker_stage' => $v['speaker_stage']
                        );
                }
                $result['course_speaker'] = $course_speaker;
        }
        
        // ============== COURSE COMMENT ===================
        $sql_cc = "SELECT customer_fullname, customer_nickname, customer_image, commenter_title, commenter_detail
                        FROM v_comment_customer where course_id = @course_id";
        $sql_param_cc = array();
        $sql_param_cc['course_id'] = $_GET['course_id'];
        $ds_course_comment = null;
        $res_course_comment = $DB->query($ds_course_comment, $sql_cc, $sql_param_cc, 0, -1, "ASSOC");
        if ($res_course_comment > 0) {
                $course_comment = array();
                foreach($ds_course_comment as $v) {
                        $course_comment[] = array(
                                'customer_fullname' => $v['customer_fullname'],
                                'customer_nickname' => $v['customer_nickname'],
                                'customer_image' => WEB_META_BASE_URL.'images/customer/'.$v['customer_image'],
                                'commenter_title' => $v['commenter_title'],
                                'commenter_detail' => $v['commenter_detail']
                        );
                }
                $result['course_comment'] = $course_comment;
        }
        
        $response['data'] = $result;
}

echo json_encode($result);
?>