

<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "velvet";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = intval($_POST['id']);

    // Toggle status
    if (isset($_POST['toggle_status'])) {
        $newStatus = ($_POST['toggle_status'] === 'block') ? 'blocked' : 'active';
        $stmt = $conn->prepare("UPDATE users SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $newStatus, $userId);
        $stmt->execute();
        $stmt->close();
    }

    // Toggle role
    if (isset($_POST['toggle_role'])) {
        $newRole = ($_POST['toggle_role'] === 'promote') ? 'admin' : 'customer';
        $stmt = $conn->prepare("UPDATE users SET user_type = ? WHERE id = ?");
        $stmt->bind_param("si", $newRole, $userId);
        $stmt->execute();
        $stmt->close();
    }

    // Delete user
    if (isset($_POST['delete_user'])) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch all users
$sql = "SELECT * FROM users ORDER BY id DESC";
$result = $conn->query($sql);
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Manage Users</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    :root {
      --primary: #5e4b8b;
      --primary-light: #7d6ba0;
      --secondary: #ff7e9d;
      --light: #f8f9fa;
      --dark: #212529;
      --gray: #6c757d;
      --success: #28a745;
      --danger: #dc3545;
      --warning: #fd7e14;
      --info: #17a2b8;
    }
    
    body {
      background-color: #f5f7fb;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: var(--dark);
    }
    
    .admin-container {
      max-width: 1400px;
      margin: 0 auto;
      padding: 20px;
    }
    
    .sidebar {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      height: 100%;
      padding: 20px 0;
    }
    
    .sidebar-header {
      padding: 0 20px 20px;
      border-bottom: 1px solid var(--light);
    }
    
    .nav-link {
      color: var(--gray);
      padding: 12px 20px;
      margin: 5px 10px;
      border-radius: 8px;
      font-weight: 500;
      transition: all 0.3s;
    }
    
    .nav-link:hover, .nav-link.active {
      color: var(--primary);
      background-color: rgba(94, 75, 139, 0.1);
    }
    
    .nav-link i {
      margin-right: 10px;
      width: 20px;
      text-align: center;
    }
    
    .main-content {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      padding: 25px;
    }
    
    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
      padding-bottom: 20px;
      border-bottom: 1px solid var(--light);
    }
    
    .page-title {
      color: var(--primary);
      font-weight: 600;
      margin: 0;
    }
    
    .badge {
      font-weight: 500;
      padding: 5px 10px;
      border-radius: 20px;
    }
    
    .badge-primary {
      background-color: var(--primary);
    }
    
    .badge-success {
      background-color: var(--success);
    }
    
    .badge-danger {
      background-color: var(--danger);
    }
    
    .badge-warning {
      background-color: var(--warning);
    }
    
    .user-card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      transition: all 0.3s;
      margin-bottom: 20px;
    }
    
    .user-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    
    .user-avatar {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background-color: var(--light);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--primary);
      font-weight: bold;
      font-size: 24px;
      margin-right: 15px;
    }
    
    .user-info {
      flex: 1;
    }
    
    .user-name {
      font-weight: 600;
      margin-bottom: 5px;
    }
    
    .user-email {
      color: var(--gray);
      font-size: 0.9rem;
    }
    
    .user-meta {
      font-size: 0.85rem;
      color: var(--gray);
    }
    
    .action-btn {
      padding: 6px 12px;
      font-size: 0.85rem;
      border-radius: 6px;
      margin: 2px;
      transition: all 0.3s;
    }
    
    .action-btn i {
      margin-right: 5px;
    }
    
    .btn-sm-block {
      width: 100%;
      margin-top: 5px;
    }
    
    .empty-state {
      text-align: center;
      padding: 50px 20px;
    }
    
    .empty-state i {
      font-size: 3rem;
      color: var(--light);
      margin-bottom: 15px;
    }
    
    .search-box {
      position: relative;
      margin-bottom: 20px;
    }
    
    .search-box i {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--gray);
    }
    
    .search-input {
      padding-left: 40px;
      border-radius: 30px;
      border: 1px solid var(--light);
    }
    
    @media (max-width: 992px) {
      .sidebar {
        margin-bottom: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="sidebar">
        <div class="sidebar-brand">
            <img src="WhatsApp Image 2025-06-10 at 13.27.45_db345c97.jpg" alt="Velvet Vogue Logo">
            <h4>VELVET VOGUE</h4>
            <div class="tagline">Luxury Fashion Admin</div>
        </div>
        
        <nav class="nav flex-column mt-4">
            <li class="nav-item">
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
                <a class="nav-link active" href="feedback2.php">
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
            margin-left: 280px;
            transition: margin-left 0.3s;
        }
        
        /* Sidebar Styles */
        .sidebar {
            height: 100vh;
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-light) 100%);
            color: white;
            padding-top: 1.5rem;
            position: fixed;
            width: 280px;
            left: 0;
            top: 0;
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
        
    </style>
    <!-- Main Content -->
    <div class="col-lg-9">
      <div class="main-content">
        <div class="page-header">
          <h2 class="page-title"><i class="bi bi-people-fill me-2"></i>User Management</h2>
          <span class="badge badge-primary">
            <i class="bi bi-person-fill me-1"></i>
            <?= $result->num_rows ?> Users
          </span>
        </div>
        
        <div class="search-box">
          <i class="bi bi-search"></i>
          <input type="text" class="form-control search-input" placeholder="Search users...">
        </div>
        
        <?php if ($result->num_rows > 0): ?>
          <div class="row">
            <?php while ($row = $result->fetch_assoc()): ?>
              <div class="col-md-6 col-lg-4">
                <div class="user-card p-3">
                  <div class="d-flex align-items-center mb-3">
                    <div class="user-avatar">
                      <?= strtoupper(substr($row['name'], 0, 1)) ?>
                    </div>
                    <div class="user-info">
                      <h5 class="user-name"><?= htmlspecialchars($row['name']) ?></h5>
                      <div class="user-email"><?= htmlspecialchars($row['email']) ?></div>
                    </div>
                  </div>
                  
                  <div class="d-flex justify-content-between mb-3">
                    <span class="badge <?= $row['user_type'] === 'admin' ? 'badge-primary' : 'badge-warning' ?>">
                      <?= ucfirst($row['user_type']) ?>
                    </span>
                    <span class="badge <?= $row['status'] === 'active' ? 'badge-success' : 'badge-danger' ?>">
                      <?= ucfirst($row['status']) ?>
                    </span>
                  </div>
                  
                  <div class="user-meta mb-3">
                    <small><i class="bi bi-calendar me-1"></i> Joined <?= date('M d, Y', strtotime($row['registered_at'])) ?></small>
                  </div>
                  
                  <div class="d-flex flex-wrap">
                    <!-- Status Toggle -->
                    <form method="post" class="w-100 mb-1">
                      <input type="hidden" name="id" value="<?= $row['id'] ?>">
                      <input type="hidden" name="toggle_status" value="<?= $row['status'] === 'active' ? 'block' : 'unblock' ?>">
                      <button class="btn btn-sm <?= $row['status'] === 'active' ? 'btn-danger' : 'btn-success' ?> action-btn btn-sm-block" type="submit">
                        <i class="bi bi-<?= $row['status'] === 'active' ? 'slash-circle' : 'check-circle' ?>"></i>
                        <?= $row['status'] === 'active' ? 'Block User' : 'Unblock User' ?>
                      </button>
                    </form>
                    
                    <!-- Role Toggle -->
                    <form method="post" class="w-100 mb-1">
                      <input type="hidden" name="id" value="<?= $row['id'] ?>">
                      <input type="hidden" name="toggle_role" value="<?= $row['user_type'] === 'admin' ? 'demote' : 'promote' ?>">
                      <button class="btn btn-sm <?= $row['user_type'] === 'admin' ? 'btn-warning' : 'btn-primary' ?> action-btn btn-sm-block" type="submit">
                        <i class="bi bi-<?= $row['user_type'] === 'admin' ? 'arrow-down-circle' : 'arrow-up-circle' ?>"></i>
                        <?= $row['user_type'] === 'admin' ? 'Demote to Customer' : 'Promote to Admin' ?>
                      </button>
                    </form>
                    
                    <!-- Delete Button -->
                    <form method="post" class="w-100" onsubmit="return confirm('Are you sure you want to delete this user?');">
                      <input type="hidden" name="id" value="<?= $row['id'] ?>">
                      <input type="hidden" name="delete_user" value="1">
                      <button class="btn btn-sm btn-outline-danger action-btn btn-sm-block" type="submit">
                        <i class="bi bi-trash"></i> Delete User
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
        <?php else: ?>
          <div class="empty-state">
            <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
            <h4 class="mt-3">No users found</h4>
            <p class="text-muted">There are currently no registered users in the system</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Search functionality
  document.querySelector('.search-input').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    document.querySelectorAll('.user-card').forEach(card => {
      const name = card.querySelector('.user-name').textContent.toLowerCase();
      const email = card.querySelector('.user-email').textContent.toLowerCase();
      if (name.includes(searchTerm) || email.includes(searchTerm)) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });
  });
</script>
</body>
</html>

