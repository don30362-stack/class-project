<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION['login'] = null;
$_SESSION['emailid'] = null;
$_SESSION['email'] = null;
$_SESSION['cname'] = null;

unset($_SESSION['login']);
unset($_SESSION['emailid']);
unset($_SESSION['email']);
unset($_SESSION['cname']);

$sPath = "index.php";
header(sprintf("Location: %s", $sPath));

?>
