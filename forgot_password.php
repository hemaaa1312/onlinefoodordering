<?php
putenv('ENVIRONMENT=development');
session_start();
include 'db.php';

// Development mail function
function dev_mail($to, $subject, $message, $headers) {
    $log = "To: $to\nSubject: $subject\nHeaders: $headers\nMessage:\n$message\n\n";
    try {
        if (file_put_contents('mail_log.txt', $log, FILE_APPEND) === false) {
            error_log("Failed to write to mail log");
            return false;
        }
        return true;
    } catch (Exception $e) {
        error_log("Mail log error: " . $e->getMessage());
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    // Validate email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address";
    } else {
        try {
            // Check if the email exists in the database
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Generate a unique token for password reset
                $token = bin2hex(random_bytes(32));
                $expires = date("Y-m-d H:i:s", time() + 3600); // Token expires in 1 hour

                // Store the token in the database
                $stmt = $conn->prepare("UPDATE users SET reset_token = :token, reset_token_expires = :expires WHERE email = :email");
                $stmt->bindParam(':token', $token);
                $stmt->bindParam(':expires', $expires);
                $stmt->bindParam(':email', $email);
                
                if ($stmt->execute()) {
                    // Send the password reset link via email
                    $reset_link = "http://" . $_SERVER['HTTP_HOST'] . "/reset_password.php?token=" . urlencode($token);
                    $subject = "Password Reset Request";
                    $message = "Click the link below to reset your password:\n\n$reset_link\n\n";
                    $message .= "This link will expire in 1 hour.\n";
                    $message .= "If you didn't request this, please ignore this email.";
                    $headers = "From: no-reply@" . $_SERVER['HTTP_HOST'] . "\r\n";
                    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

                    // Use development mailer in development environment
                    if (getenv('ENVIRONMENT') === 'development') {
                        dev_mail($email, $subject, $message, $headers);
                        $success = "A password reset link has been generated. (Development mode - no email sent)";
                    } else {
                        if (mail($email, $subject, $message, $headers)) {
                            $success = "A password reset link has been sent to your email.";
                        } else {
                            $error = "Failed to send the password reset email. Please try again later.";
                        }
                    }
                } else {
                    $error = "Failed to generate reset token. Please try again.";
                }
            } else {
                $error = "No account found with that email address.";
            }
        } catch(PDOException $e) {
            error_log("Password reset error: " . $e->getMessage());
            $error = "An error occurred. Please try again later.";
        }
    }
}
?>

<?php include 'Components/header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
        }
        .forgot-password-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }
        .forgot-password-form {
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .forgot-password-form h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #2c3e50;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .btn-reset {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-reset:hover {
            background-color: #2980b9;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
        }
        .error {
            background-color: #fde8e8;
            color: #e74c3c;
        }
        .success {
            background-color: #e8f8f0;
            color: #27ae60;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            color: #3498db;
            text-decoration: none;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="forgot-password-container">
        <div class="forgot-password-form">
            <h2>Forgot Password</h2>
            
            <?php if (isset($error)): ?>
                <div class="message error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <?php if (isset($success)): ?>
                <div class="message success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            
            <form action="forgot_password.php" method="POST">
                <div class="form-group">
                    <input type="email" name="email" placeholder="Enter your email address" required>
                </div>
                <button type="submit" class="btn-reset">Send Reset Link</button>
            </form>
            
            <div class="back-link">
                <a href="login.php">Back to Login</a>
            </div>
        </div>
    </div>
</body>
</html>