<?php
if (session_id() == '') {
    session_start();
}
require '../config/database.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 1) {
    header('Location: dashboard.php');
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: categories_list.php');
    exit;
}

$error = '';
try {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($name === '') {
            $error = 'Le nom de la catégorie est requis.';
        } else {
            $stmt = $pdo->prepare('UPDATE categories SET name = :name, description = :description WHERE id = :id');
            $stmt->execute([':name' => $name, ':description' => $description, ':id' => $id]);
            header('Location: categories_list.php');
            exit;
        }
    }

    $stmt = $pdo->prepare('SELECT id, name, description FROM categories WHERE id = ?');
    $stmt->execute([$id]);
    $cat = $stmt->fetch();
    if (!$cat) {
        header('Location: categories_list.php');
        exit;
    }
} catch (PDOException $e) {
    $error = 'Erreur ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Modifier catégorie</title>
    
    <style>
        body{
        font-family:Arial,Helvetica,sans-serif;
        background:#f4f6f9;
        padding:30px
    }
        .box{
            max-width:700px;
            margin:0 auto;
            background:#fff;
            padding:20px;
            border-radius:8px;
            box-shadow:0 4px 12px rgba(0,0,0,0.06)
        }
        label{display:block;margin:10px 0 6px;font-weight:600
        }
        input[type="text"], 
        textarea{width:100%;padding:8px;
            border:1px solid #ccc;border-radius:6px}
        .actions{
            margin-top:12px
        }
        .btn{
            display:inline-block;
            padding:8px 14px;
            border-radius:6px;
            border:none;cursor:pointer;
            font-weight:600}
        .btn.save{
            background:#198754;
            color:#fff
        }
        .btn.cancel{
            background:#6c757d;
            color:#fff;
            text-decoration:none;
            padding:8px 12px;
            border-radius:6px}
        .error{
            background:#f8d7da;
            color:#721c24;
            padding:8px;
            border-radius:6px;
            margin-top:10px
        }
    </style>
</head>
<body>
    <div class="box">
        <h2>Modifier la catégorie</h2>
        <?php if ($error) { ?><div class="error"><?= htmlspecialchars($error) ?></div><?php } ?>

        <form method="post">
            <label for="name">Nom</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($cat['name']) ?>" required>

            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4"><?= htmlspecialchars($cat['description']) ?></textarea>

            <div class="actions">
                <button type="submit" class="btn save">Enregistrer</button>
                <a href="categories_list.php" class="btn cancel">Annuler</a>
            </div>
        </form>
    </div>
</body>
</html>
