<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('Connections/conn_db.php');
require_once('php_lib.php');
?>

<!DOCTYPE html>
<html lang="zh">

<head>
    <?php require_once('headfile.php'); ?>
</head>

<body>
    <section id="header">
        <?php require_once('navbar.php'); ?>
    </section>

    <section id="breadcrumb">
        <?php require_once('breadcrumb.php'); ?>
    </section>

    <section id="content" class="checkout-page py-4 py-md-5">
        <div class="container">

            <!-- 頁面標題 -->
            <div class="checkout-header text-center">
                <span class="checkout-eyebrow">CHECKOUT</span>
                <h1 class="checkout-title">結帳</h1>
            </div>


            <?php
            $SQLstring = "SELECT * FROM cart,product,product_img
                      WHERE ip='" . $_SERVER['REMOTE_ADDR'] . "'
                      AND orderid IS NULL
                      AND cart.p_id = product_img.p_id
                      AND cart.p_id = product.p_id
                      AND product_img.sort = 1
                      ORDER BY cartid DESC";

            $cart_rs = $link->query($SQLstring);

            $ptotal = 0;
            $shipping = 100;
            ?>


            <?php if ($cart_rs->rowCount() != 0) { ?>

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

                                <div class="recipient-info">

                                    <div class="recipient-row">
                                        <span class="recipient-label">收件人</span>
                                        <strong>李小明</strong>
                                    </div>

                                    <div class="recipient-row">
                                        <span class="recipient-label">聯絡電話</span>
                                        <span>0912-345-678</span>
                                    </div>

                                    <div class="recipient-row">
                                        <span class="recipient-label">配送地址</span>

                                        <span>
                                            407 台中市西屯區<br>
                                            中正路 1 號
                                        </span>
                                    </div>

                                </div>


                                <button
                                    type="button"
                                    class="checkout-outline-btn">
                                    更改收件資訊
                                </button>

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

                                <i class="fa-regular fa-credit-card checkout-section-icon"></i>

                            </div>


                            <div class="checkout-section-body">

                                <div class="payment-options">

                                    <!-- 信用卡 -->
                                    <label class="payment-option">

                                        <input
                                            type="radio"
                                            name="payment"
                                            value="credit"
                                            checked>

                                        <span class="payment-option-content">

                                            <span class="payment-radio"></span>

                                            <span class="payment-text">
                                                <strong>信用卡</strong>
                                                <small>
                                                    VISA / Mastercard / JCB
                                                </small>
                                            </span>

                                            <i class="fa-regular fa-credit-card"></i>

                                        </span>

                                    </label>


                                    <!-- ATM -->
                                    <label class="payment-option">

                                        <input
                                            type="radio"
                                            name="payment"
                                            value="atm">

                                        <span class="payment-option-content">

                                            <span class="payment-radio"></span>

                                            <span class="payment-text">
                                                <strong>ATM 轉帳</strong>
                                                <small>
                                                    完成訂單後取得付款資訊
                                                </small>
                                            </span>

                                            <i class="fa-solid fa-building-columns"></i>

                                        </span>

                                    </label>


                                    <!-- 貨到付款 -->
                                    <label class="payment-option">

                                        <input
                                            type="radio"
                                            name="payment"
                                            value="cod">

                                        <span class="payment-option-content">

                                            <span class="payment-radio"></span>

                                            <span class="payment-text">
                                                <strong>貨到付款</strong>
                                                <small>
                                                    商品送達時付款
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

                                <?php while ($cart_data = $cart_rs->fetch()) { ?>

                                    <?php
                                    $subtotal =
                                        $cart_data['p_price'] *
                                        $cart_data['qty'];

                                    $ptotal += $subtotal;
                                    ?>

                                    <div class="checkout-product">

                                        <div class="checkout-product-image">

                                            <img
                                                src="product_img/<?= $cart_data['img_file']; ?>"
                                                alt="<?= $cart_data['p_name']; ?>">

                                        </div>


                                        <div class="checkout-product-info">

                                            <div class="checkout-product-main">

                                                <div>

                                                    <span class="checkout-product-code">
                                                        PRODUCT <?= $cart_data['p_id']; ?>
                                                    </span>

                                                    <h3>
                                                        <?= $cart_data['p_name']; ?>
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
                                class="checkout-submit-btn">
                                確認送出訂單
                                <i class="fa-solid fa-arrow-right ms-2"></i>
                            </button>


                            <p class="checkout-notice">
                                送出訂單即表示您已確認配送資訊、
                                商品內容與付款方式。
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
        <?php require_once('why_us.php'); ?>
    </section>

    <section id="pre-footer-cta" class="py-5 position-relative d-flex align-items-center">
        <?php require_once('footer_cta.php'); ?>
    </section>

    <section id="footer" class="py-4 py-md-5 text-white">
        <?php require_once('footer.php'); ?>
    </section>

    <?php require_once('jsfile.php'); ?>

</body>

</html>