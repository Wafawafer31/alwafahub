<?php
require_once '../vendor/autoload.php';

function getDriveClient() {
    $client = new Google_Client();
    $client->setApplicationName('AlwafaHub RTPD');
    $client->setScopes(Google_Service_Drive::DRIVE_READONLY);
    $client->setAuthConfig('../config/credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    $tokenPath = '../config/token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // Refresh token if expired
    if ($client->isAccessTokenExpired()) {
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        } else {
            die("Token Google Drive kedaluwarsa. Silakan autentikasi ulang.");
        }
    }

    return new Google_Service_Drive($client);
}
