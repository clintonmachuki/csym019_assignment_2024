<?php
    include 'connector.php';

    session_start(); // Start the session to manage user session data

// Check if the user is not logged in (session variable not set)
if (!isset($_SESSION['username'])) {
    header("Location: login.html"); // Redirect to the login page
    exit(); // Terminate the script execution
}

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $team_name = $_POST['team_name'];
        $team_logo = $_POST['team_logo']; // Assuming team logo is stored as a URL

        // Insert new team into the database
        $sql = "INSERT INTO Teams (TeamName, TeamLogo) VALUES ('$team_name', '$team_logo')";
        if ($conn->query($sql) === TRUE) {
            // Redirect to teams_input.html with success message
            header("Location: teams_input.php?status=success");
            exit();
        } else {
            // Redirect to teams_input.html with error message
            header("Location: teams_input.php?status=error");
            exit();
        }
    }

    $conn->close();
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Team</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <ul>
        <!-- List of navigation links to other pages -->
        <li><a href="league_table.php">League Table</a></li>
        <li><a href="add_results.php">Add Results</a></li>
        <li><a href="add_scorers.php">Add Scorers</a></li>
        <li><a href="top_scorers.php">Top Scorers</a></li>
        <li><a href="statistics.php">Statistics</a></li>
        <li><a href="display_fixtures.php">Display Fixtures</a></li>
        <li><a href="fixtures.php">Fixtures</a></li>
        <li><a href="add_player.php">Add Player</a></li>
        <li><a href="teams_input.php">Teams Input</a></li>
        <li><a href="register_admin.html">Register admin</a></li>
    </ul>
    <h2>Add New Team</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <label for="team_name">Team Name:</label>
        <input type="text" id="team_name" name="team_name" required><br><br>
        <label for="team_logo">Team Logo:</label>
        <input type="text" id="team_logo" name="team_logo"><br><br>
        <button type="submit">Add Team</button>
    </form>
    <footer>
        <p>&copy; 2024 EPL. All rights reserved.</p>
    </footer>
</body>
</html>
