
<?php
$conn = mysqli_connect('localhost', 'root', '', 'velvet');

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}
?>

<?php
// session_start();
// require_once 'config.php';

// Redirect if already logged in
if (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'Admin') {
    header("Location: admin_dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    try {
        // Validate inputs
        if (empty($email) || empty($password)) {
            throw new Exception("Please enter both email and password");
        }

        // Check admin credentials
        $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ? AND user_type = 'Admin'");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $admin['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $admin['id'];
            
                $_SESSION['user_email'] = $admin['email'];
                $_SESSION['user_type'] = 'Admin';
                $_SESSION['logged_in'] = true;
                
                // Redirect to admin dashboard
                header("Location: admin_dashboard.php");
                exit();
            } else {
                throw new Exception("Invalid email or password");
            }
        } else {
            throw new Exception("Invalid email or password");
        }
        
        $stmt->close();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Velvet Vogue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color:rgb(117, 22, 22);
            --secondary-color: #f8f9fa;
            --accent-color: #e83e8c;
            --text-color: #333;
            --light-text: #6c757d;
            --border-color: #dee2e6;
            --danger-color: #dc3545;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        
        .login-container {
            width: 100%;
            max-width: 450px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .login-header {
            background-color: var(--primary-color);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .login-header h2 {
            margin-bottom: 10px;
        }
        
        .login-header img {
            width: 80px;
            margin-bottom: 15px;
        }
        
        .login-body {
            padding: 30px;
        }
        
        .form-control {
            height: 50px;
            border-radius: 5px;
            border: 1px solid var(--border-color);
            padding-left: 15px;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(12, 6, 104, 0.25);
        }
        
        .input-group-text {
            background-color: white;
            border-right: none;
        }
        
        .input-group .form-control {
            border-left: none;
        }
        
        .btn-login {
            background-color: var(--primary-color);
            color: white;
            border: none;
            height: 50px;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            background-color:rgb(125, 25, 25);
        }
        
        .error-message {
            color: var(--danger-color);
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        
        .error-message i {
            margin-right: 10px;
        }
        
        .forgot-password {
            text-align: right;
            margin-top: 10px;
        }
        
        .forgot-password a {
            color: var(--light-text);
            text-decoration: none;
            font-size: 14px;
        }
        
        .forgot-password a:hover {
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            
            <h2>Admin Portal</h2>
            <p>Access your administration dashboard</p>
        </div>
        
        <div class="login-body">
            <?php if (!empty($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                               required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="forgot-password">
                        <a href="forgot_password.php">Forgot Password?</a>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-login">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple client-side validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            
            if (!email || !password) {
                e.preventDefault();
                alert('Please fill in both email and password');
            }
        });
    </script>
</body>
</html>