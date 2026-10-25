<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "velvet");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$message = '';
$error = '';
$edit_product = null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add/Update Product
    if (isset($_POST['submit_product'])) {
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $name = $conn->real_escape_string($_POST['name']);
        $price = floatval($_POST['price']);
        $description = $conn->real_escape_string($_POST['description']);
        $type = $conn->real_escape_string($_POST['type']);
        $category = $conn->real_escape_string($_POST['category']);
        $size = $conn->real_escape_string($_POST['available_size']);
        $color = $conn->real_escape_string($_POST['available_color']);
        $sold = 0; // Default value
        
        // Image handling
        $image_path = isset($_POST['existing_image']) ? $_POST['existing_image'] : '';
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $upload_dir = "uploads/";
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $tmp_name = $_FILES['image']['tmp_name'];
            $original_name = basename($_FILES['image']['name']);
            $target_file = $upload_dir . time() . "_" . $original_name;
            
            if (move_uploaded_file($tmp_name, $target_file)) {
                // Delete old image if exists
                if (!empty($image_path) && file_exists($image_path)) {
                    unlink($image_path);
                }
                $image_path = $target_file;
            }
        }
        
        if ($id > 0) {
            // Update product
            $sql = "UPDATE products SET 
                    name='$name', price=$price, " . 
                    (!empty($image_path) ? "image_url='$image_path', " : "") . 
                    "description='$description', type='$type', 
                    category='$category', available_size='$size', 
                    available_color='$color' 
                    WHERE id=$id";
        } else {
            // Add new product
            $sql = "INSERT INTO products 
                    (name, price, image_url, sold, description, type, category, available_size, available_color) 
                    VALUES 
                    ('$name', $price, '$image_path', $sold, '$description', '$type', '$category', '$size', '$color')";
        }
        
        if ($conn->query($sql)) {
            $message = $id > 0 ? "Product updated successfully!" : "Product added successfully!";
        } else {
            $error = "Database error: " . $conn->error;
        }
    }
}

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // First get image path to delete the file
    $result = $conn->query("SELECT image_url FROM products WHERE id=$id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (file_exists($row['image_url'])) {
            unlink($row['image_url']);
        }
    }
    
    // Then delete the record
    if ($conn->query("DELETE FROM products WHERE id=$id")) {
        $message = "Product deleted successfully!";
    } else {
        $error = "Error deleting product: " . $conn->error;
    }
}

// Get product for editing
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM products WHERE id=$id");
    if ($result->num_rows > 0) {
        $edit_product = $result->fetch_assoc();
    }
}

