<?php
// index.php - Landing Page untuk Event Collage Generator

// Load konfigurasi umum (jika ada)
require_once __DIR__ . '/lib/db.php'; // opsional, kalau perlu akses DB
$config = [
    'judul' => 'Event Collage Generator',
    'deskripsi' => 'Pilih foto, buat kolase, dan abadikan momen acara dengan mudah.',
];

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $config['judul']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <h1><?= $config['judul']; ?></h1>
        <p><?= $config['deskripsi']; ?></p>
    </header>

    <main>
        <section>
            <h2>Mulai</h2>
            <p>Silakan login sebagai admin atau pilih event sebagai klien.</p>
            <a href="admin/login.php" class="btn">Login Admin</a>
            <a href="clients/" class="btn">Masuk Sebagai Klien</a>
        </section>
    </main>

    <footer>
        <p>&copy; <?= date('Y'); ?> Muhammad Studio. All rights reserved.</p>
    </footer>
</body>
</html>
