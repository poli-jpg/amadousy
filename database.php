<?php
define("SITE_TITLE", "Gestion des étudiants");

$pdo = new PDO(
    "mysql:host=localhost;dbname=gestion_produit;charset=utf8mb4",
    "root",
    "",
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
);

function openConnexion()
{
    global $pdo;
    return $pdo;
}
