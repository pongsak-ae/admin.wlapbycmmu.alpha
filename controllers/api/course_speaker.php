<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json');
use OMCore\OMDb;
$DB = OMDb::singleton();
$log = new OMCore\OMLog;

$response = array();
$today = date("Y-m-d H:i:s");
$exp = strtotime('+30 days', strtotime($today));
$expires = date('Y-m-d H:i:s', $exp);

$sql = "SELECT speaker_name, speaker_surname, speaker_position, speaker_company, speaker_image, speaker_stage, speaker_order
        FROM v_course_speaker WHERE course_active = '1' AND speaker_active = '1' order by speaker_stage, speaker_order";
$sql_param = array();
$ds = null;
$res = $DB->query($ds, $sql, $sql_param, 0, -1, "ASSOC");
$result = array();
foreach($ds as $v) {
        $result[] = array(
                'speaker_name' => $v['speaker_name'],
                'speaker_surname' => $v['speaker_surname'],
                'speaker_position' => $v['speaker_position'],
                'speaker_company' => $v['speaker_company'],
                'speaker_image' => WEB_META_BASE_URL.'images/speaker/'.$v['speaker_image'],
                'speaker_stage' => $v['speaker_stage'],
                'speaker_order' => $v['speaker_order']
        );
}
$response['data'] = $result;
//echo json_encode($response, JSON_UNESCAPED_SLASHES);

echo json_encode($result);
?>