/*
# Create Events Table

1. New Tables
   - `events`
     - `id` (int, primary key, auto increment)
     - `event_id` (varchar, unique identifier)
     - `client_name` (varchar, nama klien)
     - `client_slug` (varchar, slug untuk URL)
     - `event_title` (varchar, judul event)
     - `event_date` (date, tanggal event)
     - `template_config` (json, konfigurasi template)
     - `collage_status` (enum, status kolase)
     - `collage_filename` (varchar, nama file kolase)
     - `created_at` (timestamp)
     - `updated_at` (timestamp)

2. Security
   - Primary key dengan auto increment
   - Unique constraint pada event_id dan client_slug
   - Index pada kolom yang sering dicari

3. Features
   - JSON column untuk fleksibilitas konfigurasi template
   - Status tracking untuk kolase
   - Timestamp untuk audit trail
*/

CREATE TABLE IF NOT EXISTS events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_id VARCHAR(50) UNIQUE NOT NULL,
    client_name VARCHAR(100) NOT NULL,
    client_slug VARCHAR(100) UNIQUE NOT NULL,
    event_title VARCHAR(200) NOT NULL,
    event_date DATE NOT NULL,
    template_config JSON DEFAULT NULL,
    collage_status ENUM('pending', 'processing', 'completed', 'failed') DEFAULT 'pending',
    collage_filename VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create indexes for better performance
CREATE INDEX IF NOT EXISTS idx_events_client_slug ON events(client_slug);
CREATE INDEX IF NOT EXISTS idx_events_event_date ON events(event_date);
CREATE INDEX IF NOT EXISTS idx_events_status ON events(collage_status);