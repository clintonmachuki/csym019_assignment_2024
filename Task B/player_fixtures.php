<?php
include 'connector.php'; // Include the database connector file

// Check if the player ID is provided in the URL
if (isset($_GET['player_id'])) {
    $playerId = $_GET['player_id'];

    // Fetch player information from the database
    $sql = "SELECT PlayerName FROM Players WHERE PlayerID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $playerId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $player = $result->fetch_assoc();
        $playerName = $player['PlayerName'];

        // Fetch fixtures in which the player scored from the database
        $sql = "SELECT f.Date, t1.TeamName AS HomeTeam, t2.TeamName AS AwayTeam
                FROM Fixtures f
                INNER JOIN Goals g ON f.FixtureID = g.FixtureID
                INNER JOIN Teams t1 ON f.HomeTeamID = t1.TeamID
                INNER JOIN Teams t2 ON f.AwayTeamID = t2.TeamID
                WHERE g.PlayerID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $playerId);
        $stmt->execute();
        $result = $stmt->get_result();

        $fixtures = array(); // Initialize an array to store fixtures data

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $fixtures[] = $row; // Add each row (fixture data) to the fixtures array
            }
        } else {
            echo "No fixtures found for this player.";
        }
    } else {
        echo "Player not found.";
    }
} else {
    echo "Player ID not provided.";
}

// Close the database connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $playerName; ?>'s</title>
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
    
    <div class="table-container">
    <h2><?php echo $playerName; ?>'s Has scored in the following fixtures</h2>
    <table border="1" cellpadding="5" cellspacing="0">
    <!-- Table opening tag with border, cell padding, and cell spacing attributes -->
    <tr>
        <!-- Table row opening tag for the table header row -->
        <th>Date</th>
        <!-- Table header cell for the date column -->
        <th>Home Team</th>
        <!-- Table header cell for the home team column -->
        <th>Away Team</th>
        <!-- Table header cell for the away team column -->
    </tr>
    <!-- Table row closing tag for the table header row -->
    <?php foreach ($fixtures as $fixture): ?>
        <!-- PHP foreach loop to iterate over each fixture in the $fixtures array -->
        <tr>
            <!-- Table row opening tag for each fixture -->
            <td><?php echo $fixture['Date']; ?></td>
            <!-- Table data cell containing the date of the fixture -->
            <td><?php echo $fixture['HomeTeam']; ?></td>
            <!-- Table data cell containing the home team name of the fixture -->
            <td><?php echo $fixture['AwayTeam']; ?></td>
            <!-- Table data cell containing the away team name of the fixture -->
        </tr>
        <!-- Table row closing tag for each fixture -->
    <?php endforeach; ?>
    <!-- End of the PHP foreach loop -->
</table>
<!-- Table closing tag -->
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
