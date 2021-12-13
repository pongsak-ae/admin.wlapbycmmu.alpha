<?php

use OMCore\OMDb;
use OMCore\OMImage;
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
        $sql = "SELECT speaker_id, speaker_name, speaker_surname, speaker_email, speaker_position, 
                    speaker_company, speaker_image, speaker_order, speaker_active, (select max(speaker_order) FROM speaker WHERE speaker_status = 'Y') as speaker_order_max
                FROM speaker WHERE speaker_status = 'Y'";
        $sql_param = array();
        $ds = null;
        $res = $DB->query($ds, $sql, $sql_param, 0, -1, "ASSOC");
        $response['data'] = $ds;
        $response['status'] = true;
    } else if ($cmd == "add_speaker") {
        $add_s_name = isset($_POST['add_s_name']) ? $_POST['add_s_name'] : "";
        $add_s_lname = isset($_POST['add_s_lname']) ? $_POST['add_s_lname'] : "";
        $add_s_email = isset($_POST['add_s_email']) ? $_POST['add_s_email'] : "";
        $add_s_comp = isset($_POST['add_s_comp']) ? $_POST['add_s_comp'] : "";
        $add_s_pos = isset($_POST['add_s_pos']) ? $_POST['add_s_pos'] : "";
        $newfilename = '';
        if (!empty($_FILES["add_s_img"])) {
            $newfilename = date('Ymd').'_'.OMImage::uuname()."." . str_replace(" ", "", basename($_FILES["add_s_img"]["type"]));
            copy($_FILES["add_s_img"]["tmp_name"], ROOT_DIR . "images/speaker/" . $newfilename);
        }
        
        $sql_param = array();
        $new_id = "";
        $sql_param['speaker_name'] = addslashes($add_s_name);
        $sql_param['speaker_surname'] = addslashes($add_s_lname);
        $sql_param['speaker_position'] = addslashes($add_s_pos);
        $sql_param['speaker_company'] = addslashes($add_s_comp);
        $sql_param['speaker_email'] = addslashes($add_s_email);
        $sql_param['speaker_image'] = $newfilename;
        $sql_param['create_by'] = getSESSION();

        $res = $DB->executeInsert('speaker', $sql_param, $new_id);
        if ($res > 0) {
            $sql_upd_last_order = "UPDATE speaker set speaker_order = ((SELECT selected_value 
                                    FROM (SELECT MAX(speaker_order) AS selected_value FROM speaker) AS sub_selected_value) + 1) 
                                    WHERE speaker_id = ".$new_id;
            $DB->execute($sql_upd_last_order);
            $response['status'] = true;
            $response['msg'] = 'Create speaker successfully';
        } else {
            $response['status'] = false;
            $response['msg'] = 'Create speaker unsuccessfully';  
        }
    } else if ($cmd == "update_speaker"){
        $edit_s_id = isset($_POST['edit_s_id']) ? $_POST['edit_s_id'] : "";
        $edit_s_name = isset($_POST['edit_s_name']) ? $_POST['edit_s_name'] : "";
        $edit_s_lname = isset($_POST['edit_s_lname']) ? $_POST['edit_s_lname'] : "";
        $edit_s_email = isset($_POST['edit_s_email']) ? $_POST['edit_s_email'] : "";
        $edit_s_comp = isset($_POST['edit_s_comp']) ? $_POST['edit_s_comp'] : "";
        $edit_s_pos = isset($_POST['edit_s_pos']) ? $_POST['edit_s_pos'] : "";
        $newfilename = '';
        if (!empty($_FILES["edit_s_img"]["tmp_name"])) {
            $newfilename = date('Ymd').'_'.OMImage::uuname()."." . str_replace(" ", "", basename($_FILES["edit_s_img"]["type"]));
            copy($_FILES["edit_s_img"]["tmp_name"], ROOT_DIR . "images/speaker/" . $newfilename);
        }
        $edit_s_order = isset($_POST['edit_s_order']) ? $_POST['edit_s_order'] : "";
        $edit_s_current_order = isset($_POST['edit_s_current_order']) ? $_POST['edit_s_current_order'] : "";
        $sql_param = array();
        $sql_param['speaker_id'] = $edit_s_id;
        $sql_param['speaker_name'] = addslashes($edit_s_name);
        $sql_param['speaker_surname'] = addslashes($edit_s_lname);
        $sql_param['speaker_position'] = addslashes($edit_s_pos);
        $sql_param['speaker_company'] = addslashes($edit_s_comp);
        $sql_param['speaker_email'] = addslashes($edit_s_email);
        if ($newfilename != '') {
            $sql_param['speaker_image'] = $newfilename;
        }
        $sql_param['update_by'] = getSESSION();
        $res = $DB->executeUpdate('speaker', 1, $sql_param); 
        if ($res > 0) {
            if ($edit_s_current_order != $edit_s_order) {
                if ($edit_s_current_order < $edit_s_order) {
                    $set = "speaker_order = speaker_order - 1";
                    $where = "speaker_order > $edit_s_current_order and speaker_order <= $edit_s_order";
                } else {
                    $set = "speaker_order = speaker_order + 1";
                    $where = "speaker_order < $edit_s_current_order and speaker_order >= $edit_s_order";
                }

                $sql_upd_reorder = "update speaker set $set where $where";
                $DB->execute($sql_upd_reorder);
                $sql_upd_order = "update speaker set speaker_order = $edit_s_order where speaker_id = $edit_s_id";
                //$sql_param_d = array();
                //$sql_param_d['course_id'] = $edit_c_course_id;
                $res_d = $DB->execute($sql_upd_order);
            }
            
            $response['status'] = true;
            $response['msg'] = 'Update successfully';
        }else{
            $response['status'] = false;
            $response['msg'] = 'Update unsuccessfully';
        }
    } else if ($cmd == "update_active") {
        $speaker_id  = isset($_POST['speaker_id']) ? $_POST['speaker_id'] : "";
        $speaker_active  = isset($_POST['speaker_active']) ? $_POST['speaker_active'] : "";
        $sql_param = array();
        $sql_param['speaker_id'] = $speaker_id;
        $sql_param['speaker_active'] = ($speaker_active == '1') ? '0' : '1';
        $sql_param['update_by'] = getSESSION();
        $res = $DB->executeUpdate('speaker', 1, $sql_param);
        if ($res > 0) {
            $response['status'] = true;
            $response['msg'] = 'successfully';
        }else{
            $response['status'] = false;
            $response['msg'] = 'failed';
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
            $sql_param['speaker_status'] = 'N';
            $sql_param['update_by'] = getSESSION();
            $res = $DB->executeUpdate('speaker', 1, $sql_param);
            if ($res > 0) {
                $response['status'] = true;
                $response['msg'] = 'successfully';
            }else{
                $response['status'] = false;
                $response['msg'] = 'failed';
            }
        }else{
            $response['status'] = false;
            $response['msg'] = 'Can\'t remove speaker in course';
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