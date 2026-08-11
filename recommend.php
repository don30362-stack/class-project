<?php
global $link;
$SQLstring = 'SELECT * FROM hot,product,product_img WHERE hot.p_id=product_img.p_id AND hot.p_id=product.p_id AND product_img.sort=1 ORDER BY h_sort';
$hot = $link->query($SQLstring);
?>
<div class="container">
    <h2 class="text-center fw-bold mb-4">推薦商品</h2>
    <div class="row row-cols-2 row-cols-md-4 g-3 g-md-4 text-center">

        <?php while ($data = $hot->fetch()) { ?>
            <div class="col">
                <a href="#" class="text-decoration-none text-dark d-block">
                    <div class="card h-100 border rounded-0 shadow-sm">
                        <div class="ratio ratio-1x1 bg-light">
                            <img src="./product_img/<?php echo $data['img_file']; ?>" class="card-img-top object-fit-cover" alt="recommend<?php echo $data['h_sort']; ?>">
                        </div>
                        <div class="card-body p-2 p-md-3">
                            <h5 class="card-title fw-bold fs-6 mb-1"><?php echo $data['p_name']; ?></h5>
                            <p class="card-text fw-bold mb-0">$<?php echo $data['p_price']; ?></p>
                        </div>
                    </div>
                </a>
            </div>
        <?php } ?>
    </div>
</div>