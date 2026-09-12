<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');

    $httpsValue = isset($_SERVER['HTTPS']) ? strtolower((string)$_SERVER['HTTPS']) : '';
    $isHttpsRequest = in_array($httpsValue, array('on', '1'), true)
        || (isset($_SERVER['SERVER_PORT']) && (string)$_SERVER['SERVER_PORT'] === '443');
    $currentCookieParams = session_get_cookie_params();

    session_set_cookie_params(array(
        'lifetime' => $currentCookieParams['lifetime'],
        'path' => $currentCookieParams['path'],
        'domain' => $currentCookieParams['domain'],
        'secure' => $isHttpsRequest,
        'httponly' => true,
        'samesite' => 'Lax',
    ));

    session_start();
}
