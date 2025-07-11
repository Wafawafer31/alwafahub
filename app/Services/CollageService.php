<?php
// app/Services/CollageService.php - Service untuk membuat kolase

class CollageService {
    
    public function generate($event, $selectedPhotos) {
        try {
            $templateConfig = json_decode($event['template_config'], true);
            $canvasW = $templateConfig['canvas']['width'];
            $canvasH = $templateConfig['canvas']['height'];
            $slots = $templateConfig['slots'];
            $frameFile = $templateConfig['frame'];
            
            // Create canvas
            $canvas = imagecreatetruecolor($canvasW, $canvasH);
            $bgColor = imagecolorallocate($canvas, 255, 255, 255);
            imagefill($canvas, 0, 0, $bgColor);
            
            // Add photos to slots
            foreach ($slots as $i => $slot) {
                if (isset($selectedPhotos[$i])) {
                    $this->addPhotoToSlot($canvas, $selectedPhotos[$i], $slot, $event['client_slug']);
                }
            }
            
            // Add frame
            $this->addFrame($canvas, $frameFile, $canvasW, $canvasH);
            
            // Save collage
            $timestamp = date('Y-m-d_H-i-s');
            $filename = "collage-{$event['client_slug']}-{$timestamp}.jpg";
            $outputPath = __DIR__ . "/../../public/uploads/clients/{$event['client_slug']}/output/{$filename}";
            
            $success = imagejpeg($canvas, $outputPath, 95);
            imagedestroy($canvas);
            
            if ($success) {
                $this->updateEventCollageStatus($event['id'], $filename);
                return ['success' => true, 'filename' => $filename];
            }
            
        } catch (Exception $e) {
            error_log("Collage generation failed: " . $e->getMessage());
        }
        
        return ['success' => false, 'message' => 'Gagal membuat kolase'];
    }
    
    private function addPhotoToSlot($canvas, $photo, $slot, $clientSlug) {
        $photoPath = __DIR__ . "/../../public/uploads/clients/{$clientSlug}/thumbs/{$photo['filename']}";
        
        if (!file_exists($photoPath)) {
            return false;
        }
        
        // Create temporary resized image
        $tempPath = sys_get_temp_dir() . "/temp_" . uniqid() . ".jpg";
        $this->resizeAndCropImage($photoPath, $tempPath, $slot['width'], $slot['height']);
        
        $photoImg = imagecreatefromjpeg($tempPath);
        imagecopy($canvas, $photoImg, $slot['x'], $slot['y'], 0, 0, $slot['width'], $slot['height']);
        
        imagedestroy($photoImg);
        unlink($tempPath);
        
        return true;
    }
    
    private function addFrame($canvas, $frameFile, $canvasW, $canvasH) {
        $framePath = __DIR__ . "/../../public/assets/frames/{$frameFile}";
        
        if (!file_exists($framePath)) {
            return false;
        }
        
        $frame = imagecreatefrompng($framePath);
        if (!$frame) {
            return false;
        }
        
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
        
        return true;
    }
    
    private function resizeAndCropImage($srcPath, $destPath, $width, $height) {
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
    
    private function updateEventCollageStatus($eventId, $filename) {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("
                UPDATE events 
                SET collage_status = 'completed', collage_filename = ?, updated_at = NOW() 
                WHERE id = ?
            ");
            $stmt->execute([$filename, $eventId]);
        } catch (PDOException $e) {
            error_log("Failed to update collage status: " . $e->getMessage());
        }
    }
}