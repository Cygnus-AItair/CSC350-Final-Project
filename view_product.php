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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Product Details</h1>
    </header>
    <div class="section">
        <p><strong>ID:</strong> <?= htmlspecialchars($product['id']) ?></p>
        <p><strong>Name:</strong> <?= htmlspecialchars($product['name']) ?></p>
        <p><strong>Category:</strong> <?= htmlspecialchars($product['category']) ?></p>
        <p><strong>Unit:</strong> <?= htmlspecialchars($product['unit']) ?></p>
        <p><strong>Created:</strong> <?= htmlspecialchars($product['created_at']) ?></p>
        <button onclick="location.href='admin_dashboard.php'" class="back-btn">Back</button>
    </div>
</body>
</html>