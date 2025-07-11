<?php
// lib/drive-sync.php – Sinkronisasi file Google Drive (autoloaded via Composer)

namespace Lib;

use Google\Client;
use Google\Service\Drive;

class DriveSync {
    private $client;
    private $drive;

    public function __construct($credentialsPath, $tokenPath) {
        $this->client = new Client();
        $this->client->setApplicationName('AlwafaHub Drive Sync');
        $this->client->setScopes(Drive::DRIVE_READONLY);
        $this->client->setAuthConfig($credentialsPath);
        $this->client->setAccessType('offline');

        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $this->client->setAccessToken($accessToken);
        }

        if ($this->client->isAccessTokenExpired()) {
            if ($this->client->getRefreshToken()) {
                $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
                file_put_contents($tokenPath, json_encode($this->client->getAccessToken()));
            } else {
                throw new \Exception("🔐 Token Drive expired dan tidak dapat direfresh.");
            }
        }

        $this->drive = new Drive($this->client);
    }

    public function listFiles($folderId) {
        $files = [];
        $response = $this->drive->files->listFiles([
            'q' => "'$folderId' in parents and mimeType contains 'image/' and trashed = false",
            'fields' => 'files(id, name, mimeType)'
        ]);

        foreach ($response->getFiles() as $file) {
            $files[] = [
                'id' => $file->id,
                'name' => $file->name,
                'type' => $file->mimeType
            ];
        }

        return $files;
    }

    public function downloadFile($fileId, $savePath) {
        $response = $this->drive->files->get($fileId, ['alt' => 'media']);
        $content = $response->getBody()->getContents();
        file_put_contents($savePath, $content);
        return true;
    }
}
