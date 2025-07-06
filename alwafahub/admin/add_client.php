<?php include 'auth.php'; ?>
<?php
$configFile = '../config/clients.json';
$messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientName = trim($_POST['client_name'] ?? '');
    $driveFolderId = trim($_POST['drive_folder_id'] ?? '');

    if (!$clientName || !$driveFolderId) {
        $messages[] = "Nama klien dan ID folder Google Drive wajib diisi.";
    } else {
        $safeName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', strtolower($clientName));
        $clientPath = "../clients/$safeName";

        // Buat folder klien jika belum ada
        if (!is_dir($clientPath)) {
            mkdir("$clientPath/uploads_unframed", 0777, true);
            mkdir("$clientPath/thumbs", 0777, true);
        }

        // Simpan ke config
        $clients = [];
        if (file_exists($configFile)) {
            $json = file_get_contents($configFile);
            $clients = json_decode($json, true) ?: [];
        }

        $clients[$safeName] = [
            'display_name' => $clientName,
            'drive_folder_id' => $driveFolderId
        ];

        file_put_contents($configFile, json_encode($clients, JSON_PRETTY_PRINT));

        $messages[] = "Klien '$clientName' berhasil ditambahkan.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Klien Baru - Admin AlwafaHub</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <h2>Tambah Klien Baru</h2>

    <form action="add_client.php" method="post">
        <label>Nama Klien:</label><br>
        <input type="text" name="client_name" required><br><br>

        <label>ID Folder Google Drive:</label><br>
        <input type="text" name="drive_folder_id" required><br><br>

        <button type="submit">Tambahkan Klien</button>
    </form>

    <div style="margin-top:20px;">
        <?php foreach ($messages as $msg): ?>
            <p><?= htmlspecialchars($msg) ?></p>
        <?php endforeach; ?>
    </div>
</body>
</html>
