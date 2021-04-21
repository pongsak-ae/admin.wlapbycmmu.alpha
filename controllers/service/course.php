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
        $sql = "SELECT * FROM course WHERE status = 'Y' ORDER BY course_active DESC";
        $sql_param = array();
        // $sql_param['course'] = $course_id;
        $ds = null;
        $res = $DB->query($ds, $sql, $sql_param, 0, -1, "ASSOC");
        $response['data'] = $ds;
        $response['status'] = true;

    } else if ($cmd == "add_course"){
        $add_c_active   = isset($_POST['add_c_active']) ? $_POST['add_c_active'] : "";
        $add_c_no       = isset($_POST['add_c_no']) ? $_POST['add_c_no'] : "";
        $add_c_name     = isset($_POST['add_c_name']) ? $_POST['add_c_name'] : "";
        $add_c_datetime = isset($_POST['add_c_datetime']) ? $_POST['add_c_datetime'] : "";
        $add_c_place    = isset($_POST['add_c_place']) ? $_POST['add_c_place'] : "";
        $add_c_price    = isset($_POST['add_c_price']) ? $_POST['add_c_price'] : "";
        $add_c_start    = isset($_POST['add_c_start']) ? $_POST['add_c_start'] : "";
        $add_c_end      = isset($_POST['add_c_end']) ? $_POST['add_c_end'] : "";
        $add_c_speeker  = isset($_POST['add_c_speeker']) ? $_POST['add_c_speeker'] : "";

        $sql_c = "SELECT course_no FROM course WHERE course_no = @c_no";
        $sql_param_c = array();
        $sql_param_c['c_no'] = $add_c_no;
        $ds_c = null;
        $res_c = $DB->query($ds_c, $sql_c, $sql_param_c, 0, -1, "ASSOC");

        if ($res_c == 0) {

            if ($add_c_active == '1') {
                // UPDATE COURSE ACTICE ALL 0
                $sql_param = array();
                $sql_param['course_active'] = 0;
                $res = $DB->executeUpdate('course', 0, $sql_param); 
            }else{
                $res = 1;
            }

            if ($res > 0) {

                $sql_param_ac = array();
                $new_id_ac = "";
                $sql_param_ac['course_active']      = $add_c_active;
                $sql_param_ac['course_no']          = $add_c_no;
                $sql_param_ac['course_name']        = $add_c_name;
                $sql_param_ac['course_datetime']    = $add_c_datetime;
                $sql_param_ac['course_place']       = $add_c_place;
                $sql_param_ac['course_price']       = $add_c_price;
                $sql_param_ac['course_startdate']   = $add_c_start;
                $sql_param_ac['course_enddate']     = $add_c_end;
                $sql_param_ac['create_by']          = getSESSION();

                $res_ac = $DB->executeInsert('course', $sql_param_ac, $new_id_ac);

                if ($res_ac > 0) {

                    $speeker_explode = explode(",", $add_c_speeker);
                    foreach($speeker_explode as $key=>$value) {
                        $sql_param_sp = array();
                        $new_id_sp = "";
                        $sql_param_sp['course_id']  = $new_id_ac;
                        $sql_param_sp['speaker_id'] = $value;
                        $res_sp = $DB->executeInsert('course_speaker', $sql_param_sp, $new_id_sp);
                    }

                    $response['status'] = true;
                    $response['msg'] = 'Create course successfully';

                }else{
                    $response['status'] = false;
                    $response['msg'] = 'Create course unsuccessfully';  
                }
                
            }else{
                $response['status'] = false;
                $response['msg'] = 'Create course unsuccessfully';
            }

        }else{
            $response['status'] = false;
            $response['msg'] = 'Course No already !';  
        }

    } else if ($cmd == "remove_course"){
        $course_id    = isset($_POST['course_id']) ? $_POST['course_id'] : "";

        $sql_s = "SELECT * FROM tview_course_register WHERE course_id = @course_id GROUP BY course_id";
        $sql_param_s = array();
        $sql_param_s['course_id'] = $course_id;
        $ds_s = null;
        $res_s = $DB->query($ds_s, $sql_s, $sql_param_s, 0, -1, "ASSOC");

        if ($res_s == 0) {
            $sql_c = "SELECT course_active FROM course WHERE course_id = @course_id LIMIT 1";
            $sql_param_c = array();
            $sql_param_c['course_id'] = $course_id;
            $ds_c = null;
            $res_c = $DB->query($ds_c, $sql_c, $sql_param_c, 0, -1, "ASSOC");

            if ($ds_c[0]['course_active'] == '0') {
                $sql_param = array();
                $sql_param['course_id'] = $course_id;
                $sql_param['status']    = 'N';
                $sql_param['update_by'] = getSESSION();
                $res = $DB->executeUpdate('course', 1, $sql_param);

                if ($res > 0) {
                    $response['status'] = true;
                    $response['msg'] = 'Remove course successfully';
                }else{
                    $response['status'] = false;
                    $response['msg'] = 'Remove course unsuccessfully';
                }
                
            }else{
                $response['status'] = false;
                $response['msg'] = 'Can not remove course active';
            }

        }else{
            $response['status'] = false;
            $response['msg'] = 'Can not remove course used';
        }

    } else if ($cmd == "edit_course"){

        $edit_c_course_id= isset($_POST['edit_c_course_id']) ? $_POST['edit_c_course_id'] : "";

        $edit_c_active   = isset($_POST['edit_c_active']) ? $_POST['edit_c_active'] : "";
        // $edit_c_no       = isset($_POST['edit_c_no']) ? $_POST['edit_c_no'] : "";
        $edit_c_name     = isset($_POST['edit_c_name']) ? $_POST['edit_c_name'] : "";
        $edit_c_datetime = isset($_POST['edit_c_datetime']) ? $_POST['edit_c_datetime'] : "";
        $edit_c_place    = isset($_POST['edit_c_place']) ? $_POST['edit_c_place'] : "";
        $edit_c_price    = isset($_POST['edit_c_price']) ? $_POST['edit_c_price'] : "";
        $edit_c_start    = isset($_POST['edit_c_start']) ? $_POST['edit_c_start'] : "";
        $edit_c_end      = isset($_POST['edit_c_end']) ? $_POST['edit_c_end'] : "";
        $edit_c_speeker  = isset($_POST['edit_c_speeker']) ? $_POST['edit_c_speeker'] : "";

        // $sql_c = "SELECT course_no FROM course WHERE course_no = @c_no";
        // $sql_param_c = array();
        // $sql_param_c['c_no'] = $edit_c_no;
        // $ds_c = null;
        // $res_c = $DB->query($ds_c, $sql_c, $sql_param_c, 0, -1, "ASSOC");

        // if ($res_c == 0) {
            if ($edit_c_active == '1') {
                $sql_param = array();
                $sql_param['course_active'] = 0;
                $res = $DB->executeUpdate('course', 0, $sql_param); 
            }else{
                $res = 1;
            }

            if ($res > 0) {
                $sql_param_ec = array();
                $sql_param_ec['course_id']          = $edit_c_course_id;  
                $sql_param_ec['course_active']      = $edit_c_active;  
                // $sql_param_ec['course_no']          = $edit_c_no;
                $sql_param_ec['course_name']        = $edit_c_name;
                $sql_param_ec['course_datetime']    = $edit_c_datetime;
                $sql_param_ec['course_place']       = $edit_c_place;
                $sql_param_ec['course_price']       = $edit_c_price;
                $sql_param_ec['course_startdate']   = $edit_c_start;
                $sql_param_ec['course_enddate']     = $edit_c_end;
                $sql_param_ec['update_by']          = getSESSION();
                $res_ec = $DB->executeUpdate('course', 1, $sql_param_ec); 

                if ($res_ec > 0) {

                    $sql_d = "DELETE FROM course_speaker WHERE course_id = @course_id";

                    $sql_param_d = array();
                    $sql_param_d['course_id'] = 1;
                    $res_d = $DB->execute($sql_d, $sql_param_d);

                    $speeker_explode = explode(",", $edit_c_speeker);
                    foreach($speeker_explode as $key=>$value) {
                        $sql_param_sp = array();
                        $new_id_sp = "";
                        $sql_param_sp['course_id']  = $edit_c_course_id;
                        $sql_param_sp['speaker_id'] = $value;
                        $res_sp = $DB->executeInsert('course_speaker', $sql_param_sp, $new_id_sp);
                    }

                    $response['status'] = true;
                    $response['msg'] = 'Update course successfully';
                }else{
                    $response['status'] = false;
                    $response['msg'] = 'Update course unsuccessfully';  
                }

            }else{
                $response['status'] = false;
                $response['msg'] = 'Update course unsuccessfully'; 
            }

        // }else{
        //     $response['status'] = false;
        //     $response['msg'] = 'Course No already !';  
        // }

    } else if ($cmd == "add_course_speeker"){
        $sql = "SELECT * FROM speaker WHERE speaker_status = 'Y'";
        $sql_param = array();
        // $sql_param['c_no'] = $add_c_no;
        $ds = null;
        $res = $DB->query($ds, $sql, $sql_param, 0, -1, "ASSOC");
        $response['status'] = true;
        $response['data'] = $ds;

    } else if ($cmd == "edit_course_speeker"){
        $course_id    = isset($_POST['course_id']) ? $_POST['course_id'] : "";

        $sql = "SELECT * FROM course_speaker WHERE course_id = @course_id";
        $sql_param = array();
        $sql_param['course_id'] = $course_id;
        $ds = null;
        $res = $DB->query($ds, $sql, $sql_param, 0, -1, "ASSOC");
        $response['status'] = true;
        $response['data'] = $ds;

    } else if ($cmd == "customer"){
        $course_id    = isset($_POST['course_id']) ? $_POST['course_id'] : "";

        $sql = "SELECT cus_id, customer_fullname, customer_image, customer_company, course_name FROM customer c
                LEFT JOIN course co ON c.course_id = co.course_id
                WHERE c.course_id = @course_id";
        $sql_param = array();
        $sql_param['course_id'] = $course_id;
        $ds = null;
        $res = $DB->query($ds, $sql, $sql_param, 0, -1, "ASSOC");
        $response['status'] = true;
        $response['data'] = $ds;

    } else if ($cmd == "comment"){
        $course_id    = isset($_POST['course_id']) ? $_POST['course_id'] : "";

        $sql = "SELECT commenter_id, commenter_title, commenter_detail, customer_fullname
                        , customer_company, customer_image, customer_position, cm.createdatetime AS commentdate
                FROM commenter cm LEFT JOIN customer c ON cm.cus_id = c.cus_id WHERE c.course_id = @course_id";

        $sql_param = array();
        $sql_param['course_id'] = $course_id;
        $ds = null;
        $res = $DB->query($ds, $sql, $sql_param, 0, -1, "ASSOC");
        $response['status'] = true;
        $response['data'] = $ds;

    } else if ($cmd == "add_comment"){
        $course_id          = isset($_POST['course_id']) ? $_POST['course_id'] : "";
        $comment_speeker    = isset($_POST['comment_speeker']) ? $_POST['comment_speeker'] : "";
        $comment_title      = isset($_POST['comment_title']) ? $_POST['comment_title'] : "";
        $comment_detail     = isset($_POST['comment_detail']) ? $_POST['comment_detail'] : "";

        $sql_param = array();
        $new_id = "";
        $sql_param['course_id']         = $course_id;
        $sql_param['cus_id']            = $comment_speeker;
        $sql_param['commenter_title']   = $comment_title;
        $sql_param['commenter_detail']  = $comment_detail;
        $sql_param['create_by']         = getSESSION();

        $res = $DB->executeInsert('commenter', $sql_param, $new_id);

        if ($res > 0) {
            $response['status'] = true;
        }else{
            $response['status'] = false;
            $response['msg'] = 'Create comment unsuccessfully'; 
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