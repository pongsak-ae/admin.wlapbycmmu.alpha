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
    if($cmd == "speaker"){
        $sql = "SELECT speaker_id, speaker_name, speaker_surname, speaker_email, speaker_position, speaker_company, speaker_image, speaker_sort
                FROM speaker WHERE speaker_status = 'Y' ORDER BY speaker_sort";
        $sql_param = array();
        $ds = null;
        $res = $DB->query($ds, $sql, $sql_param, 0, -1, "ASSOC");
        // $source_re = array();
        // foreach($ds as $v){
        //     $source_re[] = array(
        //         'id' => $v['speaker_id'],
        //         'name' => $v['speaker_name'].'|'.$v['speaker_surname'].'|'.$v['speaker_email'].'|'.$v['speaker_image'],
        //         'title' => $v['speaker_position'].'|'.$v['speaker_company'],
        //         'sort' => $v['speaker_sort']
        //     );
        // }
        $response['data'] = $ds;
        $response['status'] = true;
    } else if ($cmd == "update_speaker"){
        $speaker_id = isset($_POST['speaker_id']) ? $_POST['speaker_id'] : "";
        $status = isset($_POST['speaker_status']) ? $_POST['speaker_status'] : "";
        $sql_param = array();
        $sql_param['speaker_id']    = $customer_id;
        $sql_param['speaker_status']    = $status;
        $sql_param['update_by'] = getSESSION();
        $res = $DB->executeUpdate('speaker', 1, $sql_param); 

        if ($res > 0) {
            $response['status'] = true;
            $response['msg'] = 'Change ' . $status . ' successfully';
        }else{
            $response['status'] = false;
            $response['msg'] = 'Change ' . $status . ' unsuccessfully';
        }
    } else if ($cmd == "remove_speaker") {
        $speaker_id  = isset($_POST['speaker_id']) ? $_POST['speaker_id'] : "";
        $sql_s = "SELECT * FROM v_course_speaker WHERE speaker_id = @speaker_id LIMIT 1";
        $sql_param_s = array();
        $sql_param_s['speaker_id'] = $speaker_id;
        $ds_s = null;
        $res_s = $DB->query($ds_s, $sql_s, $sql_param_s, 0, -1, "ASSOC");
        if ($res_s == 0) {
            $sql_param = array();
            $sql_param['speaker_id'] = $speaker_id;
            $sql_param['status']    = 'N';
            $sql_param['update_by'] = getSESSION();
            $res = $DB->executeUpdate('speaker', 1, $sql_param);

            if ($res > 0) {
                $response['status'] = true;
                $response['msg'] = 'Remove course successfully';
            }else{
                $response['status'] = false;
                $response['msg'] = 'Remove course unsuccessfully';
            }
        }else{
            $response['status'] = false;
            $response['msg'] = 'Can not remove course used';
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