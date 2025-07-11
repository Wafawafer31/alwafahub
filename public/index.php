<?php
// public/index.php - Entry point aplikasi dengan routing sederhana

session_start();

// Autoload classes
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../app/Controllers/',
        __DIR__ . '/../app/Models/',
        __DIR__ . '/../app/Services/',
        __DIR__ . '/../app/Config/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Simple routing
$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);
$segments = explode('/', trim($path, '/'));

try {
    switch ($segments[0]) {
        case '':
            // Homepage
            require_once __DIR__ . '/../resources/views/homepage.php';
            break;
            
        case 'admin':
            $controller = new AdminController();
            $action = $segments[1] ?? 'index';
            
            switch ($action) {
                case 'login':
                    $controller->login();
                    break;
                case 'logout':
                    $controller->logout();
                    break;
                case 'create-client':
                    $controller->createClient();
                    break;
                default:
                    $controller->index();
            }
            break;
            
        case 'clients':
            if (isset($segments[1])) {
                $controller = new ClientController();
                $clientSlug = $segments[1];
                $action = $segments[2] ?? 'gallery';
                
                switch ($action) {
                    case 'generate':
                        $controller->generateCollage($clientSlug);
                        break;
                    case 'download-zip':
                        $controller->downloadZip($clientSlug);
                        break;
                    default:
                        $controller->gallery($clientSlug);
                }
            } else {
                http_response_code(404);
                require_once __DIR__ . '/../resources/views/errors/404.php';
            }
            break;
            
        default:
            http_response_code(404);
            require_once __DIR__ . '/../resources/views/errors/404.php';
    }
} catch (Exception $e) {
    error_log("Application error: " . $e->getMessage());
    http_response_code(500);
    require_once __DIR__ . '/../resources/views/errors/500.php';
}