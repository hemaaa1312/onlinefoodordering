<?php
session_start();
require 'db.php';
$title = "Our Locations";
include 'Components/header.php';
?>

<section class="locations">
    <h1 class="heading">Our Locations</h1>
    
    <div class="map-container">
        <!-- This would be replaced with your actual map implementation -->
        <div class="map-placeholder">
            <img src="images/map-placeholder.jpg" alt="Our Locations Map">
        </div>
    </div>
    
    <div class="box-container">
        <?php
        $locations_stmt = $conn->prepare("SELECT * FROM restaurants WHERE status = 'active' ORDER BY city");
        $locations_stmt->execute();
        
        if($locations_stmt->rowCount() > 0) {
            while($location = $locations_stmt->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <div class="box" id="<?= strtolower(str_replace(' ', '-', $location['city'])) ?>">
            <h3><?= htmlspecialchars($location['name']) ?></h3>
            <p><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($location['address']) ?></p>
            <p><i class="fas fa-phone"></i> <?= htmlspecialchars($location['phone']) ?></p>
            <p><i class="fas fa-clock"></i> <?= htmlspecialchars($location['opening_time']) ?> - <?= htmlspecialchars($location['closing_time']) ?></p>
            <a href="restaurant.php?id=<?= $location['id'] ?>" class="btn">Visit Restaurant</a>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">No locations available yet!</p>';
        }
        ?>
    </div>
    
    <div class="opening-hours">
        <h2>General Opening Hours</h2>
        <table>
            <tr>
                <th>Day</th>
                <th>Hours</th>
            </tr>
            <tr>
                <td>Monday - Thursday</td>
                <td>11:00 AM - 10:00 PM</td>
            </tr>
            <tr>
                <td>Friday - Saturday</td>
                <td>11:00 AM - 11:00 PM</td>
            </tr>
            <tr>
                <td>Sunday</td>
                <td>11:00 AM - 9:00 PM</td>
            </tr>
        </table>
        <p class="note">*Hours may vary by location. Please check specific restaurant pages for details.</p>
    </div>
</section>

<?php include 'Components/footer.php'; ?>