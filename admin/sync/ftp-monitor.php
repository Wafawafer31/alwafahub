<?php
$ftpHost = "ftp.example.com";
$ftpUser = "fotografer";
$ftpPass = "password123";
$remoteDir = "/upload/";
$baseLocalDir = __DIR__ . "/../photos/ftp/";

$eventId = $_GET['event'] ?? 'default';
$localDir = $baseLocalDir . "evt_" . $eventId . "/";

if (!is_dir($localDir)) mkdir($localDir, 0777, true);

$conn = ftp_connect($ftpHost);
$login = ftp_login($conn, $ftpUser, $ftpPass);
ftp_pasv($conn, true);

$files = ftp_nlist($conn, $remoteDir);
foreach ($files as $file) {
    $localPath = $localDir . basename($file);
    if (!file_exists($localPath)) {
        $success = ftp_get($conn, $localPath, $file, FTP_BINARY);
        echo $success ? "✅ $file disalin ke $eventId<br>" : "❌ Gagal salin $file<br>";
    }
}
ftp_close($conn);
