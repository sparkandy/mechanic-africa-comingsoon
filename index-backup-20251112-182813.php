<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mechanic Africa - Professional Auto Services</title>
    <link rel="stylesheet" href="styles.css?v=4">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <div class="container">
        <!-- Left Side - Image -->
        <div class="image-section">
            <img src="mechanic-africa.jpeg" alt="Mechanic Africa - Professional Auto Services" class="hero-image">
            <div class="image-overlay">
                <div class="overlay-content">
                    <h2>Expert Auto Care</h2>
                    <p>Trusted by thousands across Africa</p>
                    <a href="pricing.php" class="pricing-link">View Our Plans →</a>
                </div>
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="content-section" id="contact-form">
            <div class="form-container">
                <h2 class="form-title">Let's Get Started</h2>
                <p class="form-subtitle">Kindly fill in the information below</p>
                
                <form class="contact-form" id="contactForm">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" placeholder="Paul Iromtubor" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="Pauliromtubor@gmail.com" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="package">Select Service Package</label>
                        <select id="package" name="package" required>
                            <option value="">Choose your plan</option>
                            <option value="4-cylinders">4 Cylinders - 60k</option>
                            <option value="7-cylinders">7 Cylinders - 70k</option>
                            <option value="8-cylinders">8 Cylinders - 90k</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="car">Car Information</label>
                        <input type="text" id="car" name="car" placeholder="2024 Toyota Corolla" required>
                    </div>
                    
                    <!-- reCAPTCHA -->
                    <div class="form-group captcha-group">
                        <div class="g-recaptcha" data-sitekey="<?php 
                            require_once 'config.php'; 
                            echo RECAPTCHA_SITE_KEY; 
                        ?>"></div>
                        <div class="captcha-note">
                            <small>Please complete the CAPTCHA to verify you're not a robot</small>
                        </div>
                    </div>
                    
                    <button type="submit" class="submit-btn">Send</button>
                </form>
            </div>
        </div>
    </div>
    <script src="script.js?v=4"></script>
</body>
</html>