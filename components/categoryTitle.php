<?php
$level1 = "";
$level2 = "";
$level3 = "";

if (isset($productId)) {
    $p_id = $productId;
    $SQLstring = "SELECT main_class.cname AS main_name
                FROM product 
                LEFT JOIN pyclass AS sub_class ON product.classid = sub_class.classid 
                LEFT JOIN pyclass AS main_class ON sub_class.uplink = main_class.classid 
                WHERE product.p_id = :value0";
    $SQLstringParams = array(':value0' => (int)$p_id);
    $query = $link->prepare($SQLstring);
    $query->execute($SQLstringParams);
    $data = $query->fetch();
    if ($data && isset($data['main_name'])) {
        $level3 = '<h1>' . htmlspecialchars($data['main_name'], ENT_QUOTES, 'UTF-8') . '</h1>';
    }
} elseif (isset($_GET['level'], $_GET['classid']) && is_string($_GET['level']) && is_string($_GET['classid']) && ctype_digit($_GET['classid'])) {
    $classid = (int)$_GET['classid'];
    $SQLstring = "SELECT cname AS main_name FROM pyclass WHERE classid = :value0";
    $SQLstringParams = array(':value0' => (int)$classid);
    $query = $link->prepare($SQLstring);
    $query->execute($SQLstringParams);
    $data = $query->fetch();
    if ($data && isset($data['main_name'])) {
        $level3 = '<h1>' . htmlspecialchars($data['main_name'], ENT_QUOTES, 'UTF-8') . '</h1>';
    }
} elseif (isset($_GET['classid']) && is_string($_GET['classid']) && ctype_digit($_GET['classid'])) {
    $classid = (int)$_GET['classid'];
    $SQLstring = "SELECT main_class.cname AS main_name
                FROM pyclass AS sub_class 
                LEFT JOIN pyclass AS main_class ON sub_class.uplink = main_class.classid 
                WHERE sub_class.classid = :value0";
    $SQLstringParams = array(':value0' => (int)$classid);
    $query = $link->prepare($SQLstring);
    $query->execute($SQLstringParams);
    $data = $query->fetch();
    if ($data && isset($data['main_name'])) {
        $level3 = '<h1>' . htmlspecialchars($data['main_name'], ENT_QUOTES, 'UTF-8') . '</h1>';
    }
}
?>

<div class="text-center">
    <?php echo $level1 . $level2 . $level3; ?>
</div>
