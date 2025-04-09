<?php
// Authentication check would go here

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and process form submission
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);
    $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    
    // Image upload handling
    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Check if image file is a actual image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check === false) {
        die("File is not an image.");
    }
    
    // Check file size (5MB max)
    if ($_FILES["image"]["size"] > 5000000) {
        die("Sorry, your file is too large.");
    }
    
    // Allow certain file formats
    if(!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        die("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
    }
    
    // Upload file
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Insert into database
        $conn = new mysqli('localhost', 'username', 'password', 'restaurant_db');
        $stmt = $conn->prepare("INSERT INTO restaurants (name, location, city, description, featured_image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $location, $city, $description, basename($_FILES["image"]["name"]));
        $stmt->execute();
        $restaurant_id = $stmt->insert_id;
        $stmt->close();
        
        // Process menu items
        if (isset($_POST['menu_item_name'])) {
            $menu_stmt = $conn->prepare("INSERT INTO menus (restaurant_id, item_name, description, price, category) VALUES (?, ?, ?, ?, ?)");
            foreach ($_POST['menu_item_name'] as $index => $item_name) {
                $item_desc = $_POST['menu_item_desc'][$index];
                $item_price = $_POST['menu_item_price'][$index];
                $item_category = $_POST['menu_item_category'][$index];
                
                $menu_stmt->bind_param("issds", $restaurant_id, $item_name, $item_desc, $item_price, $item_category);
                $menu_stmt->execute();
            }
            $menu_stmt->close();
        }
        $conn->close();
        
        header("Location: restaurant.php?id=$restaurant_id");
        exit();
    } else {
        die("Sorry, there was an error uploading your file.");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Restaurant</title>
    <style>
        .form-group {
            margin-bottom: 15px;
        }
        .menu-item-fields {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Add New Restaurant</h1>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Restaurant Name:</label>
            <input type="text" name="name" required>
        </div>
        
        <div class="form-group">
            <label>Location:</label>
            <input type="text" name="location" required>
        </div>
        
        <div class="form-group">
            <label>City:</label>
            <select name="city" required>
                <option value="Mumbai">Mumbai</option>
                <option value="Nagpur">Nagpur</option>
                <option value="Gondia">Gondia</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Description:</label>
            <textarea name="description" required></textarea>
        </div>
        
        <div class="form-group">
            <label>Featured Image:</label>
            <input type="file" name="image" accept="image/*" required>
        </div>
        
        <h2>Menu Items</h2>
        <div id="menu-items-container">
            <div class="menu-item-fields">
                <div class="form-group">
                    <label>Item Name:</label>
                    <input type="text" name="menu_item_name[]" required>
                </div>
                
                <div class="form-group">
                    <label>Description:</label>
                    <textarea name="menu_item_desc[]"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Price:</label>
                    <input type="number" step="0.01" name="menu_item_price[]" required>
                </div>
                
                <div class="form-group">
                    <label>Category:</label>
                    <input type="text" name="menu_item_category[]" required>
                </div>
            </div>
        </div>
        
        <button type="button" id="add-menu-item">Add Another Menu Item</button>
        <button type="submit">Save Restaurant</button>
    </form>

    <script>
        document.getElementById('add-menu-item').addEventListener('click', function() {
            const container = document.getElementById('menu-items-container');
            const newItem = container.firstElementChild.cloneNode(true);
            
            // Clear input values
            const inputs = newItem.querySelectorAll('input, textarea');
            inputs.forEach(input => {
                if (input.type !== 'button') {
                    input.value = '';
                }
            });
            
            container.appendChild(newItem);
        });
    </script>
</body>
</html>