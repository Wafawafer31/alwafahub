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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-800) 50%, var(--primary-900) 100%);">
        <div class="form-container">
            <div style="text-align: center; margin-bottom: 2rem;">
                <h1 style="color: var(--primary-600); margin-bottom: 0.5rem;">🔐 Admin Login</h1>
                <p style="color: var(--gray-600); margin-bottom: 0;">Masuk ke dashboard AlwafaHub</p>
            </div>
            
            <form method="post" id="loginForm">
                <div class="form-group">
                    <label for="username">👤 Username</label>
                    <input type="text" name="username" id="username" required placeholder="Masukkan username">
                </div>

                <div class="form-group">
                    <label for="password">🔑 Password</label>
                    <input type="password" name="password" id="password" required placeholder="Masukkan password">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                    🔓 Masuk ke Dashboard
                </button>
            </form>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error" style="margin-top: 1rem;">
                    <?= $error; ?>
                </div>
            <?php endif; ?>
            
            <div style="text-align: center; margin-top: 2rem; padding-top: 1rem; border-top: 1px solid var(--gray-200);">
                <p style="font-size: 0.9rem; color: var(--gray-500);">
                    🔒 Sistem keamanan AlwafaHub<br>
                    <small>Hanya admin yang memiliki akses</small>
                </p>
            </div>
        </div>
    </div>

    <script>
        // Form enhancement
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.classList.add('loading');
            submitBtn.innerHTML = '⏳ Memverifikasi...';
        });
        
        // Auto-focus username field
        document.getElementById('username').focus();
        
        // Enter key navigation
        document.getElementById('username').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('password').focus();
            }
        });
    </script>
    </main>
</body>
</html>
