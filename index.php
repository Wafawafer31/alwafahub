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
  <title>AlwafaHub – Kreativitas Digital & Fotografi Event</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
  <header class="hero">
    <h1>AlwafaHub</h1>
    <p>Kami mengabadikan momen dan membangun identitas visual bisnis Anda.</p>
    <a href="#layanan" class="btn">Lihat Layanan</a>
  </header>

  <section id="tentang">
    <h2>Tentang Kami</h2>
    <p>AlwafaHub adalah studio kreatif yang menggabungkan fotografi event, desain grafis, dan solusi digital untuk menghadirkan layanan visual berkualitas tinggi.</p>
  </section>

  <section id="layanan">
    <h2>Layanan Kami</h2>
    <ul>
      <li><strong>📸 Wedding & Event Photography</strong> – Dokumentasi momen spesial Anda dengan sentuhan profesional.</li>
      <li><strong🎨 Desain Grafis</strong> – Mulai dari logo, flyer, hingga identitas visual bisnis Anda.</li>
      <li><strong>💼 Branding & Identitas</strong> – Bangun citra bisnis Anda secara elegan dan konsisten.</li>
      <li><strong>🌐 Layanan Digital</strong> – Website, landing page, hingga sistem backend custom.</li>
    </ul>
  </section>

  <section id="portfolio">
    <h2>Contoh Karya</h2>
    <div class="gallery">
      <img src="assets/img/wedding-thumb.jpg" alt="Wedding Sample">
      <img src="assets/img/design-thumb.jpg" alt="Design Sample">
    </div>
  </section>

  <section id="testimoni">
    <h2>Apa Kata Klien Kami</h2>
    <blockquote>
      “AlwafaHub membuat acara kami tak terlupakan—foto dan desainnya luar biasa!”
      <cite>– Rani & Fajar</cite>
    </blockquote>
  </section>

  <footer>
    <p>Hubungi kami di <a href="mailto:info@alwafahub.com">info@alwafahub.com</a> atau <a href="https://wa.me/628XXXXXXXXX">WhatsApp</a></p>
    <p>&copy; <?= date('Y'); ?> AlwafaHub. All rights reserved.</p>
  </footer>
</body>
      </html>
