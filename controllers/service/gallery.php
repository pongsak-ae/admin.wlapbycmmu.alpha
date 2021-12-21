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
    if($cmd == "gallery"){
        $course_id    = isset($_POST['course']) ? $_POST['course'] : "";
        $sql = "SELECT * FROM gallery WHERE course_id = @course AND gallery_status = 'Y'";

        $sql_param = array();
        $sql_param['course'] = $course_id;
        $ds = null;
        $res = $DB->query($ds, $sql, $sql_param, 0, -1, "ASSOC");
        $response['data'] = $ds;
        $response['status'] = true;

    } else if ($cmd == "remove_gallery"){
        $gallery_id    = isset($_POST['gallery_id']) ? $_POST['gallery_id'] : "";

        $sql_param = array();
        $sql_param['gallery_id'] = $gallery_id;
        $sql_param['gallery_status']    = 'N';
        $res = $DB->executeUpdate('gallery', 1, $sql_param);

        if ($res > 0) {
            $response['status'] = true;
            $response['msg'] = 'Remove gallery successfully';
        }else{
            $response['status'] = false;
            $response['msg'] = 'Remove gallery unsuccessfully';
        }

    } else if ($cmd == "active_gallery"){
        $gallery_ac_id    = isset($_POST['gallery_ac_id']) ? $_POST['gallery_ac_id'] : "";
        $gallery_active   = isset($_POST['gallery_active']) ? $_POST['gallery_active'] : "";

        $sql_param = array();
        $sql_param['gallery_id'] = $gallery_ac_id;
        $sql_param['gallery_active']    = $gallery_active;
        $res = $DB->executeUpdate('gallery', 1, $sql_param);

        if ($res > 0) {
            $response['status'] = true;
            $response['msg'] = 'Update gallery successfully';
        }else{
            $response['status'] = false;
            $response['msg'] = 'Update gallery unsuccessfully';
        }

    } else if ($cmd == "add_gallery"){
        $add_gallery_cid      = isset($_POST['add_gallery_cid']) ? $_POST['add_gallery_cid'] : "";
        $add_gallery_status   = isset($_POST['add_gallery_status']) ? $_POST['add_gallery_status'] : "";
        $add_gallery_name     = isset($_POST['add_gallery_name']) ? $_POST['add_gallery_name'] : "";
        $add_gallery_alt      = isset($_POST['add_gallery_alt']) ? $_POST['add_gallery_alt'] : "";
        $add_gallery_img      = isset($_FILES['add_gallery_img']) ? $_FILES['add_gallery_img'] : "";
        $add_gallery_stage    = isset($_POST['add_gallery_stage']) ? $_POST['add_gallery_stage'] : "";

        $image_gallery = null;
        if (!empty($_FILES["add_gallery_img"])) {
            $image_gallery = date('Ymd').'_'.OMImage::uuname()."." . str_replace(" ", "", basename($_FILES["add_gallery_img"]["type"]));
            copy($add_gallery_img["tmp_name"], ROOT_DIR . "images/gallery/" . $image_gallery);
        }

        $sql_param = array();
        $new_id = "";
        $sql_param['course_id']         = $add_gallery_cid;
        $sql_param['gallery_active']    = $add_gallery_status;
        $sql_param['gallery_name']      = $add_gallery_name;
        $sql_param['gallery_alt']       = $add_gallery_alt;
        $sql_param['gallery_img']       = $image_gallery;
        $sql_param['create_by']           = getSESSION();
        $sql_param['gallery_stage']       = $add_gallery_stage;

        $res = $DB->executeInsert('gallery', $sql_param, $new_id);

        if ($res > 0) {
            $response['status'] = true;
            $response['msg'] = 'Create image successfully';
        }else{
            $response['status'] = false;
            $response['msg'] = 'Create image unsuccessfully';  
        }

    } else if ($cmd == "edit_gallery"){
        $sql_param = array();
        $gallery_e_id           = isset($_POST['gallery_e_id']) ? $_POST['gallery_e_id'] : "";
        $edit_gallery_status    = isset($_POST['edit_gallery_status']) ? $_POST['edit_gallery_status'] : "";
        $edit_gallery_name      = isset($_POST['edit_gallery_name']) ? $_POST['edit_gallery_name'] : "";
        $edit_gallery_alt       = isset($_POST['edit_gallery_alt']) ? $_POST['edit_gallery_alt'] : "";
        $edit_gallery_img       = isset($_FILES["edit_gallery_img"]) ? $_FILES["edit_gallery_img"] : "";
        $edit_gallery_stage       = isset($_POST["edit_gallery_stage"]) ? $_POST["edit_gallery_stage"] : "";

        if($edit_gallery_img != ""){
            $image_gallery = null;
            if (!empty($_FILES["edit_gallery_img"])) {
                $image_gallery = date('Ymd').'_'.OMImage::uuname()."." . str_replace(" ", "", basename($_FILES["edit_gallery_img"]["type"]));
                copy($edit_gallery_img["tmp_name"], ROOT_DIR . "images/gallery/" . $image_gallery);
            }
            $sql_param['gallery_img']       = $image_gallery;
        }


        
        $sql_param['gallery_id']        = $gallery_e_id;
        $sql_param['gallery_name']      = $edit_gallery_name;
        
        $sql_param['gallery_alt']       = $edit_gallery_alt;
        $sql_param['gallery_active']    = $edit_gallery_status;
        $sql_param['gallery_stage']    = $edit_gallery_stage;
        $res = $DB->executeUpdate('gallery', 1, $sql_param);

        if ($res > 0) {
            $response['status'] = true;
            $response['msg'] = 'Update gallery successfully';
        }else{
            $response['status'] = false;
            $response['msg'] = 'Update gallery unsuccessfully';
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