<?php
function logActivity($conn, $action) {
    $user_id = $_SESSION['user_id'] ?? null;
    $ip = $_SERVER['REMOTE_ADDR'];

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO activity_logs (user_id, action, ip_address)
         VALUES (?, ?, ?)"
    );
    mysqli_stmt_bind_param($stmt, "iss", $user_id, $action, $ip);
    mysqli_stmt_execute($stmt);
}
