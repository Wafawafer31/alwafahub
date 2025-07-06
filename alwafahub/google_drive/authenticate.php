<?php
require_once '../vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setApplicationName('AlwafaHub RTPD Auth');
$client->setScopes(Google_Service_Drive::DRIVE_READONLY);
$client->setAuthConfig('../config/credentials.json');
$client->setAccessType('offline');
$client->setPrompt('select_account consent');

$tokenPath = '../config/token.json';

if (!isset($_GET['code'])) {
    // Step 1: Redirect to Google's OAuth server
    $authUrl = $client->createAuthUrl();
    echo "<h3>Autentikasi Google Drive</h3>";
    echo "<p><a href='$authUrl'>Klik di sini untuk login ke akun Google Drive</a></p>";
} else {
    // Step 2: Handle the response from Google
    $accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (isset($accessToken['error'])) {
        echo "Error saat autentikasi: " . $accessToken['error_description'];
        exit;
    }

    $client->setAccessToken($accessToken);

    // Simpan token ke file
    if (!file_exists(dirname($tokenPath))) {
        mkdir(dirname($tokenPath), 0755, true);
    }
    file_put_contents($tokenPath, json_encode($client->getAccessToken()));

    echo "<h3>Berhasil!</h3>";
    echo "<p>Token berhasil disimpan. Anda bisa menutup halaman ini.</p>";
}
