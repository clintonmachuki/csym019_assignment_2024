<?php
// Include the connector file to establish a database connection
include 'connector.php';

session_start(); // Start the session to manage user session data

// Check if the user is not logged in (session variable not set)
if (!isset($_SESSION['username'])) {
    header("Location: login.html"); // Redirect to the login page
    exit(); // Terminate the script execution
}

// Fetch fixtures from the database
$sql = "SELECT FixtureID, HomeTeamID, AwayTeamID, Date FROM Fixtures"; // SQL query to select fixtures from Fixtures table
$result = $conn->query($sql); // Execute the query and store the result

$fixtures = array(); // Initialize an empty array to store fixtures
if ($result->num_rows > 0) { // Check if there are fixtures in the result
    while($row = $result->fetch_assoc()) { // Loop through each row in the result
        $fixtures[] = $row; // Add the row to the fixtures array
    }
}

// Fetch team names from the Teams table
$sql = "SELECT TeamID, TeamName FROM Teams"; // SQL query to select TeamID and TeamName from Teams table
$result = $conn->query($sql); // Execute the query and store the result

$teams = array(); // Initialize an empty array to store team names
if ($result->num_rows > 0) { // Check if there are teams in the result
    while($row = $result->fetch_assoc()) { // Loop through each row in the result
        $teams[$row['TeamID']] = $row['TeamName']; // Store team names with their corresponding IDs
    }
}

// Fetch players from the Players table
$sql = "SELECT PlayerID, PlayerName, TeamID FROM Players"; // SQL query to select PlayerID, PlayerName, and TeamID from Players table
$result = $conn->query($sql); // Execute the query and store the result

$players = array(); // Initialize an empty array to store players
if ($result->num_rows > 0) { // Check if there are players in the result
    while($row = $result->fetch_assoc()) { // Loop through each row in the result
        $players[$row['PlayerID']] = array( // Store player details in the players array
            'PlayerName' => $row['PlayerName'], // Store player name
            'TeamID' => $row['TeamID'] // Store team ID
        );
    }
}

// Close the database connection
$conn->close(); // Close the database connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Scorers</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include stylesheet -->
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
    <h2>Add Scorers</h2>
    <form action="process_scorers.php" method="POST" id="scorersForm"> <!-- Form to add scorers -->
        <label for="fixture">Select Fixture:</label>
        <select id="fixture" name="fixture" required> <!-- Dropdown to select fixture -->
            <option value="">Select Fixture</option> <!-- Default option -->
            <?php foreach ($fixtures as $fixture): ?> <!-- Iterate through fixtures array -->
                <option value="<?php echo $fixture['FixtureID']; ?>"> <!-- Display fixture option -->
                    <?php echo $teams[$fixture['HomeTeamID']] . ' vs ' . $teams[$fixture['AwayTeamID']] . ' - ' . $fixture['Date']; ?> <!-- Display fixture details -->
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <div id="scorersContainer">
            <div class="scorerEntry">
                <label for="player1">Select Scorer:</label>
                <select name="player[]" class="playerSelect" required> <!-- Dropdown to select player -->
                    <option value="">Select Scorer</option> <!-- Default option -->
                    <?php foreach ($players as $playerID => $playerData): ?> <!-- Iterate through players array -->
                        <option value="<?php echo $playerID; ?>"><?php echo $playerData['PlayerName']; ?></option> <!-- Display player options -->
                    <?php endforeach; ?>
                </select>
                <label for="goals1">Goals:</label>
                <input type="number" name="goals[]" class="goalInput" min="1" required> <!-- Input field for goals -->
            </div>
        </div>

        <button type="button" id="addScorerButton">Add Scorer</button> <!-- Button to add more scorers -->
        <br><br>
        <button type="submit">Add Scorers</button> <!-- Submit button -->
    </form>

    <script>
    document.getElementById("fixture").addEventListener("change", function() { // Add an event listener to the fixture dropdown for change event
        var fixtureId = this.value; // Get the selected fixture ID
        var selectedHomeTeamId = <?php echo json_encode($fixtures[0]['HomeTeamID']); ?>; // Set default selected home team ID from PHP variable
        var selectedAwayTeamId = <?php echo json_encode($fixtures[0]['AwayTeamID']); ?>; // Set default selected away team ID from PHP variable
        

        // Find selected home and away team IDs based on the selected fixture ID
        <?php foreach ($fixtures as $fixture): ?> // Iterate through fixtures array
            if (fixtureId == <?php echo $fixture['FixtureID']; ?>) { // Check if the fixture ID matches the selected fixture
                selectedHomeTeamId = <?php echo $fixture['HomeTeamID']; ?>; // Set the selected home team ID
                selectedAwayTeamId = <?php echo $fixture['AwayTeamID']; ?>; // Set the selected away team ID
            }
        <?php endforeach; ?>

        // Filter players based on the selected teams
        var players = <?php echo json_encode($players); ?>; // Get players data from PHP variable
        var playerSelects = document.getElementsByClassName("playerSelect"); // Get all elements with class 'playerSelect'
        Array.from(playerSelects).forEach(function(select) { // Iterate through each player select element
            var teamId = select.dataset.teamId; // Get the team ID associated with the select element
            select.innerHTML = ""; // Clear the current options of the select element
            select.appendChild(document.createElement("option")); // Add default option to the select element
            Object.entries(players).forEach(([playerId, playerData]) => { // Iterate through each player data
                var playerTeamId = playerData['TeamID']; // Get the team ID of the player
                if (playerTeamId == selectedHomeTeamId || playerTeamId == selectedAwayTeamId) { // Check if player belongs to selected home or away team
                    var option = document.createElement("option"); // Create a new option element
                    option.value = playerId; // Set the value of the option to player ID
                    option.textContent = playerData['PlayerName']; // Set the text content of the option to player name
                    select.appendChild(option); // Append the option to the select element
                }
            });
        });
    });
</script>
<footer>
        <p>&copy; 2024 EPL. All rights reserved.</p>
    </footer>
</body>
</html>
