<?php
// Include the connector file to establish a database connection
include 'connector.php';

// Fetch fixtures from the database
$sql = "SELECT FixtureID, HomeTeamID, AwayTeamID, Result, Date FROM Fixtures"; // SQL query to select fixtures from Fixtures table
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

// Close the database connection
$conn->close(); // Close the database connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Results to Fixtures</title>
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
    <h2>Add Results to Fixtures</h2>
    <form action="process_results.php" method="POST"> <!-- Form to add results -->
        <label for="fixture">Select Fixture:</label>
        <select id="fixture" name="fixture" required> <!-- Dropdown to select fixture -->
            <option value="">Select Fixture</option> <!-- Default option -->
            <?php foreach ($fixtures as $fixture): ?> <!-- Iterate through fixtures array -->
                <?php if(empty($fixture['Result'])): ?> <!-- Check if result is empty -->
                    <option value="<?php echo $fixture['FixtureID']; ?>"> <!-- Display fixture option -->
                        <?php echo $teams[$fixture['HomeTeamID']] . ' vs ' . $teams[$fixture['AwayTeamID']] . ' - ' . $fixture['Date']; ?> <!-- Display fixture details -->
                    </option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select><br><br>
        <label for="result">Result:</label>
        <input type="text" id="result" name="result" required><br><br> <!-- Input field for result -->
        <button type="submit">Add Result</button> <!-- Submit button -->
    </form>

    <script>
        // Function to show only fixtures where no results have been posted yet
        function filterFixtures() {
            var fixtures = document.getElementById("fixture").getElementsByTagName("option"); // Get all fixture options
            for (var i = 0; i < fixtures.length; i++) { // Loop through each option
                if (fixtures[i].value !== "" && fixtures[i].textContent.includes(" - ")) { // Check if option is valid
                    fixtures[i].style.display = "block"; // Show the fixture option
                } else {
                    fixtures[i].style.display = "none"; // Hide the fixture option
                }
            }
        }

        // Call the function when the page loads and when the user changes the result input
        window.onload = filterFixtures; // Call filterFixtures function when the page loads
        document.getElementById("result").addEventListener("change", filterFixtures); // Call filterFixtures function when the result input changes
    </script>
</body>
</html>
