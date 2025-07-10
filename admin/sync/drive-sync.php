<?php
require_once '../../lib/google-drive-api.php'; // Class handler API

$eventId = $_GET['event'] ?? 'default';
$config = loadEventConfig($eventId);
$folderId = $config['sync_settings']['drive_folder_id'];
$localDir = __DIR__ . '/../photos/drive/';

$accessToken = getGoogleAccessToken(); // Fungsi autentikasi OAuth

$files = getDriveFiles($accessToken, $folderId); // Ambil daftar file

foreach ($files as $file) {
    $filename = $file['name'];
    $fileId = $file['id'];
    $localPath = $localDir . $filename;

    if (!file_exists($localPath)) {
        $content = downloadDriveFile($accessToken, $fileId);
        file_put_contents($localPath, $content);
        echo "✅ File baru disimpan: $filename<br>";
    }
}
?>
