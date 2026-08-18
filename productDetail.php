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
        <div class="container my-5">
            <div class="row gx-5">
                <?php //require_once('goods_content.php'); 
                ?>

                <div class="col-md-6 mb-4 mb-md-0">
                    <?php
                    $SQLstring = sprintf("SELECT * FROM product,product_img WHERE product.p_id=product_img.p_id AND product_img.p_id=%d ORDER BY sort", $_GET['p_id']);
                    $img_rs = $link->query($SQLstring);
                    $images = [];
                    while ($row = $img_rs->fetch()) {
                        $images[] = $row;
                    }
                    $firstImg = !empty($images) ? $images[0] : null;
                    ?>

                    <div class="d-flex flex-row-reverse align-items-start gap-3">
                        <div style="flex: 1;" class="shadow-sm rounded overflow-hidden bg-light">
                            <?php if ($firstImg): ?>
                                <!-- 注意：這裡改為指向隱藏畫廊的獨立 class -->
                                <a id="mainImgLink" href="#" title="<?php echo $firstImg['p_name'] ?>" class="d-block">
                                    <img id="showGoods" name="showGoods" src="product_img/<?php echo $firstImg['img_file']; ?>" class="img-fluid w-100" style="object-fit: contain; max-height: 500px;" alt="<?php echo $firstImg['p_name'] ?>" title="<?php echo $firstImg['p_name'] ?>">
                                </a>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex flex-column gap-2" style="width: 70px; flex-shrink: 0; max-height: 500px; overflow-y: auto;">
                            <?php
                            if (!empty($images)) {
                                foreach ($images as $index => $img) {
                            ?>
                                    <div class="w-100">
                                        <!-- 移除 fancybox 類別，href 設為空，避免觸發燈箱 -->
                                        <a href=""
                                            title="<?php echo $img['p_name'] ?>"
                                            class="thumb-link <?php echo $index === 0 ? 'active' : ''; ?>"
                                            onclick="changeMainImg(event, 'product_img/<?php echo $img['img_file']; ?>')">
                                            <img src="product_img/<?php echo $img['img_file']; ?>" class="img-fluid rounded border thumb-img" alt="<?php echo $img['p_name'] ?>" title="<?php echo $img['p_name'] ?>">
                                        </a>
                                    </div>
                            <?php
                                }
                            } ?>
                        </div>

                    </div>
                </div>
                <div style="display: none;">
                    <?php
                    if (!empty($images)) {
                        foreach ($images as $img) {
                    ?>
                            <a href="product_img/<?php echo $img['img_file']; ?>" class="fb-gallery" rel="group" title="<?php echo $img['p_name']; ?>"></a>
                    <?php
                        }
                    }
                    ?>
                </div>

                <?php
                $img_rs->execute();
                $imgList = $img_rs->fetch();
                ?>
                <div class="col-md-6">
                    <div class="ps-md-4 d-flex flex-column h-100 justify-content-between">
                        <div>
                            <h1 class="h2 fw-bold text-dark mb-2"><?php echo $imgList['p_name'] ?></h1>
                            <p class="text-secondary fs-6 lh-lg mb-4" style="text-align: justify;"><?php echo $imgList['p_intro'] ?></p>
                            <hr class="text-muted opacity-25 my-4">
                            <div>
                                <span class="fs-4 fw-normal text-muted me-2">售價</span>
                                <h2 class="fw-bold m-0" style="color: #111111; font-size: 2.2rem;">$<?php echo $imgList['p_price'] ?></h2>
                            </div>

                            <div class="bg-light p-4 rounded-3 border border-light mt-auto">
                                <div class="d-flex flex-wrap align-items-center gap-3">

                                    <div style="width: 140px; flex-shrink: 0;">
                                        <div class="input-group">
                                            <button class="btn btn-outline-secondary px-3" type="button" onclick="changeQty(-1)">−</button>
                                            <input type="number" id="qty" name="qty" class="form-control text-center fw-bold border-secondary border-start-0 border-end-0" value="1" min="1" readonly>
                                            <button class="btn btn-outline-secondary px-3" type="button" onclick="changeQty(1)">+</button>
                                        </div>
                                    </div>

                                    <div class="flex-grow-1">
                                        <button name="button01" id="button01" type="button" class="btn btn-success btn-lg w-100 fw-bold shadow-sm color-success py-2-5" onclick="addcart(<?php echo $imgList['p_id']; ?>)">
                                            <i class="bi bi-cart-plus me-2"></i>加入購物車
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-5 pt-4 border-top">
                <div class="col-12">
                    <h3 class="h4 fw-bold mb-4 text-center text-md-start position-relative pb-2" style="border-bottom: 2px solid #000000; width: fit-content;">
                        商品詳情
                    </h3>
                    <div class="product-detail-content lh-lg mt-4 text-muted">
                        <?php echo $imgList['p_content']; ?>
                    </div>
                </div>
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
    <script>
        $(function() {
            // 1. 初始化隱藏的畫廊（綁定 rel="group"）
            $(".fb-gallery").fancybox({
                // 這裡可以放您的舊版 fancybox 設定參數
            });

            // 2. 點擊主圖時，模擬點擊隱藏畫廊中對應的圖片
            $("#mainImgLink").click(function(e) {
                e.preventDefault();

                // 尋找目前處於 active 狀態的縮圖是在第幾個項目 (index)
                var activeIndex = $(".thumb-link.active").closest('.w-100').index();

                // 觸發隱藏畫廊中相同順序的圖片點擊
                if (activeIndex !== -1) {
                    $(".fb-gallery").eq(activeIndex).click();
                }
            });

            // 原本的滑鼠滑入事件（若需要保留可留著，不需要可刪除）
            // $(".card .row.mt-2 .col-md-4 a").mouseover(function() {
            //     var imgsrc = $(this).children("img").attr("src");
            //     $("#showGoods").attr({
            //         "src": imgsrc
            //     });
            // });
        });

        // 3. 切換主圖大畫面的 Function
        function changeMainImg(event, imgSrc) {
            event.preventDefault(); // 阻止 <a> 標籤預設跳轉

            // 變更主圖的 src
            const mainImg = document.getElementById('showGoods');
            if (mainImg) {
                mainImg.src = imgSrc;
            }

            // 更新縮圖的選取高亮狀態
            document.querySelectorAll('.thumb-link').forEach(link => {
                link.classList.remove('active');
            });
            event.currentTarget.classList.add('active');
        }

        // 4. 數量加減控制
        function changeQty(amount) {
            const qtyInput = document.getElementById('qty');
            let currentQty = parseInt(qtyInput.value) || 1;
            currentQty += amount;
            if (currentQty < 1) {
                currentQty = 1;
            }
            qtyInput.value = currentQty;
        }
    </script>

</body>

</html>