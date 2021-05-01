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
    if($cmd == "employee"){
        $sql = "SELECT emp_id, username, full_name, telephone, email, position, is_admin FROM employee WHERE status = 'Y'";
        $sql_param = array();
        $ds = null;
        $res = $DB->query($ds, $sql, $sql_param, 0, -1, "ASSOC");
        $response['data'] = $ds;
        $response['status'] = true;
    } else if ($cmd == "add_employee") {
        if ($_SESSION['isAdmin'] == "Y") {
            $add_e_username = isset($_POST['add_e_username']) ? $_POST['add_e_username'] : "";
            $sql = "SELECT * FROM employee WHERE username = @username LIMIT 1";
            $sql_param = array();
            $sql_param['username'] = $add_e_username;
            $ds = null;
            $res = $DB->query($ds, $sql, $sql_param, 0, -1, "ASSOC");
            if ($res == 0) {
                $add_e_password = isset($_POST['add_e_password']) ? $_POST['add_e_password'] : "";
                $add_e_name = !empty($_POST['add_e_name']) ? $_POST['add_e_name'] : null;
                $add_e_phone = !empty($_POST['add_e_phone']) ? $_POST['add_e_phone'] : null;
                $add_e_email = !empty($_POST['add_e_email']) ? $_POST['add_e_email'] : null;
                $add_e_pos = !empty($_POST['add_e_pos']) ? $_POST['add_e_pos'] : null;
                $add_e_admin = !empty($_POST['add_e_admin']) ? $_POST['add_e_admin'] : null;
                
                $new_id = "";
                $sql_param['password'] = password_hash($add_e_password, PASSWORD_DEFAULT);
                $sql_param['full_name'] = $add_e_name;
                $sql_param['telephone'] = $add_e_phone;
                $sql_param['email'] = addslashes($add_e_email);
                $sql_param['position'] = addslashes($add_e_pos);
                $sql_param['is_admin'] = $add_e_admin;
                $sql_param['create_by'] = getSESSION();

                $res = $DB->executeInsert('employee', $sql_param, $new_id);
                if ($res > 0) {
                    $response['status'] = true;
                    $response['msg'] = 'Create employee successfully';
                } else {
                    $response['status'] = false;
                    $response['msg'] = 'Create employee unsuccessfully';  
                }
            } else {
                $response['status'] = false;
                $response['msg'] = "User name already exists";
            }
        } else {
            $response['status'] = false;
            $response['msg'] = 'You are not authorized to add employee.';
        }
    } else if ($cmd == "update_employee"){
        if ($_SESSION['isAdmin'] == "Y") {
            if (!empty($_POST['edit_e_id']) && !empty($_POST['edit_e_username'])) {
                $sql_param = array();
                $sql_param['emp_id'] = $_POST['edit_e_id'];
                $sql_param['username'] = addslashes($_POST['edit_e_username']);
                if (isset($_POST['edit_e_password']) && !empty($_POST['edit_e_password'])) {
                    $sql_param['password'] = password_hash($_POST['edit_e_password'], PASSWORD_DEFAULT);
                }
                if (!empty($_POST['edit_e_name'])) $sql_param['full_name'] = $_POST['edit_e_name'];
                if (!empty($_POST['edit_e_phone'])) $sql_param['telephone'] = $_POST['edit_e_phone'];
                if (!empty($_POST['edit_e_email'])) $sql_param['email'] = $_POST['edit_e_email'];
                if (!empty($_POST['edit_e_pos'])) $sql_param['position'] = $_POST['edit_e_pos'];
                $sql_param['is_admin'] = $_POST['edit_e_admin'];
                $sql_param['update_by'] = getSESSION();
                $res = $DB->executeUpdate('employee', 1, $sql_param); 
                if ($res > 0) {
                    $response['status'] = true;
                    $response['msg'] = 'Update successfully';
                }else{
                    $response['status'] = false;
                    $response['msg'] = 'Update unsuccessfully';
                }
            } else {
                $response['status'] = false;
                $response['msg'] = 'invalid data';
            }
        } else {
            $response['status'] = false;
            $response['msg'] = 'You are not authorized to edit employee.';
        }
    } else if ($cmd == "update_status") {
        if ($_SESSION['isAdmin'] == "Y") {
            if (!empty($_POST['emp_id']) && !empty($_POST['emp_active'])) {
                $sql_param = array();
                $sql_param['emp_id'] = $_POST['emp_id'];
                $sql_param['is_admin'] = $_POST['emp_active'];
                $sql_param['update_by'] = getSESSION();
                $res = $DB->executeUpdate('employee', 1, $sql_param);
                if ($res > 0) {
                    $response['status'] = true;
                    $response['msg'] = 'successfully';
                }else{
                    $response['status'] = false;
                    $response['msg'] = 'failed';
                }
            } else {
                $response['status'] = false;
                $response['msg'] = 'invalid data';
            }
        } else {
            $response['status'] = false;
            $response['msg'] = 'You are not authorized to update status employee.';
        }
    } else if ($cmd == "remove_employee") {
        if ($_SESSION['isAdmin'] == "Y") {
            if (isset($_POST['emp_id'])) {
                $sql_param = array();
                $sql_param['emp_id'] = $_POST['emp_id'];
                $sql_param['status'] = 'N';
                $sql_param['update_by'] = getSESSION();
                $res = $DB->executeUpdate('employee', 1, $sql_param);
                if ($res > 0) {
                    $response['status'] = true;
                    $response['msg'] = 'successfully';
                }else{
                    $response['status'] = false;
                    $response['msg'] = 'failed';
                }
            } else {
                $response['status'] = false;
                $response['msg'] = 'invalid data';
            }
        } else {
            $response['status'] = false;
            $response['msg'] = 'You are not authorized to remove employee.';
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