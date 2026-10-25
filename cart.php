<?php
session_start();
$conn = new mysqli("localhost", "root", "", "velvet");

// Handle add to cart
if (isset($_GET['add'])) {
    $id = intval($_GET['add']);
    $result = $conn->query("SELECT * FROM products WHERE id = $id");
    $product = $result->fetch_assoc();

    if ($product) {
        if (!isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image_url'],
                'quantity' => 1
            ];
        } else {
            $_SESSION['cart'][$id]['quantity']++;
        }
    }
    header("Location: cart.php");
    exit();
}

// Handle remove from cart
if (isset($_GET['remove'])) {
    $id = intval($_GET['remove']);
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit();
}

// Handle quantity update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['quantities'] as $id => $qty) {
        $_SESSION['cart'][$id]['quantity'] = max(1, intval($qty));
    }
}

// Function to format price in Sri Lankan Rupees
function formatLKR($amount) {
    return 'Rs. ' . number_format($amount, 2);
}

// Function to generate star ratings
function generateStarRating($rating) {
    $stars = '';
    $fullStars = floor($rating);
    $halfStar = ($rating - $fullStars) >= 0.5;
    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
    
    for ($i = 0; $i < $fullStars; $i++) {
        $stars .= '<i class="fas fa-star"></i>';
    }
    
    if ($halfStar) {
        $stars .= '<i class="fas fa-star-half-alt"></i>';
    }
    
    for ($i = 0; $i < $emptyStars; $i++) {
        $stars .= '<i class="far fa-star"></i>';
    }
    
    return $stars;
}

