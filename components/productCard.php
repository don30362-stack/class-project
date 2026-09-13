<?php
$maxRows_rs = 12;
$pageNum_rs = 0;
if (isset($_GET['pageNum_rs']) && is_string($_GET['pageNum_rs'])) {
    $pageNum_rs = max(0, min((int)$_GET['pageNum_rs'], intdiv(PHP_INT_MAX, $maxRows_rs)));
}
$queryParams = array();
$queryFrom = ' FROM product AS p
               INNER JOIN product_img AS pi ON pi.p_id = p.p_id AND pi.sort = 1';
$queryWhere = ' WHERE p.p_open = 1';
if (isset($_GET['search_name']) && is_string($_GET['search_name'])) {
    $queryFrom .= ' INNER JOIN pyclass AS c ON c.classid = p.classid';
    $queryWhere .= ' AND p.p_name LIKE :search_name';
    $queryParams = array(':search_name' => "%" . $_GET['search_name'] . "%");
} elseif (isset($_GET['level'], $_GET['classid']) && is_string($_GET['level']) && is_string($_GET['classid']) && $_GET['level'] === '1' && ctype_digit($_GET['classid'])) {
    $queryFrom .= ' INNER JOIN pyclass AS c ON c.classid = p.classid';
    $queryWhere .= ' AND c.uplink = :classid';
    $queryParams = array(':classid' => (int)$_GET['classid']);
} elseif (isset($_GET['classid']) && is_string($_GET['classid']) && ctype_digit($_GET['classid'])) {
    $queryWhere .= ' AND p.classid = :classid';
    $queryParams = array(':classid' => (int)$_GET['classid']);
}

// 列表與總筆數共用相同的關聯、篩選條件；總筆數由資料庫計算。
$countStatement = $link->prepare('SELECT COUNT(*)' . $queryFrom . $queryWhere);
$countStatement->execute($queryParams);
$totalRows_rs = (int)$countStatement->fetchColumn();
$totalPages_rs = max(0, (int)ceil($totalRows_rs / $maxRows_rs) - 1);
$pageNum_rs = min($pageNum_rs, $totalPages_rs);
$startRow_rs = $pageNum_rs * $maxRows_rs;

$query = 'SELECT p.p_id, p.p_name, p.p_price, pi.img_file' . $queryFrom . $queryWhere
       . ' ORDER BY p.p_id DESC LIMIT :offset, :limit';
$pList01 = $link->prepare($query);
foreach ($queryParams as $parameter => $value) {
    $pList01->bindValue($parameter, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
}
$pList01->bindValue(':offset', $startRow_rs, PDO::PARAM_INT);
$pList01->bindValue(':limit', $maxRows_rs, PDO::PARAM_INT);
$pList01->execute();
$productRows = $pList01->fetchAll(PDO::FETCH_ASSOC);
$i = 1;
?>
<?php if (!empty($productRows)) { ?>
    <div class="row text-center gy-4 gx-3">
        <?php foreach ($productRows as $pList01_Rows) { ?>
            <div class="col-6 col-md-3">
                <div class="card h-100 rounded-0">
                    <a href="productDetail.php?p_id=<?php echo $pList01_Rows['p_id']; ?>">
                        <div class="ratio ratio-1x1 bg-light">
                            <img src="./product_img/<?= e(safeImageFilename($pList01_Rows['img_file'])) ?>" class="card-img-top" alt="<?= e($pList01_Rows['p_name']) ?>" title="<?= e($pList01_Rows['p_name']) ?>">
                        </div>
                    </a>
                    <div class="card-body p-2 p-md-3">
                        <h5 class="card-title"><?= e($pList01_Rows['p_name']) ?></h5>
                        <p class="card-price m-0"><span style="font-size: 80%; font-weight: 500; margin-right: 2px;">NT$</span> <?= number_format($pList01_Rows['p_price']) ?></p>
                    </div>
                </div>
            </div>
        <?php } 
        ?>
    </div>

    <div class="row mt-2">
        <?php
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
