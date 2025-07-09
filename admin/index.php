<?php
// admin/index.php – Dashboard admin AlwafaHub
require_once __DIR__ . '/../lib/db.php'; // jika ada koneksi DB
require_once 'auth-check.php'; // proteksi login admin
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin – AlwafaHub</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header class="hero">
        <h1>Dashboard Admin</h1>
        <p>Kelola klien, event, dan kolase digital</p>
    </header>

    <main style="max-width:800px;margin:40px auto;">
        <section>
            <h2>📦 Manajemen Klien & Event</h2>
            <ul style="list-style:none;padding-left:0;">
                <li><a href="create-client.php" class="btn">➕ Buat Folder Klien Baru</a></li>
                <li><a href="sync/ftp-monitor.php" class="btn">🔄 Sinkronisasi Foto via FTP</a></li>
                <li><a href="sync/drive-sync.php" class="btn">☁️ Sinkronisasi Google Drive</a></li>
            </ul>
        </section>

        <section style="margin-top:40px;">
            <h2>🖼️ Preview & Template Kolase</h2>
            <ul style="list-style:none;padding-left:0;">
                <li><a href="templates/" class="btn">🎨 Manajemen Frame Kolase</a></li>
                <li><a href="kolase-preview/" class="btn">👀 Lihat Preview Kolase</a></li>
            </ul>
        </section>

        <section style="margin-top:40px;">
            <h2>🔐 Admin</h2>
            <ul style="list-style:none;padding-left:0;">
                <li><a href="logout.php" class="btn">🚪 Logout</a></li>
            </ul>
        </section>
    </main>

    <footer style="text-align:center;margin:40px 0;color:#555;">
        <p>&copy; <?= date('Y'); ?> AlwafaHub Admin Panel</p>
    </footer>
</body>
</html>
