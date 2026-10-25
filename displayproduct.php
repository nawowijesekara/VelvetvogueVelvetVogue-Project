<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "velvet");

// Handle add to cart POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    
    // Fetch product details
    $product_query = "SELECT * FROM products WHERE id = $product_id";
    $product_result = mysqli_query($conn, $product_query);
    $product = mysqli_fetch_assoc($product_result);
    
    // Initialize cart if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Check if product already in cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += 1;
            $found = true;
            break;
        }
    }
    
    // If not found, add to cart
    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image_url'],
            'quantity' => 1
        ];
    }
    
    // Redirect to prevent form resubmission
    header("Location: display_product.php");
    exit();
}

// Search and filter logic
$searchTerm = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$type = $_GET['type'] ?? '';
$available_size = $_GET['available_size'] ?? '';
$minPrice = $_GET['min_price'] ?? '';
$maxPrice = $_GET['max_price'] ?? '';

$sql = "SELECT * FROM products WHERE 1=1";

if (!empty($searchTerm)) {
    $sql .= " AND name LIKE '%$searchTerm%'";
}
if (!empty($category)) {
    $sql .= " AND category = '$category'";
}
if (!empty($type)) {
    $sql .= " AND type = '$type'";
}
if (!empty($available_size) && $available_size != 'available_size') {
    $sql .= " AND FIND_IN_SET('$available_size', available_size)";
}
if (!empty($minPrice)) {
    $sql .= " AND price >= $minPrice";
}
if (!empty($maxPrice)) {
    $sql .= " AND price <= $maxPrice";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Products | Velvet Vogue</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-color: #800020; /* Deep red */
      --secondary-color: #FFFFFF; /* White */
      --accent-color: #D4AF37; /* Gold */
      --dark-color: #1a1a1a;
      --light-gray: #f5f5f5;
      --text-dark: #333333;
      --text-light: #777777;
    }
    
    body {
      font-family: 'Roboto', sans-serif;
      background-color: var(--secondary-color);
      color: var(--text-dark);
    }
    
    /* Header Styles */
    .announcement-bar {
      background-color: var(--primary-color);
      color: var(--secondary-color);
      padding: 8px 0;
      text-align: center;
      font-size: 14px;
      font-weight: 500;
    }
    
    .header {
      background-color: var(--secondary-color);
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      padding: 15px 0;
      position: sticky;
      top: 0;
      z-index: 1000;
      border-bottom: 1px solid var(--primary-color);
    }
    
    .logo {
      font-family: 'Playfair Display', serif;
      font-weight: 700;
      font-size: 28px;
      color: var(--primary-color);
    }
    
    .logo span {
      color: var(--accent-color);
    }
    
    /* Navigation */
    .nav-link {
      color: var(--dark-color);
      font-weight: 500;
      padding: 8px 15px;
      transition: all 0.3s;
    }
    
    .nav-link:hover, .nav-link.active {
      color: var(--primary-color);
    }
    
    .dropdown-menu {
      border: 1px solid var(--primary-color);
    }
    
    .dropdown-item:hover {
      background-color: var(--primary-color);
      color: var(--secondary-color);
    }
    
    /* Icons */
    .nav-icon {
      color: var(--dark-color);
      transition: color 0.3s;
    }
    
    .nav-icon:hover {
      color: var(--accent-color);
    }
    
    /* Product Card Styles */
    .product-card {
      border: none;
      border-radius: 8px;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      margin-bottom: 25px;
      background: var(--secondary-color);
      border: 1px solid rgba(0,0,0,0.1);
    }
    
    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
      border-color: var(--primary-color);
    }
    
    .product-img {
      height: 280px;
      object-fit: cover;
      width: 100%;
      border-bottom: 1px solid rgba(0,0,0,0.1);
    }
    
    .product-body {
      padding: 20px;
    }
    
    .product-title {
      font-size: 1.1rem;
      font-weight: 600;
      margin-bottom: 10px;
      color: var(--text-dark);
      font-family: 'Playfair Display', serif;
    }
    
    .product-price {
      font-size: 1.2rem;
      font-weight: 700;
      color: var(--primary-color);
      margin-bottom: 15px;
    }
    
    .product-meta {
      font-size: 0.85rem;
      color: var(--text-light);
      margin-bottom: 5px;
    }
    
    .add-to-cart-btn {
      background-color: var(--primary-color);
      color: var(--secondary-color);
      border: none;
      padding: 10px 15px;
      border-radius: 6px;
      font-weight: 600;
      width: 100%;
      transition: all 0.3s;
    }
    
    .add-to-cart-btn:hover {
      background-color: var(--accent-color);
      color: var(--dark-color);
    }
    
    .add-to-cart-btn i {
      margin-right: 8px;
      color: var(--accent-color);
    }
    
    /* Search Filter Styles */
    .search-filter {
      background-color: var(--secondary-color);
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      margin-bottom: 30px;
      border: 1px solid rgba(0,0,0,0.1);
    }
    
    .search-filter .btn-primary {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }
    
    .search-filter .btn-primary:hover {
      background-color: var(--accent-color);
      border-color: var(--accent-color);
      color: var(--dark-color);
    }
    
    /* Cart Indicator */
    .cart-indicator {
      position: relative;
      display: inline-block;
    }
    
    .cart-count {
      position: absolute;
      top: -8px;
      right: -8px;
      background-color: var(--accent-color);
      color: var(--dark-color);
      border-radius: 50%;
      width: 20px;
      height: 20px;
      font-size: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
    }
    
    /* Footer */
    .site-footer {
      background-color: var(--primary-color);
      color: var(--secondary-color);
      padding: 60px 0 20px;
    }
    
    .footer-title {
      font-family: 'Playfair Display', serif;
      font-weight: 700;
      margin-bottom: 20px;
      position: relative;
      display: inline-block;
    }
    
    .footer-title::after {
      content: '';
      position: absolute;
      bottom: -8px;
      left: 0;
      width: 50px;
      height: 2px;
      background-color: var(--accent-color);
    }
    
    .footer-link {
      color: var(--secondary-color);
      text-decoration: none;
      transition: color 0.3s;
      display: block;
      margin-bottom: 10px;
    }
    
    .footer-link:hover {
      color: var(--accent-color);
    }
    
    .social-icon {
      color: var(--secondary-color);
      font-size: 1.2rem;
      margin-right: 15px;
      transition: color 0.3s;
    }
    
    .social-icon:hover {
      color: var(--accent-color);
    }
    
    /* Responsive Adjustments */
    @media (max-width: 992px) {
      .product-img {
        height: 220px;
      }
    }
    
    @media (max-width: 768px) {
      .product-img {
        height: 180px;
      }
      
      .nav-item {
        margin-bottom: 5px;
      }
    }
    
    @media (max-width: 576px) {
      .product-img {
        height: 150px;
      }
    }
  </style>
