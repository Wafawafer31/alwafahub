<?php
session_start();
require_once('../config/db.php');

// Cek autentikasi admin
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM blog_posts WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage_blog.php?msg=deleted");
    exit;
}

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    if (isset($_POST['id']) && $_POST['id'] != '') {
        // Edit mode
        $id = intval($_POST['id']);
        $stmt = $conn->prepare("UPDATE blog_posts SET title=?, content=? WHERE id=?");
        $stmt->execute([$title, $content, $id]);
    } else {
        // New post
        $stmt = $conn->prepare("INSERT INTO blog_posts (title, content, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$title, $content]);
    }
    header("Location: manage_blog.php?msg=saved");
    exit;
}

// Ambil semua postingan
$stmt = $conn->prepare("SELECT * FROM blog_posts ORDER BY created_at DESC");
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Jika edit, ambil data
$editPost = null;
if (isset($_GET['edit'])) {
    $editId = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM blog_posts WHERE id = ?");
    $stmt->execute([$editId]);
    $editPost = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Blog</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Kelola Blog</h2>
    <p><a href="dashboard.php">← Kembali ke Dashboard</a></p>

    <?php if (isset($_GET['msg'])): ?>
        <p style="color: green;">
            <?php if ($_GET['msg'] === 'saved') echo "Berhasil disimpan."; ?>
            <?php if ($_GET['msg'] === 'deleted') echo "Berhasil dihapus."; ?>
        </p>
    <?php endif; ?>

    <form method="POST" style="margin-bottom: 30px;">
        <input type="hidden" name="id" value="<?= $editPost['id'] ?? '' ?>">
        <label>Judul:</label><br>
        <input type="text" name="title" required value="<?= htmlspecialchars($editPost['title'] ?? '') ?>" style="width: 100%;"><br><br>
        <label>Isi:</label><br>
        <textarea name="content" rows="10" required style="width: 100%;"><?= htmlspecialchars($editPost['content'] ?? '') ?></textarea><br><br>
        <button type="submit"><?= $editPost ? 'Update' : 'Tambah' ?> Post</button>
    </form>

    <h3>Daftar Artikel</h3>
    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <tr>
            <th>Judul</th>
            <th>Tanggal</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($posts as $post): ?>
        <tr>
            <td><?= htmlspecialchars($post['title']) ?></td>
            <td><?= $post['created_at'] ?></td>
            <td>
                <a href="manage_blog.php?edit=<?= $post['id'] ?>">Edit</a> |
                <a href="manage_blog.php?delete=<?= $post['id'] ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
