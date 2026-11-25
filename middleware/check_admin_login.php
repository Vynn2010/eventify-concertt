<?php
require_once __DIR__ . "/../actions/auth/session_init.php";

if (empty($_SESSION["login_berhasil_admin"]) || $_SESSION["login_berhasil_admin"] !== true) {
    header("Location: /eventify/pages/admin/loginpage_admin.php");
    exit();
}