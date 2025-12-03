<!DOCTYPE html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Your Pantry Cart</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

<header>
  <h1>Your Pantry Cart</h1>
  <div class="nav-buttons">
    <button onclick="location.href='foodbank.php'">Home</button>
    <button onclick="location.href='products.php'">Pantry</button>
    <button id="signinBtn">Sign In</button>
    <button id="signupBtn">Sign Up</button>
    <button id="logoutBtn" style="display:none;">Log Out</button>
  </div>
</header>

<div class="section">
  <h2>Items You've Selected</h2>
  <div class="products">
    <ul>
      <?php foreach ($cartItems as $item): ?>
        <li>
          <?= htmlspecialchars($item['name']) ?> 
          (Qty: <?= $item['quantity'] ?>, $<?= number_format($item['price'] * $item['quantity'], 2) ?>)
          <form action="delete_from_cart.php" method="post" class="delete-form">
            <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
            <button type="submit" class="btn">Delete</button>
          </form>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>

  <div class="checkout-section">
    <button id="checkoutBtn" class="btn">Checkout</button>
  </div>
</div>

</body>
</html>