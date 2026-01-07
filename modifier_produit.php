<?php
session_start();
require '../config/database.php';

try {
    $cats = $pdo->query("SELECT id, name FROM categories ORDER BY id")->fetchAll();
} catch (PDOException $e) {
    $cats = [];
}

$product = null;
$error = '';
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $prix = $_POST['prix'];
    $quantite = $_POST['quantite'];
    $categorie_id = $_POST['categorie_id'] ?? null;
    if (empty($name)) {
        $error = "Le nom du produit est requis";
    } else {
            try {
            $catParam = ($categorie_id === '' || $categorie_id === null) ? null : (int)$categorie_id;
            $stmt = $pdo->prepare("UPDATE produit SET name = ?, description = ?, prix = ?, quantite = ?, categorie_id = ? WHERE id = ?");
            $stmt->execute([$name, $description, $prix, $quantite, $catParam, $id]);
            header("Location: liste_produit.php");
            exit();
        } catch (PDOException $e) {
            $error = "Erreur : " . $e->getMessage();
        }
    }
}
$id = $_GET['id'] ?? $_GET['idm'] ?? null;
if ($id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM produit WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();

        if (!$product) {
            header("Location: liste_produit.php");
            exit();
        }
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
} else {
    header("Location: liste_produit.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_TITLE; ?></title>
</head>
<body>
    <style>
    body{
        font-family: Arial, sans-serif;
        background:#f4f6f9;
        padding:30px;
    }

    .container{
        max-width:1100px;
        margin:auto;
    }

    .card{
        background:#fff;
        padding:20px;
        border-radius:10px;
        box-shadow:0 5px 15px rgba(0,0,0,0.1);
    }

    .title{
        text-align:center;
        margin-bottom:15px;
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

    form{
        max-width:500px;
        margin:auto;
    }

    form div{
        margin-bottom:15px;
    }

    label{
        display:block;
        margin-bottom:5px;
        font-weight:bold;
        color:#333;
    }

    input, select{
        width:100%;
        padding:10px;
        border:1px solid #ccc;
        border-radius:5px;
        font-size:14px;
        box-sizing:border-box;
    }

    input:focus, select:focus{
        outline:none;
        border-color:#0d6efd;
    }

    button{
        width:100%;
        background:#0d6efd;
        color:#fff;
        border:none;
        padding:12px;
        border-radius:5px;
        cursor:pointer;
        font-size:16px;
        font-weight:bold;
    }

    button:hover{
        opacity:0.9;
    }

    .error-message{
        background:#f8d7da;
        color:#721c24;
        padding:10px;
        border-radius:5px;
        margin-bottom:15px;
        text-align:center;
    }


    </style>
    <div class="container">
        <div class="card">
            <h2 class="title">Modifier un produit</h2>
            <div class="actions-bar">
                <a href="liste_produit.php">← Retour à la liste</a>
            </div>

            <?php if ($error) { ?>
                <div class="error-message">
                    <strong>Erreur :</strong> <?= htmlspecialchars($error) ?>
                </div>
            <?php } ?>


            <?php if ($product) { ?>
            <form action="modifier_produit.php" method="POST">
                <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
                
                <div>
                    <label for="name">NAME:</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($product['name'] ?? $product['nom'] ?? '') ?>" required>
                </div>
                <div>
                    <label for="description">DESCRIPTION:</label>
                    <input type="text" id="description" name="description" value="<?= htmlspecialchars($product['description']) ?>" required>
                </div>
                <div>
                    <label for="prix">PRIX:</label>
                    <input type="number" id="prix" name="prix" step="0.01" value="<?= htmlspecialchars($product['prix'] ?? $product['price'] ?? '') ?>" required>
                </div>
                <div>
                        <label for="quantite">QUANTITE:</label>
                        <input type="number" id="quantite" name="quantite" value="<?= htmlspecialchars($product['quantite'] ?? '') ?>" required>
                    </div>

                    <div>
                        <label for="categorie_id">CATEGORIE:</label>
                        <select name="categorie_id" id="categorie_id">
                            <option value="">-- Aucune --</option>
                            <?php foreach ($cats as $cat) {
                                $sel = ((string)($product['categorie_id'] ?? '') === (string)$cat['id']) ? 'selected' : '';
                            ?>
                            <option value="<?= htmlspecialchars($cat['id']) ?>" <?= $sel ?>><?= htmlspecialchars($cat['name']) ?></option>
                            <?php } ?>
                        </select>
                    </div>

                <div>
                    <button type="submit" name="update">Modifier le produit</button>
                </div>
            </form>
            <?php } ?>
        </div>
    </div>
</body>
</html>
