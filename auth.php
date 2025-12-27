<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/role.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: /gearguard/auth/login.php");
    exit;
}
