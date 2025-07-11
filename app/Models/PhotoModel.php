<?php
// app/Models/PhotoModel.php - Model untuk foto

require_once __DIR__ . '/../Config/database.php';

class PhotoModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getByEventId($eventId) {
        $stmt = $this->db->prepare("
            SELECT * FROM photos 
            WHERE event_id = ? 
            ORDER BY capture_time ASC, filename ASC
        ");
        $stmt->execute([$eventId]);
        return $stmt->fetchAll();
    }
    
    public function getSelected($eventId) {
        $stmt = $this->db->prepare("
            SELECT * FROM photos 
            WHERE event_id = ? AND is_selected = 1 
            ORDER BY selection_order ASC
        ");
        $stmt->execute([$eventId]);
        return $stmt->fetchAll();
    }
    
    public function create($data) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO photos (event_id, filename, original_name, file_size, mime_type, capture_time, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");
            
            return $stmt->execute([
                $data['event_id'],
                $data['filename'],
                $data['original_name'],
                $data['file_size'],
                $data['mime_type'],
                $data['capture_time'] ?? date('Y-m-d H:i:s')
            ]);
        } catch (PDOException $e) {
            error_log("Photo creation failed: " . $e->getMessage());
            return false;
        }
    }
    
    public function updateSelection($eventId, $selectedPhotos) {
        try {
            $this->db->beginTransaction();
            
            // Reset semua foto untuk event ini
            $stmt = $this->db->prepare("
                UPDATE photos 
                SET is_selected = 0, selection_order = NULL 
                WHERE event_id = ?
            ");
            $stmt->execute([$eventId]);
            
            // Set foto yang dipilih
            foreach ($selectedPhotos as $order => $filename) {
                $stmt = $this->db->prepare("
                    UPDATE photos 
                    SET is_selected = 1, selection_order = ? 
                    WHERE event_id = ? AND filename = ?
                ");
                $stmt->execute([$order + 1, $eventId, $filename]);
            }
            
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Photo selection update failed: " . $e->getMessage());
            return false;
        }
    }
    
    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM photos WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Photo deletion failed: " . $e->getMessage());
            return false;
        }
    }
    
    public function getCount($eventId = null) {
        if ($eventId) {
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM photos WHERE event_id = ?");
            $stmt->execute([$eventId]);
        } else {
            $stmt = $this->db->query("SELECT COUNT(*) as count FROM photos");
        }
        $result = $stmt->fetch();
        return $result['count'];
    }
}