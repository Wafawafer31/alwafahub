<?php
include '../../admin/config-reader.php';
include '../../lib/gd-helper.php';

$eventId = 'wafa'; // Bisa juga dari $_GET atau $_POST
$config = loadEventConfig($eventId);
$layout = $config['template_layout'];
$selectedPhotos = $config['selected_photos'];
$frameFile = $layout['frame'];
$canvasW = $layout['canvas']['width'];
$canvasH = $layout['canvas']['height'];

$canvas = imagecreatetruecolor($canvasW, $canvasH);
$bgColor = imagecolorallocate($canvas, 255, 255, 255);
imagefill($canvas, 0, 0, $bgColor);

// Tempelkan foto ke slot
foreach ($layout['slots'] as $i => $slot) {
    $photoPath = "../../clients/$eventId/selected/" . ($selectedPhotos[$i] ?? '');
    if (file_exists($photoPath)) {
        $photo = imagecreatefromjpeg($photoPath);
        $resized = imagescale($photo, $slot['width'], $slot['height']);
        imagecopy($canvas, $resized, $slot['x'], $slot['y'], 0, 0, $slot['width'], $slot['height']);
        imagedestroy($resized);
    }
}

// Tempelkan frame PNG transparan
$framePath = "../../assets/frames/$frameFile";
if (file_exists($framePath)) {
    $frame = imagecreatefrompng($framePath);
    imagecopy($canvas, $frame, 0, 0, 0, 0, $canvasW, $canvasH);
    imagedestroy($frame);
}

// Simpan hasil kolase
$outputPath = "../../clients/$eventId/output/collage-final.jpg";
imagejpeg($canvas, $outputPath, 90);
imagedestroy($canvas);

// Update status di config
$config['collage_output']['status'] = 'done';
$config['collage_output']['filename'] = basename($outputPath);
saveEventConfig($eventId, $config);

echo "<p>Kolase berhasil dibuat: <a href='$outputPath' target='_blank'>Download JPG</a></p>";
?>
