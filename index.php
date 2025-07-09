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
  <meta name="description" content="AlwafaHub - Studio kreatif profesional untuk fotografi event, desain grafis, branding, dan solusi digital berkualitas tinggi.">
  <meta name="keywords" content="fotografi event, desain grafis, branding, wedding photography, digital solutions">
</head>
<body>
  <header class="hero">
    <div class="hero-content">
      <h1>AlwafaHub</h1>
      <p>Kami mengabadikan momen dan membangun identitas visual bisnis Anda dengan sentuhan profesional dan kreativitas tanpa batas.</p>
      <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; margin-top: 2rem;">
        <a href="#layanan" class="btn btn-primary">🚀 Lihat Layanan</a>
        <a href="#portfolio" class="btn btn-secondary">🎨 Portfolio</a>
      </div>
    </div>
  </header>

  <section id="tentang">
    <h2>Tentang Kami</h2>
    <div class="card">
      <p style="font-size: 1.1rem; text-align: center; margin-bottom: 0;">AlwafaHub adalah studio kreatif yang menggabungkan fotografi event, desain grafis, dan solusi digital untuk menghadirkan layanan visual berkualitas tinggi. Dengan pengalaman bertahun-tahun, kami berkomitmen memberikan hasil terbaik untuk setiap klien.</p>
    </div>
  </section>

  <section id="layanan">
    <h2>Layanan Kami</h2>
    <div class="services-grid">
      <div class="service-card">
        <span class="service-icon">📸</span>
        <h3>Wedding & Event Photography</h3>
        <p>Dokumentasi momen spesial Anda dengan sentuhan profesional dan artistik yang memukau.</p>
      </div>
      <div class="service-card">
        <span class="service-icon">🎨</span>
        <h3>Desain Grafis</h3>
        <p>Mulai dari logo, flyer, hingga identitas visual bisnis yang mencerminkan karakter brand Anda.</p>
      </div>
      <div class="service-card">
        <span class="service-icon">💼</span>
        <h3>Branding & Identitas</h3>
        <p>Bangun citra bisnis Anda secara elegan dan konsisten di semua platform komunikasi.</p>
      </div>
      <div class="service-card">
        <span class="service-icon">🌐</span>
        <h3>Layanan Digital</h3>
        <p>Website, landing page, hingga sistem backend custom yang mendukung pertumbuhan bisnis Anda.</p>
      </div>
    </div>
  </section>

  <section id="portfolio">
    <h2>Contoh Karya</h2>
    <p style="text-align: center; margin-bottom: 2rem;">Berikut adalah beberapa hasil karya terbaik kami yang telah dipercaya oleh berbagai klien.</p>
    <div class="gallery">
      <img src="https://images.pexels.com/photos/1024993/pexels-photo-1024993.jpeg?auto=compress&cs=tinysrgb&w=400" alt="Wedding Photography Sample" onclick="openModal(this)">
      <img src="https://images.pexels.com/photos/1779487/pexels-photo-1779487.jpeg?auto=compress&cs=tinysrgb&w=400" alt="Event Photography Sample" onclick="openModal(this)">
      <img src="https://images.pexels.com/photos/196644/pexels-photo-196644.jpeg?auto=compress&cs=tinysrgb&w=400" alt="Design Sample" onclick="openModal(this)">
      <img src="https://images.pexels.com/photos/1181263/pexels-photo-1181263.jpeg?auto=compress&cs=tinysrgb&w=400" alt="Branding Sample" onclick="openModal(this)">
    </div>
  </section>

  <section id="testimoni">
    <h2>Apa Kata Klien Kami</h2>
    <div class="testimonial">
      <blockquote>
        "AlwafaHub membuat acara pernikahan kami tak terlupakan. Setiap momen diabadikan dengan sempurna, dan hasil desain undangan serta dekorasi benar-benar memukau semua tamu. Profesionalisme dan kreativitas tim AlwafaHub sangat luar biasa!"
      </blockquote>
      <cite>Rani & Fajar - Wedding Client</cite>
    </div>
    
    <div class="testimonial">
      <blockquote>
        "Sebagai perusahaan startup, kami membutuhkan identitas visual yang kuat. AlwafaHub tidak hanya memberikan logo yang amazing, tapi juga strategi branding yang komprehensif. Highly recommended!"
      </blockquote>
      <cite>PT. Inovasi Digital - Corporate Client</cite>
    </div>
  </section>

  <footer>
    <p>📧 <a href="mailto:info@alwafahub.com">info@alwafahub.com</a> | 📱 <a href="https://wa.me/628XXXXXXXXX">WhatsApp</a> | 📍 Jakarta, Indonesia</p>
    <p>&copy; <?= date('Y'); ?> AlwafaHub. All rights reserved.</p>
  </footer>

  <!-- Modal for Gallery -->
  <div id="imageModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.9); cursor: pointer;" onclick="closeModal()">
    <img id="modalImage" style="margin: auto; display: block; max-width: 90%; max-height: 90%; margin-top: 5%;">
  </div>

  <script>
    // Gallery Modal
    function openModal(img) {
      document.getElementById('imageModal').style.display = 'block';
      document.getElementById('modalImage').src = img.src;
    }
    
    function closeModal() {
      document.getElementById('imageModal').style.display = 'none';
    }
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });
    
    // Intersection Observer for animations
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
        }
      });
    }, observerOptions);
    
    // Observe all cards and sections
    document.addEventListener('DOMContentLoaded', () => {
      const elements = document.querySelectorAll('.card, .service-card, .testimonial');
      elements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
      });
    });
  </script>
</body>
</html>
