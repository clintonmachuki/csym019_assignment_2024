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
        <li id="league_table"><a href="league_table.php">League Table</a></li>
        <li id="add_results"><a href="add_results.php">Add Results</a></li>
        <li id="add_scorers"><a href="add_scorers.php">Add Scorers</a></li>
        <li id="top_scorers"><a href="top_scorers.php">Top Scorers</a></li>
        <li id="pie"><a href="pie.php">Pie Chart</a></li>
        <li id="statistics"><a href="statistics.php">Statistics</a></li>
        <li id="display_fixtures"><a href="display_fixtures.php">Display Fixtures</a></li>
        <li id="fixtures"><a href="fixtures.php">Fixtures</a></li>
        <li id="add_player"><a href="add_player.php">Add Player</a></li>
        <li id="teams_input"><a href="teams_input.php">Teams Input</a></li>
        <li id="register_admin"><a href="register_admin.html">Register admin</a></li>
    </ul>
    <h2>Fixtures</h2>
    <div class="table-container">
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
    </div>
    <footer>
        <p>&copy; 2024 EPL. All rights reserved.</p>
    </footer>
    <script>
    // Check if the cookie named "username" exists
    const isLoggedIn = document.cookie.split(';').some((item) => item.trim().startsWith('username='));

    // Get the list items corresponding to the menu items you want to hide/deny access to
    const addResults = document.getElementById('add_results');
    const addScorers = document.getElementById('add_scorers');
    const fixtures = document.getElementById('fixtures');
    const addPlayer = document.getElementById('add_player');
    const teamsInput = document.getElementById('teams_input');
    const registerAdmin = document.getElementById('register_admin');

    if (!isLoggedIn) {
        // User is not logged in, hide menu items and deny access to pages
        addResults.style.display = 'none'; // Hide "Add Results" menu item
        addScorers.style.display = 'none'; // Hide "Add Scorers" menu item
        fixtures.style.display = 'none'; // Hide "Fixtures" menu item
        addPlayer.style.display = 'none'; // Hide "Add Player" menu item
        teamsInput.style.display = 'none'; // Hide "Teams Input" menu item
        registerAdmin.style.display = 'none'; // Hide "Register admin" menu item

        // Redirect user to login page if they try to access certain pages directly
        const restrictedPages = ['add_results.php', 'add_scorers.php', 'fixtures.php', 'add_player.php', 'teams_input.php', 'register_admin.html'];
        if (restrictedPages.includes(window.location.pathname.split('/').pop())) {
            window.location.href = 'login.html'; // Redirect to login page
        }
    }
</script>
</body>
</html>
