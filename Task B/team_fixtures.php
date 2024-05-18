<?php
include 'connector.php'; // Include the database connector file

// Check if team ID is provided in the URL
if(isset($_GET['teamID'])) {
    $selectedTeamID = $_GET['teamID']; // Get the selected team ID from the URL parameter
} else {
    // Redirect to league table page if team ID is not provided
    header("Location: league_table.php");
    exit();
}

// Fetch fixtures for the selected team from the database
$sql = "SELECT FixtureID, HomeTeamID, AwayTeamID, Result FROM Fixtures WHERE HomeTeamID = $selectedTeamID OR AwayTeamID = $selectedTeamID"; // SQL query to fetch fixtures for the selected team
$result = $conn->query($sql); // Execute the SQL query

$fixtures = array(); // Initialize an empty array to store fixtures
if ($result->num_rows > 0) { // Check if there are any rows returned by the query
    while($row = $result->fetch_assoc()) { // Loop through each row of the result set
        if (!empty($row['Result'])) { // Check if the result is not empty
            $fixtures[] = $row; // Add the fixture to the fixtures array
        }
    }
}

// Fetch team names from the database
$teamNames = array(); // Initialize an empty array to store team names
$sql = "SELECT TeamID, TeamName FROM Teams"; // SQL query to fetch team names
$result = $conn->query($sql); // Execute the SQL query
if ($result->num_rows > 0) { // Check if there are any rows returned by the query
    while($row = $result->fetch_assoc()) { // Loop through each row of the result set
        $teamNames[$row['TeamID']] = $row['TeamName']; // Store team name in the array with TeamID as the key
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
    <title>Team Fixtures</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to an external stylesheet -->
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
        <li id="Login"><a href="Login.html">Login</a></li>
        <li id="register_admin"><a href="register_admin.html">Register admin</a></li>
    </ul>
    <h2>Team Fixtures</h2> <!-- Heading for the team fixtures -->
    <div class="table-container">
    <h3>Fixtures for <?php echo $teamNames[$selectedTeamID]; ?></h3> <!-- Display selected team name -->
    <table border="1"> <!-- Table to display the team fixtures -->
        <tr>
            <th>Home Team</th>
            <th>Result</th>
            <th>Away Team</th>
            
        </tr>
        <?php foreach ($fixtures as $fixture): ?> <!-- Loop through fixtures -->
            <tr class="clickable-row" data-href="fixture_outcome.php?fixture_id=<?php echo $fixture['FixtureID']; ?>">
                <td><?php echo $teamNames[$fixture['HomeTeamID']]; ?></td> <!-- Display Home Team Name -->
                <td><?php echo $fixture['Result']; ?></td> <!-- Display Result -->
                <td><?php echo $teamNames[$fixture['AwayTeamID']]; ?></td> <!-- Display Away Team Name -->
            </tr>
        <?php endforeach; ?> <!-- End of fixture loop -->
    </table>
        </div>
        <footer>
        <p>&copy; 2024 EPL. All rights reserved.</p>
    </footer>
</body>
</html>

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


<!-- JavaScript to make rows clickable -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var rows = document.querySelectorAll(".clickable-row"); // Select all rows with the class "clickable-row"
        rows.forEach(function(row) {
            row.addEventListener("click", function() {
                var href = row.getAttribute("data-href"); // Get the URL from the "data-href" attribute
                window.location.href = href; // Navigate to the URL when the row is clicked
            });
        });
    });
</script>
