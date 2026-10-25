<?php
session_start();
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "velvet";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle status update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Single status update
    if (isset($_POST['order_id']) && isset($_POST['current_status'])) {
        $order_id = intval($_POST['order_id']);
        $new_status = $_POST['current_status'] === 'processing' ? 'completed' : 'processing';

        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $order_id);
        $stmt->execute();
        $stmt->close();
    }
    
    // Bulk actions
    if (isset($_POST['bulk_action']) && !empty($_POST['selected_orders'])) {
        $selected_orders = $_POST['selected_orders'];
        $order_ids = implode(",", array_map('intval', $selected_orders));
        
        switch ($_POST['bulk_action']) {
            case 'delete':
                $sql = "DELETE FROM orders WHERE id IN ($order_ids)";
                $conn->query($sql);
                break;
            case 'update_status':
                if (isset($_POST['new_status'])) {
                    $new_status = $conn->real_escape_string($_POST['new_status']);
                    $sql = "UPDATE orders SET status = '$new_status' WHERE id IN ($order_ids)";
                    $conn->query($sql);
                }
                break;
            case 'export':
                header('Content-Type: text/csv');
                header('Content-Disposition: attachment; filename="orders_export.csv"');
                $output = fopen('php://output', 'w');
                fputcsv($output, array('ID', 'User ID', 'Customer', 'Email', 'Address', 'Payment Method', 'Notes', 'Subtotal', 'Tax', 'Shipping', 'Total', 'Order Date', 'Status'));
                $export_result = $conn->query("SELECT * FROM orders");
                while ($row = $export_result->fetch_assoc()) {
                    fputcsv($output, $row);
                }
                fclose($output);
                exit;
                break;
        }
    }
}

// Fetch orders with sorting and pagination
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'order_date';
$order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
$allowed_sorts = ['id', 'user_id', 'customer_name', 'email', 'payment_method', 'subtotal', 'total', 'order_date', 'status'];
$sort = in_array($sort, $allowed_sorts) ? $sort : 'order_date';
$order = $order === 'ASC' ? 'ASC' : 'DESC';

// Pagination
$per_page = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $per_page;

$total_query = $conn->query("SELECT COUNT(*) as total FROM orders");
$total_rows = $total_query->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $per_page);

