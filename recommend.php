<?php
global $link;
$SQLstring = 'SELECT * FROM hot,product,product_img WHERE hot.p_id=product_img.p_id AND hot.p_id=product.p_id AND product_img.sort=1 ORDER BY h_sort';
$hot = $link->query($SQLstring);
?>
<div class="container">
    <div class="text-center mb-5">
        <span class="recommend-eyebrow">RECOMMENDED</span>
        <h2 class="recommend-heading">推薦商品</h2>
    </div>

    <div class="row row-cols-2 row-cols-md-4 g-4 g-lg-5">

        <?php while ($data = $hot->fetch()) { ?>
            <div class="col">
                <a href="productDetail.php?p_id=<?php echo $data['p_id']; ?>"
                    class="home-recommend-product text-decoration-none">

                    <div class="home-recommend-image ratio ratio-1x1">
                        <img
                            src="./product_img/<?php echo $data['img_file']; ?>"
                            alt="<?php echo $data['p_name']; ?>">
                    </div>

                    <div class="home-recommend-info text-center">
                        <h3 class="home-recommend-name">
                            <?php echo $data['p_name']; ?>
                        </h3>

                        <p class="home-recommend-price">
                            NT$ <?php echo number_format($data['p_price']); ?>
                        </p>
                    </div>

                </a>
            </div>
        <?php } ?>

    </div>
</div>