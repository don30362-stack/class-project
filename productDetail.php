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
            <?php require_once('productDetailContent.php'); ?>
        </div>
    </section>

    <hr>

    <section id="why-choose-us" class="py-4 py-md-5">
        <?php require_once('why_us.php'); ?>
    </section>

    <section id="pre-footer-cta" class="py-4 py-md-5 position-relative d-flex align-items-center">
        <?php require_once('footer_cta.php'); ?>
    </section>

    <section id="footer" class="py-4 py-md-5 text-white">
        <?php require_once('footer.php'); ?>
    </section>

    <?php require_once('jsfile.php'); ?>
    
    <script src="fancybox-2.1.7/source/jquery.fancybox.js"></script>
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