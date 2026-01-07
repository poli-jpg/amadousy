<?php
session_start();
require_once('../config/database.php');
try {
    $conn = openConnexion();
    $utilisateur = [];
    if ($conn) {
        $stm = $conn->query("SELECT * FROM users ORDER BY id DESC");
        $utilisateur = $stm->fetchAll();
    }
} catch (PDOException $e) {
    $utilisateur = [];
    $error_message = "Erreur lors de la récupération des utilisateurs : " . $e->getMessage();
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

.edit{background:#198754;}
.delete{background:#dc3545;}

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
        <h2 class="title">Liste des utilisateurs</h2>
        <div class="actions-bar">
            <a href="dashboard.php">← Retour au tableau de bord</a>
        </div>

    <?php if (isset($error_message)) { ?>
        <div class="error-message">
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php } ?>

    <?php if (empty($utilisateur)) { ?>
        <div class="count">
            <p><strong>Aucun utilisateur disponible</strong></p>
        </div>
    <?php } else { ?>
        <div class="count">
            <strong><?= count($utilisateur) ?></strong> utilisateur(s) au total
        </div>
        <table class="product-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NAME</th>
                    <th>EMAIL</th>
                    <th>ROLE_ID</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateur as $ut) { ?>
                    <tr>
                        <td><?= htmlspecialchars($ut['id']) ?></td>
                        <td><?= htmlspecialchars($ut['username']) ?></td>
                        <td><?= htmlspecialchars($ut['email']) ?></td>
                        <td><?= htmlspecialchars($ut['role_id']) ?></td>
                        <td>
                             <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 1 && $ut['role_id'] != 1) { ?>
                                <a class="action-link edit" href="modifier_utilisateur.php?id=<?= $ut['id'] ?>">
                                    <i class="fas fa-edit"></i>
                                </a>
                            <?php } ?>

                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 1 && $ut['role_id'] != 1) { ?>
                                <a class="action-link delete" href="supprimer_utilisateur.php?id=<?= $ut['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
    </div>
    </div>
</body>
</html>