<?php
// app/Models/EventModel.php - Model untuk event/acara

require_once __DIR__ . '/../Config/database.php';

class EventModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM events ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getByClientSlug($slug) {
        $stmt = $this->db->prepare("SELECT * FROM events WHERE client_slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO events (event_id, client_name, client_slug, event_title, event_date, template_config, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");
            
            $clientSlug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $data['client_name']));
            $templateConfig = json_encode($this->getDefaultTemplate());
            
            $result = $stmt->execute([
                $data['event_id'],
                $data['client_name'],
                $clientSlug,
                $data['event_title'],
                $data['event_date'],
                $templateConfig
            ]);
            
            if ($result) {
                return [
                    'success' => true,
                    'data' => array_merge($data, ['client_slug' => $clientSlug])
                ];
            }
        } catch (PDOException $e) {
            error_log("Event creation failed: " . $e->getMessage());
        }
        
        return ['success' => false, 'message' => 'Gagal membuat event'];
    }
    
    public function update($id, $data) {
        try {
            $stmt = $this->db->prepare("
                UPDATE events 
                SET client_name = ?, event_title = ?, event_date = ?, template_config = ?, updated_at = NOW()
                WHERE id = ?
            ");
            
            return $stmt->execute([
                $data['client_name'],
                $data['event_title'],
                $data['event_date'],
                json_encode($data['template_config']),
                $id
            ]);
        } catch (PDOException $e) {
            error_log("Event update failed: " . $e->getMessage());
            return false;
        }
    }
    
    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM events WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Event deletion failed: " . $e->getMessage());
            return false;
        }
    }
    
    public function getCount() {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM events");
        $result = $stmt->fetch();
        return $result['count'];
    }
    
    public function getActiveCount() {
        $stmt = $this->db->query("
            SELECT COUNT(*) as count FROM events 
            WHERE event_date >= CURDATE() OR collage_status = 'pending'
        ");
        $result = $stmt->fetch();
        return $result['count'];
    }
    
    private function getDefaultTemplate() {
        return [
            'layout_id' => 'layout-default',
            'canvas' => ['width' => 1000, 'height' => 700],
            'slots' => [
                ['x' => 100, 'y' => 50, 'width' => 400, 'height' => 300],
                ['x' => 550, 'y' => 50, 'width' => 400, 'height' => 300]
            ],
            'frame' => 'frame01.png',
            'background_color' => '#ffffff'
        ];
    }
}