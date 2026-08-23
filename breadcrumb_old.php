<?php
$level1Open = "";
$level2Open = "";
$level3Open = "";
if (isset($_GET['p_id']) && $_GET['p_id'] != "") {
    $SQLstring = sprintf("SELECT * FROM product,pyclass, (SELECT classid as upclassid, level as uplevel, cname as upcname FROM pyclass WHERE level=1) as uplevel WHERE product.classid=pyclass.classid AND pyclass.uplink=uplevel.upclassid AND product.p_id=%d", $_GET['p_id']);
    $classid_rs = $link->query($SQLstring);
    $data = $classid_rs->fetch();
    $level1Cname = $data['upcname'];
    $level1Upclassid = $data['upclassid'];
    $level1 = $data['uplevel'];

    $level1Open = '<li class="breadcrumb-item"><a href="productList.php?classid=' . $level1Upclassid . '&level=' . $level1 . '">' . $level1Cname . '</a></li>';

    $level2Cname = $data['cname'];
    $level2Classid = $data['classid'];
    $level2Open = '<li class="breadcrumb-item"><a href="productList.php?classid=' . $level2Classid . '">' . $level2Cname . '</a></li>';

    $level3Open = '<li class="breadcrumb-item active" aria-current="page">' . $data['p_name'] . '</li>';
} elseif (isset($_GET['search_name'])) {
    $level1Open = '<li class="breadcrumb-item active" aria-current="page">關鍵字查詢：' . $_GET['search_name'] . '</li>';
} elseif (isset($_GET['level']) && isset($_GET['classid'])) {
    $SQLstring = sprintf("SELECT * FROM pyclass WHERE level=%d AND classid=%d", $_GET['level'], $_GET['classid']);
    $classid_rs = $link->query($SQLstring);
    $data = $classid_rs->fetch();
    $level1Cname = $data['cname'];
    $level1Open = '<li class="breadcrumb-item active" aria-current="page">' . $level1Cname . '</li>';
} elseif (isset($_GET['classid'])) {
    $SQLstring = sprintf("SELECT * FROM pyclass WHERE level=2 AND classid=%d", $_GET['classid']);
    $classid_rs = $link->query($SQLstring);
    $data = $classid_rs->fetch();
    $level2Cname = $data['cname'];
    $level2Uplink = $data['uplink'];
    $level2Open = '<li class="breadcrumb-item active" aria-current="page">' . $level2Cname . '</li>';

    $SQLstring = sprintf("SELECT * FROM pyclass WHERE level=1 AND classid=%d", $level2Uplink);
    $classid_rs = $link->query($SQLstring);
    $data = $classid_rs->fetch();
    $level1Cname = $data['cname'];
    $level1 = $data['level'];
    $level1Open = '<li class="breadcrumb-item"><a href="productList.php?classid=' . $level2Uplink . '&level=' . $level1 . '">' . $level1Cname . '</a></li>';
}
?>
<nav aria-label="breadcrumb" class="ms-3 mt-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">首頁</a></li>
        <?php echo $level1Open . $level2Open . $level3Open ?>
    </ol>
</nav>