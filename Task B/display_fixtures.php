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
    <title>Display Fixtures</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
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
    <h2>Fixtures</h2>
    <?php
    // Initialize variable to store previous date
    $prevDate = null;
    // Loop through fixtures to display them grouped by date
    foreach ($fixtures as $fixture) {
        // Get the date of the current fixture
        $currDate = $fixture['Date'];
        // Check if it's a new date
        if ($currDate !== $prevDate) {
            // If it's a new date, close the previous table if it exists
            if ($prevDate !== null) {
                echo '</table>';
            }
            // Display the date as a heading
            echo '<h3>' . $currDate . '</h3>';
            // Open a new table for fixtures of the current date
            echo '<table border="1" cellpadding="5" cellspacing="0">
                <tr>
                    <th>Home Team</th>
                    <th>Result</th>
                    <th>Away Team</th>
                    
                </tr>';
        }
        // Display the fixture row
        echo '<tr class="clickable-row" data-href="fixture_outcome.php?fixture_id=' . $fixture['FixtureID'] . '">
            <td>' . $fixture['HomeTeam'] . '</td>
            <td>' . $fixture['Result'] . '</td>
            <td>' . $fixture['AwayTeam'] . '</td>
            
        </tr>';
        // Update the previous date
        $prevDate = $currDate;
    }
    // Close the last table
    echo '</table>';
    ?>
    <!-- JavaScript to make rows clickable -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var rows = document.querySelectorAll(".clickable-row");
            rows.forEach(function(row) {
                row.addEventListener("click", function() {
                    var href = row.getAttribute("data-href");
                    window.location.href = href;
                });
            });
        });
    </script>
</body>
</html>
