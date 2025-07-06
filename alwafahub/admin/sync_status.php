<?php
require_once('../config/db.php');

// Ambil data status sync dari database
$stmt = $conn->prepare("SELECT * FROM sync_status ORDER BY last_sync DESC");
$stmt->execute();
$rows = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Status Sinkronisasi</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Status Sinkronisasi Google Drive</h2>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Nama Klien</th>
                <th>Waktu Terakhir Sinkron</th>
                <th>Jumlah File</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['client']) ?></td>
                    <td><?= $row['last_sync'] ?></td>
                    <td><?= $row['file_count'] ?></td>
                    <td><?= $row['status'] ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</body>
</html>
