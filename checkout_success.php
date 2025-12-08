<?php
session_start();
include 'db.php';

// Get session ID
$session_id = session_id();

//  Find a active cart for this session
$cartQuery = $conn->prepare("
    SELECT id 
    FROM carts 
    WHERE session_id = ? AND status = 'active'
    LIMIT 1
");
$cartQuery->bind_param("s", $session_id);
$cartQuery->execute();
$cartResult = $cartQuery->get_result();

if ($cartResult->num_rows === 0) {
    die("No active cart found.");
}

$cart_id = $cartResult->fetch_assoc()['id'];

// Create a new order
$insertOrder = $conn->prepare("
    INSERT INTO orders (session_id, created_at)
    VALUES (?, NOW())
");
$insertOrder->bind_param("s", $session_id);
$insertOrder->execute();
$order_id = $insertOrder->insert_id;

//  Move cart items into order_items
$itemQuery = $conn->prepare("
    SELECT product_id, quantity
    FROM cart_items
    WHERE cart_id = ?
");
$itemQuery->bind_param("i", $cart_id);
$itemQuery->execute();
$items = $itemQuery->get_result();

while ($row = $items->fetch_assoc()) {
    $insertItem = $conn->prepare("
        INSERT INTO order_items (order_id, product_id, quantity)
        VALUES (?, ?, ?)
    ");
    $insertItem->bind_param("iii", $order_id, $row['product_id'], $row['quantity']);
    $insertItem->execute();
}

// Clear cart items
$deleteItems = $conn->prepare("DELETE FROM cart_items WHERE cart_id = ?");
$deleteItems->bind_param("i", $cart_id);
$deleteItems->execute();

$updateCart = $conn->prepare("
    UPDATE carts SET status = 'converted' WHERE id = ?
");
$updateCart->bind_param("i", $cart_id);
$updateCart->execute();

// Create a new empty cart
$newStatus = 'active';
$newCart = $conn->prepare("
    INSERT INTO carts (user_id, session_id, status)
    VALUES (NULL, ?, ?)
");
$newCart->bind_param("ss", $session_id, $newStatus);
$newCart->execute();

// Redirect to thank you page
header("Location: thankyou.php?order_id=$order_id");
exit;
?>
