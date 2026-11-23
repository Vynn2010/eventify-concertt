<?php
if (session_status() === PHP_SESSION_NONE) {
    $lifetime = 60 * 60 * 24 * 30; // 30 hari

    ini_set('session.gc_maxlifetime', (string) $lifetime); //bedanya ada (string) $lifetime

    session_set_cookie_params([
        'lifetime' => $lifetime,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}