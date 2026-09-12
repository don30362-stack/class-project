<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type:application/json;charset=utf-8');

require_once('Connections/conn_db.php');

$Zip = "SELECT t.Name, t.Post, c.Name AS Cityname
        FROM town AS t
        INNER JOIN city AS c ON c.AutoNo = t.AutoNo
        WHERE t.townNo = :value0";
$ZipParams = array(':value0' => (int)$_GET["AutoNo"]);
$Zip_rs = $link->prepare($Zip);
$Zip_rs->execute($ZipParams);
$Town_rows = $Zip_rs->fetch(PDO::FETCH_ASSOC);
$htmlstring = "<option value=''>選擇鄉鎮市</option>";
if ($Town_rows) {
    $retcode = array("c" => "1", "Post" => $Town_rows['Post'], "Name" => $Town_rows['Name'], "Cityname" => $Town_rows['Cityname']);
} else {
    $retcode = array("c" => "0", "m" => "找不到相關資料");
}

echo json_encode($retcode, JSON_UNESCAPED_UNICODE);
return;