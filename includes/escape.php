<?php

function e($value): string
{
    return htmlspecialchars(is_string($value) ? $value : (string)($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function safeImageFilename($value, string $fallback = ''): string
{
    if (!is_string($value) || preg_match('/\A[a-zA-Z0-9][a-zA-Z0-9._-]{0,99}\z/D', $value) !== 1 || basename($value) !== $value) {
        return $fallback;
    }
    return $value;
}

function jsValue($value): string
{
    return json_encode($value, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
}
