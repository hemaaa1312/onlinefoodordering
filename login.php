<?php
session_start();
include 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email_or_name = trim($_POST['email_or_name']);
    $password = $_POST['password'];

    // Validate inputs
    if (empty($email_or_name) || empty($password)) {
        $error = 'Both email/username and password are required!';
    } else {
        try {
            // Check if the input is an email or name
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email_or_name OR name = :email_or_name");
            $stmt->bindParam(':email_or_name', $email_or_name);
            $stmt->execute();
            
            if ($stmt->rowCount() == 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (password_verify($password, $user['password'])) {
                    // Set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['email'] = $user['email'];
                    
                    // Regenerate session ID for security
                    session_regenerate_id(true);
                    
                    // Redirect to home page
                    header("Location: index.php");
                    exit();
                } else {
                    $error = 'Invalid password!';
                }
            } else {
                $error = 'Account not found!';
            }
        } catch(PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            $error = 'Login failed. Please try again later.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Food Order System</title>
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        input {
            text-transform: none !important;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }
        
        .login-form {
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .login-form:hover {
            transform: translateY(-5px);
        }
        
        .login-form h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #2c3e50;
            font-size: 28px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #34495e;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            border-color: #3498db;
            outline: none;
        }
        
        .btn-login {
            width: 100%;
            padding: 12px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }
        
        .btn-login:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .error {
            color: #e74c3c;
            margin-bottom: 20px;
            text-align: center;
            padding: 10px;
            background: #fde8e8;
            border-radius: 6px;
            font-weight: 500;
        }
        
        .links {
            text-align: center;
            margin-top: 25px;
        }
        
        .links a {
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
            margin: 0 10px;
            transition: color 0.3s;
        }
        
        .links a:hover {
            color: #2980b9;
            text-decoration: underline;
        }
       
    </style>
</head>
<body>
    <?php 
    // Only include header if it's not already included
    
        include 'Components/header.php';
    
    ?>
    
    <div class="login-container">
        <div class="login-form">
            <h2>Welcome Back!</h2>
            
            <?php if (!empty($error)): ?>
                <div class="error">
                    <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <form action="login.php" method="POST" autocomplete="off">
                <div class="form-group">
                    <label for="email_or_name">
                        <i class="fas fa-user"></i> Email or Username
                    </label>
                    <input 
                        type="text" 
                        id="email_or_name" 
                        name="email_or_name" 
                        placeholder="Enter your email or username" 
                        required
                        autofocus
                    >
                </div>
                
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Enter your password" 
                        required
                    >
                </div>
                
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
                
                <div class="links">
                    <a href="register.php">
                        <i class="fas fa-user-plus"></i> Create Account
                    </a>
                    <a href="forgot_password.php">
                        <i class="fas fa-key"></i> Forgot Password?
                    </a>
                </div>
            </form>
    </div>   
</body>
<!--  -->
</html>