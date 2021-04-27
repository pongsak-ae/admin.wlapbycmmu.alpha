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
    if ($cmd == "add_banner"){
        $add_banner_status   = isset($_POST['add_banner_status']) ? $_POST['add_banner_status'] : "";
        $add_banner_name     = isset($_POST['add_banner_name']) ? $_POST['add_banner_name'] : "";
        $add_banner_img      = isset($_FILES['add_banner_img']) ? $_FILES['add_banner_img'] : "";

        $image_banner = null;
        if (!empty($_FILES["add_banner_img"])) {
            $image_banner = date('Ymd').'_'.OMImage::uuname()."." . str_replace(" ", "", basename($_FILES["add_banner_img"]["type"]));
            copy($add_banner_img["tmp_name"], ROOT_DIR . "images/banner/" . $image_banner);
        }

        $sql_param = array();
        $new_id = "";
        $sql_param['banner_name']    = $add_banner_name;
        $sql_param['banner_image']   = $image_banner;
        $sql_param['banner_active']  = $add_banner_status;
        $sql_param['create_by']      = getSESSION();

        $res = $DB->executeInsert('banner', $sql_param, $new_id);

        if ($res > 0) {
            $response['status'] = true;
            $response['msg'] = 'Create banner successfully';
        }else{
            $response['status'] = false;
            $response['msg'] = 'Create banner unsuccessfully';  
        }

    } else if ($cmd == "show_banner"){
        $sql = "SELECT * FROM banner WHERE banner_status = 'Y' ORDER BY banner_id DESC";
        $sql_param = array();
        $ds = null;
        $res = $DB->query($ds, $sql, $sql_param, 0, -1, "ASSOC");

        $response['status'] = true;
        $response['data'] = $ds;

    } else if ($cmd == "remove_banner"){
        $banner_id    = isset($_POST['banner_id']) ? $_POST['banner_id'] : "";

        $sql_param = array();
        $sql_param['banner_id'] = $banner_id;
        $sql_param['banner_status']    = 'N';
        $res = $DB->executeUpdate('banner', 1, $sql_param);

        if ($res > 0) {
            $response['status'] = true;
            $response['msg'] = 'Remove banner successfully';
        }else{
            $response['status'] = false;
            $response['msg'] = 'Remove banner unsuccessfully';
        }

    } else if ($cmd == "update_banner"){
        $active_banner_id    = isset($_POST['active_banner_id']) ? $_POST['active_banner_id'] : "";
        $active    = isset($_POST['active']) ? $_POST['active'] : "";

        $sql_param = array();
        $sql_param['banner_id']     = $active_banner_id;
        $sql_param['banner_active'] = $active;
        $res = $DB->executeUpdate('banner', 1, $sql_param);

        if ($res > 0) {
            $response['status'] = true;
            $response['msg'] = 'Update banner successfully';
        }else{
            $response['status'] = false;
            $response['msg'] = 'Update banner unsuccessfully';
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