<?php
include 'auth.php';
require_once '../google_drive/drive_client.php';
require_once '../google_drive/list_files.php';
require_once '../google_drive/download_and_save.php';

$clientsFile = '../config/clients.json';
$clients = json_decode(file_get_contents($clientsFile), true);

if (!isset($_GET['client']) || !isset($clients[$_GET['client']])) {
    die("Klien tidak ditemukan.");
}

$clientSlug = $_GET['client'];
$clientInfo = $clients[$clientSlug];
$driveFolderId = $clientInfo['drive_folder_id'];

$localPath = "../clients/$clientSlug/uploads_unframed/";
if (!is_dir($localPath)) {
    mkdir($localPath, 0777, true);
}

// Ambil file dari Google Drive
$client = getDriveClient(); // from drive_client.php
$files = listDriveFiles($client, $driveFolderId); // from list_files.php

$newDownloads = 0;

foreach ($files as $file) {
    $filename = $file['name'];
    $fileId = $file['id'];
    $targetPath = $localPath . $filename;

    if (!file_exists($targetPath)) {
        if (downloadAndSave($client, $fileId, $targetPath)) {
            $newDownloads++;
        }
    }
}
// Hitung jumlah file tersinkron
$file_count = count($downloadedFiles); // asumsikan ini array file yg disimpan

// Kirim log
$postData = http_build_query([
    'client' => $client_name,
    'status' => 'OK',
    'file_count' => $file_count
]);

$opts = [
    'http' => [
        'method'  => 'POST',
        'header'  => "Content-Type: application/x-www-form-urlencoded",
        'content' => $postData
    ]
];
$context = stream_context_create($opts);
file_get_contents('http://localhost/alwafahub/admin/update_sync_log.php', false, $context);

echo "<h2>Sinkronisasi Klien: {$clientInfo['display_name']}</h2>";
echo "<p>Total file baru yang diunduh: <strong>$newDownloads</strong></p>";
echo "<p><a href='dashboard.php'>⬅️ Kembali ke Dashboard</a></p>";
