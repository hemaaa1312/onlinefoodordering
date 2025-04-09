<?php

session_start(); // Start the session
include 'db.php';
// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Get form data
$name = $_POST['name'];
$review = $_POST['review'];
$rating = $_POST['rating'];

// Insert review into database
$sql = "INSERT INTO reviews (name, review, rating) VALUES ('$name', '$review', '$rating')";
if ($conn->query($sql) === TRUE) {
    echo "Review submitted successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn = null;

// Redirect back to the index page
header("Location: index.php");
exit();
?>