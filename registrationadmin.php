<?php
// PHP code must be at the very top, before any HTML output
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "velvet_db";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Create DB connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check DB connection
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // Safely extract and validate POST data
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $confirm = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
        $user_type = "Admin";

        // Basic validation
        if (empty($name) || empty($email) || empty($password) || empty($confirm)) {
            throw new Exception("All fields are required.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }

        if (strlen($password) < 8) {
            throw new Exception("Password must be at least 8 characters long.");
        }

        if ($password !== $confirm) {
            throw new Exception("Passwords do not match.");
        }

        // Check if email already exists
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            throw new Exception("This email is already registered.");
        }

        // Insert new user
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $hashed_password, $user_type);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Registration successful! Welcome to Velvet Vogue!";
            unset($_SESSION['form_data']);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            throw new Exception("Error: " . $stmt->error);
        }

        $stmt->close();
        $check->close();
        $conn->close();
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        $_SESSION['form_data'] = [
            'name' => $name ?? '',
            'email' => $email ?? ''
        ];
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Velvet Vogue</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color:rgb(152, 43, 43);
            --primary-dark:rgb(150, 57, 57);
            --text-color: #2d3748;
            --light-gray: #f7fafc;
            --gray: #e2e8f0;
            --dark-gray: #a0aec0;
            --error-color: #e53e3e;
            --success-color: #38a169;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-gray);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            background-image: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
        }
        
        .register-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 440px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        
        .register-header {
            background: var(--primary-color);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .register-header h1 {
            font-weight: 600;
            font-size: 28px;
            margin-bottom: 5px;
        }
        
        .register-header p {
            opacity: 0.9;
            font-weight: 300;
        }
        
        .register-form {
            padding: 40px;
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 14px;
        }
        
        .form-control {
            width: 100%;
            padding: 14px 16px 14px 45px;
            border: 2px solid var(--gray);
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(138, 99, 255, 0.2);
        }
        
        .form-group i {
            position: absolute;
            left: 15px;
            top: 40px;
            color: var(--primary-color);
            font-size: 16px;
        }
        
        .btn {
            width: 100%;
            padding: 14px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn:hover {
            background: var(--primary-dark);
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 14px;
        }
        
        .alert-error {
            background: #fff5f5;
            color: var(--error-color);
            border-left: 4px solid var(--error-color);
        }
        
        .alert-success {
            background: #f0fff4;
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }
        
        .register-footer {
            text-align: center;
            padding: 20px;
            border-top: 1px solid var(--gray);
            font-size: 14px;
        }
        
        .register-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .register-footer a:hover {
            text-decoration: underline;
        }
        
        .password-container {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 40px;
            cursor: pointer;
            color: var(--dark-gray);
        }
        
        .input-error {
            color: var(--error-color);
            font-size: 13px;
            margin-top: 5px;
            display: none;
        }
        
        .error-border {
            border-color: var(--error-color) !important;
        }
        
        @media (max-width: 480px) {
            .register-form {
                padding: 30px 20px;
            }
            
            .register-header {
                padding: 25px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h1>Create Your Account</h1>
            <p>Join the Velvet Vogue community</p>
        </div>
        
        <div class="register-form">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <form method="POST" id="registerForm">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <i class="fas fa-user"></i>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter your full name" required 
                           value="<?php echo isset($_SESSION['form_data']['name']) ? htmlspecialchars($_SESSION['form_data']['name']) : ''; ?>">
                    <div class="input-error" id="name-error"></div>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required 
                           value="<?php echo isset($_SESSION['form_data']['email']) ? htmlspecialchars($_SESSION['form_data']['email']) : ''; ?>">
                    <div class="input-error" id="email-error"></div>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Create a password" required>
                    <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                    <div class="input-error" id="password-error"></div>
                </div>
                
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <i class="fas fa-lock"></i>
                    <input type="password" id="confirmPassword" name="confirm_password" class="form-control" placeholder="Confirm your password" required>
                    <i class="fas fa-eye toggle-password" id="toggleConfirmPassword"></i>
                    <div class="input-error" id="confirm-error"></div>
                </div>
                
                <input type="hidden" name="user_type" value="Admin">
                
                <button type="submit" class="btn">
                    <i class="fas fa-user-plus"></i> Register Now
                </button>
            </form>
        </div>
        
        <div class="register-footer">
            Already have an account? <a href="login.php">Sign in here</a>
        </div>
    </div>

    <script>
        // Password toggle functionality
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const confirmPassword = document.getElementById('confirmPassword');
            const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPassword.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });

        // Form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            let isValid = true;
            
            // Clear previous errors
            document.querySelectorAll('.input-error').forEach(el => {
                el.style.display = 'none';
                el.textContent = '';
            });
            
            document.querySelectorAll('input').forEach(input => {
                input.classList.remove('error-border');
            });
            
            // Validate name
            const name = document.querySelector('input[name="name"]').value.trim();
            if (!name) {
                showError('name-error', 'Name is required', 'name');
                isValid = false;
            }
            
            // Validate email
            const email = document.querySelector('input[name="email"]').value.trim();
            if (!email) {
                showError('email-error', 'Email is required', 'email');
                isValid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showError('email-error', 'Please enter a valid email', 'email');
                isValid = false;
            }
            
            // Validate password
            const password = document.getElementById('password').value;
            if (!password) {
                showError('password-error', 'Password is required', 'password');
                isValid = false;
            } else if (password.length < 8) {
                showError('password-error', 'Password must be at least 8 characters', 'password');
                isValid = false;
            }
            
            // Validate confirm password
            const confirmPassword = document.getElementById('confirmPassword').value;
            if (!confirmPassword) {
                showError('confirm-error', 'Please confirm your password', 'confirm_password');
                isValid = false;
            } else if (password !== confirmPassword) {
                showError('confirm-error', 'Passwords do not match', 'confirm_password');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });

        function showError(elementId, message, inputName) {
            const errorElement = document.getElementById(elementId);
            const inputElement = document.querySelector(`input[name="${inputName}"]`) || 
                                document.getElementById(inputName);
            
            errorElement.textContent = message;
            errorElement.style.display = 'block';
            inputElement.classList.add('error-border');
        }
    </script>
</body>
</html>