// Fetch all products for display with average ratings
$products = $conn->query("
    SELECT p.*, AVG(r.rating) as avg_rating, COUNT(r.id) as review_count 
    FROM products p
    LEFT JOIN reviews r ON p.id = r.product_id
    GROUP BY p.id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart | Velvet Vogue</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #c8102e; /* Velvet red */
            --primary-light: #e63946;
            --gold-accent: #d4af37;
            --dark-bg: #1a1a1a;
            --light-bg: #f8f8f8;
            --text-color: #333;
            --light-text: #777;
            --border-color: #e0e0e0;
            --success: #28a745;
            --bg-color: #ffffff;
            --card-bg: #ffffff;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Header Styles */
        .announcement-bar {
            background-color: var(--dark-bg);
            color: white;
            padding: 8px 0;
            text-align: center;
            font-size: 14px;
        }
        
        .announcement-bar a {
            color: var(--gold-accent);
            text-decoration: none;
            font-weight: 500;
        }
        
        .header {
            background-color: var(--dark-bg);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        
        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            font-weight: 700;
            color: white;
            text-decoration: none;
        }
        
        .logo span {
            color: var(--gold-accent);
        }
        
        .nav-links {
            display: flex;
            gap: 25px;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .nav-links a:hover {
            color: var(--gold-accent);
        }
        
        .nav-links a i {
            font-size: 18px;
        }
        
        .cart-icon {
            position: relative;
            font-size: 20px;
            color: white;
        }
        
        .cart-count {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: var(--primary);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }
        
        h1, h2 {
            font-family: 'Playfair Display', serif;
            color: var(--dark-bg);
            margin-bottom: 25px;
            font-weight: 700;
        }
        
        h1 {
            font-size: 2.2rem;
            color: var(--primary);
        }
        
        h2 {
            font-size: 1.8rem;
            position: relative;
            padding-bottom: 10px;
        }
        
        h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--gold-accent);
        }
        
        .section {
            background-color: var(--bg-color);
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            padding: 30px;
            margin-bottom: 40px;
            border: 1px solid var(--border-color);
        }
        
        .empty-cart {
            text-align: center;
            padding: 60px 0;
        }
        
        .empty-cart i {
            font-size: 80px;
            color: var(--light-text);
            margin-bottom: 20px;
        }
        
        .empty-cart p {
            font-size: 18px;
            color: var(--light-text);
            margin-bottom: 30px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        th, td {
            padding: 18px 15px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        
        th {
            background-color: var(--light-bg);
            font-weight: 600;
            color: var(--dark-bg);
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 0.5px;
        }
        
        .product-cell {
            display: flex;
            align-items: center;
        }
        
        .product-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 20px;
            border: 1px solid var(--border-color);
        }
        
        .product-name {
            font-weight: 500;
            font-size: 16px;
        }
        
        .qty-input {
            width: 70px;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            text-align: center;
            background-color: var(--bg-color);
            font-size: 15px;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 25px;
            border-radius: 6px;
            font-weight: 500;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
            font-size: 15px;
            gap: 8px;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-light);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(200, 16, 46, 0.2);
        }
        
        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--primary);
            color: var(--primary);
        }
        
        .btn-outline:hover {
            background-color: var(--primary);
            color: white;
        }
        
        .btn-danger {
            background-color: transparent;
            color: var(--light-text);
            border: 1px solid var(--border-color);
        }
        
        .btn-danger:hover {
            background-color: #f8f8f8;
            color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-success {
            background-color: var(--success);
            color: white;
        }
        
        .btn-success:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }
        
        .btn-sm {
            padding: 8px 15px;
            font-size: 14px;
        }
        
        .cart-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
        }
        
        .cart-summary {
            text-align: right;
        }
        
        .total {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .total-amount {
            color: var(--primary);
            font-size: 28px;
        }
        
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }
        
        .product-card {
            background-color: var(--card-bg);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.03);
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }
        
        .product-card-img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-bottom: 1px solid var(--border-color);
            background-color: var(--light-bg);
        }
        
        .product-card-body {
            padding: 20px;
        }
        
        .product-card-title {
            font-weight: 600;
            margin-bottom: 12px;
            font-size: 16px;
        }
        
        .product-card-price {
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .product-card-actions {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }
        
        .price {
            font-weight: bold;
        }
        
        /* Search Filter Styles */
        .search-filter {
            background-color: var(--bg-color);
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.03);
            margin-bottom: 40px;
            border: 1px solid var(--border-color);
        }
        
        /* Rating Stars */
        .rating-stars {
            color: var(--gold-accent);
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .rating-stars .far,
        .rating-stars .fas {
            margin-right: 3px;
        }
        
        .rating-stars .fa-star-half-alt {
            color: var(--gold-accent);
        }
        
        .rating-stars .far.fa-star {
            color: #ddd;
        }
        
        .rating-text {
            font-size: 13px;
            color: var(--light-text);
            margin-left: 5px;
        }
        
        /* Footer Styles */
        footer {
            background-color: var(--dark-bg);
            color: white;
            padding: 50px 0 30px;
            margin-top: 60px;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .footer-column h3 {
            font-family: 'Playfair Display', serif;
            color: var(--gold-accent);
            margin-bottom: 20px;
            font-size: 18px;
        }
        
        .footer-links {
            list-style: none;
        }
        
        .footer-links li {
            margin-bottom: 12px;
        }
        
        .footer-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-links a:hover {
            color: var(--gold-accent);
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .social-links a {
            color: white;
            font-size: 20px;
            transition: color 0.3s;
        }
        
        .social-links a:hover {
            color: var(--gold-accent);
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255,255,255,0.1);
            font-size: 14px;
            color: rgba(255,255,255,0.7);
        }
        
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .nav-links {
                justify-content: center;
                flex-wrap: wrap;
            }
            
            .product-cell {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .product-img {
                margin-right: 0;
                margin-bottom: 15px;
            }
            
            .cart-actions {
                flex-direction: column;
                gap: 20px;
            }
            
            .cart-summary {
                text-align: center;
                width: 100%;
            }
            
            .product-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Announcement Bar -->
    <div class="announcement-bar">
        <div class="container">
            <i class="fas fa-truck"></i> Island Wide Delivery Within 3 Days* - <span style="color: var(--gold-accent)">Buy 1 & Get 1 Free</span> - <a href="#">SHOP NOW</a>
        </div>
    </div>
    
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a href="home.php" class="logo">VELVET <span>VOGUE</span></a>
                
                <nav class="nav-links d-none d-md-flex">
                    <a href="home.php"><i class="fas fa-home"></i> Home</a>
                    <a href="#products"><i class="fas fa-store"></i> Shop</a>
                    <a href="#"><i class="fas fa-star"></i> Collections</a>
                    <a href="#"><i class="fas fa-info-circle"></i> About</a>
                    <a href="#"><i class="fas fa-phone"></i> Contact</a>
                </nav>
                
                <div class="d-flex align-items-center gap-4">
                    <a href="#" class="text-white"><i class="fas fa-search"></i></a>
                    <a href="#" class="text-white"><i class="fas fa-user"></i></a>
                    <a href="cart.php" class="cart-icon">
                        <i class="fas fa-shopping-bag"></i>
                        <span class="cart-count"><?= !empty($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?></span>
                    </a>
                </div>
            </div>
        </div>
    </header>
    
    <div class="container">
        <div class="section">
            <h1><i class="fas fa-shopping-bag"></i> Your Shopping Cart</h1>
            
            <?php if (empty($_SESSION['cart'])): ?>
                <div class="empty-cart">
                    <i class="fas fa-bag-shopping"></i>
                    <p>Your cart is empty. Start shopping now!</p>
                    <a href="#products" class="btn btn-primary mt-3">
                        <i class="fas fa-store"></i> Browse Products
                    </a>
                </div>
            <?php else: ?>
            <form method="post" action="cart.php">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $grandTotal = 0;
                        foreach ($_SESSION['cart'] as $id => $item):
                            $total = $item['price'] * $item['quantity'];
                            $grandTotal += $total;
                        ?>
                        <tr>
                            <td>
                                <div class="product-cell">
                                    <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="product-img">
                                    <span class="product-name"><?= htmlspecialchars($item['name']) ?></span>
                                </div>
                            </td>
                            <td class="price"><?= formatLKR($item['price']) ?></td>
                            <td>
                                <input type="number" name="quantities[<?= $id ?>]" value="<?= $item['quantity'] ?>" min="1" class="qty-input">
                            </td>
                            <td class="price"><?= formatLKR($total) ?></td>
                            <td>
                                <a href="cart.php?remove=<?= $id ?>" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash-alt"></i> Remove
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="cart-actions">
                    <a href="products.php" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i> Continue Shopping
                    </a>
                    <div class="cart-summary">
                        <div class="total">
                            Grand Total: <span class="total-amount"><?= formatLKR($grandTotal) ?></span>
                        </div>
                        <button type="submit" class="btn btn-outline">
                            <i class="fas fa-sync-alt"></i> Update Cart
                        </button>
                        <a href="checkout.php" class="btn btn-success">
                            <i class="fas fa-credit-card"></i> Proceed to Checkout
                        </a>
                    </div>
                </div>
            </form>
            <?php endif; ?>
        </div>
        
        <div class="section" id="products">
            <h2><i class="fas fa-bolt"></i> Recommended Products</h2>
            
            <div class="product-grid">
                <?php while ($row = $products->fetch_assoc()): 
                    $avgRating = $row['avg_rating'] ? round($row['avg_rating'], 1) : 0;
                    $reviewCount = $row['review_count'] ? $row['review_count'] : 0;
                ?>
                <div class="product-card">
                    <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" class="product-card-img">
                    <div class="product-card-body">
                        <h3 class="product-card-title"><?= htmlspecialchars($row['name']) ?></h3>
                        
                        <!-- Star Rating Display -->
                        <div class="rating-stars">
                            <?= generateStarRating($avgRating) ?>
                            <span class="rating-text">
                                <?= $avgRating ?> (<?= $reviewCount ?> reviews)
                            </span>
                        </div>
                        
                        <div class="product-card-price price"><?= formatLKR($row['price']) ?></div>
                        
                        <div style="font-size: 0.85rem; color: var(--light-text); margin-bottom: 5px;">
                            <span><i class="fas fa-ruler-combined me-1"></i> Size: <?= htmlspecialchars($row['available_size']) ?></span>
                        </div>
                        
                        <div style="font-size: 0.85rem; color: var(--light-text); margin-bottom: 15px;">
                            <span><i class="fas fa-tag me-1"></i> <?= htmlspecialchars($row['category']) ?> • <?= htmlspecialchars($row['type']) ?></span>
                        </div>
                        
                        <div class="product-card-actions">
                            <a href="cart.php?add=<?= $row['id'] ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-cart-plus"></i> Add to Cart
                            </a>
                            <?php if (isset($_SESSION['cart'][$row['id']])): ?>
                                <a href="cart.php?remove=<?= $row['id'] ?>" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash-alt"></i> Remove
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>VELVET VOGUE</h3>
                    <p>Your premier destination for luxury fashion that blends timeless elegance with contemporary style.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-pinterest-p"></i></a>
                    </div>
                </div>
                
                <div class="footer-column">
                    <h3>Shop</h3>
                    <ul class="footer-links">
                        <li><a href="#">Women's Collection</a></li>
                        <li><a href="#">Men's Collection</a></li>
                        <li><a href="#">Accessories</a></li>
                        <li><a href="#">New Arrivals</a></li>
                        <li><a href="#">Sale Items</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Help</h3>
                    <ul class="footer-links">
                        <li><a href="#">Customer Service</a></li>
                        <li><a href="#">Track Order</a></li>
                        <li><a href="#">Returns & Exchanges</a></li>
                        <li><a href="#">Shipping Information</a></li>
                        <li><a href="#">FAQs</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Contact</h3>
                    <ul class="footer-links">
                        <li><i class="fas fa-map-marker-alt me-2"></i> 123 Fashion Ave, Colombo 03</li>
                        <li><i class="fas fa-phone me-2"></i> +94 112 345 678</li>
                        <li><i class="fas fa-envelope me-2"></i> info@velvetvogue.com</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> Velvet Vogue. All rights reserved.</p>
                <p class="mt-2">Proudly Sri Lankan - Supporting Local Businesses</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple cart count update
        document.querySelectorAll('a[href*="add="]').forEach(link => {
            link.addEventListener('click', function() {
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    let count = parseInt(cartCount.textContent) || 0;
                    cartCount.textContent = count + 1;
                    cartCount.style.display = 'flex';
                }
                
                // Show a quick notification
                const notification = document.createElement('div');
                notification.style.position = 'fixed';
                notification.style.bottom = '20px';
                notification.style.right = '20px';
                notification.style.backgroundColor = 'var(--primary)';
                notification.style.color = 'white';
                notification.style.padding = '12px 24px';
                notification.style.borderRadius = '6px';
                notification.style.boxShadow = '0 3px 15px rgba(0,0,0,0.1)';
                notification.style.zIndex = '1000';
                notification.textContent = 'Item added to cart!';
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.style.opacity = '0';
                    notification.style.transition = 'opacity 0.5s';
                    setTimeout(() => notification.remove(), 500);
                }, 2000);
            });
        });
    </script>
</body>
</html>