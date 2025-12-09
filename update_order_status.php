<?php
session_start();
require_once 'db.php';

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "No order ID provided.";
    header("Location: admin_dashboard.php");
    exit();
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("
    UPDATE orders 
    SET status = CASE 
        WHEN status = 'pending' THEN 'completed' 
        ELSE 'pending' 
    END
    WHERE id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    $_SESSION['message'] = "Order status updated.";
} else {
    $_SESSION['error'] = "Unable to update order.";
}

header("Location: admin_dashboard.php");
exit();
?>