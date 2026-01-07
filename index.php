<?php
 session_start();
require __DIR__ . '/config/database.php';
try {
    $produits = $pdo->query("SELECT * FROM produit ORDER BY id DESC")->fetchAll();
} catch (PDOException $e) {
    $produits = [];
    $error_message = "Erreur" . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue des Produits - <?php echo SITE_TITLE; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            background-color: #0d6efd;
            padding: 30px;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        header h1 {
            color: #333;
            font-size: 2.5em;
            font-weight: 700;
            margin-bottom: 10px;
        }

        header p {
            color: #666;
            font-size: 1.1em;
        }

        .nav-links {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .nav-links a {
            color: #151414e7;
            background: linear-gradient( #0d6efd 0%, #0d6efd 100%);
            padding: 12px 25px;
            text-decoration: none;
            font-weight: 600;
        
        }

       
        .nav-links a.secondary {
            background: linear-gradient( #0d6efd 0%, #0d6efd 100%);
        }

        .content-section {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            margin-bottom: 30px;
        
        }

        .products-title {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            font-size: 2em;

        }

        .products-count {
            text-align: center;
            margin-bottom: 20px;
            color: #666;
            font-size: 1.1em;
        }

       

        .products-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }

        .products-table thead {
            background: linear-gradient(#0d6efd 0%, #0d6efd 100%);
            color: #fff;
        }

        .products-table th {
            padding: 20px;
            text-align: left;
            font-size: 1.1em;
        }
        .products-table td {
            padding: 20px;
            color: #333;
            font-size: 1em;
        }

        .product-id {
            color: #667eea;
        }

        .product-name {
            color: #333;
        }

        .product-description {
            color: #666;
            max-width: 300px;
        }


        .product-stock.in-stock {
            color: #0f0f0fff;
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
            header {
                flex-direction: column;
                text-align: center;
            }

            .nav-links {
                justify-content: center;
            }

            .products-table th,
            .products-table td {
                padding: 10px;
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>
    <div>
        <header>
            <div>
                <h1>🛍️ Catalogue des Produits</h1>
                <p>Découvrez notre sélection de produits</p>
            </div>
            <div class="nav-links">
                <a href="authentification/login.php">Connexion</a>
                <a href="authentification/connecter.php" class="secondary">Inscription</a>
            </div>
        </header>

        <?php include __DIR__ . '/admin/categories_list.php'; ?>

        <?php if (isset($error_message)) { ?>
            <div class="error-message">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php } ?>

        <?php if (empty($produits)) { ?>
            <div class="empty-state">
                <h3>Aucun produit disponible</h3>
                <p>Le catalogue est vide pour le moment.</p>
            </div>
        <?php } else { ?>

        <div class="content-section">
        <h2 class="products-title">Nos produits</h2>
        <div class="products-count">
            <strong><?= count($produits) ?></strong> produit(s) disponibles(s)
        </div>
            <div class="products-table-container">
                <table class="products-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Prix</th>
                            <th>Categorie_id</th>
                            <th>Quantité</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produits as $produit) { ?>
                            <tr>
                                <td class="product-id"><?= htmlspecialchars($produit['id']) ?></td>
                                <td class="product-name"><?= htmlspecialchars($produit['name']) ?></td>
                                <td class="product-description"><?= htmlspecialchars($produit['description']) ?></td>
                                <td class="product-price"><?= htmlspecialchars($produit['prix']) ?></td>
                                <td class="product-category"><?= htmlspecialchars($produit['categorie_id'] ?? 'N/A') ?></td>
                                <td class="product-stock <?php
                                if ($produit['quantite'] == 0) echo 'out-of-stock';
                                elseif ($produit['quantite'] < 5) echo 'low-stock';
                                else echo 'in-stock';
                                ?>">
                                    <?php
                                        if ($produit['quantite'] == 0) {
                                            echo 'Rupture de stock';
                                        } else {
                                            echo htmlspecialchars($produit['quantite']);
                                        }
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php } ?>
    </div>
</body>
</html>