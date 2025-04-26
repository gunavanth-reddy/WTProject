
<?php
session_start();
include 'db_connect.php';

// Check if we need to refresh menu data
if (!isset($_SESSION['foodItems']) || !isset($_SESSION['drinkItems']) || 
    !isset($_SESSION['menu_last_updated']) || (time() - $_SESSION['menu_last_updated'] > 300)) {
    // Load menu data from database
    $query = "SELECT id, item_name, category, price, availability FROM menu ORDER BY category, item_name";
    $result = $conn->query($query);

    $foodItems = [];
    $drinkItems = [];

    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Define image paths based on item names
            $imagePath = "";
            switch($row['item_name']) {
                case 'Butter Chicken':
                    $imagePath = "https://www.samaheats.com/wp-content/uploads/2024/03/Untitled-design-15-500x500.png";
                    break;
                case 'Paneer Tikka':
                    $imagePath = "https://kannanskitchen.com/wp-content/uploads/2023/01/DSC_4737-500x500.jpg";
                    break;
                case 'Garlic Naan':
                    $imagePath = "https://zestfulkitchen.com/wp-content/uploads/2020/01/garlic-naan-hero_for-web-4-683x1024.jpg";
                    break;
                case 'Chicken Biryani':
                    $imagePath = "https://www.licious.in/blog/wp-content/uploads/2022/06/chicken-hyderabadi-biryani-01.jpg";
                    break;
                case 'Dal Makhani':
                    $imagePath = "https://palatesdesire.com/wp-content/uploads/2018/12/Dal_Makhani-scaled.jpg";
                    break;
                case 'Chole Bhature':
                    $imagePath = "https://media.vogue.in/wp-content/uploads/2020/08/chole-bhature-recipe.jpg";
                    break;
                case 'Palak Paneer':
                    $imagePath = "https://www.vegrecipesofindia.com/wp-content/uploads/2021/06/palak-paneer-3.jpg";
                    break;
                case 'Masala Dosa':
                    $imagePath = "https://vismaifood.com/storage/app/uploads/public/8b4/19e/427/thumb__700_0_0_0_auto.jpg";
                    break;
                case 'Masala Chai':
                    $imagePath = "https://www.teacupsfull.com/cdn/shop/articles/Screenshot_2023-10-20_at_11.07.13_AM.png?v=1697780292";
                    break;
                case 'Mango Lassi':
                    $imagePath = "https://cakewhiz.com/wp-content/uploads/2022/05/Easy-Indian-Mango-Lassi.jpg";
                    break;
                case 'Sweet Lassi':
                    $imagePath = "https://pipingpotcurry.com/wp-content/uploads/2021/05/Lassi-in-a-glass.jpg";
                    break;
                case 'Badam Milk':
                    $imagePath = "https://www.indianveggiedelight.com/wp-content/uploads/2023/04/badam-milk-featured.jpg";
                    break;
                case 'Thandai':
                    $imagePath = "https://masalaandchai.com/wp-content/uploads/2021/03/Thandai.jpg";
                    break;
                case 'Coffee':
                    $imagePath = "https://images.pexels.com/photos/312418/pexels-photo-312418.jpeg?cs=srgb&dl=pexels-chevanon-312418.jpg&fm=jpg";
                    break;
                default:
                    $imagePath = "https://via.placeholder.com/250x180?text=Image+Not+Available";
            }

            $item = [
                'id' => $row['id'],
                'name' => $row['item_name'],
                'price' => (float)$row['price'],
                'availability' => $row['availability'] == 1 ? true : false,
                'image' => $imagePath
            ];
            
            // Separate food and drink items
            if (strtolower($row['category']) == 'food') {
                $foodItems[] = $item;
            } else {
                $drinkItems[] = $item;
            }
        }
    }

    // Store in session
    $_SESSION['foodItems'] = $foodItems;
    $_SESSION['drinkItems'] = $drinkItems;
    $_SESSION['menu_last_updated'] = time();
}

