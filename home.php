<?php
session_start(); // Start the session at the very beginning of the script

// Assuming 'config.php' is included elsewhere if needed for database operations,
// but for the home page display, session_start() is the primary requirement.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Velvet Vogue - Premium Fashion Destination</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <meta name="description" content="Shop stylish dresses and accessories from Velvet Vogue. Fashion for every season.">
    <style>
        :root {
            --velvet: #800020; /* Deep, rich red/purple, similar to original meron but conceptually changed */
            --white: #FFFFFF; /* Pure white */
            --light-velvet: #A04040; /* Lighter shade of velvet */
            --off-white: #F8F8F8; /* A subtle off-white for backgrounds */
            --gold: #D4AF37;
            --light-gray: #f8f9fa;
            --dark-gray: #212529;
            --gray-text: #6c757d;
            --black: #000000;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Playfair Display', serif;
            background-color: var(--white); /* Changed to white */
            color: var(--dark-gray);
            line-height: 1.6;
        }
        
        /* Header Styles */
        .announcement-bar {
            background-color: var(--velvet); /* Changed to velvet */
            color: var(--white); /* Changed to white */
            padding: 8px 0;
            text-align: center;
            font-size: 14px;
            font-weight: 500;
        }
        
        .announcement-bar a {
            color: var(--gold);
            text-decoration: underline;
            margin-left: 5px;
        }
        
        .header {
            background-color: var(--white); /* Changed to white */
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 15px 0;
            border-bottom: 1px solid var(--velvet); /* Changed to velvet */
        }
        
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .logo-section {
            display: flex;
            align-items: center;
            gap: 10px; /* Space between logo image and text */
        }

        .logo-img {
            height: 50px; /* Adjust as needed */
            border-radius: 5px; /* Optional: subtle rounding */
        }
        
        .logo {
            font-size: 28px;
            font-weight: 700;
            color: var(--velvet); /* Changed to velvet */
            text-decoration: none;
            font-family: 'Playfair Display', serif;
            letter-spacing: 1px;
        }
        
        .nav-links {
            display: flex;
            gap: 25px;
        }
        
        .nav-links a {
            color: var(--dark-gray);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
            position: relative;
            font-size: 16px;
        }
        
        .nav-links a:hover {
            color: var(--velvet); /* Changed to velvet */
        }
        
        .nav-links a.active::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: var(--velvet); /* Changed to velvet */
        }
        
        .dropdown {
            position: relative;
        }
        
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: var(--white); /* Changed to white */
            min-width: 200px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            z-index: 1;
            border-radius: 5px;
            padding: 10px 0;
            border: 1px solid var(--velvet); /* Changed to velvet */
        }
        
        .dropdown:hover .dropdown-content {
            display: block;
        }
        
        .dropdown-content a {
            display: block;
            padding: 8px 15px;
            color: var(--dark-gray);
        }
        
        .dropdown-content a:hover {
            background-color: var(--off-white); /* Changed to off-white */
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .header-right a {
            color: var(--dark-gray);
            text-decoration: none;
            font-weight: 500;
        }
        
        .btn {
            padding: 8px 16px;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none; /* Ensure buttons are not underlined */
        }
        
        .btn-primary {
            background-color: var(--velvet); /* Changed to velvet */
            color: var(--white); /* Changed to white */
            border: none;
        }
        
        .btn-primary:hover {
            background-color: var(--light-velvet); /* Changed to light-velvet */
        }

        .btn-outline-primary {
            background-color: transparent;
            color: var(--velvet); /* Changed to velvet */
            border: 1px solid var(--velvet); /* Changed to velvet */
        }

        .btn-outline-primary:hover {
            background-color: var(--off-white); /* Changed to off-white */
            color: var(--velvet); /* Changed to velvet */
        }
        
        .profile-section {
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
        }

        .profile-name {
            font-weight: 600;
            color: var(--dark-gray);
        }
        
        .profile-photo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--velvet); /* Changed to velvet */
            transition: transform 0.3s;
        }
        
        .profile-photo:hover {
            transform: scale(1.1);
        }
        
        .notification-dot {
            position: absolute;
            top: 0;
            right: 0;
            width: 10px;
            height: 10px;
            background-color: var(--gold);
            border-radius: 50%;
            border: 2px solid var(--white); /* Changed to white */
        }
        
        /* Hero Banner */
        .hero-banner {
            width: 100%;
            height: 80vh;
            max-height: 800px;
            position: relative;
            overflow: hidden;
            background: linear-gradient(rgba(128, 0, 32, 0.3), rgba(128, 0, 32, 0.3)), /* Remains for subtle overlay */
                        url('https://images.unsplash.com/photo-1515264350165-8b3877995669?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'); /* New image */
            background-size: cover;
            background-position: center;
        }
        
        .hero-content {
            position: absolute;
            top: 50%;
            left: 10%;
            transform: translateY(-50%);
            max-width: 500px;
            color: var(--white); /* Changed to white */
        }
        
        .hero-content h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            font-weight: 700;
        }
        
        .hero-content p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }
        
        .btn-hero {
            padding: 12px 30px;
            background-color: var(--white); /* Changed to white */
            color: var(--velvet); /* Changed to velvet */
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 30px;
            text-decoration: none;
            transition: all 0.3s;
            border: 2px solid var(--white); /* Changed to white */
        }
        
        .btn-hero:hover {
            background-color: transparent;
            color: var(--white); /* Changed to white */
        }
        
        /* Promo Slider */
        .promo-slider {
            width: 100%;
            height: 400px;
            margin: 40px 0;
            position: relative;
            background: var(--velvet); /* Changed to velvet */
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white); /* Changed to white */
            padding: 0 20px;
        }
        
        .promo-content {
            max-width: 1200px;
            text-align: center;
        }
        
        .promo-content h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        
        .promo-content p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Collections */
        .section-title {
            text-align: center;
            margin: 60px 0 40px;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            color: var(--velvet); /* Changed to velvet */
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
        }
        
        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background-color: var(--velvet); /* Changed to velvet */
        }
        
        .section-title p {
            font-size: 1.1rem;
            color: var(--gray-text);
            max-width: 700px;
            margin: 0 auto;
        }
        
        .collections {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin: 40px auto;
            max-width: 1200px;
            padding: 0 20px;
        }
        
        .collection-card {
            flex: 1;
            min-width: 300px;
            height: 400px;
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .collection-card:hover {
            transform: translateY(-10px);
        }
        
        .collection-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        
        .collection-card:hover img {
            transform: scale(1.05);
        }
        
        .collection-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(128, 0, 32, 0.8), transparent); /* Remains for subtle overlay */
            padding: 20px;
            color: var(--white); /* Changed to white */
        }
        
        .collection-overlay h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        
        /* Product Sliders */
        .product-slider {
            max-width: 1200px;
            margin: 60px auto;
            padding: 0 20px;
        }
        
        .swiper-slide {
            background: var(--white); /* Changed to white */
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
            border: 1px solid var(--off-white); /* Changed to off-white */
        }
        
        .swiper-slide:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        
        .product-img {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }
        
        .product-info {
            padding: 20px;
            background: var(--white); /* Changed to white */
        }
        
        .product-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--dark-gray);
        }
        
        .product-price {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--velvet); /* Changed to velvet */
            margin-bottom: 10px;
        }
        
        .product-sizes {
            font-size: 0.9rem;
            color: var(--gray-text);
        }
        
        /* Footer */
        .site-footer {
            background-color: var(--velvet); /* Changed to velvet */
            color: var(--white); /* Changed to white */
            padding: 60px 0 20px;
            margin-top: 60px;
        }
        
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            padding: 0 20px;
        }
        
        .footer-column h3 {
            font-size: 1.3rem;
            margin-bottom: 20px;
            color: var(--white); /* Changed to white */
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-column h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 2px;
            background-color: var(--gold);
        }
        
        .footer-column p, .footer-column a {
            color: var(--off-white); /* Changed to off-white */
            margin-bottom: 10px;
            display: block;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-column a:hover {
            color: var(--gold);
        }
        
        .social-icons {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }
        
        .social-icons a {
            font-size: 1.2rem;
            color: var(--white); /* Changed to white */
            transition: color 0.3s;
        }
        
        .social-icons a:hover {
            color: var(--gold);
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            margin-top: 30px;
            border-top: 1px solid var(--light-velvet); /* Changed to light-velvet */
            color: var(--off-white); /* Changed to off-white */
            font-size: 0.9rem;
        }
        
        /* Responsive Styles */
        @media (max-width: 1024px) {
            .hero-content h1 {
                font-size: 2.5rem;
            }
            
            .collections {
                flex-wrap: wrap;
            }
            
            .collection-card {
                min-width: calc(50% - 20px);
            }
        }
        
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                gap: 15px;
            }
            
            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .hero-content {
                left: 5%;
                max-width: 90%;
                text-align: center;
            }
            
            .hero-content h1 {
                font-size: 2rem;
            }
            
            .collection-card {
                min-width: 100%;
            }

            .header-right {
                flex-wrap: wrap;
                justify-content: center;
            }
        }
        
        @media (max-width: 480px) {
            .hero-content h1 {
                font-size: 1.8rem;
            }
            
            .hero-content p {
                font-size: 1rem;
            }
            
            .section-title h2 {
                font-size: 2rem;
            }
            
            .promo-content h2 {
                font-size: 1.8rem;
            }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="announcement-bar">
        Island Wide Delivery Within 3 Days* - Buy 1 & Get 1 Free - <a href="#">SHOP NOW</a>
    </div>
    
    <header class="header">
        <div class="header-container">
            <div class="logo-section">
                <img src="logo.png" alt="Logo" class="logo-img">
                <a href="home.php" class="logo">VELVET VOGUE</a>

            <nav class="nav-links">
                <a href="home.php" class="active">Home</a>
                
                <div class="dropdown">
                    <a href="#">Women's <i class="fas fa-chevron-down"></i></a>
                    <div class="dropdown-content">
                        <a href="index.php">Formal Wear</a>
                        <a href="index.php">Casual Wear</a>
                    </div>
                </div>
                
                <div class="dropdown">
                    <a href="#">Men's <i class="fas fa-chevron-down"></i></a>
                    <div class="dropdown-content">
                        <a href="index.php">Formal Wear</a>
                        <a href="index.php">Casual Wear</a>
                    </div>
                </div>
                
                <a href="promotion.php">Promotion</a>
                <a href="index.php">New Arrival</a>
            </nav>
            
            <div class="header-right">
                <a href="help.php"><i class="fas fa-question-circle"></i></a>
                <a href="feedback.php"><i class="fas fa-comment"></i></a>
                <a href="cart.php"><i class="fas fa-shopping-cart"></i></a>
                
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                    <div class="profile-section">
                        <span class="profile-name">Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
                        <a href="profile.php">
                            <img src="images (4).jpg" alt="Profile" class="profile-photo">
                            <span class="notification-dot"></span>
                        </a>
                        <button id="logoutBtn" class="btn btn-primary">Logout</button>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-primary">Login</a>
                    <a href="registration.php" class="btn btn-primary">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    
    <!-- <section class="hero-banner">
        <div class="hero-content">
            <h1>Elegance Redefined</h1>
            <p>Discover our premium velvet and white collection that blends timeless sophistication with modern style</p>
            <a href="Velvetvogue/Simple Modern Photo Collage Autumn Fashion Sale Banner.jpg" class="btn-hero">Shop The Collection</a>
        </div>
    </section> -->
    
    <div class="promo-slider">
        <div class="promo-content">
            <h2>Summer Sale Event</h2>
            <p>Enjoy up to 50% off on selected items from our velvet collection. Limited time offer - shop now before these elegant pieces are gone!</p>
            <a href="#" class="btn-hero" style="background-color: var(--gold); color: var(--velvet);">View Promotions</a>
        </div>
    </div>
    
    <div class="section-title">
        <h2>New Arrivals</h2>
        <p>Explore the latest trends in our velvet and white color palette. Timeless elegance for every occasion.</p>
    </div>
    
    <div class="collections">
        <div class="collection-card">
            <img src="download.jpg" alt="Women's Collection">
            <div class="collection-overlay">
                <h3>Velvet Elegance</h3>
                <a href="#" class="btn-hero">View Collection</a>
            </div>
        </div>
        
        <div class="collection-card">
            <img src="download (2).jpg" alt="Men's Wear">
            <div class="collection-overlay">
                <h3>White Classics</h3>
                <a href="#" class="btn-hero">Shop Now</a>
            </div>
        </div>
        
        <div class="collection-card">
            <img src="pp-watch.webp" alt="Accessories">
            <div class="collection-overlay">
                <h3>Luxury Accessories</h3>
                <a href="#" class="btn-hero">Discover</a>
            </div>
        </div>
    </div>
    
    <div class="section-title">
        <h2>Women's Collection</h2>
        <p>Sophisticated styles in rich velvet and crisp white tones for the modern woman</p>
    </div>
    
    <div class="product-slider swiper mySwiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <img src="download (3).jpg" class="product-img" alt="Dress 1">
                <div class="product-info">
                    <div class="product-title">Velvet Evening Gown</div>
                    <div class="product-price">Rs 8,590.00</div>
                    <div class="product-sizes">Sizes: S, M, L</div>
                </div>
            </div>
            
            <div class="swiper-slide">
                <img src="download (4).jpg" class="product-img" alt="Dress 2">
                <div class="product-info">
                    <div class="product-title">White Silk Blouse</div>
                    <div class="product-price">Rs 5,290.00</div>
                    <div class="product-sizes">Sizes: XS, S, M</div>
                </div>
            </div>
            
            <div class="swiper-slide">
                <img src="download (5).jpg" class="product-img" alt="Pants">
                <div class="product-info">
                    <div class="product-title">Velvet Tailored Pants</div>
                    <div class="product-price">Rs 6,890.00</div>
                    <div class="product-sizes">Sizes: 6, 8, 10</div>
                </div>
            </div>
            
            <div class="swiper-slide">
                <img src="download (6).jpg" class="product-img" alt="Dress 3">
                <div class="product-info">
                    <div class="product-title">Two-Tone Wrap Dress</div>
                    <div class="product-price">Rs 7,990.00</div>
                    <div class="product-sizes">Sizes: S, M, L, XL</div>
                </div>
            </div>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
    
    <div class="section-title">
        <h2>Men's Collection</h2>
        <p>Refined menswear in deep velvet and crisp neutral white tones</p>
    </div>
    
    <div class="product-slider swiper mySwiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <img src="tb470js_150_b.jpg" class="product-img" alt="Suit">
                <div class="product-info">
                    <div class="product-title">Velvet Wool Suit</div>
                    <div class="product-price">Rs 15,290.00</div>
                    <div class="product-sizes">Sizes: 38, 40, 42</div>
                </div>
            </div>
            
            <div class="swiper-slide">
                <img src="download (10).jpg" class="product-img" alt="Shirt">
                <div class="product-info">
                    <div class="product-title">White Linen Shirt</div>
                    <div class="product-price">Rs 4,190.00</div>
                    <div class="product-sizes">Sizes: S, M, L, XL</div>
                </div>
            </div>
            
            <div class="swiper-slide">
                <img src="download (11).jpg" class="product-img" alt="Jacket">
                <div class="product-info">
                    <div class="product-title">Velvet Leather Jacket</div>
                    <div class="product-price">Rs 12,100.00</div>
                    <div class="product-sizes">Sizes: M, L, XL</div>
                </div>
            </div>
            
            <div class="swiper-slide">
                <img src="download (12).jpg" class="product-img" alt="Pants">
                <div class="product-info">
                    <div class="product-title">White Chino Pants</div>
                    <div class="product-price">Rs 3,990.00</div>
                    <div class="product-sizes">Sizes: 30, 32, 34</div>
                </div>
            </div>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
    
    <div class="section-title">
        <h2>Best Sellers</h2>
        <p>Our most loved pieces in the velvet and white palette</p>
    </div>
    
    <div class="product-slider swiper mySwiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <img src="download (13).jpg" class="product-img" alt="Heels">
                <div class="product-info">
                    <div class="product-title">White Leather Heels</div>
                    <div class="product-price">Rs 6,590.00</div>
                    <div class="product-sizes">Sizes: 5, 6, 7, 8</div>
                </div>
            </div>
            
            <div class="swiper-slide">
                <img src="download (14).jpg" class="product-img" alt="Handbag">
                <div class="product-info">
                    <div class="product-title">Velvet Leather Handbag</div>
                    <div class="product-price">Rs 8,790.00</div>
                    <div class="product-sizes">One Size</div>
                </div>
            </div>
            
            <div class="swiper-slide">
                <img src="download (16).jpg" class="product-img" alt="Scarf">
                <div class="product-info">
                    <div class="product-title">Velvet & Cashmere Scarf</div>
                    <div class="product-price">Rs 3,990.00</div>
                    <div class="product-sizes">One Size</div>
                </div>
            </div>
            
            <div class="swiper-slide">
                <img src="download (15).jpg" class="product-img" alt="Watch">
                <div class="product-info">
                    <div class="product-title">Gold & White Watch</div>
                    <div class="product-price">Rs 9,490.00</div>
                    <div class="product-sizes">One Size</div>
                </div>
            </div>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
    
    <div class="collections">
        <div class="collection-card">
            <img src="images (1).jpg" alt="Women's Collection">
            <div class="collection-overlay">
                <h3>Women's Collection</h3>
                <a href="#" class="btn-hero">Shop Women</a>
            </div>
        </div>
        
        <div class="collection-card">
            <img src="images (2).jpg" alt="Men's Collection">
            <div class="collection-overlay">
                <h3>Men's Collection</h3>
                <a href="#" class="btn-hero">Shop Men</a>
            </div>
        </div>
    </div>
    
    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-column">
                <h3>About Velvet Vogue</h3>
                <p>Your premier destination for fashion that blends timeless elegance with contemporary style in our signature velvet and white palette.</p>
            </div>
            
            <div class="footer-column">
                <h3>Shop</h3>
                <a href="#">Women's</a>
                <a href="#">Men's</a>
                <a href="#">Accessories</a>
                <a href="#">New Arrivals</a>
            </div>
            
            <div class="footer-column">
                <h3>Help</h3>
                <a href="#">Customer Service</a>
                <a href="#">Track Order</a>
                <a href="#">Returns & Exchanges</a>
                <a href="#">Shipping Info</a>
            </div>
            
            <div class="footer-column">
                <h3>Contact Us</h3>
                <p><i class="fas fa-envelope"></i> support@velvetvogue.com</p>
                <p><i class="fas fa-phone"></i> +123 456 7890</p>
                
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-pinterest"></i></a>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; 2023 Velvet Vogue. All rights reserved.</p>
        </div>
    </footer>
    
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        // Initialize all sliders
        document.addEventListener('DOMContentLoaded', function() {
            const sliders = document.querySelectorAll('.mySwiper');
            
            sliders.forEach(function(slider) {
                new Swiper(slider, {
                    slidesPerView: 4,
                    spaceBetween: 30,
                    navigation: {
                        nextEl: slider.querySelector('.swiper-button-next'),
                        prevEl: slider.querySelector('.swiper-button-prev'),
                    },
                    breakpoints: {
                        1024: { slidesPerView: 4 },
                        768: { slidesPerView: 2 },
                        480: { slidesPerView: 1 }
                    }
                });
            });
            
            // Logout button functionality
            const logoutBtn = document.getElementById('logoutBtn');
            if (logoutBtn) { // Check if the logout button exists (it will only if logged in)
                logoutBtn.addEventListener('click', function() {
                    // Redirect to a logout handler. This handler (logout.php) would destroy the session.
                    window.location.href = 'logout.php';
                });
            }
        });
    </script>
    
</body>
</html>