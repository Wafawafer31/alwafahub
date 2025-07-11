# AlwafaHub - Professional Event Photo Gallery System

Sistem gallery foto profesional dengan fitur kolase otomatis, sinkronisasi cloud, dan manajemen klien terintegrasi.

## 📁 Struktur Direktori Baru

```
alwafahub/
├── app/                          # Core aplikasi
│   ├── Config/                   # Konfigurasi aplikasi
│   │   ├── app.php              # Konfigurasi utama
│   │   ├── database.php         # Konfigurasi database
│   │   └── events/              # Konfigurasi event (pindahan dari admin/config)
│   ├── Controllers/             # Controllers MVC
│   │   ├── AdminController.php  # Controller admin
│   │   └── ClientController.php # Controller klien
│   ├── Models/                  # Models data
│   │   ├── EventModel.php       # Model event
│   │   └── PhotoModel.php       # Model foto
│   ├── Services/                # Business logic
│   │   └── CollageService.php   # Service kolase
│   ├── Libraries/               # Library custom (pindahan dari lib/)
│   │   ├── drive-sync.php       # Sinkronisasi Google Drive
│   │   ├── gd-helper.php        # Helper manipulasi gambar
│   │   └── google-drive-api.php # API Google Drive
│   └── Views/                   # Views (akan dibuat sesuai kebutuhan)
├── public/                      # Public assets & entry point
│   ├── index.php               # Entry point aplikasi
│   ├── assets/                 # Assets statis (pindahan dari assets/)
│   │   ├── css/
│   │   ├── js/
│   │   ├── images/
│   │   └── frames/
│   └── uploads/                # File upload
│       └── clients/            # Folder per klien
├── database/                   # Database management
│   ├── migrations/             # Migration files
│   └── seeds/                  # Seed data
├── resources/                  # Resources & templates
│   ├── views/                  # View templates
│   │   ├── homepage.php        # Homepage
│   │   └── errors/             # Error pages
│   └── templates/              # Template kolase (pindahan dari admin/templates)
├── storage/                    # Storage aplikasi
│   ├── logs/                   # Log files
│   ├── cache/                  # Cache files
│   └── sessions/               # Session files
├── tests/                      # Unit tests
├── vendor/                     # Composer dependencies
└── README.md                   # Dokumentasi
```

## 🚀 Fitur Utama

### ✅ Fitur yang Dipertahankan:
- **Admin Panel**: Dashboard lengkap untuk manajemen klien dan event
- **Gallery Klien**: Interface responsif untuk viewing dan seleksi foto
- **Kolase Generator**: Sistem otomatis pembuat kolase dengan template
- **Download System**: Download individual dan bulk ZIP
- **Photo Selection**: Sistem seleksi foto untuk kolase
- **Template Management**: Manajemen frame dan layout kolase
- **Database Integration**: Struktur database yang proper
- **Responsive Design**: UI yang mobile-friendly

### 🆕 Peningkatan Struktur:
- **MVC Architecture**: Pemisahan yang jelas antara Model, View, Controller
- **Service Layer**: Business logic terpisah dari controller
- **Database Migrations**: Manajemen skema database yang terstruktur
- **Error Handling**: Halaman error yang proper (404, 500)
- **Routing System**: Routing sederhana tapi efektif
- **Autoloading**: Class autoloading untuk kemudahan development
- **Configuration Management**: Konfigurasi terpusat dan terorganisir

## 🔧 Instalasi & Setup

1. **Database Setup**:
   ```bash
   # Jalankan migrations
   mysql -u root -p alwafahub < database/migrations/001_create_events_table.sql
   mysql -u root -p alwafahub < database/migrations/002_create_photos_table.sql
   mysql -u root -p alwafahub < database/migrations/003_create_admin_users_table.sql
   ```

2. **Permissions**:
   ```bash
   chmod -R 755 public/uploads/
   chmod -R 755 storage/
   ```

3. **Web Server**:
   - Document root: `public/`
   - URL Rewriting: Aktifkan untuk clean URLs

## 📋 URL Structure

- `/` - Homepage
- `/admin` - Admin dashboard
- `/admin/login` - Admin login
- `/admin/create-client` - Buat klien baru
- `/clients/{slug}` - Gallery klien
- `/clients/{slug}/generate` - Generate kolase
- `/clients/{slug}/download-zip` - Download ZIP

## 🔐 Default Admin

- **Username**: `admin`
- **Password**: `alwafa123`
- **Email**: `admin@alwafahub.com`

## 📝 Migration dari Struktur Lama

Semua file dan fungsi telah dipindahkan dengan mapping berikut:

| Lama | Baru |
|------|------|
| `admin/` | `app/Controllers/AdminController.php` |
| `clients/` | `app/Controllers/ClientController.php` |
| `lib/` | `app/Libraries/` |
| `assets/` | `public/assets/` |
| `admin/config/` | `app/Config/events/` |
| `admin/templates/` | `resources/templates/` |

## 🛠️ Development

Struktur baru ini memungkinkan:
- **Scalability**: Mudah menambah fitur baru
- **Maintainability**: Code yang terorganisir dan mudah dipelihara
- **Testability**: Struktur yang mendukung unit testing
- **Security**: Separation of concerns yang lebih baik
- **Performance**: Autoloading dan caching yang efisien

## 📞 Support

Untuk bantuan teknis, hubungi tim AlwafaHub melalui:
- Email: info@alwafahub.com
- WhatsApp: +62 8XX-XXXX-XXXX