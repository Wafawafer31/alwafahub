<?php include 'auth.php'; ?>
<?php
// Ambil data dari database
require_once('../config/db.php');

$stmt = $conn->prepare("SELECT * FROM sync_status ORDER BY last_sync DESC");
$stmt->execute();
$syncRows = $stmt->fetchAll();
?>

<h3>Status Sinkronisasi Google Drive</h3>
<table border="1" cellpadding="8" cellspacing="0" style="width:100%; margin-top:10px;">
    <thead>
        <tr>
            <th>Nama Klien</th>
            <th>Waktu Terakhir Sinkron</th>
            <th>Jumlah File</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($syncRows as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['client']) ?></td>
                <td><?= $row['last_sync'] ?></td>
                <td><?= $row['file_count'] ?></td>
                <td><?= $row['status'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
$clientsFile = '../config/clients.json';
$clients = [];

if (file_exists($clientsFile)) {
    $clients = json_decode(file_get_contents($clientsFile), true);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - AlwafaHub</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <h2>Dashboard Admin - AlwafaHub</h2>

    <h2>Selamat Datang, Admin!</h2>

    <div class="dashboard-buttons">
        <a href="upload.php" class="btn btn-primary">📤 Upload Foto</a>
        <a href="sync_drive.php" class="btn btn-success">🔄 Sinkronisasi Google Drive</a>
        <a href="add_client.php" class="btn btn-warning">➕ Tambah Klien</a>
        <a href="layout_builder.php" class="btn btn-secondary">🖋 Edit Layout Website</a>
        <a href="blog_manager.php" class="btn btn-info">📝 Kelola Blog</a>
        <a href="portfolio_manager.php" class="btn btn-dark">🎨 Kelola Portofolio</a>
    </div>
        <p><a href="add_client.php">➕ Tambah Klien Baru</a></p>
        <p><a href="upload.php">⬆️ Upload Manual</a></p>

    <hr>

    <?php if (!$clients): ?>
        <p>Belum ada klien ditambahkan.</p>
    <?php else: ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <tr>
                <th>Nama Klien</th>
                <th>ID Folder Google Drive</th>
                <th>Jumlah Upload</th>
                <th>Jumlah Thumbnail</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($clients as $slug => $info): ?>
                <?php
                    $uploadDir = "../clients/$slug/uploads_unframed/";
                    $thumbDir = "../clients/$slug/thumbs/";

                    $uploadCount = is_dir($uploadDir) ? count(glob("$uploadDir*.{jpg,jpeg,png}", GLOB_BRACE)) : 0;
                    $thumbCount  = is_dir($thumbDir)  ? count(glob("$thumbDir*.{jpg,jpeg,png}", GLOB_BRACE)) : 0;
                ?>
                <tr>
                    <td><?= htmlspecialchars($info['display_name']) ?></td>
                    <td><?= htmlspecialchars($info['drive_folder_id']) ?></td>
                    <td><?= $uploadCount ?></td>
                    <td><?= $thumbCount ?></td>
                    <td>
                        <a href="sync_drive.php?client=<?= $slug ?>">🔄 Sync</a> |
                        <a href="collage_editor.php?client=<?= $slug ?>">🖼 Kolase</a> |
                        <a href="../alwafa.php?client=<?= $slug ?>" target="_blank">🔗 Lihat Galeri</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
