<?php
session_start();
require '../config/database.php';
try {
    $produit = $pdo->query("SELECT * FROM produit ORDER BY id DESC")->fetchAll();
} catch (PDOException $e) {
    $produit = [];
    $error_message = "Erreur lors de la récupération des produits : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_TITLE; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>
<body>
    <style>
    body{
    font-family: Arial, sans-serif;
    background-color:#f4f6f9;
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
    box-shadow:0 5px 15px rgba(228, 13, 13, 0.1);
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

.product-table{
    width:100%;
    border-collapse:collapse;
}

.product-table th{
    background:#0d6efd;
    color:#fff;
    padding:10px;
}

.product-table td{
    padding:8px;
    text-align:center;
}

.product-table tr:nth-child(even){
    background:#f1f1f1;
}

.action-link{
    color:#fff;
    padding:5px 8px;
    border-radius:4px;
    text-decoration:none;
    margin:0 3px;
}

.edit{background: #198754;}
.delete{background: #dc3545;}

.count{
    text-align:center;
    margin-bottom:15px;
    color:#333;
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
        <h2 class="title">Liste des produits</h2>
        <div class="actions-bar">
            <a href="dashboard.php">← Retour au tableau de bord</a>
        </div>
    
    <?php if (isset($error_message)) { ?>
        <div class="error-message">
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php } ?>
    
    <?php if (empty($produit)) { ?>
        <div class="count">
            <p><strong>Aucun produit disponible</strong></p>
        </div>
    <?php } else { ?>
        <div class="count">
            <strong><?= count($produit) ?></strong> produit(s) au total
        </div>
        <table class="product-table">
            <thead>
                <?php $colspan = (isset($_SESSION['role']) && $_SESSION['role'] === 1) ? 7 : 6; ?>
                <tr>
                    <th colspan="<?= $colspan ?>" style="text-align:center;font-size:1.05em;padding:14px 10px;background:#f8fafc;color:#333;"></th>
                </tr>
                <tr>
                    <th>ID</th>
                    <th>NAME</th>
                    <th>Description</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Catégorie_id</th>
                    <?php
            if(isset($_SESSION['role']) && $_SESSION['role'] === 1){
            ?> 
                    <th>Actions</th>
                    <?php } ?> 
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produit as $p) { ?>
                    <tr>
                        <td><?= htmlspecialchars($p['id']) ?></td>
                        <td><?= htmlspecialchars($p['name']) ?></td>
                        <td><?= htmlspecialchars($p['description']) ?></td>
                        <td><?= htmlspecialchars($p['prix']) ?></td>
                        <td><?= htmlspecialchars($p['quantite']) ?></td>
                        <td><?= htmlspecialchars($p['categorie_id']) ?></td>
                         <?php
            if(isset($_SESSION['role']) && $_SESSION['role'] === 1){
            ?> 
                        <td>
                            <a class="action-link edit" href="modifier_produit.php?idm=<?= $p['id'] ?>" class="edit">
                                <i class="fa-solid fa-pen"></i> 
                            </a>
                            <a class="action-link delete" href="supprimer_produit.php?id=<?= $p['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?') " class="delete">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                        <?php } ?> 
                            
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
    </div>
    </div>
</body>
</html>

