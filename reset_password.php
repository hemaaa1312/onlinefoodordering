<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate passwords
    if ($new_password !== $confirm_password) {
        echo "Passwords do not match.";
        exit();
    }

    // Check if the token is valid and not expired
    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = :token AND reset_token_expires > NOW()");
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the user's password and clear the reset token
        $stmt = $conn->prepare("UPDATE users SET password = :password, reset_token = NULL, reset_token_expires = NULL WHERE id = :id");
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':id', $user['id']);
        $stmt->execute();

        echo "Password reset successfully. <a href='login.php'>Login here</a>.";
    } else {
        echo "Invalid or expired token.";
    }
} else {
    $token = $_GET['token'] ?? null;

    if (!$token) {
        echo "Invalid token.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .reset-password-form {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        .reset-password-form h2 {
            margin-bottom: 20px;
        }
        .reset-password-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .reset-password-form button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .reset-password-form button:hover {
            background-color: #0056b3;
        }
        .reset-password-form p {
            margin-top: 10px;
        }
        .reset-password-form a {
            color: #007bff;
            text-decoration: none;
        }
        .reset-password-form a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="reset-password-form">
        <h2>Reset Password</h2>
        <form action="reset_password.php" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <input type="password" name="new_password" placeholder="New Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
<?php include 'Components/footer.php'; ?>