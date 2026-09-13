<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type:application/json;charset=utf-8');

require_once dirname(__DIR__) . '/includes/session.php';
require_once dirname(__DIR__) . '/includes/csrf.php';
require_once dirname(__DIR__) . '/config/conn_db.php';
require_once dirname(__DIR__) . '/includes/cart.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    echo json_encode(array('c' => '0', 'm' => '請使用 POST 送出請求。'), JSON_UNESCAPED_UNICODE);
    return;
}

if (!csrf_validate($_POST['csrf_token'] ?? null)) {
    http_response_code(403);
    echo json_encode(array('c' => '0', 'm' => '請求驗證失敗，請重新整理頁面後再試。'), JSON_UNESCAPED_UNICODE);
    return;
}

$cartId = cartParsePositiveInteger($_POST['cartid'] ?? null);
$quantity = cartParseQuantity($_POST['qty'] ?? null);

if ($cartId === null || $quantity === null) {
    echo json_encode(array('c' => '0', 'm' => '購物車項目或數量格式不正確。'), JSON_UNESCAPED_UNICODE);
    return;
}

$ownerSql = cartOwnerSql(cartCurrentOwner(false), 'c');

try {
    $link->beginTransaction();
    $selectSql = 'SELECT c.cartid FROM cart AS c
                  INNER JOIN product AS p ON p.p_id = c.p_id AND p.p_open = 1
                  WHERE c.cartid = :cartid AND c.orderid IS NULL
                    AND ' . $ownerSql['condition'] . '
                  LIMIT 1 FOR UPDATE';
    $selectStatement = $link->prepare($selectSql);
    $selectStatement->execute(array_merge(array(':cartid' => $cartId), $ownerSql['params']));

    if ($selectStatement->fetchColumn() === false) {
        $link->rollBack();
        echo json_encode(array('c' => '0', 'm' => '購物車項目不存在或無法修改。'), JSON_UNESCAPED_UNICODE);
        return;
    }

    $updateSql = 'UPDATE cart AS c SET c.qty = :qty
                  WHERE c.cartid = :cartid AND c.orderid IS NULL
                    AND ' . $ownerSql['condition'];
    $updateStatement = $link->prepare($updateSql);
    $updateStatement->execute(array_merge(array(':qty' => $quantity, ':cartid' => $cartId), $ownerSql['params']));
    $link->commit();

    echo json_encode(array('c' => '1', 'm' => '謝謝您！產品數量已更新。'), JSON_UNESCAPED_UNICODE);
} catch (Throwable $exception) {
    if ($link->inTransaction()) {
        $link->rollBack();
    }
    error_log('Cart quantity update failed: transaction_failed');
    echo json_encode(array('c' => '0', 'm' => '抱歉！資料無法寫入後台資料庫，請聯絡管理人員。'), JSON_UNESCAPED_UNICODE);
}
