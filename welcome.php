
<?php
// Start session at the beginning of the file
session_start();
?>
   <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Savory Seasons Restaurant</title>
    <style>
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
        /* Simple fade-in animation when page loads */
        animation: fadeIn 1s ease;
      }

      @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
      }
      
      /* Header styles with floating effect */
      .header {
        background-color: rgba(66, 39, 59, 0.95); /* Deep plum with transparency */
        color: white;
        padding: 15px 5%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.15); /* Subtle box shadow for floating effect */
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
        color: #e6c095; /* Soft gold accent */
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
        transition: all 0.3s ease; /* Smooth transition for hover */
      }

      .nav-link:hover {
        background-color: rgba(230, 192, 149, 0.2);
        transform: translateY(-2px); /* Slight lift effect */
      }

      /* Main content and image slider */
      .main-content {
        text-align: center;
        padding: 80px 20px;
        height: 75vh;
        background-size: cover;
        background-position: center;
        color: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        box-shadow: inset 0 0 150px rgba(0, 0, 0, 0.4); /* Inner shadow for depth */
      }

      .background-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4); /* Overlay for better text readability */
        z-index: 1;
      }

      .content-wrapper {
        position: relative;
        z-index: 2;
        max-width: 800px;
      }

      .main-content h2 {
        font-size: 3.5rem;
        margin-bottom: 20px;
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
      }

      .main-content p {
        font-size: 1.2rem;
        margin-bottom: 30px;
        text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7);
        max-width: 700px;
      }

      .cta-button {
        background-color: #e6c095;
        color: #42273b;
        border: none;
        padding: 12px 28px;
        font-size: 1.1rem;
        font-weight: bold;
        border-radius: 30px;
        cursor: pointer;
        text-decoration: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
      }

      .cta-button:hover {
        background-color: #d9b087;
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
      }

      /* About section */
      .about-section {
        padding: 80px 5%;
        background-color: #f7f5f0;
        text-align: center;
      }

      .section-title {
        font-size: 2.5rem;
        color: #42273b;
        margin-bottom: 20px;
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

      .about-content {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        max-width: 1200px;
        margin: 0 auto;
      }

      .about-text {
        flex: 1;
        min-width: 300px;
        padding: 20px;
        text-align: left;
      }

      .about-text h3 {
        color: #42273b;
        margin-bottom: 15px;
        font-size: 1.8rem;
      }

      .about-text p {
        margin-bottom: 15px;
        color: #555;
        line-height: 1.8;
      }

      .about-image {
        flex: 1;
        min-width: 300px;
        padding: 20px;
      }

      .about-image img {
        width: 100%;
        height: auto;
        border-radius: 10px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
      }

      .about-image img:hover {
        transform: scale(1.02);
      }

      /* Features section */
      .features {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        padding: 50px 5%;
        background-color: #f0ebe3;
      }

      .feature {
        flex: 1;
        min-width: 250px;
        max-width: 350px;
        margin: 20px;
        padding: 30px;
        text-align: center;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
      }

      .feature:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      }

      .feature h3 {
        color: #42273b;
        margin-bottom: 15px;
      }

      .feature-icon {
        font-size: 40px;
        margin-bottom: 20px;
        color: #e6c095;
      }

      /* Chef section */
      .chef-section {
        padding: 80px 5%;
        background-color: #f7f5f0;
        text-align: center;
      }

      .chef-cards {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 30px;
        margin-top: 40px;
      }

      .chef-card {
        width: 280px;
        background-color: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
      }

      .chef-card:hover {
        transform: translateY(-10px);
      }

      .chef-image {
        width: 100%;
        height: 200px;
        background-color: #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
      }

      .chef-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }

      .chef-details {
        padding: 20px;
      }

      .chef-details h3 {
        color: #42273b;
        margin-bottom: 5px;
      }

      .chef-details p {
        color: #777;
        font-style: italic;
        margin-bottom: 10px;
      }

      /* Testimonials section */
      .testimonials {
        padding: 80px 5%;
        background-color: #42273b;
        text-align: center;
        color: white;
      }

      .testimonials .section-title {
        color: white;
      }

      .testimonial-slider {
        max-width: 800px;
        margin: 40px auto 0;
        position: relative;
      }

      .testimonial {
        background-color: rgba(255, 255, 255, 0.1);
        padding: 30px;
        border-radius: 10px;
        margin: 0 20px;
      }

      .testimonial p {
        font-style: italic;
        margin-bottom: 20px;
        line-height: 1.8;
      }

      .testimonial-author {
        font-weight: bold;
        color: #e6c095;
      }

      /* Footer styles with gradient background */
      .copyright {
        background-color: #331d2e;
        color: white;
        text-align: center;
        padding: 15px 0;
        font-size: 0.9rem;
      }

      .footer-nav {
        background: linear-gradient(to bottom, #42273b, #331d2e); /* Gradient background */
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

      /* Social icons styling */
      .social-icons {
        display: flex;
        gap: 15px;
      }

      .social-icon {
        display: inline-block;
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
      .nav-link.login-signup {
  background-color: #e6c095;  /* Soft gold */
  color: #42273b;             /* Dark text */
  padding: 8px 15px;
  border-radius: 5px;
  transition: all 0.3s ease;
}

.nav-link.login-signup:hover {
  background-color: #d9b087;  /* Slightly darker gold on hover */
  transform: translateY(-2px);
}


      /* Newsletter subscribe */
      .newsletter {
        margin-top: 20px;
      }

      .newsletter input {
        padding: 10px;
        width: 100%;
        border: none;
        border-radius: 5px;
        margin-bottom: 10px;
      }

      .newsletter button {
        background-color: #e6c095;
        color: #42273b;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
      }

      .newsletter button:hover {
        background-color: #d9b087;
      }

      /* Opening hours */
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
        
        .main-content {
          height: 60vh;
          padding: 60px 15px;
        }
        
        .main-content h2 {
          font-size: 2.5rem;
        }
        
        .about-content {
          flex-direction: column;
        }
        
        .about-text, .about-image {
          width: 100%;
        }
        
        .features {
          padding: 30px 15px;
        }
        .welcome-message {
      color: #e6c095;
      font-weight: 500;
    }
        .feature {
          margin: 15px;
          padding: 20px;
        }
        
        .chef-cards {
          gap: 20px;
        }
      }
      
      @media (max-width: 480px) {
        .main-content h2 {
          font-size: 2rem;
        }
        
        .main-content p {
          font-size: 1rem;
        }
        
        .cta-button {
          padding: 10px 20px;
          font-size: 1rem;
        }
        
        .section-title {
          font-size: 2rem;
        }
      }
    </style>
  </head>
  <body>
    <!-- Header with navigation -->
    <div class="header">
      <a href="login.php" class="logo">Savory <span>Seasons</span></a>
      <nav>
        <a href="order.php" class="nav-link">Menu&Orders</a>
        <a href="reservation page.html" class="nav-link">Reservations</a>
        <a href="delivery.php" class="nav-link">Delivery / Take Away</a>


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

    <!-- Main content with image slider -->
    <div class="main-content">
      <div class="background-overlay"></div>
      <div class="content-wrapper">
        <h2>Welcome to Savory Seasons</h2>
        <p>Savor the essence of every season with dishes crafted from the finest local ingredients, blending timeless flavors with modern culinary artistry in a cozy, welcoming ambiance.</p>
        <a href="reservation page.html" class="cta-button">Reserve Your Table</a>
      </div>
    </div>

    <!-- About section -->
    <div class="about-section">
      <h2 class="section-title">Our Story</h2>
      <div class="about-content">
        <div class="about-text">
          <h3>A Culinary Journey</h3>
          <p>Founded in 2010 by Chef Chaitanya, Savory Seasons began as a small family restaurant with a passion for authentic flavors and seasonal ingredients. Over the years, we've grown into a culinary destination while maintaining our commitment to quality and hospitality.</p>
          <p>Our philosophy is simple: use the freshest ingredients, prepare them with care, and serve them with love. We work closely with local farmers and producers to ensure that every dish tells a story of our region's rich agricultural heritage.</p>
          <p>Whether you're joining us for a romantic dinner, family celebration, or business lunch, we strive to create memorable experiences that nourish both body and soul.</p>
        </div>
        <div class="about-image">
          <img src="https://images.pexels.com/photos/1267320/pexels-photo-1267320.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Restaurant interior">
        </div>
      </div>
    </div>

    <!-- Features section -->
    <div class="features">
      <div class="feature">
        <div class="feature-icon">🍽️</div>
        <h3>Seasonal Menu</h3>
        <p>Our menu changes with the seasons to showcase the freshest ingredients at their peak flavor. From spring vegetables to autumn harvest, every dish celebrates nature's bounty.</p>
      </div>
      <div class="feature">
        <div class="feature-icon">🥂</div>
        <h3>Curated Wine List</h3>
        <p>Our sommelier has carefully selected wines from around the world to perfectly complement our menu. Enjoy expert pairings that enhance your dining experience.</p>
      </div>
      <div class="feature">
        <div class="feature-icon">🛎️</div>
        <h3>Attentive Service</h3>
        <p>Our professional staff is dedicated to providing warm, personalized service that makes every guest feel special. We're here to ensure your visit exceeds expectations.</p>
      </div>
    </div>

    <!-- Chef section -->
    <div class="chef-section">
      <h2 class="section-title">Meet Our Chefs</h2>
      <div class="chef-cards">
        <div class="chef-card">
          <div class="chef-image">
            <img src="https://images.pexels.com/photos/3814446/pexels-photo-3814446.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Chef Maria">
          </div>
          <div class="chef-details">
            <h3>chaitanya</h3>
            <p>Executive Chef</p>
            <p>With over 20 years of experience in fine dining, Chef Maria brings passion and creativity to every dish she creates.</p>
          </div>
        </div>
        <div class="chef-card">
          <div class="chef-image">
            <img src="https://images.pexels.com/photos/8629099/pexels-photo-8629099.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Chef James">
          </div>
          <div class="chef-details">
            <h3>Aditya</h3>
            <p>Pastry Chef</p>
            <p>A master of sweet creations, Chef James transforms simple ingredients into extraordinary desserts that delight the senses.</p>
          </div>
        </div>
        <div class="chef-card">
          <div class="chef-image">
            <img src="https://images.pexels.com/photos/6605903/pexels-photo-6605903.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Chef Sophie">
          </div>
          <div class="chef-details">
            <h3> Tharun</h3>
            <p>Sous Chef</p>
            <p>Chef Sophie's innovative approach to traditional techniques brings unexpected flavors and textures to our signature dishes.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Testimonials section -->
    <div class="testimonials">
      <h2 class="section-title">What Our Guests Say</h2>
      <div class="testimonial-slider">
        <div class="testimonial">
          <p>"The seasonal tasting menu at Savory Seasons was an absolute delight. Each course told a story of the region, and the wine pairings were perfect. The staff made us feel like family from the moment we walked in."</p>
          <div class="testimonial-author">- Emily T., Local Food Critic</div>
        </div>
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
          <li style="color:white ;"> Contact Us: 99995846954</li>
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
        <div class="newsletter">
          <h4>Subscribe to our Newsletter</h4>
          <input type="email" placeholder="Your email address">
          <button>Subscribe</button>
        </div>
      </div>
    </div>

    <!-- Copyright footer -->
    <div class="copyright">
      &copy; 2025 Savory Seasons Restaurant. All Rights Reserved.
    </div>

    <!-- JavaScript for image slider with smooth transitions -->
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Array of background images for the slider
        const images = [
          "https://images.pexels.com/photos/958545/pexels-photo-958545.jpeg?cs=srgb&dl=pexels-chanwalrus-958545.jpg&fm=jpg",
          "https://images.pexels.com/photos/70497/pexels-photo-70497.jpeg?cs=srgb&dl=pexels-pixabay-70497.jpg&fm=jpg",
          "https://i.gzn.jp/img/2020/11/13/pretty-food-perceived-healthy/00_m.jpg"
        ];
        
        let currentIndex = 0;
        let nextIndex = 0;
        const mainContent = document.querySelector('.main-content');
        
        // Set initial background
        mainContent.style.backgroundImage = `url(${images[0]})`;

        // Create preloaded image objects to ensure smooth transitions
        const imageObjects = images.map(src => {
          const img = new Image();
          img.src = src;
          return img;
        });
        
        // Function to change background with fade effect
        function changeBackground() {
          // Update indices
          currentIndex = nextIndex;
          nextIndex = (nextIndex + 1) % images.length;
          
          // Fade transition
          mainContent.style.transition = 'background-image 2s ease-in-out';
          mainContent.style.backgroundImage = `url(${images[currentIndex]})`;
        }
        
        // Change background every 5 seconds
        setInterval(changeBackground, 5000);
      });
    </script>
  </body>
  </html>