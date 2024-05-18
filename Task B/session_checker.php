<?php
session_start(); // Start the session to manage user session data

// Check if the user is not logged in (session variable not set)
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to the login page
    exit(); // Terminate the script execution
}
?>
