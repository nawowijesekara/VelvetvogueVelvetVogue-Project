<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user info
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Handle profile photo update
$photo_error = "";
if (isset($_POST['update_photo']) && isset($_FILES['profile_photo'])) {
    $target_dir = "uploads/";
    $file_name = uniqid() . '_' . basename($_FILES["profile_photo"]["name"]);
    $target_file = $target_dir . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is actual image
    $check = getimagesize($_FILES["profile_photo"]["tmp_name"]);
    if($check === false) {
        $photo_error = "File is not an image.";
    } elseif ($_FILES["profile_photo"]["size"] > 5000000) {
        $photo_error = "File is too large (max 5MB).";
    } elseif(!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        $photo_error = "Only JPG, JPEG, PNG & GIF files allowed.";
    } elseif (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file)) {
        // Delete old photo if not default
        if($user['profile_photo'] && $user['profile_photo'] != 'default.png') {
            @unlink($target_dir . $user['profile_photo']);
        }
        
        $update = $conn->prepare("UPDATE users SET profile_photo = ? WHERE id = ?");
        $update->bind_param("si", $file_name, $user_id);
        $update->execute();
        header("Location: profile.php");
        exit();
    } else {
        $photo_error = "Error uploading file.";
    }
}

// Handle password change
$password_error = "";
$success = "";
if (isset($_POST['change_password'])) {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];

    if (empty($current) || empty($new)) {
        $password_error = "Both fields are required.";
    } elseif (strlen($new) < 8) {
        $password_error = "Password must be at least 8 characters.";
    } elseif (password_verify($current, $user['password'])) {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $update->bind_param("si", $hashed, $user_id);
        $update->execute();
        $success = "Password changed successfully!";
    } else {
        $password_error = "Current password is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | Velvet Vogue</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #8a63ff;
            --primary-dark: #6a4bcc;
            --accent: #ff7e9d;
            --text: #2d3748;
            --light: #f7fafc;
            --gray: #e2e8f0;
            --dark-gray: #a0aec0;
            --success: #38a169;
            --error: #e53e3e;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: var(--text);
            line-height: 1.6;
        }
        
        .profile-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .profile-card {
            display: grid;
            grid-template-columns: 300px 1fr;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .profile-sidebar {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 40px;
            text-align: center;
            position: relative;
        }
        
        .profile-main {
            padding: 40px;
        }
        
        .profile-img-container {
            margin-bottom: 30px;
            position: relative;
        }
        
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .edit-photo-btn {
            position: absolute;
            bottom: 10px;
            right: 30px;
            background: var(--accent);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
        }
        
        .edit-photo-btn:hover {
            transform: scale(1.1);
        }
        
        .user-name {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .user-role {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            margin-bottom: 30px;
        }
        
        .sidebar-menu {
            text-align: left;
            margin-top: 30px;
        }
        
        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            color: white;
            text-decoration: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s;
        }
        
        .menu-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .menu-item:hover {
            color: var(--accent);
            padding-left: 10px;
        }
        
        .menu-item.active {
            color: var(--accent);
            font-weight: 500;
        }
        
        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--accent);
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .info-item {
            margin-bottom: 20px;
        }
        
        .info-label {
            font-size: 14px;
            color: var(--dark-gray);
            margin-bottom: 5px;
        }
        
        .info-value {
            font-weight: 500;
            padding: 10px 15px;
            background: var(--light);
            border-radius: 8px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid var(--gray);
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(138, 99, 255, 0.1);
        }
        
        .btn {
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .btn-accent {
            background: var(--accent);
            color: white;
        }
        
        .btn-accent:hover {
            background: #ff6b8b;
            transform: translateY(-2px);
        }
        
        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-top: 15px;
            font-size: 14px;
        }
        
        .alert-success {
            background: rgba(56, 161, 105, 0.1);
            color: var(--success);
            border-left: 4px solid var(--success);
        }
        
        .alert-error {
            background: rgba(229, 62, 62, 0.1);
            color: var(--error);
            border-left: 4px solid var(--error);
        }
        
        .hidden {
            display: none;
        }
        
        .file-input {
            display: none;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (max-width: 768px) {
            .profile-card {
                grid-template-columns: 1fr;
            }
            
            .profile-sidebar {
                padding: 30px 20px;
            }
            
            .profile-main {
                padding: 30px 20px;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="profile-container">
    <div class="profile-card">
        <div class="profile-sidebar">
            <div class="profile-img-container">
                <!-- <img src="uploads/<?= htmlspecialchars($user['profile_photo'] ?? 'default.png') ?>" class="profile-img" alt="Profile Photo">
                <button class="edit-photo-btn" onclick="document.getElementById('fileInput').click()">
                    <i class="fas fa-camera"></i> -->
                </button>
            </div>
            
            <h3 class="user-name"><?= htmlspecialchars($user['name']) ?></h3>
            <div class="user-role">
                <?= htmlspecialchars(ucfirst($user['user_type'])) ?>
                <?php if ($user['user_type'] === 'admin'): ?>
                    <i class="fas fa-star ms-2" style="color: var(--accent);"></i>
                <?php endif; ?>
            </div>
            
            <div class="sidebar-menu">
                <a href="#" class="menu-item active" data-tab="account">
                    <i class="fas fa-user"></i> Account
                </a>
                <a href="#" class="menu-item" data-tab="security">
                    <i class="fas fa-lock"></i> Security
                </a>
                <a href="#" class="menu-item" data-tab="orders">
                    <i class="fas fa-shopping-bag"></i> My Orders
                </a>
                <a href="#" class="menu-item" data-tab="wishlist">
                    <i class="fas fa-heart"></i> Wishlist
                </a>
                <a href="logout.php" class="menu-item">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
        
        <div class="profile-main">
            <!-- Account Tab -->
            <div id="account" class="tab-content active">
                <h3 class="section-title">Account Information</h3>
                
                <form method="POST" enctype="multipart/form-data" id="photoForm">
                    <input type="file" id="fileInput" name="profile_photo" class="file-input" accept="image/*">
                </form>
                
                <?php if (!empty($photo_error)): ?>
                    <div class="alert alert-error"><?= $photo_error ?></div>
                <?php endif; ?>
                
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Full Name</div>
                        <div class="info-value"><?= htmlspecialchars($user['name']) ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Email Address</div>
                        <div class="info-value"><?= htmlspecialchars($user['email']) ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Account Type</div>
                        <div class="info-value">
                            <?= htmlspecialchars(ucfirst($user['user_type'])) ?>
                            <?php if ($user['user_type'] === 'admin'): ?>
                                <span class="badge" style="background: var(--accent); color: white; margin-left: 10px;">Admin</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Member Since</div>
                        <div class="info-value"><?= date('F j, Y', strtotime($user['created_at'])) ?></div>
                    </div>
                </div>
                
                <button class="btn btn-primary mt-3" onclick="document.getElementById('fileInput').click()">
                    <i class="fas fa-camera me-2"></i> Update Profile Photo
                </button>
            </div>
            
            <!-- Security Tab -->
            <div id="security" class="tab-content">
                <h3 class="section-title">Change Password</h3>
                
                <form method="POST">
                    <div class="form-group">
                        <label for="current_password" class="info-label">Current Password</label>
                        <input type="password" id="current_password" name="current_password" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password" class="info-label">New Password</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" required>
                    </div>
                    
                    <button class="btn btn-accent" name="change_password">
                        <i class="fas fa-key me-2"></i> Update Password
                    </button>
                </form>
                
                <?php if (!empty($password_error)): ?>
                    <div class="alert alert-error"><?= $password_error ?></div>
                <?php endif; ?>
                
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>
            </div>
            
            <!-- Orders Tab -->
            <div id="orders" class="tab-content">
                <h3 class="section-title">My Orders</h3>
                <p>Your order history will appear here.</p>
                <!-- Order history content would go here -->
            </div>
            
            <!-- Wishlist Tab -->
            <div id="wishlist" class="tab-content">
                <h3 class="section-title">My Wishlist</h3>
                <p>Your saved items will appear here.</p>
                <!-- Wishlist content would go here -->
            </div>
        </div>
    </div>
</div>

<script>
    // Tab switching functionality
    document.querySelectorAll('.menu-item').forEach(item => {
        if(!item.getAttribute('href') || item.getAttribute('href') === '#') {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const tabId = this.getAttribute('data-tab');
                
                // Update active tab
                document.querySelectorAll('.menu-item').forEach(i => {
                    i.classList.remove('active');
                });
                this.classList.add('active');
                
                // Show corresponding content
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                });
                document.getElementById(tabId).classList.add('active');
            });
        }
    });
    
    // Auto-submit photo form when file is selected
    document.getElementById('fileInput').addEventListener('change', function() {
        document.getElementById('photoForm').submit();
    });
</script>
</body>
</html>