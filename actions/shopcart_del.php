<?php
require_once dirname(__DIR__) . '/includes/session.php';
require_once dirname(__DIR__) . '/includes/csrf.php';
require_once dirname(__DIR__) . '/config/conn_db.php';
require_once dirname(__DIR__) . '/includes/cart.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    exit;
}

if (!csrf_validate($_POST['csrf_token'] ?? null)) {
    http_response_code(403);
    exit('請求驗證失敗，請重新整理頁面後再試。');
}

$ownerSql = cartOwnerSql(cartCurrentOwner(false), 'c');
$mode = isset($_POST['mode']) && is_string($_POST['mode']) ? $_POST['mode'] : '';

if ($mode === '1') {
    $cartId = cartParsePositiveInteger($_POST['cartid'] ?? null);
    if ($cartId !== null) {
        $deleteSql = 'DELETE c FROM cart AS c
                      WHERE c.cartid = :cartid AND c.orderid IS NULL
                        AND ' . $ownerSql['condition'];
        $deleteStatement = $link->prepare($deleteSql);
        $deleteStatement->execute(array_merge(array(':cartid' => $cartId), $ownerSql['params']));
    }
} elseif ($mode === '2') {
    $deleteSql = 'DELETE c FROM cart AS c
                  WHERE c.orderid IS NULL AND ' . $ownerSql['condition'];
    $deleteStatement = $link->prepare($deleteSql);
    $deleteStatement->execute($ownerSql['params']);
}

header('Location: ../cart.php');
exit;
