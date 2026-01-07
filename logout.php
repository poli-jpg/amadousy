<?php
define("SITE_TITLE", "Gestion des étudiants");
?>
<?php
session_start();
session_destroy();
header('Location: login.php');
exit();