<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "velvet");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle status update
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['inquiry_id'])) {
    $inquiry_id = (int)$_POST['inquiry_id'];

    // Get current status
    $check_sql = "SELECT status FROM inquiries WHERE id = $inquiry_id";
    $check_result = $conn->query($check_sql);
    if ($check_result && $check_result->num_rows > 0) {
        $current_status = $check_result->fetch_assoc()['status'];
        $new_status = ($current_status === 'In Progress') ? 'Resolved' : 'In Progress';

        $update_sql = "UPDATE inquiries SET status = '$new_status' WHERE id = $inquiry_id";
        $conn->query($update_sql);
    }
}

// Fetch updated inquiries
$sql = "SELECT * FROM inquiries ORDER BY submitted_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Customer Inquiries</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary-color:rgb(109, 28, 28);
      --secondary-color: #ffffff;
      --accent-color: #d4af37;
      --dark-accent: #000000;
      --text-color: #333333;
      --light-gray: #f5f5f5;
      --dark-gray: #e0e0e0;
      --success-color: #28a745;
      --warning-color: #ffc107;
      --danger-color:rgb(84, 15, 22);
    }
    
    body {
      background-color: var(--secondary-color);
      color: var(--text-color);
      font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }
    
    .sidebar {
      background: linear-gradient(180deg, var(--dark-accent) 0%, #222222 100%);
      min-height: 100vh;
      width: 250px;
      position: fixed;
      box-shadow: 0 0.15rem 1.75rem 0 rgba(0, 0, 0, 0.2);
      border-right: 3px solid var(--accent-color);
    }
    
    .sidebar-brand {
      height: 4.375rem;
      text-decoration: none;
      font-size: 1.2rem;
      font-weight: 800;
      padding: 1.5rem 1rem;
      text-align: center;
      letter-spacing: 0.05rem;
      z-index: 1;
      color: var(--accent-color);
      border-bottom: 1px solid var(--accent-color);
    }
    
    .sidebar-divider {
      border-top: 1px solid rgba(212, 175, 55, 0.3);
      margin: 0 1rem 1rem;
    }
    
    .nav-item {
      position: relative;
    }
    
    .nav-link {
      color: rgba(255, 255, 255, 0.8);
      padding: 0.75rem 1rem;
      font-weight: 600;
      font-size: 0.85rem;
      transition: all 0.3s;
    }
    
    .nav-link:hover {
      color: var(--accent-color);
      background-color: rgba(209, 0, 0, 0.2);
    }
    
    .nav-link i {
      margin-right: 0.5rem;
      color: var(--accent-color);
    }
    
    .active {
      color: white;
      background-color: var(--primary-color);
    }
    
    .active i {
      color: white;
    }
    
    .content-wrapper {
      margin-left: 250px;
      width: calc(100% - 250px);
      padding: 2rem;
      background-color: var(--light-gray);
    }
    
    .card {
      border: none;
      border-radius: 0.35rem;
      box-shadow: 0 0.15rem 1.75rem 0 rgba(0, 0, 0, 0.1);
      margin-bottom: 2rem;
      border-top: 3px solid var(--primary-color);
    }
    
    .card-header {
      background-color: white;
      border-bottom: 1px solid var(--light-gray);
      padding: 1rem 1.35rem;
      font-weight: 700;
      color: var(--primary-color);
    }
    
    .badge-pill {
      border-radius: 10rem;
      padding: 0.5em 0.8em;
    }
    
    .btn-status {
      min-width: 120px;
    }
    
    .table-responsive {
      overflow-x: auto;
    }
    
    .table {
      color: var(--text-color);
    }
    
    .table th {
      border-top: none;
      font-weight: 700;
      text-transform: uppercase;
      font-size: 0.7rem;
      letter-spacing: 0.05em;
      color: var(--text-color);
      background-color: var(--primary-color);
      color: white;
    }
    
    .message-cell {
      max-width: 300px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    
    .message-cell:hover {
      white-space: normal;
      overflow: visible;
      position: absolute;
      z-index: 100;
      background: white;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      padding: 10px;
      border-radius: 5px;
      max-width: 400px;
    }
    
    .status-badge {
      font-size: 0.75rem;
      font-weight: 600;
    }
    
    .topbar {
      height: 4.375rem;
      box-shadow: 0 0.15rem 1.75rem 0 rgba(0, 0, 0, 0.1);
      background-color: white;
      border-bottom: 1px solid var(--dark-gray);
    }
    
    .breadcrumb {
      font-size: 0.85rem;
      background-color: transparent;
    }
    
    .breadcrumb-item.active {
      color: var(--primary-color);
      font-weight: bold;
    }
    
    .user-dropdown img {
      height: 2rem;
      width: 2rem;
      border-radius: 50%;
      border: 2px solid var(--accent-color);
    }
    
    .dropdown-menu {
      font-size: 0.85rem;
      border: 1px solid var(--accent-color);
    }
    
    .dropdown-item:hover {
      background-color: var(--primary-color);
      color: white;
    }
    
    .btn-primary {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }
    
    .btn-primary:hover {
      background-color:rgb(82, 14, 14);
      border-color:rgb(83, 14, 14);
    }
    
    .btn-outline-primary {
      color: var(--primary-color);
      border-color: var(--primary-color);
    }
    
    .btn-outline-primary:hover {
      background-color: var(--primary-color);
      color: white;
    }
    
    .bg-success {
      background-color: var(--success-color) !important;
    }
    
    .bg-warning {
      background-color: var(--warning-color) !important;
    }
    
    h1, h2, h3, h4, h5, h6 {
      color: var(--primary-color);
    }
    
    .modal-header {
      background-color: var(--primary-color);
      color: white;
    }
    
    .modal-title {
      font-weight: bold;
    }
    
    .close {
      color: white;
    }
    
    .navbar-nav .nav-link {
      color: var(--text-color);
    }
    
    .fa-inbox {
      color: var(--primary-color);
    }
  </style>
</head>
<body><!-- Sidebar Navigation -->
<div class="sidebar">
  <div class="sidebar-brand">
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

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Topbar -->
    <nav class="navbar navbar-expand topbar mb-4 static-top">
      <div class="container-fluid">
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
          <i class="fa fa-bars"></i>
        </button>
        
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
          <li class="breadcrumb-item active">Customer Support</li>
        </ol>
        
        <ul class="navbar-nav ml-auto">
          <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin User</span>
              <img class="img-profile rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/60x60">
              <i class="fas fa-angle-down ml-1" style="color: var(--accent-color);"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
              <a class="dropdown-item" href="#">
                <i class="fas fa-user fa-sm fa-fw mr-2" style="color: var(--accent-color);"></i>
                Profile
              </a>
              <a class="dropdown-item" href="#">
                <i class="fas fa-cogs fa-sm fa-fw mr-2" style="color: var(--accent-color);"></i>
                Settings
              </a>
              <a class="dropdown-item" href="#">
                <i class="fas fa-list fa-sm fa-fw mr-2" style="color: var(--accent-color);"></i>
                Activity Log
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="logout.php">
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2" style="color: var(--accent-color);"></i>
                Logout
              </a>
            </div>
          </li>
        </ul>
      </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid">
      <!-- Page Heading -->
      <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
          <i class="fas fa-comments mr-2" style="color: var(--accent-color);"></i>Customer Inquiries
        </h1>
        <button class="btn btn-sm btn-primary">
          <i class="fas fa-download mr-1"></i> Generate Report
        </button>
      </div>
      
      <!-- Status Cards -->
      <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                    Total Inquiries</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800">24</div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-inbox fa-2x" style="color: var(--primary-color);"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                    Resolved</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800">14</div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-check-circle fa-2x" style="color: var(--success-color);"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                    In Progress</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800">8</div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-hourglass-half fa-2x" style="color: var(--warning-color);"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                    Urgent</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800">2</div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-exclamation-triangle fa-2x" style="color: var(--danger-color);"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Content Row -->
      <div class="row">
        <div class="col-12">
          <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">Customer Support Tickets</h6>
              <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-v fa-sm fa-fw" style="color: var(--accent-color);"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                  <div class="dropdown-header">Filter Options:</div>
                  <a class="dropdown-item" href="#"><i class="fas fa-list mr-1"></i> All Inquiries</a>
                  <a class="dropdown-item" href="#"><i class="fas fa-hourglass-half mr-1"></i> Pending</a>
                  <a class="dropdown-item" href="#"><i class="fas fa-check-circle mr-1"></i> Resolved</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#"><i class="fas fa-file-export mr-1"></i> Export</a>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <?php if ($result && $result->num_rows > 0): ?>
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>Ticket ID</th>
                        <th>Customer</th>
                        <th>Contact</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                          <td>#<?= $row['id'] ?></td>
                          <td><?= htmlspecialchars($row['name']) ?></td>
                          <td><a href="mailto:<?= htmlspecialchars($row['email']) ?>"><?= htmlspecialchars($row['email']) ?></a></td>
                          <td><?= htmlspecialchars($row['subject']) ?></td>
                          <td class="message-cell" title="<?= htmlspecialchars($row['message']) ?>">
                            <?= nl2br(htmlspecialchars(substr($row['message'], 0, 50))) ?>...
                          </td>
                          <td><?= date('M d, Y h:i A', strtotime($row['submitted_at'])) ?></td>
                          <td>
                            <span class="badge status-badge badge-pill bg-<?= $row['status'] === 'Resolved' ? 'success' : 'warning' ?>">
                              <?= $row['status'] ?>
                            </span>
                          </td>
                          <td>
                            <form method="POST" class="d-inline">
                              <input type="hidden" name="inquiry_id" value="<?= $row['id'] ?>">
                              <button class="btn btn-sm btn-status btn-<?= $row['status'] === 'In Progress' ? 'success' : 'secondary' ?>" type="submit">
                                <i class="fas fa-<?= $row['status'] === 'In Progress' ? 'check' : 'sync' ?> mr-1"></i>
                                <?= $row['status'] === 'In Progress' ? 'Resolve' : 'Reopen' ?>
                              </button>
                            </form>
                            <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#replyModal" data-email="<?= htmlspecialchars($row['email']) ?>" data-subject="RE: <?= htmlspecialchars($row['subject']) ?>">
                              <i class="fas fa-reply"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger">
                              <i class="fas fa-trash-alt"></i>
                            </button>
                          </td>
                        </tr>
                      <?php endwhile; ?>
                    </tbody>
                  </table>
                <?php else: ?>
                  <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x mb-3" style="color: var(--primary-color);"></i>
                    <h5 class="text-gray-800">No support tickets found</h5>
                    <p class="text-muted">Customer inquiries will appear here when submitted</p>
                    <button class="btn btn-primary mt-2">
                      <i class="fas fa-sync-alt mr-1"></i> Refresh
                    </button>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Reply Modal -->
  <div class="modal fade" id="replyModal" tabindex="-1" role="dialog" aria-labelledby="replyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="replyModalLabel">Reply to Customer</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="replyForm">
            <div class="form-group">
              <label for="recipientEmail">To:</label>
              <input type="email" class="form-control" id="recipientEmail" readonly>
            </div>
            <div class="form-group">
              <label for="emailSubject">Subject:</label>
              <input type="text" class="form-control" id="emailSubject">
            </div>
            <div class="form-group">
              <label for="emailMessage">Message:</label>
              <textarea class="form-control" id="emailMessage" rows="5"></textarea>
            </div>
            <div class="form-group">
              <label for="emailTemplate">Template:</label>
              <select class="form-control" id="emailTemplate">
                <option value="">Select a template...</option>
                <option value="thank_you">Thank You Response</option>
                <option value="shipping_info">Shipping Information</option>
                <option value="return_policy">Return Policy</option>
                <option value="custom">Custom</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i> Cancel
          </button>
          <button type="button" class="btn btn-primary" id="sendReply">
            <i class="fas fa-paper-plane mr-1"></i> Send Reply
          </button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    $(document).ready(function() {
      // Initialize reply modal with data
      $('#replyModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var recipient = button.data('email');
        var subject = button.data('subject');
        var modal = $(this);
        modal.find('#recipientEmail').val(recipient);
        modal.find('#emailSubject').val(subject);
      });
      
      // Send reply button handler
      $('#sendReply').click(function() {
        // Here you would typically send the email via AJAX or form submission
        alert('Reply sent! (This is a demo - no email actually sent)');
        $('#replyModal').modal('hide');
      });
      
      // Toggle message cell expansion
      $('.message-cell').click(function() {
        $(this).toggleClass('expanded');
      });
      
      // Template selection
      $('#emailTemplate').change(function() {
        if($(this).val() === 'thank_you') {
          $('#emailMessage').val('Dear Customer,\n\nThank you for contacting us. We appreciate your feedback and will get back to you shortly.\n\nBest regards,\nSupport Team');
        } else if($(this).val() === 'shipping_info') {
          $('#emailMessage').val('Dear Customer,\n\nOur standard shipping time is 3-5 business days. For more information, please visit our shipping policy page.\n\nBest regards,\nSupport Team');
        } else if($(this).val() === 'return_policy') {
          $('#emailMessage').val('Dear Customer,\n\nYou may return items within 30 days of purchase. Please ensure the items are in original condition with tags attached.\n\nBest regards,\nSupport Team');
        }
      });
    });
  </script>
</body>
</html>