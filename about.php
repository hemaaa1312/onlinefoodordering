<?php
session_start();
require 'db.php';
$title = "About Us";
include 'Components/header.php';
?>
<div><br><br><br><br></div>

<section class="about-page">
<h1>Our Food Ordering Platform</h1>
    
    <div class="about-content">
        <div class="image">
            <img src="images/imp.jpg" alt="Our Restaurant">
        </div>
        <div class="content">
        <h3>Welcome to Our Food Delivery Solution</h3>
            <p>Our online food ordering platform was developed to revolutionize the way restaurants connect with customers. This comprehensive system provides a seamless digital experience for both food businesses and their patrons.</p>
            
            <p>For restaurants, we offer a complete digital storefront solution that includes menu management, order processing, and customer relationship tools. Our platform helps establishments of all sizes expand their reach and streamline operations.</p>
            
            <p>For customers, we've created an intuitive interface that makes discovering new restaurants and ordering food effortless. With features like real-time order tracking, secure payments, and personalized recommendations, we're transforming the food delivery experience.</p>
         </div>
    </div>
    
    <!-- <div class="mission">
        <h2>Our Mission</h2>
        <p>To provide exceptional dining experiences through authentic flavors, quality ingredients, and warm hospitality that makes every customer feel like family.</p>
    </div> -->
    
    <div class="team">
        <h2>Key Features</h2>
        <div class="box-container">
            <div class="box">
                <i class="fas fa-mobile-alt"></i>
                <h3>Responsive Design</h3>
                <span>Works perfectly on all devices</span>
                <div class="share">
                    <p>Mobile-first approach ensures accessibility for all users</p>
                </div>
            </div>
            <div class="box">
                <i class="fas fa-bolt"></i>
                <h3>Fast Ordering</h3>
                <span>Quick checkout process</span>
                <div class="share">
                    <p>Streamlined flow from menu to confirmation</p>
                </div>
            </div>
            <div class="box">
                <i class="fas fa-lock"></i>
                <h3>Secure Payments</h3>
                <span>Multiple payment options</span>
                <div class="share">
                    <p>PCI-compliant processing with encryption</p>
                </div>
            </div>
            <div class="box">
                <i class="fas fa-chart-line"></i>
                <h3>Business Analytics</h3>
                <span>Real-time insights</span>
                <div class="share">
                    <p>Detailed reporting for restaurant partners</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="achievements">
        <h2>Platform Statistics</h2>
        <div class="box-container">
            <div class="box">
                <i class="fas fa-store"></i>
                <h3>100+</h3>
                <p>Partner Restaurants</p>
            </div>
            <div class="box">
                <i class="fas fa-utensils"></i>
                <h3>5,000+</h3>
                <p>Menu Items Available</p>
            </div>
            <div class="box">
                <i class="fas fa-users"></i>
                <h3>50,000+</h3>
                <p>Satisfied Customers</p>
            </div>
            <div class="box">
                <i class="fas fa-truck"></i>
                <h3>24/7</h3>
                <p>Delivery Availability</p>
            </div>
        </div>
    </div>
</section>
<?php include 'Components/footer.php'; ?>