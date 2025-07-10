<?php
include '../../admin/config-reader.php';

// Simple GD helper functions
function resizeAndCropImage($srcPath, $destPath, $width, $height) {
    $srcImg = imagecreatefromjpeg($srcPath);
    $srcWidth = imagesx($srcImg);
    $srcHeight = imagesy($srcImg);
    
    // Calculate crop dimensions
    $srcRatio = $srcWidth / $srcHeight;
    $destRatio = $width / $height;
    
    if ($srcRatio > $destRatio) {
        // Source is wider
        $cropHeight = $srcHeight;
        $cropWidth = $srcHeight * $destRatio;
        $cropX = ($srcWidth - $cropWidth) / 2;
        $cropY = 0;
    } else {
        // Source is taller
        $cropWidth = $srcWidth;
        $cropHeight = $srcWidth / $destRatio;
        $cropX = 0;
        $cropY = ($srcHeight - $cropHeight) / 2;
    }
    
    $destImg = imagecreatetruecolor($width, $height);
    imagecopyresampled($destImg, $srcImg, 0, 0, $cropX, $cropY, $width, $height, $cropWidth, $cropHeight);
    
    imagejpeg($destImg, $destPath, 90);
    imagedestroy($srcImg);
    imagedestroy($destImg);
}

$eventId = 'wafa'; // Bisa juga dari $_GET atau $_POST
$config = loadEventConfig($eventId);
$layout = $config['template_layout'];
$selectedPhotos = $config['selected_photos'];
$frameFile = $layout['frame'];
$canvasW = $layout['canvas']['width'];
$canvasH = $layout['canvas']['height'];

// Create output directory if not exists
$outputDir = __DIR__ . '/output/';
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

$canvas = imagecreatetruecolor($canvasW, $canvasH);
$bgColor = imagecolorallocate($canvas, 255, 255, 255);
imagefill($canvas, 0, 0, $bgColor);

// Tempelkan foto ke slot
foreach ($layout['slots'] as $i => $slot) {
    if (isset($selectedPhotos[$i])) {
        $photoPath = __DIR__ . "/thumbs/" . $selectedPhotos[$i];
        if (file_exists($photoPath)) {
            // Create temporary resized image
            $tempPath = __DIR__ . "/temp_" . $i . ".jpg";
            resizeAndCropImage($photoPath, $tempPath, $slot['width'], $slot['height']);
            
            $photo = imagecreatefromjpeg($tempPath);
            imagecopy($canvas, $photo, $slot['x'], $slot['y'], 0, 0, $slot['width'], $slot['height']);
            imagedestroy($photo);
            unlink($tempPath); // Clean up temp file
        }
    }
}

// Tempelkan frame PNG transparan
$framePath = "../../assets/frames/$frameFile";
if (file_exists($framePath)) {
    $frame = imagecreatefrompng($framePath);
    if ($frame) {
        // Enable alpha blending for transparency
        imagealphablending($canvas, true);
        imagesavealpha($canvas, true);
        
        // Resize frame to match canvas
        $frameResized = imagecreatetruecolor($canvasW, $canvasH);
        imagealphablending($frameResized, false);
        imagesavealpha($frameResized, true);
        
        imagecopyresampled($frameResized, $frame, 0, 0, 0, 0, $canvasW, $canvasH, imagesx($frame), imagesy($frame));
        imagecopy($canvas, $frameResized, 0, 0, 0, 0, $canvasW, $canvasH);
        
        imagedestroy($frame);
        imagedestroy($frameResized);
    }
}

// Generate unique filename with timestamp
$timestamp = date('Y-m-d_H-i-s');
$outputFilename = "collage-{$eventId}-{$timestamp}.jpg";
$outputPath = $outputDir . $outputFilename;

// Save collage
$success = imagejpeg($canvas, $outputPath, 95);
imagedestroy($canvas);

if ($success) {
    // Update config
    $config['collage_output']['status'] = 'done';
    $config['collage_output']['filename'] = $outputFilename;
    $config['collage_output']['created_at'] = date('Y-m-d H:i:s');
    saveEventConfig($eventId, $config);
    
    // Redirect back to gallery with success message
    header("Location: index.php?success=1&file=" . urlencode($outputFilename));
    exit;
} else {
    // Redirect back with error
    header("Location: index.php?error=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generating Collage...</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/client-gallery.css">
</head>
<body>
    <div class="container-fluid vh-100 d-flex align-items-center justify-content-center bg-primary">
        <div class="text-center text-white">
            <div class="spinner-border mb-4" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h2>Sedang Membuat Kolase...</h2>
            <p>Mohon tunggu sebentar, kolase Anda sedang diproses.</p>
        </div>
    imagedestroy($frame);
