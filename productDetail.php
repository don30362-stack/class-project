<?php
require_once __DIR__ . '/includes/session.php';
require_once(__DIR__ . '/config/conn_db.php');
require_once(__DIR__ . '/includes/php_lib.php');
$rawProductId = $_GET['p_id'] ?? null;
if (!is_string($rawProductId) || preg_match('/\A[1-9][0-9]*\z/D', $rawProductId) !== 1) {
    http_response_code(400);
    $productError = '商品編號格式不正確。';
} else {
    $productId = (int)$rawProductId;
    $productCheck = $link->prepare('SELECT 1 FROM product WHERE p_id = :p_id AND p_open = 1 LIMIT 1');
    $productCheck->execute(array(':p_id' => $productId));
    if (!$productCheck->fetchColumn()) {
        http_response_code(404);
        $productError = '找不到此商品，或商品目前未開放。';
    }
}
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
            <?php if (isset($productError)): ?>
                <div class="alert alert-warning" role="alert"><?= e($productError) ?></div>
            <?php else: ?>
                <?php require_once(__DIR__ . '/components/productDetailContent.php'); ?>
            <?php endif; ?>
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
            currentQty = Math.min(49, Math.max(1, currentQty + amount));
            qtyInput.value = currentQty;
            updateQuantityControls();
        }

        function updateQuantityControls() {
            const qtyInput = document.getElementById('qty');
            const quantityError = document.getElementById('product-quantity-error');
            let quantity = Number(qtyInput.value);
            const valid = Number.isInteger(quantity) && quantity >= 1 && quantity <= 49;
            quantityError.textContent = valid ? '' : '商品數量請輸入 1～49。';
            qtyInput.setAttribute('aria-invalid', valid ? 'false' : 'true');
            document.getElementById('qtyMinus').disabled = !valid || quantity <= 1;
            document.getElementById('qtyPlus').disabled = !valid || quantity >= 49;
        }

        document.getElementById('qty')?.addEventListener('input', updateQuantityControls);
        document.getElementById('qty')?.addEventListener('change', updateQuantityControls);
        updateQuantityControls();
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
