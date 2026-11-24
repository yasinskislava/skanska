<?php

function startAppSession(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start([
            'cookie_httponly' => true,
            'cookie_samesite' => 'Lax',
        ]);
    }
}

function escapeHtml(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function setUserPreferenceCookie(string $name, string $value, int $ttlSeconds = 86400): void
{
    setcookie($name, $value, [
        'expires'  => time() + $ttlSeconds,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

function setFlashMessage(string $type, string $message): void
{
    startAppSession();
    $_SESSION['flash'] = [
        'type'    => $type,
        'message' => $message,
    ];
}

function consumeFlashMessage(): ?array
{
    startAppSession();
    if (!isset($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}