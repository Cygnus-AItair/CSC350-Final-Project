<?php
session_start();
require_once 'db.php';

//check if user is the admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: foodbak.php');
    exit();
}

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    
    //delete product
    $sql = "DELETE FROM products WHERE id = $product_id";
    
    if ($conn->query($sql)) {
        $_SESSION['message'] = "Product deleted successfully.";
    } else {
        $_SESSION['error'] = "Error deleting product: " . $conn->error;
    }
}

header('Location: admin_dashboard.php');
exit();
?>
