<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . '/config/conn_db.php');
require_once(__DIR__ . '/includes/php_lib.php');
?>

<!DOCTYPE html>
<html lang="zh">

<head>
    <?php require_once(__DIR__ . '/includes/headfile.php'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/photoswipe@5.4.4/dist/photoswipe.css">
</head>

<body>
    <section id="header">
        <?php require_once(__DIR__ . '/components/navbar.php'); ?>
    </section>

    <section id="breadcrumb">
        <?php require_once(__DIR__ . '/components/breadcrumb.php'); ?>
    </section>

    <section id="categoryTitle">
        <?php require_once(__DIR__ . '/components/categoryTitle.php'); ?>
    </section>

    <section id="content" class="mt-5">
        <div class="container my-5">
            <?php require_once(__DIR__ . '/components/productDetailContent.php'); ?>
        </div>
    </section>

    <hr>

    <section id="why-choose-us" class="py-4 py-md-5">
        <?php require_once(__DIR__ . '/components/why_us.php'); ?>
    </section>

    <section id="pre-footer-cta" class="py-4 py-md-5 position-relative d-flex align-items-center">
        <?php require_once(__DIR__ . '/components/footer_cta.php'); ?>
    </section>

    <section id="footer" class="py-4 py-md-5 text-white">
        <?php require_once(__DIR__ . '/components/footer.php'); ?>
    </section>

    <?php require_once(__DIR__ . '/includes/jsfile.php'); ?>
    
    <script>
        function changeMainImg(event, imgSrc, imageIndex) {
            event.preventDefault();

            const mainImg = document.getElementById('showGoods');
            const mainImgLink = document.getElementById('mainImgLink');

            if (mainImg) {
                mainImg.src = imgSrc;
            }

            if (mainImgLink) {
                mainImgLink.href = imgSrc;
                mainImgLink.dataset.galleryIndex = imageIndex;
            }

            document.querySelectorAll('.thumb-link').forEach(link => {
                link.classList.remove('active');
            });
            event.currentTarget.classList.add('active');
        }

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

    <script type="module">
        import PhotoSwipeLightbox from 'https://cdn.jsdelivr.net/npm/photoswipe@5.4.4/dist/photoswipe-lightbox.esm.js';

        const productGallery = document.getElementById('product-gallery');
        const mainImgLink = document.getElementById('mainImgLink');

        if (productGallery && mainImgLink) {
            const productLightbox = new PhotoSwipeLightbox({
                gallery: productGallery,
                children: 'a',
                pswpModule: () => import('https://cdn.jsdelivr.net/npm/photoswipe@5.4.4/dist/photoswipe.esm.js')
            });

            productLightbox.init();

            mainImgLink.addEventListener('click', function(event) {
                event.preventDefault();

                const imageIndex = Number(mainImgLink.dataset.galleryIndex || 0);

                productLightbox.loadAndOpen(imageIndex, {
                    gallery: productGallery
                });
            });
        }
    </script>

</body>

</html>
