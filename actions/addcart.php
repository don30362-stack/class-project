<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

require_once dirname(__DIR__) . '/config/conn_db.php';
if(isset($_GET['p_id']) && isset($_GET['qty'])){
    $p_id = $_GET['p_id'];
    $qty = $_GET['qty'];
    $u_ip = $_SERVER['REMOTE_ADDR'];
    //查詢是否有相同的產品編號
    $query = "SELECT * FROM cart WHERE p_id=:value0 AND ip=:value1 AND orderid IS NULL";
    $queryParams = array(':value0' => $p_id, ':value1' => $_SERVER['REMOTE_ADDR']);
    $result = $link->prepare($query);
    $querySucceeded = $result->execute($queryParams);
    if($querySucceeded){
        if($result->rowCount()==0){
            $query = "INSERT INTO cart (p_id, qty, ip) VALUES (:value0,:value1,:value2)";
            $queryParams = array(':value0' => $p_id, ':value1' => $qty, ':value2' => $u_ip);
        }else{
            $cart_data = $result->fetch();
            if($cart_data['qty'] + $qty >49){
                $qty = 49; //設定產品數量上限為49件
            }else{
                $qty = $qty + $cart_data['qty'];
            }
            $query = "UPDATE cart SET qty = :value0 WHERE cart.cartid =:value1";
            $queryParams = array(':value0' => $qty, ':value1' => $cart_data['cartid']);
        }
        $statement = $link->prepare($query);
        $result = $statement->execute($queryParams);
        $retcode = array("c" => "1", "m" => "謝謝您！產品已加入購物車中。");
    }else{
        $retcode = array("c" => "0", "m" => "抱歉！資料無法寫入後台資料庫，請聯絡管理人員");
    }
    echo json_encode($retcode, JSON_UNESCAPED_UNICODE);
} 
return;
?>
