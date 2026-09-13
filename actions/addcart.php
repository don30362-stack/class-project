<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

require_once dirname(__DIR__) . '/includes/session.php';
require_once dirname(__DIR__) . '/config/conn_db.php';
require_once dirname(__DIR__) . '/includes/cart.php';

$productId = cartParsePositiveInteger($_GET['p_id'] ?? null);
$requestedQuantity = cartParseQuantity($_GET['qty'] ?? null);

if ($productId === null || $requestedQuantity === null) {
    echo json_encode(array('c' => '0', 'm' => '商品或數量格式不正確。'), JSON_UNESCAPED_UNICODE);
    return;
}

$owner = cartCurrentOwner(true);
$ownerSql = cartOwnerSql($owner, 'c');

try {
    $link->beginTransaction();

    if (!cartProductIsPurchasable($link, $productId, true)) {
        $link->rollBack();
        echo json_encode(array('c' => '0', 'm' => '商品不存在或目前無法購買。'), JSON_UNESCAPED_UNICODE);
        return;
    }

    $selectSql = 'SELECT c.cartid, c.qty FROM cart AS c
                  WHERE c.p_id = :p_id AND c.orderid IS NULL
                    AND ' . $ownerSql['condition'] . '
                  ORDER BY c.cartid FOR UPDATE';
    $selectStatement = $link->prepare($selectSql);
    $selectStatement->execute(array_merge(array(':p_id' => $productId), $ownerSql['params']));
    $existingRows = $selectStatement->fetchAll(PDO::FETCH_ASSOC);

    if (empty($existingRows)) {
        if ($owner['type'] === 'member') {
            $insert = $link->prepare(
                'INSERT INTO cart (emailid, anonymous_token_hash, p_id, qty, ip)
                 VALUES (:emailid, NULL, :p_id, :qty, NULL)'
            );
            $insert->execute(array(':emailid' => $owner['emailid'], ':p_id' => $productId, ':qty' => $requestedQuantity));
        } else {
            $insert = $link->prepare(
                'INSERT INTO cart (emailid, anonymous_token_hash, p_id, qty, ip)
                 VALUES (NULL, :token_hash, :p_id, :qty, NULL)'
            );
            $insert->execute(array(':token_hash' => $owner['anonymous_token_hash'], ':p_id' => $productId, ':qty' => $requestedQuantity));
        }
    } else {
        $keepId = (int)$existingRows[0]['cartid'];
        $newQuantity = $requestedQuantity;
        foreach ($existingRows as $row) {
            $newQuantity = min(CART_MAX_QUANTITY, $newQuantity + max(0, (int)$row['qty']));
        }

        $updateSql = 'UPDATE cart AS c SET c.qty = :qty
                      WHERE c.cartid = :cartid AND c.orderid IS NULL
                        AND ' . $ownerSql['condition'];
        $updateStatement = $link->prepare($updateSql);
        $updateStatement->execute(array_merge(array(':qty' => max(1, $newQuantity), ':cartid' => $keepId), $ownerSql['params']));

        foreach (array_slice($existingRows, 1) as $duplicateRow) {
            $deleteSql = 'DELETE c FROM cart AS c
                          WHERE c.cartid = :cartid AND c.orderid IS NULL
                            AND ' . $ownerSql['condition'];
            $deleteStatement = $link->prepare($deleteSql);
            $deleteStatement->execute(array_merge(array(':cartid' => (int)$duplicateRow['cartid']), $ownerSql['params']));
        }
    }

    $link->commit();
    echo json_encode(array('c' => '1', 'm' => '謝謝您！產品已加入購物車中。'), JSON_UNESCAPED_UNICODE);
} catch (Throwable $exception) {
    if ($link->inTransaction()) {
        $link->rollBack();
    }
    error_log('Cart add failed: transaction_failed');
    echo json_encode(array('c' => '0', 'm' => '抱歉！資料無法寫入後台資料庫，請聯絡管理人員。'), JSON_UNESCAPED_UNICODE);
}
