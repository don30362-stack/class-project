<?php
if (isset($_GET['p_id'])) {
    $ladderSQL = sprintf("SELECT uplink FROM pyclass,product WHERE pyclass.classid=product.classid AND p_id=%d", $_GET['p_id']);
    $classid_rs = $link->query($ladderSQL);
    $data = $classid_rs->fetch();
    $ladder = $data ? $data['uplink'] : 1;
} elseif (isset($_GET['level']) && $_GET['level'] == 1) {
    $ladder = $_GET['classid'];
} elseif (isset($_GET['classid'])) {
    $ladderSQL = "SELECT uplink FROM pyclass where level=2 AND classid=" . $_GET['classid'];
    $classid_rs = $link->query($ladderSQL);
    $data = $classid_rs->fetch();
    $ladder = $data ? $data['uplink'] : 1;
} else {
    $ladder = 1;
}

$SQLstring = 'SELECT * FROM pyclass WHERE level=1 ORDER BY sort';
$pyclass01 = $link->query($SQLstring);
?>

<div class="accordion" id="accordionExample">
    <?php
    while ($pyclass01_rows = $pyclass01->fetch()) {
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
                    <i class="fas <?php echo $pyclass01_rows['fonticon']; ?> fa-lg fa-fw"></i>
                    <?php echo $pyclass01_rows['cname']; ?>
                </button>
            </h2>

            <div id="collapseOne<?php echo $i; ?>"
                class="accordion-collapse collapse <?php echo $isCurrentParent ? 'show' : ''; ?>" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <ul class="category-list">

                        <?php
                        $subSQL = sprintf("SELECT * FROM pyclass WHERE level=2 AND uplink=%d ORDER BY sort", $pyclass01_rows['classid']);
                        $pyclass02 = $link->query($subSQL);

                        while ($pyclass02_rows = $pyclass02->fetch()) {
                            $isCurrentChild = (isset($_GET['classid']) && $_GET['classid'] == $pyclass02_rows['classid']);
                        ?>
                            <li class="category-item">
                                <a href="productList.php?classid=<?php echo $pyclass02_rows['classid']; ?>"
                                    class="<?php echo $isCurrentChild ? 'active-child' : ''; ?>">
                                    <?php echo $pyclass02_rows['cname']; ?>
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