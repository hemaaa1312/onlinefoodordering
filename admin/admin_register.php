<?php
// Start the session at the beginning of the script
session_start();

include '../db.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Basic validation
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Invalid email format.';
    } elseif ($password !== $confirm_password) {
        $error_message = 'Passwords do not match.';
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT * FROM admins WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->rowCount() > 0) {
            $error_message = 'Email already exists.';
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new admin into the database
            $stmt = $conn->prepare("INSERT INTO admins (name, email, password) VALUES (:name, :email, :password)");
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'password' => $hashed_password
            ]);

            // Redirect to login page after successful registration
            header('Location: admin_login.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
<?php include '../Components/admin_header.php' ?>
    <div class="admin-login">
        <h2>Sign Up</h2>
        <?php if (!empty($error_message)): ?>
            <div class="error"><?= htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form action="admin_register.php" method="POST">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Sign Up</button>
        </form>
        <div class="login-link">
            Already have an account? <a href="admin_login.php">Login here</a>
        </div>
    </div>
   
</body>
</html>