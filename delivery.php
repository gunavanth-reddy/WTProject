<?php
// Start session at the beginning of the file
session_start();


// TEMP: Just to check if the session is working
/*echo "<pre>";
print_r($_SESSION['orderItems']);
echo "</pre>"; */
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delivery - Savory Seasons Restaurant</title>
  <style>
   /* Base styles and resets */
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

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

/* Header styles */
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
  transition: transform 0.3s ease;
}

.logo:hover {
  transform: scale(1.05);
}

.logo span {
  color: #e6c095;
}

nav {
  display: flex;
  gap: 10px;
}

.nav-link {
  color: white;
  text-decoration: none;
  font-weight: 500;
  padding: 8px 12px;
  border-radius: 4px;
  transition: all 0.3s ease;
}

.nav-link:hover {
  background-color: rgba(230, 192, 149, 0.2);
  transform: translateY(-2px);
}

.welcome-message {
  color: #e6c095;
  font-weight: 500;
}

/* Delivery Form Section */
.delivery-section {
  padding: 60px 5%;
  background-color: #f7f5f0;
  text-align: center;
  max-width: 1000px;
  margin: 0 auto;
}

.section-title {
  font-size: 2.5rem;
  color: #42273b;
  margin-bottom: 30px;
  position: relative;
  display: inline-block;
}

.section-title::after {
  content: '';
  display: block;
  width: 80px;
  height: 3px;
  background-color: #e6c095;
  margin: 10px auto 0;
}

.section-subtitle {
  font-size: 1.8rem;
  color: #42273b;
  margin-bottom: 20px;
  position: relative;
  display: inline-block;
}

.section-subtitle::after {
  content: '';
  display: block;
  width: 60px;
  height: 2px;
  background-color: #e6c095;
  margin: 8px auto 0;
}

/* Option selector styles */
.option-selector {
  display: flex;
  justify-content: center;
  margin-bottom: 30px;
}

.option-btn {
  background-color: #f0ebe3;
  color: #42273b;
  border: none;
  padding: 12px 25px;
  font-size: 1rem;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.3s ease;
}

.option-btn:first-child {
  border-radius: 30px 0 0 30px;
}

.option-btn:last-child {
  border-radius: 0 30px 30px 0;
}

.option-btn.active {
  background-color: #e6c095;
  color: white;
}

/* Form styles */
.delivery-form, .takeaway-form {
  background-color: white;
  padding: 40px;
  border-radius: 10px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
  margin-top: 30px;
  text-align: left;
}

.form-group {
  margin-bottom: 25px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
  color: #42273b;
}

.form-control {
  width: 100%;
  padding: 12px 15px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 1rem;
  transition: all 0.3s ease;
}

.form-control:focus {
  border-color: #e6c095;
  outline: none;
  box-shadow: 0 0 0 3px rgba(230, 192, 149, 0.2);
}

.form-row {
  display: flex;
  flex-wrap: wrap;
  margin: 0 -10px;
}

.form-col {
  flex: 1;
  min-width: 250px;
  padding: 0 10px;
}

.submit-btn {
  background-color: #e6c095;
  color: #42273b;
  border: none;
  padding: 12px 28px;
  font-size: 1.1rem;
  font-weight: bold;
  border-radius: 30px;
  cursor: pointer;
  text-decoration: none;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
  display: inline-block;
  margin-top: 20px;
}

