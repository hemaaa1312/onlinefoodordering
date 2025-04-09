<?php
include 'db.php';
session_start();

// Initialize variables
$fetch_profile = [];
$user_id = '';
$message = [];

// Check if user is logged in
if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
   
   // Fetch user profile data
   $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
   $select_profile->execute([$user_id]);
   $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
   
   // Initialize empty values if profile not found
   if(!$fetch_profile) {
       $fetch_profile = [
           'name' => '',
           'email' => '',
           'address' => '',
           'number' => ''
       ];
   }
}else{
   header('location:home.php');
   exit(); // Always exit after header redirect
}

if(isset($_POST['submit'])){
   $name = $_POST['name'];
   $email = $_POST['email'];
   $address = $_POST['address'];
   $method = $_POST['method'];
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];
   
   // Get phone number from profile
   $number = $fetch_profile['number'];
   
   // Check if cart has items
   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);
   
   if($check_cart->rowCount() > 0){
      // Insert order
      $insert_order = $conn->prepare("INSERT INTO `orders` 
         (user_id, name, email, number, address, total_products, total_price, method, placed_on) 
         VALUES (?,?,?,?,?,?,?,?, NOW())");
      $insert_order->execute([
         $user_id,
         $name,
         $email,
         $number,
         $address,
         $total_products,
         $total_price,
         $method
      ]);
      
      // Delete cart items
      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);
      
      $message[] = 'Order placed successfully!';
   }else{
      $message[] = 'Your cart is empty!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <!-- Keep existing head section -->
</head>
<body>
   
<!-- header section starts  -->
<?php include 'Components/header.php'; ?>
<!-- header section ends -->
<div><br><br><br><br></div>
<div class="heading">
   <h3>checkout</h3>
   <p><a href="index.php">home</a> <span> / checkout</span></p>
</div>

<section class="checkout">

   <h1 class="title">order summary</h1>

<form action="" method="post">

   <div class="cart-items">
      <h3>cart items</h3>
      <?php
         $grand_total = 0;
         $cart_items = []; // Initialize as array, not string
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
               $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].') - ';
               $total_products = implode('', $cart_items); // Changed to empty string delimiter
               $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
      ?>
      <p><span class="name"><?= htmlspecialchars($fetch_cart['name']); ?></span><span class="price">₹<?= htmlspecialchars($fetch_cart['price']); ?> x <?= htmlspecialchars($fetch_cart['quantity']); ?></span></p>
      <?php
            }
         }else{
            echo '<p class="empty">your cart is empty!</p>';
         }
      ?>
      <p class="grand-total"><span class="name">grand total :</span><span class="price">₹<?= $grand_total; ?></span></p>
      <a href="cart.php" class="btn">veiw cart</a>
   </div>

   <input type="hidden" name="total_products" value="<?= isset($total_products) ? htmlspecialchars($total_products) : ''; ?>">
   <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
   <input type="hidden" name="name" value="<?= htmlspecialchars($fetch_profile['name']); ?>">
   <input type="hidden" name="email" value="<?= htmlspecialchars($fetch_profile['email']); ?>">
   <input type="hidden" name="address" value="<?= htmlspecialchars($fetch_profile['address']); ?>">

   <div class="user-info">
      <h3>your info</h3>
      <p><i class="fas fa-user"></i><span><?= htmlspecialchars($fetch_profile['name']); ?></span></p>
      <p><i class="fas fa-envelope"></i><span><?= htmlspecialchars($fetch_profile['email']); ?></span></p>
      <a href="update_profile.php" class="btn">update info</a>
      <h3>delivery address</h3>
      <p><i class="fas fa-map-marker-alt"></i><span>
         <?php 
         if(empty($fetch_profile['address'])){
            echo 'please enter your address';
         }else{
            echo htmlspecialchars($fetch_profile['address']);
         } 
         ?>
      </span></p>
      <a href="update_address.php" class="btn">update address</a>
      <select name="method" class="box" required>
         <option value="" disabled selected>select payment method --</option>
         <option value="cash on delivery">cash on delivery</option>
         <option value="credit card">credit card</option>
         <option value="paytm">paytm</option>
         <option value="paypal">paypal</option>
      </select>
      <input type="submit" value="place order" class="btn <?= empty($fetch_profile['address']) ? 'disabled' : ''; ?>" style="width:100%; background:var(--red); color:var(--white);" name="submit" <?= empty($fetch_profile['address']) ? 'disabled' : ''; ?>>
   </div>

</form>
   
</section>

<?php include 'Components/footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>