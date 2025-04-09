<?php
session_start();
include '../db.php';

redirectIfNotLoggedIn();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $rating = $_POST['rating'];
    $review = $_POST['review'];

    $stmt = $conn->prepare("INSERT INTO reviews (user_id, name, rating, review) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$user_id, $name, $rating, $review])) {
        header('Location: index.php');
        exit();
    } else {
        $error = "Something went wrong. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Review</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include '../Components/admin_header.php' ?>
    <h1>Submit Review</h1>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form action="review_form.php" method="POST">
        <input type="text" name="name" placeholder="Your Name" required>
        <select name="rating" required>
            <option value="1">1 Star</option>
            <option value="2">2 Stars</option>
            <option value="3">3 Stars</option>
            <option value="4">4 Stars</option>
            <option value="5">5 Stars</option>
        </select>
        <textarea name="review" placeholder="Your Review" required></textarea>
        <button type="submit">Submit Review</button>
    </form>
    
</body>
</html>