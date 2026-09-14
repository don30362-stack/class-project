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
    <style>
        table input:invalid {
            border: solid red 3px;
        }
    </style>
</head>

<body>
    <section id="header">
        <?php require_once(__DIR__ . '/components/navbar.php'); ?>
    </section>

    <section id="breadcrumb">
        <?php require_once(__DIR__ . '/components/breadcrumb.php'); ?>
    </section>

    <section id="content" class="cart-page py-4 py-md-5">
        <div class="container">
            <div id="cart-page-feedback" class="alert alert-danger d-none" role="alert" aria-live="polite"></div>
            <?php require_once(__DIR__ . '/components/cart_content.php'); ?>
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

<script>
    function showCartPageError(message, input) {
        const rowError = input?.closest('.cart-quantity')?.querySelector('.quantity-error');
        if (rowError) {
            rowError.textContent = message;
            rowError.style.display = 'block';
        } else {
            $('#cart-page-feedback').text(message).removeClass('d-none');
        }
    }

    $(".cart-quantity input").on('focus', function() {
        $(this).data('previous-value', this.value);
    });

    $(".cart-quantity input").change(function() {
        const input = this;
        const qty = Number($(this).val());
        const cartid = $(this).data('cartid');
        const error = this.closest('.cart-quantity').querySelector('.quantity-error');

        if (!Number.isInteger(qty) || qty < 1 || qty > 49) {
            showCartPageError('商品數量請輸入 1～49。', input);
            this.setAttribute('aria-invalid', 'true');
            return false;
        }

        error.style.display = '';
        error.textContent = '請輸入 1～49';
        this.setAttribute('aria-invalid', 'false');
        this.disabled = true;

        $.ajax({
            url: 'actions/change_qty.php',
            type: 'post',
            dataType: 'json',
            data: {
                cartid: cartid,
                qty: qty,
                csrf_token: document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            success: function(data) {

                if (data.c == true) {
                    window.location.reload();
                } else {
                    showCartPageError(data.m, input);
                }

            },
            error: function() {
                showCartPageError('購物車更新失敗，請稍後再試。', input);
            },
            complete: function() {
                input.disabled = false;
            }
        });

    });
</script>


</html>
