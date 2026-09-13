<?php
require_once dirname(__DIR__) . '/includes/session.php';
require_once dirname(__DIR__) . '/config/conn_db.php';
require_once dirname(__DIR__) . '/includes/cart.php';

$ownerSql = cartOwnerSql(cartCurrentOwner(false), 'c');
$mode = isset($_GET['mode']) && is_string($_GET['mode']) ? $_GET['mode'] : '';

if ($mode === '1') {
    $cartId = cartParsePositiveInteger($_GET['cartid'] ?? null);
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
