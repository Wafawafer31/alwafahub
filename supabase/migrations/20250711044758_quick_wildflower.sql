/*
# Create Photos Table

1. New Tables
   - `photos`
     - `id` (int, primary key, auto increment)
     - `event_id` (int, foreign key ke events)
     - `filename` (varchar, nama file)
     - `original_name` (varchar, nama file asli)
     - `file_size` (int, ukuran file dalam bytes)
     - `mime_type` (varchar, tipe MIME)
     - `capture_time` (datetime, waktu pengambilan foto)
     - `is_selected` (boolean, apakah dipilih untuk kolase)
     - `selection_order` (int, urutan seleksi)
     - `created_at` (timestamp)
     - `updated_at` (timestamp)

2. Security
   - Foreign key constraint ke tabel events
   - Index pada kolom yang sering dicari

3. Features
   - Tracking metadata foto lengkap
   - Selection system untuk kolase
   - Ordering system untuk urutan foto
*/

CREATE TABLE IF NOT EXISTS photos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_id INT NOT NULL,
    filename VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    file_size INT DEFAULT 0,
    mime_type VARCHAR(50) DEFAULT NULL,
    capture_time DATETIME DEFAULT NULL,
    is_selected BOOLEAN DEFAULT FALSE,
    selection_order INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

-- Create indexes for better performance
CREATE INDEX IF NOT EXISTS idx_photos_event_id ON photos(event_id);
CREATE INDEX IF NOT EXISTS idx_photos_filename ON photos(filename);
CREATE INDEX IF NOT EXISTS idx_photos_selected ON photos(is_selected);
CREATE INDEX IF NOT EXISTS idx_photos_capture_time ON photos(capture_time);