<?php
include 'connector.php';

// Fetch top goal scorers from the database
$sql = "SELECT PlayerID, SUM(goals) AS TotalGoals FROM goals GROUP BY PlayerID ORDER BY TotalGoals DESC LIMIT 10";
$result = $conn->query($sql);

$topScorers = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $playerID = $row['PlayerID'];
        $totalGoals = $row['TotalGoals'];
        
        // Retrieve player name from the Players table
        $playerSql = "SELECT PlayerName FROM Players WHERE PlayerID = $playerID";
        $playerResult = $conn->query($playerSql);
        if ($playerResult->num_rows > 0) {
            $playerRow = $playerResult->fetch_assoc();
            $playerName = $playerRow['PlayerName'];
            
            // Add player name and total goals to the top scorers array
            $topScorers[] = array('PlayerName' => $playerName, 'TotalGoals' => $totalGoals);
        }
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Goal Scorers</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Top Goal Scorers</h2>
    <!-- Navigation Links -->
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
    <!-- Display Top Goal Scorers in a Table -->
    <table>
        <thead>
            <tr>
                <th>Player Name</th>
                <th>Total Goals</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($topScorers as $scorer): ?>
                <!-- Loop through each top scorer and display their name and total goals -->
                <tr>
                    <!-- Display the player's name -->
                    <td><?php echo $scorer['PlayerName']; ?></td>
                    <!-- Display the total goals scored by the player -->
                    <td><?php echo $scorer['TotalGoals']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
