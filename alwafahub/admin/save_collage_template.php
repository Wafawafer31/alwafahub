<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    http_response_code(403);
    echo "Akses ditolak";
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['client']) || !isset($data['html']) || !isset($data['style'])) {
    http_response_code(400);
    echo "Data tidak lengkap";
    exit;
}

$client = preg_replace('/[^a-zA-Z0-9_\-]/', '', $data['client']);
$templateDir = __DIR__ . "/../clients/{$client}/template";

if (!is_dir($templateDir)) {
    mkdir($templateDir, 0755, true);
}

$templateFile = "{$templateDir}/collage_template.json";
$templateData = [
    "html" => $data['html'],
    "style" => $data['style'],
    "saved_at" => date("Y-m-d H:i:s")
];

file_put_contents($templateFile, json_encode($templateData, JSON_PRETTY_PRINT));

echo "Template berhasil disimpan.";
