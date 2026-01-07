<?php
session_start();
require '../config/database.php';

$user = null;
$error = '';

if (isset($_POST['update'])) {
  $id = $_POST['id'];
  $username = trim($_POST['username'] ?? '');
  $email = trim($_POST['email'] ?? '');

  try {
    $stmt = $pdo->prepare('UPDATE users SET username = ?, email = ? WHERE id = ?');
    $stmt->execute([$username, $email, $id]);
    header('Location: liste_utilisateurs.php');
    exit();
  } catch (PDOException $e) {
    $error = 'Erreur : ' . $e->getMessage();
  }
}

$id = $_GET['id'] ?? $_GET['idm'] ?? null;
if ($id) {
  try {
    $stmt = $pdo->prepare('SELECT id, username, email FROM users WHERE id = ?');
    $stmt->execute([$id]);
    $user = $stmt->fetch();

    if (!$user) {
      header('Location: liste_utilisateurs.php');
      exit();
    }
  } catch (PDOException $e) {
    $error = 'Erreur : ' . $e->getMessage();
  }
} else {
  header('Location: liste_utilisateurs.php');
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
      <h2 class="title">Modifier un utilisateur</h2>
      <div class="actions-bar">
        <a href="liste_utilisateurs.php">← Retour à la liste</a>
      </div>

      <?php if ($error) { ?>
        <div class="error-message">
          <strong>Erreur :</strong> <?= htmlspecialchars($error) ?>
        </div>
      <?php } ?>

      <?php if ($user) { ?>
      <form action="modifier_utilisateur.php" method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">

        <div>
          <label for="username">NOM:</label>
          <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>">
        </div>

        <div>
          <label for="email">EMAIL:</label>
          <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>">
        </div>

        <div>
          <button type="submit" name="update">Modifier l'utilisateur</button>
        </div>
      </form>
      <?php } ?>
    </div>
  </div>
</body>
</html>
