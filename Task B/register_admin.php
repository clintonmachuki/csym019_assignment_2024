<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

include 'connector.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username already exists
    $sql = "SELECT * FROM admin_users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Username already exists, redirect to registration page with error message
        header("Location: register_admin.html?error=1");
        exit();
    } else {
        // Insert new admin user into the database
        $sql = "INSERT INTO admin_users (username, password) VALUES ('$username', '$password')";
        if ($conn->query($sql) === TRUE) {
            // Registration successful, redirect to login page
            header("Location: login.html");
            exit();
        } else {
            // Error in inserting user data, redirect to registration page with error message
            header("Location: register_admin.html?error=2");
            exit();
        }
    }
}

$conn->close();
?>
