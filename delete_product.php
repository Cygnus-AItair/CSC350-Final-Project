<?php
session_start();
require_once 'db.php';

// Ensure only admins can access
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: foodbak.php');
    exit();
}

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "No product ID provided.";
    header("Location: admin_dashboard.php");
    exit();
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    $_SESSION['message'] = "Product deleted successfully.";
} else {
    $_SESSION['error'] = "Unable to delete product.";
}

header("Location: admin_dashboard.php");
exit();
?>