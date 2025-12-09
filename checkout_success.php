<?php
session_start();
include 'db.php';

// Get session ID
$session_id = session_id();

// Find the active cart
$cartQuery = $conn->prepare("
    SELECT id, user_id 
    FROM carts 
    WHERE session_id = ? AND status = 'active'
    LIMIT 1
");
$cartQuery->bind_param("s", $session_id);
$cartQuery->execute();
$cartResult = $cartQuery->get_result();

if ($cartResult->num_rows === 0) {
    // No active cart
    header("Location: cart.php");
    exit();
}

$cart = $cartResult->fetch_assoc();
$cart_id = $cart['id'];
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Create new order
$orderQuery = $conn->prepare("
    INSERT INTO orders (user_id, cart_id, status) 
    VALUES (?, ?, 'pending')
");
$orderQuery->bind_param("ii", $user_id, $cart_id);
$orderQuery->execute();
$order_id = $orderQuery->insert_id;

// Copy cart items into order_items
$itemQuery = $conn->prepare("
    SELECT product_id, quantity 
    FROM cart_items 
    WHERE cart_id = ?
");
$itemQuery->bind_param("i", $cart_id);
$itemQuery->execute();
$itemResult = $itemQuery->get_result();

while ($item = $itemResult->fetch_assoc()) {
    $insertItem = $conn->prepare("
        INSERT INTO order_items (order_id, product_id, quantity) 
        VALUES (?, ?, ?)
    ");
    $insertItem->bind_param("iii", $order_id, $item['product_id'], $item['quantity']);
    $insertItem->execute();
}

// Mark cart as converted
$updateCart = $conn->prepare("
    UPDATE carts 
    SET status = 'converted' 
    WHERE id = ?
");
$updateCart->bind_param("i", $cart_id);
$updateCart->execute();

// Redirect to thank you page
header("Location: thankyou.php");
exit();
?>