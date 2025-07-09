<?php
// lib/gd-helper.php – Fungsi bantu manipulasi gambar untuk kolase event

namespace Lib;

class GDHelper {
    public static function resizeImage($srcPath, $destPath, $width, $height) {
        $srcImg = imagecreatefromjpeg($srcPath);
        $resized = imagecreatetruecolor($width, $height);
        imagecopyresampled($resized, $srcImg, 0, 0, 0, 0, $width, $height, imagesx($srcImg), imagesy($srcImg));
        imagejpeg($resized, $destPath, 90);
        imagedestroy($srcImg);
        imagedestroy($resized);
    }

    public static function overlayFrame($photoPath, $framePath, $outputPath) {
        $photo = imagecreatefromjpeg($photoPath);
        $frame = imagecreatefrompng($framePath);
        imagealphablending($frame, true);
        imagesavealpha($frame, true);

        $width = imagesx($photo);
        $height = imagesy($photo);
        $resizedFrame = imagecreatetruecolor($width, $height);
        imagesavealpha($resizedFrame, true);
        imagefill($resizedFrame, 0, 0, imagecolorallocatealpha($resizedFrame, 0, 0, 0, 127));
        imagecopyresampled($resizedFrame, $frame, 0, 0, 0, 0, $width, $height, imagesx($frame), imagesy($frame));
        imagecopy($photo, $resizedFrame, 0, 0, 0, 0, $width, $height);

        imagejpeg($photo, $outputPath, 90);
        imagedestroy($photo);
        imagedestroy($frame);
        imagedestroy($resizedFrame);
    }

    public static function cropCenter($srcPath, $destPath, $targetWidth, $targetHeight) {
        $srcImg = imagecreatefromjpeg($srcPath);
        $srcWidth = imagesx($srcImg);
        $srcHeight = imagesy($srcImg);

        $cropX = ($srcWidth - $targetWidth) / 2;
        $cropY = ($srcHeight - $targetHeight) / 2;

        $cropped = imagecreatetruecolor($targetWidth, $targetHeight);
        imagecopy($cropped, $srcImg, 0, 0, $cropX, $cropY, $targetWidth, $targetHeight);
        imagejpeg($cropped, $destPath, 90);

        imagedestroy($srcImg);
        imagedestroy($cropped);
    }
}
