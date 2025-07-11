<?php
// download_zip.php
// ZIP semua file di folder thumbs/ dan kirim ke user
$thumbDir = __DIR__ . '/thumbs/';
$zipName = 'all_photos_' . date('Ymd_His') . '.zip';
$zipPath = sys_get_temp_dir() . '/' . $zipName;

$zip = new ZipArchive();
if ($zip->open($zipPath, ZipArchive::CREATE) !== TRUE) {
    die('Gagal membuat file ZIP');
}

$files = array_filter(scandir($thumbDir), fn($f) => preg_match('/\.jpe?g$/i', $f));
foreach ($files as $file) {
    $filePath = $thumbDir . $file;
    if (is_file($filePath)) {
        $zip->addFile($filePath, $file);
    }
}
$zip->close();

header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . $zipName . '"');
header('Content-Length: ' . filesize($zipPath));
readfile($zipPath);
// Hapus file zip temp setelah download
unlink($zipPath);
exit;
