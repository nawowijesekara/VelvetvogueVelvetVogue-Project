<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "velvet");

// Handle add to cart POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    
    // Fetch product details (using prepared statement)
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product_result = $stmt->get_result();
    $product = $product_result->fetch_assoc();
    
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
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Search and filter logic with prepared statements
$searchTerm = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$type = $_GET['type'] ?? '';
$available_size = $_GET['available_size'] ?? '';
$minPrice = $_GET['min_price'] ?? '';
$maxPrice = $_GET['max_price'] ?? '';

$sql = "SELECT * FROM products WHERE 1=1";
$types = "";
$params = [];

if (!empty($searchTerm)) {
    $sql .= " AND name LIKE CONCAT('%', ?, '%')";
    $types .= "s";
    $params[] = $searchTerm;
}
if (!empty($category)) {
    $sql .= " AND category = ?";
    $types .= "s";
    $params[] = $category;
}
if (!empty($type)) {
    $sql .= " AND type = ?";
    $types .= "s";
    $params[] = $type;
}
if (!empty($available_size) && $available_size != 'available_size') {
    $sql .= " AND FIND_IN_SET(?, available_size)";
    $types .= "s";
    $params[] = $available_size;
}
if (!empty($minPrice)) {
    $sql .= " AND price >= ?";
    $types .= "d";
    $params[] = $minPrice;
}
if (!empty($maxPrice)) {
    $sql .= " AND price <= ?";
    $types .= "d";
    $params[] = $maxPrice;
}

