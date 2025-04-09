<?php
session_start();
include 'db.php';

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'Components/add_cart.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food</title>
   <style>
    
.restaurant-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    padding: 20px;
}
.restaurant-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
.restaurant-img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}
.restaurant-info {
    padding: 15px;
}
.rating {
    color: #ffc107;
    font-weight: bold;
}
.view-btn {
    display: inline-block;
    padding: 8px 15px;
    background: #ff6b6b;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    margin-top: 10px;
}
   </style>
   
</head>
<body>
    <?php include 'Components/header.php'; ?>

    <!-- Home Section -->
    <section class="home" id="home">
        <div class="swiper-container home-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide slide">
                    <div class="content">
                        <span>Our Special Dish</span>
                        <h3>Spicy Pasta</h3>
                        <p>Pasta that is more tasty and delicious.</p>
                        <a href="menu.php" class="btn">See menu</a>
                    </div>
                    <div class="image">
                        <img src="images/vegpasta.png" alt="">
                    </div>
                </div>
                <div class="swiper-slide slide">
                    <div class="content">
                        <span>Our Special Dish</span>
                        <h3>Burger</h3>
                        <p>The best burger in the city.</p>
                        <a href="menu.php" class="btn">See menu</a>
                    </div>
                    <div class="image">
                        <img src="images/new burger.png" alt="">
                    </div>
                </div>
                <div class="swiper-slide slide">
                    <div class="content">
                        <span>Our Special Dish</span>
                        <h3>Pizza</h3>
                        <p>The best pizza in the city.</p>
                        <a href="menu.php" class="btn">See menu</a>
                    </div>
                    <div class="image">
                        <img src="images/home-img-3.png" alt="">
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>
    

    
  <section class="category">

   <h1 class="title">food category</h1>

   <div class="box-container">

      <a href="category.php?category=fast food" class="box">
         <img src="images/cat-1.png" alt="">
         <h3>fast food</h3>
      </a>

      <a href="category.php?category=main dish" class="box">
         <img src="images/cat-2.png" alt="">
         <h3>main dishes</h3>
      </a>

      <a href="category.php?category=drinks" class="box">
         <img src="images/cat-3.png" alt="">
         <h3>drinks</h3>
      </a>

      <a href="category.php?category=desserts" class="box">
         <img src="images/cat-4.png" alt="">
         <h3>desserts</h3>
      </a>

   </div>

</section>

<section class="products">

   <h1 class="title">latest dishes</h1>

   <div class="box-container">

      <?php
         $select_products = $conn->prepare("SELECT * FROM `dishes` LIMIT 6");
         $select_products->execute();
         if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
      ?>
      <form action="" method="post" class="box">
         <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
         <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
         <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
         <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
         <a href="quick_view.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
         <button type="submit" class="fas fa-shopping-cart" name="add_to_cart"></button>
         <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
         <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat"><?= $fetch_products['category']; ?></a>
         <div class="name"><?= $fetch_products['name']; ?></div>
         <div class="flex">
            <div class="price"><span>₹</span><?= $fetch_products['price']; ?></div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
         </div>
      </form>
      <?php
            }
         }else{
            echo '<p class="empty">no products added yet!</p>';
         }
      ?>

   </div>

   <div class="more-btn">
      <a href="menu.php" class="btn">veiw all</a>
   </div>
 </section>
