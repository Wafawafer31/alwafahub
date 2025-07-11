/*
# Create Admin Users Table

1. New Tables
   - `admin_users`
     - `id` (int, primary key, auto increment)
     - `username` (varchar, unique)
     - `email` (varchar, unique)
     - `password_hash` (varchar, hashed password)
     - `full_name` (varchar, nama lengkap)
     - `role` (enum, peran admin)
     - `is_active` (boolean, status aktif)
     - `last_login` (timestamp, login terakhir)
     - `created_at` (timestamp)
     - `updated_at` (timestamp)

2. Security
   - Password hashing dengan bcrypt
   - Unique constraints pada username dan email
   - Role-based access control

3. Features
   - Multi-admin support
   - Activity tracking
   - Role management
*/

CREATE TABLE IF NOT EXISTS admin_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('super_admin', 'admin', 'editor') DEFAULT 'admin',
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create default admin user (password: alwafa123)
INSERT INTO admin_users (username, email, password_hash, full_name, role) VALUES 
('admin', 'admin@alwafahub.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'super_admin');

-- Create indexes
CREATE INDEX IF NOT EXISTS idx_admin_username ON admin_users(username);
CREATE INDEX IF NOT EXISTS idx_admin_email ON admin_users(email);
CREATE INDEX IF NOT EXISTS idx_admin_active ON admin_users(is_active);