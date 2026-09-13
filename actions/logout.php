<?php
require_once dirname(__DIR__) . '/includes/session.php';
require_once dirname(__DIR__) . '/includes/csrf.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    exit;
}

if (!csrf_validate($_POST['csrf_token'] ?? null)) {
    http_response_code(403);
    exit('請求驗證失敗，請重新整理頁面後再試。');
}

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
