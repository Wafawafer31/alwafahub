<?php
// app/Controllers/ClientController.php - Controller untuk halaman klien

require_once __DIR__ . '/../Models/EventModel.php';
require_once __DIR__ . '/../Models/PhotoModel.php';
require_once __DIR__ . '/../Services/CollageService.php';

class ClientController {
    private $eventModel;
    private $photoModel;
    private $collageService;
    
    public function __construct() {
        $this->eventModel = new EventModel();
        $this->photoModel = new PhotoModel();
        $this->collageService = new CollageService();
    }
    
    public function gallery($clientSlug) {
        $event = $this->eventModel->getByClientSlug($clientSlug);
        if (!$event) {
            $this->show404();
            return;
        }
        
        $photos = $this->photoModel->getByEventId($event['id']);
        $photoGroups = $this->groupPhotosByTime($photos);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePhotoSelection($event['id'], $_POST);
        }
        
        $data = [
            'event' => $event,
            'photo_groups' => $photoGroups,
            'selected_photos' => $this->photoModel->getSelected($event['id'])
        ];
        
        $this->loadView('client/gallery', $data);
    }
    
    public function generateCollage($clientSlug) {
        $event = $this->eventModel->getByClientSlug($clientSlug);
        if (!$event) {
            $this->show404();
            return;
        }
        
        $selectedPhotos = $this->photoModel->getSelected($event['id']);
        $result = $this->collageService->generate($event, $selectedPhotos);
        
        if ($result['success']) {
            header("Location: /clients/{$clientSlug}?success=1&file=" . urlencode($result['filename']));
        } else {
            header("Location: /clients/{$clientSlug}?error=1");
        }
        exit;
    }
    
    public function downloadZip($clientSlug) {
        $event = $this->eventModel->getByClientSlug($clientSlug);
        if (!$event) {
            $this->show404();
            return;
        }
        
        $photos = $this->photoModel->getByEventId($event['id']);
        $this->createAndDownloadZip($photos, $clientSlug);
    }
    
    private function handlePhotoSelection($eventId, $postData) {
        $selected = $postData['selected'] ?? [];
        $this->photoModel->updateSelection($eventId, $selected);
    }
    
    private function groupPhotosByTime($photos) {
        $groups = [];
        $timeSlots = ['08:30-09:00', '09:30-10:00', '10:00-10:30', '11:00-11:30', '14:00-14:30', '15:30-16:00'];
        
        foreach ($photos as $index => $photo) {
            $timeSlot = $timeSlots[$index % count($timeSlots)];
            $timeClass = 'time-' . str_replace([':', '-'], ['-', '-'], $timeSlot);
            
            if (!isset($groups[$timeSlot])) {
                $groups[$timeSlot] = [
                    'class' => $timeClass,
                    'photos' => []
                ];
            }
            $groups[$timeSlot]['photos'][] = $photo;
        }
        
        return $groups;
    }
    
    private function createAndDownloadZip($photos, $clientSlug) {
        $zipName = "photos_{$clientSlug}_" . date('Ymd_His') . '.zip';
        $zipPath = sys_get_temp_dir() . '/' . $zipName;
        
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) !== TRUE) {
            die('Gagal membuat file ZIP');
        }
        
        foreach ($photos as $photo) {
            $filePath = __DIR__ . "/../../public/uploads/clients/{$clientSlug}/thumbs/" . $photo['filename'];
            if (file_exists($filePath)) {
                $zip->addFile($filePath, $photo['filename']);
            }
        }
        $zip->close();
        
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $zipName . '"');
        header('Content-Length: ' . filesize($zipPath));
        readfile($zipPath);
        unlink($zipPath);
        exit;
    }
    
    private function loadView($view, $data = []) {
        extract($data);
        require_once __DIR__ . "/../Views/{$view}.php";
    }
    
    private function show404() {
        http_response_code(404);
        $this->loadView('errors/404');
    }
}