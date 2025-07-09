<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Ganti dengan validasi database di tahap produksi
    if ($username === 'admin' && $password === 'alwafa123') {
        $_SESSION['admin_logged_in'] = true;
        header('Location: index.php');
        exit;
    } else {
        $error = "❌ Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin – AlwafaHub</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <main style="max-width:400px;margin:80px auto;">
        <h2>🔐 Login Admin</h2>
        <form method="post">
            <label for="username">Username:</label><br>
            <input type="text" name="username" id="username" required style="width:100%;margin-top:8px;"><br><br>

            <label for="password">Password:</label><br>
            <input type="password" name="password" id="password" required style="width:100%;margin-top:8px;"><br><br>

            <button type="submit" class="btn">🔓 Masuk</button>
        </form>

        <?php if (!empty($error)): ?>
            <div style="margin-top:20px;color:red;"><?= $error; ?></div>
        <?php endif; ?>
    </main>
</body>
</html>
