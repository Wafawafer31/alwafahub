<?php
$config = json_decode(file_get_contents(__DIR__ . '/config.json'), true);
$thumbDir = __DIR__ . '/thumbs/';
$selectedDir = __DIR__ . '/selected/';
$outputDir = __DIR__ . '/output/';

$thumbs = array_filter(scandir($thumbDir), fn($f) => preg_match('/\.jpe?g$/i', $f));
$selected = $config['selected_photos'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected = $_POST['selected'] ?? [];
    $config['selected_photos'] = $selected;
    file_put_contents(__DIR__ . '/config.json', json_encode($config, JSON_PRETTY_PRINT));
    echo "<p>✅ Foto berhasil dipilih. Siap buat kolase!</p>";
}
?>

<h2>Galeri Foto Event: <?= htmlspecialchars($config['event_title']) ?></h2>
<form method="post">
    <div style="display:flex; flex-wrap:wrap;">
        <?php foreach ($thumbs as $thumb): ?>
            <label style="margin:5px; text-align:center;">
                <img src="thumbs/<?= $thumb ?>" width="150" /><br>
                <input type="checkbox" name="selected[]" value="<?= $thumb ?>"
                    <?= in_array($thumb, $selected) ? 'checked' : '' ?>>
            </label>
        <?php endforeach; ?>
    </div>
    <button type="submit">Simpan Pilihan Foto</button>
</form>

<?php if ($config['collage_output']['status'] === 'done'): ?>
    <p>🎉 Kolase sudah tersedia: <a href="output/<?= $config['collage_output']['filename'] ?>" target="_blank">Download</a></p>
<?php elseif (count($selected) === count($config['template_layout']['slots'])): ?>
    <form method="post" action="generate.php">
        <button type="submit">Generate Kolase Sekarang</button>
    </form>
<?php else: ?>
    <p>🔔 Pilih <?= count($config['template_layout']['slots']) ?> foto untuk mulai membuat kolase.</p>
<?php endif; ?>
