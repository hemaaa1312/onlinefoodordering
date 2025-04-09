<?php
include 'db.php';
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
   
   // Fetch user profile data from database
   $stmt = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
   $stmt->execute([$user_id]);
   $fetch_profile = $stmt->fetch(PDO::FETCH_ASSOC);
   
   // Check if user exists
   if(!$fetch_profile) {
      header('location:index.php');
      exit();
   }
}else{
   $user_id = '';
   header('location:index.php');
   exit();
};
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>profile</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style1.css">
</head>
<?php include 'Components/header.php'; ?>
<body>
   
<div><div></div></div>
<div><br><br></div>
<div><br><br></div><div><br><br></div><div></div>
<section class="user-details">
   <div class="user">
   <img src="images/user-icon.png" alt="">
      <p><i class="fas fa-user"></i><span><span><?= $fetch_profile['name']; ?></span></span></p>
      <p><i class="fas fa-phone"></i><span><?= $fetch_profile['number']; ?></span></p>
      <p><i class="fas fa-envelope"></i><span><?= $fetch_profile['email']; ?></span></p>
      <a href="update_profile.php" class="btn">update info</a>
      <p class="address"><i class="fas fa-map-marker-alt"></i>
         <span>
            <?php 
            if(empty($fetch_profile['address'] ?? '')) {
               echo 'please enter your address';
            } else {
               echo htmlspecialchars($fetch_profile['address'] ?? '');
            } 
            ?>
         </span>
      </p>
      <a href="update_address.php" class="btn">update address</a>
   </div>
</section>
<?php include 'Components/footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>