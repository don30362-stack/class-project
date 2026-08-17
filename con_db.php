<?php
date_default_timezone_set('Asia/Taipei');

$dsn = 'mysql:host=localhost;dbname=expstore;charset=utf8mb4';
$root = 'sales';
$password = '123456';

try{
    $pdo = new PDO($dsn, $root, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo 'Error' . $e->getMessage();
}
?>