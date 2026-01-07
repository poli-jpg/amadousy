<?php
session_start();
require '../config/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 1) {
    header("Location: dashboard.php");
    exit();
}
$id = $_GET['id'] ?? null;
if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM produit WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: dashboard.php");
        exit();
    } catch (PDOException $e) {
        header("Location: dashboard.php");
        exit();
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>


