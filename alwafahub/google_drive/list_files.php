<?php
function listDriveFiles($service, $folderId) {
    $files = [];
    $pageToken = null;

    do {
        $response = $service->files->listFiles([
            'q' => "'$folderId' in parents and mimeType contains 'image/' and trashed = false",
            'spaces' => 'drive',
            'fields' => 'nextPageToken, files(id, name)',
            'pageToken' => $pageToken
        ]);

        foreach ($response->files as $file) {
            $files[] = [
                'id' => $file->getId(),
                'name' => $file->getName()
            ];
        }

        $pageToken = $response->getNextPageToken();
    } while ($pageToken != null);

    return $files;
}
