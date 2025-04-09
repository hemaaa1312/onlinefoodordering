<?php
session_start();
include 'db.php';

// // Check if the user is logged in
// if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
//     header("Location: login.php");
//     exit();
// }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $rating = $_POST['rating'];
    $review = $_POST['review'];

    // Use prepared statement to prevent SQL injection
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Review</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div><br><br></div>
 <?php include 'Components/header.php'; ?>
    <div class="review-form-container">
        <h2>Submit Your Review</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="submit_review.php" method="POST">
            <div class="form-group">
                <label for="name">Your Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="review">Your Review:</label>
                <textarea id="review" name="review" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="rating">Rating:</label>
                <select id="rating" name="rating" required>
                    <option value="1">1 Star</option>
                    <option value="2">2 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="5">5 Stars</option>
                </select>
            </div>
            <button type="submit" class="btn">Submit Review</button>
        </form>
    </div>
    <?php include 'Components/footer.php' ?>
</body>
</html>