<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json');
use OMCore\OMDb;
$DB = OMDb::singleton();

$today = date("Y-m-d H:i:s");
$exp = strtotime('+30 days', strtotime($today));
$expires = date('Y-m-d H:i:s', $exp);

$sql = "SELECT shirt_id, shirt_gender, shirt_size, shirt_width, shirt_height FROM shirt";
$sql_param = array();
$ds = null;
$res = $DB->query($ds, $sql, $sql_param, 0, -1, "ASSOC");
$result = array();
foreach($ds as $v) {
        $result[] = array(
                'shirt_id' => $v['shirt_id'],
                'shirt_gender' => $v['shirt_gender'],
                'shirt_size' => $v['shirt_size'],
                'shirt_width' => $v['shirt_width'],
                'shirt_height' => $v['shirt_height']
        );
}

echo json_encode($result);
?>