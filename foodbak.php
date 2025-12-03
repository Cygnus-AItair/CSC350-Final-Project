<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FoodBankProject</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

<header>
  <h1>Welcome</h1>
  <div class="nav-buttons">
    <!-- Visible by default -->
    <button id="signinBtn" onclick="toggleModal('signinModal')">Sign In</button>
    <button id="signupBtn" onclick="toggleModal('signupModal')">Sign Up</button>
    <button onclick="location.href='cart.php'">Cart</button>

    <!-- Hidden by default Use PHP to enable display--> 
    <button id="logoutBtn" style="display:none;">Log Out</button>
  </div>
</header>

  <div class="table">
    <h2>The Pantry</h2>
  </div>

  <div class="section">
    <h2>Featured Products</h2>
    <div class="products">
      <div class="product">
        <img src="images/rice.jpg" alt="Product 1" />
        <h3>Rice</h3>
        <p>1lb bag</p>
        <button onclick="addToCart('Rice (1 lb)', 1)">Add to Cart</button>
      </div>
      <div class="product">
        <img src="images/oliveoil.jpg" alt="Product 2" />
        <h3>Olive Oil</h3>
        <p>16oz Bottle</p>
        <button onclick="addToCart('Olive Oil (16oz)', 1)">Add to Cart</button>
      </div>
      <div class="product">
        <img src="images/butter.jpg" alt="Product 3" />
        <h3>Butter</h3>
        <p>1lb Stick of butter</p>
        <button onclick="addToCart('Butter (1 lb)', 1)">Add to Cart</button>
      </div>
    </div>

    <div style="text-align: center; margin-top: 40px;">
      <button onclick="location.href='products.php'" style="padding: 12px 20px; background-color: #333; color: #f2ebe9; border: none; border-radius: 4px; cursor: pointer;">
        View All Pantry Products
      </button>
    </div>

    <div class="cart-status" id="cartStatus">
      🛒 Your cart is empty.
      <button onclick="location.href='cart.php'" style="padding: 12px 20px; background-color: #333; color: #f2ebe9; border: none; border-radius: 4px; cursor: pointer;align-self: right;">
        Checkout
      </button>
    </div>
  </div>

  <!-- Sign In Modal -->
  <div class="modal" id="signinModal">
    <div class="modal-content">
      <h2>Sign In</h2>
      <input type="email" placeholder="Email" />
      <input type="password" placeholder="Password" />
      <button onclick="toggleModal('signinModal')">Submit</button>
    </div>
  </div>

  <!-- Sign Up Modal -->
  <div class="modal" id="signupModal">
    <div class="modal-content">
      <h2>Sign Up</h2>
      <input type="text" placeholder="Name" />
      <input type="email" placeholder="Email" />
      <input type="password" placeholder="Password" />
      <button onclick="toggleModal('signupModal')">Register</button>
    </div>
  </div>

  <script>
    let cart = [];

    function addToCart(item, quantity) {
      cart.push({ item, quantity});
      updateCartStatus();
    }

    function updateCartStatus() {
      const cartDiv = document.getElementById('cartStatus');
      if (cart.length === 0) {
        cartDiv.innerText = '🛒 Your cart is empty.';
        return;
      }
      const items = cart.map(i => `${i.item} (Qty: ${i.quantity})`).join(', ');
      cartDiv.innerText = `🛒 Cart: ${items}`;
    }

    function viewCart() {
      alert(cart.length ? cart.map(i => `${i.item} - Quantity: $(i.quantity}').join('\n') : 'Cart is empty.');
    }

    function toggleModal(id) {
      const modal = document.getElementById(id);
      modal.style.display = modal.style.display === 'flex' ? 'none' : 'flex';
    }

    // Close modals on outside click
    window.onclick = function(event) {
      ['signinModal', 'signupModal'].forEach(id => {
        const modal = document.getElementById(id);
        if (event.target === modal) modal.style.display = 'none';
      });
    };
  </script>

</body>
</html>
