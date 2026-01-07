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
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: categories_list.php");
        exit();
    } catch (PDOException $e) {
        header("Location: categories_list.php");
        exit();
    }
} else {
    header("Location: categories_list.php");
    exit();
}
?>


