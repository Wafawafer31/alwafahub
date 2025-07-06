<?php
// alwafa.php
session_start();
require_once 'config/database.php';
require_once 'config/settings.php';

// Ambil daftar klien dari file JSON
$clients = json_decode(file_get_contents('config/clients.json'), true);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Alwafa Hub - Realtime Photo Gallery</title>
  <link rel="stylesheet" href="css/style.css"/>
  <link rel="stylesheet" href="css/rtd.css"/>
</head>
<body>

  <!-- Header -->
  <header>
    <div class="logo">Alwafa Hub</div>
    <nav>
      <ul>
        <li><a href="alwafa.php">Beranda</a></li>
        <li><a href="portfolio/">Portofolio</a></li>
        <li><a href="blog/">Blog</a></li>
        <li><a href="admin/login.php">Login Admin</a></li>
      </ul>
    </nav>
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-text">
      <h1>Alwafa Hub</h1>
      <p>Layanan galeri foto real-time untuk momen spesial Anda.</p>
    </div>
  </section>

  <!-- Daftar Klien -->
  <section class="clients">
    <h2>Galeri Klien</h2>
    <div class="client-grid">
      <?php foreach ($clients as $client): ?>
        <div class="client-card">
          <a href="index.php?klien=<?= urlencode($client['id']) ?>">
            <img src="images/hero.jpg" alt="<?= htmlspecialchars($client['name']) ?>"/>
            <h3><?= htmlspecialchars($client['name']) ?></h3>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <p>&copy; <?= date('Y') ?> Alwafa Hub. All rights reserved.</p>
  </footer>

</body>
</html>
