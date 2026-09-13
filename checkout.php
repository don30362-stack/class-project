<?php
require_once __DIR__ . '/includes/session.php';
require_once(__DIR__ . '/config/conn_db.php');
require_once(__DIR__ . '/includes/php_lib.php');
require_once(__DIR__ . '/includes/cart.php');
?>

<!DOCTYPE html>
<html lang="zh">

<head>
    <?php require_once(__DIR__ . '/includes/headfile.php'); ?>
</head>

<body>
    <section id="header">
        <?php require_once(__DIR__ . '/components/navbar.php'); ?>
    </section>

    <section id="breadcrumb">
        <?php require_once(__DIR__ . '/components/breadcrumb.php'); ?>
    </section>

    <section id="content" class="checkout-page py-4 py-md-5">
        <div class="container">

            <!-- 頁面標題 -->
            <div class="checkout-header text-center">
                <span class="checkout-eyebrow">CHECKOUT</span>
                <h1 class="checkout-title">結帳</h1>
            </div>


            <?php
            $ownerSql = cartOwnerSql(cartCurrentOwner(false), 'c');
            $SQLstring = "SELECT c.cartid, c.qty, p.p_id, p.p_name, p.p_price, pi.img_file
              FROM cart AS c
              INNER JOIN product AS p ON p.p_id = c.p_id
              INNER JOIN product_img AS pi ON pi.p_id = c.p_id AND pi.sort = 1
              WHERE c.orderid IS NULL AND " . $ownerSql['condition'] . "
              ORDER BY c.cartid DESC";
            $SQLstringParams = $ownerSql['params'];

            $cart_rs = $link->prepare($SQLstring);
            $cart_rs->execute($SQLstringParams);
            $cartRows = $cart_rs->fetchAll(PDO::FETCH_ASSOC);

            $ptotal = 0;
            $shipping = 100;
            ?>


            <?php if (!empty($cartRows)) { ?>

                <div class="row g-4 g-xl-5">

                    <!-- =====================================================
                     左側：配送與付款
                     ===================================================== -->
                    <div class="col-lg-7">

                        <!-- 配送資訊 -->
                        <section class="checkout-section">

                            <div class="checkout-section-header">

                                <div class="checkout-section-title">
                                    <span class="checkout-step">01</span>

                                    <div>
                                        <span class="checkout-section-en">
                                            DELIVERY
                                        </span>

                                        <h2>配送資訊</h2>
                                    </div>
                                </div>

                                <i class="fa-solid fa-truck checkout-section-icon"></i>

                            </div>


                            <div class="checkout-section-body">

                                <div class="checkout-preview-message">
                                    <i class="fa-solid fa-circle-info"></i>
                                    <div>
                                        <strong>結帳介面預覽</strong>
                                        <p>收件資訊與正式送出功能將於下一階段串接，目前不會建立訂單。</p>
                                    </div>
                                </div>

                            </div>

                        </section>


                        <!-- 付款方式 -->
                        <section class="checkout-section">

                            <div class="checkout-section-header">

                                <div class="checkout-section-title">
                                    <span class="checkout-step">02</span>

                                    <div>
                                        <span class="checkout-section-en">
                                            PAYMENT
                                        </span>

                                        <h2>付款方式</h2>
                                    </div>
                                </div>

                                <i class="fa-solid fa-hand-holding-dollar checkout-section-icon"></i>

                            </div>


                            <div class="checkout-section-body">

                                <div class="payment-options">

                                    <!-- 貨到付款 -->
                                    <label class="payment-option">

                                        <input
                                            type="radio"
                                            name="payment"
                                            value="cod"
                                            checked>

                                        <span class="payment-option-content">

                                            <span class="payment-radio"></span>

                                            <span class="payment-text">
                                                <strong>貨到付款</strong>
                                                <small>
                                                     商品送達時再行付款；正式下單功能尚未開放
                                                </small>
                                            </span>

                                            <i class="fa-solid fa-hand-holding-dollar"></i>

                                        </span>

                                    </label>

                                </div>

                            </div>

                        </section>


                        <!-- 訂購商品 -->
                        <section class="checkout-section">

                            <div class="checkout-section-header">

                                <div class="checkout-section-title">
                                    <span class="checkout-step">03</span>

                                    <div>
                                        <span class="checkout-section-en">
                                            ITEMS
                                        </span>

                                        <h2>訂購商品</h2>
                                    </div>
                                </div>

                                <i class="fa-solid fa-bag-shopping checkout-section-icon"></i>

                            </div>


                            <div class="checkout-product-list">

                                <?php foreach ($cartRows as $cart_data) { ?>

                                    <?php
                                    $subtotal =
                                        $cart_data['p_price'] *
                                        $cart_data['qty'];

                                    $ptotal += $subtotal;
                                    ?>

                                    <div class="checkout-product">

                                        <div class="checkout-product-image">

                                            <img
                                                src="product_img/<?= e(safeImageFilename($cart_data['img_file'])); ?>"
                                                alt="<?= e($cart_data['p_name']); ?>">

                                        </div>


                                        <div class="checkout-product-info">

                                            <div class="checkout-product-main">

                                                <div>

                                                    <span class="checkout-product-code">
                                                        PRODUCT <?= $cart_data['p_id']; ?>
                                                    </span>

                                                    <h3>
                                                        <?= e($cart_data['p_name']); ?>
                                                    </h3>

                                                    <span class="checkout-product-unit-price">
                                                        NT$ <?= number_format($cart_data['p_price']); ?>
                                                    </span>

                                                </div>


                                                <div class="checkout-product-qty">
                                                    × <?= $cart_data['qty']; ?>
                                                </div>

                                            </div>


                                            <div class="checkout-product-subtotal">

                                                <span>小計</span>

                                                <strong>
                                                    NT$ <?= number_format($subtotal); ?>
                                                </strong>

                                            </div>

                                        </div>

                                    </div>

                                <?php } ?>

                            </div>

                        </section>

                    </div>


                    <!-- =====================================================
                     右側：訂單摘要
                     ===================================================== -->
                    <div class="col-lg-5 col-xl-4 offset-xl-1">

                        <aside class="checkout-summary">

                            <span class="checkout-summary-eyebrow">
                                ORDER SUMMARY
                            </span>

                            <h2 class="checkout-summary-title">
                                訂單摘要
                            </h2>


                            <div class="checkout-summary-row">

                                <span>商品金額</span>

                                <span>
                                    NT$ <?= number_format($ptotal); ?>
                                </span>

                            </div>


                            <div class="checkout-summary-row">

                                <span>運費</span>

                                <span>
                                    NT$ <?= number_format($shipping); ?>
                                </span>

                            </div>


                            <div class="checkout-summary-divider"></div>


                            <div class="checkout-summary-total">

                                <span>應付總額</span>

                                <strong>
                                    NT$ <?= number_format($ptotal + $shipping); ?>
                                </strong>

                            </div>


                            <button
                                type="button"
                                class="checkout-submit-btn"
                                disabled>
                                下單功能尚未開放
                                <i class="fa-solid fa-clock ms-2"></i>
                            </button>


                            <p class="checkout-notice">
                                本頁目前僅供確認商品與結帳介面，
                                不會建立或送出訂單。
                            </p>


                            <a href="cart.php" class="checkout-back-link">
                                <i class="fa-solid fa-arrow-left me-2"></i>
                                返回購物車
                            </a>

                        </aside>

                    </div>

                </div>


            <?php } else { ?>

                <div class="checkout-empty">

                    <i class="fa-solid fa-bag-shopping"></i>

                    <h2>目前沒有可結帳的商品</h2>

                    <p>
                        請先將商品加入購物車，再進行結帳。
                    </p>

                    <a href="productList.php">
                        前往商品專區
                    </a>

                </div>

            <?php } ?>

        </div>
    </section>

    <section id="why-choose-us" class="py-4 py-md-5">
        <?php require_once(__DIR__ . '/components/why_us.php'); ?>
    </section>

    <section id="pre-footer-cta" class="py-5 position-relative d-flex align-items-center">
        <?php require_once(__DIR__ . '/components/footer_cta.php'); ?>
    </section>

    <section id="footer" class="py-4 py-md-5 text-white">
        <?php require_once(__DIR__ . '/components/footer.php'); ?>
    </section>

    <?php require_once(__DIR__ . '/includes/jsfile.php'); ?>

</body>

</html>
