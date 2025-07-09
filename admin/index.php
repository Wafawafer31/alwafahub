<?php
// admin/index.php – Dashboard admin AlwafaHub
require_once __DIR__ . '/../lib/db.php'; // jika ada koneksi DB
require_once 'auth-check.php'; // proteksi login admin
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin – AlwafaHub</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <header class="hero">
        <div class="hero-content">
            <h1>🎛️ Dashboard Admin</h1>
            <p>Kelola klien, event, dan kolase digital dengan mudah dan efisien</p>
        </div>
    </header>

    <main class="admin-container">
        <div class="admin-grid">
            <div class="admin-card">
                <h3>📦 Manajemen Klien & Event</h3>
                <ul class="admin-actions">
                    <li><a href="create-client.php">➕ Buat Folder Klien Baru</a></li>
                    <li><a href="sync/ftp-monitor.php">🔄 Sinkronisasi Foto via FTP</a></li>
                    <li><a href="sync/drive-sync.php">☁️ Sinkronisasi Google Drive</a></li>
                </ul>
            </div>

            <div class="admin-card">
                <h3>🖼️ Preview & Template Kolase</h3>
                <ul class="admin-actions">
                    <li><a href="templates/">🎨 Manajemen Frame Kolase</a></li>
                    <li><a href="kolase-preview/">👀 Lihat Preview Kolase</a></li>
                    <li><a href="template-editor.php">✏️ Edit Template Autoframe</a></li>
                </ul>
            </div>

            <div class="admin-card">
                <h3>📊 Statistik & Monitoring</h3>
                <ul class="admin-actions">
                    <li><a href="#" onclick="showStats()">📈 Lihat Statistik Klien</a></li>
                    <li><a href="#" onclick="showActivity()">🔍 Log Aktivitas</a></li>
                    <li><a href="#" onclick="showStorage()">💾 Status Storage</a></li>
                </ul>
            </div>

            <div class="admin-card">
                <h3>⚙️ Pengaturan Sistem</h3>
                <ul class="admin-actions">
                    <li><a href="#" onclick="showSettings()">🔧 Konfigurasi Sistem</a></li>
                    <li><a href="#" onclick="showBackup()">💾 Backup & Restore</a></li>
                    <li><a href="logout.php">🚪 Logout</a></li>
                </ul>
            </div>
        </div>

        <!-- Quick Stats Section -->
        <section style="margin-top: 3rem;">
            <h2>📊 Quick Overview</h2>
            <div class="services-grid">
                <div class="service-card">
                    <span class="service-icon">👥</span>
                    <h3>Total Klien</h3>
                    <p style="font-size: 2rem; font-weight: bold; color: var(--primary-600);">
                        <?php
                        $clientsDir = __DIR__ . '/../clients';
                        $clientCount = is_dir($clientsDir) ? count(array_diff(scandir($clientsDir), array('.', '..'))) : 0;
                        echo $clientCount;
                        ?>
                    </p>
                </div>
                <div class="service-card">
                    <span class="service-icon">📁</span>
                    <h3>Project Aktif</h3>
                    <p style="font-size: 2rem; font-weight: bold; color: var(--accent-600);">
                        <?php
                        // Count active projects (folders with photos)
                        $activeProjects = 0;
                        if (is_dir($clientsDir)) {
                            foreach (scandir($clientsDir) as $client) {
                                if ($client != '.' && $client != '..' && is_dir("$clientsDir/$client")) {
                                    $photosDir = "$clientsDir/$client/thumbs";
                                    if (is_dir($photosDir) && count(array_diff(scandir($photosDir), array('.', '..'))) > 0) {
                                        $activeProjects++;
                                    }
                                }
                            }
                        }
                        echo $activeProjects;
                        ?>
                    </p>
                </div>
                <div class="service-card">
                    <span class="service-icon">🖼️</span>
                    <h3>Template Tersedia</h3>
                    <p style="font-size: 2rem; font-weight: bold; color: var(--primary-500);">
                        <?php
                        $templatesDir = __DIR__ . '/../assets/frames';
                        $templateCount = is_dir($templatesDir) ? count(glob("$templatesDir/*.png")) : 0;
                        echo $templateCount;
                        ?>
                    </p>
                </div>
                <div class="service-card">
                    <span class="service-icon">💾</span>
                    <h3>Storage Usage</h3>
                    <p style="font-size: 1.5rem; font-weight: bold; color: var(--accent-500);">
                        <?php
                        function formatBytes($size, $precision = 2) {
                            $units = array('B', 'KB', 'MB', 'GB', 'TB');
                            for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
                                $size /= 1024;
                            }
                            return round($size, $precision) . ' ' . $units[$i];
                        }
                        
                        function getDirSize($dir) {
                            $size = 0;
                            if (is_dir($dir)) {
                                foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir)) as $file) {
                                    $size += $file->getSize();
                                }
                            }
                            return $size;
                        }
                        
                        $totalSize = getDirSize(__DIR__ . '/../clients');
                        echo formatBytes($totalSize);
                        ?>
                    </p>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; <?= date('Y'); ?> AlwafaHub Admin Panel</p>
    </footer>

    <script>
        // Admin Dashboard Interactions
        function showStats() {
            alert('📊 Fitur statistik akan segera hadir!\n\nFitur ini akan menampilkan:\n• Grafik aktivitas klien\n• Statistik penggunaan template\n• Report bulanan');
        }
        
        function showActivity() {
            alert('🔍 Log aktivitas akan segera hadir!\n\nFitur ini akan menampilkan:\n• Riwayat login admin\n• Aktivitas pembuatan klien\n• Log sinkronisasi');
        }
        
        function showStorage() {
            alert('💾 Monitor storage akan segera hadir!\n\nFitur ini akan menampilkan:\n• Penggunaan disk per klien\n• Cleanup otomatis\n• Backup status');
        }
        
        function showSettings() {
            alert('🔧 Pengaturan sistem akan segera hadir!\n\nFitur ini akan menampilkan:\n• Konfigurasi database\n• Setting email\n• API configurations');
        }
        
        function showBackup() {
            alert('💾 Backup system akan segera hadir!\n\nFitur ini akan menampilkan:\n• Automated backup\n• Restore points\n• Cloud backup sync');
        }
        
        // Auto-refresh stats every 30 seconds
        setInterval(() => {
            // In production, this would fetch updated stats via AJAX
            console.log('Stats refreshed at:', new Date().toLocaleTimeString());
        }, 30000);
    </script>
</body>
</html>
