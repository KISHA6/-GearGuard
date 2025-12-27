<?php
require_once "config/auth.php";
require_once "config/db.php";
require_once "config/logger.php";

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $current = $_POST['current'];
    $new = $_POST['new'];
    $confirm = $_POST['confirm'];

    if ($new !== $confirm) {
        $msg = "Passwords do not match";
    } else {
        $id = $_SESSION['user_id'];
        $res = mysqli_query($conn, "SELECT password FROM users WHERE id=$id");
        $row = mysqli_fetch_assoc($res);

        if (!password_verify($current, $row['password'])) {
            $msg = "Current password is incorrect";
        } else {
            $hash = password_hash($new, PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE users SET password='$hash' WHERE id=$id");
            logActivity($conn, "Changed password");
            $msg = "Password updated successfully";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Change Password</title>
</head>
<body>

<h2>🔐 Change Password</h2>
<p><?= $msg ?></p>

<form method="POST">
  <input type="password" name="current" placeholder="Current password" required><br><br>
  <input type="password" name="new" placeholder="New password" required><br><br>
  <input type="password" name="confirm" placeholder="Confirm new password" required><br><br>
  <button type="submit">Update Password</button>
</form>

</body>
</html>
