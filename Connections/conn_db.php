<?php
date_default_timezone_set('Asia/Taipei');

$dsn = 'mysql:host=localhost;dbname=expstore;charset=utf8mb4';
$root = 'sales';
$password = '123456';

try{
    $link = new PDO($dsn, $root, $password);
    $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo 'Error' . $e->getMessage();
}
?>