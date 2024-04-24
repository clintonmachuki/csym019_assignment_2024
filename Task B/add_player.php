<?php
// Include the connector file to establish a database connection
include 'connector.php';

// Define variables and initialize with empty values
$name = $team = ""; // Initialize name and team variables
$name_err = $team_err = ""; // Initialize error variables for name and team

// Query to retrieve teams from the database
$sql_teams = "SELECT TeamID, TeamName FROM Teams"; // SQL query to select TeamID and TeamName from Teams table
$result_teams = $conn->query($sql_teams); // Execute the query and store the result in $result_teams variable

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the form has been submitted using POST method
    // Validate name
    if (empty(trim($_POST["name"]))) { // Check if the name field is empty
        $name_err = "Please enter the player's name."; // Set an error message if name field is empty
    } else {
        $name = trim($_POST["name"]); // Store the trimmed value of name field in $name variable
    }

    // Validate team
    if (empty(trim($_POST["team"]))) { // Check if the team field is empty
        $team_err = "Please select the player's team."; // Set an error message if team field is empty
    } else {
        $team = trim($_POST["team"]); // Store the trimmed value of team field in $team variable
    }

    // Check input errors before inserting into database
    if (empty($name_err) && empty($team_err)) { // Check if there are no errors
        // Prepare an insert statement
        $sql = "INSERT INTO Players (PlayerName, TeamID) VALUES (?, ?)"; // SQL query to insert data into Players table

        if ($stmt = $conn->prepare($sql)) { // Prepare the SQL statement
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("si", $param_name, $param_team); // Bind parameters

            // Set parameters
            $param_name = $name; // Set parameter for name
            $param_team = $team; // Set parameter for team

            // Attempt to execute the prepared statement
            if ($stmt->execute()) { // Execute the prepared statement
                // Redirect to players page
                header("location: add_player.php"); // Redirect to add_player.php page
                exit(); // Exit script
            } else {
                echo "Something went wrong. Please try again later."; // Display error message if execution fails
            }

            // Close statement
            $stmt->close(); // Close the prepared statement
        }
    }

    // Close connection
    $conn->close(); // Close the database connection
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Player</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include stylesheet -->
</head>
<body>
    <h2>Add New Player</h2>
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
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div>
            <label>Name</label> <!-- Input field for player's name -->
            <input type="text" name="name" value="<?php echo $name; ?>"> <!-- Display entered name if form submission fails -->
            <span><?php echo $name_err; ?></span> <!-- Display name error message -->
        </div>
        <div>
            <label>Team</label> <!-- Dropdown for selecting player's team -->
            <select name="team">
                <option value="">Select a team</option> <!-- Default option -->
                <?php
                // Loop through teams data and display options
                if ($result_teams->num_rows > 0) { // Check if there are teams in the database
                    while ($row = $result_teams->fetch_assoc()) { // Iterate through each team
                        echo "<option value='" . $row["TeamID"] . "'>" . $row["TeamName"] . "</option>"; // Display team options
                    }
                }
                ?>
            </select>
            <span><?php echo $team_err; ?></span> <!-- Display team error message -->
        </div>
        <div>
            <input type="submit" value="Submit"> <!-- Submit button -->
            <input type="reset" value="Reset"> <!-- Reset button to clear form -->
        </div>
    </form>
</body>
</html>
