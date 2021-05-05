<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json');
use OMCore\OMDb;
$DB = OMDb::singleton();

$today = date("Y-m-d H:i:s");
$exp = strtotime('+30 days', strtotime($today));
$expires = date('Y-m-d H:i:s', $exp);

$sql = "SELECT shirt_id, shirt_gender, shirt_size, shirt_width, shirt_height FROM shirt ORDER BY shirt_gender, shirt_width";
$sql_param = array();
$ds = null;
$res = $DB->query($ds, $sql, $sql_param, 0, -1, "ASSOC");
$response = array();
$result = array();
foreach($ds as $v) {
        $result[$v['shirt_gender']][] = array(
                'shirt_id' => $v['shirt_id'],
                'shirt_gender' => $v['shirt_gender'],
                'shirt_size' => $v['shirt_size'],
                'shirt_width' => $v['shirt_width'],
                'shirt_height' => $v['shirt_height']
        );
}
$response[] = $result;
echo json_encode($response);
?>