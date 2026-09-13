<?php
require_once dirname(__DIR__) . '/config/conn_db.php';
if (isset($_GET['email']) && is_string($_GET['email']) && strlen($_GET['email']) <= 100 && filter_var($_GET['email'], FILTER_VALIDATE_EMAIL) !== false) {
    $email = $_GET['email'];
    $query = "SELECT emailid FROM member WHERE email=:value0";
    $queryParams = array(':value0' => $email);
    $result = $link->prepare($query);
    $result->execute($queryParams);
    $row = $result->rowCount();
    if ($row == 0) {
        echo 'true';
        return;
    }
}
if (isset($_GET['email'])) {
    http_response_code(400);
}
echo 'false';
return;
