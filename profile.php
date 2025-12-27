<?php
require_once "config/auth.php";
require_once "config/db.php";

$id = $_SESSION['user_id'];
$res = mysqli_query($conn, "SELECT name, email, role FROM users WHERE id=$id");
$user = mysqli_fetch_assoc($res);
?>
<!DOCTYPE html>
<html>
<head>
  <title>My Profile</title>
</head>
<body>

<h2>👤 My Profile</h2>

<p><b>Name:</b> <?= htmlspecialchars($user['name']) ?></p>
<p><b>Email:</b> <?= htmlspecialchars($user['email']) ?></p>
<p><b>Role:</b> <?= ucfirst($user['role']) ?></p>

<a href="change_password.php">Change Password</a>

</body>
</html>
