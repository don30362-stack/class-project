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
if ($emailId === null) {
    header('Location: login.php');
    exit;
}

$orders = orderGetMemberOrders($link, $emailId);
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
                <span class="order-complete-eyebrow">MY ORDERS</span>
                <h1>我的訂單</h1>
                <p>查看已成立訂單與當時保存的交易內容。</p>
            </header>

            <?php if (empty($orders)) { ?>
                <section class="order-empty-state text-center">
                    <span class="order-empty-icon"><i class="fa-solid fa-receipt"></i></span>
                    <h2>目前還沒有訂單</h2>
                    <p>完成選購與結帳後，訂單會顯示在這裡。</p>
                    <a href="productList.php">前往商品專區</a>
                </section>
            <?php } else { ?>
                <div class="order-list" aria-label="訂單列表">
                    <?php foreach ($orders as $order) { ?>
                        <article class="order-list-card">
                            <div class="order-list-main">
                                <span class="order-list-label">訂單編號</span>
                                <h2><?= e($order['orderid']) ?></h2>
                                <time datetime="<?= e(str_replace(' ', 'T', $order['create_date'])) ?>"><?= e($order['create_date']) ?></time>
                            </div>

                            <dl class="order-list-meta">
                                <div>
                                    <dt>狀態</dt>
                                    <dd><span class="order-status"><?= e(orderStatusLabel((int)$order['status'])) ?></span></dd>
                                </div>
                                <div>
                                    <dt>付款方式</dt>
                                    <dd><?= e(orderPaymentLabel((int)$order['howpay'])) ?></dd>
                                </div>
                                <div>
                                    <dt>訂單總額</dt>
                                    <dd class="order-list-total">NT$ <?= e(orderDisplayMoney($order['order_total'])) ?></dd>
                                </div>
                            </dl>

                            <a class="order-detail-link" href="orderdetail.php?orderid=<?= rawurlencode($order['orderid']) ?>">
                                查看明細 <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </article>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </main>

    <section id="footer" class="py-4 py-md-5 text-white">
        <?php require_once __DIR__ . '/components/footer.php'; ?>
    </section>

    <?php require_once __DIR__ . '/includes/jsfile.php'; ?>
</body>
</html>
