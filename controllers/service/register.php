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
    if($cmd == "register"){

        $username           = isset($_POST['username']) ? $_POST['username'] : "";
        $password           = isset($_POST['password']) ? $_POST['password'] : "";
        $confirm_password   = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : "";
        $email              = isset($_POST['email']) ? $_POST['email'] : "";

        // ########## CHECK USERNAME ENGLISH OR NUMBER ########## \\
        if (text_english($username) == true) {
            // ########## CHECK USERNAME IN DB ########## \\
            if (checkUsername($username) == 0) {
                // ########## CHECK PASSWORD DUP ########## \\
                if ($password == $confirm_password) {

                    $sql_param = array();
                    $new_id = "";
                    $sql_param['username']  = $username;
                    $sql_param['password']  = my_encrypt($password, WCMSetting::$ENCRYPT_LOGIN);
                    $sql_param['email']     = $email;

                    $res = $DB->executeInsert('users', $sql_param, $new_id);
                    if ($res > 0) {
                        $response['status'] = true;
                        $response['msg'] = 'Successfully';
                    }else{
                        $response['status'] = false;
                        $response['msg'] = 'Unsuccess';
                    }

                }else{
                    $response['status'] = false;
                    $response['msg'] = 'Passwords do not match';
                }
            }else{
                $response['status'] = false;
                $response['msg'] = 'Account already exists';
            }
        }else{
            $response['status'] = false;
            $response['msg'] = 'Please enter username in english';
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