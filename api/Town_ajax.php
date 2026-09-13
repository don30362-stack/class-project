<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once dirname(__DIR__) . '/config/conn_db.php';
require_once dirname(__DIR__) . '/includes/escape.php';

$value = $_POST['CNo'] ?? null;
if (!is_string($value) || preg_match('/\A[1-9][0-9]*\z/D', $value) !== 1) {
    http_response_code(400);
    echo json_encode(array('c' => '0', 'm' => '縣市編號格式不正確'), JSON_UNESCAPED_UNICODE);
    exit;
}
$Town = "SELECT townNo, Name FROM town WHERE AutoNo = :value0 AND State = 0 ORDER BY townNo";
$TownParams = array(':value0' => (int)$value);
$Town_rs = $link->prepare($Town);
$Town_rs->execute($TownParams);
$Town_num = $Town_rs->rowCount();
$htmlstring = "<option value=''>選擇鄉鎮市</option>";
if ($Town_num > 0) {
    while ($Town_rows = $Town_rs->fetch()) {
        $htmlstring .= '<option value="' . (int)$Town_rows['townNo'] . '">' . e($Town_rows['Name']) . '</option>';
    }
    $retcode = array("c" => "1", "m" => $htmlstring);
} else {
    http_response_code(404);
    $retcode = array("c" => "0", "m" => "找不到相關資料");
}

echo json_encode($retcode, JSON_UNESCAPED_UNICODE);
exit;
