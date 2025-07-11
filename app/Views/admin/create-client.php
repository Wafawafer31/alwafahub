<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Klien Baru - AlwafaHub Admin</title>
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
        
        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        
        .btn-secondary {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        
        .btn-primary {
            background: #4834d4;
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .form-header h2 {
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .form-header p {
            color: #666;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e1e5e9;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            padding: 0.75rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            border-left: 4px solid #28a745;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 0.75rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            border-left: 4px solid #dc3545;
        }
        
        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
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
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>➕ Buat Klien Baru</h1>
        <a href="/admin" class="btn btn-secondary">← Kembali ke Dashboard</a>
    </header>
    
    <div class="container">
        <div class="form-container">
            <div class="form-header">
                <h2>📝 Form Klien Baru</h2>
                <p>Isi informasi klien dan event untuk membuat gallery baru</p>
            </div>
            
            <?php if (isset($message)): ?>
                <div class="success">
                    ✅ <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="error">
                    ⚠️ <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="/admin/create-client">
                <div class="form-row">
                    <div class="form-group">
                        <label for="event_id">🆔 Event ID</label>
                        <input type="text" id="event_id" name="event_id" required 
                               placeholder="Contoh: WED2024001" 
                               value="<?= 'EVT' . date('Ymd') . rand(100, 999) ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="event_date">📅 Tanggal Event</label>
                        <input type="date" id="event_date" name="event_date" required 
                               value="<?= date('Y-m-d') ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="client_name">👤 Nama Klien</label>
                    <input type="text" id="client_name" name="client_name" required 
                           placeholder="Contoh: Budi & Sari">
                </div>
                
                <div class="form-group">
                    <label for="event_title">🎉 Judul Event</label>
                    <input type="text" id="event_title" name="event_title" required 
                           placeholder="Contoh: Wedding Budi & Sari">
                </div>
                
                <div class="form-group">
                    <label for="event_description">📝 Deskripsi Event (Opsional)</label>
                    <textarea id="event_description" name="event_description" 
                              placeholder="Deskripsi singkat tentang event ini..."></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="template_type">🎨 Template Kolase</label>
                        <select id="template_type" name="template_type">
                            <option value="wedding">Wedding Template</option>
                            <option value="birthday">Birthday Template</option>
                            <option value="corporate">Corporate Template</option>
                            <option value="graduation">Graduation Template</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="max_photos">📸 Maksimal Foto untuk Kolase</label>
                        <select id="max_photos" name="max_photos">
                            <option value="4">4 Foto</option>
                            <option value="6" selected>6 Foto</option>
                            <option value="8">8 Foto</option>
                            <option value="12">12 Foto</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">🚀 Buat Klien</button>
                    <a href="/admin" class="btn btn-secondary">❌ Batal</a>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Auto generate slug dari nama klien
        document.getElementById('client_name').addEventListener('input', function() {
            const name = this.value;
            const eventTitle = document.getElementById('event_title');
            if (name && !eventTitle.value) {
                eventTitle.value = `Event ${name}`;
            }
        });
        
        // Validasi tanggal tidak boleh masa lalu
        document.getElementById('event_date').addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate < today) {
                alert('⚠️ Tanggal event tidak boleh di masa lalu!');
                this.value = '';
            }
        });
        
        console.log('📝 Create Client form loaded successfully!');
    </script>
</body>
</html>