<?php
// app/Controllers/AdminController.php - Controller untuk admin panel

require_once __DIR__ . '/../Models/EventModel.php';
require_once __DIR__ . '/../Models/ClientModel.php';

class AdminController {
    private $eventModel;
    private $clientModel;
    
    public function __construct() {
        $this->eventModel = new EventModel();
        $this->clientModel = new ClientModel();
    }
    
    public function index() {
        $this->checkAuth();
        
        $data = [
            'title' => 'Dashboard Admin - AlwafaHub',
            'clients_count' => $this->clientModel->getCount(),
            'active_projects' => $this->eventModel->getActiveCount(),
            'storage_usage' => $this->getStorageUsage()
        ];
        
        $this->loadView('admin/dashboard', $data);
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if ($this->authenticate($username, $password)) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_user'] = $username;
                header('Location: /admin');
                exit;
            } else {
                $error = "Username atau password salah";
            }
        }
        
        $this->loadView('admin/login', ['error' => $error ?? null]);
    }
    
    public function logout() {
        session_destroy();
        header('Location: /admin/login');
        exit;
    }
    
    public function createClient() {
        $this->checkAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->clientModel->create($_POST);
            if ($result['success']) {
                $this->createClientDirectories($result['data']);
                $message = "Klien berhasil dibuat";
            } else {
                $error = $result['message'];
            }
        }
        
        $this->loadView('admin/create-client', [
            'message' => $message ?? null,
            'error' => $error ?? null
        ]);
    }
    
    private function checkAuth() {
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header('Location: /admin/login');
            exit;
        }
    }
    
    private function authenticate($username, $password) {
        // Implementasi autentikasi sederhana
        return ($username === 'admin' && $password === 'alwafa123');
    }
    
    private function loadView($view, $data = []) {
        extract($data);
        require_once __DIR__ . "/../Views/{$view}.php";
    }
    
    private function getStorageUsage() {
        $size = 0;
        $path = __DIR__ . '/../../public/uploads';
        if (is_dir($path)) {
            foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $file) {
                $size += $file->getSize();
            }
        }
        return $this->formatBytes($size);
    }
    
    private function formatBytes($size, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }
        return round($size, $precision) . ' ' . $units[$i];
    }
    
    private function createClientDirectories($clientData) {
        $clientSlug = strtolower($clientData['client_name']);
        $basePath = __DIR__ . "/../../public/uploads/clients/{$clientSlug}";
        
        $directories = ['thumbs', 'selected', 'output', 'temp'];
        foreach ($directories as $dir) {
            $path = "{$basePath}/{$dir}";
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
        }
    }
}