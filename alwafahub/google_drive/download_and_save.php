<?php
function downloadAndSave($service, $fileId, $targetPath) {
    try {
        $response = $service->files->get($fileId, ['alt' => 'media']);
        $content = $response->getBody()->getContents();
        file_put_contents($targetPath, $content);
        return true;
    } catch (Exception $e) {
        error_log("Gagal download file ID $fileId: " . $e->getMessage());
        return false;
    }
}
