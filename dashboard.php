<?php
session_start();
require '../config/database.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_TITLE; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: #f5f7fa;
            color: rgba(0, 0, 0, 0.88);
            line-height: 1.6;
        }
        .topbar {
            background: linear-gradient(#0d6efd 0%, #0d6efd 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .brand h1 { font-size: 1.5rem; font-weight: 600; margin-bottom: 0.25rem; }
        .brand .small-muted { font-size: 0.85rem; opacity: 0.9; }
        .topbar a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            background: rgba(255,255,255,0.2);
            border-radius: 6px;
            transition: background 0.3s;
        }
        .topbar a:hover { background: rgba(255,255,255,0.3); }
        .layout {
            display: flex;
            min-height: calc(100vh - 80px);
        }
        .sidebar {
            width: 220px;
            background: white;
            padding: 1.5rem 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
        }
        .sidebar a {
            display: block;
            padding: 0.75rem 1.5rem;
            color: #555;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        .sidebar a:hover {
            background: #f8f9fa;
            border-left-color: #667eea;
            color: #667eea;
        }
        .main {
            flex: 1;
            padding: 1.5rem;
        }
        .main h2 {
            font-size: 1.5rem;
            color: #2d3748;
            margin-bottom: 1rem;
        }
        .small-muted {
            font-size: 0.875rem;
            color: rgba(15, 15, 16, 1);
        }
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .card {
            background: white;
            padding: 1.25rem;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }
        .card h3 {
            font-size: 0.9rem;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.75rem;
        }
        .card p {
            font-size: 2rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }
        .card a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        .card a:hover { 
            text-decoration: underline; 
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead {
            background: #f7fafc;
        }
        th {
            padding: 0.75rem;
            text-align: left;
            font-weight: 600;
            color: #4a5568;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        td {
            padding: 0.75rem;
            border-top: 1px solid #e2e8f0;
            color: #2d3748;
        }
       
        .actions a {
            display: inline-block;
            padding: 0.4rem 0.8rem;
            margin-right: 0.5rem;
            background: #198654;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.85rem;
        }
        .actions a:last-child {
            background: #dc3545;
        }
        .actions a:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <?php
    try {
        $productCount = (int) $pdo->query("SELECT COUNT(*) FROM produit")->fetchColumn();
    } catch 
    (Exception $e) { 
        $productCount = 0;
     }
    try {
        $userCount = (int) $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    } catch 
    (Exception $e) 
    { $userCount = 0;
       }
    try {
        $recent = $pdo->query("SELECT id, name, prix, quantite, categorie_id FROM produit ORDER BY id DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
    } catch 
    (Exception $e) { 
        $recent = [];
       }
    try {
        $recentCats = $pdo->query("SELECT id, name FROM categories ORDER BY id DESC LIMIT 2")->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $recentCats = [];
    }
    ?>

    <div class="topbar">
        <div class="brand">
            <h1>Gestion de Produit</h1>
            <div class="small-muted">Bienvenue</div>
        </div>
        <div>
            <a href="../authentification/logout.php">Déconnexion</a>
        </div>
    </div>

    <div class="layout">
        <nav class="sidebar">
            <?php
            if(isset($_SESSION['role']) && $_SESSION['role'] === 1){
            ?> 
            <a href="dashboard.php">Tableau de bord</a>
            <a href="ajout_produit.php">Ajouter un produit</a>
            <a href="liste_produit.php">Liste des produits</a>
            <a href="categories_list.php">Liste des categories</a>
            <a href="liste_utilisateurs.php">Utilisateurs</a>
            <a href="statistiques.php">Statistiques</a>
            <?php } else{?>
            <a href="ajout_produit.php">Ajouter un produit</a>
            <a href="liste_produit.php">Liste des produits</a>
            <a href="categories_list.php">Liste des categories</a>
             
            <?php }?> 
            
        </nav>

        <main class="main">
            <h2>Tableau de bord</h2>

            <div class="kpi-grid">
                <div class="card">
                    <h3>Produits</h3>
                    <p><?= htmlspecialchars($productCount) ?></p>
                </div>
                <?php
                if(isset($_SESSION['role']) && $_SESSION['role'] === 1){
                ?> 
                <div class="card">
                    <h3>Utilisateurs</h3>
                    <p><?= htmlspecialchars($userCount) ?></p>
                </div>
                   <?php } ?>
                <?php
                if(isset($_SESSION['role']) && $_SESSION['role'] === 1){
                ?>
                <div class="card">
                    <h3>Actions</h3>
                    <p><a href="ajout_produit.php">Ajouter produit</a></p>
                </div>
                <?php } ?>
            </div>

            <section>
                 <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 1){ ?>
                <h3>Produits récents</h3>
                <?php if (empty($recent)) { ?>
                    <div class="card"><p class="small-muted">Aucun produit récent.</p></div>
                <?php } else { ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Prix</th>
                                <th>Quantité</th>
                                <th>Categorie_id</th>
                                <?php
                                  if(isset($_SESSION['role']) && $_SESSION['role'] === 1){
                                ?>
                                <th>Actions</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($recent as $r) { ?>
                            <tr>
                                <td><?= htmlspecialchars($r['id']) ?></td>
                                <td><?= htmlspecialchars($r['name']) ?></td>
                                <td><?= htmlspecialchars($r['prix']) ?></td>
                                <td><?= htmlspecialchars($r['quantite']) ?></td>
                                <td><?= htmlspecialchars($r['categorie_id'] ?? 'N/A') ?></td>
                                <?php
                                if(isset($_SESSION['role']) && $_SESSION['role'] === 1){
                                ?>
                                <td class="actions"><a href="modifier_produit.php?idm=<?= $r['id'] ?> " class="edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <a href="supprimer_produit.php?id=<?= $r['id'] ?>" onclick="return confirm('Supprimer ?') " class="delete">
                                    <i class="fa-solid fa-trash"></i>
                                </a></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
            </section>

            <?php } ?>

            <section style="margin-top:1rem">
                <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 1){ ?>
                <h3>Catégories récentes</h3>
                <?php if (empty($recentCats)) { ?>
                    <div class="card"><p class="small-muted">Aucune catégorie récente.</p></div>
                <?php } else { ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 1){ ?>
                                <th>Actions</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                      <?php } ?>
                        <?php foreach ($recentCats as $c) { ?>
                            <tr>
                                <td><?= htmlspecialchars($c['id']) ?></td>
                                <td><?= htmlspecialchars($c['name']) ?></td>
                                <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 1){ ?>
                                <td class="actions">
                                    <a href="modifier_categorie.php?id=<?= $c['id'] ?>" class="edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <a href="supprimer_categorie.php?id=<?= $c['id'] ?>" onclick="return confirm('Supprimer ?')" class="delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
            </section>
        </main>
    </div>
</body>
</html>
