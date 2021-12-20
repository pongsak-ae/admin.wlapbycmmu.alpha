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
if (isset($_GET['course_id']) && !empty($_GET['course_id'])){
        $wh_course = " course_id = ".$_GET['course_id'];
} else {
        $wh_course = "course_active = '1'";
}

// ============== COURSE DETAIL ===================
$sql = "SELECT course_id, course_name, course_detail, course_datetime, course_place, course_price, course_startdate, course_enddate, course_schedule
        FROM course WHERE ".$wh_course." LIMIT 1";
$sql_param = array();
$ds = null;
$res = $DB->query($ds, $sql, $sql_param, 0, -1, "ASSOC");
$result = array();
$course_id = $ds[0]['course_id'];
$result['course_detail'] = array(
        'course_name' => $ds[0]['course_name'],
        'course_detail' => base64_decode($ds[0]['course_detail']),
        'course_datetime' => $ds[0]['course_datetime'],
        'course_place' => $ds[0]['course_place'],
        'course_price' => $ds[0]['course_price'],
        'course_startdate' => $ds[0]['course_startdate'],
        'course_enddate' => $ds[0]['course_enddate'],
        'course_schedule' => WEB_META_BASE_URL.'images/table/'.$ds[0]['course_schedule']
);
// ============== COURSE SPEAKER ===================
$sql_cs = "SELECT speaker_name, speaker_surname, speaker_position, speaker_company, speaker_image, speaker_stage, speaker_order
FROM v_course_speaker WHERE course_id = @course_id order by speaker_stage, speaker_order";
$sql_param_cs = array();
$sql_param_cs['course_id'] = $course_id;
$ds_course_speaker = null;
$res_course_speaker = $DB->query($ds_course_speaker, $sql_cs, $sql_param_cs, 0, -1, "ASSOC");
$course_speaker = array();
if ($res_course_speaker > 0) {
        foreach($ds_course_speaker as $v) {
                $course_speaker[] = array(
                        'speaker_name' => $v['speaker_name'],
                        'speaker_surname' => $v['speaker_surname'],
                        'speaker_position' => $v['speaker_position'],
                        'speaker_company' => $v['speaker_company'],
                        'speaker_image' => WEB_META_BASE_URL.'images/speaker/'.$v['speaker_image'],
                        'speaker_stage' => $v['speaker_stage'],
                        'speaker_order' => $v['speaker_order']
                );
        }
}
$result['course_speaker'] = $course_speaker;
// ============== COURSE COMMENT ===================
$sql_cc = "SELECT customer_fullname, customer_nickname, customer_image, commenter_title, commenter_detail
                FROM v_comment_customer where course_id = @course_id";
$sql_param_cc = array();
$sql_param_cc['course_id'] = $course_id;
$ds_course_comment = null;
$res_course_comment = $DB->query($ds_course_comment, $sql_cc, $sql_param_cc, 0, -1, "ASSOC");
$course_comment = array();
if ($res_course_comment > 0) {
        foreach($ds_course_comment as $v) {
                $course_comment[] = array(
                        'customer_fullname' => $v['customer_fullname'],
                        'customer_nickname' => $v['customer_nickname'],
                        'customer_image' => WEB_META_BASE_URL.'images/customer/'.$v['customer_image'],
                        'commenter_title' => $v['commenter_title'],
                        'commenter_detail' => $v['commenter_detail']
                );
        }
        
}
$result['course_comment'] = $course_comment;
// ============== COURSE GALLERY ===================
$sql_g = "SELECT gallery_name, gallery_img, gallery_alt
                FROM gallery where course_id = @course_id and gallery_active = '1' and gallery_status = 'Y'";
$sql_param_g = array();
$sql_param_g['course_id'] = $course_id;
$ds_course_gallery = null;
$res_course_gallery = $DB->query($ds_course_gallery, $sql_g, $sql_param_g, 0, -1, "ASSOC");
$course_gallery = array();
if ($res_course_gallery > 0) {
        foreach($ds_course_gallery as $v) {
                $course_gallery[] = array(
                        'gallery_name' => $v['gallery_name'],
                        'gallery_img' => WEB_META_BASE_URL.'images/gallery/'.$v['gallery_img'],
                        'gallery_alt' => $v['gallery_alt']
                );
        }
        
}
$result['course_gallery'] = $course_gallery;
$response[] = $result;

echo json_encode($response);
?>