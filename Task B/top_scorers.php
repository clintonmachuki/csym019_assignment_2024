<?php
include 'connector.php';

// Fetch goals scored by each player from the database
$sql = "SELECT Players.PlayerID, Players.PlayerName, Teams.TeamName, COUNT(Goals.PlayerID) AS GoalsScored
            FROM Players
            INNER JOIN Teams ON Players.TeamID = Teams.TeamID
            LEFT JOIN Goals ON Players.PlayerID = Goals.PlayerID
            GROUP BY Players.PlayerID
            ";
$result = $conn->query($sql);

$goalscorers = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $goalscorers[] = $row;
    }
}

// Fetch all teams for the team filter
$sql = "SELECT TeamName FROM Teams";
$result = $conn->query($sql);

$teams = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $teams[] = $row['TeamName'];
    }
}

// Handle team filter
if(isset($_POST['team_filter'])) {
    $selected_team = $_POST['team_filter'];

    // If a team is selected, filter goalscorers by that team
    if ($selected_team != 'All') {
        $filtered_goalscorers = array_filter($goalscorers, function($goalscorer) use ($selected_team) {
            return $goalscorer['TeamName'] == $selected_team;
        });
    } else {
        $filtered_goalscorers = $goalscorers;
    }
} else {
    // If no team filter is provided, display all goalscorers
    $filtered_goalscorers = $goalscorers;
}
// Sort the filtered goalscorers array by the 'Goals Scored' column in descending order
usort($filtered_goalscorers, function($a, $b) {
    return $b['GoalsScored'] - $a['GoalsScored'];
});

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Scorers</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>    
<ul>
        <!-- List of navigation links to other pages -->
        <li id="league_table"><a href="league_table.php">League Table</a></li>
        <li id="add_results"><a href="add_results.php">Add Results</a></li>
        <li id="add_scorers"><a href="add_scorers.php">Add Scorers</a></li>
        <li id="top_scorers"><a href="top_scorers.php">Top Scorers</a></li>
        <li id="statistics"><a href="statistics.php">Statistics</a></li>
        <li id="display_fixtures"><a href="display_fixtures.php">Display Fixtures</a></li>
        <li id="fixtures"><a href="fixtures.php">Fixtures</a></li>
        <li id="add_player"><a href="add_player.php">Add Player</a></li>
        <li id="teams_input"><a href="teams_input.php">Teams Input</a></li>
        <li id="register_admin"><a href="register_admin.html">Register admin</a></li>
        <li id="Login"><a href="Login.html">Login</a></li>
    </ul>
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
    <h2>Top Scorers</h2>
    <div class="table-container">
    <form action="top_scorers.php" method="POST">
        <label for="team_filter">Filter by Team:</label>
        <select id="team_filter" name="team_filter">
            <option value="All">All</option>
            <?php foreach ($teams as $team): ?>
                <option value="<?php echo $team; ?>"><?php echo $team; ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Apply Filter</button>
    </form>
    <table id="top-scorers-table">
        <thead>
            <tr>
                <th>Player Name</th>
                <th>Team</th>
                <th>Goals Scored</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filtered_goalscorers as $goalscorer): ?>
                <?php if ($goalscorer['GoalsScored'] > 0): ?>
                    <tr class="clickable-row" data-href="player_fixtures.php?player_id=<?php echo $goalscorer['PlayerID']; ?>">
                        <td><?php echo $goalscorer['PlayerName']; ?></td>
                        <td><?php echo $goalscorer['TeamName']; ?></td>
                        <td><?php echo $goalscorer['GoalsScored']; ?></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
                </div>
                <footer>
        <p>&copy; 2024 EPL. All rights reserved.</p>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var table = document.getElementById("top-scorers-table");
            var rows = table.getElementsByTagName("tr");
            for (var i = 0; i < rows.length; i++) {
                var row = rows[i];
                row.addEventListener("click", function() {
                    var href = this.dataset.href;
                    if (href) {
                        window.location.href = href;
                    }
                });
            }
        });
        
    </script>
</body>
</html>
