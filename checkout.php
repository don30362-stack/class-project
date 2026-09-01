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

    <section id="content" class="mt-2">
        <div class="container">
            <?php //require_once('checkout.php'); 
            ?>
            <h3>電商藥粧：會員結帳作業</h3>
            <div class="row">
                <div class="card col">
                    <div class="card-header" style="color: #007bff;"><i class="fas fa-truck fa-flip-horizontal me-1"></i>
                        配送資訊
                    </div>
                    <div class="card-body">
                        <h4 class="card-title">收件人資訊：</h4>
                        <h5 class="card-title">姓名：李小明</h5>
                        <p class="card-text">電話：0912345678</p>
                        <p class="card-text">郵遞區號：407台中市西屯區</p>
                        <p class="card-text">地址：中正路1號</p>
                        <a href="#" class="btn btn-outline-primary">選擇其他收件人</a>
                    </div>
                </div>
                <div class="card col ms-3">
                    <div class="card-header" style="color: #000;"><i class="fas fa-credit-card me-1"></i>
                        付款方式
                    </div>
                    <div class="card-body">
                        <h4 class="card-title">收件人資訊：</h4>
                        <h5 class="card-title">姓名：李小明</h5>
                        <p class="card-text">電話：0912345678</p>
                        <p class="card-text">郵遞區號：407台中市西屯區</p>
                        <p class="card-text">地址：中正路1號</p>
                        <a href="#" class="btn btn-outline-primary">選擇其他收件人</a>
                    </div>
                </div>
            </div>
            <?php
            $SQLstring = "SELECT * FROM cart,product,product_img WHERE ip='" . $_SERVER['REMOTE_ADDR'] . "' AND orderid IS NULL AND cart.p_id = product_img.p_id AND cart.p_id = product.p_id AND product_img.sort = 1 ORDER BY cartid DESC";
            $cart_rs = $link->query($SQLstring);
            $ptotal = 0;
            ?>
            <?php if ($cart_rs->rowCount() != 0) { ?>
                <div class="table-responsive-md">
                    <table class="table table-hover mt-3">
                        <thead>
                            <tr class="table-warning">
                                <td width="10%">產品編號</td>
                                <td width="10%">圖片</td>
                                <td width="25%">名稱</td>
                                <td width="15%">價格</td>
                                <td width="10%">數量</td>
                                <td width="15%">小計</td>
                            </tr>
                        </thead>

                        <tbody>
                            <?php while ($cart_data = $cart_rs->fetch()) { ?>
                                <tr>
                                    <td><?php echo $cart_data['p_id']; ?></td>
                                    <td>
                                        <img src="product_img/<?php echo $cart_data['img_file']; ?>" alt="<?php echo $cart_data['p_name']; ?>" class="img-fluid">
                                    </td>
                                    <td><?php echo $cart_data['p_name']; ?></td>
                                    <td>
                                        <h4 class="color_e600a0 pt-1">$<?php echo $cart_data['p_price']; ?></h4>
                                    </td>
                                    <td style="min-width: 100px;">
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="qty[]" name="qty[]" value="<?php echo $cart_data['qty']; ?>" min="1" max="49" cartid="<?php echo $cart_data['cartid']; ?>" required disabled>
                                        </div>
                                    </td>
                                    <td>
                                        <h4 class="color_e600a0 pt-1">$<?php echo $cart_data['p_price'] * $cart_data['qty']; ?></h4>
                                    </td>
                                </tr>

                            <?php
                                $ptotal += $cart_data['p_price'] * $cart_data['qty'];
                            }
                            ?>

                        </tbody>

                        <tfoot>
                            <tr>
                                <td colspan="6">累計：<?= $ptotal ?></td>
                            </tr>
                            <tr>
                                <td colspan="6">運費：100</td>
                            </tr>
                            <tr>
                                <td colspan="6" class="color_red">總計：<?= $ptotal + 100 ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            <?php } else { ?>
                <div class="alert alert-warning" role="alert">
                    抱歉！目前購物車沒有相關產品。
                </div>
            <?php } ?>

            <!-- <div class="table-responsive-md">
                <table class="table table-hover mt-3">
                    <thead>
                        <tr class="text-bg-primary">
                            <td width="10%">產品編號</td>
                            <td width="10%">圖片</td>
                            <td width="30%">名稱</td>
                            <td width="15%">價格</td>
                            <td width="15%">數量</td>
                            <td width="20%">小計</td>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>
                                <img src="product_img/zoom-front-174388.webp" alt="Maybelline 媚比琳純淨礦物極效幻膚BB凝露 升級版 SPF 50/PA++++ 01白皙色" class="img-fluid">
                            </td>
                            <td>Maybelline 媚比琳純淨礦物極效幻膚BB凝露 升級版 SPF 50/PA++++ 01白皙色</td>
                            <td>
                                <h4 class="color_e600a0">$999</h4>
                            </td>
                            <td>10</td>
                            <td>
                                <h4 class="color_e600a0">$999</h4>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>
                                <img src="product_img/zoom-front-174388.webp" alt="Maybelline 媚比琳純淨礦物極效幻膚BB凝露 升級版 SPF 50/PA++++ 01白皙色" class="img-fluid">
                            </td>
                            <td>Maybelline 媚比琳純淨礦物極效幻膚BB凝露 升級版 SPF 50/PA++++ 01白皙色</td>
                            <td>
                                <h4 class="color_e600a0">$999</h4>
                            </td>
                            <td>10</td>
                            <td>
                                <h4 class="color_e600a0">$999</h4>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>
                                <img src="product_img/zoom-front-174388.webp" alt="Maybelline 媚比琳純淨礦物極效幻膚BB凝露 升級版 SPF 50/PA++++ 01白皙色" class="img-fluid">
                            </td>
                            <td>Maybelline 媚比琳純淨礦物極效幻膚BB凝露 升級版 SPF 50/PA++++ 01白皙色</td>
                            <td>
                                <h4 class="color_e600a0">$999</h4>
                            </td>
                            <td>10</td>
                            <td>
                                <h4 class="color_e600a0">$999</h4>
                            </td>
                        </tr>
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="7">累計：546566</td>
                        </tr>
                        <tr>
                            <td colspan="7">運費：566</td>
                        </tr>
                        <tr>
                            <td colspan="7" class="color_red">總計：546566</td>
                        </tr>
                        <tr>
                            <td colspan="7">
                                <button type="button" id="btn04" name="btn04" class="btn btn-danger"><i class="fas fa-cart-arrow-down"></i>確認結帳</button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div> -->
        </div>
    </section>

    <hr>

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