<?php
include 'connector.php'; // Include the database connector file

// Check if the player ID is provided in the URL
if (isset($_GET['player_id'])) {
    $playerId = $_GET['player_id'];

    // Fetch player information from the database
    $sql = "SELECT PlayerName FROM Players WHERE PlayerID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $playerId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $player = $result->fetch_assoc();
        $playerName = $player['PlayerName'];

        // Fetch fixtures in which the player scored from the database
        $sql = "SELECT f.Date, t1.TeamName AS HomeTeam, t2.TeamName AS AwayTeam
                FROM Fixtures f
                INNER JOIN Goals g ON f.FixtureID = g.FixtureID
                INNER JOIN Teams t1 ON f.HomeTeamID = t1.TeamID
                INNER JOIN Teams t2 ON f.AwayTeamID = t2.TeamID
                WHERE g.PlayerID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $playerId);
        $stmt->execute();
        $result = $stmt->get_result();

        $fixtures = array(); // Initialize an array to store fixtures data

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $fixtures[] = $row; // Add each row (fixture data) to the fixtures array
            }
        } else {
            echo "No fixtures found for this player.";
        }
    } else {
        echo "Player not found.";
    }
} else {
    echo "Player ID not provided.";
}

// Close the database connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $playerName; ?>'s Fixtures</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2><?php echo $playerName; ?>'s Fixtures</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>Date</th>
            <th>Home Team</th>
            <th>Away Team</th>
        </tr>
        <?php foreach ($fixtures as $fixture): ?>
            <tr>
                <td><?php echo $fixture['Date']; ?></td>
                <td><?php echo $fixture['HomeTeam']; ?></td>
                <td><?php echo $fixture['AwayTeam']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
