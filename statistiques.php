<?php
if (session_id() == '') {
    session_start();
}
require '../config/database.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 1) {
    header('Location: dashboard.php');
    exit;
}

$error_message = '';
try {
    
    $res = $pdo->query('SELECT COUNT(*) FROM produit');
    $prodCount = $res ? (int) $res->fetchColumn() : 0;
    $res = $pdo->query('SELECT COUNT(*) FROM categories');
    $catCount = $res ? (int) $res->fetchColumn() : 0;
    $res = $pdo->query('SELECT COUNT(*) FROM users');
    $userCount = $res ? (int) $res->fetchColumn() : 0;
    

} catch (PDOException $e) {
    
    $prodCount = $catCount = $userCount = 0;
    $error_message = 'Erreur base de données : ' . $e->getMessage();
}
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Statistiques</title>
    <style>
        body{
            font-family:Arial,Helvetica,sans-serif;
            background:#f4f6f9;padding:30px
        }
        .wrap{
            max-width:1100px;
            margin:0 auto
        }
        .card{
            background:#fff;
            padding:20px;
            border-radius:10px;
            box-shadow:0 6px 22px rgba(0,0,0,0.04);
        }
        .grid{display:flex;
            gap:16px;
            flex-wrap:wrap;
            margin-bottom:20px}
        .stat{flex:1 1 200px;
            background:linear-gradient(180deg,#fff,#f7fbff);
            padding:18px;border-radius:8px;
            border:1px solid #e9eef8}
        .stat h3{
            margin:0;font-size:1.5em;
            color:#0d6efd
        }
        .stat p{
            margin:6px 0 0;
            color:#333;
            font-weight:700;
            font-size:1.2em}
        .small{
            color:#666;
            font-size:0.9em
        }
        .topbar{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:14px
        }
        a.btn{
            background:#0d6efd;
            color:#fff;padding:8px 12px;
            border-radius:6px;
            text-decoration:none;
            font-weight:600
        }
        .error{
            background:#f8d7da;
            color:#721c24;padding:8px;
            border-radius:6px;
            margin-bottom:12px
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <div class="topbar">
                <h2>Statistiques</h2>
                <div>
                    <a href="dashboard.php" class="btn">← Retour</a>
                </div>
            </div>

            <?php if ($error_message) { ?><div class="error"><?= htmlspecialchars($error_message) ?></div><?php } ?>

            <div class="grid">
                <div class="stat">
                    <div class="small">Produits</div>
                    <h3><?= number_format($prodCount, 0, ',', ' ') ?></h3>
                    <p class="small">Total des produits enregistrés</p>
                </div>

                <div class="stat">
                    <div class="small">Catégories</div>
                    <h3><?= number_format($catCount, 0, ',', ' ') ?></h3>
                    <p class="small">Total des catégories</p>
                </div>

                <div class="stat">
                    <div class="small">Utilisateurs</div>
                    <h3><?= number_format($userCount, 0, ',', ' ') ?></h3>
                    <p class="small">Total des comptes utilisateurs</p>
                </div>

    </div>

        </div>
    </div>
</body>
</html>
