<?php
session_start();
// include '../db.php';
// include 'auth.php';
// redirectIfNotLoggedIn();

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cart</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include '../Components/admin_header.php' ?>
    <h1>Your Cart</h1>
    <table>
        <thead>
            <tr>
                <th>Dish</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart_items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['dish_name']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>$<?= $item['price'] ?></td>
                    <td>$<?= $item['quantity'] * $item['price'] ?></td>
                    <td>
                        <a href="remove_from_cart.php?id=<?= $item['id'] ?>">Remove</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="order.php" class="btn">Place Order</a>
</body>
</html>