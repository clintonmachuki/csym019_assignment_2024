<?php
include 'connector.php'; // Include the database connector file

// Get the fixture ID from the URL parameter
if (isset($_GET['fixture_id'])) {
    $fixture_id = $_GET['fixture_id'];
} else {
    // Redirect to display_fixtures.php if fixture_id parameter is not provided
    header("Location: display_fixtures.php");
    exit();
}

// Fetch fixture outcome from the database based on the provided fixture ID
$sql = "SELECT f.FixtureID, h.TeamName AS HomeTeam, a.TeamName AS AwayTeam, f.Date, f.Result
        FROM Fixtures f
        INNER JOIN Teams h ON f.HomeTeamID = h.TeamID
        INNER JOIN Teams a ON f.AwayTeamID = a.TeamID
        WHERE f.FixtureID = $fixture_id"; // SQL query to fetch fixture outcome for the provided fixture ID
$result = $conn->query($sql); // Execute the query

$fixture = $result->fetch_assoc(); // Fetch the fixture data

// Fetch goal scorers for the fixture from the database
$sql = "SELECT g.PlayerID, g.goals, p.PlayerName, t.TeamName
        FROM goals g
        INNER JOIN Players p ON g.PlayerID = p.PlayerID
        INNER JOIN Teams t ON p.TeamID = t.TeamID
        WHERE g.FixtureID = $fixture_id"; // SQL query to fetch goal scorers for the provided fixture ID
$result = $conn->query($sql); // Execute the query

$goal_scorers = array(); // Initialize an empty array to store goal scorers data
if ($result->num_rows > 0) { // Check if there are any rows returned by the query
    while($row = $result->fetch_assoc()) { // Loop through each row of the result set
        $goal_scorers[] = $row; // Add each row (goal scorer data) to the goal_scorers array
    }
}

// Close the database connection
$conn->close(); // Close the database connection after fetching data
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fixture Outcome</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2 class="heading">Fixture Outcome</h2>
        <ul>
        <!-- List of navigation links to other pages -->
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
    </ul>
        <div class="fixture-details">
            <h3>Fixture Details</h3>
            <p><strong>FixtureID:</strong> <?php echo $fixture['FixtureID']; ?></p>
            <p><strong>Home Team:</strong> <?php echo $fixture['HomeTeam']; ?></p>
            <p><strong>Away Team:</strong> <?php echo $fixture['AwayTeam']; ?></p>
            <p><strong>Date:</strong> <?php echo $fixture['Date']; ?></p>
            <p><strong>Result:</strong> <?php echo $fixture['Result']; ?></p>
        </div>

        <div class="goal-scorers">
            <h3>Goal Scorers</h3>
            <ul class="scorers-list">
                <?php foreach ($goal_scorers as $scorer): ?>
                    <li><?php echo $scorer['PlayerName']; ?> (<?php echo $scorer['TeamName']; ?>)</li> <!-- Display goal scorers and their teams -->
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html>
