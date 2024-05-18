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
        <h2 class="heading">Fixture Outcome</h2>
        <div class="fixture-details">
            <h3>Fixture Details</h3>
            <p><strong>Home Team:</strong> <?php echo $fixture['HomeTeam']; ?></p>
            <p><strong>Away Team:</strong> <?php echo $fixture['AwayTeam']; ?></p>
            <p><strong>Date:</strong> <?php echo $fixture['Date']; ?></p>
            <p><strong>Result:</strong> <?php echo $fixture['Result']; ?></p>
        </div>

        <div class="goal-scorers">
            <h3>Goal Scorers</h3>
            <ul class="scorers-list">
                <?php foreach ($goal_scorers as $scorer): ?>
                    <li><?php echo $scorer['PlayerName']; ?> (<?php echo $scorer['TeamName']; ?>) - <?php echo $scorer['goals']; ?> goals</li> <!-- Display goal scorers, their teams, and the number of goals -->
                <?php endforeach; ?>
            </ul>
        </div>
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
