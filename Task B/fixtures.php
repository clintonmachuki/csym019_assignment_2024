<?php
include 'connector.php'; // Include the database connector file

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the request method is POST
    // Retrieve form data
    $home_team_id = $_POST['home_team']; // Retrieve home team ID from the form
    $away_team_id = $_POST['away_team']; // Retrieve away team ID from the form
    $date = $_POST['date']; // Retrieve date from the form
    $result = $_POST['result']; // Retrieve result from the form (assuming it can be a string)

    // Insert new fixture into the database
    $sql = "INSERT INTO Fixtures (HomeTeamID, AwayTeamID, Date, Result) VALUES ('$home_team_id', '$away_team_id', '$date', '$result')";
    if ($conn->query($sql) === TRUE) { // Execute the SQL query and check if it was successful
        // Redirect to fixtures.php with success message
        header("Location: fixtures.php?status=success"); // Redirect to fixtures.php with a success status in the URL
        exit(); // Exit the script
    } else {
        // Redirect to fixtures.php with error message
        header("Location: fixtures.php?status=error"); // Redirect to fixtures.php with an error status in the URL
        exit(); // Exit the script
    }
}

// Fetch team names from the Teams table
$sql = "SELECT TeamID, TeamName FROM Teams"; // SQL query to fetch team names from the Teams table
$result = $conn->query($sql); // Execute the SQL query

$teams = array(); // Initialize an empty array to store team names
if ($result->num_rows > 0) { // Check if there are any rows returned by the query
    while($row = $result->fetch_assoc()) { // Loop through each row of the result set
        $teams[] = $row; // Add each row (team data) to the teams array
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
    <title>Fixtures</title> <!-- Title of the webpage -->
    <link rel="stylesheet" href="styles.css"> <!-- Link to an external stylesheet -->
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
    <h2>Fixtures</h2> <!-- Heading for the page -->
    <?php
    // Display status message if provided in the URL
    if (isset($_GET['status'])) { // Check if 'status' parameter is set in the URL
        if ($_GET['status'] == 'success') { // Check if the status is 'success'
            echo '<p style="color: green;">Fixture added successfully!</p>'; // Display success message
        } elseif ($_GET['status'] == 'error') { // Check if the status is 'error'
            echo '<p style="color: red;">Error adding fixture. Please try again!</p>'; // Display error message
        }
    }
    ?>
    <form action="fixtures.php" method="POST"> <!-- Form to add a new fixture -->
        <label for="home_team">Home Team:</label> <!-- Label for home team select dropdown -->
        <select id="home_team" name="home_team" required> <!-- Select dropdown for home team -->
            <option value="">Select Home Team</option> <!-- Default option for home team -->
            <?php foreach ($teams as $team): ?> <!-- Loop through each team -->
                <option value="<?php echo $team['TeamID']; ?>"><?php echo $team['TeamName']; ?></option> <!-- Option for each team -->
            <?php endforeach; ?>
        </select><br><br>
        <label for="away_team">Away Team:</label> <!-- Label for away team select dropdown -->
        <select id="away_team" name="away_team" required> <!-- Select dropdown for away team -->
            <option value="">Select Away Team</option> <!-- Default option for away team -->
            <?php foreach ($teams as $team): ?> <!-- Loop through each team -->
                <option value="<?php echo $team['TeamID']; ?>"><?php echo $team['TeamName']; ?></option> <!-- Option for each team -->
            <?php endforeach; ?>
        </select><br><br>
        <label for="date">Date:</label> <!-- Label for date input -->
        <input type="date" id="date" name="date" required><br><br> <!-- Input field for date -->
        <label for="result">Result:</label> <!-- Label for result input -->
        <input type="text" id="result" name="result"><br><br> <!-- Input field for result -->
        <button type="submit">Add Fixture</button> <!-- Submit button to add fixture -->
    </form>
</body>
</html>
