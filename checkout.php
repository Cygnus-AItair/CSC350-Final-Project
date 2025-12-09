<?php
session_start();
include 'db.php';

// Get session ID
$session_id = session_id();

// Find the active cart for this session
$cartQuery = $conn->prepare("
    SELECT id 
    FROM carts 
    WHERE session_id = ? AND status = 'active'
    LIMIT 1
");
$cartQuery->bind_param("s", $session_id);
$cartQuery->execute();
$cartResult = $cartQuery->get_result();

$cart_id = $cartResult->num_rows > 0 ? $cartResult->fetch_assoc()['id'] : null;

// Load items
$items = [];
if ($cart_id) {
    $itemQuery = $conn->prepare("
        SELECT 
            products.name,
            products.description,
            products.image_path,
            products.unit,
            cart_items.quantity
        FROM cart_items
        JOIN products ON cart_items.product_id = products.id
        WHERE cart_items.cart_id = ?
    ");
    $itemQuery->bind_param("i", $cart_id);
    $itemQuery->execute();
    $items = $itemQuery->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
  <h1>Checkout</h1>
  <div class="nav-buttons">
    <button onclick="location.href='foodbak.php'">Home</button>
    <button onclick="location.href='products.php'">Products</button>
    <button onclick="location.href='cart.php'">Cart</button>
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

<div class="checkout-section">
    <h2>Your Items</h2>

    <?php if (!$cart_id || $items->num_rows === 0): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <?php while ($row = $items->fetch_assoc()): ?>
            <div class="checkout-item">
                <img src="<?= htmlspecialchars($row['image_path']) ?>" 
                     alt="<?= htmlspecialchars($row['name']) ?>">
                <div>
                    <h3><?= htmlspecialchars($row['name']) ?></h3>
                    <p><?= htmlspecialchars($row['description']) ?></p>
                    <p><?= htmlspecialchars($row['unit']) ?></p>
                    <p>Quantity: <?= $row['quantity'] ?></p>
                </div>
            </div>
        <?php endwhile; ?>

        <!-- Confirm button now points to checkout_success.php -->
        <form action="checkout_success.php" method="POST">
            <button type="submit" class="confirm-btn">Confirm Pickup</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>