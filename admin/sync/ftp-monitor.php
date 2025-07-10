<?php
$ftpHost = "ftp.example.com";
$ftpUser = "fotografer";
$ftpPass = "password123";
$remoteDir = "/upload/";
$localDir = __DIR__ . "/../photos/ftp/";

$conn = ftp_connect($ftpHost);
$login = ftp_login($conn, $ftpUser, $ftpPass);

if (!$conn || !$login) {
    die("Koneksi FTP gagal.");
}

ftp_pasv($conn, true); // Mode pasif

$files = ftp_nlist($conn, $remoteDir);
foreach ($files as $file) {
    $localPath = $localDir . basename($file);
    if (!file_exists($localPath)) {
        $success = ftp_get($conn, $localPath, $file, FTP_BINARY);
        if ($success) {
            echo "✅ File baru disalin: " . basename($file) . "<br>";
        } else {
            echo "❌ Gagal menyalin: " . basename($file) . "<br>";
        }
    }
}

ftp_close($conn);
?>
