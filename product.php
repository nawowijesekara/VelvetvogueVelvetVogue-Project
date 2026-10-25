<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Products | Velvet Vogue</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-color: #1a1a1a; /* Rich black */
      --secondary-color: #3d3d3d;
      --accent-color: #d4af8e; /* Luxury gold */
      --light-bg: #f8f5f2; /* Soft ivory */
      --dark-text: #2a2a2a;
      --light-text: #777;
      --border-color: #e0d9d1;
    }
    
    body {
      font-family: 'Montserrat', sans-serif;
      background-color: var(--light-bg);
      color: var(--dark-text);
      line-height: 1.7;
    }
    
    h1, h2, h3, h4, h5, h6 {
      font-family: 'Cormorant Garamond', serif;
      font-weight: 600;
      letter-spacing: 0.5px;
    }
    
    /* Header Styles */
    .announcement-bar {
      background-color: var(--primary-color);
      color: white;
      padding: 10px 0;
      text-align: center;
      font-size: 13px;
      letter-spacing: 1px;
      text-transform: uppercase;
      font-weight: 500;
    }
    
    .header {
      background-color: white;
      box-shadow: 0 2px 20px rgba(0,0,0,0.05);
      padding: 15px 0;
      position: sticky;
      top: 0;
      z-index: 1000;
      border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .brand-logo {
      font-family: 'Cormorant Garamond', serif;
      font-weight: 700;
      letter-spacing: 2px;
      color: var(--primary-color);
      font-size: 1.8rem;
    }
    
    /* Product Card Styles */
    .product-card {
      border: none;
      border-radius: 0;
      overflow: hidden;
      transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
      margin-bottom: 30px;
      background: white;
      box-shadow: 0 5px 15px rgba(0,0,0,0.03);
      position: relative;
    }
    
    .product-card:before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background-color: var(--accent-color);
      transform: scaleX(0);
      transform-origin: left;
      transition: transform 0.4s ease;
    }
    
    .product-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 35px rgba(0,0,0,0.08);
    }
    
    .product-card:hover:before {
      transform: scaleX(1);
    }
    
    .product-img-container {
      position: relative;
      overflow: hidden;
    }
    
    .product-img {
      height: 320px;
      object-fit: cover;
      width: 100%;
      background-color: var(--light-bg);
      transition: transform 0.8s cubic-bezier(0.19, 1, 0.22, 1);
    }
    
    .product-card:hover .product-img {
      transform: scale(1.05);
    }
    
    .product-badge {
      position: absolute;
      top: 15px;
      right: 15px;
      background-color: var(--accent-color);
      color: var(--primary-color);
      padding: 6px 12px;
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 1px;
      text-transform: uppercase;
    }
    
    .product-body {
      padding: 25px;
      text-align: center;
    }
    
    .product-title {
      font-size: 1.2rem;
      font-weight: 500;
      margin-bottom: 10px;
      color: var(--dark-text);
      font-family: 'Cormorant Garamond', serif;
    }
    
    .product-price {
      font-size: 1.3rem;
      font-weight: 600;
      color: var(--primary-color);
      margin-bottom: 15px;
      font-family: 'Cormorant Garamond', serif;
    }
    
    .product-meta {
      font-size: 0.85rem;
      color: var(--light-text);
      margin-bottom: 5px;
      letter-spacing: 0.5px;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    
    .product-meta i {
      margin-right: 8px;
      color: var(--accent-color);
    }
    
    .add-to-cart-btn {
      background-color: var(--primary-color);
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: 0;
      font-weight: 500;
      width: 100%;
      transition: all 0.3s;
      letter-spacing: 1px;
      text-transform: uppercase;
      font-size: 0.85rem;
      margin-top: 15px;
      position: relative;
      overflow: hidden;
    }
    
    .add-to-cart-btn:after {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: 0.5s;
    }
    
    .add-to-cart-btn:hover {
      background-color: var(--secondary-color);
    }
    
    .add-to-cart-btn:hover:after {
      left: 100%;
    }
    
    .add-to-cart-btn i {
      margin-right: 10px;
    }
    
    /* Search Filter Styles */
    .search-filter {
      background-color: white;
      border-radius: 0;
      padding: 40px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.03);
      margin-bottom: 50px;
      border: none;
      position: relative;
      overflow: hidden;
    }
    
    .search-filter:before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 4px;
      background: linear-gradient(90deg, var(--accent-color), var(--primary-color));
    }
    
    .search-filter h2 {
      font-size: 2rem;
      margin-bottom: 25px;
      color: var(--primary-color);
      text-align: center;
      position: relative;
      padding-bottom: 15px;
    }
    
    .search-filter h2:after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 2px;
      background-color: var(--accent-color);
    }
    
    .form-control, .form-select {
      border: 1px solid var(--border-color);
      background-color: white;
      border-radius: 0;
      padding: 12px 15px;
      font-size: 0.9rem;
      height: auto;
    }
    
    .form-control:focus, .form-select:focus {
      border-color: var(--accent-color);
      box-shadow: 0 0 0 2px rgba(212, 175, 142, 0.2);
    }
    
    .btn-primary {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
      border-radius: 0;
      padding: 12px 25px;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-size: 0.85rem;
      transition: all 0.3s;
    }
    
    .btn-primary:hover {
      background-color: var(--secondary-color);
      border-color: var(--secondary-color);
      transform: translateY(-2px);
    }
    
    .btn-outline-secondary {
      border-color: var(--border-color);
      color: var(--dark-text);
      border-radius: 0;
    }
    
    /* Navigation */
    .nav-link {
      color: var(--dark-text);
      font-weight: 500;
      letter-spacing: 1px;
      padding: 10px 20px !important;
      position: relative;
      text-transform: uppercase;
      font-size: 0.85rem;
    }
    
    .nav-link:after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 20px;
      width: 0;
      height: 2px;
      background-color: var(--accent-color);
      transition: width 0.3s ease;
    }
    
    .nav-link:hover:after {
      width: calc(100% - 40px);
    }
    
    .nav-link:hover {
      color: var(--primary-color);
    }
    
    .dropdown-menu {
      border-radius: 0;
      border: 1px solid var(--border-color);
      box-shadow: 0 5px 20px rgba(0,0,0,0.05);
      min-width: 220px;
    }
    
    .dropdown-item {
      padding: 10px 20px;
      font-size: 0.85rem;
      transition: all 0.2s;
    }
    
    .dropdown-item:hover {
      background-color: var(--light-bg);
      color: var(--primary-color);
      padding-left: 25px;
    }
    
    /* Alert */
    .alert-info {
      background-color: white;
      border-left: 4px solid var(--accent-color);
      color: var(--dark-text);
      border-radius: 0;
      box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    }
    
    /* Footer */
    footer {
      background-color: var(--primary-color);
      color: #ddd;
      padding: 80px 0 40px;
      position: relative;
    }
    
    footer:before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 4px;
      background: linear-gradient(90deg, var(--accent-color), var(--primary-color));
    }
    
    footer a {
      color: #ccc;
      text-decoration: none;
      transition: all 0.3s;
      letter-spacing: 0.5px;
    }
    
    footer a:hover {
      color: var(--accent-color);
      padding-left: 5px;
    }
    
    footer h5 {
      color: white;
      font-size: 1.2rem;
      margin-bottom: 25px;
      letter-spacing: 1px;
      position: relative;
      padding-bottom: 15px;
      font-family: 'Cormorant Garamond', serif;
    }
    
    footer h5:after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 50px;
      height: 2px;
      background-color: var(--accent-color);
    }
    
    footer ul li {
      margin-bottom: 12px;
    }
    
    .footer-divider {
      border-top: 1px solid rgba(255,255,255,0.1);
      margin: 50px 0 30px;
    }
    
    .social-icons a {
      display: inline-block;
      width: 40px;
      height: 40px;
      line-height: 40px;
      text-align: center;
      background-color: rgba(255,255,255,0.08);
      color: white;
      border-radius: 50%;
      margin-right: 12px;
      transition: all 0.3s;
      font-size: 1rem;
    }
    
    .social-icons a:hover {
      background-color: var(--accent-color);
      color: var(--primary-color);
      transform: translateY(-5px);
    }
    
    /* Responsive Adjustments */
    @media (max-width: 992px) {
      .product-img {
        height: 280px;
      }
    }
    
    @media (max-width: 768px) {
      .product-img {
        height: 240px;
      }
      
      .header {
        padding: 12px 0;
      }
      
      .search-filter {
        padding: 25px;
      }
      
      .brand-logo {
        font-size: 1.5rem;
      }
    }
    
    @media (max-width: 576px) {
      .product-img {
        height: 200px;
      }
      
      .search-filter {
        padding: 20px 15px;
      }
    }
    
    /* Animation */
    @keyframes fadeInUp {
      from { 
        opacity: 0; 
        transform: translateY(30px);
      }
      to { 
        opacity: 1; 
        transform: translateY(0);
      }
    }
    
    .product-card {
      animation: fadeInUp 0.6s cubic-bezier(0.23, 1, 0.32, 1) forwards;
      opacity: 0;
    }
    
    .product-card:nth-child(1) { animation-delay: 0.1s; }
    .product-card:nth-child(2) { animation-delay: 0.2s; }
    .product-card:nth-child(3) { animation-delay: 0.3s; }
    .product-card:nth-child(4) { animation-delay: 0.4s; }
    .product-card:nth-child(5) { animation-delay: 0.5s; }
    .product-card:nth-child(6) { animation-delay: 0.6s; }
    .product-card:nth-child(7) { animation-delay: 0.7s; }
    .product-card:nth-child(8) { animation-delay: 0.8s; }
    
    /* Loading Animation */
    .loading-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: var(--light-bg);
      z-index: 9999;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
    }
    
    .loading-spinner {
      width: 50px;
      height: 50px;
      border: 4px solid rgba(212, 175, 142, 0.2);
      border-top: 4px solid var(--accent-color);
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin-bottom: 20px;
    }
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
</head>
<body>
  <!-- Loading Overlay (remove this in production) -->
  <div class="loading-overlay">
    <div class="loading-spinner"></div>
    <h4 class="mt-3" style="font-family: 'Cormorant Garamond', serif;">VELVET VOGUE</h4>
  </div>

  <!-- Announcement Bar -->
  <div class="announcement-bar">
    <div class="container">
      <span>Free Island Wide Delivery • Exclusive Members Discounts • <a href="#" style="color: var(--accent-color); text-decoration: underline;">Shop Now</a></span>
    </div>
  </div>
  
  <!-- Header -->
  <header class="header">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
          <button class="btn btn-outline-secondary me-3 d-lg-none border-0">
            <i class="fas fa-bars fs-5"></i>
          </button>
          <a href="home.php" class="text-decoration-none">
            <h4 class="m-0 brand-logo">VELVET VOGUE</h4>
          </a>
        </div>
        
        <nav class="d-none d-lg-flex">
          <ul class="nav">
            <li class="nav-item"><a href="home.php" class="nav-link">Home</a></li>
            
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                Women
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Evening Wear</a></li>
                <li><a class="dropdown-item" href="#">Day Dresses</a></li>
                <li><a class="dropdown-item" href="#">Luxury Knitwear</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">View All</a></li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                Men
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Tailoring</a></li>
                <li><a class="dropdown-item" href="#">Casual Luxury</a></li>
                <li><a class="dropdown-item" href="#">Accessories</a></li>
              </ul>
            </li>
            <li class="nav-item"><a href="#" class="nav-link">Collections</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Journal</a></li>
          </ul>
        </nav>
        
        <div class="d-flex align-items-center">
          <div class="dropdown me-3 d-none d-lg-block">
            <button class="btn btn-outline-secondary dropdown-toggle border-0" data-bs-toggle="dropdown">
              <i class="fas fa-globe"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="#">English</a></li>
              <li><a class="dropdown-item" href="#">Français</a></li>
            </ul>
          </div>
          
          <?php if(isset($_SESSION['user_id'])): ?>
            <div class="dropdown me-3">
              <button class="btn btn-outline-secondary dropdown-toggle border-0" data-bs-toggle="dropdown">
                <i class="fas fa-user"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user-circle me-2"></i> Profile</a></li>
                <li><a class="dropdown-item" href="wishlist.php"><i class="fas fa-heart me-2"></i> Wishlist</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
              </ul>
            </div>
          <?php else: ?>
            <a href="login.php" class="btn btn-outline-secondary me-2 border-0"><i class="fas fa-user me-1"></i> Login</a>
            <a href="registration.php" class="btn btn-primary me-3">Join Us</a>
          <?php endif; ?>
          
          <a href="cart.php" class="cart-indicator position-relative">
            <i class="fas fa-shopping-cart fs-5"></i>
            <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
              <span class="cart-count"><?= count($_SESSION['cart']) ?></span>
            <?php endif; ?>
          </a>
        </div>
      </div>
    </div>
  </header>
  
  <!-- Main Content -->
  <main class="py-5">
    <div class="container">
      <!-- Hero Banner -->
      <div class="hero-banner mb-5" style="background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('https://images.unsplash.com/photo-1483985988355-763728e1935b?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80'); height: 400px; background-size: cover; background-position: center; display: flex; align-items: center; justify-content: center; text-align: center; color: white;">
        <div>
          <h1 class="display-4 mb-3">Autumn Collection</h1>
          <p class="lead mb-4">Timeless elegance for the modern individual</p>
          <a href="#" class="btn btn-outline-light px-4 py-2" style="border-width: 2px;">Discover Now</a>
        </div>
      </div>
      
      <!-- Search and Filter Section -->
      <div class="search-filter mb-5">
        <h2>Discover Our Collection</h2>
        <form method="GET" class="row g-3">
          <div class="col-lg-5">
            <div class="input-group">
              <input type="text" name="search" class="form-control" placeholder="Search our collection..." value="<?= htmlspecialchars($searchTerm) ?>">
              <button class="btn btn-primary" type="submit">
                <i class="fas fa-search"></i> Search
              </button>
            </div>
          </div>
          
          <div class="col-lg-2">
            <select name="category" class="form-select">
              <option value="">All Categories</option>
              <option value="Women" <?= $category == 'Women' ? 'selected' : '' ?>>Women</option>
              <option value="Men" <?= $category == 'Men' ? 'selected' : '' ?>>Men</option>
              <option value