<?php
require_once dirname(__DIR__) . '/includes/session.php';

$_SESSION = array();

if (ini_get('session.use_cookies')) {
    $cookieParams = session_get_cookie_params();
    setcookie(session_name(), '', array(
        'expires' => time() - 42000,
        'path' => $cookieParams['path'],
        'domain' => $cookieParams['domain'],
        'secure' => $cookieParams['secure'],
        'httponly' => $cookieParams['httponly'],
        'samesite' => $cookieParams['samesite'],
    ));
}

session_destroy();

$sPath = "../index.php";
header(sprintf("Location: %s", $sPath));
exit;