.submit-btn:hover {
  background-color: #d9b087;
  transform: translateY(-3px);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

/* Footer styles */
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

.social-icons {
  display: flex;
  gap: 15px;
}

.social-icon {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background-color: rgba(255, 255, 255, 0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
}

.social-icon:hover {
  background-color: #e6c095;
  transform: translateY(-3px);
}

.social-icon img {
  width: 20px;
  height: 20px;
  object-fit: cover;
}

.copyright {
  background-color: #331d2e;
  color: white;
  text-align: center;
  padding: 15px 0;
  font-size: 0.9rem;
}

.opening-hours {
  color: white;
  margin-top: 20px;
}

.opening-hours p {
  margin-bottom: 8px;
  display: flex;
  justify-content: space-between;
}

.opening-hours .day {
  font-weight: bold;
  color: #e6c095;
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
  
  .form-row {
    flex-direction: column;
  }
  
  .form-col {
    padding: 0;
  }
  
  .delivery-form, .takeaway-form {
    padding: 20px;
  }
  
  .section-title {
    font-size: 2rem;
  }
  
  .section-subtitle {
    font-size: 1.5rem;
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
     < <a href="payment.html" class="nav-link">Payment</a>
      <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
        <!-- Show when user is logged in -->
        <span class="nav-link welcome-message">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
        <a href="logout.php" class="nav-link">Logout</a>
      <?php else: ?>
        <!-- Show when user is not logged in -->
        <a href="login.html" class="nav-link">Login / Sign Up</a>
      <?php endif; ?>
    </nav>
  </div>

 <!-- Delivery Form Section -->
<div class="delivery-section">
  <h2 class="section-title">Order Information</h2>
  
  <!-- Add toggle buttons -->
  <div class="option-selector">
    <button class="option-btn active" id="delivery-option">Delivery</button>
    <button class="option-btn" id="takeaway-option">Take Away</button>
  </div>
  
  <!-- Delivery Form Container -->
  <div id="delivery-form-container">
    <h3 class="section-subtitle">Delivery Details</h3>
    <form class="delivery-form" action="process_delivery.php" method="post">
      <input type="hidden" name="order_type" value="delivery">
      <div class="form-row">
        <div class="form-col">
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" class="form-control" required>
          </div>
        </div>
        <div class="form-col">
          <div class="form-group">
            <label for="phone">Phone</label>
            <input type="tel" id="phone" name="phone" class="form-control" required>
          </div>
        </div>
      </div>
      
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" class="form-control" required>
      </div>
      
      <div class="form-group">
        <label for="address">Address</label>
        <textarea id="address" name="address" class="form-control" rows="3" required></textarea>
      </div>
      
      <div class="form-group">
        <label for="delivery-instructions">Delivery Instructions (Optional)</label>
        <textarea id="delivery-instructions" name="delivery_instructions" class="form-control" rows="2"></textarea>
      </div>
      
      <div class="form-group">
        <label for="delivery-time">Preferred Delivery Time</label>
        <input type="time" id="delivery-time" name="preferred_time" class="form-control">
      </div>
      
      <div style="text-align: center;">
        <button type="submit" class="submit-btn">proceed to payment</button>
      </div>
    </form>
  </div>
  
  <!-- Take Away Form Container -->
  <div id="takeaway-form-container" style="display: none;">
    <h3 class="section-subtitle">Take Away Details</h3>
    <form class="takeaway-form" action="process_takeaway.php" method="post">
      <input type="hidden" name="order_type" value="takeaway">
      <div class="form-row">
        <div class="form-col">
          <div class="form-group">
            <label for="takeaway-name">Name</label>
            <input type="text" id="takeaway-name" name="name" class="form-control" required>
          </div>
        </div>
        <div class="form-col">
          <div class="form-group">
            <label for="takeaway-phone">Phone</label>
            <input type="tel" id="takeaway-phone" name="phone" class="form-control" required>
          </div>
        </div>
      </div>
      
      <div class="form-group">
        <label for="takeaway-email">Email (Optional)</label>
        <input type="email" id="takeaway-email" name="email" class="form-control">
      </div>
      
      <div class="form-group">
        <label for="pickup-time">Preferred Pickup Time</label>
        <input type="time" id="pickup-time" name="pickup_time" class="form-control" required>
      </div>
      
      <div class="form-group">
        <label for="takeaway-notes">Special Instructions (Optional)</label>
        <textarea id="takeaway-notes" name="special_instructions" class="form-control" rows="2"></textarea>
      </div>
      
      <div style="text-align: center;">
        <button type="submit" class="submit-btn">proceed to payment</button>
      </div>
    </form>
  </div>
</div>


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
    // Toggle between delivery and takeaway forms
document.getElementById('delivery-option').addEventListener('click', function() {
  document.getElementById('delivery-form-container').style.display = 'block';
  document.getElementById('takeaway-form-container').style.display = 'none';
  this.classList.add('active');
  document.getElementById('takeaway-option').classList.remove('active');
});

document.getElementById('takeaway-option').addEventListener('click', function() {
  document.getElementById('delivery-form-container').style.display = 'none';
  document.getElementById('takeaway-form-container').style.display = 'block';
  this.classList.add('active');
  document.getElementById('delivery-option').classList.remove('active');
    });


  </script>
</body>
</html>
