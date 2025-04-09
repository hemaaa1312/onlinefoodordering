<?php
// Start the session at the beginning of the script
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    // Redirect to the login page if not logged in
    header('Location: admin_login.php');
    exit;
}

include '../db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

<!-- custom css file link  -->
<link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
<?php include '../Components/admin_header.php' ?>

<section class="dashboard">

<h1 class="heading">dashboard</h1>

<div class="box-container">

<!-- <div class="box">
   <h3>welcome!</h3> -->
   <!-- <p></p>
   <a href="update_profile.php" class="btn">update profile</a>
</div> -->

<div class="box">
   <?php
      $total_pendings = 0;
      $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
      $select_pendings->execute(['pending']);
      while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
         $total_pendings += $fetch_pendings['total_price'];
      }
   ?>
   <h3><span>₹</span><?= $total_pendings; ?><span>/-</span></h3>
   <p>total pendings</p>
   <a href="placed_orders.php" class="btn">see orders</a>
</div>

<div class="box">
   <?php
      $total_completes = 0;
      $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
      $select_completes->execute(['completed']);
      while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
         $total_completes += $fetch_completes['total_price'];
      }
   ?>
   <h3><span>₹</span><?= $total_completes; ?><span>/-</span></h3>
   <p>total completes</p>
   <a href="placed_orders.php" class="btn">see orders</a>
</div>

<div class="box">
   <?php
      $select_orders = $conn->prepare("SELECT * FROM `orders`");
      $select_orders->execute();
      $numbers_of_orders = $select_orders->rowCount();
   ?>
   <h3><?= $numbers_of_orders; ?></h3>
   <p>total orders</p>
   <a href="placed_orders.php" class="btn">see orders</a>
</div>

<div class="box">
   <?php
      $select_products = $conn->prepare("SELECT * FROM `dishes`");
      $select_products->execute();
      $numbers_of_products = $select_products->rowCount();
   ?>
   <h3><?= $numbers_of_products; ?></h3>
   <p>products added</p>
   <a href="product.php" class="btn">see products</a>
</div>


<div class="box">
   <?php
      $select_users = $conn->prepare("SELECT * FROM `users`");
      $select_users->execute();
      $numbers_of_users = $select_users->rowCount();
   ?>
   <h3><?= $numbers_of_users; ?></h3>
   <p>users accounts</p>
   <a href="users_accounts.php" class="btn">see users</a>
</div>

<div class="box">
   <?php
      $select_admins = $conn->prepare("SELECT * FROM `admins`");
      $select_admins->execute();
      $numbers_of_admins = $select_admins->rowCount();
   ?>
   <h3><?= $numbers_of_admins; ?></h3>
   <p>admins</p>
   <a href="admin_acounts.php" class="btn">see admins</a>
</div>



</div>

</section>

<!-- admin dashboard section ends -->









<!-- custom js file link  -->
<script src="../js/admin_script1.js"></script>

</body>
</html>