$stmt = $conn->prepare($sql);
if (!empty($types)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Products | Velvet Vogue</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-red:rgb(85, 26, 26);
      --dark-red:rgb(94, 25, 25);
      --light-red: #ffebee;
      --pure-white: #ffffff;
      --light-gray: #f5f5f5;
      --dark-gray: #333333;
    }
    
    body {
      font-family: 'Montserrat', sans-serif;
      background-color: var(--pure-white);
      color: var(--dark-gray);
    }
    
    /* Header Styles */
    .announcement-bar {
      background-color: var(--primary-red);
      color: var(--pure-white);
      padding: 8px 0;
      text-align: center;
      font-size: 0.9rem;
      font-weight: 500;
    }
    
    .announcement-bar a {
      color: var(--pure-white);
      text-decoration: underline;
      font-weight: 600;
    }
    
    .header {
      background-color: var(--pure-white);
      border-bottom: 1px solid rgba(0,0,0,0.1);
      padding: 15px 0;
      position: sticky;
      top: 0;
      z-index: 1000;
    }
    
    .logo {
      color: var(--primary-red);
      font-weight: 700;
      font-size: 1.8rem;
      letter-spacing: 1px;
    }
    
    .nav-link {
      color: var(--dark-gray);
      font-weight: 500;
      padding: 8px 15px;
      transition: color 0.3s;
    }
    
    .nav-link:hover, .nav-link:focus {
      color: var(--primary-red);
    }
    
    .dropdown-menu {
      border: none;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .dropdown-item:active, .dropdown-item:hover {
      background-color: var(--light-red);
      color: var(--primary-red);
    }
    
    /* Button Styles */
    .btn-outline-red {
      border-color: var(--primary-red);
      color: var(--primary-red);
    }
    
    .btn-outline-red:hover {
      background-color: var(--primary-red);
      color: var(--pure-white);
    }
    
    .btn-red {
      background-color: var(--primary-red);
      color: var(--pure-white);
      font-weight: 600;
    }
    
    .btn-red:hover {
      background-color: var(--dark-red);
      color: var(--pure-white);
    }
    
    /* Product Card Styles */
    .product-card {
      border: none;
      border-radius: 8px;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      margin-bottom: 25px;
      background: var(--pure-white);
      box-shadow: 0 3px 10px rgba(0,0,0,0.05);
      position: relative;
    }
    
    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(209, 0, 0, 0.1);
    }
    
    .product-img-container {
      position: relative;
      overflow: hidden;
      background: var(--light-gray);
    }
    
    .product-img {
      height: 280px;
      width: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
    }
    
    .product-card:hover .product-img {
      transform: scale(1.03);
    }
    
    .product-badge {
      position: absolute;
      top: 10px;
      right: 10px;
      background-color: var(--primary-red);
      color: var(--pure-white);
      padding: 3px 8px;
      border-radius: 4px;
      font-size: 0.7rem;
      font-weight: 600;
    }
    
    .product-body {
      padding: 20px;
    }
    
    .product-title {
      font-size: 1rem;
      font-weight: 600;
      margin-bottom: 8px;
      color: var(--dark-gray);
      height: 40px;
      overflow: hidden;
    }
    
    .product-price {
      font-size: 1.1rem;
      font-weight: 700;
      color: var(--primary-red);
      margin-bottom: 12px;
    }
    
    .product-old-price {
      text-decoration: line-through;
      color: var(--dark-gray);
      opacity: 0.6;
      font-size: 0.9rem;
      margin-left: 8px;
    }
    
    .product-meta {
      font-size: 0.8rem;
      color: #666;
      margin-bottom: 4px;
    }
    
    .add-to-cart-btn {
      background-color: var(--primary-red);
      color: var(--pure-white);
      border: none;
      padding: 10px 15px;
      border-radius: 6px;
      font-weight: 600;
      width: 100%;
      transition: background-color 0.3s;
      margin-top: 10px;
    }
    
    .add-to-cart-btn:hover {
      background-color: var(--dark-red);
    }
    
    .add-to-cart-btn i {
      margin-right: 8px;
    }
    
    /* Search Filter Styles */
    .search-filter {
      background-color: var(--pure-white);
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 2px 15px rgba(0,0,0,0.05);
      margin-bottom: 30px;
      border: 1px solid rgba(0,0,0,0.05);
    }
    
    .filter-title {
      color: var(--primary-red);
      font-weight: 600;
      margin-bottom: 15px;
      font-size: 1.1rem;
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
      background-color: var(--primary-red);
      color: var(--pure-white);
      border-radius: 50%;
      width: 20px;
      height: 20px;
      font-size: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
    }
    
    /* Footer Styles */
    .footer {
      background-color: var(--dark-gray);
      color: var(--pure-white);
      padding: 50px 0 20px;
    }
    
    .footer-heading {
      color: var(--pure-white);
      font-weight: 600;
      margin-bottom: 20px;
      font-size: 1.1rem;
    }
    
    .footer-link {
      color: #bbb;
      display: block;
      margin-bottom: 10px;
      transition: color 0.3s;
      text-decoration: none;
    }
    
    .footer-link:hover {
      color: var(--pure-white);
    }
    
    .social-icon {
      color: var(--pure-white);
      font-size: 1.2rem;
      margin-right: 15px;
      transition: color 0.3s;
    }
    
    .social-icon:hover {
      color: var(--primary-red);
    }
    
    .footer-bottom {
      border-top: 1px solid rgba(255,255,255,0.1);
      padding-top: 20px;
      margin-top: 30px;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
      .product-img {
        height: 200px;
      }
      
      .header {
        padding: 10px 0;
      }
      
      .logo {
        font-size: 1.5rem;
      }
    }
    
    /* Notification */
    .cart-notification {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color: var(--primary-red);
      color: var(--pure-white);
      padding: 12px 20px;
      border-radius: 6px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      z-index: 1050;
      display: flex;
      align-items: center;
      transform: translateY(100px);
      opacity: 0;
      transition: transform 0.3s ease, opacity 0.3s ease;
    }
    
    .cart-notification.show {
      transform: translateY(0);
      opacity: 1;
    }
    
    .cart-notification i {
      margin-right: 10px;
      font-size: 1.2rem;
    }
  </style>
