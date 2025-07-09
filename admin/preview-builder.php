<?php
include 'auth-check.php';
include 'config-reader.php';

$eventId = $_GET['event'] ?? 'default';
$config = loadEventConfig($eventId);
$layout = $config['template_layout'] ?? [];

$canvasW = $layout['canvas']['width'] ?? 800;
$canvasH = $layout['canvas']['height'] ?? 600;
$slots = $layout['slots'] ?? [];
$frame = $layout['frame'] ?? 'default-frame.png';

// Dummy foto untuk preview
$dummyPhotos = [
    '/admin/kolase-preview/dummy1.jpg',
    '/admin/kolase-preview/dummy2.jpg',
    '/admin/kolase-preview/dummy3.jpg'
];
?>

<h2>Preview Layout Kolase - Event: <?= htmlspecialchars($eventId) ?></h2>

<canvas id="previewCanvas" width="<?= $canvasW ?>" height="<?= $canvasH ?>" style="border:1px solid #ccc;"></canvas>

<script>
const canvas = document.getElementById('previewCanvas');
const ctx = canvas.getContext('2d');

// Gambar background putih
ctx.fillStyle = '#fff';
ctx.fillRect(0, 0, canvas.width, canvas.height);

// Gambar dummy foto ke slot
const slots = <?= json_encode($slots) ?>;
const dummyPhotos = <?= json_encode($dummyPhotos) ?>;

slots.forEach((slot, index) => {
    const img = new Image();
    img.src = dummyPhotos[index % dummyPhotos.length];
    img.onload = () => {
        ctx.drawImage(img, slot.x, slot.y, slot.width, slot.height);
    };
});

// Gambar frame PNG transparan
const frameImg = new Image();
frameImg.src = '/assets/frames/<?= $frame ?>';
frameImg.onload = () => ctx.drawImage(frameImg, 0, 0, canvas.width, canvas.height);
</script>
