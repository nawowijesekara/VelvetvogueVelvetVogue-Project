

<?php
session_start();
require_once 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$orders = [];
$error = '';

try {
    // Get all orders for the current user
    $stmt = $conn->prepare("
        SELECT o.*, COUNT(oi.id) as item_count 
        FROM orders o
        LEFT JOIN order_items oi ON o.id = oi.order_id
        WHERE o.user_id = ?
        GROUP BY o.id
        ORDER BY o.order_date DESC
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    $stmt->close();

} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History | Velvet Vogue</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color:rgb(97, 8, 8);
            --secondary-color: #f8f9fa;
            --accent-color: #e83e8c;
            --text-color: #333;
            --light-text: #6c757d;
            --border-color: #dee2e6;
            --success-color: #28a745;
            --processing-color: #ffc107;
            --shipped-color: #17a2b8;
            --cancelled-color: #dc3545;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
            background-color: #f5f5f5;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .page-header h1 {
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .page-header p {
            color: var(--light-text);
        }
        
        .orders-list {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        
        .order-card {
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.3s;
        }
        
        .order-card:hover {
            background-color: var(--secondary-color);
        }
        
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }
        
        .order-number {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 18px;
        }
        
        .order-date {
            color: var(--light-text);
        }
        
        .order-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }
        
        .status-processing {
            background-color: rgba(255, 193, 7, 0.2);
            color: #b68b00;
        }
        
        .status-shipped {
            background-color: rgba(23, 162, 184, 0.2);
            color: #0d6b79;
        }
        
        .status-delivered {
            background-color: rgba(40, 167, 69, 0.2);
            color: var(--success-color);
        }
        
        .status-cancelled {
            background-color: rgba(220, 53, 69, 0.2);
            color: var(--cancelled-color);
        }
        
        .order-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .detail-group {
            margin-bottom: 10px;
        }
        
        .detail-label {
            font-size: 14px;
            color: var(--light-text);
            margin-bottom: 5px;
        }
        
        .detail-value {
            font-weight: 500;
        }
        
        .order-items-count {
            display: flex;
            align-items: center;
        }
        
        .order-items-count i {
            margin-right: 8px;
            color: var(--primary-color);
        }
        
        .order-total {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .order-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 15px;
        }
        
        .view-btn {
            padding: 8px 15px;
            background-color: white;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .view-btn:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .empty-state {
            text-align: center;
            padding: 50px 20px;
        }
        
        .empty-state i {
            font-size: 50px;
            color: var(--light-text);
            margin-bottom: 20px;
        }
        
        .empty-state h3 {
            color: var(--text-color);
            margin-bottom: 10px;
        }
        
        .empty-state p {
            color: var(--light-text);
            margin-bottom: 20px;
        }
        
        .shop-btn {
            padding: 10px 25px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .shop-btn:hover {
            background-color: #0a0557;
        }
        
        @media (max-width: 768px) {
            .order-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .order-status {
                margin-top: 10px;
            }
            
            .order-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1>Your Order History</h1>
            <p>View all your past orders with Velvet Vogue</p>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <div class="orders-list">
            <?php if (empty($orders)): ?>
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <h3>No Orders Found</h3>
                    <p>You haven't placed any orders yet.</p>
                    <a href="products.php" class="shop-btn">
                        <i class="fas fa-shopping-bag"></i> Start Shopping
                    </a>
                </div>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-number">Order #<?php echo htmlspecialchars($order['id']); ?></div>
                        <div class="order-date"><?php echo date('F j, Y', strtotime($order['order_date'])); ?></div>
                        <div class="order-status status-<?php echo strtolower($order['status']); ?>">
                            <?php echo ucfirst(htmlspecialchars($order['status'])); ?>
                        </div>
                    </div>
                    
                    <div class="order-details">
                        <div class="detail-group">
                            <div class="detail-label">Items</div>
                            <div class="detail-value order-items-count">
                                <i class="fas fa-boxes"></i> <?php echo htmlspecialchars($order['item_count']); ?> items
                            </div>
                        </div>
                        
                        <div class="detail-group">
                            <div class="detail-label">Payment Method</div>
                            <div class="detail-value"><?php echo htmlspecialchars($order['payment_method']); ?></div>
                        </div>
                        
                        <div class="detail-group">
                            <div class="detail-label">Total Amount</div>
                            <div class="detail-value order-total">Rs<?php echo number_format($order['total'], 2); ?></div>
                        </div>
                    </div>
                    
                    <div class="order-actions">
                        <a href="order_details.php?order_id=<?php echo $order['id']; ?>" class="view-btn">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>