</head>
<body>
  <!-- Announcement Bar -->
  <div class="announcement-bar">
    <div class="container">
      Island Wide Delivery Within 3 Days* - Buy 1 & Get 1 Free - <a href="#" class="text-white">SHOP NOW</a>
    </div>
  </div>
  
  <!-- Header -->
  <header class="header">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
          <button class="btn btn-outline-secondary me-3 d-lg-none">
            <i class="fas fa-bars"></i>
          </button>
          <a href="home.php" class="text-decoration-none">
            <span class="logo">VELVET VOGUE</span>
          </a>
        </div>
        
        <nav class="d-none d-lg-flex align-items-center">
          <ul class="nav me-4">
            <li class="nav-item"><a href="home.php" class="nav-link">Home</a></li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                Women's
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Formal Wear</a></li>
                <li><a class="dropdown-item" href="#">Casual Wear</a></li>
                <li><a class="dropdown-item" href="#">Dresses</a></li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                Men's
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Formal Wear</a></li>
                <li><a class="dropdown-item" href="#">Casual Wear</a></li>
                <li><a class="dropdown-item" href="#">Accessories</a></li>
              </ul>
            </li>
            <li class="nav-item"><a href="#" class="nav-link">Accessories</a></li>
            <li class="nav-item"><a href="order_history.php" class="nav-link">Order History</a></li>
          </ul>
        </nav>
        
        <div class="d-flex align-items-center">
          <div class="dropdown me-3">
            <button class="btn btn-outline-red dropdown-toggle" data-bs-toggle="dropdown">
              <i class="fas fa-globe"></i>
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">English</a></li>
              <li><a class="dropdown-item" href="#">French</a></li>
            </ul>
          </div>
          
          <?php if(isset($_SESSION['user_id'])): ?>
            <div class="dropdown me-3">
              <button class="btn btn-outline-red dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-user"></i>
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
              </ul>
            </div>
          <?php else: ?>
            <a href="login.php" class="btn btn-outline-red me-2">Login</a>
            <a href="registration.php" class="btn btn-red me-3">Sign Up</a>
          <?php endif; ?>
          
          <a href="cart.php" class="cart-indicator position-relative">
            <i class="fas fa-shopping-cart fs-5" style="color: var(--dark-gray);"></i>
            <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
              <span class="cart-count"><?= count($_SESSION['cart']) ?></span>
            <?php endif; ?>
          </a>
        </div>
      </div>
    </div>
  </header>
  
  <!-- Main Content -->
  <main class="py-4">
    <div class="container">
      <!-- Search and Filter Section -->
      <div class="search-filter mb-4">
        <h5 class="filter-title"><i class="fas fa-sliders-h me-2"></i>Filter Products</h5>
        <form method="GET" class="row g-3">
          <div class="col-md-4">
            <div class="input-group">
              <input type="text" name="search" class="form-control" placeholder="Search products..." value="<?= htmlspecialchars($searchTerm) ?>">
              <button class="btn btn-red" type="submit">
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
              <span class="input-group-text">Rs.</span>
              <input type="number" name="min_price" class="form-control" placeholder="Min" value="<?= htmlspecialchars($minPrice) ?>">
              <span class="input-group-text">-</span>
              <input type="number" name="max_price" class="form-control" placeholder="Max" value="<?= htmlspecialchars($maxPrice) ?>">
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
                <div class="product-img-container">
                  <img src="<?= htmlspecialchars($row['image_url']) ?>" class="product-img" alt="<?= htmlspecialchars($row['name']) ?>">
                  <?php if($row['discount_price'] > 0): ?>
                    <span class="product-badge">SALE</span>
                  <?php endif; ?>
                </div>
                
                <div class="product-body">
                  <h5 class="product-title"><?= htmlspecialchars($row['name']) ?></h5>
                  <div class="product-price">
                    Rs. <?= number_format($row['price'], 2) ?>
                    <?php if($row['original_price'] > $row['price']): ?>
                      <span class="product-old-price">Rs. <?= number_format($row['original_price'], 2) ?></span>
                    <?php endif; ?>
                  </div>
                  
                  <div class="product-meta">
                    <span><i class="fas fa-ruler-combined me-1"></i> Size: <?= htmlspecialchars($row['available_size']) ?></span>
                  </div>
                  
                  <div class="product-meta">
                    <span><i class="fas fa-palette me-1"></i> Color: <?= htmlspecialchars($row['available_color']) ?></span>
                  </div>
                  
                  <form method="post" class="mt-3">
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
            <div class="alert alert-info text-center py-4">
              <i class="fas fa-search fa-2x mb-3" style="color: var(--primary-red);"></i>
              <h4>No products found matching your criteria</h4>
              <p class="mb-0">Try adjusting your search or filter parameters</p>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </main>
  
  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 mb-4">
          <h5 class="footer-heading">About Velvet Vogue</h5>
          <p>Your premier destination for fashion that blends timeless elegance with contemporary style.</p>
          <div class="mt-3">
            <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-pinterest"></i></a>
          </div>
        </div>
        
        <div class="col-lg-2 col-md-4 mb-4">
          <h5 class="footer-heading">Shop</h5>
          <a href="#" class="footer-link">Women's</a>
          <a href="#" class="footer-link">Men's</a>
          <a href="#" class="footer-link">Accessories</a>
          <a href="#" class="footer-link">New Arrivals</a>
        </div>
        
        <div class="col-lg-2 col-md-4 mb-4">
          <h5 class="footer-heading">Customer Service</h5>
          <a href="#" class="footer-link">Contact Us</a>
          <a href="#" class="footer-link">FAQs</a>
          <a href="#" class="footer-link">Shipping Info</a>
          <a href="#" class="footer-link">Returns & Exchanges</a>
        </div>
        
        <div class="col-lg-2 col-md-4 mb-4">
          <h5 class="footer-heading">Company</h5>
          <a href="#" class="footer-link">About Us</a>
          <a href="#" class="footer-link">Careers</a>
          <a href="#" class="footer-link">Privacy Policy</a>
          <a href="#" class="footer-link">Terms of Service</a>
        </div>
        
        <div class="col-lg-2 mb-4">
          <h5 class="footer-heading">Contact</h5>
          <p><i class="fas fa-map-marker-alt me-2"></i> 123 Fashion St, Colombo</p>
          <p><i class="fas fa-phone me-2"></i> +94 76 123 4567</p>
          <p><i class="fas fa-envelope me-2"></i> hello@velvetvogue.lk</p>
        </div>
      </div>
      
      <div class="footer-bottom">
        <div class="row">
          <div class="col-md-6">
            <p>&copy; <?= date('Y') ?> Velvet Vogue. All rights reserved.</p>
          </div>
          <div class="col-md-6 text-md-end">
            <img src="https://via.placeholder.com/40x25" alt="Payment Methods" class="ms-2" style="filter: brightness(0) invert(1);">
            <img src="https://via.placeholder.com/40x25" alt="Payment Methods" class="ms-2" style="filter: brightness(0) invert(1);">
            <img src="https://via.placeholder.com/40x25" alt="Payment Methods" class="ms-2" style="filter: brightness(0) invert(1);">
          </div>
        </div>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Enhanced cart notification
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
        
        // Show notification
        const notification = document.createElement('div');
        notification.className = 'cart-notification';
        notification.innerHTML = `
          <i class="fas fa-check-circle"></i>
          <span>Item added to your cart</span>
        `;
        document.body.appendChild(notification);
        
        // Trigger animation
        setTimeout(() => {
          notification.classList.add('show');
        }, 10);
        
        // Remove notification after delay
        setTimeout(() => {
          notification.classList.remove('show');
          setTimeout(() => notification.remove(), 300);
        }, 3000);
      });
    });
  </script>
</body>
</html>