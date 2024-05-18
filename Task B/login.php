<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['username'])) {
    // Redirect to dashboard if already logged in
    header("Location: dashboard.html");
    exit();
}

include 'connector.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to fetch user data based on username
    $sql = "SELECT * FROM admin_users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) { // If a single row is returned, the username exists
        $row = $result->fetch_assoc();
        $hashed_password = $row['password']; // Get the hashed password from the database
        if (password_verify($password, $hashed_password)) { // Verify the password
            // Password is correct, set session variables
            $_SESSION['username'] = $username; // Store the username in the session variable
            header("Location: dashboard.html"); // Redirect to the dashboard page upon successful login
            exit(); // Terminate the script execution
        } else {
            // Password is incorrect, redirect to login page with error message
            header("Location: login.html?error=1"); // Redirect to the login page with an error parameter
            exit(); // Terminate the script execution
        }
    } else {
        // Username not found, redirect to login page with error message
        header("Location: login.html?error=2"); // Redirect to the login page with an error parameter
        exit(); // Terminate the script execution
    }
}

$conn->close();
?>
