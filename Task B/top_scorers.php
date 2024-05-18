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

$teams = array(); // Initialize an empty array to hold the team names
// Check if the result has more than 0 rows
if ($result->num_rows > 0) { // If there are rows in the result set
    // Loop through each row in the result set
    while($row = $result->fetch_assoc()) { // Fetch a row as an associative array
        // Add the value of 'TeamName' from the current row to the $teams array
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
<h2>Top Scorers</h2> <!-- Header for the section -->
    <div class="table-container"> <!-- Container for the table -->
    <form action="top_scorers.php" method="POST"> <!-- Form to filter top scorers by team -->
        <label for="team_filter">Filter by Team:</label> <!-- Label for the team filter dropdown -->
        <select id="team_filter" name="team_filter"> <!-- Dropdown for selecting a team to filter -->
            <option value="All">All</option> <!-- Option to show all teams -->
            <?php foreach ($teams as $team): ?> <!-- Loop through each team in the $teams array -->
                <option value="<?php echo $team; ?>"><?php echo $team; ?></option> <!-- Option for each team -->
            <?php endforeach; ?>
        </select>
        <button type="submit">Apply Filter</button> <!-- Button to submit the form -->
    </form>
    <table id="top-scorers-table"> <!-- Table to display top scorers -->
        <thead>
            <tr>
                <th>Player Name</th> <!-- Table header for player names -->
                <th>Team</th> <!-- Table header for team names -->
                <th>Goals Scored</th> <!-- Table header for goals scored -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filtered_goalscorers as $goalscorer): ?> <!-- Loop through each filtered goalscorer -->
                <?php if ($goalscorer['GoalsScored'] > 0): ?> <!-- Check if the player has scored more than 0 goals -->
                    <tr class="clickable-row" data-href="player_fixtures.php?player_id=<?php echo $goalscorer['PlayerID']; ?>"> <!-- Table row for each goalscorer with clickable link -->
                        <td><?php echo $goalscorer['PlayerName']; ?></td> <!-- Display player name -->
                        <td><?php echo $goalscorer['TeamName']; ?></td> <!-- Display team name -->
                        <td><?php echo $goalscorer['GoalsScored']; ?></td> <!-- Display goals scored -->
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
                </div>
                <footer> <!-- Footer section -->
        <p>&copy; 2024 EPL. All rights reserved.</p> <!-- Copyright notice -->
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() { // Ensure the script runs after the DOM is fully loaded
            var table = document.getElementById("top-scorers-table"); // Get the table element by its ID
            var rows = table.getElementsByTagName("tr"); // Get all rows in the table
            for (var i = 0; i < rows.length; i++) { // Loop through each row
                var row = rows[i];
                row.addEventListener("click", function() { // Add click event listener to each row
                    var href = this.dataset.href; // Get the data-href attribute of the clicked row
                    if (href) { // If there is a href value
                        window.location.href = href; // Navigate to the URL specified in data-href
                    }
                });
            }
        });
        
    </script>
</body>
</html>
