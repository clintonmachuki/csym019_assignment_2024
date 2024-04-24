<?php
session_start(); // Start the session to manage user session data
include 'connector.php'; // Include the database connection script

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the request method is POST
    $username = $_POST['username']; // Retrieve username from the POST data
    $password = $_POST['password']; // Retrieve password from the POST data

    // Query to check if username and password match
    $sql = "SELECT * FROM admin_users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql); // Execute the query

    if ($result->num_rows == 1) { // If a single row is returned, authentication is successful
        // Authentication successful, set session variables
        $_SESSION['username'] = $username; // Store the username in the session variable
        header("Location: dashboard.html"); // Redirect to the dashboard page upon successful login
        exit(); // Terminate the script execution
    } else {
        // Authentication failed, redirect to login page with error message
        header("Location: login.html?error=1"); // Redirect to the login page with an error parameter
        exit(); // Terminate the script execution
    }
}

$conn->close(); // Close the database connection
?>
