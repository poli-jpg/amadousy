<?php
session_start();
require __DIR__ . '/../config/database.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($email === '' || $password === '') {
        $error = 'Veuillez remplir tous les champs';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);       
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role_id'];
                header('Location: ../admin/dashboard.php');
                exit();
            } else {
                $error = 'Mot de passe incorrect';
            }
        } else {
            $error = 'Aucun compte trouvé avec cet email';
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
    </style>

    <div class="container">
        <div class="card">
            <h2 class="title">PAGE D'AUTHENTIFICATION</h2>
            <?php if ($error) { ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>
                </div>
            <?php } ?>
            
            <form method="POST">
                <div>
                    <label for="email">EMAIL:</label>
                    <input type="email" id="email" name="email" placeholder="exemple@email.com" required>
                </div>
                
                <div>
                    <label for="password">MOT DE PASSE:</label>
                    <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required>
                </div>
                
                <div>
                    <button type="submit" name="login">Se connecter</button>
                </div>
            </form>
            
            <div class="login-link">
                Pas encore de compte ? <a href="connecter.php">S'inscrire</a>
            </div>

            <div class="login-link" style="margin-top:10px;">
                <a href="../index.php" style="color:#6c757d; font-size:0.9em;">← Retour au catalogue</a>
            </div>
        </div>
    </div>
</body>
</html>