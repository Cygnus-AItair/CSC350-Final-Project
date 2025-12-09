<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>The Pantry</title>
  <link rel="stylesheet" href="style.css" />

  <style>
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.6);
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background: white;
      padding: 20px;
      width: 300px;
      border-radius: 8px;
      text-align: center;
    }

    .modal-content input {
      width: 90%;
      padding: 10px;
      margin: 10px 0;
    }

    .modal-content button {
      padding: 10px 20px;
      cursor: pointer;
    }
  </style>
</head>
<body>

<header>
  <h1>The Pantry</h1>
  <div class="nav-buttons">
  <button onclick="location.href='foodbak.php'">Home</button>
  <button onclick="location.href='products.php'">Products</button>
  <button onclick="location.href='cart.php'">Cart</button>

  <?php if (isset($_SESSION['user_id'])): ?>
      <span class="welcome">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></span>
      <button onclick="location.href='logout.php'">Log Out</button>
  <?php else: ?>
      <button onclick="toggleModal('signinModal')">Sign In</button>
      <button onclick="toggleModal('signupModal')">Sign Up</button>
  <?php endif; ?>
</div>
</header>


<div class="hero-image">
  <img src="images/foodspread.png" alt="Food Spread" style="width:100%; height:auto;" />
</div>

<div class="section">
  <h2>Featured Products</h2>
  <div class="products">
    <?php
    $result = $conn->query("SELECT id, name, description, image_path FROM products LIMIT 3");
    while ($row = $result->fetch_assoc()):
    ?>
      <div class="product">
        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" 
             alt="<?php echo htmlspecialchars($row['name']); ?>" />
        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
        <p><?php echo htmlspecialchars($row['description']); ?></p>
        <form action="add_to_cart.php" method="post">
          <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
          <button type="submit">Add to Cart</button>
        </form>
      </div>
    <?php endwhile; ?>
  </div>

  <div style="text-align: center; margin-top: 40px;">
    <button onclick="location.href='products.php'" class="view-all-btn">
      View All Pantry Products
    </button>
  </div>
</div>

<!-- Sign In Modal -->
<div class="modal" id="signinModal">
  <div class="modal-content">
    <h2>Sign In</h2>
      <form method="POST" action="signin.php">
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit">Submit</button>
    </form>
  </div>
</div>

<!-- Sign Up Modal -->
<div class="modal" id="signupModal">
  <div class="modal-content">
    <h2>Sign Up</h2>
    <form action="signup.php" method="post">
      <input type="text" name="name" placeholder="Name" required />
      <input type="email" name="email" placeholder="Email" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit">Register</button>
    </form>
  </div>
</div>

<script>
  function toggleModal(id) {
    const modal = document.getElementById(id);
    modal.style.display = modal.style.display === 'flex' ? 'none' : 'flex';
  }

  window.onclick = function(event) {
    ['signinModal','signupModal'].forEach(id => {
      const modal = document.getElementById(id);
      if (event.target === modal) modal.style.display = 'none';
    });
  };
</script>

</body>
</html>
