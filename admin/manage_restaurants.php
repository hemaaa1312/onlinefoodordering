<!-- admin_panel.php -->
 <?php 
 include '../db.php';
 session_start();
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'Components/header.php'; ?>

    <section class="admin-panel">
        <h1 class="heading">Manage Restaurants</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch restaurants from the database
                $sql = "SELECT * FROM restaurants";
                $stmt = $conn->query($sql);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '
                    <tr>
                        <td>' . $row['id'] . '</td>
                        <td>' . $row['name'] . '</td>
                        <td>' . $row['location'] . '</td>
                        <td>' . $row['description'] . '</td>
                        <td><img src="' . $row['image'] . '" width="100"></td>
                        <td>
                            <a href="edit_restaurant.php?id=' . $row['id'] . '" class="btn">Edit</a>
                            <a href="delete_restaurant.php?id=' . $row['id'] . '" class="btn">Delete</a>
                        </td>
                    </tr>';
                }
                ?>
            </tbody>
        </table>
    </section>
  
  
</body>
</html>