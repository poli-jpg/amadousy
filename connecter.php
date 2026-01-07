<?php
session_start();
require '../config/database.php';

$error = '';
$success = '';

if (isset($_POST['register'])) {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($nom) || empty($email) || empty($password)) {
        $error = "Veuillez remplir tous les champs";
    } elseif (strlen($password) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Cet email est déjà utilisé";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare(
                "INSERT INTO users (username, email, password, role_id) VALUES (?, ?, ?, 2)"
            );
            $stmt->execute([$nom, $email, $hashedPassword]);
            $success = "Inscription réussie ";
        }
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
        margin-bottom:20px;
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

    .login-link{
        text-align:center;
        margin-top:15px;
    }

    .login-link a{
        color:#0d6efd;
        text-decoration:none;
    }

    .login-link a:hover{
        text-decoration:underline;
    }

    .success-message{
        background:#d1e7dd;
        color:#0f5132;
        padding:10px;
        border-radius:5px;
        margin-bottom:15px;
        text-align:center;
    }

    .home-link{
        text-align:center;
        margin-top:15px;
    }

    .home-link a{
        color:#6c757d;
        text-decoration:none;
        font-size:0.9em;
    }

    .home-link a:hover{
        text-decoration:underline;
    }
    </style>

    <div class="container">
        <div class="card">
            <h2 class="title">INSCRIPTION</h2>
            <div class="actions-bar">
                <a href="../index.php">← Retour au catalogue</a>
            </div>
            
            <?php if ($error) { ?>
                <div class="error-message">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php } ?>

            <?php if ($success) { ?>
                <div class="success-message">
                    <?= htmlspecialchars($success) ?>
                    <br>
                    <a href="login.php" style="color:#0f5132; font-weight:bold;">Connectez-vous maintenant</a>
                </div>
            <?php } ?>

            <form method="POST">
                <div>
                    <label for="nom">NOM:</label>
                    <input type="text" id="nom" name="nom" placeholder="Entrez votre nom" required>
                </div>
                <div>
                    <label for="email">EMAIL:</label>
                    <input type="email" id="email" name="email" placeholder="exemple@email.com" required>
                </div>
                <div>
                    <label for="password">MOT DE PASSE:</label>
                    <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required>
                </div>
                <div>
                    <button type="submit" name="register">S'inscrire</button>
                </div>
            </form>

            <div class="login-link">
                Déjà un compte ? <a href="login.php">Se connecter</a>
            </div>

            <div class="home-link">
                <a href="../index.php">Retour à l'accueil</a>
            </div>
        </div>
    </div>
</body>
</html>

