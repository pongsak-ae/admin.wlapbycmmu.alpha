<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json');
use OMCore\OMDb;
$DB = OMDb::singleton();

$today = date("Y-m-d H:i:s");
$exp = strtotime('+30 days', strtotime($today));
$expires = date('Y-m-d H:i:s', $exp);

$sql = "SELECT b.course_id FROM course a , course b WHERE a.course_active = '1' AND b.course_no < a.course_no ORDER BY b.course_no desc LIMIT 1";
$sql_param = array();
$ds = null;
$res = $DB->query($ds, $sql, $sql_param, 0, -1, "ASSOC");
$result = array();
if ($res > 0) {
        $sql_cc = "SELECT customer_fullname, customer_nickname, customer_image, commenter_title, commenter_detail
                        FROM v_comment_customer where course_id = @course_id";
        $sql_param_cc = array();
        $sql_param_cc['course_id'] = $ds[0]['course_id'];
        $ds_course_comment = null;
        $res_course_comment = $DB->query($ds_course_comment, $sql_cc, $sql_param_cc, 0, -1, "ASSOC");
        if ($res_course_comment > 0) {
                foreach($ds_course_comment as $v) {
                        $result[] = array(
                                'customer_fullname' => $v['customer_fullname'],
                                'customer_nickname' => $v['customer_nickname'],
                                'customer_image' => WEB_META_BASE_URL.'images/customer/'.$v['customer_image'],
                                'commenter_title' => $v['commenter_title'],
                                'commenter_detail' => $v['commenter_detail']
                        );
                }
        }
}

echo json_encode($result);
?>