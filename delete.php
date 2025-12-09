<?php
session_start();
include 'db.php';


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['item_id'])) {
    $item_id = intval($_POST['item_id']);
    $deleteQuery = $conn->prepare("DELETE FROM cart_items WHERE id = ?");
    $deleteQuery->bind_param("i", $item_id);
    if ($deleteQuery->execute()) {
        $_SESSION['message'] = "Item removed from cart.";
    } else {
        $_SESSION['error'] = "Failed to remove item.";
    }
}

header("Location: cart.php");
exit();
?>