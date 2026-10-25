<?php
session_start();

// Check if user is logged in
$user = isset($_SESSION['user']) ? $_SESSION['user'] : [
    'name' => '',
    'email' => ''
];

// Sample cart items (in a real application, this would come from your database)
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$shipping = 500.00; // Sample shipping cost
$tax_rate = 0.12; // 12% tax
$tax = $subtotal * $tax_rate;
$total = $subtotal + $shipping + $tax;

// Initialize error variable
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    // Validate and process the order
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $zip = trim($_POST['zip']);
    $country = trim($_POST['country']);
    $notes = trim($_POST['notes']);
    $payment_method = $_POST['payment_method'];
    
    // Basic validation
    if (empty($name) || empty($email) || empty($phone) || empty($address) || 
        empty($city) || empty($zip) || empty($country) || empty($payment_method)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (!preg_match('/^\d{10}$/', $phone)) {
        $error = 'Please enter a valid 10-digit phone number.';
    } else {
        // Process the order (in a real application, you would save to database here)
        
        // Clear the cart
        unset($_SESSION['cart']);
        
        // Redirect to thank you page
        header('Location: order_confirmation.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Velvet Vogue</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            --border-color: #e0e0e0;
            --success-color: #28a745;
            --error-color: #dc3545;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            color: var(--text-dark);
            background-color: var(--secondary-color);
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
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
        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .nav-links {
            display: flex;
            gap: 20px;
        }
        
        .nav-link {
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
            padding: 8px 0;
            position: relative;
        }
        
        .nav-link:hover, .nav-link.active {
            color: var(--primary-color);
        }
        
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: var(--accent-color);
        }
        
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .nav-icon {
            color: var(--text-dark);
            font-size: 1.2rem;
            transition: color 0.3s;
        }
        
        .nav-icon:hover {
            color: var(--accent-color);
        }
        
        .cart-indicator {
            position: relative;
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
        
        /* Checkout Content */
        .checkout-header {
            text-align: center;
            margin: 40px 0;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .checkout-header h1 {
            font-family: 'Playfair Display', serif;
            color: var(--primary-color);
            margin-bottom: 10px;
            font-weight: 600;
            font-size: 2.5rem;
        }
        
        .checkout-header p {
            color: var(--text-light);
            font-size: 1.1rem;
        }
        
        .checkout-grid {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 40px;
        }
        
        .checkout-form, .order-summary {
            background: var(--secondary-color);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color);
        }
        
        .section-title {
            font-family: 'Playfair Display', serif;
            color: var(--primary-color);
            margin-bottom: 25px;
            font-size: 1.5rem;
            font-weight: 600;
            position: relative;
            padding-bottom: 10px;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 2px;
            background-color: var(--accent-color);
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-dark);
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 1rem;
            transition: all 0.3s;
            background-color: var(--light-gray);
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            outline: none;
            background-color: var(--secondary-color);
            box-shadow: 0 0 0 2px rgba(128, 0, 32, 0.1);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .payment-methods {
            margin-top: 40px;
        }
        
        .payment-option {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s;
            background-color: var(--light-gray);
        }
        
        .payment-option:hover {
            border-color: var(--primary-color);
            background-color: var(--secondary-color);
        }
        
        .payment-option input {
            margin-right: 15px;
            accent-color: var(--primary-color);
        }
        
        .payment-icon {
            margin-right: 15px;
            color: var(--primary-color);
            font-size: 1.2rem;
            min-width: 24px;
        }
        
        .order-summary {
            position: sticky;
            top: 20px;
        }
        
        .order-items {
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .order-item {
            display: flex;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--light-gray);
        }
        
        .order-item:last-child {
            border-bottom: none;
        }
        
        .order-item-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 15px;
            border: 1px solid var(--border-color);
        }
        
        .order-item-details {
            flex: 1;
        }
        
        .order-item-name {
            font-weight: 500;
            margin-bottom: 5px;
            color: var(--text-dark);
        }
        
        .order-item-meta {
            display: flex;
            gap: 15px;
            font-size: 0.9rem;
            color: var(--text-light);
        }
        
        .order-totals {
            margin-top: 25px;
        }
        
        .order-total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding: 8px 0;
        }
        
        .order-total-label {
            color: var(--text-light);
        }
        
        .order-total-value {
            font-weight: 500;
            color: var(--text-dark);
        }
        
        .order-grand-total {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
            padding-top: 15px;
            border-top: 2px solid var(--primary-color);
            margin-top: 15px;
        }
        
        .btn {
            display: inline-block;
            padding: 15px 25px;
            background-color: var(--primary-color);
            color: var(--secondary-color);
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s;
            width: 100%;
            font-family: 'Playfair Display', serif;
        }
        
        .btn:hover {
            background-color: var(--accent-color);
            color: var(--dark-color);
        }
        
        .btn i {
            margin-right: 10px;
        }
        
        .btn-back {
            background-color: var(--light-gray);
            color: var(--text-dark);
            margin-top: 20px;
        }
        
        .btn-back:hover {
            background-color: #e0e0e0;
        }
        
        .error-message {
            color: var(--error-color);
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 15px 20px;
            border-radius: 4px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
        }
        
        .error-message i {
            margin-right: 10px;
            color: var(--error-color);
        }
        
        @media (max-width: 992px) {
            .checkout-grid {
                grid-template-columns: 1fr;
            }
            
            .order-summary {
                position: static;
                margin-top: 40px;
            }
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .nav-container {
                flex-direction: column;
                gap: 15px;
            }
            
            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .checkout-header h1 {
                font-size: 2rem;
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
    
    <!-- Header Navigation -->
    <header class="header">
        <div class="container">
            <div class="nav-container">
                <a href="home.php" class="logo">VELVET <span>VOGUE</span></a>
                
                <nav class="nav-links">
                    <a href="home.php" class="nav-link">Home</a>
                    <a href="#" class="nav-link">Women</a>
                    <a href="#" class="nav-link">Men</a>
                    <a href="#" class="nav-link">Collections</a>
                    <a href="order_history.php" class="nav-link">Orders</a>
                </nav>
                
                <div class="nav-actions">
                    <a href="search.php" class="nav-icon">
                        <i class="fas fa-search"></i>
                    </a>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="profile.php" class="nav-icon">
                            <i class="fas fa-user"></i>
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="nav-icon">
                            <i class="fas fa-user"></i>
                        </a>
                    <?php endif; ?>
                    <a href="cart.php" class="nav-icon cart-indicator">
                        <i class="fas fa-shopping-bag"></i>
                        <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                            <span class="cart-count"><?= count($_SESSION['cart']) ?></span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Checkout Content -->
    <div class="container">
        <div class="checkout-header">
            <h1>Complete Your Order</h1>
            <p>Finalize your purchase with Velvet Vogue's premium collection</p>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <div class="checkout-grid">
            <div class="checkout-form">
                <h2 class="section-title">Shipping Information</h2>
                <form method="POST" id="checkoutForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" class="form-control" 
                                   value="<?php echo isset($user['name']) ? htmlspecialchars($user['name']) : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" class="form-control" 
                                   value="<?php echo isset($user['email']) ? htmlspecialchars($user['email']) : ''; ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Shipping Address</label>
                        <textarea id="address" name="address" class="form-control" rows="3" required></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" id="city" name="city" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="zip">Postal Code</label>
                            <input type="text" id="zip" name="zip" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" id="country" name="country" class="form-control" value="Sri Lanka" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="notes">Order Notes (Optional)</label>
                        <textarea id="notes" name="notes" class="form-control" rows="2" 
                                  placeholder="Special instructions for delivery"></textarea>
                    </div>
                    
                    <div class="payment-methods">
                        <h2 class="section-title">Payment Method</h2>
                        
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="Credit Card" required checked>
                            <i class="fas fa-credit-card payment-icon"></i>
                            <span>Credit Card</span>
                        </label>
                        
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="Cash on Delivery" required>
                            <i class="fas fa-money-bill-wave payment-icon"></i>
                            <span>Cash on Delivery</span>
                        </label>
                    </div>
                    
                    <button type="submit" name="place_order" class="btn">
                        <i class="fas fa-lock"></i> Complete Order
                    </button>
                </form>
            </div>
            
            <div class="order-summary">
                <h2 class="section-title">Order Summary</h2>
                
                <div class="order-items">
                    <?php if (empty($cart_items)): ?>
                        <p>Your cart is empty</p>
                    <?php else: ?>
                        <?php foreach ($cart_items as $item_id => $item): ?>
                        <div class="order-item">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" class="order-item-img" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <div class="order-item-details">
                                <div class="order-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                                <div class="order-item-meta">
                                    <span>Rs<?php echo number_format($item['price'], 2); ?></span>
                                    <span>× <?php echo $item['quantity']; ?></span>
                                    <span>Rs<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <div class="order-totals">
                    <div class="order-total-row">
                        <span class="order-total-label">Subtotal</span>
                        <span class="order-total-value">Rs<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    <div class="order-total-row">
                        <span class="order-total-label">Shipping</span>
                        <span class="order-total-value">Rs<?php echo number_format($shipping, 2); ?></span>
                    </div>
                    <div class="order-total-row">
                        <span class="order-total-label">Tax (<?php echo ($tax_rate * 100); ?>%)</span>
                        <span class="order-total-value">Rs<?php echo number_format($tax, 2); ?></span>
                    </div>
                    <div class="order-total-row order-grand-total">
                        <span>Total</span>
                        <span>Rs<?php echo number_format($total, 2); ?></span>
                    </div>
                </div>
                
                <a href="cart.php" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i> Back to Cart
                </a>
            </div>
        </div>
    </div>

    <script>
        // Client-side validation
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            const phone = document.getElementById('phone').value;
            if (!/^\d{10}$/.test(phone)) {
                alert('Please enter a valid 10-digit phone number');
                e.preventDefault();
            }
        });
    </script>
</body>
</html>