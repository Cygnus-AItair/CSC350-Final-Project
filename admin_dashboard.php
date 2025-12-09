<?php
session_start();
require_once 'db.php';

// Checks if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Admin Dashboard</h1>
    <div class="nav-buttons">
        <button onclick="location.href='foodbak.php'">Home</button>
        <button onclick="location.href='products.php'">View Products</button>
        <button onclick="location.href='logout.php'">Logout</button>
    </div>
</header>

<?php if (isset($_SESSION['message'])): ?>
    <div class="alert success"><?= htmlspecialchars($_SESSION['message']) ?></div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert error"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="section">
    <h2>User Management</h2>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Created</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
            while ($user = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$user['id']}</td>";
                echo "<td>{$user['name']}</td>";
                echo "<td>{$user['email']}</td>";
                echo "<td>{$user['role']}</td>";
                echo "<td>{$user['created_at']}</td>";
                echo "<td>";
                if ($user['role'] !== 'admin') {
                    echo "<a href='delete_user.php?id={$user['id']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
                } else {
                    echo "Admin (protected)";
                }
                echo "</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<div class="section">
    <h2>Product Management</h2>
    <button onclick="location.href='add_product.php'" class="admin-btn">Add New Product</button>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th><th>Name</th><th>Category</th><th>Unit</th><th>Created</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
            while ($product = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$product['id']}</td>";
                echo "<td>{$product['name']}</td>";
                echo "<td>{$product['category']}</td>";
                echo "<td>{$product['unit']}</td>";
                echo "<td>{$product['created_at']}</td>";
                echo "<td>";
                echo "<a href='view_product.php?id={$product['id']}' class='admin-link'>View</a> | ";
                echo "<a href='edit_product.php?id={$product['id']}' class='admin-link'>Edit</a> | ";
                echo "<a href='delete_product.php?id={$product['id']}' class='admin-link' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
                echo "</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<div class="section">
    <h2>Order Management</h2>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Order ID</th><th>User</th><th>Status</th><th>Created</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("
                SELECT o.*, u.name AS user_name
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                ORDER BY o.created_at DESC
            ");
            while ($order = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$order['id']}</td>";
                echo "<td>" . ($order['user_name'] ?: 'Guest') . "</td>";
                echo "<td>{$order['status']}</td>";
                echo "<td>{$order['created_at']}</td>";
                echo "<td>";
                echo "<a href='view_order.php?id={$order['id']}'>View</a> | ";
                echo "<a href='update_order_status.php?id={$order['id']}'>Update Status</a> | ";
                echo "<a href='cancel_order.php?id={$order['id']}' class='admin-link' onclick='return confirm(\"Cancel this order?\")'>Cancel</a>";
                echo "</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>