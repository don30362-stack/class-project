<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once dirname(__DIR__) . '/config/conn_db.php';

$value = $_GET['AutoNo'] ?? null;
if (!is_string($value) || preg_match('/\A[1-9][0-9]*\z/D', $value) !== 1) {
    http_response_code(400);
    echo json_encode(array('c' => '0', 'm' => '地區編號格式不正確'), JSON_UNESCAPED_UNICODE);
    exit;
}
$Zip = "SELECT t.Name, t.Post, c.Name AS Cityname
        FROM town AS t
        INNER JOIN city AS c ON c.AutoNo = t.AutoNo
        WHERE t.townNo = :value0 AND t.State = 0 AND c.State = 0";
$ZipParams = array(':value0' => (int)$value);
$Zip_rs = $link->prepare($Zip);
$Zip_rs->execute($ZipParams);
$Town_rows = $Zip_rs->fetch(PDO::FETCH_ASSOC);
$htmlstring = "<option value=''>選擇鄉鎮市</option>";
if ($Town_rows) {
    $retcode = array("c" => "1", "Post" => $Town_rows['Post'], "Name" => $Town_rows['Name'], "Cityname" => $Town_rows['Cityname']);
} else {
    http_response_code(404);
    $retcode = array("c" => "0", "m" => "找不到相關資料");
}

echo json_encode($retcode, JSON_UNESCAPED_UNICODE);
exit;
