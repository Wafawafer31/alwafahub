-- Database: alwafahub
CREATE DATABASE IF NOT EXISTS alwafahub CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE alwafahub;

-- Tabel admin user (login)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    reset_token VARCHAR(100),
    reset_expiry DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel klien
CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    folder_name VARCHAR(100) NOT NULL UNIQUE,
    drive_folder_id VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel status sinkronisasi Google Drive
CREATE TABLE sync_status (
    client VARCHAR(100) PRIMARY KEY,
    last_sync DATETIME,
    status VARCHAR(50),
    file_count INT
);

-- Tabel layout template kolase per klien
CREATE TABLE collage_templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client VARCHAR(100),
    name VARCHAR(100),
    json_config TEXT, -- konfigurasi layout (background, posisi, dll)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel blog
CREATE TABLE blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    content TEXT,
    featured_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel portofolio
CREATE TABLE portfolio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    description TEXT,
    image VARCHAR(255),
    category VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
