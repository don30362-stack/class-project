<?php

require_once dirname(__DIR__) . '/includes/session.php';
require_once dirname(__DIR__) . '/includes/csrf.php';
require_once dirname(__DIR__) . '/includes/escape.php';
require_once dirname(__DIR__) . '/includes/cart.php';
require_once dirname(__DIR__) . '/includes/order.php';
require_once dirname(__DIR__) . '/config/conn_db.php';

function createOrderRedirect(string $orderId): void
{
    header('Location: ../ordercomplete.php?orderid=' . rawurlencode($orderId), true, 303);
    exit;
}

function createOrderFailure(string $message, int $status): void
{
    http_response_code($status);
    ?><!doctype html>
    <html lang="zh-Hant">
    <head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>訂單未建立</title></head>
    <body>
        <script>alert(<?= jsValue($message) ?>); history.back();</script>
        <p><a href="../checkout.php">返回結帳頁</a></p>
    </body>
    </html><?php
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Allow: POST');
    createOrderFailure('請使用結帳頁送出訂單。', 405);
}

if (!cartIsAuthenticated()) {
    header('Location: ../login.php?sPath=checkout', true, 303);
    exit;
}

$emailId = cartParsePositiveInteger($_SESSION['emailid'] ?? null);
if ($emailId === null) {
    createOrderFailure('會員登入狀態無效，請重新登入。', 403);
}

$tokenHash = orderSubmittedTokenHash($_POST['submission_token'] ?? null);
if ($tokenHash === null) {
    createOrderFailure('送單憑證格式無效，請重新整理結帳頁後再試。', 400);
}

if (!csrf_validate($_POST['csrf_token'] ?? null)) {
    createOrderFailure('請求驗證失敗，請重新整理結帳頁後再試。', 403);
}

$existingOrderId = orderFindBySubmissionHash($link, $emailId, $tokenHash);
if ($existingOrderId !== null) {
    orderConsumeSubmissionToken($tokenHash);
    createOrderRedirect($existingOrderId);
}

if (!orderSessionTokenMatches($tokenHash)) {
    createOrderFailure('送單憑證已失效，請重新整理結帳頁後再試。', 409);
}

try {
    $orderId = orderCreateFromMemberCart($link, $emailId, $_POST, $tokenHash);
    orderConsumeSubmissionToken($tokenHash);
    createOrderRedirect($orderId);
} catch (InvalidArgumentException | DomainException $exception) {
    createOrderFailure($exception->getMessage(), 422);
} catch (Throwable $exception) {
    $existingOrderId = orderFindBySubmissionHash($link, $emailId, $tokenHash);
    if ($existingOrderId !== null) {
        orderConsumeSubmissionToken($tokenHash);
        createOrderRedirect($existingOrderId);
    }
    error_log('Order creation failed: transaction_failed');
    createOrderFailure('訂單建立失敗，購物車內容已保留，請稍後再試。', 500);
}
