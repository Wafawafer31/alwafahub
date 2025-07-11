<?php
require_once __DIR__ . '/../vendor/autoload.php';

function getGoogleClient() {
    $client = new Google_Client();
    $client->setAuthConfig(__DIR__ . '/../credentials.json'); // File kredensial OAuth
    $client->addScope(Google_Service_Drive::DRIVE_READONLY);
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Token bisa disimpan di file atau session
    $tokenPath = __DIR__ . '/../token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);

        // Refresh token jika expired
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
    } else {
        // Jika belum ada token, arahkan ke proses otorisasi manual
        echo "Token belum tersedia. Jalankan proses otorisasi terlebih dahulu.";
        exit;
    }

    return $client;
}

function getDriveFiles($accessToken, $folderId) {
    $client = getGoogleClient();
    $service = new Google_Service_Drive($client);

    $query = "'$folderId' in parents and mimeType contains 'image/' and trashed = false";
    $optParams = [
        'q' => $query,
        'fields' => 'files(id, name, mimeType)'
    ];

    $results = $service->files->listFiles($optParams);
    return $results->getFiles();
}

function downloadDriveFile($accessToken, $fileId) {
    $client = getGoogleClient();
    $service = new Google_Service_Drive($client);

    $response = $service->files->get($fileId, ['alt' => 'media']);
    return $response->getBody()->getContents();
}
