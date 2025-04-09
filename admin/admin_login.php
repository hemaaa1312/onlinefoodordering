<?php
// Start the session at the beginning of the script
session_start();

include '../db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = trim($_POST['login']); // This can be either email or name
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($login) || empty($password)) {
        $error = 'Both login (email/name) and password are required.';
    } else {
        // Check if the login input matches either email or name in the database
        $stmt = $conn->prepare("SELECT * FROM admins WHERE email = :login OR name = :login");
        $stmt->execute(['login' => $login]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password'])) {
            // Store admin data in the session
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['admin_name'] = $admin['name']; // Optionally store the admin's name

            // Redirect to the admin dashboard
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid login (email/name) or password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
<?php include '../Components/admin_header.php' ?>
    <div class="admin-login">
        <h2>Admin Login</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form action="admin_login.php" method="POST">
            <input type="text" name="login" placeholder="Email or Name" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <p>Don't have an account? <a href="admin_register.php">Signup here</a></p>
        </form>
    </div>
    
</body>
</html>