<?php
// session_start(); // Start the session
include 'db.php';

// Initialize variables
$logged_in = isset($_SESSION['user_id']) || (isset($_SESSION['logged_in']) && $_SESSION['logged_in']);
$user_id = $logged_in ? ($_SESSION['user_id'] ?? $_SESSION['id'] ?? null) : null;
$name = $logged_in ? $_SESSION['name'] : '';
$total_cart_items = 0;

// Fetch user data if logged in
if ($logged_in) {
    try {
        $stmt = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Get cart count
        $stmt = $conn->prepare("SELECT COUNT(*) FROM `cart` WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $total_cart_items = $stmt->fetchColumn();
    } catch(PDOException $e) {
        error_log("Database error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FOOD</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/style1.css">
    <style>
        /* Improved dropdown styling */
        .profile-dropdown {
            display: none;
            position: absolute;
            right: 20px;
            top: 60px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            width: 250px;
            z-index: 1000;
        }
        .profile-dropdown.active {
            display: block;
        }
        .profile-dropdown p {
            margin: 0 0 15px;
            font-size: 16px;
            color: #333;
        }
        .profile-dropdown .btn {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            text-align: center;
            border-radius: 5px;
            font-size: 14px;
            transition: all 0.3s;
        }
        .profile-dropdown .btn-primary {
            background: #27ae60;
            color: white;
        }
        .profile-dropdown .btn-danger {
            background: #e74c3c;
            color: white;
        }
        .profile-dropdown .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <a href="index.php" class="logo"><i class="fas fa-utensils"></i> Food</a>
        <nav class="navbar">
            <a class="active" href="index.php#home">Home</a>
            <a href="#about">About</a>
            <a href="#review">Reviews</a>
            <a href="menu.php">Menu</a>
            <!-- <a href="restaurant.php">Restaurant</a> -->
            <a href="order.php">Order</a>
        </nav>
        <div class="icons">
            <i class="fas fa-bars" id="menu-bars"></i>
            <a href="search.php"><i class="fas fa-search" id="search-icon"></i></a>
            <!-- <a href="#" class="fas fa-heart"></a> -->
            <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_items; ?>)</span></a>
            <i id="user-btn" class="fas fa-user"></i>
        </div>

        <!-- Profile Dropdown -->
        <div class="profile-dropdown" id="profileDropdown">
            <?php if ($logged_in): ?>
                <p>Welcome, <?= htmlspecialchars($name) ?></p>
                <a href="profile.php" class="btn btn-primary">Profile</a>
                <a href="Components/logout.php" class="btn btn-danger">Logout</a>
            <?php else: ?>
                <p>Please login to first</p>
                <a href="login.php" class="btn btn-primary">Login</a>
                <a href="register.php" class="btn btn-primary">Register</a>
            <?php endif; ?>
        </div>
    </header>

    <script>
        // Improved dropdown toggle
        document.getElementById('user-btn').addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('profileDropdown').classList.toggle('active');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            document.getElementById('profileDropdown').classList.remove('active');
        });
    </script>

    <!-- Search Form -->
    <form action="" id="search-form">
        <input type="search" placeholder="Search here..." name="" id="search-box">
        <label for="search-box" class="fas fa-search"></label>
        <i class="fas fa-times" id="close"></i>
    </form>

    <!-- Scripts -->
    <script>
        // Toggle profile dropdown
        document.getElementById('user-btn').onclick = () => {
            document.querySelector('.profile').classList.toggle('active');
        };

        // Close search form
        document.getElementById('close').onclick = () => {
            document.getElementById('search-form').style.display = 'none';
        };

        Open search form
        document.getElementById('search-icon').onclick = () => {
            document.getElementById('search-form').style.display = 'flex';
        };
    </script>
</body>
</html>