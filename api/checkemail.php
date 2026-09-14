<?php
require_once dirname(__DIR__) . '/config/conn_db.php';
if (!isset($_GET['email']) || !is_string($_GET['email'])) {
    http_response_code(400);
    echo 'false';
    return;
}

if (strlen($_GET['email']) > 100 || filter_var($_GET['email'], FILTER_VALIDATE_EMAIL) === false) {
    http_response_code(422);
    echo 'false';
    return;
}

$email = $_GET['email'];
$query = "SELECT emailid FROM member WHERE email=:value0";
$queryParams = array(':value0' => $email);
$result = $link->prepare($query);
$result->execute($queryParams);
$row = $result->rowCount();
echo $row == 0 ? 'true' : 'false';
return;
