<?php require_once dirname(__DIR__) . '/config/conn_db.php'; ?>
<?php
if (isset($_GET['mode']) && $_GET['mode'] != '') {
    $mode = $_GET['mode'];
    $SQLstring = null;
    switch($mode){
        case 1:
            $SQLstring = "DELETE FROM cart WHERE cartid = :value0 AND orderid IS NULL";
            $SQLstringParams = array(':value0' => (int)$_GET['cartid']);
            break;
        case 2:
            $SQLstring = "DELETE FROM cart WHERE ip = :value0 AND orderid IS NULL";
            $SQLstringParams = array(':value0' => $_SERVER['REMOTE_ADDR']);
            break;
    }
    if ($SQLstring !== null) {
        $result = $link->prepare($SQLstring);
        $result->execute($SQLstringParams);
    }
}
$deleteGoto = "../cart.php";
header(sprintf("location:%s", $deleteGoto));

?>
