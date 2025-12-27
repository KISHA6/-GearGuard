<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function csrf_field() {
    echo '<input type="hidden" name="csrf_token" value="'.$_SESSION['csrf_token'].'">';
}

function verify_csrf() {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        http_response_code(403);
        echo json_encode(["status"=>"error","message"=>"Invalid CSRF token"]);
        exit;
    }
}
