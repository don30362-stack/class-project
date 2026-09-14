<?php
require_once __DIR__ . '/includes/session.php';
require_once(__DIR__ . '/includes/cart.php');
if (!cartIsAuthenticated()) {
    header('Location: login.php?sPath=checkout');
    exit;
}
require_once(__DIR__ . '/config/conn_db.php');
require_once(__DIR__ . '/includes/php_lib.php');
require_once(__DIR__ . '/includes/order.php');

$checkoutEmailId = cartParsePositiveInteger($_SESSION['emailid'] ?? null);
if ($checkoutEmailId === null) {
    header('Location: login.php?sPath=checkout');
    exit;
}

$checkoutAddress = orderGetMemberAddress($link, $checkoutEmailId);
$submissionToken = orderCreateSubmissionToken();
$cityRows = $link->query('SELECT AutoNo, Name FROM city WHERE State = 0 ORDER BY cityOrder, AutoNo')->fetchAll(PDO::FETCH_ASSOC);
$selectedCityId = $checkoutAddress['city_id'] ?? null;
$selectedTownId = $checkoutAddress['town_id'] ?? null;
$townRows = array();
if ($selectedCityId !== null) {
    $townStatement = $link->prepare('SELECT townNo, Name FROM town WHERE AutoNo = :city_id AND State = 0 ORDER BY townNo');
    $townStatement->execute(array(':city_id' => $selectedCityId));
    $townRows = $townStatement->fetchAll(PDO::FETCH_ASSOC);
}
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
            $SQLstring = "SELECT c.cartid, c.qty, p.p_id, p.p_name, p.p_price,
                     (SELECT pi.img_file FROM product_img AS pi
                      WHERE pi.p_id = p.p_id ORDER BY pi.sort, pi.img_id LIMIT 1) AS img_file
              FROM cart AS c
              INNER JOIN product AS p ON p.p_id = c.p_id
              WHERE c.emailid = :emailid
                AND c.anonymous_token_hash IS NULL
                AND c.orderid IS NULL
              ORDER BY c.cartid DESC";

            $cart_rs = $link->prepare($SQLstring);
            $cart_rs->execute(array(':emailid' => $checkoutEmailId));
            $cartRows = $cart_rs->fetchAll(PDO::FETCH_ASSOC);

            $ptotal = 0;
            $shipping = 100;
            ?>


            <?php if (!empty($cartRows)) { ?>

                <form id="checkout-form" method="POST" action="actions/create_order.php">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="submission_token" value="<?= e($submissionToken) ?>">
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

                                <div class="checkout-recipient-grid">
                                    <div class="checkout-field checkout-field-full">
                                        <label for="recipient_name">收件人</label>
                                        <input type="text" id="recipient_name" name="recipient_name" maxlength="30" required
                                            value="<?= e($checkoutAddress['cname'] ?? '') ?>">
                                    </div>
                                    <div class="checkout-field checkout-field-full">
                                        <label for="recipient_phone">手機</label>
                                        <input type="tel" id="recipient_phone" name="recipient_phone" maxlength="10"
                                            pattern="09[0-9]{8}" inputmode="numeric" required
                                            value="<?= e($checkoutAddress['mobile'] ?? '') ?>">
                                    </div>
                                    <div class="checkout-field">
                                        <label for="city_id">縣市</label>
                                        <select id="city_id" name="city_id" required>
                                            <option value="">請選擇縣市</option>
                                            <?php foreach ($cityRows as $cityRow) { ?>
                                                <option value="<?= (int)$cityRow['AutoNo'] ?>" <?= (string)$selectedCityId === (string)$cityRow['AutoNo'] ? 'selected' : '' ?>><?= e($cityRow['Name']) ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="checkout-field">
                                        <label for="town_id">行政區</label>
                                        <select id="town_id" name="town_id" required>
                                            <option value="">請選擇行政區</option>
                                            <?php foreach ($townRows as $townRow) { ?>
                                                <option value="<?= (int)$townRow['townNo'] ?>" <?= (string)$selectedTownId === (string)$townRow['townNo'] ? 'selected' : '' ?>><?= e($townRow['Name']) ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="checkout-field checkout-field-full">
                                        <label for="postal_code">郵遞區號</label>
                                        <input type="text" id="postal_code" name="postal_code" maxlength="10" inputmode="numeric" required
                                            value="<?= e($checkoutAddress['myZip'] ?? '') ?>">
                                    </div>
                                    <div class="checkout-field checkout-field-full">
                                        <label for="recipient_address">詳細地址</label>
                                        <input type="text" id="recipient_address" name="recipient_address" maxlength="200" required
                                            value="<?= e($checkoutAddress['address'] ?? '') ?>">
                                    </div>
                                </div>

                                <div id="checkout-location-error" class="alert alert-danger d-none mt-3 mb-0" role="alert" aria-live="polite"></div>

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
                                                    value="cod"
                                                    checked
                                                    disabled>

                                        <span class="payment-option-content">

                                            <span class="payment-radio"></span>

                                            <span class="payment-text">
                                                <strong>貨到付款</strong>
                                                <small>
                                                     商品送達時再行付款
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
                                type="submit"
                                class="checkout-submit-btn">
                                <span class="checkout-submit-label">確認下單</span>
                                <i class="fa-solid fa-arrow-right ms-2"></i>
                            </button>


                            <p class="checkout-notice">
                                送出後將以貨到付款建立訂單，
                                商品價格與總額會由伺服器重新確認。
                            </p>


                            <a href="cart.php" class="checkout-back-link">
                                <i class="fa-solid fa-arrow-left me-2"></i>
                                返回購物車
                            </a>

                        </aside>

                    </div>

                </div>
                </form>


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

    <script>
        const checkoutForm = document.getElementById('checkout-form');
        const citySelect = document.getElementById('city_id');
        const townSelect = document.getElementById('town_id');
        const postalInput = document.getElementById('postal_code');
        const locationError = document.getElementById('checkout-location-error');

        function showLocationError(message) {
            if (!locationError) return;
            locationError.textContent = message;
            locationError.classList.remove('d-none');
        }

        function clearLocationError() {
            if (!locationError) return;
            locationError.textContent = '';
            locationError.classList.add('d-none');
        }

        if (citySelect && townSelect && postalInput) {
            citySelect.addEventListener('change', function() {
                clearLocationError();
                townSelect.innerHTML = '<option value="">請選擇行政區</option>';
                townSelect.disabled = true;
                postalInput.value = '';
                if (!this.value) {
                    townSelect.disabled = false;
                    return;
                }

                $.ajax({
                    url: 'api/Town_ajax.php',
                    type: 'post',
                    dataType: 'json',
                    data: { CNo: this.value },
                    success: function(data) {
                        if (data.c == true) {
                            townSelect.innerHTML = data.m;
                            townSelect.disabled = false;
                        } else {
                            townSelect.innerHTML = '<option value="">行政區載入失敗</option>';
                            showLocationError(typeof data.m === 'string' ? data.m : '行政區資料載入失敗，請稍後再試。');
                        }
                    },
                    error: function() {
                        townSelect.innerHTML = '<option value="">行政區載入失敗</option>';
                        showLocationError('行政區資料載入失敗，請稍後再試。');
                    }
                });
            });

            townSelect.addEventListener('change', function() {
                clearLocationError();
                postalInput.value = '';
                if (!this.value) return;

                $.ajax({
                    url: 'api/Zip_ajax.php',
                    type: 'get',
                    dataType: 'json',
                    data: { AutoNo: this.value },
                    success: function(data) {
                        if (data.c == true) {
                            postalInput.value = data.Post;
                        } else {
                            showLocationError(typeof data.m === 'string' ? data.m : '郵遞區號資料載入失敗，請稍後再試。');
                        }
                    },
                    error: function() {
                        postalInput.value = '';
                        showLocationError('郵遞區號資料載入失敗，請稍後再試。');
                    }
                });
            });
        }

        if (checkoutForm) {
            checkoutForm.addEventListener('submit', function() {
                const submitButton = checkoutForm.querySelector('.checkout-submit-btn');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.setAttribute('aria-busy', 'true');
                    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span><span class="checkout-submit-label">訂單處理中…</span>';
                }
            });
            window.addEventListener('pageshow', function() {
                const submitButton = checkoutForm.querySelector('.checkout-submit-btn');
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.removeAttribute('aria-busy');
                    submitButton.innerHTML = '<span class="checkout-submit-label">確認下單</span><i class="fa-solid fa-arrow-right ms-2"></i>';
                }
            });
        }
    </script>

</body>

</html>