$result = $conn->query("SELECT * FROM orders ORDER BY $sort $order LIMIT $per_page OFFSET $offset");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Management | Velvet Vogue</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary-color: #6c757d;
      --primary-light: #8e9aaf;
      --secondary-color: #f8f9fa;
      --accent-color: #adb5bd;
      --text-dark: #495057;
      --text-light: #6c757d;
      --success-color: #28a745;
      --warning-color: #ffc107;
      --info-color: #17a2b8;
      --light-gray: #e9ecef;
      --soft-white: #f8f9fa;
    }
    
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: var(--soft-white);
      color: var(--text-dark);
    }
    
    .navbar {
      background-color: var(--light-gray) !important;
      border-bottom: 1px solid #dee2e6;
    }
    
    .nav-menu li a {
      color: var(--text-dark) !important;
    }
    
    .nav-menu li a:hover {
      background-color: rgba(0, 0, 0, 0.05) !important;
    }
    
    .logout-btn {
      background-color: var(--light-gray);
      color: var(--text-dark);
      border: 1px solid #dee2e6;
    }
    
    .logout-btn:hover {
      background-color: rgba(0, 0, 0, 0.05);
    }
    
    .table-container {
      background-color: white;
      border-radius: 8px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.05);
      padding: 20px;
      margin-bottom: 20px;
      border: 1px solid #dee2e6;
    }
    
    .table thead th {
      background-color: var(--light-gray);
      color: var(--text-dark);
      border-bottom: 1px solid #dee2e6;
      font-weight: 600;
    }
    
    .table tbody tr {
      border-bottom: 1px solid #f1f1f1;
    }
    
    .table tbody tr:hover {
      background-color: rgba(0, 0, 0, 0.01);
    }
    
    .status-badge {
      padding: 5px 10px;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 600;
      display: inline-block;
    }
    
    .status-processing {
      background-color: rgba(255, 193, 7, 0.1);
      color: var(--warning-color);
      border: 1px solid rgba(255, 193, 7, 0.3);
    }
    
    .status-completed {
      background-color: rgba(40, 167, 69, 0.1);
      color: var(--success-color);
      border: 1px solid rgba(40, 167, 69, 0.3);
    }
    
    .bulk-actions {
      background-color: white;
      padding: 15px;
      border-radius: 8px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.05);
      margin-bottom: 20px;
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      align-items: center;
      border: 1px solid #dee2e6;
    }
    
    .empty-state {
      text-align: center;
      padding: 40px 20px;
      background-color: white;
      border-radius: 8px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.05);
      border: 1px solid #dee2e6;
    }
    
    .empty-state i {
      font-size: 3rem;
      color: var(--text-light);
      margin-bottom: 15px;
    }
    
    .pagination .page-item.active .page-link {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }
    
    .pagination .page-link {
      color: var(--primary-color);
      border: 1px solid #dee2e6;
    }
    
    .pagination .page-item:not(.active) .page-link:hover {
      background-color: var(--light-gray);
    }
    
    .action-btn {
      padding: 3px 6px;
      font-size: 0.8rem;
      margin: 0 2px;
      border: 1px solid #dee2e6;
    }
    
    .btn-outline-secondary {
      border-color: #dee2e6;
      color: var(--text-dark);
    }
    
    .btn-outline-secondary:hover {
      background-color: var(--light-gray);
    }
    
    .form-select, .form-control {
      border: 1px solid #dee2e6;
    }
    
    @media (max-width: 768px) {
      .table-responsive {
        overflow-x: auto;
        display: block;
        width: 100%;
      }
      
      .bulk-actions {
        flex-direction: column;
        align-items: flex-start;
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
      <a class="nav-link active" href="#">
         <a class="nav-link" href="admin_dashboard.php">
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
   <nav class="navbar navbar-expand-lg mb-4">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item"><a class="nav-link" href="admin_dashboard.php"><i class="fas fa-tachometer-alt me-1"></i>Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="addproduct.php"><i class="fas fa-plus-circle me-1"></i>Add Products</a></li>
          <li class="nav-item"><a class="nav-link active" href="vieworders.php"><i class="fas fa-shopping-cart me-1"></i>View Orders</a></li>
          <li class="nav-item"><a class="nav-link" href="manageusers.php"><i class="fas fa-users me-1"></i>Manage Users</a></li>
          <li class="nav-item"><a class="nav-link" href="feedback2.php"><i class="fas fa-comment me-1"></i>Feedback</a></li>
          <li class="nav-item"><a class="nav-link" href="viewinqueries.php"><i class="fas fa-question-circle me-1"></i>Inquiries</a></li>
          <li class="nav-item"><a class="nav-link" href="payment.php"><i class="fas fa-credit-card me-1"></i>Payment</a></li>
        </ul>
        <button class="btn btn-outline-secondary" onclick="window.location.href='logout2.php'"><i class="fas fa-sign-out-alt me-1"></i>Logout</button>
      </div>
    </div>
  </nav>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0 text-muted">
      <i class="fas fa-shopping-bag me-2"></i>Order Management
    </h2>
    <button onclick="history.back()" class="btn btn-outline-secondary">
      <i class="fas fa-arrow-left me-1"></i> Back
    </button>
  </div>

  <form method="post" id="bulkActionForm">
    <div class="bulk-actions">
      <div class="form-check me-3">
        <input class="form-check-input" type="checkbox" id="selectAll">
        <label class="form-check-label text-muted" for="selectAll">Check all</label>
      </div>
      
      <select name="bulk_action" class="form-select form-select-sm me-2" style="width: 150px;">
        <option value="">With selected:</option>
        <option value="update_status">Update Status</option>
        <option value="delete">Delete</option>
        <option value="export">Export</option>
      </select>
      
      <select name="new_status" class="form-select form-select-sm me-2" style="width: 150px;">
        <option value="processing">Processing</option>
        <option value="completed">Completed</option>
      </select>
      
      <button type="submit" class="btn btn-sm btn-outline-secondary">Apply</button>
    </div>

    <div class="table-container">
      <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th><input type="checkbox" id="selectAllRows"></th>
                <th>ID</th>
                <th>Customer</th>
                <th>Email</th>
                <th>Address</th>
                <th>Payment Method</th>
                <th>Subtotal</th>
                <th>Tax</th>
                <th>Shipping</th>
                <th>Total</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><input type="checkbox" name="selected_orders[]" value="<?= $row['id'] ?>"></td>
                  <td><?= htmlspecialchars($row['id']) ?></td>
                  <td><?= htmlspecialchars($row['customer_name']) ?></td>
                  <td><?= htmlspecialchars($row['email']) ?></td>
                  <td><?= htmlspecialchars($row['customer_address']) ?></td>
                  <td><?= htmlspecialchars($row['payment_method']) ?></td>
                  <td>Rs<?= number_format($row['subtotal'], 2) ?></td>
                  <td>Rs<?= number_format($row['tax'], 2) ?></td>
                  <td>Rs<?= number_format($row['shipping'], 2) ?></td>
                  <td>Rs<?= number_format($row['total'], 2) ?></td>
                  <td><?= htmlspecialchars($row['order_date']) ?></td>
                  <td>
                    <span class="status-badge status-<?= $row['status'] ?>">
                      <?= ucfirst($row['status']) ?>
                    </span>
                  </td>
                  <td>
                    <div class="d-flex">
                      <form method="post" class="d-inline">
                        <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="current_status" value="<?= $row['status'] ?>">
                        <button type="submit" class="btn btn-sm <?= $row['status'] === 'processing' ? 'btn-outline-success' : 'btn-outline-warning' ?> action-btn">
                          <i class="fas fa-exchange-alt"></i>
                        </button>
                      </form>
                      <a href="#" class="btn btn-sm btn-outline-primary action-btn" title="Edit">
                        <i class="fas fa-edit"></i>
                      </a>
                      <a href="#" class="btn btn-sm btn-outline-secondary action-btn" title="Copy">
                        <i class="fas fa-copy"></i>
                      </a>
                      <form method="post" class="d-inline">
                        <input type="hidden" name="selected_orders[]" value="<?= $row['id'] ?>">
                        <input type="hidden" name="bulk_action" value="delete">
                        <button type="submit" class="btn btn-sm btn-outline-danger action-btn" title="Delete">
                          <i class="fas fa-trash"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="empty-state">
          <i class="fas fa-shopping-bag"></i>
          <h4 class="text-muted">No Orders Found</h4>
          <p class="text-muted">There are currently no orders to display.</p>
        </div>
      <?php endif; ?>
    </div>
    
    <?php if ($total_pages > 1): ?>
    <nav aria-label="Page navigation">
      <ul class="pagination justify-content-center mt-4">
        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
          <a class="page-link" href="?page=<?= $page - 1 ?>" tabindex="-1" aria-disabled="<?= $page <= 1 ? 'true' : 'false' ?>">Previous</a>
        </li>
        
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <li class="page-item <?= $i == $page ? 'active' : '' ?>">
            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>
        
        <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
          <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
        </li>
      </ul>
    </nav>
    <?php endif; ?>
  </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  $(document).ready(function() {
    // Select all checkbox functionality
    $('#selectAll, #selectAllRows').change(function() {
      $('input[name="selected_orders[]"]').prop('checked', this.checked);
    });
    
    // Bulk action form validation
    $('#bulkActionForm').submit(function(e) {
      const action = $('select[name="bulk_action"]').val();
      const checked = $('input[name="selected_orders[]"]:checked').length > 0;
      
      if (action && !checked) {
        alert('Please select at least one order');
        e.preventDefault();
      } else if (!action) {
        alert('Please select an action');
        e.preventDefault();
      }
    });
  });
</script>
</body>
</html>
