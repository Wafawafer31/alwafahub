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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <header class="hero" style="padding: 3rem 1rem;">
        <div class="hero-content">
            <h1>🧩 Buat Klien Baru</h1>
            <p>Buat struktur folder dan konfigurasi untuk klien atau event baru</p>
        </div>
    </header>

    <main class="admin-container">
        <div class="form-container">
            <form method="post" id="clientForm">
                <div class="form-group">
                    <label for="nama_klien">📝 Nama Klien / Event</label>
                    <input type="text" name="nama_klien" id="nama_klien" required 
                           placeholder="Contoh: Wedding Andi & Sari, Corporate Event PT ABC"
                           style="font-size: 1rem;">
                    <small style="color: var(--gray-500); font-size: 0.9rem; display: block; margin-top: 0.5rem;">
                        💡 Nama akan dikonversi menjadi folder (contoh: "Wedding Andi" → "wedding-andi")
                    </small>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                    🚀 Buat Struktur Klien
                </button>
            </form>

            <?php if (!empty($message)): ?>
                <div class="alert <?= strpos($message, '✅') !== false ? 'alert-success' : 'alert-warning'; ?>" style="margin-top: 1.5rem;">
                    <?= $message; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Info Section -->
        <section style="margin-top: 3rem;">
            <div class="card">
                <h3>📁 Struktur Folder yang Akan Dibuat</h3>
                <div style="background: var(--gray-50); padding: 1rem; border-radius: var(--radius-md); margin-top: 1rem; font-family: monospace;">
                    <div>📂 clients/</div>
                    <div>&nbsp;&nbsp;└── 📂 [nama-klien]/</div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;├── 📂 thumbs/ <small style="color: var(--gray-500);">(foto thumbnail)</small></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;├── 📂 selected/ <small style="color: var(--gray-500);">(foto terpilih)</small></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;├── 📂 output/ <small style="color: var(--gray-500);">(hasil kolase)</small></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;├── 📄 index.php <small style="color: var(--gray-500);">(halaman klien)</small></div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└── 📄 config.json <small style="color: var(--gray-500);">(konfigurasi)</small></div>
                </div>
            </div>
        </section>

        <!-- Navigation -->
        <div style="text-align: center; margin-top: 2rem;">
            <a href="index.php" class="btn btn-outline">← Kembali ke Dashboard</a>
        </div>
    </main>

    <script>
        // Form enhancement
        document.getElementById('clientForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            const clientName = document.getElementById('nama_klien').value.trim();
            
            if (clientName.length < 3) {
                e.preventDefault();
                alert('⚠️ Nama klien harus minimal 3 karakter');
                return;
            }
            
            submitBtn.classList.add('loading');
            submitBtn.innerHTML = '⏳ Membuat struktur...';
        });
        
        // Auto-focus and preview
        document.getElementById('nama_klien').focus();
        
        document.getElementById('nama_klien').addEventListener('input', function() {
            const value = this.value.trim();
            const folderName = value.toLowerCase().replace(/[^a-zA-Z0-9]/g, '-');
            const preview = document.querySelector('.folder-preview');
            
            if (!preview) {
                const previewDiv = document.createElement('div');
                previewDiv.className = 'folder-preview';
                previewDiv.style.cssText = 'margin-top: 0.5rem; padding: 0.5rem; background: var(--primary-50); border-radius: var(--radius-sm); font-size: 0.9rem; color: var(--primary-700);';
                this.parentNode.appendChild(previewDiv);
            }
            
            if (value) {
                document.querySelector('.folder-preview').innerHTML = `📁 Folder akan dibuat: <strong>clients/${folderName}/</strong>`;
            } else {
                document.querySelector('.folder-preview').innerHTML = '';
            }
        });
    </script>
</body>
</html>
