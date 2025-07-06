<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$templateFile = __DIR__ . '/../config/layout_template.html';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['html'])) {
    file_put_contents($templateFile, $_POST['html']);
    echo json_encode(['success' => true]);
    exit;
}

$layoutContent = file_exists($templateFile) ? file_get_contents($templateFile) : "<section><h1>Selamat Datang di Alwafa Hub</h1></section>";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Layout Builder – Visual Editor</title>
    <link href="https://unpkg.com/grapesjs/dist/css/grapes.min.css" rel="stylesheet"/>
    <style>
        body, html {
            height: 100%;
            margin: 0;
        }
        #gjs {
            height: 100vh;
            border: none;
        }
        .save-btn {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 9999;
            background: #333;
            color: white;
            padding: 10px 20px;
            border: none;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<button class="save-btn" onclick="saveLayout()">💾 Simpan Layout</button>

<div id="gjs"><?= $layoutContent ?></div>

<script src="https://unpkg.com/grapesjs"></script>
<script>
    const editor = grapesjs.init({
        container: '#gjs',
        fromElement: true,
        height: '100%',
        width: 'auto',
        storageManager: { autoload: false },
        plugins: ['gjs-blocks-basic'],
        pluginsOpts: {
            'gjs-blocks-basic': {}
        }
    });

    function saveLayout() {
        const html = editor.getHtml();
        fetch('layout_builder.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'html=' + encodeURIComponent(html)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) alert("Layout berhasil disimpan!");
        });
    }
</script>

</body>
</html>
