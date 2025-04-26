
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
      /* Reset and base styles */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Montserrat', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: linear-gradient(120deg, #f7f5f0 0%, #f0ebe3 100%);
  color: #2d1a23;
  line-height: 1.7;
  animation: fadeIn 1s ease;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

/* Header styles */
.header {
  background: rgba(50, 24, 35, 0.97);
  color: #fff;
  padding: 18px 5%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: 0 4px 16px rgba(50,24,35,0.18);
  border-bottom: 2px solid #e6c095;
}

.logo {
  font-size: 2.1rem;
  font-weight: 800;
  letter-spacing: 2px;
  color: #fff;
  text-decoration: none;
  display: flex;
  align-items: center;
  transition: color 0.2s;
}

.logo span {
  color: #e6c095;
  margin-left: 6px;
  text-shadow: 0 2px 8px #42273b30;
}

nav {
  display: flex;
  gap: 10px;
}

.nav-link {
  color: #fff;
  text-decoration: none;
  margin: 0 8px;
  font-weight: 600;
  font-size: 1.05rem;
  padding: 9px 18px;
  border-radius: 5px;
  transition: background 0.3s, color 0.3s, transform 0.2s;
  position: relative;
}

.nav-link:hover,
.nav-link.active {
  background: #e6c095;
  color: #42273b;
  transform: translateY(-3px) scale(1.04);
  box-shadow: 0 2px 12px #e6c09550;
}

.nav-link.login-signup {
  background: #e6c095;
  color: #42273b;
  font-weight: 700;
  border-radius: 30px;
  box-shadow: 0 2px 10px #e6c09530;
}

.nav-link.login-signup:hover {
  background: #f3d8b1;
  color: #42273b;
}

.welcome-message {
  color: #e6c095;
  font-weight: 600;
  margin-left: 10px;
}

/* Main content hero */
.main-content {
  text-align: center;
  padding: 100px 20px 80px 20px;
  min-height: 70vh;
  background-size: cover;
  background-position: center;
  color: #fff;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  position: relative;
  box-shadow: inset 0 0 180px #2d1a2380;
}

