<?php
session_start();
require_once 'db.php';

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "No product ID provided.";
    header("Location: admin_dashboard.php");
    exit();
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    $_SESSION['error'] = "Product not found.";
    header("Location: admin_dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $unit = trim($_POST['unit']);

    $update = $conn->prepare("UPDATE products SET name = ?, category = ?, unit = ? WHERE id = ?");
    $update->bind_param("sssi", $name, $category, $unit, $id);
    $update->execute();

    if ($update->affected_rows > 0) {
        $_SESSION['message'] = "Product updated successfully.";
    } else {
        $_SESSION['error'] = "Unable to update product.";
    }

    header("Location: admin_dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Edit Product</h1>
    </header>
    <div class="section">
        <form method="post">
            <label>Name: <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>"></label><br><br>
            <label>Category: <input type="text" name="category" value="<?= htmlspecialchars($product['category']) ?>"></label><br><br>
            <label>Unit: <input type="text" name="unit" value="<?= htmlspecialchars($product['unit']) ?>"></label><br><br>
            <button type="submit" class="admin-btn">Update Product</button>
        </form>
    </div>
</body>
</html>