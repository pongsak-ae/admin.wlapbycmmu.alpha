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
    if ($cmd == "update_contact"){
        
        $contact_address    = isset($_POST['contact_address']) ? $_POST['contact_address'] : "";
        $contact_phone      = isset($_POST['contact_phone']) ? $_POST['contact_phone'] : "";
        $contact_email      = isset($_POST['contact_email']) ? $_POST['contact_email'] : "";
        $contact_fax        = isset($_POST['contact_fax']) ? $_POST['contact_fax'] : "";
        $contact_line       = isset($_POST['contact_line']) ? $_POST['contact_line'] : "";
        $contact_facebook   = isset($_POST['contact_facebook']) ? $_POST['contact_facebook'] : "";

        $sql_param = array();
        $sql_param['contact_id']    = 1;
        $sql_param['address']       = $contact_address;
        $sql_param['telephone']     = $contact_phone;
        $sql_param['email']         = $contact_email;
        $sql_param['fax']           = $contact_fax;
        $sql_param['line']          = $contact_line;
        $sql_param['facebook']      = $contact_facebook;
        $res = $DB->executeUpdate('contact', 1, $sql_param);

        if ($res > 0) {
            $response['status'] = true;
            $response['msg'] = 'Update contact successfully';
        }else{
            $response['status'] = false;
            $response['msg'] = 'Update contact unsuccessfully';
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