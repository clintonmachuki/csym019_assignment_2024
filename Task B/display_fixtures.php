<?php
include 'connector.php'; // Include the database connector file

// Fetch fixtures from the database
$sql = "SELECT f.FixtureID, h.TeamName AS HomeTeam, a.TeamName AS AwayTeam, f.Date, f.Result
        FROM Fixtures f
        INNER JOIN Teams h ON f.HomeTeamID = h.TeamID
        INNER JOIN Teams a ON f.AwayTeamID = a.TeamID
        ORDER BY f.Date DESC"; // SQL query to fetch fixtures with home and away team names, ordered by date in descending order
$result = $conn->query($sql); // Execute the query

$fixtures = array(); // Initialize an empty array to store fixtures data
if ($result->num_rows > 0) { // Check if there are any rows returned by the query
    while($row = $result->fetch_assoc()) { // Loop through each row of the result set
        $fixtures[] = $row; // Add each row (fixture data) to the fixtures array
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
    <title>Display Fixtures</title> <!-- Title of the webpage -->
    <link rel="stylesheet" href="styles.css"> <!-- Link to an external stylesheet -->
</head>
<body>
    <h2>Fixtures</h2> <!-- Heading for the page -->
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
    <table border="1" cellpadding="5" cellspacing="0"> <!-- Table to display fixtures data -->
        <tr>
            <th>Home Team</th> <!-- Table header for Home Team -->
            <th>Away Team</th> <!-- Table header for Away Team -->
            <th>Date</th> <!-- Table header for Date -->
            <th>Result</th> <!-- Table header for Result -->
        </tr>
        <?php foreach ($fixtures as $fixture): ?> <!-- Loop through each fixture data -->
            <tr>
                <td><?php echo $fixture['HomeTeam']; ?></td> <!-- Display Home Team -->
                <td><?php echo $fixture['AwayTeam']; ?></td> <!-- Display Away Team -->
                <td><?php echo $fixture['Date']; ?></td> <!-- Display Date -->
                <td><?php echo $fixture['Result']; ?></td> <!-- Display Result -->
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
