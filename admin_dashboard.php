<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard | Velvet Vogue</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary-color: #1a1a2e;
      --primary-light: #16213e;
      --secondary-color: #f8f5f2;
      --accent-color: #e94560;
      --gold-accent: #d4af37;
      --text-dark: #0f0f0f;
      --text-light: #5a5a5a;
      --success-color: #28a745;
      --warning-color: #ffc107;
      --info-color: #17a2b8;
      --luxury-font: 'Playfair Display', serif;
    }
    
    body {
      font-family: 'Roboto', sans-serif;
      background-color: var(--secondary-color);
      color: var(--text-dark);
      overflow-x: hidden;
    }
    
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;500&display=swap');
    
    .sidebar {
      height: 100vh;
      background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-light) 100%);
      color: white;
      padding-top: 1.5rem;
      position: fixed;
      width: 280px;
      z-index: 1000;
      border-right: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .sidebar-brand {
      text-align: center;
      margin-bottom: 2rem;
      padding: 0 1rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      padding-bottom: 1.5rem;
    }
    
    .sidebar-brand h4 {
      font-family: var(--luxury-font);
      font-weight: 700;
      letter-spacing: 2px;
      margin-bottom: 0.5rem;
      color: var(--gold-accent);
    }
    
    .sidebar-brand img {
      height: 50px;
      margin-bottom: 1rem;
    }
    
    .sidebar-brand .tagline {
      font-size: 0.75rem;
      letter-spacing: 1px;
      color: rgba(255,255,255,0.7);
    }
    
    .nav-item {
      margin-bottom: 0.5rem;
      position: relative;
    }
    
    .nav-link {
      color: rgba(255,255,255,0.85);
      padding: 0.85rem 1.5rem;
      font-weight: 400;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      letter-spacing: 0.5px;
      border-left: 3px solid transparent;
    }
    
    .nav-link:hover, .nav-link.active {
      color: white;
      background-color: rgba(255,255,255,0.05);
      border-left: 3px solid var(--gold-accent);
    }
    
    .nav-link i {
      margin-right: 1rem;
      font-size: 1.1rem;
      width: 20px;
      text-align: center;
      color: var(--gold-accent);
    }
    
    .main-content {
      margin-left: 280px;
      width: calc(100% - 280px);
      padding: 2.5rem;
      background-color: var(--secondary-color);
    }
    
    .dashboard-header {
      background-color: white;
      padding: 1.75rem;
      border-radius: 8px;
      margin-bottom: 2.5rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 4px 20px rgba(0,0,0,0.03);
      border: 1px solid rgba(0,0,0,0.03);
    }
    
    .greeting h3 {
      font-family: var(--luxury-font);
      font-weight: 600;
      margin-bottom: 0.5rem;
      color: var(--primary-color);
    }
    
    .greeting p {
      color: var(--text-light);
      margin-bottom: 0;
      font-size: 0.9rem;
    }
    
    .user-profile {
      display: flex;
      align-items: center;
    }
    
    .user-profile img {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      margin-right: 1rem;
      object-fit: cover;
      border: 2px solid var(--gold-accent);
    }
    
    .user-info strong {
      display: block;
      font-weight: 500;
    }
    
    .user-info small {
      display: block;
      color: var(--text-light);
      font-size: 0.8rem;
    }
    
    .stats-card {
      background-color: white;
      border-radius: 8px;
      padding: 1.75rem;
      margin-bottom: 1.5rem;
      transition: all 0.3s ease;
      border: 1px solid rgba(0,0,0,0.03);
      box-shadow: 0 4px 20px rgba(0,0,0,0.03);
      position: relative;
      overflow: hidden;
    }
    
    .stats-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 4px;
      height: 100%;
      background-color: var(--gold-accent);
    }
    
    .stats-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }
    
    .stats-card .icon {
      width: 55px;
      height: 55px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 1.25rem;
      font-size: 1.5rem;
      background-color: rgba(212, 175, 55, 0.1);
      color: var(--gold-accent);
    }
    
    .stats-card h5 {
      font-size: 0.95rem;
      color: var(--text-light);
      margin-bottom: 0.75rem;
      font-weight: 500;
      letter-spacing: 0.5px;
    }
    
    .stats-card .count {
      font-size: 1.85rem;
      font-weight: 600;
      margin-bottom: 0;
      color: var(--primary-color);
    }
    
    .stats-card .change {
      font-size: 0.85rem;
      margin-top: 0.75rem;
      display: flex;
      align-items: center;
    }
    
    .change.positive {
      color: var(--success-color);
    }
    
    .change.negative {
      color: var(--accent-color);
    }
    
    .recent-orders {
      background-color: white;
      border-radius: 8px;
      padding: 1.75rem;
      box-shadow: 0 4px 20px rgba(0,0,0,0.03);
      border: 1px solid rgba(0,0,0,0.03);
    }
    
    .section-title {
      font-family: var(--luxury-font);
      font-weight: 600;
      margin-bottom: 1.75rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: var(--primary-color);
    }
    
    .section-title a {
      font-size: 0.9rem;
      color: var(--gold-accent);
      text-decoration: none;
      font-weight: 500;
      letter-spacing: 0.5px;
    }
    
    .table {
      color: var(--text-dark);
    }
    
    .table th {
      border-top: none;
      font-weight: 600;
      text-transform: uppercase;
      font-size: 0.75rem;
      letter-spacing: 0.5px;
      color: var(--text-light);
      border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .table td {
      vertical-align: middle;
      padding: 1rem;
      border-bottom: 1px solid rgba(0,0,0,0.03);
    }
    
    .badge {
      padding: 0.4em 0.8em;
      font-weight: 500;
      border-radius: 4px;
      font-size: 0.75rem;
      letter-spacing: 0.5px;
    }
    
    .badge-success {
      background-color: rgba(40, 167, 69, 0.1);
      color: var(--success-color);
    }
    
    .badge-warning {
      background-color: rgba(255, 193, 7, 0.1);
      color: var(--warning-color);
    }
    
    .badge-danger {
      background-color: rgba(233, 69, 96, 0.1);
      color: var(--accent-color);
    }
    
    .btn-outline-primary {
      color: var(--primary-color);
      border-color: var(--primary-color);
      font-size: 0.8rem;
      padding: 0.35rem 0.75rem;
      letter-spacing: 0.5px;
    }
    
    .btn-outline-primary:hover {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }
    
    @media (max-width: 992px) {
      .sidebar {
        width: 80px;
        overflow: hidden;
      }
      
      .sidebar-brand h4, .sidebar-brand .tagline, .nav-link span {
        display: none;
      }
      
      .nav-link {
        justify-content: center;
        padding: 0.85rem 0;
        border-left: none;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        margin: 0 auto 0.5rem;
      }
      
      .nav-link i {
        margin-right: 0;
        font-size: 1.25rem;
      }
      
      .main-content {
        margin-left: 80px;
        width: calc(100% - 80px);
      }
    }
    
    /* Animation */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .stats-card, .recent-orders {
      animation: fadeIn 0.6s ease forwards;
    }
    
    .stats-card:nth-child(1) { animation-delay: 0.1s; }
    .stats-card:nth-child(2) { animation-delay: 0.2s; }
    .stats-card:nth-child(3) { animation-delay: 0.3s; }
    .stats-card:nth-child(4) { animation-delay: 0.4s; }
    .recent-orders { animation-delay: 0.5s; }
  </style>
</head>
<body>

<div class="wrapper">
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-brand">
      <!-- <img src="07Oct24 Anis Free Upload .png" alt="Velvet Vogue Logo"> -->
      <h4>VELVET VOGUE</h4>
      <div class="tagline">Luxury Fashion Admin</div>
    </div>
    
    <nav class="nav flex-column mt-4">
      <li class="nav-item">
        <a class="nav-link active" href="#">
          <i class="fas fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="addproduct.php">
          <i class="fas fa-tshirt"></i>
          <span>Products</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="vieworders.php">
          <i class="fas fa-shopping-bag"></i>
          <span>Orders</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="manageusers.php">
          <i class="fas fa-users"></i>
          <span>Customers</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="feedback2.php">
          <i class="fas fa-star"></i>
          <span>Feedback</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="viewinqueries.php">
          <i class="fas fa-envelope"></i>
          <span>Inquiries</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="payment.php">
          <i class="fas fa-credit-card"></i>
          <span>Payments</span>
        </a>
      </li>
      <li class="nav-item mt-4">
        <a class="nav-link" href="logout2.php">
          <i class="fas fa-sign-out-alt"></i>
          <span>Logout</span>
        </a>
      </li>
    </nav>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
      <div class="greeting">
        <!-- <h3>Welcome </h3> -->
        <!-- <p>Your luxury fashion dashboard for <?= date('F j, Y') ?></p> -->
      </div>
      
      <div class="user-profile">
        <img src="download (1).jpg" alt="Admin Profile">
        <div class="user-info">
          <strong>Admin User</strong>
          <small>Head Administrator</small>
        </div>
      </div>
    </div>
   
    
  
    <!-- Modern Luxury Dashboard Design -->
<div class="luxury-dashboard">
  <!-- Header with Dark Theme -->
  <header class="luxury-header">
    <div class="header-content">
      <div class="brand-info">
        <h1 class="brand-name">VOGUE VAULT</h1>
        <p class="brand-subtitle">Admin Console</p>
      </div>
      
      <div class="header-actions">
        <div class="date-display">
          <i class="fas fa-calendar-alt"></i>
          <span><?= date('l, F j, Y') ?></span>
        </div>
        
      
      </div>
    </div>
  </header>

  <!-- Metric Cards - Horizontal Layout -->
  <section class="metrics-container">
    <div class="metric-card">
      <div class="metric-icon" style="background-color: #f8e8ee;">
        <i class="fas fa-tshirt" style="color: #d23c77;"></i>
      </div>
      <div class="metric-details">
        <h3>187</h3>
        <p>TOTAL PRODUCTS</p>
        <span class="metric-trend up">+15% <i class="fas fa-arrow-up"></i></span>
      </div>
    </div>
    
    <div class="metric-card">
      <div class="metric-icon" style="background-color: #e8f4f8;">
        <i class="fas fa-shopping-bag" style="color: #3c8dbc;"></i>
      </div>
      <div class="metric-details">
        <h3>64</h3>
        <p>TOTAL ORDERS</p>
        <span class="metric-trend up">+8% <i class="fas fa-arrow-up"></i></span>
      </div>
    </div>
    
    <div class="metric-card">
      <div class="metric-icon" style="background-color: #e8f8f0;">
        <i class="fas fa-users" style="color: #3cd278;"></i>
      </div>
      <div class="metric-details">
        <h3>42</h3>
        <p>PREMIUM CLIENTS</p>
        <span class="metric-trend up">+5 <i class="fas fa-user-plus"></i></span>
      </div>
    </div>
    
    <div class="metric-card">
      <div class="metric-icon" style="background-color: #f5e8f8;">
        <i class="fas fa-rupee-sign" style="color: #9c3cd2;"></i>
      </div>
      <div class="metric-details">
        <h3>Rs2000.00</h3>
        <p>TOTAL REVENUE</p>
        <span class="metric-trend up">+22% <i class="fas fa-arrow-up"></i></span>
      </div>
    </div>
  </section>

  <!-- Recent Orders with Card Layout -->
  <section class="orders-section">
    <div class="section-header">
      <h2>Recent Orders</h2>
      <a href="vieworders.php" class="view-all">
        View All <i class="fas fa-chevron-right"></i>
      </a>
    </div>
    
    <div class="orders-grid">
      <!-- Order Card 1 -->
      <div class="order-card">
        <div class="order-header">
          <span class="order-id">#VV-2105</span>
          <span class="order-status delivered">Delivered</span>
        </div>
        <div class="order-body">
          <h4>Priya Sharma</h4>
          <p class="order-date">May 21, 2023</p>
          <p class="order-amount">Rs12,500</p>
        </div>
        <div class="order-footer">
          <button class="action-button">View Details</button>
        </div>
      </div>
      
      <!-- Order Card 2 -->
      <div class="order-card">
        <div class="order-header">
          <span class="order-id">#VV-2104</span>
          <span class="order-status processing">Processing</span>
        </div>
        <div class="order-body">
          <h4>Rahul Kapoor</h4>
          <p class="order-date">May 20, 2023</p>
          <p class="order-amount">Rs8,750</p>
        </div>
        <div class="order-footer">
          <button class="action-button">View Details</button>
        </div>
      </div>
      
      <!-- Order Card 3 -->
      <div class="order-card">
        <div class="order-header">
          <span class="order-id">#VV-2103</span>
          <span class="order-status delivered">Delivered</span>
        </div>
        <div class="order-body">
          <h4>Neha Patel</h4>
          <p class="order-date">May 19, 2023</p>
          <p class="order-amount">Rs15,200</p>
        </div>
        <div class="order-footer">
          <button class="action-button">View Details</button>
        </div>
      </div>
      
      <!-- Order Card 4 -->
      <div class="order-card">
        <div class="order-header">
          <span class="order-id">#VV-2102</span>
          <span class="order-status shipped">Shipped</span>
        </div>
        <div class="order-body">
          <h4>Arjun Singh</h4>
          <p class="order-date">May 18, 2023</p>
          <p class="order-amount">Rs9,800</p>
        </div>
        <div class="order-footer">
          <button class="action-button">View Details</button>
        </div>
      </div>
      
      <!-- Order Card 5 -->
      <div class="order-card">
        <div class="order-header">
          <span class="order-id">#VV-2101</span>
          <span class="order-status returned">Returned</span>
        </div>
        <div class="order-body">
          <h4>Meera Desai</h4>
          <p class="order-date">May 17, 2023</p>
          <p class="order-amount">Rs11,300</p>
        </div>
        <div class="order-footer">
          <button class="action-button">View Details</button>
        </div>
      </div>
    </div>
  </section>
</div>

<style>
  /* Luxury Dashboard Styles */
  .luxury-dashboard {
    font-family: 'Montserrat', sans-serif;
    background-color: #f9f9f9;
    color: #333;
    padding: 20px;
  }
  
  .luxury-header {
    background-color: #1a1a2e;
    color: white;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 30px;
  }
  
  .header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  
  .brand-name {
    font-size: 24px;
    font-weight: 700;
    margin: 0;
    letter-spacing: 1px;
  }
  
  .brand-subtitle {
    font-size: 14px;
    opacity: 0.8;
    margin: 5px 0 0;
  }
  
  .header-actions {
    display: flex;
    align-items: center;
    gap: 30px;
  }
  
  .date-display {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
  }
  
  .user-profile {
    display: flex;
    align-items: center;
    gap: 15px;
  }
  
  .profile-info {
    display: flex;
    flex-direction: column;
    text-align: right;
  }
  
  .user-name {
    font-weight: 600;
  }
  
  .user-role {
    font-size: 12px;
    opacity: 0.7;
  }
  
  .profile-image {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(255,255,255,0.3);
  }
  
  /* Metrics Section */
  .metrics-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
  }
  
  .metric-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
  }
  
  .metric-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
  }
  
  .metric-details h3 {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
  }
  
  .metric-details p {
    margin: 5px 0;
    font-size: 13px;
    color: #666;
  }
  
  .metric-trend {
    font-size: 12px;
    font-weight: 600;
  }
  
  .metric-trend.up {
    color: #4CAF50;
  }
  
  /* Orders Section */
  .orders-section {
    background: white;
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
  }
  
  .section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
  }
  
  .section-header h2 {
    margin: 0;
    font-size: 20px;
  }
  
  .view-all {
    color: #666;
    text-decoration: none;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: color 0.3s;
  }
  
  .view-all:hover {
    color: #1a1a2e;
  }
  
  .orders-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
  }
  
  .order-card {
    border: 1px solid #eee;
    border-radius: 10px;
    padding: 15px;
    transition: transform 0.3s, box-shadow 0.3s;
  }
  
  .order-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
  }
  
  .order-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
  }
  
  .order-id {
    font-size: 13px;
    color: #666;
  }
  
  .order-status {
    font-size: 12px;
    padding: 3px 10px;
    border-radius: 20px;
    font-weight: 600;
  }
  
  .order-status.delivered {
    background-color: #e8f5e9;
    color: #4CAF50;
  }
  
  .order-status.processing {
    background-color: #fff8e1;
    color: #FFA000;
  }
  
  .order-status.shipped {
    background-color: #e3f2fd;
    color: #2196F3;
  }
  
  .order-status.returned {
    background-color: #ffebee;
    color: #F44336;
  }
  
  .order-body h4 {
    margin: 0 0 5px;
    font-size: 16px;
  }
  
  .order-date {
    font-size: 13px;
    color: #666;
    margin: 0 0 10px;
  }
  
  .order-amount {
    font-size: 18px;
    font-weight: 700;
    margin: 0;
  }
  
  .order-footer {
    margin-top: 15px;
  }
  
  .action-button {
    background: none;
    border: 1px solid #ddd;
    padding: 8px 15px;
    border-radius: 5px;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.3s;
  }
  
  .action-button:hover {
    background: #1a1a2e;
    color: white;
    border-color: #1a1a2e;
  }
</style>
 
     

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Database connection for velvet_db
  <?php
  $conn = new mysqli("localhost", "root", "", "velvet_db");
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
  ?>
  
  $(document).ready(function() {
    // Highlight active nav item
    $('.nav-link').click(function() {
      $('.nav-link').removeClass('active');
      $(this).addClass('active');
    });
    
    // Animate stats cards on hover
    $('.stats-card').hover(
      function() {
        $(this).css('transform', 'translateY(-8px)');
      },
      function() {
        $(this).css('transform', 'translateY(0)');
      }
    );
  });
</script>

</body>
</html>