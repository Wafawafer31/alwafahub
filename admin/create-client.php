<?php
// admin/create-client.php – Form pembuatan folder klien otomatis via web admin

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nama_klien'])) {
    $namaKlien = trim($_POST['nama_klien']);
    $folderName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $namaKlien));
    $basePath = __DIR__ . "/../clients/$folderName";

    if (!is_dir($basePath)) {
        mkdir($basePath, 0777, true);
        foreach (['thumbs', 'selected', 'output'] as $dir) {
            mkdir("$basePath/$dir", 0777, true);
        }

        file_put_contents("$basePath/index.php", "<?php\n// Halaman untuk klien: $namaKlien\n");

        $config = [
            "event" => $namaKlien,
            "layout" => "default",
            "frame" => "classic.png"
        ];
        file_put_contents("$basePath/config.json", json_encode($config, JSON_PRETTY_PRINT));

        $message = "✅ Folder klien '<strong>$namaKlien</strong>' berhasil dibuat!";
    } else {
        $message = "⚠️ Folder untuk klien '<strong>$namaKlien</strong>' sudah ada.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Klien Baru – AlwafaHub</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <main style="max-width:600px;margin:50px auto;">
        <h2>🧩 Buat Folder Klien Baru</h2>
        <form method="post">
            <label for="nama_klien">Nama Klien / Event:</label><br>
            <input type="text" name="nama_klien" id="nama_klien" required style="width:100%;padding:10px;margin-top:10px;">
            <br><br>
            <button type="submit" class="btn">🚀 Buat Struktur Klien</button>
        </form>
        <div style="margin-top:30px;font-size:1.1rem;">
            <?= $message; ?>
        </div>
    </main>
</body>
</html>
