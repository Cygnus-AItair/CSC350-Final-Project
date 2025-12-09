<?php
session_start();
include 'db.php';

// Get PHP session ID
$session_id = session_id();

// Check if a cart already exists for this session
$cartQuery = $conn->prepare("
    SELECT id 
    FROM carts 
    WHERE session_id = ?
    LIMIT 1
");
$cartQuery->bind_param("s", $session_id);
$cartQuery->execute();
$cartResult = $cartQuery->get_result();

if ($cartResult->num_rows > 0) {
    $cart_id = $cartResult->fetch_assoc()['id'];
} else {
    $status = 'active';
    $insertCart = $conn->prepare("
        INSERT INTO carts (user_id, session_id, status)
        VALUES (NULL, ?, ?)
    ");
    $insertCart->bind_param("ss", $session_id, $status);
    $insertCart->execute();
    $cart_id = $insertCart->insert_id;
}

// Get product ID from POST
if (!isset($_POST['product_id'])) {
    die("No product selected.");
}
$product_id = intval($_POST['product_id']);

// Check if item already in cart
$checkItem = $conn->prepare("
    SELECT quantity 
    FROM cart_items 
    WHERE cart_id = ? AND product_id = ?
");
$checkItem->bind_param("ii", $cart_id, $product_id);
$checkItem->execute();
$itemResult = $checkItem->get_result();

if ($itemResult->num_rows > 0) {
    $updateItem = $conn->prepare("
        UPDATE cart_items 
        SET quantity = quantity + 1
        WHERE cart_id = ? AND product_id = ?
    ");
    $updateItem->bind_param("ii", $cart_id, $product_id);
    $updateItem->execute();
} else {
    $insertItem = $conn->prepare("
        INSERT INTO cart_items (cart_id, product_id, quantity)
        VALUES (?, ?, 1)
    ");
    $insertItem->bind_param("ii", $cart_id, $product_id);
    $insertItem->execute();
}

// Add success message
$_SESSION['message'] = "Item added to cart!";

// Redirect back safely
$redirect = $_SERVER['HTTP_REFERER'] ?? 'cart.php';
header("Location: $redirect");
exit();
?>