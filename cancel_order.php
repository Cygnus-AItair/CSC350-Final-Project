<?php
session_start();
require_once 'db.php';

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "No order ID provided.";
    header("Location: admin_dashboard.php");
    exit();
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    $_SESSION['message'] = "Order cancelled successfully.";
} else {
    $_SESSION['error'] = "Unable to cancel order.";
}

header("Location: admin_dashboard.php");
exit();
?>