<?php
session_start();
require_once 'db.php';

//check if user is admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: foodbak.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $category = $conn->real_escape_string($_POST['category']);
    $unit = $conn->real_escape_string($_POST['unit']);
    
    if (empty($name) || empty($category) || empty($unit)) {
        $error = "Name, category, and unit are required.";
    } else {
        //insert the product
        $sql = "INSERT INTO products (name, description, category, unit) 
                VALUES ('$name', '$description', '$category', '$unit')";
        
        if ($conn->query($sql) === TRUE) {
            $success = "Product added successfully!";
            $name = $description = $category = $unit = '';
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
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
            <div style="color: red; padding: 10px; background: #ffe6e6; border-radius: 4px; margin-bottom: 15px;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div style="color: green; padding: 10px; background: #e6ffe6; border-radius: 4px; margin-bottom: 15px;">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <input type="text" name="name" placeholder="Product Name" required 
                   value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>"
                   style="width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px;">
            
            <textarea name="description" placeholder="Description" 
                      style="width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; height: 100px;"><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
            
            <input type="text" name="category" placeholder="Category (e.g., Grains, Canned Goods)" required 
                   value="<?php echo isset($category) ? htmlspecialchars($category) : ''; ?>"
                   style="width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px;">
            
            <input type="text" name="unit" placeholder="Unit (e.g., 5 lb bag, 16 oz bottle)" required 
                   value="<?php echo isset($unit) ? htmlspecialchars($unit) : ''; ?>"
                   style="width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px;">
            
            <button type="submit" 
                   style="width: 100%; padding: 12px; background-color: #333; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
                Add Product
            </button>
        </form>
    </div>
</body>
</html>
