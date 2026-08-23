<?php
$maxRows_rs = 12;
$pageNum_rs = 0;
if (isset($_GET['pageNum_rs'])) {
    $pageNum_rs = $_GET['pageNum_rs'];
}
$startRow_rs = $pageNum_rs * $maxRows_rs;
if (isset($_GET['search_name'])) {
    //使用關鍵字查詢
    $queryFirst = sprintf('SELECT * FROM product,product_img,pyclass WHERE p_open=1 AND product_img.sort=1 AND product.p_id=product_img.p_id AND product.classid=pyclass.classid AND product.p_name LIKE "%s" ORDER BY product.p_id DESC', "%" . $_GET['search_name'] . "%");
} elseif (isset($_GET['level']) && $_GET['level'] == 1) {
    //使用第一層類別查詢
    $queryFirst = sprintf('SELECT * FROM product,product_img,pyclass WHERE p_open=1 AND product_img.sort=1 AND product.p_id=product_img.p_id AND product.classid=pyclass.classid AND pyclass.uplink="%d" ORDER BY product.p_id DESC', $_GET['classid']);
} elseif (isset($_GET['classid'])) {
    //使用第二層類別查詢
    $queryFirst = sprintf('SELECT * FROM product,product_img WHERE p_open=1 AND product_img.sort=1 AND product.p_id=product_img.p_id AND product.classid= "%d" ORDER BY product.p_id DESC', $_GET['classid']);
} else {
    //列出全部產品
    $queryFirst = sprintf('SELECT * FROM product,product_img WHERE p_open=1 AND product_img.sort=1 AND product.p_id=product_img.p_id  ORDER BY product.p_id DESC', $maxRows_rs);
}


$query = sprintf('%s LIMIT %d,%d', $queryFirst, $startRow_rs, $maxRows_rs);
$pList01 = $link->query($query);
$i = 1;
?>
<?php if ($pList01->rowCount() != 0) { ?>
    <div class="row text-center gy-4 gx-3">
        <?php while ($pList01_Rows = $pList01->fetch()) { ?>
            <div class="col-6 col-md-3">
                <div class="card h-100 rounded-0">
                    <a href="productDetail.php?p_id=<?php echo $pList01_Rows['p_id']; ?>">
                        <div class="ratio ratio-1x1 bg-light">
                            <img src="./product_img/<?= $pList01_Rows['img_file'] ?>" class="card-img-top" alt="<?= $pList01_Rows['p_name'] ?>" title="<?= $pList01_Rows['p_name'] ?>">
                        </div>
                    </a>
                    <div class="card-body p-2 p-md-3">
                        <h5 class="card-title"><?= $pList01_Rows['p_name'] ?></h5>
                        <p class="card-price m-0"><span style="font-size: 80%; font-weight: 500; margin-right: 2px;">NT$</span> <?= number_format($pList01_Rows['p_price']) ?></p>
                    </div>
                </div>
            </div>
        <?php } 
        ?>
    </div>

    <div class="row mt-2">
        <?php
        if (isset($_GET['totalRows_rs'])) {
            $totalRows_rs = $_GET['totalRows_rs'];
        } else {
            $all_rs = $link->query($queryFirst);
            $totalRows_rs = $all_rs->rowCount();
        }
        $totalPages_rs = ceil($totalRows_rs / $maxRows_rs) - 1;
        $prev_rs = '&laquo;';
        $next_rs = '&raquo;';
        $seprator = '|';
        $max_links = 20;
        $page_rs = buildNavigation($pageNum_rs, $totalPages_rs, $prev_rs, $next_rs, $seprator, $max_links, true, 3, 'rs');

        ?>

        <nav aria-label="Page navigation example">
            <ul class="pagination  justify-content-center my-4">
                <?php echo $page_rs[0] . $page_rs[1] . $page_rs[2]; ?>
            </ul>
        </nav>
    </div>

<?php } else { ?>
    <div class="alert alert-danger" role="alert">
        抱歉，沒有相關產品。
    </div>
<?php } ?>