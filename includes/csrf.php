<?php

const CSRF_TOKEN_SESSION_KEY = 'csrf_token';

function csrf_token(): string
{
    $token = $_SESSION[CSRF_TOKEN_SESSION_KEY] ?? null;

    if (!is_string($token) || preg_match('/\A[0-9a-f]{64}\z/D', $token) !== 1) {
        $token = bin2hex(random_bytes(32));
        $_SESSION[CSRF_TOKEN_SESSION_KEY] = $token;
    }

    return $token;
}

function csrf_validate($submittedToken): bool
{
    $expectedToken = $_SESSION[CSRF_TOKEN_SESSION_KEY] ?? null;

    return is_string($submittedToken)
        && is_string($expectedToken)
        && preg_match('/\A[0-9a-f]{64}\z/D', $expectedToken) === 1
        && hash_equals($expectedToken, $submittedToken);
}

function csrf_rotate(): string
{
    unset($_SESSION[CSRF_TOKEN_SESSION_KEY]);

    return csrf_token();
}
