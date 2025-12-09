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
    SELECT o.*, u.name AS user_name
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    WHERE o.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    $_SESSION['error'] = "Order not found.";
    header("Location: admin_dashboard.php");
    exit();
}

// Load order items
$itemStmt = $conn->prepare("
    SELECT p.name, p.unit, oi.quantity
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$itemStmt->bind_param("i", $id);
$itemStmt->execute();
$items = $itemStmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Order</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Order Details</h1>
    </header>
    <div class="section">
        <p><strong>Order ID:</strong> <?= htmlspecialchars($order['id']) ?></p>
        <p><strong>User:</strong> <?= htmlspecialchars($order['user_name'] ?: 'Guest') ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($order['status']) ?></p>
        <p><strong>Created:</strong> <?= htmlspecialchars($order['created_at']) ?></p>
    </div>

    <div class="section">
        <h2>Items</h2>
        <?php if ($items->num_rows > 0): ?>
            <ul>
                <?php while ($row = $items->fetch_assoc()): ?>
                    <li><?= htmlspecialchars($row['name']) ?> (<?= htmlspecialchars($row['unit']) ?>) — Quantity: <?= $row['quantity'] ?></li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No items found for this order.</p>
        <?php endif; ?>
    </div>

    <button onclick="location.href='admin_dashboard.php'" class="back-btn">Back</button>
</body>
</html>