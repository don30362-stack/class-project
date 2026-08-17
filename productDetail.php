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
    <link rel="stylesheet" href="fancybox-2.1.7/source/jquery.fancybox.css">
</head>

<body>
    <section id="header">
        <?php require_once('navbar.php'); ?>
    </section>

    <section id="breadcrumb">
        <?php require_once('breadcrumb.php'); ?>
    </section>

    <section id="categoryTitle">
        <?php require_once('categoryTitle.php'); ?>
    </section>

    <section id="content" class="mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2">
                    <?php require_once('productFilter.php'); ?>
                </div>

                <div class="col-md-10">
                    <?php //require_once('goods_content.php'); 
                    ?>
                    <div class="card mb-3">
                        <div class="row g-0">
                            <?php
                            $SQLstring = sprintf("SELECT * FROM product,product_img WHERE product.p_id=product_img.p_id AND product_img.p_id=%d ORDER BY sort", $_GET['p_id']);
                            $img_rs = $link->query($SQLstring);
                            $imgList = $img_rs->fetch();
                            ?>
                            <div class="col-md-4">
                                <img id="showGoods" name="showGoods" src="product_img/<?php echo $imgList['img_file']; ?>" class="img-fluid rounded-start" alt="<?php echo $imgList['p_name'] ?>" title="<?php echo $imgList['p_name'] ?>">
                                <div class="row mt-2">
                                    <?php do { ?>
                                        <div class="col-md-4">
                                            <a href="product_img/<?php echo $imgList['img_file']; ?>" title="<?php echo $imgList['p_name'] ?>" rel="group" class="fancybox">
                                                <img src="product_img/<?php echo $imgList['img_file']; ?>" class="img-fluid" alt="<?php echo $imgList['p_name'] ?>" title="<?php echo $imgList['p_name'] ?>">
                                            </a>
                                        </div>
                                    <?php } while ($imgList = $img_rs->fetch()); ?>
                                </div>
                            </div>
                            <?php
                            $img_rs->execute();
                            $imgList = $img_rs->fetch();
                            ?>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $imgList['p_name'] ?></h5>
                                    <p class="card-text"><?php echo $imgList['p_intro'] ?></p>
                                    <h4 class="color_e600a0">$<?php echo $imgList['p_price'] ?></h4>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="input-group input-group-lg">
                                                <span class="input-group-text color-success" id="inputGroup-sizing-lg">數量</span>
                                                <input type="number" id="qty" name="qty" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-lg" value="1">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <button name="button01" id="button01" type="button" class="btn btn-success btn-lg color-success" onclick="addcart(<?php echo $imgList['p_id']; ?>)">加入購物車</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo $imgList['p_content']; ?>
                </div>
            </div>
        </div>
    </section>

    <hr>

    <section id="why-choose-us" class="py-4 py-md-5">
        <?php require_once('why_us.php'); ?>
    </section>

    <section id="pre-footer-cta" class="py-4 py-md-5 position-relative d-flex align-items-center">
        <?php require_once('footer_cta.php'); ?>
    </section>

    <section id="footer">
        <?php require_once('footer.php'); ?>
    </section>

    <?php require_once('jsfile.php'); ?>
    <script src="fancybox-2.1.7/source/jquery.fancybox.js"></script>
    <script>
        $(function() {
            $(".card .row.mt-2 .col-md-4 a").mouseover(function() {
                var imgsrc = $(this).children("img").attr("src");
                $("#showGoods").attr({
                    "src": imgsrc
                });
            });

            $(".fancybox").fancybox();
        });

        function addcart(p_id) {
            var qty = $("#qty").val();
            if (qty <= 0) {
                alert("產品數量不得為或為負數，請再修改數量!");
                return (false);
            }
            if (qty == undefined) {
                qty = 1;
            } else if (qty >= 50) {
                alert("由於採購數量限制，產品數量將限制在50以下!");
                return (false);
            }

            $.ajax({
                url: 'addcart.php',
                type: 'get',
                dataType: 'json',
                data: {
                    p_id: p_id,
                    qty: qty
                },
                success: function(data) {
                    if (data.c == true) {
                        alert(dat.m);
                        window.location.reload();
                    } else {
                        alert(data.m);
                    }
                },
                error: function(data) {
                    alert("系統目前無法連接到後台資料庫。");
                }
            });
        }
    </script>

</body>

</html>