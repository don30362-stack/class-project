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
    <style>
        table input:invalid {
            border: solid red 3px;
        }
    </style>
</head>

<body>
    <section id="header">
        <?php require_once('navbar.php'); ?>
    </section>

    <section id="breadcrumb">
        <?php require_once('breadcrumb.php'); ?>
    </section>

    <section id="content" class="cart-page py-4 py-md-5">
        <div class="container">
            <?php require_once('cart_content.php'); ?>
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

<script>
    $(".cart-quantity input").change(function() {

        var qty = $(this).val();
        const cartid = $(this).attr("cartid");

        if (qty < 1 || qty > 49) {
            alert("商品數量請輸入 1～49。");
            $(this).focus();
            return false;
        }

        $.ajax({
            url: 'change_qty.php',
            type: 'post',
            dataType: 'json',
            data: {
                cartid: cartid,
                qty: qty
            },
            success: function(data) {

                if (data.c == true) {
                    window.location.reload();
                } else {
                    alert(data.m);
                }

            },
            error: function() {
                alert("系統目前無法連接到後台資料庫");
            }
        });

    });
</script>


</html>