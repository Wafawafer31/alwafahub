<?php
// app/Models/ClientModel.php - Model untuk klien

require_once __DIR__ . '/../Config/database.php';

class ClientModel {
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
    
    public function getBySlug($slug) {
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
            
            $clientSlug = $this->generateSlug($data['client_name']);
            $templateConfig = json_encode($this->getDefaultTemplate($data['template_type'] ?? 'wedding'));
            
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
            error_log("Client creation failed: " . $e->getMessage());
        }
        
        return ['success' => false, 'message' => 'Gagal membuat klien'];
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
            error_log("Client update failed: " . $e->getMessage());
            return false;
        }
    }
    
    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM events WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Client deletion failed: " . $e->getMessage());
            return false;
        }
    }
    
    public function getCount() {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM events");
        $result = $stmt->fetch();
        return $result['count'];
    }
    
    private function generateSlug($name) {
        $slug = strtolower($name);
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Check if slug exists, add number if needed
        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    private function slugExists($slug) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM events WHERE client_slug = ?");
        $stmt->execute([$slug]);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
    
    private function getDefaultTemplate($type) {
        $templates = [
            'wedding' => [
                'layout_id' => 'wedding-layout',
                'canvas' => ['width' => 1200, 'height' => 800],
                'slots' => [
                    ['x' => 50, 'y' => 50, 'width' => 350, 'height' => 300],
                    ['x' => 450, 'y' => 50, 'width' => 350, 'height' => 300],
                    ['x' => 850, 'y' => 50, 'width' => 300, 'height' => 300],
                    ['x' => 50, 'y' => 400, 'width' => 350, 'height' => 300],
                    ['x' => 450, 'y' => 400, 'width' => 350, 'height' => 300],
                    ['x' => 850, 'y' => 400, 'width' => 300, 'height' => 300]
                ],
                'frame' => 'wedding-frame.png',
                'background_color' => '#ffffff'
            ],
            'birthday' => [
                'layout_id' => 'birthday-layout',
                'canvas' => ['width' => 1000, 'height' => 700],
                'slots' => [
                    ['x' => 100, 'y' => 50, 'width' => 400, 'height' => 300],
                    ['x' => 550, 'y' => 50, 'width' => 400, 'height' => 300],
                    ['x' => 100, 'y' => 400, 'width' => 400, 'height' => 250],
                    ['x' => 550, 'y' => 400, 'width' => 400, 'height' => 250]
                ],
                'frame' => 'birthday-frame.png',
                'background_color' => '#fff8dc'
            ],
            'corporate' => [
                'layout_id' => 'corporate-layout',
                'canvas' => ['width' => 1400, 'height' => 900],
                'slots' => [
                    ['x' => 50, 'y' => 50, 'width' => 300, 'height' => 200],
                    ['x' => 400, 'y' => 50, 'width' => 300, 'height' => 200],
                    ['x' => 750, 'y' => 50, 'width' => 300, 'height' => 200],
                    ['x' => 1100, 'y' => 50, 'width' => 250, 'height' => 200],
                    ['x' => 50, 'y' => 300, 'width' => 300, 'height' => 200],
                    ['x' => 400, 'y' => 300, 'width' => 300, 'height' => 200],
                    ['x' => 750, 'y' => 300, 'width' => 300, 'height' => 200],
                    ['x' => 1100, 'y' => 300, 'width' => 250, 'height' => 200]
                ],
                'frame' => 'corporate-frame.png',
                'background_color' => '#f8f9fa'
            ]
        ];
        
        return $templates[$type] ?? $templates['wedding'];
    }
}