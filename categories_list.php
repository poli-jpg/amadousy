<?php
if (session_id() == '') {
    session_start();
}
if (!isset($pdo)) {
    require '../config/database.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajout'])) {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($name === '') {
        $error_message = 'Le nom de la catégorie est requis';
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO categories (name, description) VALUES (:name, :description)');
            $stmt->execute([':name' => $name, ':description' => $description]);
            header('Location: categories_list.php');
            exit;
        } catch (PDOException $e) {
            $error_message = 'Erreur  : ' . $e->getMessage();
        }
    }
}
try {
    $categories = $pdo->query("SELECT id, name, description FROM categories ORDER BY id")->fetchAll();
} catch (PDOException $e) {
    try {
        $categories = $pdo->query("SELECT * FROM categories ORDER BY id")->fetchAll();
    } catch (PDOException $e2) {
        $categories = [];
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des catégories</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(rgba(255, 255, 255, 1), rgba(255, 255, 255, 1));
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 2200px;
            margin: 0 auto;
            padding: 20px;
        }

        .content-section {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .categories-title {
            text-align: center;
            color: #333;
            font-size: 2em;
            font-weight: 700;
        }
        .actions-bar{
            display:flex;
            justify-content:space-between;
            margin-bottom:15px;
}

         .actions-bar a{
           background:#0d6efd;
           color:#fff;
           padding:7px 12px;
           text-decoration:none;
           border-radius:5px;
}

        .categories-count {
            text-align: center;
            margin-bottom: 20px;
            color: #666;
            font-size: 1.1em;
        }


        .categories-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            
        }

        .categories-table thead {
            background: linear-gradient(#0d6efd 0%, #0d6efd 100%);
            color: #fff;
        }

        .categories-table th {
            padding: 20px;
            text-align: left;
            font-size: 1.1em;
        }

        .categories-table tbody tr:last-child {
            border-bottom: none;
        }

        .categories-table td {
            padding: 20px;
            color: #333;
            font-size: 1em;
        }

        .category-id {
            font-weight: 600;
            color: #667eea;
        }

        .category-name {
            font-weight: 600;
            color: #333;
        }

        .category-description {
            color: #666;
            max-width: 400px;
        }

        .action-btn {
            color: #fff;
            padding: 8px 15px;
            text-decoration: none;
            display: inline-block;
            margin-right: 5px;
            
        }

        .action-btn.edit {
            background: linear-gradient(45deg, #198754, #198754);
            
        }


        .action-btn.delete {
            background: linear-gradient(45deg, #dc3545, #dc3545);
            
        }


        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .empty-state h3 {
            margin-bottom: 15px;
            color: #333;
            font-size: 1.5em;
        }

        @media (max-width: 768px) {
            .categories-table th,
            .categories-table td {
                padding: 10px;
                font-size: 0.9em;
            }

            .action-btn {
                padding: 6px 12px;
                font-size: 0.8em;
            }
        }

    
        .add-category-box {
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .add-category-form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        .add-category-input {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .add-category-input.name {
            min-width: 220px;
        }

        .add-category-input.desc {
            min-width: 320px;
        }

        .add-category-btn {
            background: #0d6efd;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }

        .category-error {
            margin-top: 10px;
            color: #721c24;
            background: #f8d7da;
            padding: 8px;
            border-radius: 6px;
        }
    </style>
</head>
<body>

    <div class="container">
    <div class="content-section">
        <h2 class="categories-title">Liste des catégories</h2>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 1) { ?>
            <div class="add-category-box">
                <form method="post" class="add-category-form">
                    <label for="name">NOM</label>
                    <input type="text" name="name"  required class="add-category-input name">
                    <label for="description">DESCRIPTION</label>
                    <input type="text" name="description" class="add-category-input desc">
                    <button type="submit" name="ajout" class="add-category-btn">Ajouter</button>
                </form>
                <?php if (isset($error_message)) { ?>
                    <div class="category-error"><?= htmlspecialchars($error_message) ?></div>
                <?php } ?>
            </div>
        <?php } ?>
    <?php if (empty($categories)) { ?>
        <div class="empty-state">
            <h3>Aucune catégorie disponible</h3>
            <p>Le catalogue de catégories est vide pour le moment.</p>
        </div>
    <?php } else { ?>
        <div class="categories-count">
            <strong><?= count($categories) ?></strong> catégorie(s) au total
        </div>
        <div class="categories-table-container">
            <table class="categories-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Description</th>
                         <?php
                if(isset($_SESSION['role']) && $_SESSION['role'] === 1){
                ?>
                        <th>Actions</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat) {
                        $cid = htmlspecialchars($cat['id'] ?? $cat['ID'] ?? '');
                        $cname = htmlspecialchars($cat['name'] ?? $cat['nom'] ?? $cat['categorie'] ?? '');
                        $cdesc = htmlspecialchars($cat['description'] ?? $cat['desc'] ?? '');
                    ?>
                        <tr>
                            <td class="category-id"><?= $cid ?></td>
                            <td class="category-name"><?= $cname ?></td>
                            <td class="category-description"><?= nl2br($cdesc) ?></td>
                             <?php
                    if(isset($_SESSION['role']) && $_SESSION['role'] === 1){
                    ?>
                            <td>
                                <a href="modifier_categorie.php?id=<?= $cid ?>" class="action-btn edit">
                                <i class="fas fa-pen"></i>
                            </a>
                                <a href="supprimer_categorie.php?id=<?= $cid ?>" class="action-btn delete">
                                <i class="fas fa-trash"></i>
                                </a>
                            </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
    </div>
    </div>

</body>
</html>

<?php

?>
