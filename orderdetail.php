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
if ($emailId === null || !orderIsValidOrderId($orderId)) {
    http_response_code(404);
    exit('找不到訂單。');
}

$order = orderGetMemberOrder($link, $emailId, $orderId);
if ($order === null) {
    http_response_code(404);
    exit('找不到訂單。');
}
$orderItems = orderGetMemberOrderItems($link, $emailId, $orderId);
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

    <main class="order-history-page py-5">
        <div class="container">
            <header class="order-history-header text-center">
                <span class="order-complete-eyebrow">ORDER DETAIL</span>
                <h1>訂單明細</h1>
                <p><?= e($order['orderid']) ?></p>
            </header>

            <div class="order-complete-layout">
                <section class="order-complete-card">
                    <div class="order-complete-card-heading">
                        <span>ORDER</span>
                        <h2>訂單資訊</h2>
                    </div>
                    <dl class="order-info-list">
                        <div><dt>訂單編號</dt><dd><?= e($order['orderid']) ?></dd></div>
                        <div><dt>建立日期</dt><dd><?= e($order['create_date']) ?></dd></div>
                        <div><dt>狀態</dt><dd><span class="order-status"><?= e(orderStatusLabel((int)$order['status'])) ?></span></dd></div>
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
                        <div><dt>郵遞區號</dt><dd><?= e($order['postal_code']) ?></dd></div>
                        <div><dt>縣市</dt><dd><?= e($order['city_name']) ?></dd></div>
                        <div><dt>鄉鎮</dt><dd><?= e($order['town_name']) ?></dd></div>
                        <div><dt>地址</dt><dd><?= e($order['recipient_address']) ?></dd></div>
                    </dl>
                </section>
            </div>

            <section class="order-complete-card order-items-card">
                <div class="order-complete-card-heading">
                    <span>ITEMS</span>
                    <h2>商品明細</h2>
                </div>
                <div class="order-complete-items">
                    <?php foreach ($orderItems as $item) { ?>
                        <article class="order-complete-item">
                            <div>
                                <h3><?= e($item['product_name']) ?></h3>
                                <p>成交單價 NT$ <?= e(orderDisplayMoney($item['unit_price'])) ?> × <?= (int)$item['quantity'] ?></p>
                            </div>
                            <strong>小計 NT$ <?= e(orderDisplayMoney($item['subtotal'])) ?></strong>
                        </article>
                    <?php } ?>
                </div>
                <div class="order-total-list">
                    <div><span>商品小計</span><strong>NT$ <?= e(orderDisplayMoney($order['items_subtotal'])) ?></strong></div>
                    <div><span>運費</span><strong>NT$ <?= e(orderDisplayMoney($order['shipping_fee'])) ?></strong></div>
                    <div class="order-grand-total"><span>訂單總額</span><strong>NT$ <?= e(orderDisplayMoney($order['order_total'])) ?></strong></div>
                </div>
            </section>

            <div class="order-complete-actions order-detail-actions text-center">
                <a href="orderlist.php"><i class="fa-solid fa-arrow-left me-2"></i>返回我的訂單</a>
            </div>
        </div>
    </main>

    <section id="footer" class="py-4 py-md-5 text-white">
        <?php require_once __DIR__ . '/components/footer.php'; ?>
    </section>

    <?php require_once __DIR__ . '/includes/jsfile.php'; ?>
</body>
</html>
