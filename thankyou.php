<?php
session_start();
include 'db.php';

// Get the most recent order for this user/session
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$session_id = session_id();

$orderQuery = $conn->prepare("
    SELECT orders.id, orders.created_at
    FROM orders
    JOIN carts ON orders.cart_id = carts.id
    WHERE carts.session_id = ?
    ORDER BY orders.created_at DESC
    LIMIT 1
");
$orderQuery->bind_param("s", $session_id);
$orderQuery->execute();
$orderResult = $orderQuery->get_result();

$order = $orderResult->num_rows > 0 ? $orderResult->fetch_assoc() : null;
$order_id = $order ? $order['id'] : null;

// Load order items if order exists
$items = [];
if ($order_id) {
    $itemQuery = $conn->prepare("
        SELECT products.name, products.unit, order_items.quantity
        FROM order_items
        JOIN products ON order_items.product_id = products.id
        WHERE order_items.order_id = ?
    ");
    $itemQuery->bind_param("i", $order_id);
    $itemQuery->execute();
    $items = $itemQuery->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thank You</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
  <h1>Thank You!</h1>
  <div class="nav-buttons">
    <button onclick="location.href='foodbak.php'">Home</button>
    <button onclick="location.href='products.php'">Products</button>
    <?php if (isset($_SESSION['user_id'])): ?>
        <span class="welcome">Welcome, <?= htmlspecialchars($_SESSION['name']) ?></span>
        <?php if ($_SESSION['user_role'] === 'admin'): ?>
            <button onclick="location.href='admin_dashboard.php'">Admin Dashboard</button>
        <?php endif; ?>
        <button onclick="location.href='logout.php'">Log Out</button>
    <?php else: ?>
        <button onclick="location.href='signin.php'">Sign In</button>
        <button onclick="location.href='signup.php'">Sign Up</button>
    <?php endif; ?>
  </div>
</header>

<?php if (isset($_SESSION['message'])): ?>
    <div class="alert success">
        <?= htmlspecialchars($_SESSION['message']) ?>
    </div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert error">
        <?= htmlspecialchars($_SESSION['error']) ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="thankyou-section">
    <h2>Your order has been placed successfully!</h2>

    <?php if ($order_id && $items->num_rows > 0): ?>
        <h3>Order Summary (Order #<?= $order_id ?>)</h3>
        <?php while ($row = $items->fetch_assoc()): ?>
            <div class="order-item">
                <?= htmlspecialchars($row['name']) ?> (<?= htmlspecialchars($row['unit']) ?>)  
                — Quantity: <?= $row['quantity'] ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No order details available.</p>
    <?php endif; ?>

    <button onclick="location.href='products.php'" class="back-btn">Continue Shopping</button>
</div>

</body>
</html>