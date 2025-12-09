<?php
session_start();
include 'db.php';

// Get products ordered by category and name
$stmt = $conn->prepare("SELECT * FROM products ORDER BY category, name");
$stmt->execute();
$result = $stmt->get_result();

// Organize products by category
$productsByCategory = [];
while ($row = $result->fetch_assoc()) {
    $productsByCategory[$row['category']][] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
  <h1>Products</h1>
  <div class="nav-buttons">
    <button onclick="location.href='foodbak.php'">Home</button>
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

<?php foreach ($productsByCategory as $category => $products): ?>
    <div class="section">
        <h2><?= htmlspecialchars($category) ?></h2>
        <div class="products">
            <?php foreach ($products as $product): ?>
                <div class="product">
                    <img src="<?= htmlspecialchars($product['image_path']) ?>" 
                         alt="<?= htmlspecialchars($product['name']) ?>">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p><?= htmlspecialchars($product['unit']) ?></p>
                    <form action="add_to_cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endforeach; ?>

</body>
</html>