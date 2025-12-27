<?php
session_start();
require_once("../config/db.php");

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if ($email == '' || $password == '') {
    echo "EMPTY";
    exit;
}

$hashed = md5($password);

$sql = "SELECT * FROM users WHERE email='$email' AND password='$hashed'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_role'] = $user['role'];

    echo "SUCCESS";
} else {
    echo "INVALID";
}
