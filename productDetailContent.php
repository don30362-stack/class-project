<div class="row gx-5">
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
                                <button class="btn btn-outline-dark px-3" type="button" onclick="changeQty(-1)">−</button>
                                <input type="number" id="qty" name="qty" class="form-control text-center fw-bold border-secondary border-start-0 border-end-0" value="1" min="1" readonly>
                                <button class="btn btn-outline-dark px-3" type="button" onclick="changeQty(1)">+</button>
                            </div>
                        </div>

                        <div class="flex-grow-1">
                            <button name="button01" id="button01" type="button" class="btn  custom-cart-btn-gold btn-lg w-100 fw-bold shadow-sm py-3" style="letter-spacing: 2px;" onclick="addcart(<?php echo $imgList['p_id']; ?>)">
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
        <h3 class="h4 fw-bold mb-4 ps-3 product-main-title">
            商品詳情
        </h3>
        <div class="product-detail-content lh-lg mt-4 text-muted">
            <?php echo $imgList['p_content']; ?>
        </div>
    </div>
</div>