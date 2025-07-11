<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard Admin - AlwafaHub' ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f6fa;
            line-height: 1.6;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header h1 {
            font-size: 1.5rem;
        }
        
        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: #4834d4;
            color: white;
        }
        
        .btn-secondary {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        
        .actions-section {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }
        
        .action-card {
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s;
        }
        
        .action-card:hover {
            border-color: #667eea;
            transform: translateY(-3px);
        }
        
        .action-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .action-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #333;
        }
        
        .action-desc {
            color: #666;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .container {
                padding: 0 1rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>🎯 Dashboard Admin AlwafaHub</h1>
        <div class="header-actions">
            <span>👋 Selamat datang, <?= $_SESSION['admin_user'] ?? 'Admin' ?></span>
            <a href="/admin/logout" class="btn btn-secondary">🚪 Logout</a>
        </div>
    </header>
    
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-number"><?= $clients_count ?? 0 ?></div>
                <div class="stat-label">Total Klien</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">📅</div>
                <div class="stat-number"><?= $active_projects ?? 0 ?></div>
                <div class="stat-label">Proyek Aktif</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">💾</div>
                <div class="stat-number"><?= $storage_usage ?? '0 MB' ?></div>
                <div class="stat-label">Penggunaan Storage</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <div class="stat-number"><?= date('d/m/Y') ?></div>
                <div class="stat-label">Hari Ini</div>
            </div>
        </div>
        
        <div class="actions-section">
            <h2>🚀 Aksi Cepat</h2>
            <div class="actions-grid">
                <div class="action-card">
                    <div class="action-icon">➕</div>
                    <div class="action-title">Buat Klien Baru</div>
                    <div class="action-desc">Tambahkan klien baru dan setup gallery untuk event mereka</div>
                    <a href="/admin/create-client" class="btn btn-primary">Buat Klien</a>
                </div>
                
                <div class="action-card">
                    <div class="action-icon">📁</div>
                    <div class="action-title">Kelola File</div>
                    <div class="action-desc">Upload, organize, dan kelola file foto untuk semua klien</div>
                    <a href="#" class="btn btn-primary" onclick="alert('Fitur akan segera hadir!')">Kelola File</a>
                </div>
                
                <div class="action-card">
                    <div class="action-icon">🎨</div>
                    <div class="action-title">Template Kolase</div>
                    <div class="action-desc">Customize template dan frame untuk kolase foto</div>
                    <a href="#" class="btn btn-primary" onclick="alert('Fitur akan segera hadir!')">Edit Template</a>
                </div>
                
                <div class="action-card">
                    <div class="action-icon">📈</div>
                    <div class="action-title">Laporan & Analytics</div>
                    <div class="action-desc">Lihat statistik penggunaan dan performa sistem</div>
                    <a href="#" class="btn btn-primary" onclick="alert('Fitur akan segera hadir!')">Lihat Laporan</a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Auto refresh stats setiap 30 detik
        setInterval(() => {
            // Implementasi refresh stats jika diperlukan
        }, 30000);
        
        // Welcome message
        console.log('🎯 AlwafaHub Admin Dashboard loaded successfully!');
    </script>
</body>
</html>