<?php
$level1 = "";
$level2 = "";
$level3 = "";

if (isset($_GET['p_id']) && $_GET['p_id'] != "") {
    $p_id = (int)$_GET['p_id'];
    $SQLstring = sprintf(
        "SELECT main_class.cname AS main_name 
                FROM product 
                LEFT JOIN pyclass AS sub_class ON product.classid = sub_class.classid 
                LEFT JOIN pyclass AS main_class ON sub_class.uplink = main_class.classid 
                WHERE product.p_id = %d",
        $p_id
    );
    $query = $link->query($SQLstring);
    $data = $query->fetch();
    if ($data && isset($data['main_name'])) {
        $level3 = '<h1>' . htmlspecialchars($data['main_name'], ENT_QUOTES, 'UTF-8') . '</h1>';
    }
} elseif (isset($_GET['level']) && isset($_GET['classid']) && $_GET['classid'] != "") {
    $classid = (int)$_GET['classid'];
    $SQLstring = sprintf("SELECT cname AS main_name FROM pyclass WHERE classid = %d", $classid);
    $query = $link->query($SQLstring);
    $data = $query->fetch();
    if ($data && isset($data['main_name'])) {
        $level3 = '<h1>' . htmlspecialchars($data['main_name'], ENT_QUOTES, 'UTF-8') . '</h1>';
    }
} elseif (isset($_GET['classid']) && $_GET['classid'] != "") {
    $classid = (int)$_GET['classid'];
    $SQLstring = sprintf(
        "SELECT main_class.cname AS main_name 
                FROM pyclass AS sub_class 
                LEFT JOIN pyclass AS main_class ON sub_class.uplink = main_class.classid 
                WHERE sub_class.classid = %d",
        $classid
    );
    $query = $link->query($SQLstring);
    $data = $query->fetch();
    if ($data && isset($data['main_name'])) {
        $level3 = '<h1>' . htmlspecialchars($data['main_name'], ENT_QUOTES, 'UTF-8') . '</h1>';
    }
}
?>

<div class="text-center">
    <?php echo $level1 . $level2 . $level3; ?>
</div>