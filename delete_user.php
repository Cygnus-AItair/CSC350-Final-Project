<?php
session_start();
require_once 'db.php';

// Ensure only admins can access
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: foodbak.php');
    exit();
}

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "No user ID provided.";
    header("Location: admin_dashboard.php");
    exit();
}

$id = intval($_GET['id']);

// Prevent deleting admins
$stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    $_SESSION['message'] = "User deleted successfully.";
} else {
    $_SESSION['error'] = "Unable to delete user or admin accounts cannot be deleted.";
}

header("Location: admin_dashboard.php");
exit();
?>