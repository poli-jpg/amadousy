<?php
session_start();
require '../config/database.php';

try {
    $cats = $pdo->query("SELECT * FROM categories")->fetchAll();
} catch (PDOException $e) {
    $cats = [];
    $error_message = "Erreur lors de la récupération des produits : " . $e->getMessage();
}
$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter'])) {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = $_POST['price'] ?? '';
    $quantite = $_POST['quantite'] ?? '';
    $categorie_id = $_POST['categorie_id'] ?? null;
    
    try {
        // préparer la valeur de categorie_id (null ou entier)
        $catParam = $categorie_id === '' || $categorie_id === null ? null : (int)$categorie_id;
        $stmt = $pdo->prepare("INSERT INTO produit (name, description, prix, quantite, categorie_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $quantite, $catParam]);
        header("Location: liste_produit.php");
        exit();
    } catch (PDOException $e) {
        $error_message = 'Erreur lors de l\'ajout du produit : ' . $e->getMessage();
    }
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
            <h2 class="title">Ajouter un produit</h2>
            <div class="actions-bar">
                <a href="dashboard.php">← Retour au tableau de bord</a>
            </div>

            <?php if (!empty($error_message)) { ?>
                <div class="error-message">
                    <?= htmlspecialchars($error_message) ?>
                </div>
            <?php } ?>

            <form action="ajout_produit.php" method="POST">
                <div>
                    <label for="name">NAME:</label>
                    <input type="text" name="name" id="name" required>
                </div>
                <div>
                    <label for="description">DESCRIPTION:</label>
                    <input type="text" name="description" id="description" required>
                </div>
                <div>
                    <label for="price">PRICE:</label>
                    <input type="number" name="price" id="price" step="0.01" required>
                </div>
                <div>
                    <label for="quantite">QUANTITE:</label>
                    <input type="number" name="quantite" id="quantite" required>
                </div>
                <div>
                    <label for="categorie_id">CATEGORIE:</label>
                    <select name="categorie_id" id="categorie_id">
                        <option value="">Selectionner Categorie</option>
                        <?php foreach($cats as $cat){ ?>
                        <option value="<?= htmlspecialchars($cat['id']) ?>"><?= htmlspecialchars($cat['name']) ?></option> 
                        <?php } ?>
                    </select>
                </div>
                <div>
                    <button type="submit" name="ajouter">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
    
</body>
</html>