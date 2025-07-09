<?php
function loadEventConfig($eventId) {
    $filePath = __DIR__ . "/config/event-{$eventId}.json";
    if (!file_exists($filePath)) {
        return null;
    }

    $json = file_get_contents($filePath);
    return json_decode($json, true);
}

function saveEventConfig($eventId, $data) {
    $filePath = __DIR__ . "/config/event-{$eventId}.json";
    $json = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents($filePath, $json);
}

// Contoh penggunaan:
$eventId = 'yoga-merryna';
$config = loadEventConfig($eventId);

if ($config) {
    echo "<h2>Event: " . $config['event_title'] . "</h2>";
    echo "<p>Tanggal: " . $config['event_date'] . "</p>";
    echo "<p>Client: " . $config['client_name'] . "</p>";
    echo "<p>Layout ID: " . $config['template_layout']['layout_id'] . "</p>";
} else {
    echo "<p>Config event tidak ditemukan.</p>";
}
?>
