<?php
require_once __DIR__ . "/../actions/auth/session_init.php";

if (empty($_SESSION["user_id"])) {
    header("Location: /eventify/pages/user/loginpage_user.php");
    exit();
}