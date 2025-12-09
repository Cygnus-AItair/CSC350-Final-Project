<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $unit = trim($_POST['unit']);

    if ($name && $category && $unit) {
        $stmt = $conn->prepare("INSERT INTO products (name, category, unit, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $name, $category, $unit);
        $stmt->execute();

        $_SESSION['message'] = "Product added successfully.";
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "All fields are required.";
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
        <h1>Add Product</h1>
    </header>
    <div class="section">
        <form method="post">
            <label>Name: <input type="text" name="name"></label><br><br>
            <label>Category: <input type="text" name="category"></label><br><br>
            <label>Unit: <input type="text" name="unit"></label><br><br>
            <button type="submit" class="admin-btn">Add Product</button>
        </form>
    </div>
</body>
</html>