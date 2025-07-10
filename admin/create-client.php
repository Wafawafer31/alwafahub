<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventId = trim($_POST['event_id']);
    $clientName = trim($_POST['client_name']);
    $eventTitle = trim($_POST['event_title']);
    $eventDate = $_POST['event_date'];

    // Validasi sederhana
    if (!$eventId || !$clientName || !$eventTitle || !$eventDate) {
        echo "<p style='color:red;'>❌ Semua field wajib diisi.</p>";
    } else {
        // Buat konfigurasi JSON
        $config = [
            "event_id" => $eventId,
            "client_name" => $clientName,
            "event_title" => $eventTitle,
            "event_date" => $eventDate,
            "page_url" => "/clients/" . strtolower($clientName) . "/",
            "template_layout" => [
                "layout_id" => "layout-default",
                "canvas" => ["width" => 1000, "height" => 700],
                "slots" => [],
                "frame" => "frame01.png",
                "background_color" => "#ffffff"
            ],
            "sync_settings" => [
                "ftp_monitor" => true,
                "drive_monitor" => true,
                "drive_folder_id" => "",
                "auto_import_interval" => 15
            ],
            "thumbnail_settings" => [
                "sort_by" => "capture_time",
                "time_filter_enabled" => true,
                "refresh_interval" => 10
            ],
            "selected_photos" => [],
            "collage_output" => [
                "status" => "pending",
                "filename" => "collage-" . strtolower($clientName) . ".jpg"
            ]
        ];

        // Simpan konfigurasi ke folder /admin/config/
        $configPath = __DIR__ . "/config/event-" . strtolower($clientName) . ".json";
        file_put_contents($configPath, json_encode($config, JSON_PRETTY_PRINT));

        // Buat folder sinkronisasi modular
        $ftpDir = __DIR__ . "/photos/ftp/evt_" . $eventId . "/";
        $driveDir = __DIR__ . "/photos/drive/evt_" . $eventId . "/";
        if (!is_dir($ftpDir)) mkdir($ftpDir, 0777, true);
        if (!is_dir($driveDir)) mkdir($driveDir, 0777, true);

        // Buat folder klien publik
        $clientDir = __DIR__ . "/../clients/" . strtolower($clientName) . "/";
        $subDirs = ['thumbs', 'selected', 'output'];
        foreach ($subDirs as $sub) {
            $path = $clientDir . $sub . "/";
            if (!is_dir($path)) mkdir($path, 0777, true);
        }

        // Buat file config.json lokal klien
        file_put_contents($clientDir . "config.json", json_encode($config, JSON_PRETTY_PRINT));

        echo "<p style='color:green;'>✅ Klien berhasil dibuat dan folder sinkronisasi telah disiapkan.</p>";
    }
}
?>

<h2>🧾 Tambah Klien Baru</h2>
<form method="post">
    <label>Event ID:</label><br>
    <input type="text" name="event_id" placeholder="evt_003" required><br><br>

    <label>Nama Klien:</label><br>
    <input type="text" name="client_name" placeholder="Anisa" required><br><br>

    <label>Judul Event:</label><br>
    <input type="text" name="event_title" placeholder="Wedding Anisa & Rafi" required><br><br>

    <label>Tanggal Event:</label><br>
    <input type="date" name="event_date" required><br><br>

    <button type="submit">➕ Buat Klien & Struktur Folder</button>
</form>
