<?php
session_start();
require_once "../config/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            header("Location: /gearguard/index.php");
            exit;
        } else {
            $error = "Invalid password";
        }
    } else {
        $error = "User not found";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login | GearGuard Pro</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <link rel="stylesheet" href="login.css" />
</head>
<body>

<div class="login-container">
  <div class="login-card glass">

    <div class="login-header">
      <i class="fas fa-robot"></i>
      <h1>GearGuard Pro</h1>
      <p>Smart Maintenance Management System</p>
    </div>

    <?php if ($error): ?>
      <p style="color:red;text-align:center;margin-bottom:10px;">
        <?= $error ?>
      </p>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" required />
      </div>

      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required />
      </div>

      <button type="submit" class="btn-primary">
        <i class="fas fa-sign-in-alt"></i> Login
      </button>

      <div class="login-footer">
        <a href="#">Forgot Password?</a>
      </div>
    </form>

  </div>
</div>

</body>
</html>
