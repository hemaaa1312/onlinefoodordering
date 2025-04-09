<?php
session_start();
require 'db.php';

i
// Input validation
$restaurant_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$restaurant_id) {
    die("Invalid restaurant ID");
}

// Get restaurant details
$stmt = $conn->prepare("SELECT * FROM restaurants WHERE id = ?");
$stmt->bind_param("i", $restaurant_id);
$stmt->execute();
$restaurant = $stmt->get_result()->fetch_assoc();

if (!$restaurant) {
    die("Restaurant not found");
}

// Get menu items
$menu_stmt = $conn->prepare("SELECT * FROM menus WHERE restaurant_id = ?");
$menu_stmt->bind_param("i", $restaurant_id);
$menu_stmt->execute();
$menu_items = $menu_stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($restaurant['name']); ?></title>
    <style>
        .menu-item {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }
        .menu-category {
            margin-top: 20px;
            font-weight: bold;
            color: #ff6b6b;
        }
    </style>
</head>
<body>
    <h1><?php echo htmlspecialchars($restaurant['name']); ?></h1>
    <p>Location: <?php echo htmlspecialchars($restaurant['location']); ?>, <?php echo htmlspecialchars($restaurant['city']); ?></p>
    <img src="images/<?php echo htmlspecialchars($restaurant['featured_image']); ?>" alt="<?php echo htmlspecialchars($restaurant['name']); ?>" width="500">
    <p><?php echo htmlspecialchars($restaurant['description']); ?></p>
    
    <h2>Menu</h2>
    <?php
    $current_category = '';
    while ($item = $menu_items->fetch_assoc()) {
        if ($item['category'] != $current_category) {
            echo '<div class="menu-category">' . htmlspecialchars($item['category']) . '</div>';
            $current_category = $item['category'];
        }
        echo '<div class="menu-item">';
        echo '<h3>' . htmlspecialchars($item['item_name']) . '</h3>';
        echo '<p>' . htmlspecialchars($item['description']) . '</p>';
        echo '<p>₹' . number_format($item['price'], 2) . '</p>';
        echo '</div>';
    }
    ?>
</body>
</html>

<?php
$conn->close();
?>