<section>
 <h1>OUR RESTAURANTS</h1>

    <div class="restaurant-grid">
        <!-- Mumbai Restaurants -->
        
        <div class="restaurant-card">
            <img src="images/Bademiass.jpg" alt="Bademiya Restaurant" class="restaurant-img">
            <div class="restaurant-info">
                <h3>Bademiya Restaurant</h3>
                <p>Colaba Causeway, Mumbai</p>
                <p>Famous for its late-night kebabs and rolls</p>
                <span class="rating">★ 4.5</span>
                <a href="menu.php?id=1" class="view-btn">View Menu</a>
            </div>
        </div></a>

        <div class="restaurant-card">
            <img src="images/trishna.jpg" alt="Trishna Restaurant" class="restaurant-img">
            <div class="restaurant-info">
                <h3>Trishna</h3>
                <p>Fort, Mumbai</p>
                <p>Renowned for seafood and coastal cuisine</p>
                <span class="rating">★ 4.7</span>
                <a href="menu.php?id=2" class="view-btn">View Menu</a>
            </div>
        </div>

        <!-- Nagpur Restaurants -->
        <div class="restaurant-card">
            <img src="images/haldiram.jpg" alt="Haldiram's" class="restaurant-img">
            <div class="restaurant-info">
                <h3>Haldiram's</h3>
                <p>Sitabuldi, Nagpur</p>
                <p>Famous for sweets and traditional snacks</p>
                <span class="rating">★ 4.3</span>
                <a href="menu.php?id=3" class="view-btn">View Menu</a>
            </div>
        </div>

        <div class="restaurant-card">
            <img src="images/barbeque.jfif" alt="Barbeque Nation" class="restaurant-img">
            <div class="restaurant-info">
                <h3>Barbeque Nation</h3>
                <p>Wardha Road, Nagpur</p>
                <p>Popular for live grills and buffet</p>
                <span class="rating">★ 4.2</span>
                <a href="menu.php?id=4" class="view-btn">View Menu</a>
            </div>
        </div>

        <!-- Gondia Restaurants -->
        <div class="restaurant-card">
            <img src="images/rajdhani.jfif" alt="Hotel Rajdhani" class="restaurant-img">
            <div class="restaurant-info">
                <h3>Hotel Rajdhani</h3>
                <p>Gondia Station Road</p>
                <p>Known for Maharashtrian thali</p>
                <span class="rating">★ 4.0</span>
                <a href="menu.php?id=5" class="view-btn">View Menu</a>
            </div>
        </div>

        <div class="restaurant-card">
            <img src="images/saipalace.jpg" alt="Sai Palace" class="restaurant-img">
            <div class="restaurant-info">
                <h3>Sai Palace</h3>
                <p>Main Market, Gondia</p>
                <p>Multi-cuisine restaurant</p>
                <span class="rating">★ 3.9</span>
                <a href="menu.php?id=6" class="view-btn">View Menu</a>
            </div>
        </div>
    </div> 
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <h3 class="sub-heading">About Us</h3>
        <!-- <h1 class="heading">Why Choose Us?</h1> -->
        <div class="row">
            <div class="image">
                <img src="images/imp.jpg" alt="">
            </div>
            <div class="content">
                <h3>Best Food in the Country</h3>
                <p>Welcome to our restaurant, where we serve delicious and authentic dishes made with love and passion.</p>
                <div class="icons-container">
                    <div class="icons">
                        <i class="fas fa-shipping-fast"></i>
                        <span>Free Delivery</span>
                    </div>
                    <div class="icons">
                        <i class="fas fa-dollar-sign"></i>
                        <span>Easy Payments</span>
                    </div>
                    <div class="icons">
                        <i class="fas fa-headset"></i>
                        <span>24/7 Service</span>
                    </div>
                </div>
                <a href="about.php" class="btn">Learn More</a>
            </div>
        </div>
    </section>

    <!-- Review Section -->
    <section class="review" id="review">
        <h3 class="sub-heading">Customer's Review</h3>
        <a href="review_form.php" class="btn">Click and Submit Review</a>
        <div class="swiper-container review-slider">
            <div class="swiper-wrapper">
                <?php
                // Initialize and execute the reviews query
                $review_query = $conn->prepare("SELECT * FROM reviews ORDER BY created_at DESC");
                $review_query->execute();
                $reviews = $review_query->fetchAll(PDO::FETCH_ASSOC);

                if (count($reviews) > 0) {
                    foreach ($reviews as $review) {
                        echo '
                        <div class="swiper-slide slide">
                            <i class="fas fa-quote-right"></i>
                            <div class="user">
                                <div class="user-info">
                                    <h3>' . htmlspecialchars($review['name']) . '</h3>
                                    <div class="stars">';
                        
                        // Display star ratings
                        for ($i = 1; $i <= 5; $i++) {
                            echo $i <= $review['rating'] ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                        }
                        
                        echo '
                                    </div>
                                </div>
                            </div>
                            <p>' . htmlspecialchars($review['review']) . '</p>
                        </div>';
                    }
                } else {
                    echo '<div class="swiper-slide"><p class="empty">No reviews yet. Be the first to review!</p></div>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <section class="footer">
        <div class="box-container">
            <div class="box">
                <h3>Locations</h3>
                <a href="#">New York</a>
                <a href="#">Los Angeles</a>
                <a href="#">Chicago</a>
                <a href="#">Houston</a>
                <a href="#">Miami</a>
            </div>
            <div class="box">
                <h3>Quick Links</h3>
                <a href="#">Home</a>
                <a href="#">Dishes</a>
                <a href="#">About</a>
                <a href="#">Menu</a>
                <a href="#">Review</a>
                <a href="#">Order</a>
            </div>
            <div class="box">
                <h3>Contact Info</h3>
                <a href="#">+123-456-7890</a>
                <a href="#">+111-222-3333</a>
                <a href="#">fooder@gmail.com</a>
                <a href="#">info.fooder@gmail.com</a>
                <a href="admin/admin_login.php">AdministrationLogin</a>
            </div>
            <div class="box">
                <h3>Follow Us</h3>
                <a href="#">Facebook</a>
                <a href="#">Twitter</a>
                <a href="#">Instagram</a>
                <a href="#">LinkedIn</a>
            </div>
        </div>
        <div class="credit">Copyright @ 2025 by <span>#FOODER</span></div>
    </section>

    <!-- Scripts -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="js/script.js"></script>
    
    <!-- <div class="loader-container">
        <h1> Welcome </h1><br>
        <img src="images/loader.gif" alt="">
    </div> -->
</body>
</html>