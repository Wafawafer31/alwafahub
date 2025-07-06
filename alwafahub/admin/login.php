<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Login - AlwafaHub</title>
  <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
  <div class="login-container">
    <h2>Admin Login</h2>
    <form action="login.php" method="POST">
      <input type="text" name="username" placeholder="Username" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      <button type="submit" name="login">Login</button>
    </form>
  </div>

<?php
if (isset($_POST['login'])) {
  $user = $_POST['username'];
  $pass = $_POST['password'];

  // Login dummy: nanti diganti database
  if ($user === 'admin' && $pass === 'admin123') {
    $_SESSION['admin'] = true;
    header("Location: index.php");
    exit;
  } else {
    echo "<p style='color:red;text-align:center;'>Login gagal.</p>";
  }
}
?>
</body>
</html>
