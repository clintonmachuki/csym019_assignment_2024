<?php
session_start();

include 'connector.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to fetch user data based on username
    $sql = "SELECT * FROM admin_users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Username exists, verify password
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Password is correct, start session, set cookie, and redirect to dashboard
            $_SESSION['username'] = $username;
            setcookie("username", $username, time() + (86400 * 30), "/"); // Cookie lasts for 30 days
            header("Location: dashboard.php");
            exit();
        } else {
            // Incorrect password, redirect to login page with error message
            header("Location: login.html?error=1");
            exit();
        }
    } else {
        // Username not found, redirect to login page with error message
        header("Location: login.html?error=2");
        exit();
    }
}

$conn->close();
?>