.background-overlay {
  position: absolute;
  top: 0; left: 0; width: 100%; height: 100%;
  background: linear-gradient(120deg, #42273bcc 0%, #2d1a2390 100%);
  z-index: 1;
}

.content-wrapper {
  position: relative;
  z-index: 2;
  max-width: 820px;
  margin: auto;
}

.main-content h2 {
  font-size: 3.2rem;
  font-weight: 900;
  letter-spacing: 1.5px;
  margin-bottom: 22px;
  text-shadow: 2px 4px 16px #2d1a2380;
}

.main-content p {
  font-size: 1.25rem;
  margin-bottom: 32px;
  text-shadow: 1px 2px 8px #2d1a2370;
}

.cta-button {
  background: linear-gradient(90deg, #e6c095 60%, #f3d8b1 100%);
  color: #42273b;
  border: none;
  padding: 15px 38px;
  font-size: 1.17rem;
  font-weight: bold;
  border-radius: 35px;
  cursor: pointer;
  text-decoration: none;
  box-shadow: 0 6px 24px #e6c09540;
  transition: background 0.3s, transform 0.2s, box-shadow 0.3s;
  letter-spacing: 1px;
}

.cta-button:hover {
  background: #f3d8b1;
  color: #42273b;
  transform: translateY(-4px) scale(1.03);
  box-shadow: 0 10px 36px #e6c09570;
}

/* Section titles */
.section-title {
  font-size: 2.7rem;
  color: #42273b;
  font-weight: 800;
  margin-bottom: 18px;
  display: inline-block;
  position: relative;
  letter-spacing: 1.2px;
}

.section-title::after {
  content: '';
  display: block;
  width: 90px;
  height: 4px;
  background: linear-gradient(90deg, #e6c095 60%, #f3d8b1 100%);
  margin: 12px auto 0;
  border-radius: 2px;
}

/* About section */
.about-section {
  padding: 90px 5% 70px 5%;
  background: #fff;
  text-align: center;
}

.about-content {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  align-items: center;
  max-width: 1200px;
  margin: 0 auto;
  gap: 40px;
}

.about-text {
  flex: 1.2;
  min-width: 320px;
  padding: 24px 20px 24px 0;
  text-align: left;
}

.about-text h3 {
  color: #e6c095;
  font-size: 2rem;
  margin-bottom: 14px;
  font-weight: 700;
}

.about-text p {
  margin-bottom: 16px;
  color: #4a3a3a;
  font-size: 1.12rem;
}

.about-image {
  flex: 1;
  min-width: 320px;
  padding: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.about-image img {
  width: 100%;
  max-width: 420px;
  border-radius: 16px;
  box-shadow: 0 10px 32px #2d1a2330;
  transition: transform 0.35s cubic-bezier(.19,1,.22,1), box-shadow 0.3s;
}

.about-image img:hover {
  transform: scale(1.04) rotate(-1deg);
  box-shadow: 0 16px 48px #e6c09560;
}

/* Features section */
.features {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  padding: 60px 5% 50px 5%;
  background: linear-gradient(120deg, #f0ebe3 0%, #f7f5f0 100%);
  gap: 30px;
}

.feature {
  flex: 1;
  min-width: 260px;
  max-width: 350px;
  margin: 12px;
  padding: 36px 22px;
  text-align: center;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 6px 24px #42273b15;
  transition: transform 0.25s, box-shadow 0.25s;
  border: 2px solid #f3d8b1;
}

.feature:hover {
  transform: translateY(-10px) scale(1.03);
  box-shadow: 0 16px 48px #e6c09540;
  border-color: #e6c095;
}

.feature-icon {
  font-size: 2.6rem;
  margin-bottom: 18px;
  color: #e6c095;
  filter: drop-shadow(0 2px 4px #e6c09550);
}

.feature h3 {
  color: #42273b;
  margin-bottom: 13px;
  font-size: 1.35rem;
  font-weight: 700;
}

/* Chef section */
.chef-section {
  padding: 90px 5% 70px 5%;
  background: #fff;
  text-align: center;
}

.chef-cards {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 38px;
  margin-top: 44px;
}

.chef-card {
  width: 300px;
  background: linear-gradient(120deg, #f7f5f0 80%, #f3d8b1 100%);
  border-radius: 14px;
  overflow: hidden;
  box-shadow: 0 6px 24px #42273b10;
  transition: transform 0.22s, box-shadow 0.22s;
  border: 2px solid #e6c09530;
}

.chef-card:hover {
  transform: translateY(-10px) scale(1.03);
  box-shadow: 0 16px 48px #e6c09540;
  border-color: #e6c095;
}

.chef-image {
  width: 100%;
  height: 210px;
  background: #f3d8b1;
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
  padding: 26px 20px 18px 20px;
}

.chef-details h3 {
  color: #42273b;
  margin-bottom: 7px;
  font-size: 1.2rem;
  font-weight: 700;
}

.chef-details p {
  color: #8c7b7b;
  font-style: italic;
  margin-bottom: 10px;
  font-size: 1.04rem;
}

/* Testimonials section */
.testimonials {
  padding: 90px 5% 80px 5%;
  background: linear-gradient(120deg, #42273b 70%, #331d2e 100%);
  text-align: center;
  color: #fff;
}

.testimonials .section-title {
  color: #fff;
}

.testimonial-slider {
  max-width: 800px;
  margin: 44px auto 0;
  position: relative;
}

.testimonial {
  background: rgba(255,255,255,0.07);
  padding: 36px 28px;
  border-radius: 12px;
  margin: 0 20px;
  box-shadow: 0 4px 16px #2d1a2360;
  border: 1.5px solid #e6c09520;
}

.testimonial p {
  font-style: italic;
  margin-bottom: 22px;
  line-height: 1.8;
  font-size: 1.13rem;
}

.testimonial-author {
  font-weight: bold;
  color: #e6c095;
  font-size: 1.08rem;
}

/* Footer styles */
.footer-nav {
  background: linear-gradient(120deg, #42273b 80%, #331d2e 100%);
  padding: 60px 5%;
  display: flex;
  flex-wrap: wrap;
  justify-content: space-around;
  align-items: flex-start;
  gap: 30px;
}

.column {
  margin: 15px;
  min-width: 170px;
}

.column h4 {
  margin-bottom: 22px;
  font-weight: bold;
  color: #fff;
  font-size: 1.17rem;
  position: relative;
  padding-bottom: 10px;
  letter-spacing: 1px;
}

.column h4::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 44px;
  height: 3px;
  background: #e6c095;
  border-radius: 2px;
}

.column ul {
  list-style: none;
}

.column li {
  margin-bottom: 14px;
  font-size: 1.04rem;
}

.column a {
  text-decoration: none;
  color: #dfdfdf;
  transition: color 0.3s, padding-left 0.3s;
}

.column a:hover {
  color: #e6c095;
  padding-left: 7px;
}

.opening-hours {
  color: #fff;
  margin-top: 18px;
  font-size: 1.04rem;
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

/* Social icons */
.social-icons {
  display: flex;
  gap: 18px;
  margin-top: 10px;
}

.social-icon {
  display: inline-flex;
  width: 38px;
  height: 38px;
  border-radius: 50%;
  background: rgba(255,255,255,0.10);
  align-items: center;
  justify-content: center;
  transition: background 0.3s, transform 0.2s;
  box-shadow: 0 2px 8px #e6c09530;
}

.social-icon:hover {
  background: #e6c095;
  transform: translateY(-3px) scale(1.08);
}

.social-icon img {
  width: 22px;
  height: 22px;
  object-fit: cover;
}

/* Newsletter subscribe */
.newsletter {
  margin-top: 22px;
}

.newsletter input {
  padding: 11px;
  width: 100%;
  border: none;
  border-radius: 6px;
  margin-bottom: 12px;
  font-size: 1.07rem;
  background: #fff;
  box-shadow: 0 2px 8px #e6c09520;
}

.newsletter button {
  background: #e6c095;
  color: #42273b;
  border: none;
  padding: 11px 18px;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
  font-size: 1.07rem;
  box-shadow: 0 2px 10px #e6c09530;
  transition: background 0.3s;
}

.newsletter button:hover {
  background: #f3d8b1;
}

.copyright {
  background: #331d2e;
  color: #fff;
  text-align: center;
  padding: 17px 0;
  font-size: 0.95rem;
  letter-spacing: 0.5px;
}

/* Responsive design */
@media (max-width: 1020px) {
  .about-content, .features, .chef-cards, .footer-nav {
    flex-direction: column;
    align-items: center;
    gap: 24px;
  }
  .about-image, .about-text {
    padding: 10px 0;
    width: 100%;
    max-width: 100%;
  }
  .chef-card {
    width: 90vw;
    max-width: 370px;
  }
}

@media (max-width: 768px) {
  .header {
    flex-direction: column;
    padding: 14px 2vw;
  }
  .logo {
    margin-bottom: 12px;
  }
  nav {
    width: 100%;
    justify-content: center;
    flex-wrap: wrap;
  }
  .nav-link {
    margin: 5px 0;
    font-size: 1rem;
    padding: 8px 13px;
  }
  .main-content {
    min-height: 50vh;
    padding: 60px 10px 40px 10px;
  }
  .main-content h2 {
    font-size: 2.2rem;
  }
  .about-section, .chef-section, .testimonials {
    padding: 55px 2vw 45px 2vw;
  }
  .features {
    padding: 25px 2vw 20px 2vw;
  }
  .footer-nav {
    padding: 30px 2vw;
  }
}

@media (max-width: 480px) {
  .main-content h2 {
    font-size: 1.4rem;
  }
  .main-content p {
    font-size: 0.98rem;
  }
  .cta-button {
    padding: 9px 13px;
    font-size: 0.98rem;
  }
  .section-title {
    font-size: 1.3rem;
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