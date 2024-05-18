<?php
session_start(); // Start the session to manage user session data

// Check if the user is not logged in (session variable not set)
if (!isset($_SESSION['username'])) {
    header("Location: login.html"); // Redirect to the login page
    exit(); // Terminate the script execution
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS file -->
</head>

<body>
<div class="container">
<ul class="dashboard-grid"> <!-- Add a class to the ul element for styling -->
        <li><a href="league_table.php">League Table</a></li>
        <li><a href="add_results.php">Add Results</a></li>
        <li><a href="add_scorers.php">Add Scorers</a></li>
        <li><a href="top_scorers.php">Top Scorers</a></li>
        <li><a href="pie.php">Pie Chart</a></li>
        <li><a href="statistics.php">Statistics</a></li>
        <li><a href="display_fixtures.php">Display Fixtures</a></li>
        <li><a href="fixtures.php">Fixtures</a></li>
        <li><a href="add_player.php">Add Player</a></li>
        <li><a href="teams_input.php">Teams Input</a></li>
        <li><a href="register_admin.html">Register admin</a></li>
        <li><a href="manageusers.php">Manage Admin</a></li>
    </ul>
</div>
</body>
<footer>
    <p>&copy; 2024 EPL. All rights reserved.</p>
</footer>
</html>
