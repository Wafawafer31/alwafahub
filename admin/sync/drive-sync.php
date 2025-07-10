<?php
require_once '../../lib/google-drive-api.php';

$eventId = $_GET['event'] ?? 'default';
$config = loadEventConfig($eventId);
$folderId = $config['sync_settings']['drive_folder_id'];
$localDir = __DIR__ . "/../photos/drive/evt_" . $eventId . "/";

if (!is_dir($localDir)) mkdir($localDir, 0777, true);

$accessToken = getGoogleAccessToken();
$files = getDriveFiles($accessToken, $folderId);

foreach ($files as $file) {
    $filename = $file['name'];
    $fileId = $file['id'];
    $localPath = $localDir . $filename;

    if (!file_exists($localPath)) {
        $content = downloadDriveFile($accessToken, $fileId);
        file_put_contents($localPath, $content);
        echo "✅ $filename disimpan ke $eventId<br>";
    }
}
