<?php
$config = json_decode(file_get_contents(__DIR__ . '/config.json'), true);
$thumbDir = __DIR__ . '/thumbs/';
$selectedDir = __DIR__ . '/selected/';
$outputDir = __DIR__ . '/output/';

// Get all thumbnails and group by time
$thumbs = array_filter(scandir($thumbDir), fn($f) => preg_match('/\.jpe?g$/i', $f));
$selected = $config['selected_photos'] ?? [];

// Group photos by time (simulate time-based grouping)
$photoGroups = [];
$timeSlots = ['08:30-09:00', '09:30-10:00', '10:00-10:30', '11:00-11:30', '14:00-14:30', '15:30-16:00'];

foreach ($thumbs as $index => $thumb) {
    $timeSlot = $timeSlots[$index % count($timeSlots)];
    $timeClass = 'time-' . str_replace(':', '-', str_replace('-', '-', $timeSlot));
    
    if (!isset($photoGroups[$timeSlot])) {
        $photoGroups[$timeSlot] = [
            'class' => $timeClass,
            'photos' => []
        ];
    }
    $photoGroups[$timeSlot]['photos'][] = $thumb;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected = $_POST['selected'] ?? [];
    $config['selected_photos'] = $selected;
    file_put_contents(__DIR__ . '/config.json', json_encode($config, JSON_PRETTY_PRINT));
    echo "<script>showSuccessMessage('✅ Foto berhasil dipilih. Siap buat kolase!');</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="AlwafaHub">
    <link rel="shortcut icon" href="../../assets/favicon.png">
    
    <meta name="description" content="<?= htmlspecialchars($config['event_title']) ?> - AlwafaHub Professional Photo Gallery" />
    <meta name="keywords" content="photo gallery, event photography, collage, download" />
    
    <!-- Bootstrap & External Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="../../assets/css/client-gallery.css">
    
    <title><?= htmlspecialchars($config['event_title']) ?> - AlwafaHub Gallery</title>
</head>
<body>
    <!-- Loading Overlay -->
    <div id="overlayer">
        <div class="loader">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="../../">
                <i class="bi bi-camera-fill me-2"></i>
                AlwafaHub
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#gallery-section">
                            <i class="bi bi-images me-1"></i>Gallery
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#collage-section">
                            <i class="bi bi-grid-3x3-gap me-1"></i>Kolase
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../admin/">
                            <i class="bi bi-gear me-1"></i>Admin
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="hero-overlay"></div>
        <div class="hero-slant"></div>
        <div class="container">
            <div class="row align-items-center justify-content-center min-vh-100">
                <div class="col-lg-8 text-center hero-content">
                    <div class="hero-logo mb-4" data-aos="zoom-in">
                        <i class="bi bi-camera-reels display-1 text-white"></i>
                    </div>
                    <h1 class="hero-title text-white mb-4" data-aos="fade-up" data-aos-delay="100">
                        <?= htmlspecialchars($config['event_title']) ?>
                    </h1>
                    <p class="hero-subtitle text-white-50 mb-4" data-aos="fade-up" data-aos-delay="200">
                        Pilih foto terbaik Anda dan buat kolase yang menakjubkan
                    </p>
                    <div class="hero-meta text-white-50 mb-5" data-aos="fade-up" data-aos-delay="300">
                        <i class="bi bi-calendar-event me-2"></i>
                        <?= date('d F Y', strtotime($config['event_date'])) ?>
                        <span class="mx-3">|</span>
                        <i class="bi bi-person-circle me-2"></i>
                        <?= htmlspecialchars($config['client_name']) ?>
                    </div>
                    <a href="#gallery-section" class="btn btn-primary btn-lg" data-aos="fade-up" data-aos-delay="400">
                        <i class="bi bi-images me-2"></i>
                        Lihat Gallery
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Gallery Section -->
    <div class="site-section" id="gallery-section">
        <div class="container">
            <!-- QR Code Event -->
            <div class="text-center mb-4">
                <h5>Akses Galeri via QR Code</h5>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=<?= urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>" alt="QR Event" />
                <div class="small text-muted">Scan untuk akses galeri ini</div>
            </div>
            <!-- Filter Buttons -->
            <div class="filters text-center mb-5" data-aos="fade-up">
                <ul class="filter-list">
                    <li class="active" data-filter="*">
                        <i class="bi bi-grid-3x3-gap me-2"></i>Semua Foto
                    </li>
                    <?php foreach ($photoGroups as $timeSlot => $group): ?>
                    <li data-filter=".<?= $group['class'] ?>">
                        <i class="bi bi-clock me-2"></i><?= $timeSlot ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Bulk Download ZIP Button -->
            <div class="text-center mb-4">
                <a href="download_zip.php" class="btn btn-outline-primary"><i class="bi bi-file-zip"></i> Download Semua Foto (ZIP)</a>
            </div>

            <!-- Photo Gallery Grid -->
            <div class="photo-gallery" data-aos="fade-up" data-aos-delay="200">
                <?php foreach ($photoGroups as $timeSlot => $group): ?>
                    <div class="group-thumb <?= $group['class'] }" 
                         onclick='showPopup(<?= json_encode($group['photos']) ?>, "<?= $timeSlot ?>")'>
                        <div class="group-image">
                            <img src="thumbs/<?= $group['photos'][0] }" alt="<?= $timeSlot } Photos">
                            <div class="group-overlay">
                                <i class="bi bi-eye-fill"></i>
                                <span>Lihat Foto</span>
                            </div>
                        </div>
                        <div class="group-badges">
                            <span class="badge bg-primary">
                                <i class="bi bi-clock me-1"></i><?= $timeSlot ?>
                            </span>
                            <span class="badge bg-dark">
                                <i class="bi bi-images me-1"></i><?= count($group['photos']) ?> Foto
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    </div>

    <!-- Collage Section -->
    <div class="site-section bg-light" id="collage-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="section-title mb-4" data-aos="fade-up">
                        <i class="bi bi-grid-3x3-gap me-3"></i>
                        Buat Kolase Anda
                    </h2>
                    <p class="section-subtitle mb-5" data-aos="fade-up" data-aos-delay="100">
                        Pilih <?= count($config['template_layout']['slots']) ?> foto terbaik untuk membuat kolase yang menakjubkan
                    </p>
                    
                    <!-- Selection Status -->
                    <div class="selection-status mb-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="status-card">
                            <div class="status-info">
                                <span class="status-count" id="selectedCount"><?= count($selected) ?></span>
                                <span class="status-total">/ <?= count($config['template_layout']['slots']) ?></span>
                                <span class="status-label">foto dipilih</span>
                            </div>
                            <div class="status-progress">
                                <div class="progress">
                                    <div class="progress-bar" id="selectionProgress" 
                                         style="width: <?= (count($selected) / count($config['template_layout']['slots'])) * 100 ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons" data-aos="fade-up" data-aos-delay="300">
                        <?php if ($config['collage_output']['status'] === 'done'): ?>
                            <div class="alert alert-success mb-4">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                Kolase sudah tersedia!
                            </div>
                            <a href="output/<?= $config['collage_output']['filename'] ?>" 
                               class="btn btn-success btn-lg me-3" target="_blank">
                                <i class="bi bi-download me-2"></i>
                                Download Kolase
                            </a>
                        <?php elseif (count($selected) === count($config['template_layout']['slots'])): ?>
                            <form method="post" action="generate.php" class="d-inline">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-magic me-2"></i>
                                    Generate Kolase Sekarang
                                </button>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                Pilih <?= count($config['template_layout']['slots']) - count($selected) ?> foto lagi untuk mulai membuat kolase
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="contact-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="text-white mb-4">Butuh Bantuan?</h2>
                    <p class="text-white-50 mb-4">
                        Tim AlwafaHub siap membantu Anda dengan layanan fotografi dan kolase digital terbaik
                    </p>
                    <a href="https://wa.me/628XXXXXXXXX?text=Halo%20AlwafaHub!%20Saya%20butuh%20bantuan%20dengan%20gallery%20foto%20event%20<?= urlencode($config['event_title']) ?>" 
                       class="btn btn-success btn-lg" target="_blank">
                        <i class="bi bi-whatsapp me-2"></i>
                        Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-6">
                    <div class="footer-widget">
                        <h5>AlwafaHub Professional Gallery</h5>
                        <p>Sistem gallery foto profesional dengan fitur kolase otomatis dan download real-time. 
                           Sempurna untuk event, wedding, dan dokumentasi acara penting Anda.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="footer-widget">
                        <h5>Connect with us</h5>
                        <div class="social-links">
                            <a href="#"><i class="bi bi-facebook"></i></a>
                            <a href="#"><i class="bi bi-twitter"></i></a>
                            <a href="#"><i class="bi bi-instagram"></i></a>
                            <a href="#"><i class="bi bi-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center">
                    <div class="footer-copyright">
                        <p>&copy; <?= date('Y') ?> AlwafaHub. All Rights Reserved. 
                           Designed with <i class="bi bi-heart-fill text-danger"></i> for professional photography</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Popup Modal untuk Preview Foto dan Kolase -->
    <div id="popup-container" class="photo-modal">
        <div id="popup-content" class="modal-content">
            <button class="close-btn" onclick="closePopup()">
                <i class="bi bi-x-lg"></i>
            </button>
            <div class="modal-header">
                <h5 id="popup-title">Preview Foto</h5>
                <div id="popup-number" class="photo-counter">1 / 1</div>
            </div>
            <div class="modal-body">
                <div class="image-container">
                    <img id="popup-image" src="" alt="Preview">
                    <div class="image-overlay">
                        <button class="nav-btn prev-btn" onclick="prevImage()">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button class="nav-btn next-btn" onclick="nextImage()">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="action-buttons">
                    <a id="download-link" class="btn btn-primary" download>
                        <i class="bi bi-download me-2"></i>Download Foto
                    </a>
                    <a id="zip-link" class="btn btn-outline-primary" download>
                        <i class="bi bi-file-zip"></i>Download ZIP
                    </a>
                </div>
                <!-- Selection Area for Photostrip/Collage -->
                <div class="selection-area mt-4">
                    <div class="selection-header">
                        <h6><i class="bi bi-grid-3x3-gap me-2"></i>Pilih 2 Foto untuk Kolase</h6>
                    </div>
                    <div id="thumbnail-list" class="thumbnail-grid"></div>
                    <button class="btn btn-success mt-3" id="make-collage-btn" style="display:none;" onclick="makeCollage()">
                        <i class="bi bi-magic me-2"></i>Kolase 2 Foto Ini
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal hasil kolase -->
    <div id="collage-popup-container" class="photo-modal" style="display:none;">
        <div id="collage-popup-content" class="modal-content">
            <button class="close-btn" onclick="closeCollagePopup()">
                <i class="bi bi-x-lg"></i>
            </button>
            <h5>Hasil Kolase</h5>
            <img id="collage-image" src="" alt="Collage Image" class="img-fluid border rounded shadow">
            <div class="text-center mt-3">
                <a id="collage-download-link" class="btn btn-primary" download>Download Kolase</a>
            </div>
        </div>
    </div>

    <!-- Photo Popup Modal -->
    <div id="popup-container" class="photo-modal">
        <div id="popup-content" class="modal-content">
            <button class="close-btn" onclick="closePopup()">
                <i class="bi bi-x-lg"></i>
            </button>
            
            <div class="modal-header">
                <h5 id="popup-title">Preview Foto</h5>
                <div id="popup-counter" class="photo-counter">1 / 1</div>
            </div>
            
            <div class="modal-body">
                <div class="image-container">
                    <img id="popup-image" src="" alt="Preview">
                    <div class="image-overlay">
                        <button class="nav-btn prev-btn" onclick="prevImage()">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button class="nav-btn next-btn" onclick="nextImage()">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <div class="action-buttons">
                    <a id="download-link" class="btn btn-primary" download>
                        <i class="bi bi-download me-2"></i>Download Foto
                    </a>
                    <button class="btn btn-success" id="select-btn" onclick="toggleSelection()">
                        <i class="bi bi-check-circle me-2"></i>Pilih Foto
                    </button>
                </div>
                
                <!-- Selection Area -->
                <div class="selection-area mt-4">
                    <div class="selection-header">
                        <h6><i class="bi bi-grid-3x3-gap me-2"></i>Foto Terpilih untuk Kolase</h6>
                        <button class="btn btn-sm btn-outline-danger" onclick="clearSelection()">
                            <i class="bi bi-trash me-1"></i>Hapus Semua
                        </button>
                    </div>
                    <div id="thumbnail-list" class="thumbnail-grid"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success & Error Toast -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="successToast" class="toast" role="alert">
            <div class="toast-header bg-success text-white">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong class="me-auto">Berhasil!</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" id="toastMessage">
                Operasi berhasil dilakukan.
            </div>
        </div>
        <div id="errorToast" class="toast" role="alert">
            <div class="toast-header bg-danger text-white">
                <i class="bi bi-x-circle-fill me-2"></i>
                <strong class="me-auto">Gagal!</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" id="errorMessage">
                Terjadi kesalahan.
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Global Variables
        let currentPhotos = [];
        let currentIndex = 0;
        let selectedPhotos = <?= json_encode($selected) ?>;
        let maxSelection = <?= count($config['template_layout']['slots']) ?>;
        let isotope;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true
            });

            // Initialize Isotope
            initializeIsotope();
            
            // Hide loader
            setTimeout(() => {
                document.getElementById('overlayer').style.display = 'none';
            }, 1000);

            // Update selection display
            updateSelectionDisplay();
        });

        // Initialize Isotope filtering
        function initializeIsotope() {
            const gallery = document.querySelector('.photo-gallery');
            isotope = new Isotope(gallery, {
                itemSelector: '.group-thumb',
                layoutMode: 'fitRows',
                transitionDuration: '0.6s'
            });

            // Filter buttons
            const filterButtons = document.querySelectorAll('.filter-list li');
            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                    
                    const filterValue = button.getAttribute('data-filter');
                    isotope.arrange({ 
                        filter: filterValue === '*' ? '*' : filterValue 
                    });
                });
            });
        }

        // Show photo popup
        function showPopup(photos, timeSlot) {
            currentPhotos = photos;
            currentIndex = 0;
            
            document.getElementById('popup-title').textContent = `Foto ${timeSlot}`;
            document.getElementById('popup-container').style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            showCurrentImage();
        }

        // Close popup
        function closePopup() {
            document.getElementById('popup-container').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Show current image
        function showCurrentImage() {
            if (currentPhotos.length === 0) return;
            
            const img = document.getElementById('popup-image');
            const counter = document.getElementById('popup-counter');
            const downloadLink = document.getElementById('download-link');
            const selectBtn = document.getElementById('select-btn');
            
            const currentPhoto = currentPhotos[currentIndex];
            
            img.src = `thumbs/${currentPhoto}`;
            counter.textContent = `${currentIndex + 1} / ${currentPhotos.length}`;
            downloadLink.href = `thumbs/${currentPhoto}`;
            downloadLink.download = currentPhoto;
            
            // Update select button
            const isSelected = selectedPhotos.includes(currentPhoto);
            selectBtn.innerHTML = isSelected 
                ? '<i class="bi bi-check-circle-fill me-2"></i>Terpilih'
                : '<i class="bi bi-check-circle me-2"></i>Pilih Foto';
            selectBtn.className = isSelected 
                ? 'btn btn-success' 
                : 'btn btn-outline-success';
        }

        // Navigation functions
        function prevImage() {
            currentIndex = currentIndex > 0 ? currentIndex - 1 : currentPhotos.length - 1;
            showCurrentImage();
        }

        function nextImage() {
            currentIndex = currentIndex < currentPhotos.length - 1 ? currentIndex + 1 : 0;
            showCurrentImage();
        }

        // Toggle photo selection
        function toggleSelection() {
            const currentPhoto = currentPhotos[currentIndex];
            const index = selectedPhotos.indexOf(currentPhoto);
            
            if (index > -1) {
                // Remove from selection
                selectedPhotos.splice(index, 1);
                showSuccessMessage('Foto dihapus dari pilihan');
            } else {
                // Add to selection
                if (selectedPhotos.length >= maxSelection) {
                    showErrorMessage(`Maksimal ${maxSelection} foto yang dapat dipilih`);
                    return;
                }
                selectedPhotos.push(currentPhoto);
                showSuccessMessage('Foto ditambahkan ke pilihan');
            }
            
            updateSelectionDisplay();
            showCurrentImage();
            saveSelection();
        }

        // Clear all selections
        function clearSelection() {
            selectedPhotos = [];
            updateSelectionDisplay();
            showCurrentImage();
            saveSelection();
            showSuccessMessage('Semua pilihan foto dihapus');
        }

        // Update selection display
        function updateSelectionDisplay() {
            const selectedCount = document.getElementById('selectedCount');
            const selectionProgress = document.getElementById('selectionProgress');
            const thumbnailList = document.getElementById('thumbnail-list');
            
            if (selectedCount) selectedCount.textContent = selectedPhotos.length;
            
            if (selectionProgress) {
                const percentage = (selectedPhotos.length / maxSelection) * 100;
                selectionProgress.style.width = `${percentage}%`;
            }
            
            if (thumbnailList) {
                thumbnailList.innerHTML = '';
                selectedPhotos.forEach((photo, index) => {
                    const thumb = document.createElement('div');
                    thumb.className = 'selected-thumb';
                    thumb.innerHTML = `
                        <img src="thumbs/${photo}" alt="Selected ${index + 1}">
                        <div class="thumb-number">${index + 1}</div>
                        <button class="remove-thumb" onclick="removeFromSelection('${photo}')">
                            <i class="bi bi-x"></i>
                        </button>
                    `;
                    thumbnailList.appendChild(thumb);
                });
            }
        }

        // Remove from selection
        function removeFromSelection(photo) {
            const index = selectedPhotos.indexOf(photo);
            if (index > -1) {
                selectedPhotos.splice(index, 1);
                updateSelectionDisplay();
                showCurrentImage();
                saveSelection();
                showSuccessMessage('Foto dihapus dari pilihan');
            }
        }

        // Save selection to server
        function saveSelection() {
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `selected=${selectedPhotos.map(p => encodeURIComponent(p)).join('&selected=')}`
            });
        }

        // Show success message
        function showSuccessMessage(message) {
            document.getElementById('toastMessage').textContent = message;
            const toast = new bootstrap.Toast(document.getElementById('successToast'));
            toast.show();
        }

        // Show error message
        function showErrorMessage(message) {
            document.getElementById('errorMessage').textContent = message;
            const toast = new bootstrap.Toast(document.getElementById('errorToast'));
            toast.show();
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (document.getElementById('popup-container').style.display === 'flex') {
                switch(e.key) {
                    case 'ArrowLeft':
                        prevImage();
                        break;
                    case 'ArrowRight':
                        nextImage();
                        break;
                    case ' ':
                        e.preventDefault();
                        toggleSelection();
                        break;
                    case 'Escape':
                        closePopup();
                        break;
                }
            }
        });

        // Smooth scrolling for navigation links
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

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('mainNav');
            if (window.scrollY > 100) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Integrasi script deobfuscasi photostrip/kolase
        let currentGroup = [];
        let currentIndex = 0;
        let selectedForCollage = [];
        let lastHash = null;

        function showPopup(group) {
            currentGroup = group;
            currentIndex = 0;
            updatePopupImage();
            document.getElementById('popup-container').style.display = 'flex';
        }
        function nextImage() {
            currentIndex = (currentIndex + 1) % currentGroup.length;
            updatePopupImage();
        }
        function prevImage() {
            currentIndex = (currentIndex - 1 + currentGroup.length) % currentGroup.length;
            updatePopupImage();
        }
        function closePopup() {
            document.getElementById('popup-container').style.display = 'none';
        }
        async function checkForUpdates() {
            try {
                const response = await fetch('api_photos.php');
                const data = await response.json();
                if (lastHash && data.hash !== lastHash) {
                    location.reload();
                } else {
                    lastHash = data.hash;
                }
            } catch (err) {
                console.error('Update gagal:', err);
            }
        }
        setInterval(checkForUpdates, 10000);
        function updatePopupImage() {
            const img = document.getElementById('popup-image');
            const downloadLink = document.getElementById('download-link');
            const zipLink = document.getElementById('zip-link');
            const popupNumber = document.getElementById('popup-number');
            const thumbnailList = document.getElementById('thumbnail-list');
            if (popupNumber) {
                popupNumber.textContent = (currentIndex + 1) + ' / ' + currentGroup.length;
            }
            img.src = 'uploads/' + currentGroup[currentIndex];
            downloadLink.href = 'uploads/' + currentGroup[currentIndex];
            downloadLink.setAttribute('download', currentGroup[currentIndex]);
            zipLink.href = 'download_zip.php?files=' + encodeURIComponent(currentGroup.join(','));
            thumbnailList.innerHTML = '';
            selectedForCollage = [];
            currentGroup.forEach((photo, idx) => {
                const thumbDiv = document.createElement('div');
                thumbDiv.classList.add('img-thumbnail');
                const numberDiv = document.createElement('div');
                numberDiv.classList.add('thumbnail-number');
                numberDiv.textContent = idx + 1;
                const imgThumb = document.createElement('img');
                imgThumb.src = 'uploads/' + photo;
                imgThumb.style.cursor = 'pointer';
                imgThumb.onclick = () => selectForCollage(photo, imgThumb);
                thumbDiv.appendChild(imgThumb);
                thumbDiv.appendChild(numberDiv);
                thumbnailList.appendChild(thumbDiv);
            });
        }
        function selectForCollage(photo, el) {
            const idx = selectedForCollage.indexOf(photo);
            if (idx > -1) {
                selectedForCollage.splice(idx, 1);
                el.classList.remove('selected');
            } else if (selectedForCollage.length < 2) {
                selectedForCollage.push(photo);
                el.classList.add('selected');
            }
            document.getElementById('make-collage-btn').style.display = selectedForCollage.length === 2 ? 'inline-block' : 'none';
        }
        function makeCollage() {
            const url = 'make_collage.php?folder=uploads_unframed&img1=' + encodeURIComponent(selectedForCollage[0]) + '&img2=' + encodeURIComponent(selectedForCollage[1]);
            fetch(url)
                .then(res => res.text())
                .then(imgUrl => {
                    document.getElementById('collage-popup-container').style.display = 'flex';
                    document.getElementById('collage-image').src = imgUrl;
                    document.getElementById('collage-download-link').href = imgUrl;
                    document.getElementById('collage-download-link').setAttribute('download', imgUrl.split('/').pop());
                });
        }
        function closeCollagePopup() {
            document.getElementById('collage-popup-container').style.display = 'none';
            const collageImg = document.getElementById('collage-image').src;
            const fileName = collageImg.split('/').pop();
            fetch('delete_collage.php?file=' + encodeURIComponent(fileName))
                .then(res => res.text())
                .then(msg => {
                    console.log('Collage deleted:', msg);
                });
        }
    </script>

    <!-- Tambahan library dan script fitur template -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-animateNumber/0.0.14/jquery.animateNumber.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.stellar/0.6.2/jquery.stellar.min.js"></script>
    <!-- Integrasi semua fitur JS template -->
    <script>
    AOS.init({
        duration: 800,
        easing: 'slide',
        once: true
    });
    $(function(){
        "use strict";
        $(".loader").delay(0).fadeOut("slow");
        $("#overlayer").delay(0).fadeOut("slow");
        // siteMenuClone
        $('.js-clone-nav').each(function() {
            var $this = $(this);
            $this.clone().attr('class', 'site-nav-wrap').appendTo('.site-mobile-menu-body');
        });
        setTimeout(function() {
            var counter = 0;
            $('.site-mobile-menu .has-children').each(function(){
                var $this = $(this);
                $this.prepend('<span class="arrow-collapse collapsed">');
                $this.find('.arrow-collapse').attr({
                    'data-toggle' : 'collapse',
                    'data-target' : '#collapseItem' + counter,
                });
                $this.find('> ul').attr({
                    'class' : 'collapse',
                    'id' : 'collapseItem' + counter,
                });
                counter++;
            });
        }, 1000);
        $('body').on('click', '.arrow-collapse', function(e) {
            var $this = $(this);
            if ( $this.closest('li').find('.collapse').hasClass('show') ) {
                $this.removeClass('active');
            } else {
                $this.addClass('active');
            }
            e.preventDefault();  
        });
        $(window).resize(function() {
            var $this = $(this), w = $this.width();
            if ( w > 768 ) {
                if ( $('body').hasClass('offcanvas-menu') ) {
                    $('body').removeClass('offcanvas-menu');
                }
            }
        })
        $('body').on('click', '.js-menu-toggle', function(e) {
            var $this = $(this);
            e.preventDefault();
            if ( $('body').hasClass('offcanvas-menu') ) {
                $('body').removeClass('offcanvas-menu');
                $('body').find('.js-menu-toggle').removeClass('active');
            } else {
                $('body').addClass('offcanvas-menu');
                $('body').find('.js-menu-toggle').addClass('active');
            }
        }) 
        $(document).mouseup(function(e) {
            var container = $(".site-mobile-menu");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                if ( $('body').hasClass('offcanvas-menu') ) {
                    $('body').removeClass('offcanvas-menu');
                    $('body').find('.js-menu-toggle').removeClass('active');
                }
            }
        });
        // Owl Carousel
        if ( $('.owl-single').length > 0 ) {
            var owl = $('.owl-single').owlCarousel({
                loop: true,
                autoHeight: true,
                margin: 0,
                autoplay: true,
                smartSpeed: 1000,
                items: 1,
                nav: true,
                navText: ['<span class="icon-keyboard_backspace"></span>','<span class="icon-keyboard_backspace"></span>']
            });
            owl.on('initialized.owl.carousel', function() {
                owl.trigger('refresh.owl.carousel');
            });
            $('.custom-owl-next').click(function(e) {
                e.preventDefault();
                owl.trigger('next.owl.carousel');
            })
            $('.custom-owl-prev').click(function(e) {
                e.preventDefault();
                owl.trigger('prev.owl.carousel');
            })
        }
        // Counter
        $('.count-numbers').waypoint( function( direction ) {
            if( direction === 'down' && !$(this.element).hasClass('ut-animated') ) {
                var comma_separator_number_step = $.animateNumber.numberStepFactories.separator(',')
                $('.counter > span').each(function(){
                    var $this = $(this), num = $this.data('number');
                    $this.animateNumber({ number: num, numberStep: comma_separator_number_step }, 10000);
                });
            }
        } , { offset: '95%' } );
        // Portfolio Masonry
        $('.filters ul li').click(function(){
            $('.filters ul li').removeClass('active');
            $(this).addClass('active');
            var data = $(this).attr('data-filter');
            $grid.isotope({ filter: data })
        });
        if(document.getElementById("portfolio-section")){
            var $grid = $(".grid").isotope({
                itemSelector: ".all",
                percentPosition: true,
                masonry: { columnWidth: ".all" }
            })
            $grid.imagesLoaded().progress( function() {
                $grid.isotope('layout');
            });  
        };
        // Search Toggle
        $('.js-search-toggle').on('click', function() {
            $('.search-wrap').toggleClass('active');
            setTimeout(function() { $('#s').focus(); }, 400);
        })
        $(document).mouseup(function(e) {
            var container = $(".search-wrap form");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                if ( $('.search-wrap').hasClass('active') ) {
                    $('.search-wrap').removeClass('active');
                }
            }
        }); 
        // Parallax
        $(window).stellar({
            responsive: false,
            parallaxBackgrounds: true,
            parallaxElements: true,
            horizontalScrolling: false,
            hideDistantElements: false,
            scrollProperty: 'scroll'
        });
        // Pricing Toggle
        $('.js-period-toggle').on('click', function(e) {
            var $this = $(this), pricingItem = $('.pricing-item');
            if ( $('.period-toggle').hasClass('active') ) {
                $this.removeClass('active');
                pricingItem.removeClass('yearly');
            } else {
                $this.addClass('active');
                pricingItem.addClass('yearly');
            }
            e.preventDefault();
        })
    });
    </script>
</body>
</html>
