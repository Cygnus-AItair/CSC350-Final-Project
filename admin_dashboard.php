<?php
session_start();
require_once 'db.php'; // Database connection

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
            <button onclick="location.href='foodbank.php'">Home</button>
            <button onclick="location.href='products.php'">View Products</button>
            <button onclick="location.href='logout.php'">Logout</button>
        </div>
    </header>

    <div class="section">
        <h2>User Management</h2>
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
                while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Product Management</h2>
        <button onclick="location.href='add_product.php'" style="margin-bottom: 20px;">Add New Product</button>
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Unit</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
                while ($product = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>{$product['id']}</td>";
                    echo "<td>{$product['name']}</td>";
                    echo "<td>{$product['category']}</td>";
                    echo "<td>{$product['unit']}</td>";
                    echo "<td>{$product['created_at']}</td>";
                    echo "<td>";
                    echo "<a href='edit_product.php?id={$product['id']}'>Edit</a> | ";
                    echo "<a href='delete_product.php?id={$product['id']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Order Management</h2>
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%;">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("
                    SELECT o.*, u.name as user_name 
                    FROM orders o 
                    LEFT JOIN users u ON o.user_id = u.id 
                    ORDER BY o.created_at DESC
                ");
                while ($order = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>{$order['id']}</td>";
                    echo "<td>" . ($order['user_name'] ?: 'Guest') . "</td>";
                    echo "<td>{$order['status']}</td>";
                    echo "<td>{$order['created_at']}</td>";
                    echo "<td>";
                    echo "<a href='view_order.php?id={$order['id']}'>View</a> | ";
                    echo "<a href='update_order_status.php?id={$order['id']}'>Update Status</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
