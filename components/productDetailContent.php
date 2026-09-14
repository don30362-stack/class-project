<div class="row gx-2 gx-lg-5">
    <div class="col-md-6 mb-4 mb-md-0">
        <?php
        $SQLstring = "SELECT p.p_id, p.p_name, p.p_intro, p.p_price, p.p_content, pi.img_file
                      FROM product AS p
                      INNER JOIN product_img AS pi ON pi.p_id = p.p_id
                      WHERE p.p_id = :value0
                      ORDER BY pi.sort";
        $SQLstringParams = array(':value0' => $productId);
        $img_rs = $link->prepare($SQLstring);
        $img_rs->execute($SQLstringParams);
        $images = [];
        while ($row = $img_rs->fetch()) {
            $imageSize = @getimagesize(dirname(__DIR__) . '/product_img/' . basename($row['img_file']));
            $row['img_width'] = $imageSize[0] ?? 1600;
            $row['img_height'] = $imageSize[1] ?? 1200;
            $images[] = $row;
        }
        $firstImg = !empty($images) ? $images[0] : null;
        ?>

        <div class="d-flex flex-row-reverse align-items-start gap-3">
            <div class="product-main-media shadow-sm rounded overflow-hidden">
                <?php if ($firstImg): ?>
                    <a id="mainImgLink"
                        href="product_img/<?= e(safeImageFilename($firstImg['img_file'])) ?>"
                        data-gallery-index="0"
                        title="<?= e($firstImg['p_name']) ?>"
                        class="d-block">
                        <img id="showGoods" name="showGoods" src="product_img/<?= e(safeImageFilename($firstImg['img_file'])) ?>" class="product-main-image" alt="<?= e($firstImg['p_name']) ?>" title="<?= e($firstImg['p_name']) ?>">
                    </a>
                <?php endif; ?>
            </div>

            <div class="d-flex flex-column gap-2" style="width: 70px; flex-shrink: 0; max-height: 500px; overflow-y: auto;">
                <?php
                if (!empty($images)) {
                    foreach ($images as $index => $img) {
                ?>
                        <div class="w-100">
                            <?php $safeImage = safeImageFilename($img['img_file']); ?>
                            <a href="product_img/<?= e($safeImage) ?>"
                                title="<?= e($img['p_name']) ?>"
                                class="thumb-link <?php echo $index === 0 ? 'active' : ''; ?>"
                                onclick="changeMainImg(event, <?= e(jsValue('product_img/' . $safeImage)) ?>, <?= (int)$index ?>)">
                                <img src="product_img/<?= e($safeImage) ?>" class="img-fluid rounded border thumb-img" alt="<?= e($img['p_name']) ?>" title="<?= e($img['p_name']) ?>">
                            </a>
                        </div>
                <?php
                    }
                } ?>
            </div>

        </div>
    </div>
    <div id="product-gallery" style="display: none;">
        <?php
        if (!empty($images)) {
            foreach ($images as $img) {
        ?>
                <a href="product_img/<?= e(safeImageFilename($img['img_file'])) ?>"
                    data-pswp-width="<?php echo $img['img_width']; ?>"
                    data-pswp-height="<?php echo $img['img_height']; ?>"
                    data-pswp-caption="<?= e($img['p_name']) ?>"></a>
        <?php
            }
        }
        ?>
    </div>

    <?php
    $imgList = $firstImg;
    ?>
    <div class="col-md-6">
        <div class="ps-md-4 d-flex flex-column h-100 justify-content-between">
            <div>
                <h1 class="h2 fw-bold text-dark mb-2"><?= e($imgList['p_name']) ?></h1>
                <p class="text-secondary fs-6 lh-lg mb-4" style="text-align: justify;"><?= e($imgList['p_intro']) ?></p>
                <hr class="text-muted opacity-25 my-4">
                <div>
                    <span class="fs-4 fw-normal text-muted me-2">售價</span>
                    <h2 class="fw-bold m-0" style="color: #111111; font-size: 2.2rem;">$<?php echo $imgList['p_price'] ?></h2>
                </div>

                <div class="bg-light p-4 rounded-3 border border-light mt-auto">
                    <div class="d-flex flex-wrap align-items-center gap-3">

                        <div style="width: 140px; flex-shrink: 0;">
                            <div class="input-group">
                                <button id="qtyMinus" class="btn btn-outline-dark px-3" type="button" onclick="changeQty(-1)" disabled>−</button>
                                <input type="number" id="qty" name="qty" class="form-control text-center fw-bold border-secondary border-start-0 border-end-0" value="1" min="1" max="49" inputmode="numeric" aria-describedby="product-quantity-error">
                                <button id="qtyPlus" class="btn btn-outline-dark px-3" type="button" onclick="changeQty(1)">+</button>
                            </div>
                            <div id="product-quantity-error" class="text-danger small mt-2" role="alert" aria-live="polite"></div>
                        </div>

                        <div class="flex-grow-1">
                            <button name="button01" id="button01" type="button" class="btn  custom-cart-btn-gold btn-lg w-100 fw-bold shadow-sm py-3" style="letter-spacing: 2px;" onclick="addcart(<?php echo $imgList['p_id']; ?>)">
                                <i class="bi bi-cart-plus me-2"></i>加入購物車
                            </button>
                        </div>
                    </div>
                    <div id="cart-feedback" class="alert alert-danger d-none mt-3 mb-0" role="alert" aria-live="polite"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5 pt-4 border-top">
    <div class="col-12">
        <h3 class="h4 fw-bold mb-4 ps-3 product-main-title">
            商品詳情
        </h3>
        <div class="product-detail-content lh-lg mt-4 text-muted">
            <?php // Intentional trusted HTML from curated product data. Sanitize on write if general admin editing is added. ?>
            <?php echo $imgList['p_content']; ?>
        </div>
    </div>
</div>
