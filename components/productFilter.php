<?php
if (isset($productId)) {
    $ladderSQL = "SELECT c.uplink
                  FROM pyclass AS c
                  INNER JOIN product AS p ON p.classid = c.classid
                  WHERE p.p_id = :value0";
    $ladderSQLParams = array(':value0' => $productId);
    $classid_rs = $link->prepare($ladderSQL);
    $classid_rs->execute($ladderSQLParams);
    $data = $classid_rs->fetch();
    $ladder = $data ? $data['uplink'] : 1;
} elseif (isset($_GET['level'], $_GET['classid']) && is_string($_GET['level']) && is_string($_GET['classid']) && $_GET['level'] === '1' && ctype_digit($_GET['classid'])) {
    $ladder = (int)$_GET['classid'];
} elseif (isset($_GET['classid']) && is_string($_GET['classid']) && ctype_digit($_GET['classid'])) {
    $ladderSQL = "SELECT uplink FROM pyclass where level=2 AND classid=:value0";
    $ladderSQLParams = array(':value0' => (int)$_GET['classid']);
    $classid_rs = $link->prepare($ladderSQL);
    $classid_rs->execute($ladderSQLParams);
    $data = $classid_rs->fetch();
    $ladder = $data ? $data['uplink'] : 1;
} else {
    $ladder = 1;
}

$categoryTree = getCategoryTree($link);
?>

<div class="accordion" id="accordionExample">
    <?php
    foreach ($categoryTree['parents'] as $pyclass01_rows) {
        $i = $pyclass01_rows['classid'];
        $isCurrentParent = ($i == $ladder);
    ?>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button <?php echo $isCurrentParent ? 'active-parent' : 'collapsed'; ?>"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseOne<?php echo $i; ?>"
                    aria-expanded="<?php echo $isCurrentParent ? 'true' : 'false'; ?>"
                    aria-controls="collapseOne<?php echo $i; ?>">
                    <i class="fas <?= e($pyclass01_rows['fonticon']) ?> fa-lg fa-fw"></i>
                    <?= e($pyclass01_rows['cname']) ?>
                </button>
            </h2>

            <div id="collapseOne<?php echo $i; ?>"
                class="accordion-collapse collapse <?php echo $isCurrentParent ? 'show' : ''; ?>" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <ul class="category-list">

                        <?php
                        foreach ($categoryTree['children'][(int)$pyclass01_rows['classid']] ?? array() as $pyclass02_rows) {
                            $isCurrentChild = (isset($_GET['classid']) && $_GET['classid'] == $pyclass02_rows['classid']);
                        ?>
                            <li class="category-item">
                                <a href="productList.php?classid=<?php echo $pyclass02_rows['classid']; ?>"
                                    class="<?php echo $isCurrentChild ? 'active-child' : ''; ?>">
                                    <?= e($pyclass02_rows['cname']) ?>
                                </a>
                            </li>
                        <?php } ?>

                    </ul>
                </div>
            </div>
        </div>
    <?php
        // 移除無意義的 $i++，因為迴圈一開頭 $i 隨即會被資料庫的 classid 覆蓋
    } ?>
</div>
