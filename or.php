
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
$sql = "SELECT id, customer_name, email, total_price, order_date, payment_method,status FROM orders ORDER BY order_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - View Orders</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    :root {
      --primary-color: #5e4b8b;
      --secondary-color: #d4c8e8;
      --accent-color: #ff7e9d;
      --dark-color: #2c3e50;
      --light-gray: #f8f9fa;
      --medium-gray: #e9ecef;
      --white: #ffffff;
    }
    
    body {
      background-color: #f5f7fa;
      font-family: 'Segoe UI', 'Helvetica Neue', sans-serif;
    }
    
    /* Sidebar Navigation */
    .sidebar {
      background: var(--dark-color);
      color: white;
      height: 100vh;
      position: fixed;
      width: 250px;
      transition: all 0.3s;
      z-index: 1000;
    }
    
    .sidebar-header {
      padding: 20px;
      background: rgba(0, 0, 0, 0.2);
    }
    
    .sidebar-menu {
      padding: 0;
      list-style: none;
    }
    
    .sidebar-menu li a {
      color: white;
      padding: 12px 20px;
      display: block;
      text-decoration: none;
      transition: all 0.3s;
      border-left: 3px solid transparent;
    }
    
    .sidebar-menu li a:hover, 
    .sidebar-menu li a.active {
      background: rgba(255, 255, 255, 0.1);
      border-left: 3px solid var(--accent-color);
    }
    
    .sidebar-menu li a i {
      margin-right: 10px;
      width: 20px;
      text-align: center;
    }
    
    .main-content {
      margin-left: 250px;
      padding: 30px;
      transition: all 0.3s;
    }
    
    /* Card styling */
    .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
      margin-bottom: 30px;
    }
    
    .card-header {
      background: white;
      border-bottom: 1px solid rgba(0, 0, 0, 0.05);
      font-weight: 600;
      padding: 20px;
      border-radius: 10px 10px 0 0 !important;
    }
    
    /* Table styling */
    .table-responsive {
      border-radius: 10px;
      overflow: hidden;
    }
    
    .table {
      margin-bottom: 0;
    }
    
    .table thead th {
      background: var(--primary-color);
      color: white;
      border: none;
      padding: 15px;
      font-weight: 500;
    }
    
    .table tbody tr {
      transition: all 0.2s;
    }
    
    .table tbody tr:hover {
      background-color: rgba(94, 75, 139, 0.05);
    }
    
    .table td, .table th {
      vertical-align: middle;
      padding: 12px 15px;
      border-top: 1px solid rgba(0, 0, 0, 0.03);
    }
    
    /* Status badges */
    .badge-status {
      padding: 6px 10px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 500;
      text-transform: capitalize;
    }
    
    .status-processing {
      background-color: #fff3cd;
      color: #856404;
    }
    
    .status-completed {
      background-color: #d4edda;
      color: #155724;
    }
    
    .status-cancelled {
      background-color: #f8d7da;
      color: #721c24;
    }
    
    /* Buttons */
    .btn-action {
      width: 32px;
      height: 32px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      margin: 0 3px;
      transition: all 0.2s;
    }
    
    .btn-action:hover {
      transform: translateY(-2px);
    }
    
    /* Notes tooltip */
    .notes-cell {
      max-width: 200px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      cursor: pointer;
    }
    
    /* Header */
    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }
    
    .page-title {
      color: var(--dark-color);
      font-weight: 600;
      margin: 0;
    }
    
    .order-count {
      background: var(--primary-color);
      color: white;
      padding: 8px 15px;
      border-radius: 20px;
      font-size: 14px;
    }
    
    /* Search and filter */
    .search-filter {
      background: white;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 30px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }
  </style>
</head>
<body>
  <!-- Sidebar Navigation -->
  <div class="sidebar">
    <div class="sidebar-header">
      <h4>Velvet Vogue Admin</h4>
    </div>
    <ul class="sidebar-menu">
      <li><a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
      <li><a href="addproduct.php"><i class="fas fa-plus-circle"></i> Add Products</a></li>
      <li><a href="vieworders.php" class="active"><i class="fas fa-shopping-cart"></i> View Orders</a></li>
      <li><a href="manageusers.php"><i class="fas fa-users"></i> Manage Users</a></li>
      <li><a href="feedback2.php"><i class="fas fa-comment"></i> Feedback</a></li>
      <li><a href="viewinqueries.php"><i class="fas fa-question-circle"></i> Inquiries</a></li>
      <li><a href="payment.php"><i class="fas fa-credit-card"></i> Payments</a></li>
      <li><a href="logout2.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="page-header">
      <h1 class="page-title"><i class="fas fa-receipt me-2"></i>Order Management</h1>
      <span class="order-count"><i class="fas fa-shopping-bag me-1"></i> <?= $result->num_rows ?> Orders</span>
    </div>

    <!-- Search and Filter Section -->
    <div class="search-filter">
      <div class="row">
        <div class="col-md-6">
          <div class="input-group">
            <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
            <input type="text" class="form-control" placeholder="Search orders...">
          </div>
        </div>
        <div class="col-md-3">
          <select class="form-select">
            <option selected>Filter by status</option>
            <option>Processing</option>
            <option>Completed</option>
            <option>Cancelled</option>
          </select>
        </div>
        <div class="col-md-3">
          <input type="date" class="form-control">
        </div>
      </div>
    </div>

    <!-- Orders Table -->
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Recent Orders</h5>
        <button class="btn btn-sm btn-outline-primary"><i class="fas fa-download me-1"></i> Export</button>
      </div>
      <div class="card-body p-0">
        <?php if ($result->num_rows > 0): ?>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Order ID</th>
                  <th>Customer</th>
                  <th>Date</th>
                  <th>Payment</th>
                  <th>Amount</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                  <tr>
                    <td>#<?= $row['id'] ?></td>
                    <td>
                      <div class="fw-bold"><?= htmlspecialchars($row['customer_name']) ?></div>
                      <div class="text-muted small"><?= htmlspecialchars($row['email']) ?></div>
                    </td>
                    <td><?= date('M d, Y', strtotime($row['order_date'])) ?></td>
                    <td><?= htmlspecialchars($row['payment_method']) ?></td>
                    <td class="fw-bold">Rs. <?= number_format($row['total'], 2) ?></td>
                    <td>
                      <span class="badge-status status-<?= strtolower($row['status']) ?>">
                        <?= htmlspecialchars($row['status']) ?>
                      </span>
                    </td>
                    <td>
                      <button class="btn-action btn btn-sm btn-outline-primary" title="View Details">
                        <i class="fas fa-eye"></i>
                      </button>
                      <button class="btn-action btn btn-sm btn-outline-success" title="Edit">
                        <i class="fas fa-edit"></i>
                      </button>
                      <button class="btn-action btn btn-sm btn-outline-danger" title="Delete">
                        <i class="fas fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div class="text-center py-5">
            <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No orders found</h5>
            <p class="text-muted">When you receive new orders, they'll appear here.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Tooltip initialization for notes
    document.addEventListener('DOMContentLoaded', function() {
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });
      
      // Make entire row clickable (except action buttons)
      document.querySelectorAll('tbody tr').forEach(row => {
        row.addEventListener('click', (e) => {
          // Don't navigate if clicked on an action button
          if (!e.target.closest('.btn-action')) {
            // In a real app, this would navigate to order details
            console.log('View order details for ID:', row.cells[0].textContent.replace('#',''));
          }
        });
      });
    });
  </script>
</body>
</html>