<?php include 'auth.php'; ?>
<?php
$clients = array_filter(glob("../clients/*"), 'is_dir');
$messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientName = $_POST['client'] ?? '';
    if (!$clientName || !isset($_FILES['photos'])) {
        $messages[] = "Klien atau file belum dipilih.";
    } else {
        $uploadDir = "../clients/$clientName/uploads_unframed/";
        $thumbDir = "../clients/$clientName/thumbs/";

        // Buat folder jika belum ada
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        if (!is_dir($thumbDir)) mkdir($thumbDir, 0777, true);

        foreach ($_FILES['photos']['tmp_name'] as $key => $tmpName) {
            $fileName = basename($_FILES['photos']['name'][$key]);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($tmpName, $targetPath)) {
                generateThumbnail($targetPath, $thumbDir . $fileName);
                $messages[] = "Upload berhasil: $fileName";
            } else {
                $messages[] = "Gagal upload: $fileName";
            }
        }
    }
}

function generateThumbnail($srcPath, $destPath, $thumbWidth = 300) {
    $imageInfo = getimagesize($srcPath);
    if (!$imageInfo) return;

    [$width, $height] = $imageInfo;
    $thumbHeight = floor($height * ($thumbWidth / $width));

    $thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);

    switch ($imageInfo['mime']) {
        case 'image/jpeg':
            $source = imagecreatefromjpeg($srcPath);
            break;
        case 'image/png':
            $source = imagecreatefrompng($srcPath);
            break;
        default:
            return;
    }

    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $width, $height);
    imagejpeg($thumb, $destPath, 80);

    imagedestroy($thumb);
    imagedestroy($source);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Foto Manual - Admin AlwafaHub</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <h2>Upload Foto Manual</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label>Pilih Klien:</label><br>
        <select name="client" required>
            <option value="">-- Pilih Klien --</option>
            <?php foreach ($clients as $path): 
                $name = basename($path); ?>
                <option value="<?= htmlspecialchars($name) ?>"><?= htmlspecialchars($name) ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Pilih Foto:</label><br>
        <input type="file" name="photos[]" multiple required><br><br>

        <button type="submit">Upload</button>
    </form>

    <div style="margin-top:20px;">
        <?php foreach ($messages as $msg): ?>
            <p><?= htmlspecialchars($msg) ?></p>
        <?php endforeach; ?>
    </div>
</body>
</html>
