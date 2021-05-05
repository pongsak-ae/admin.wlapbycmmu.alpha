<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json');
use OMCore\OMDb;
$DB = OMDb::singleton();
//$log = new OMCore\OMLog;

$response = array();
$today = date("Y-m-d H:i:s");
$exp = strtotime('+30 days', strtotime($today));
$expires = date('Y-m-d H:i:s', $exp);

$sql = "SELECT address, telephone, email, fax, line, facebook FROM contact limit 1";
$sql_param = array();
$ds = null;
$res = $DB->query($ds, $sql, $sql_param, 0, -1, "ASSOC");
$result = array();
foreach($ds as $v) {
        $result[] = array(
                'address' => $v['address'],
                'telephone' => $v['telephone'],
                'email' => $v['email'],
                'fax' => $v['fax'],
                'line' => $v['line'],
                'facebook' => $v['facebook']
        );
}

echo json_encode($result);
?>