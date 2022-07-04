<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json');
use OMCore\OMDb;
use OMCore\OMImage;
$DB = OMDb::singleton();
//$log = new OMCore\OMLog;

$response = array();
$today = date("Y-m-d H:i:s");
$exp = strtotime('+30 days', strtotime($today));
$expires = date('Y-m-d H:i:s', $exp);
if (isset($_POST['course_id']) && !empty($_POST['course_id'])) {
        try {
                $course_id = $_POST['course_id'];
                $customer_name_prefix = !empty($_POST['customer_name_prefix']) ? $_POST['customer_name_prefix'] : null;
                $customer_gender = !empty($_POST['customer_gender']) ? $_POST['customer_gender'] : null;
                if (strtolower($customer_gender) == 'other') {
                        $customer_gender = !empty($_POST['other_gender']) ? $_POST['other_gender'] : $customer_gender;
                }
                $customer_fullname_th = !empty($_POST['customer_fullname_th']) ? $_POST['customer_fullname_th'] : null;
                $customer_fullname_en = !empty($_POST['customer_fullname_en']) ? $_POST['customer_fullname_en'] : null;
                $customer_nickname = !empty($_POST['customer_nickname']) ? $_POST['customer_nickname'] : null;
                $customer_phone = !empty($_POST['customer_phone']) ? $_POST['customer_phone'] : null;
                $customer_birthday = !empty($_POST['customer_birthday']) ? date("Y-m-d", strtotime(str_replace('/', '-', $_POST['customer_birthday']))) : null;
                $customer_email = !empty($_POST['customer_email']) ? $_POST['customer_email'] : null;
                $customer_line = !empty($_POST['customer_line']) ? $_POST['customer_line'] : null;
                $customer_idcard = !empty($_POST['customer_idcard']) ? $_POST['customer_idcard'] : null;
                $customer_facebook = !empty($_POST['customer_facebook']) ? $_POST['customer_facebook'] : null;
                $customer_instagram = !empty($_POST['customer_instagram']) ? $_POST['customer_instagram'] : null;
                $customer_company = !empty($_POST['customer_company']) ? $_POST['customer_company'] : null;
                $customer_position = !empty($_POST['customer_position']) ? $_POST['customer_position'] : null;
                $customer_address = !empty($_POST['customer_address']) ? $_POST['customer_address'] : null;
                $coordinator_name = !empty($_POST['coordinator_name']) ? $_POST['coordinator_name'] : null;
                $coordinator_phone = !empty($_POST['coordinator_phone']) ? $_POST['coordinator_phone'] : null;
                $coordinator_email = !empty($_POST['coordinator_email']) ? $_POST['coordinator_email'] : null;
                $coordinator_adviser = !empty($_POST['coordinator_adviser']) ? $_POST['coordinator_adviser'] : null;
                $customer_improve = !empty($_POST['customer_improve']) ? implode(",", $_POST['customer_improve']) : null;
                $allergic_food = !empty($_POST['allergic_food']) ? $_POST['allergic_food'] : null;
                $customer_shirt = $_POST['customer_shirt'];
                $customer_image = null;
                if (!empty($_FILES["customer_image"])) {
                        $customer_image = date('Ymd').'_'.OMImage::uuname()."." . str_replace(" ", "", basename($_FILES["customer_image"]["type"]));
                        copy($_FILES["customer_image"]["tmp_name"], ROOT_DIR . "images/customer/" . $customer_image);
                }

                $new_id = "";
                $sql_param['course_id'] = $course_id;
                $sql_param['shirt_id'] = $customer_shirt;
                $sql_param['customer_name_prefix'] = $customer_name_prefix;
                $sql_param['customer_fullname'] = $customer_fullname_th;
                $sql_param['customer_fullname_th'] = $customer_fullname_th;
                $sql_param['customer_fullname_en'] = $customer_fullname_en;
                $sql_param['customer_nickname'] = $customer_nickname;
                $sql_param['customer_gender'] = $customer_gender;
                $sql_param['customer_line'] = $customer_line;
                $sql_param['customer_phone'] = $customer_phone;
                $sql_param['customer_facebook'] = $customer_facebook;
                $sql_param['customer_instagram'] = $customer_instagram;
                $sql_param['customer_email'] = $customer_email;
                $sql_param['customer_birthday'] = $customer_birthday;
                $sql_param['customer_company'] = $customer_company;
                $sql_param['customer_position'] = $customer_position;
                $sql_param['customer_idcard'] = $customer_idcard;
                $sql_param['customer_address'] = $customer_address;
                $sql_param['customer_image'] = $customer_image;
                $sql_param['coordinator_name'] = $coordinator_name;
                $sql_param['coordinator_phone'] = $coordinator_phone;
                $sql_param['coordinator_email'] = $coordinator_email;
                $sql_param['coordinator_adviser'] = $coordinator_adviser;
                $sql_param['customer_improve'] = $customer_improve;
                $sql_param['allergic_food'] = $allergic_food;
                $res = $DB->executeInsert('customer', $sql_param, $new_id);
                if ($res > 0) {
                        $response['status'] = true;
                        $response['msg'] = 'Register successfully';
                } else {
                        $response['status'] = false;
                        $response['msg'] = 'Register unsuccessfully';  
                }
        } catch (Exception $e) {
                $response['status'] = false;
                $response['msg'] = $e->getMessage();
        }
} else {
        $response['status'] = false;
        $response['msg'] = 'course id invalid';
}

echo json_encode($response);

function compressImage($source, $destination, $quality) { 
        // Get image info 
        $imgInfo = getimagesize($source);
        $mime = $imgInfo['mime'];
        // Create a new image from file
        switch($mime){ 
                case 'image/jpeg': 
                $image = imagecreatefromjpeg($source);
                imagejpeg($image, $destination, $quality);
                break; 
                case 'image/png': 
                $image = imagecreatefrompng($source);
                $pngQuality = ($quality - 100) / 11.111111;
                $pngQuality = round(abs($pngQuality));
                imagepng($image, $destination, $pngQuality);
                break; 
                case 'image/gif': 
                $image = imagecreatefromgif($source);
                imagegif($image, $destination, $quality);
                break; 
                default: 
                $image = imagecreatefromjpeg($source);
                imagejpeg($image, $destination, $quality);
        } 
        // Return compressed image 
        return $destination;
}
?>