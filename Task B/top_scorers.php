<?php
include 'connector.php';

// Fetch goals scored by each player from the database
$sql = "SELECT Players.PlayerName, Teams.TeamName, COUNT(Goals.PlayerID) AS GoalsScored
        FROM Players
        INNER JOIN Teams ON Players.TeamID = Teams.TeamID
        LEFT JOIN Goals ON Players.PlayerID = Goals.PlayerID
        GROUP BY Players.PlayerID";
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
    <h2>Top Scorers</h2>
    <ul>
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

    <table>
        <tr>
            <th>Player Name</th>
            <th>Team</th>
            <th>Goals Scored</th>
        </tr>
        <?php foreach ($filtered_goalscorers as $goalscorer): ?>
            <?php if ($goalscorer['GoalsScored'] > 0): ?>
                <tr>
                    <td><?php echo $goalscorer['PlayerName']; ?></td>
                    <td><?php echo $goalscorer['TeamName']; ?></td>
                    <td><?php echo $goalscorer['GoalsScored']; ?></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>
</body>
</html>
