<?php
session_start();
require_once('../config/db.php');

// Autentikasi admin
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM portfolio WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage_portfolio.php?msg=deleted");
    exit;
}

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $id = $_POST['id'] ?? null;
    $imagePath = $_POST['existing_image'] ?? '';

    // Upload gambar baru jika ada
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $newName = 'portfolio_' . time() . '.' . $ext;
        $uploadPath = '../images/portfolio/' . $newName;
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath);
        $imagePath = 'images/portfolio/' . $newName;
    }

    if ($id) {
        // Update
        $stmt = $conn->prepare("UPDATE portfolio SET title=?, description=?, image=? WHERE id=?");
        $stmt->execute([$title, $description, $imagePath, $id]);
    } else {
        // Insert
        $stmt = $conn->prepare("INSERT INTO portfolio (title, description, image, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$title, $description, $imagePath]);
    }

    header("Location: manage_portfolio.php?msg=saved");
    exit;
}

// Ambil semua entri portofolio
$stmt = $conn->prepare("SELECT * FROM portfolio ORDER BY created_at DESC");
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Jika mode edit
$editItem = null;
if (isset($_GET['edit'])) {
    $editId = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM portfolio WHERE id = ?");
    $stmt->execute([$editId]);
    $editItem = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Portofolio</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Kelola Portofolio</h2>
    <p><a href="dashboard.php">← Kembali ke Dashboard</a></p>

    <?php if (isset($_GET['msg'])): ?>
        <p style="color: green;">
            <?php if ($_GET['msg'] === 'saved') echo "Berhasil disimpan."; ?>
            <?php if ($_GET['msg'] === 'deleted') echo "Berhasil dihapus."; ?>
        </p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" style="margin-bottom: 30px;">
        <input type="hidden" name="id" value="<?= $editItem['id'] ?? '' ?>">
        <input type="hidden" name="existing_image" value="<?= $editItem['image'] ?? '' ?>">
        
        <label>Judul:</label><br>
        <input type="text" name="title" required value="<?= htmlspecialchars($editItem['title'] ?? '') ?>" style="width: 100%;"><br><br>
        
        <label>Deskripsi:</label><br>
        <textarea name="description" rows="5" style="width: 100%;"><?= htmlspecialchars($editItem['description'] ?? '') ?></textarea><br><br>
        
        <label>Gambar:</label><br>
        <?php if (!empty($editItem['image'])): ?>
            <img src="../<?= $editItem['image'] ?>" width="150"><br>
        <?php endif; ?>
        <input type="file" name="image"><br><br>

        <button type="submit"><?= $editItem ? 'Update' : 'Tambah' ?> Portofolio</button>
    </form>

    <h3>Daftar Portofolio</h3>
    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <tr>
            <th>Gambar</th>
            <th>Judul</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($items as $item): ?>
        <tr>
            <td>
                <?php if ($item['image']): ?>
                    <img src="../<?= $item['image'] ?>" width="100">
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($item['title']) ?></td>
            <td>
                <a href="manage_portfolio.php?edit=<?= $item['id'] ?>">Edit</a> |
                <a href="manage_portfolio.php?delete=<?= $item['id'] ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
