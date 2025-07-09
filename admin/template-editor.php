<?php
include 'auth-check.php'; // Proteksi login
include 'config-reader.php';

$eventId = $_GET['event'] ?? 'default';
$config = loadEventConfig($eventId);
$layout = $config['template_layout'] ?? [];

$canvasW = $layout['canvas']['width'] ?? 800;
$canvasH = $layout['canvas']['height'] ?? 600;
$slots = $layout['slots'] ?? [];
$frame = $layout['frame'] ?? 'default-frame.png';
?>

<h2>Editor Layout Kolase - Event: <?= htmlspecialchars($eventId) ?></h2>

<canvas id="layoutCanvas" width="<?= $canvasW ?>" height="<?= $canvasH ?>" style="border:1px solid #ccc;"></canvas>

<script>
const canvas = document.getElementById('layoutCanvas');
const ctx = canvas.getContext('2d');

// Gambar slot foto
const slots = <?= json_encode($slots) ?>;
slots.forEach((slot, index) => {
    ctx.fillStyle = '#ddd';
    ctx.fillRect(slot.x, slot.y, slot.width, slot.height);
    ctx.strokeStyle = '#333';
    ctx.strokeRect(slot.x, slot.y, slot.width, slot.height);
    ctx.fillStyle = '#000';
    ctx.fillText("Slot " + (index + 1), slot.x + 5, slot.y + 15);
});

// Gambar frame PNG transparan (preview)
const frameImg = new Image();
frameImg.src = '/assets/frames/<?= $frame ?>';
frameImg.onload = () => ctx.drawImage(frameImg, 0, 0, canvas.width, canvas.height);
</script>

<form method="post" action="save-layout.php">
    <input type="hidden" name="event_id" value="<?= htmlspecialchars($eventId) ?>" />
    <textarea name="layout_json" rows="10" cols="80"><?= json_encode($layout, JSON_PRETTY_PRINT) ?></textarea><br>
    <button type="submit">Simpan Layout</button>
</form>
