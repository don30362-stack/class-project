<?php
require_once dirname(__DIR__) . '/config/conn_db.php';
if (isset($_GET['email'])) {
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
echo 'false';
return;
