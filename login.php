<?php
require_once 'config.php'; // Make sure this file correctly sets up your database connection ($conn)
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $error = ''; // Initialize error message

    // Validate inputs
    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        // Check user credentials
        // Using prepared statements to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, name, email, password, user_type FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify password against the hashed password in the database
            if (password_verify($password, $user['password'])) {
                // Password is correct, set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_type'] = $user['user_type'];
                $_SESSION['logged_in'] = true;

                // Redirect based on user type
                if ($user['user_type'] === 'Admin') {
                    header('Location: admin_dashboard.php');
                    exit; // Always exit after a header redirect
                } else {
                    // Default redirection for non-admin users (customers)
                    header('Location: displayproduct.php');
                    exit; // Always exit after a header redirect
                }
            } else {
                // Incorrect password
                $error = 'Invalid email or password.';
            }
        } else {
            // User not found (email doesn't exist)
            $error = 'Invalid email or password.';
        }
        $stmt->close(); // Close the prepared statement
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Velvet Vogue</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color:rgb(106, 20, 20);
            --primary-dark:rgb(117, 22, 22);
            --text-color: #2d3748;
            --light-gray: #f7fafc;
            --gray: #e2e8f0;
            --dark-gray: #a0aec0;
            --error-color:rgb(183, 37, 37);
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
        
        .login-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 440px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        
        .login-header {
            background: var(--primary-color);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .login-header h1 {
            font-weight: 600;
            font-size: 28px;
            margin-bottom: 5px;
        }
        
        .login-header p {
            opacity: 0.9;
            font-weight: 300;
        }
        
        .login-form {
            padding: 40px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 14px;
        }
        
        .form-control {
            width: 100%;
            padding: 14px 16px;
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
        
        .login-footer {
            text-align: center;
            padding: 20px;
            border-top: 1px solid var(--gray);
            font-size: 14px;
        }
        
        .login-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
        
        .password-container {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--dark-gray);
        }
        
        @media (max-width: 480px) {
            .login-form {
                padding: 30px 20px;
            }
            
            .login-header {
                padding: 25px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Welcome Back</h1>
            <p>Sign in to your Velvet Vogue account</p>
        </div>
        
        <div class="login-form">
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php // The $success variable is not being set in the PHP logic, so this block will never display.
                  // If you intend to show a success message (e.g., after registration), you'd need to set $_SESSION['success_message']
                  // and then display it here, clearing the session variable after display.
            if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" required 
                           value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" class="form-control" required>
                        <span class="toggle-password" onclick="togglePassword()">👁️</span>
                    </div>
                </div>
                
                <button type="submit" class="btn">Sign In</button>
            </form>
        </div>
        
        <div class="login-footer">
            Don't have an account? <a href="registration.php">Create one</a>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
        }
    </script>
</body>
</html>