// Get all products
$products = [];
$result = $conn->query("SELECT * FROM products ORDER BY id DESC");
if ($result) {
    $products = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Product Management | Velvet Vogue</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    :root {
      --primary-color:rgb(105, 10, 10);
      --secondary-color:rgb(192, 51, 51);
      --accent-color:rgb(158, 23, 23);
      --light-bg: #f8f9fa;
      --dark-text: #212529;
      --light-text: #6c757d;
    }
    
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f5f5f5;
      color: var(--dark-text);
    }
    
    /* Sidebar Styles */
    /* .sidebar {
      background-color: white;
      height: 100vh;
      position: fixed;
      width: 250px;
      box-shadow: 2px 0 10px rgba(0,0,0,0.1);
      padding: 20px 0;
    }
    
    .sidebar-brand {
      padding: 0 20px 20px;
      border-bottom: 1px solid #eee;
      margin-bottom: 20px;
    }
    
    .sidebar-nav {
      list-style: none;
      padding: 0;
    }
    
    .sidebar-nav li a {
      display: block;
      padding: 10px 20px;
      color: var(--dark-text);
      text-decoration: none;
      transition: all 0.3s;
      border-left: 3px solid transparent;
    }
    
    .sidebar-nav li a:hover,
    .sidebar-nav li a.active {
      background-color: rgba(12, 6, 104, 0.05);
      border-left: 3px solid var(--primary-color);
      color: var(--primary-color);
    }
    
    .sidebar-nav li a i {
      margin-right: 10px;
      width: 20px;
      text-align: center;
    } */
    
    /* Main Content */
    .main-content {
      margin-left: 250px;
      padding: 20px;
    }
    
    /* Header */
    .admin-header {
      background-color: white;
      padding: 15px 20px;
      border-radius: 10px;
      margin-bottom: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    /* Card Styles */
    .admin-card {
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      padding: 20px;
      margin-bottom: 20px;
    }
    
    /* Form Styles */
    .form-label {
      font-weight: 600;
      color: var(--primary-color);
    }
    
    .form-control, .form-select {
      border-radius: 8px;
      padding: 10px 15px;
      border: 1px solid #ddd;
    }
    
    .form-control:focus, .form-select:focus {
      border-color: var(--accent-color);
      box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.1);
    }
    
    /* Button Styles */
    .btn-primary {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
      padding: 10px 20px;
      border-radius: 8px;
      font-weight: 600;
    }
    
    .btn-primary:hover {
      background-color: var(--secondary-color);
      border-color: var(--secondary-color);
    }
    
    .btn-outline-primary {
      color: var(--primary-color);
      border-color: var(--primary-color);
    }
    
    .btn-outline-primary:hover {
      background-color: var(--primary-color);
      color: white;
    }
    
    /* Table Styles */
    .table {
      margin-bottom: 0;
    }
    
    .table th {
      background-color: var(--primary-color);
      color: white;
      font-weight: 500;
      padding: 12px 15px;
    }
    
    .table td {
      vertical-align: middle;
      padding: 12px 15px;
    }
    
    /* Product Image */
    .product-img {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 5px;
      border: 1px solid #eee;
    }
    
    .preview-image {
      max-width: 150px;
      max-height: 150px;
      border-radius: 8px;
      border: 1px solid #ddd;
      padding: 5px;
      margin-top: 10px;
    }
    
    /* Badge */
    .badge {
      font-weight: 500;
      padding: 5px 10px;
      border-radius: 20px;
    }
    
    /* Responsive */
    @media (max-width: 992px) {
      .sidebar {
        width: 100%;
        height: auto;
        position: relative;
      }
      
      .main-content {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <!-- <div class="sidebar">
      <div class="sidebar-brand">
        <h4 class="m-0" style="color: var(--primary-color);">VELVET VOGUE</h4>
        <small class="text-muted">Admin Dashboard</small>
      </div>
      
      <ul class="sidebar-nav">
        <li><a href="admin_dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
        <li><a href="addproduct.php" class="active"><i class="bi bi-box-seam"></i> Products</a></li>
        <li><a href="vieworders.php"><i class="bi bi-receipt"></i> Orders</a></li>
        <li><a href="manageusers.php"><i class="bi bi-people"></i> Users</a></li>
        <li><a href="logout2.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
      </ul>
    </div> -->
      <div class="sidebar">
    <div class="sidebar-brand">
      <img src="WhatsApp Image 2025-06-10 at 13.27.45_db345c97.jpg" alt="Velvet Vogue Logo">
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
  </style>
    

    <!-- Main Content -->
    <div class="main-content">
      <!-- Header -->
      <div class="admin-header">
        <h2 class="mb-0"><i class="bi bi-box-seam me-2"></i> Product Management</h2>
        <span class="badge bg-primary"><?= count($products) ?> Products</span>
      </div>
      
      <!-- Messages -->
      <?php if (!empty($message)): ?>
        <div class="alert alert-success alert-dismissible fade show">
          <?= htmlspecialchars($message) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>
      
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show">
          <?= htmlspecialchars($error) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>
      
      <!-- Product Form -->
      <div class="admin-card mb-4">
        <h3 class="mb-4"><i class="bi bi-<?= $edit_product ? 'pencil-square' : 'plus-circle' ?> me-2"></i> <?= $edit_product ? 'Edit Product' : 'Add New Product' ?></h3>
        
        <form action="" method="post" enctype="multipart/form-data" id="productForm">
          <?php if ($edit_product): ?>
            <input type="hidden" name="id" value="<?= $edit_product['id'] ?>">
            <input type="hidden" name="existing_image" value="<?= htmlspecialchars($edit_product['image_url'] ?? '') ?>">
          <?php endif; ?>
          
          <div class="row g-3">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" 
                       value="<?= $edit_product ? htmlspecialchars($edit_product['name']) : '' ?>" required>
              </div>
              
              <div class="mb-3">
                <label for="price" class="form-label">Price (LKR)</label>
                <div class="input-group">
                  <span class="input-group-text">Rs.</span>
                  <input type="number" step="0.01" class="form-control" id="price" name="price" 
                         value="<?= $edit_product ? htmlspecialchars($edit_product['price']) : '' ?>" required>
                </div>
              </div>
              
              <div class="mb-3">
                <label for="image" class="form-label">Product Image</label>
                <input type="file" class="form-control" id="image" name="image" <?= !$edit_product ? 'required' : '' ?>>
                <div class="form-text">Recommended size: 500x500 pixels</div>
                
                <?php if ($edit_product && !empty($edit_product['image_url'])): ?>
                  <div class="mt-3">
                    <img src="<?= htmlspecialchars($edit_product['image_url']) ?>" class="preview-image img-thumbnail">
                    <div class="form-check mt-2">
                      <input class="form-check-input" type="checkbox" id="removeImage" name="remove_image">
                      <label class="form-check-label" for="removeImage">Remove current image</label>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
            </div>
            
            <div class="col-md-6">
              <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="5" required><?= 
                  $edit_product ? htmlspecialchars($edit_product['description']) : '' ?></textarea>
              </div>
              
              <div class="row g-2">
                <div class="col-md-6 mb-3">
                  <label for="type" class="form-label">Type</label>
                  <select class="form-select" id="type" name="type" required>
                    <option value="casual" <?= $edit_product && $edit_product['type'] == 'casual' ? 'selected' : '' ?>>Casual</option>
                    <option value="formal" <?= $edit_product && $edit_product['type'] == 'formal' ? 'selected' : '' ?>>Formal</option>
                    <option value="None" <?= $edit_product && $edit_product['type'] == 'None' ? 'selected' : '' ?>>None</option>
                  </select>
                </div>
                
                <div class="col-md-6 mb-3">
                  <label for="category" class="form-label">Category</label>
                  <select class="form-select" id="category" name="category" required>
                    <option value="men" <?= $edit_product && $edit_product['category'] == 'men' ? 'selected' : '' ?>>Men</option>
                    <option value="women" <?= $edit_product && $edit_product['category'] == 'women' ? 'selected' : '' ?>>Women</option>
                    <option value="Accessories" <?= $edit_product && $edit_product['category'] == 'Accessories' ? 'selected' : '' ?>>Accessories</option>
                    <option value="None" <?= $edit_product && $edit_product['category'] == 'None' ? 'selected' : '' ?>>None</option>
                  </select>
                </div>
              </div>
              
              <div class="row g-2">
                <div class="col-md-6 mb-3">
                  <label for="available_size" class="form-label">Available Sizes</label>
                  <input type="text" class="form-control" id="available_size" name="available_size" 
                         value="<?= $edit_product ? htmlspecialchars($edit_product['available_size']) : '' ?>" 
                         placeholder="S,M,L,XL" required>
                  <div class="form-text">Separate sizes with commas</div>
                </div>
                
                <div class="col-md-6 mb-3">
                  <label for="available_color" class="form-label">Available Colors</label>
                  <input type="text" class="form-control" id="available_color" name="available_color" 
                         value="<?= $edit_product ? htmlspecialchars($edit_product['available_color']) : '' ?>" 
                         placeholder="Red,Black,White" required>
                  <div class="form-text">Separate colors with commas</div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="d-flex justify-content-end gap-2 mt-3">
            <?php if ($edit_product): ?>
              <a href="product_management.php" class="btn btn-outline-primary">
                <i class="bi bi-x-circle me-1"></i> Cancel
              </a>
            <?php endif; ?>
            <button type="submit" name="submit_product" class="btn btn-primary">
              <i class="bi bi-<?= $edit_product ? 'check-circle' : 'plus-circle' ?> me-1"></i>
              <?= $edit_product ? 'Update Product' : 'Add Product' ?>
            </button>
          </div>
        </form>
      </div>
      
      <!-- Product List -->
      <div class="admin-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h3 class="mb-0"><i class="bi bi-grid me-2"></i> Product List</h3>
          <div>
            <input type="text" class="form-control" placeholder="Search products..." id="searchInput">
          </div>
        </div>
        
        <?php if (count($products) > 0): ?>
          <div class="table-responsive">
            <table class="table table-hover" id="productsTable">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Product</th>
                  <th>Price</th>
                  <th>Type</th>
                  <th>Category</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($products as $product): ?>
                  <tr>
                    <td><?= $product['id'] ?></td>
                    <td>
                      <div class="d-flex align-items-center">
                        <?php if (!empty($product['image_url'])): ?>
                          <img src="<?= htmlspecialchars($product['image_url']) ?>" class="product-img me-3">
                        <?php else: ?>
                          <div class="product-img bg-light text-center me-3 d-flex align-items-center justify-content-center">
                            <i class="bi bi-image text-muted"></i>
                          </div>
                        <?php endif; ?>
                        <div>
                          <h6 class="mb-0"><?= htmlspecialchars($product['name']) ?></h6>
                          <small class="text-muted"><?= substr(htmlspecialchars($product['description']), 0, 50) ?>...</small>
                        </div>
                      </div>
                    </td>
                    <td>Rs. <?= number_format($product['price'], 2) ?></td>
                    <td><?= htmlspecialchars($product['type']) ?></td>
                    <td><?= htmlspecialchars($product['category']) ?></td>
                    <td>
                      <div class="d-flex gap-2">
                        <a href="?action=edit&id=<?= $product['id'] ?>" class="btn btn-sm btn-outline-primary">
                          <i class="bi bi-pencil"></i>
                        </a>
                        <a href="?action=delete&id=<?= $product['id'] ?>" class="btn btn-sm btn-outline-danger" 
                           onclick="return confirm('Are you sure you want to delete this product?')">
                          <i class="bi bi-trash"></i>
                        </a>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div class="text-center py-5">
            <i class="bi bi-box text-muted" style="font-size: 3rem;"></i>
            <h4 class="mt-3">No products found</h4>
            <p class="text-muted">Add your first product using the form above</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Preview image before upload
    document.getElementById('image').addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const preview = document.createElement('img');
          preview.src = e.target.result;
          preview.className = 'preview-image img-thumbnail mt-2';
          
          const existingPreview = document.querySelector('.preview-image');
          if (existingPreview) {
            existingPreview.replaceWith(preview);
          } else {
            const container = document.getElementById('image').parentNode;
            container.appendChild(preview);
          }
        }
        reader.readAsDataURL(file);
      }
    });
    
    // Simple search functionality
    document.getElementById('searchInput').addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase();
      const rows = document.querySelectorAll('#productsTable tbody tr');
      
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
      });
    });
  </script>
</body>
</html>