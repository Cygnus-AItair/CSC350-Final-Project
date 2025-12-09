<?php
session_start();
require_once 'db.php';

// Ensure only admins can access
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: foodbak.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $unit = trim($_POST['unit']);

    if (empty($name) || empty($category) || empty($unit)) {
        $error = "Name, category, and unit are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO products (name, description, category, unit, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssss", $name, $description, $category, $unit);

        if ($stmt->execute()) {
            $success = "Product added successfully!";
            $name = $description = $category = $unit = '';
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Add New Product</h1>
    <div class="nav-buttons">
        <button onclick="location.href='admin_dashboard.php'">Back to Dashboard</button>
        <button onclick="location.href='logout.php'">Logout</button>
    </div>
</header>

<div class="section" style="max-width: 600px; margin: 50px auto;">
    <h2>Add New Product</h2>

    <?php if ($error): ?>
        <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="text" name="name" placeholder="Product Name" required
               value="<?= isset($name) ? htmlspecialchars($name) : '' ?>">

        <textarea name="description" placeholder="Description"><?= isset($description) ? htmlspecialchars($description) : '' ?></textarea>

        <input type="text" name="category" placeholder="Category (e.g., Grains, Canned Goods)" required
               value="<?= isset($category) ? htmlspecialchars($category) : '' ?>">

        <input type="text" name="unit" placeholder="Unit (e.g., 5 lb bag, 16 oz bottle)" required
               value="<?= isset($unit) ? htmlspecialchars($unit) : '' ?>">

        <button type="submit" class="admin-btn">Add Product</button>
    </form>
</div>
</body>
</html>