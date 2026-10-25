<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "velvet";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch orders with existing fields
$sql = "SELECT id, customer_name, email, payment_method, notes, subtotal, tax, shipping, total, order_date, status FROM orders ORDER BY order_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - View Orders | Velvet Vogue</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
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
      --danger-color: #dc3545;
      --info-color: #17a2b8;
      --luxury-font: 'Playfair Display', serif;
      --body-font: 'Roboto', sans-serif;
    }
    
    body {
      font-family: var(--body-font);
      background-color: var(--secondary-color);
      color: var(--text-dark);
      overflow-x: hidden;
    }
    
    /* Sidebar Styles */
    .sidebar {
      height: 100vh;
      background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-light) 100%);
      color: white;
      padding-top: 1.5rem;
      position: fixed;
      width: 280px;
      z-index: 1000;
      border-right: 1px solid rgba(255, 255, 255, 0.1);
      box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
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
      border-radius: 50%;
      border: 2px solid var(--gold-accent);
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
    
    /* Main Content Styles */
    .main-content {
      margin-left: 280px;
      width: calc(100% - 280px);
      padding: 2.5rem;
      background-color: var(--secondary-color);
      min-height: 100vh;
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
    
    /* Table Styles */
    .table-responsive {
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    
    .table {
      margin-bottom: 0;
    }
    
    .table thead {
      background-color: var(--primary-color);
      color: white;
    }
    
    .table th {
      font-weight: 500;
      letter-spacing: 0.5px;
      padding: 1rem;
    }
    
    .table td {
      padding: 0.75rem 1rem;
      vertical-align: middle;
    }
    
    .table-hover tbody tr:hover {
      background-color: rgba(0,0,0,0.02);
    }
    
    /* Status Badges */
    .status-processing {
      color: var(--warning-color);
      font-weight: bold;
    }
    
    .status-completed {
      color: var(--success-color);
      font-weight: bold;
    }
    
    .status-cancelled {
      color: var(--danger-color);
      font-weight: bold;
    }
    
    /* Action Buttons */
    .action-btn {
      padding: 0.35rem 0.5rem;
      margin: 0 2px;
      border-radius: 4px;
      transition: all 0.2s;
    }
    
    .action-btn:hover {
      transform: translateY(-1px);
    }
    
    /* Notes Column */
    .notes-column {
      max-width: 200px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    
    /* Page Header */
    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid rgba(0,0,0,0.1);
    }
    
    .page-header h2 {
      font-family: var(--luxury-font);
      color: var(--primary-color);
      margin: 0;
    }
    
    .page-header .badge {
      font-size: 0.9rem;
      padding: 0.5rem 0.75rem;
      background-color: var(--primary-color);
    }
    
    /* Responsive Adjustments */
    @media (max-width: 992px) {
      .sidebar {
        width: 240px;
      }
      
      .main-content {
        margin-left: 240px;
        width: calc(100% - 240px);
      }
    }
    
    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
      }
      
      .sidebar.active {
        transform: translateX(0);
      }
      
      .main-content {
        margin-left: 0;
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <!-- Sidebar Navigation -->
<div class="sidebar">
  <div class="sidebar-brand">
    <h4>VELVET VOGUE</h4>
    <div class="tagline">Luxury Fashion Admin</div>
  </div>
  
  <nav class="nav flex-column mt-4">
    <li class="nav-item">
      <a class="nav-link active" href="admin_dashboard.php">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
      </a>
    </li>admin_dashboard.php
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

<style>
  /* Navigation Styles */
  .sidebar {
    height: 100vh;
    background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%);
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
    font-family: 'Playfair Display', serif;
    font-weight: 700;
    letter-spacing: 2px;
    margin-bottom: 0.5rem;
    color: #d4af37;
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
    border-left: 3px solid #d4af37;
  }
  
  .nav-link i {
    margin-right: 1rem;
    font-size: 1.1rem;
    width: 20px;
    text-align: center;
    color: #d4af37;
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
  }
</style>

<script>
  // Navigation active state management
  $(document).ready(function() {
    // Highlight active nav item
    $('.nav-link').click(function() {
      $('.nav-link').removeClass('active');
      $(this).addClass('active');
    });
  });
</script>

  <!-- Main Content Area -->
  <div class="main-content">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
      <div class="greeting">
        <h3>Order Management</h3>
        <p>View and manage all customer orders</p>
      </div>
      
      <div class="user-profile">
        <div class="profile-info">
          <strong>Admin User</strong>
          <small>Head Administrator</small>
        </div>
        <img src="download (1).jpg" alt="Admin Profile" class="profile-image">
      </div>
    </div>
    
    <!-- Orders Section -->
    <div class="orders-section">
      <div class="page-header">
        <h2><i class="fas fa-receipt me-2"></i> Customer Orders</h2>
        <div>
          <span class="badge bg-primary">Total Orders: <?= $result->num_rows ?></span>
        </div>
      </div>

      <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>Actions</th>
                <th>ID</th>
                <th>Customer Name</th>
                <th>Email</th>
                <th>Payment Method</th>
                <th>Subtotal (Rs.)</th>
                <th>Tax (Rs.)</th>
                <th>Shipping (Rs.)</th>
                <th>Total (Rs.)</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Notes</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td>
                    <button class="btn btn-sm btn-primary action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-info action-btn" title="Copy"><i class="fas fa-copy"></i></button>
                    <button class="btn btn-sm btn-danger action-btn" title="Delete"><i class="fas fa-trash"></i></button>
                  </td>
                  <td><?= $row['id'] ?></td>
                  <td><?= htmlspecialchars($row['customer_name']) ?></td>
                  <td><?= htmlspecialchars($row['email']) ?></td>
                  <td><?= htmlspecialchars($row['payment_method']) ?></td>
                  <td><?= number_format($row['subtotal'], 2) ?></td>
                  <td><?= number_format($row['tax'], 2) ?></td>
                  <td><?= number_format($row['shipping'], 2) ?></td>
                  <td><?= number_format($row['total'], 2) ?></td>
                  <td><?= date('Y-m-d H:i:s', strtotime($row['order_date'])) ?></td>
                  <td>
                    <span class="status-<?= strtolower($row['status']) ?>">
                      <?= htmlspecialchars($row['status']) ?>
                    </span>
                  </td>
                  <td class="notes-column" title="<?= htmlspecialchars($row['notes'] ?? '') ?>">
                    <?= htmlspecialchars($row['notes'] ?? '') ?>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="alert alert-info">No orders found in the database.</div>
      <?php endif; ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Mobile sidebar toggle functionality can be added here
    document.addEventListener('DOMContentLoaded', function() {
      // You can add sidebar toggle functionality for mobile here
    });
  </script>
</body>
</html>