</head>
<body>
  <!-- Announcement Bar -->
  <div class="announcement-bar">
    <div class="container">
      Island Wide Delivery Within 3 Days* - Buy 1 & Get 1 Free - <a href="#" style="color: var(--accent-color); text-decoration: underline;">SHOP NOW</a>
    </div>
  </div>
  
  <!-- Header -->
  <header class="header">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center">
        <!-- Logo -->
        <div class="d-flex align-items-center">
          <button class="btn btn-outline-secondary me-3 d-lg-none">
            <i class="fas fa-bars"></i>
          </button>
          <a href="home.php" class="text-decoration-none">
            <h2 class="logo m-0">VELVET <span>VOGUE</span></h2>
          </a>
        </div>
        
        <!-- Main Navigation -->
        <nav class="d-none d-lg-flex align-items-center">
          <ul class="nav me-auto">
            <li class="nav-item"><a href="home.php" class="nav-link">Home</a></li>
            
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                Women's
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Formal Wear</a></li>
                <li><a class="dropdown-item" href="#">Casual Wear</a></li>
                <li><a class="dropdown-item" href="#">Evening Gowns</a></li>
              </ul>
            </li>
            
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                Men's
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Suits</a></li>
                <li><a class="dropdown-item" href="#">Casual Wear</a></li>
                <li><a class="dropdown-item" href="#">Accessories</a></li>
              </ul>
            </li>
            
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                Collections
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">New Arrivals</a></li>
                <li><a class="dropdown-item" href="#">Best Sellers</a></li>
                <li><a class="dropdown-item" href="#">Limited Edition</a></li>
              </ul>
            </li>
            
            <li class="nav-item"><a href="order_history.php" class="nav-link">Order History</a></li>
          </ul>
          
          <!-- Search Bar -->
          <form class="d-flex me-3" method="GET" action="">
            <div class="input-group">
              <input type="text" name="search" class="form-control" placeholder="Search..." style="min-width: 200px;">
              <button class="btn btn-outline-secondary" type="submit">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </form>
        </nav>
        
        <!-- User Actions -->
        <div class="d-flex align-items-center">
          <?php if(isset($_SESSION['user_id'])): ?>
            <div class="dropdown me-3">
              <a href="#" class="nav-icon" data-bs-toggle="dropdown">
                <i class="fas fa-user-circle fs-4"></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><h6 class="dropdown-header">Hello, <?= $_SESSION['user_name'] ?></h6></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i> Profile</a></li>
                <li><a class="dropdown-item" href="order_history.php"><i class="fas fa-history me-2"></i> Orders</a></li>
                <li><a class="dropdown-item" href="wishlist.php"><i class="fas fa-heart me-2"></i> Wishlist</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
              </ul>
            </div>
          <?php else: ?>
            <a href="login.php" class="nav-icon me-3">
              <i class="fas fa-user-circle fs-4"></i>
            </a>
          <?php endif; ?>
          
          <a href="cart.php" class="cart-indicator position-relative nav-icon">
            <i class="fas fa-shopping-bag fs-4"></i>
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
      <!-- Search and Filter Section -->
      <div class="search-filter mb-5">
        <form method="GET" class="row g-3">
          <div class="col-md-4">
            <div class="input-group">
              <input type="text" name="search" class="form-control" placeholder="Search products..." value="<?= htmlspecialchars($searchTerm) ?>">
              <button class="btn btn-primary" type="submit">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
          
          <div class="col-md-2">
            <select name="category" class="form-select">
              <option value="">All Categories</option>
              <option value="Men" <?= $category == 'Men' ? 'selected' : '' ?>>Men</option>
              <option value="Women" <?= $category == 'Women' ? 'selected' : '' ?>>Women</option>
              <option value="Accessories" <?= $category == 'Accessories' ? 'selected' : '' ?>>Accessories</option>
            </select>
          </div>
          
          <div class="col-md-2">
            <select name="type" class="form-select">
              <option value="">All Types</option>
              <option value="Casual" <?= $type == 'Casual' ? 'selected' : '' ?>>Casual</option>
              <option value="Formal" <?= $type == 'Formal' ? 'selected' : '' ?>>Formal</option>
              <option value="Evening" <?= $type == 'Evening' ? 'selected' : '' ?>>Evening</option>
            </select>
          </div>
          
          <div class="col-md-2">
            <select name="available_size" class="form-select">
              <option value="">All Sizes</option>
              <option value="S" <?= $available_size == 'S' ? 'selected' : '' ?>>Small (S)</option>
              <option value="M" <?= $available_size == 'M' ? 'selected' : '' ?>>Medium (M)</option>
              <option value="L" <?= $available_size == 'L' ? 'selected' : '' ?>>Large (L)</option>
              <option value="XL" <?= $available_size == 'XL' ? 'selected' : '' ?>>XL</option>
            </select>
          </div>
          
          <div class="col-md-2">
            <div class="input-group">
              <input type="number" name="min_price" class="form-control" placeholder="Min $" value="<?= htmlspecialchars($minPrice) ?>">
              <input type="number" name="max_price" class="form-control" placeholder="Max $" value="<?= htmlspecialchars($maxPrice) ?>">
            </div>
          </div>
        </form>
      </div>
      
      <!-- Products Grid -->
      <div class="row">
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-md-4 col-lg-3 mb-4">
              <div class="product-card h-100">
                <img src="<?= htmlspecialchars($row['image_url']) ?>" class="product-img" alt="<?= htmlspecialchars($row['name']) ?>">
                
                <div class="product-body">
                  <h5 class="product-title"><?= htmlspecialchars($row['name']) ?></h5>
                  <div class="product-price">Rs. <?= number_format($row['price'], 2) ?></div>
                  
                  <div class="product-meta">
                    <span><i class="fas fa-ruler-combined me-1"></i> Size: <?= htmlspecialchars($row['available_size']) ?></span>
                  </div>
                  
                  <div class="product-meta">
                    <span><i class="fas fa-palette me-1"></i> Color: <?= htmlspecialchars($row['available_color']) ?></span>
                  </div>
                  
                  <div class="product-meta mb-3">
                    <span><i class="fas fa-tag me-1"></i> <?= htmlspecialchars($row['category']) ?> • <?= htmlspecialchars($row['type']) ?></span>
                  </div>
                  
                  <form method="post">
                    <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                    <button type="submit" name="add_to_cart" class="add-to-cart-btn">
                      <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                  </form>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <div class="col-12">
            <div class="alert alert-info text-center">
              <i class="fas fa-info-circle me-2"></i> No products found matching your criteria.
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </main>
  
  <!-- Footer -->
  <footer class="site-footer">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 mb-4">
          <h3 class="footer-title">VELVET <span>VOGUE</span></h3>
          <p class="mt-3">Your premier destination for fashion that blends timeless elegance with contemporary style in our signature red and gold palette.</p>
          
          <div class="mt-4">
            <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-pinterest"></i></a>
          </div>
        </div>
        
        <div class="col-lg-2 col-md-4 mb-4">
          <h5 class="footer-title">Shop</h5>
          <a href="#" class="footer-link">Women's</a>
          <a href="#" class="footer-link">Men's</a>
          <a href="#" class="footer-link">Accessories</a>
          <a href="#" class="footer-link">New Arrivals</a>
          <a href="#" class="footer-link">Collections</a>
        </div>
        
        <div class="col-lg-2 col-md-4 mb-4">
          <h5 class="footer-title">Help</h5>
          <a href="#" class="footer-link">Customer Service</a>
          <a href="#" class="footer-link">Track Order</a>
          <a href="#" class="footer-link">Returns & Exchanges</a>
          <a href="#" class="footer-link">Shipping Info</a>
          <a href="#" class="footer-link">Size Guide</a>
        </div>
        
        <div class="col-lg-4 col-md-4 mb-4">
          <h5 class="footer-title">Contact Us</h5>
          <p><i class="fas fa-map-marker-alt me-2"></i> 123 Fashion Avenue, Colombo, Sri Lanka</p>
          <p><i class="fas fa-phone me-2"></i> +94 11 234 5678</p>
          <p><i class="fas fa-envelope me-2"></i> support@velvetvogue.com</p>
          
          <div class="mt-4">
            <h6 class="footer-title">Newsletter</h6>
            <div class="input-group mb-3">
              <input type="email" class="form-control" placeholder="Your email">
              <button class="btn btn-primary" type="button">Subscribe</button>
            </div>
          </div>
        </div>
      </div>
      
      <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">
      
      <div class="row">
        <div class="col-md-6 text-center text-md-start">
          <p>&copy; <?= date('Y') ?> Velvet Vogue. All rights reserved.</p>
        </div>
        <div class="col-md-6 text-center text-md-end">
          <a href="#" class="footer-link me-3">Privacy Policy</a>
          <a href="#" class="footer-link me-3">Terms of Service</a>
          <a href="#" class="footer-link">Accessibility</a>
        </div>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Cart notification
    document.querySelectorAll('[name="add_to_cart"]').forEach(button => {
      button.addEventListener('click', function() {
        // Update cart count
        const cartCount = document.querySelector('.cart-count');
        if (cartCount) {
          let count = parseInt(cartCount.textContent) || 0;
          cartCount.textContent = count + 1;
          cartCount.style.display = 'flex';
        } else {
          const cartIndicator = document.querySelector('.cart-indicator');
          if (cartIndicator) {
            const newCount = document.createElement('span');
            newCount.className = 'cart-count';
            newCount.textContent = '1';
            cartIndicator.appendChild(newCount);
          }
        }
        
        // Show notification toast
        const toast = document.createElement('div');
        toast.style.position = 'fixed';
        toast.style.bottom = '20px';
        toast.style.right = '20px';
        toast.style.backgroundColor = 'var(--primary-color)';
        toast.style.color = 'var(--secondary-color)';
        toast.style.padding = '15px 25px';
        toast.style.borderRadius = '5px';
        toast.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
        toast.style.zIndex = '1000';
        toast.style.display = 'flex';
        toast.style.alignItems = 'center';
        toast.innerHTML = `
          <i class="fas fa-check-circle me-2" style="color: var(--accent-color);"></i>
          Item added to cart!
        `;
        document.body.appendChild(toast);
        
        // Animate toast
        setTimeout(() => {
          toast.style.opacity = '0';
          toast.style.transition = 'opacity 0.5s ease';
          setTimeout(() => toast.remove(), 500);
        }, 2000);
      });
    });
  </script>
</body>
</html>