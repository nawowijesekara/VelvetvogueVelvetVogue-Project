<?php
session_start();

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "velvet";

try {
    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Handle feedback deletion
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_feedback'])) {
        $feedback_id = intval($_POST['feedback_id']);
        $stmt = $conn->prepare("DELETE FROM feedback WHERE id = ?");
        $stmt->bind_param("i", $feedback_id);
        $stmt->execute();
        $stmt->close();
        
        // Redirect to prevent form resubmission
        header("Location: feedback2.php");
        exit();
    }

    // Fetch feedback with sorting
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'submitted_at';
    $order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
    $allowed_sorts = ['id', 'name', 'email', 'rating', 'submitted_at'];
    $sort = in_array($sort, $allowed_sorts) ? $sort : 'submitted_at';
    $order = $order === 'ASC' ? 'ASC' : 'DESC';

    $result = $conn->query("SELECT * FROM feedback ORDER BY $sort $order");

} catch (Exception $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feedback Management | Velvet Vogue</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
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
        
        /* Main Content Styles */
        .main-content {
            padding: 2rem;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .page-title {
            font-family: var(--luxury-font);
            color: var(--primary-color);
            margin-bottom: 0;
        }
        
        /* Feedback Card Styles */
        .feedback-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            border-left: 4px solid var(--gold-accent);
            overflow: hidden;
        }
        
        .feedback-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .feedback-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            background-color: rgba(248, 249, 250, 0.5);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        
        .feedback-body {
            padding: 1.5rem;
        }
        
        .rating {
            color: var(--warning-color);
            font-size: 1.2rem;
            margin-bottom: 0;
        }
        
        .comment {
            font-size: 0.95rem;
            line-height: 1.6;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
        }
        
        .customer-info {
            display: flex;
            align-items: center;
            margin-top: 1rem;
        }
        
        .customer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--primary-color), var(--primary-light));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-weight: bold;
            flex-shrink: 0;
        }
        
        .customer-details {
            flex: 1;
            min-width: 0;
        }
        
        .customer-name {
            font-weight: 500;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .customer-email {
            font-size: 0.8rem;
            color: var(--text-light);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .feedback-date {
            font-size: 0.8rem;
            color: var(--text-light);
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            margin: 2rem 0;
        }
        
        .empty-state i {
            font-size: 3rem;
            color: var(--text-light);
            margin-bottom: 1rem;
        }
        
        /* Buttons */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-light);
            border-color: var(--primary-light);
        }
        
        /* Responsive Adjustments */
        @media (max-width: 992px) {
            body {
                margin-left: 0;
            }
            
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <img src="WhatsApp Image 2025-06-10 at 13.27.45_db345c97.jpg" alt="Velvet Vogue Logo">
            <h4>VELVET VOGUE</h4>
            <div class="tagline">Luxury Fashion Admin</div>
        </div>
        
        <nav class="nav flex-column mt-4">
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">
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

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="page-header">
                <h2 class="page-title">
                    <i class="fas fa-comment-alt me-2"></i>Customer Feedback
                </h2>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-sort me-1"></i> Sort By
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                        <li><a class="dropdown-item" href="?sort=submitted_at&order=DESC">Newest First</a></li>
                        <li><a class="dropdown-item" href="?sort=submitted_at&order=ASC">Oldest First</a></li>
                        <li><a class="dropdown-item" href="?sort=rating&order=DESC">Highest Rating</a></li>
                        <li><a class="dropdown-item" href="?sort=rating&order=ASC">Lowest Rating</a></li>
                        <li><a class="dropdown-item" href="?sort=name&order=ASC">Customer Name (A-Z)</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="row">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="feedback-card">
                                <div class="feedback-header">
                                    <div class="rating" title="<?= $row['rating'] ?> star rating">
                                        <?= str_repeat('★', $row['rating']) ?><?= str_repeat('☆', 5 - $row['rating']) ?>
                                    </div>
                                    
                                </div>
                                
                                <div class="feedback-body">
                                    <div class="comment">
                                        <?= nl2br(htmlspecialchars($row['comments'])) ?>
                                    </div>
                                    
                                    <div class="customer-info">
                                        <div class="customer-avatar">
                                            <?= strtoupper(substr($row['name'], 0, 1)) ?>
                                        </div>
                                        <div class="customer-details">
                                            <div class="customer-name"><?= htmlspecialchars($row['name']) ?></div>
                                            <div class="customer-email"><?= htmlspecialchars($row['email']) ?></div>
                                        </div>
                                        <form method="post" class="ms-2">
                                            <input type="hidden" name="feedback_id" value="<?= $row['id'] ?>">
                                            <button type="submit" name="delete_feedback" class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Are you sure you want to delete this feedback?')"
                                                    data-bs-toggle="tooltip" title="Delete Feedback">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="fas fa-comment-alt fa-3x mb-3"></i>
                            <h4>No Feedback Found</h4>
                            <p class="text-muted">There is currently no customer feedback to display.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
            
            // Mobile sidebar toggle
            $('#sidebarToggle').click(function() {
                $('.sidebar').toggleClass('active');
                $('body').toggleClass('sidebar-active');
            });
        });
    </script>
</body>
</html>