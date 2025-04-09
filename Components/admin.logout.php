<?php
include '../db.php'
// Start the session
session_start();
session_unset();

// Destroy the session
session_destroy();

// Redirect to the login page
header('location:../admin/admin_login.php');
exit;
?>