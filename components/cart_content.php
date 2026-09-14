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

<div class="cart-header text-center">
    <span class="cart-eyebrow">SHOPPING BAG</span>
    <h1 class="cart-title">購物車</h1>
</div>


<?php if (!empty($cartRows)) { ?>

    <div class="row g-4 g-xl-5">

        <!-- 左側：商品清單 -->
        <div class="col-lg-8">

            <div class="cart-list">

                <?php foreach ($cartRows as $cart_data) { ?>

                    <?php
                    $subtotal = $cart_data['p_price'] * $cart_data['qty'];
                    $ptotal += $subtotal;
                    ?>

                    <div class="cart-item">

                        <!-- 商品圖片 -->
                        <a
                            href="productDetail.php?p_id=<?= $cart_data['p_id']; ?>"
                            class="cart-item-image">
                            <img
                                src="product_img/<?= e(safeImageFilename($cart_data['img_file'])); ?>"
                                alt="<?= e($cart_data['p_name']); ?>">
                        </a>


                        <!-- 商品資料 -->
                        <div class="cart-item-info">

                            <div class="cart-item-main">

                                <div>
                                    <span class="cart-product-code">
                                        PRODUCT <?= $cart_data['p_id']; ?>
                                    </span>

                                    <a
                                        href="productDetail.php?p_id=<?= $cart_data['p_id']; ?>"
                                        class="cart-product-name">
                                        <?= e($cart_data['p_name']); ?>
                                    </a>

                                    <div class="cart-product-price">
                                        NT$ <?= number_format($cart_data['p_price']); ?>
                                    </div>
                                </div>


                                <!-- 刪除 -->
                                <form method="POST" action="actions/shopcart_del.php" onsubmit="return confirm('確定從購物車移除此商品?');">
                                    <input type="hidden" name="mode" value="1">
                                    <input type="hidden" name="cartid" value="<?= $cart_data['cartid']; ?>">
                                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                    <button type="submit" class="cart-remove-btn" aria-label="移除商品">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </form>

                            </div>


                            <!-- 數量 / 小計 -->
                            <div class="cart-item-bottom">

                                <div class="cart-quantity">
                                    <label for="cart-quantity-<?= (int)$cart_data['cartid'] ?>">數量</label>

                                    <input
                                        id="cart-quantity-<?= (int)$cart_data['cartid'] ?>"
                                        type="number"
                                        value="<?= $cart_data['qty']; ?>"
                                        min="1"
                                        max="49"
                                        data-cartid="<?= (int)$cart_data['cartid']; ?>"
                                        aria-describedby="quantity-error-<?= (int)$cart_data['cartid'] ?>"
                                        required>

                                    <small id="quantity-error-<?= (int)$cart_data['cartid'] ?>" class="quantity-error" role="alert" aria-live="polite">
                                        請輸入 1～49
                                    </small>
                                </div>

                                <div class="cart-subtotal">
                                    <span>小計</span>
                                    <strong>
                                        NT$ <?= number_format($subtotal); ?>
                                    </strong>
                                </div>

                            </div>

                        </div>

                    </div>

                <?php } ?>

            </div>


            <!-- 清空購物車 -->
            <div class="cart-list-footer">
                <form method="POST" action="actions/shopcart_del.php" onsubmit="return confirm('確定清空購物車?');">
                    <input type="hidden" name="mode" value="2">
                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                    <button type="submit" class="cart-clear-btn">清空購物車</button>
                </form>
            </div>

        </div>


        <!-- 右側：訂單摘要 -->
        <div class="col-lg-4">

            <div class="cart-summary">

                <h2 class="cart-summary-title">
                    訂單摘要
                </h2>

                <div class="cart-summary-row">
                    <span>商品金額</span>
                    <span>NT$ <?= number_format($ptotal); ?></span>
                </div>

                <div class="cart-summary-row">
                    <span>運費</span>
                    <span>NT$ <?= number_format($shipping); ?></span>
                </div>

                <div class="cart-summary-divider"></div>

                <div class="cart-summary-total">
                    <span>總計</span>
                    <strong>
                        NT$ <?= number_format($ptotal + $shipping); ?>
                    </strong>
                </div>

                <a
                    href="checkout.php"
                    class="cart-checkout-btn">
                    前往結帳
                    <i class="fa-solid fa-arrow-right ms-2"></i>
                </a>

                <a
                    href="productList.php"
                    class="cart-continue-link">
                    繼續購物
                </a>

            </div>

        </div>

    </div>


<?php } else { ?>

    <div class="empty-cart">

        <div class="empty-cart-icon">
            <i class="fa-solid fa-bag-shopping"></i>
        </div>

        <h2>購物車目前是空的</h2>

        <p>
            還沒有找到適合的商品嗎？<br>
            到商品專區看看更多精選商品。
        </p>

        <a
            href="productList.php"
            class="empty-cart-btn">
            前往商品專區
        </a>

    </div>

<?php } ?>
