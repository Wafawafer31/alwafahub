<?php
// app/Config/app.php - Konfigurasi aplikasi utama

return [
    'app_name' => 'AlwafaHub',
    'app_version' => '1.0.0',
    'app_url' => $_SERVER['HTTP_HOST'] ?? 'localhost',
    'timezone' => 'Asia/Jakarta',
    
    // Database Configuration
    'database' => [
        'host' => 'localhost',
        'dbname' => 'alwafahub',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4'
    ],
    
    // File Upload Settings
    'upload' => [
        'max_size' => '10M',
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif'],
        'upload_path' => 'public/uploads/'
    ],
    
    // Session Settings
    'session' => [
        'lifetime' => 7200, // 2 hours
        'path' => 'storage/sessions/',
        'secure' => false,
        'httponly' => true
    ]
];