// Get menu data from session
$foodItems = $_SESSION['foodItems'];
$drinkItems = $_SESSION['drinkItems'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Savory Seasons - Order Online</title>
  <!-- Add your CSS here --><style>
    /* Base styles and resets */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Montserrat', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      line-height: 1.6;
      color: #333333;
      background-color: #f7f5f0;
      animation: fadeIn 1s ease;
    }
    .out-of-stock {
      opacity: 0.7;
      position: relative;
    }

    .out-of-stock::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(255, 255, 255, 0.3);
      z-index: 1;
    }

    .out-of-stock-label {
      color: #e53935;
      font-weight: bold;
      margin-top: 10px;
      text-align: center;
      padding: 5px;
      border: 1px dashed #e53935;
      border-radius: 4px;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    
    /* Header styles with floating effect - matching home page */
    .header {
      background-color: rgba(66, 39, 59, 0.95);
      color: white;
      padding: 15px 5%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 100;
      box-shadow: 0 2px 15px rgba(0, 0, 0, 0.15);
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .logo {
      font-size: 1.8rem;
      font-weight: bold;
      color: white;
      text-decoration: none;
      display: flex;
      align-items: center;
    }

    .logo span {
      color: #e6c095;
    }

    nav {
      display: flex;
      gap: 5px;
    }

    .nav-link {
      color: white;
      text-decoration: none;
      margin: 0 10px;
      font-weight: 500;
      padding: 8px 12px;
      border-radius: 4px;
      transition: all 0.3s ease;
    }

    .nav-link:hover {
      background-color: rgba(230, 192, 149, 0.2);
      transform: translateY(-2px);
    }

    /* Page title */
    .page-title {
      text-align: center;
      padding: 40px 20px;
      background-color: #42273b;
      color: white;
    }

    .page-title h1 {
      font-size: 2.5rem;
      margin-bottom: 10px;
      text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
    }

    .page-title p {
      font-size: 1.1rem;
      max-width: 800px;
      margin: 0 auto;
    }

    /* Menu sections */
    .menu-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 40px 20px;
    }

    .section-title {
      font-size: 2rem;
      color: #42273b;
      margin-bottom: 30px;
      position: relative;
      display: inline-block;
    }

    .section-title::after {
      content: '';
      display: block;
      width: 60px;
      height: 3px;
      background-color: #e6c095;
      margin-top: 10px;
    }

    /* Menu grid */
    .menu-section {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 30px;
      margin-bottom: 50px;
    }

    .menu-item {
      background-color: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .menu-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .menu-item img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }

    .menu-item-details {
      padding: 15px;
    }

    .menu-item-details h3 {
      color: #42273b;
      margin-bottom: 5px;
      font-size: 1.2rem;
    }

    .menu-item-price {
      color: #e6c095;
      font-weight: bold;
      margin-bottom: 10px;
      font-size: 1.1rem;
    }

    /* Quantity selector - modern style */
    .quantity-selector {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-top: 10px;
    }

    .quantity-btn {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      border: none;
      background-color: #f0ebe3;
      color: #42273b;
      font-size: 1.2rem;
      font-weight: bold;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s ease;
    }

    .quantity-btn:hover {
      background-color: #e6c095;
      color: white;
    }

    .quantity-btn:active {
      transform: scale(0.95);
    }

    .quantity-value {
      font-size: 1rem;
      font-weight: bold;
      width: 40px;
      text-align: center;
      border: none;
      background: transparent;
    }

    /* Order summary */
    .order-summary {
      position: sticky;
      top: 90px;
      background-color: white;
      border-radius: 10px;
      padding: 25px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
      max-width: 1000px;
      margin: 0 auto 50px;
    }

    .order-summary h2 {
      color: #42273b;
      margin-bottom: 20px;
      font-size: 1.5rem;
    }

    .order-list {
      list-style: none;
      margin-bottom: 20px;
      max-height: 300px;
      overflow-y: auto;
    }

    .order-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 0;
      border-bottom: 1px solid #f0ebe3;
    }

    .order-item-details {
      flex: 1;
    }

    .order-item-name {
      font-weight: bold;
    }

    .order-item-price {
      color: #666;
    }

    .order-item-actions {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .remove-btn {
      background-color: #ffebee;
      color: #e53935;
      border: none;
      border-radius: 4px;
      padding: 5px 10px;
      cursor: pointer;
      font-size: 0.8rem;
      transition: all 0.2s ease;
    }

    .remove-btn:hover {
      background-color: #e53935;
      color: white;
    }

    .order-total {
      display: flex;
      justify-content: space-between;
      font-size: 1.2rem;
      font-weight: bold;
      padding: 15px 0;
      border-top: 2px solid #f0ebe3;
      margin-top: 10px;
    }

    .checkout-btn {
      background-color: #e6c095;
      color: #42273b;
      border: none;
      padding: 12px 25px;
      font-size: 1.1rem;
      font-weight: bold;
      border-radius: 30px;
      cursor: pointer;
      width: 100%;
      margin-top: 20px;
      transition: all 0.3s ease;
    }

    .checkout-btn:hover {
      background-color: #d9b087;
      transform: translateY(-3px);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    /* Toast notification */
    .toast {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color: #42273b;
      color: white;
      padding: 15px 25px;
      border-radius: 5px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
      display: flex;
      align-items: center;
      transform: translateY(100px);
      opacity: 0;
      transition: all 0.3s ease;
      z-index: 1000;
    }

    .toast.show {
      transform: translateY(0);
      opacity: 1;
    }

    .toast-icon {
      margin-right: 10px;
      font-size: 1.2rem;
    }
    
  /* Social icons resized */
  .social-icons {
    display: flex;
    gap: 15px;
  }

  .social-icon img {
    width: 40px; /* Resized for consistency */
    height: 40px;
    border-radius: 50%;
    transition: transform 0.3s ease;
  }
  .refresh-btn {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background-color: #42273b;
  color: white;
  border: none;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 2px 10px rgba(0,0,0,0.2);
  z-index: 100;
  transition: all 0.3s ease;
}

.refresh-btn:hover {
  transform: rotate(180deg);
  background-color: #e6c095;
}

    /* Footer - matching home page */
    .copyright {
      background-color: #331d2e;
      color: white;
      text-align: center;
      padding: 15px 0;
      font-size: 0.9rem;
    }

    .footer-nav {
      background: linear-gradient(to bottom, #42273b, #331d2e);
      padding: 50px 5%;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
      align-items: flex-start;
    }

    .column {
      margin: 15px;
      min-width: 150px;
    }

    .column h4 {
      margin-bottom: 20px;
      font-weight: bold;
      color: #fff;
      position: relative;
      padding-bottom: 10px;
    }

    .column h4::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 40px;
      height: 3px;
      background-color: #e6c095;
    }

    .column ul {
      list-style: none;
    }

    .column li {
      margin-bottom: 12px;
    }

    .column a {
      text-decoration: none;
      color: #dfdfdf;
      transition: all 0.3s ease;
    }

    .column a:hover {
      color: #e6c095;
      padding-left: 5px;
    }

    /* Responsive design */
    @media (max-width: 768px) {
      .header {
        flex-direction: column;
        padding: 15px;
      }
      
      .logo {
        margin-bottom: 15px;
      }
      
      nav {
        width: 100%;
        justify-content: center;
        flex-wrap: wrap;
      }
      
      .nav-link {
        margin: 5px;
      }
      
      .menu-section {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      }
    }
    
    @media (max-width: 480px) {
      .page-title h1 {
        font-size: 2rem;
      }
      
      .section-title {
        font-size: 1.5rem;
      }
      
      .menu-section {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <!-- Header with navigation -->
  <div class="header">
    <a href="welcome.php" class="logo">Savory <span>Seasons</span></a>
    <nav>
      <a href="order.php" class="nav-link">Menu&Orders</a>
      <a href="reservation page.html" class="nav-link">Reservations</a>
      <a href="delivery.php" class="nav-link">Delivery / Take Away</a>
      <a href="payment.html" class="nav-link">Payment</a>
    </nav>
  </div>
  <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
          <!-- Show when user is logged in -->
          <span class="nav-link welcome-message">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
          <a href="logout.php" class="nav-link">Logout</a>
        <?php else: ?>
          <!-- Show when user is not logged in -->
          <a href="login.html" class="nav-link">Login / Sign Up</a>
        <?php endif; ?>
  <!-- Page title -->
  <div class="page-title">
    <h1>Menu&Order Online</h1>
    <p>Enjoy our seasonal menu from the comfort of your home. All orders are prepared fresh when you order.</p>
  </div>

  <div class="menu-container">
    <!-- Food section -->
    <h2 class="section-title">Food Menu</h2>
    <div class="menu-section" id="foodMenu"></div>

    <!-- Drinks section -->
    <h2 class="section-title">Beverages</h2>
    <div class="menu-section" id="drinkMenu"></div>

    <!-- Order summary -->
    <div class="order-summary">
      <h2>Your Order</h2>
      <ul class="order-list" id="orderList"></ul>
      <div class="order-total">
        <span>Total:</span>
        <span id="orderTotal">₹0</span>
      </div>
      <button class="checkout-btn" onclick="placeOrder()">Delivery/takeaway details</button>
    </div>
  </div>

  <!-- Toast notification -->
  <div class="toast" id="toast">
    <span class="toast-icon">✓</span>
    <span id="toastMessage"></span>
  </div>

  <!-- Refresh button -->
  <button onclick="refreshMenuAvailability()" class="refresh-btn">
    <span>Refresh Menu</span>
  </button>

  <!-- Footer navigation -->
  <div class="footer-nav">
    <div class="column">
      <h4>About Us</h4>
      <ul>
        <li><a href="#">Our Story</a></li>
        <li><a href="#">Our Team</a></li>
        <li><a href="#">Careers</a></li>
      </ul>
    </div>

    <div class="column">
      <h4>Contact</h4>
      <ul>
        <li><a href="#">Make a Reservation</a></li>
        <li style="color:white;"> Contact Us: 99995846954</li>
      </ul>
    </div>

    <div class="column">
      <h4>Opening Hours</h4>
      <div class="opening-hours">
        <p><span class="day">Monday - Thursday:</span> <span>11:30 AM - 10:00 PM</span></p>
        <p><span class="day">Friday - Saturday:</span> <span>11:30 AM - 11:00 PM</span></p>
        <p><span class="day">Sunday:</span> <span>10:00 AM - 9:00 PM</span></p>
        <p><span class="day">Brunch:</span> <span>Weekends 10:00 AM - 2:00 PM</span></p>
      </div>
    </div>
    
    <div class="column">
      <h4>Connect With Us</h4>
      <div class="social-icons">
        <a href="#" class="social-icon"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/95/Instagram_logo_2022.svg/640px-Instagram_logo_2022.svg.png" alt="Instagram"></a>
        <a href="#" class="social-icon"><img src="https://e7.pngegg.com/pngimages/708/311/png-clipart-twitter-twitter-thumbnail.png" alt="Twitter"></a>
        <a href="#" class="social-icon"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRokEYt0yyh6uNDKL8uksVLlhZ35laKNQgZ9g&s" alt="LinkedIn"></a>
        <a href="#" class="social-icon"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/51/Facebook_f_logo_%282019%29.svg/800px-Facebook_f_logo_%282019%29.svg.png" alt="Facebook"></a>
      </div>
    </div>
  </div>

  <!-- Copyright footer -->
  <div class="copyright">
    &copy; 2025 Savory Seasons Restaurant. All Rights Reserved.
  </div>

  <script>
    // Initialize menu items from PHP session data
    const foodItems = <?php echo json_encode($foodItems); ?>;
    const drinkItems = <?php echo json_encode($drinkItems); ?>;
    
    // Order management
    let orderItems = {};
    let total = 0;

    // Display menu items
    function displayMenu() {
        const foodMenu = document.getElementById("foodMenu");
        const drinkMenu = document.getElementById("drinkMenu");

        foodMenu.innerHTML = '';
        drinkMenu.innerHTML = '';

        // Create food items
        foodItems.forEach(item => {
            foodMenu.innerHTML += `
                <div class="menu-item ${!item.availability ? 'out-of-stock' : ''}">
                    <img src="${item.image}" alt="${item.name}" onerror="this.src='https://via.placeholder.com/250x180?text=Image+Not+Available'">
                    <div class="menu-item-details">
                        <h3>${item.name}</h3>
                        <div class="menu-item-price">₹${item.price}</div>
                        ${item.availability ? 
                        `<div class="quantity-selector">
                            <button class="quantity-btn" onclick="decreaseQuantity('${item.name}')">-</button>
                            <input type="text" class="quantity-value" id="qty-${item.name.replace(/\s+/g, '-').toLowerCase()}" value="0" readonly>
                            <button class="quantity-btn" onclick="addToOrder('${item.name}', ${item.price})">+</button>
                        </div>` : 
                        `<div class="out-of-stock-label">Currently Unavailable</div>`}
                    </div>
                </div>
            `;
        });

        // Create drink items
        drinkItems.forEach(item => {
            drinkMenu.innerHTML += `
                <div class="menu-item ${!item.availability ? 'out-of-stock' : ''}">
                    <img src="${item.image}" alt="${item.name}" onerror="this.src='https://via.placeholder.com/250x180?text=Image+Not+Available'">
                    <div class="menu-item-details">
                        <h3>${item.name}</h3>
                        <div class="menu-item-price">₹${item.price}</div>
                        ${item.availability ? 
                        `<div class="quantity-selector">
                            <button class="quantity-btn" onclick="decreaseQuantity('${item.name}')">-</button>
                            <input type="text" class="quantity-value" id="qty-${item.name.replace(/\s+/g, '-').toLowerCase()}" value="0" readonly>
                            <button class="quantity-btn" onclick="addToOrder('${item.name}', ${item.price})">+</button>
                        </div>` : 
                        `<div class="out-of-stock-label">Currently Unavailable</div>`}
                    </div>
                </div>
            `;
        });
    }

    function addToOrder(name, price) {
        // Find the item in either food or drink arrays
        const item = [...foodItems, ...drinkItems].find(item => item.name === name);
        
        // Check if item exists and is available
        if (!item || !item.availability) {
            showToast(`Sorry, ${name} is currently not available`);
            return;
        }
        
        if (!orderItems[name]) {
            orderItems[name] = {
                quantity: 0,
                price: price
            };
        }

        orderItems[name].quantity++;
        updateOrderDisplay();
        showToast(`Added ${name} to your order`);

        // Update quantity display
        const qtyInput = document.getElementById(`qty-${name.replace(/\s+/g, '-').toLowerCase()}`);
        if (qtyInput) {
            qtyInput.value = orderItems[name].quantity;
        }
    }

    function decreaseQuantity(name) {
        if (orderItems[name] && orderItems[name].quantity > 0) {
            orderItems[name].quantity--;

            if (orderItems[name].quantity === 0) {
                delete orderItems[name];
            }

            updateOrderDisplay();
            showToast(`Removed ${name} from your order`);
        }

        // Update quantity display
        const qtyInput = document.getElementById(`qty-${name.replace(/\s+/g, '-').toLowerCase()}`);
        if (qtyInput) {
            qtyInput.value = orderItems[name] ? orderItems[name].quantity : 0;
        }
    }

    function updateOrderDisplay() {
        const orderList = document.getElementById("orderList");
        const orderTotal = document.getElementById("orderTotal");

        orderList.innerHTML = '';
        total = 0;

        for (const [name, item] of Object.entries(orderItems)) {
            if (item.quantity > 0) {
                const itemTotal = item.price * item.quantity;
                total += itemTotal;

                orderList.innerHTML += `
                    <li class="order-item">
                        <div class="order-item-details">
                            <div class="order-item-name">${name} × ${item.quantity}</div>
                            <div class="order-item-price">₹${item.price} each</div>
                        </div>
                        <div class="order-item-actions">
                            <div>₹${itemTotal}</div>
                            <button class="remove-btn" onclick="removeFromOrder('${name}')">Remove</button>
                        </div>
                    </li>
                `;
            }
        }

        orderTotal.textContent = `₹${total}`;
    }

    function removeFromOrder(name) {
        if (orderItems[name]) {
            delete orderItems[name];
            updateOrderDisplay();
            showToast(`Removed ${name} from your order`);

            // Reset quantity display
            const qtyInput = document.getElementById(`qty-${name.replace(/\s+/g, '-').toLowerCase()}`);
            if (qtyInput) {
                qtyInput.value = 0;
            }
        }
    }

    function placeOrder() {
        if (Object.keys(orderItems).length === 0) {
            showToast("Your order is empty.");
            return;
        }

        // Check if any ordered items are now out of stock
        const unavailableItems = [];
        for (const itemName in orderItems) {
            const item = [...foodItems, ...drinkItems].find(i => i.name === itemName);
            if (item && !item.availability) {
                unavailableItems.push(itemName);
            }
        }

        if (unavailableItems.length > 0) {
            showToast(`Sorry, ${unavailableItems.join(', ')} ${unavailableItems.length > 1 ? 'are' : 'is'} no longer available.`);
            return;
        }

        // Store order items in session for the next page
        fetch("save_order.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(orderItems)
        })
        .then(response => response.text())
        .then(result => {
            console.log(result);
            // Go to delivery page after saving
            window.location.href = "delivery.php";
        })
        .catch(error => {
            console.error("Error saving order:", error);
            showToast("Error processing your order. Please try again.");
        });
    }

    function showToast(message) {
        const toast = document.getElementById("toast");
        const toastMessage = document.getElementById("toastMessage");

        toastMessage.textContent = message;
        toast.classList.add("show");

        setTimeout(() => {
            toast.classList.remove("show");
        }, 3000);
    }
    
    function refreshMenuAvailability() {
        window.location.href = "order.php";
    }

    window.onload = function() {
        displayMenu();
    };
  </script>
</body>
</html>
