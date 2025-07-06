<?php
require_once('../config/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client = $_POST['client'] ?? '';
    $status = $_POST['status'] ?? 'OK';
    $file_count = (int) ($_POST['file_count'] ?? 0);

    if ($client !== '') {
        $stmt = $conn->prepare("
            INSERT INTO sync_status (client, last_sync, status, file_count)
            VALUES (?, NOW(), ?, ?)
            ON DUPLICATE KEY UPDATE
            last_sync = VALUES(last_sync),
            status = VALUES(status),
            file_count = VALUES(file_count)
        ");
        $stmt->execute([$client, $status, $file_count]);
        echo "OK";
    } else {
        echo "No client name";
    }
}
