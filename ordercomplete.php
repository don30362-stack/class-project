<?php

require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/cart.php';
if (!cartIsAuthenticated()) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/config/conn_db.php';
require_once __DIR__ . '/includes/php_lib.php';
require_once __DIR__ . '/includes/order.php';

$emailId = cartParsePositiveInteger($_SESSION['emailid'] ?? null);
$orderId = isset($_GET['orderid']) && is_string($_GET['orderid']) ? $_GET['orderid'] : '';
if ($emailId === null || preg_match('/\AHF[0-9]{12}\z/D', $orderId) !== 1) {
    http_response_code(404);
    exit('找不到訂單。');
}

$orderStatement = $link->prepare(
    'SELECT orderid, recipient_name, recipient_phone, postal_code,
            city_name, town_name, recipient_address, howpay, status,
            items_subtotal, shipping_fee, order_total, create_date
     FROM uorder
     WHERE orderid = :orderid AND emailid = :emailid
     LIMIT 1'
);
$orderStatement->execute(array(':orderid' => $orderId, ':emailid' => $emailId));
$order = $orderStatement->fetch(PDO::FETCH_ASSOC);
if (!$order) {
    http_response_code(404);
    exit('找不到訂單。');
}

$itemStatement = $link->prepare(
    'SELECT product_name, unit_price, quantity, subtotal
     FROM order_items WHERE orderid = :orderid ORDER BY id'
);
$itemStatement->execute(array(':orderid' => $orderId));
$orderItems = $itemStatement->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="zh-Hant">

<head>
    <?php require_once __DIR__ . '/includes/headfile.php'; ?>
</head>

<body>
    <section id="header">
        <?php require_once __DIR__ . '/components/navbar.php'; ?>
    </section>

    <main class="order-complete-page py-5">
        <div class="container">
            <header class="order-complete-header text-center">
                <span class="order-complete-icon"><i class="fa-solid fa-check"></i></span>
                <span class="order-complete-eyebrow">ORDER CONFIRMED</span>
                <h1>訂單已成立</h1>
                <p>感謝您的訂購，請保存以下訂單編號。</p>
            </header>

            <div class="order-complete-layout">
                <section class="order-complete-card">
                    <div class="order-complete-card-heading">
                        <span>ORDER</span>
                        <h2>訂單資訊</h2>
                    </div>
                    <dl class="order-info-list">
                        <div><dt>訂單編號</dt><dd><?= e($order['orderid']) ?></dd></div>
                        <div><dt>訂單日期</dt><dd><?= e($order['create_date']) ?></dd></div>
                        <div><dt>訂單狀態</dt><dd><span class="order-status"><?= e(orderStatusLabel((int)$order['status'])) ?></span></dd></div>
                        <div><dt>付款方式</dt><dd><?= e(orderPaymentLabel((int)$order['howpay'])) ?></dd></div>
                    </dl>
                </section>

                <section class="order-complete-card">
                    <div class="order-complete-card-heading">
                        <span>DELIVERY</span>
                        <h2>收件資訊</h2>
                    </div>
                    <dl class="order-info-list">
                        <div><dt>收件人</dt><dd><?= e($order['recipient_name']) ?></dd></div>
                        <div><dt>手機</dt><dd><?= e($order['recipient_phone']) ?></dd></div>
                        <div><dt>地址</dt><dd><?= e($order['postal_code'] . ' ' . $order['city_name'] . $order['town_name'] . $order['recipient_address']) ?></dd></div>
                    </dl>
                </section>
            </div>

            <section class="order-complete-card order-items-card">
                <div class="order-complete-card-heading">
                    <span>ITEMS</span>
                    <h2>訂購商品</h2>
                </div>
                <div class="order-complete-items">
                    <?php foreach ($orderItems as $item) { ?>
                        <article class="order-complete-item">
                            <div>
                                <h3><?= e($item['product_name']) ?></h3>
                                <p>NT$ <?= htmlspecialchars(orderDisplayMoney($item['unit_price']), ENT_QUOTES, 'UTF-8') ?> × <?= (int)$item['quantity'] ?></p>
                            </div>
                            <strong>NT$ <?= htmlspecialchars(orderDisplayMoney($item['subtotal']), ENT_QUOTES, 'UTF-8') ?></strong>
                        </article>
                    <?php } ?>
                </div>
                <div class="order-total-list">
                    <div><span>商品小計</span><strong>NT$ <?= htmlspecialchars(orderDisplayMoney($order['items_subtotal']), ENT_QUOTES, 'UTF-8') ?></strong></div>
                    <div><span>運費</span><strong>NT$ <?= htmlspecialchars(orderDisplayMoney($order['shipping_fee']), ENT_QUOTES, 'UTF-8') ?></strong></div>
                    <div class="order-grand-total"><span>訂單總額</span><strong>NT$ <?= htmlspecialchars(orderDisplayMoney($order['order_total']), ENT_QUOTES, 'UTF-8') ?></strong></div>
                </div>
            </section>

            <div class="order-complete-actions text-center">
                <a href="productList.php">繼續選購</a>
            </div>
        </div>
    </main>

    <section id="footer" class="py-4 py-md-5 text-white">
        <?php require_once __DIR__ . '/components/footer.php'; ?>
    </section>

    <?php require_once __DIR__ . '/includes/jsfile.php'; ?>
</body>
</html>
