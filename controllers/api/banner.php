<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Credentials: true');
use OMCore\OMDb;
$DB = OMDb::singleton();
//$log = new OMCore\OMLog;

$response = array();
$today = date("Y-m-d H:i:s");
$exp = strtotime('+30 days', strtotime($today));
$expires = date('Y-m-d H:i:s', $exp);

$sql = "SELECT banner_name, banner_image FROM banner WHERE banner_active = '1' and banner_status = 'Y' ORDER BY banner_id desc";
$sql_param = array();
$ds = null;
$res = $DB->query($ds, $sql, $sql_param, 0, -1, "ASSOC");
$result = array();
foreach($ds as $v) {
        $result[] = array(
                'banner_name' => $v['banner_name'],
                'banner_image' => WEB_META_BASE_URL.'images/banner/'.$v['banner_image']
        );
}
$response['data'] = $result;
//echo json_encode($response, JSON_UNESCAPED_SLASHES);

echo json_encode($result);
?>