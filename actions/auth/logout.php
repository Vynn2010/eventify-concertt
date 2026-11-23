<?php
include __DIR__ . "/session_init.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$target = (($_SESSION['role'] ?? '') === 'admin')
    ? "../../pages/admin/loginpage_admin.php"
    : "../../pages/user/loginpage_user.php";

$_SESSION = [];
session_destroy();
header("Location: " . $target);
exit;
