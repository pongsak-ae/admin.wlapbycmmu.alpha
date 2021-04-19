<?php

use OMCore\OMDb;
// use OMCore\OMMail;
$DB = OMDb::singleton();
$log = new OMCore\OMLog;

$response = array();
$today = date("Y-m-d H:i:s");
$exp = strtotime('+30 days', strtotime($today));
$expires = date('Y-m-d H:i:s', $exp);

$cmd = isset($_POST['cmd']) ? $_POST['cmd'] : "";

if ($cmd != "") {
    if($cmd == "course"){
        // $course_id    = isset($_POST['course']) ? $_POST['course'] : "";
        $sql = "SELECT * FROM course ORDER BY course_active DESC";

        $sql_param = array();
        // $sql_param['course'] = $course_id;
        $ds = null;
        $res = $DB->query($ds, $sql, $sql_param, 0, -1, "ASSOC");
        $response['data'] = $ds;
        $response['status'] = true;

    } else if ($cmd == "change_status"){
        $customer_id    = isset($_POST['customer_id']) ? $_POST['customer_id'] : "";
        $status         = isset($_POST['status']) ? $_POST['status'] : "";
        
        $sql_param = array();
        $sql_param['cus_id']    = $customer_id;
        $sql_param['status']    = $status;
        $sql_param['update_by'] = getSESSION();
        $res = $DB->executeUpdate('customer', 1, $sql_param); 

        if ($res > 0) {
            $response['status'] = true;
            $response['msg'] = 'Change ' . $status . ' successfully';
        }else{
            $response['status'] = false;
            $response['msg'] = 'Change ' . $status . ' unsuccessfully';
        }

    } else {
        $response['status'] = false;
        $response['error_msg'] = 'no command';
        $response['code'] = '500';
    }

} else {
    // error
    $response['status'] = false;
    $response['msg'] = 'no command';
    $response['code'] = '500';
}

echo json_encode($response);

?>