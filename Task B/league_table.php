<?php
include 'connector.php'; // Include the database connector file

// Fetch fixtures from the database
$sql = "SELECT FixtureID, HomeTeamID, AwayTeamID, Result FROM Fixtures"; // SQL query to fetch fixtures
$result = $conn->query($sql); // Execute the SQL query

$fixtures = array(); // Initialize an empty array to store fixtures
if ($result->num_rows > 0) { // Check if there are any rows returned by the query
    while($row = $result->fetch_assoc()) { // Loop through each row of the result set
        if (!empty($row['Result'])) { // Check if the result is not empty
            $fixtures[] = $row; // Add the fixture to the fixtures array
        }
    }
}

// Initialize arrays for storing team statistics
$points = array();
$played = array();
$won = array();
$drawn = array();
$lost = array();
$goalsFor = array();
$goalsAgainst = array();
$goalDifference = array();

// Fetch team names from the database
$sql = "SELECT TeamID, TeamName FROM Teams"; // SQL query to fetch team names
$result = $conn->query($sql); // Execute the SQL query

$teams = array(); // Initialize an empty array to store teams
if ($result->num_rows > 0) { // Check if there are any rows returned by the query
    while($row = $result->fetch_assoc()) { // Loop through each row of the result set
        $teamID = $row['TeamID']; // Get the team ID
        $teamName = $row['TeamName']; // Get the team name
        $teams[$teamID] = $teamName; // Add the team to the teams array

        // Initialize team statistics arrays
        $points[$teamName] = 0;
        $played[$teamName] = 0;
        $won[$teamName] = 0;
        $drawn[$teamName] = 0;
        $lost[$teamName] = 0;
        $goalsFor[$teamName] = 0;
        $goalsAgainst[$teamName] = 0;
        $goalDifference[$teamName] = 0;
    }
}

// Process results and calculate points for each team
foreach ($fixtures as $fixture) {
    $result = $fixture['Result'];
    list($home_score, $away_score) = explode('-', $result);

    // Update played matches
    $played[$teams[$fixture['HomeTeamID']]]++;
    $played[$teams[$fixture['AwayTeamID']]]++;

    // Update goals for and against
    $goalsFor[$teams[$fixture['HomeTeamID']]] += $home_score;
    $goalsFor[$teams[$fixture['AwayTeamID']]] += $away_score;
    $goalsAgainst[$teams[$fixture['HomeTeamID']]] += $away_score;
    $goalsAgainst[$teams[$fixture['AwayTeamID']]] += $home_score;

    if ($home_score > $away_score) {
        // Home team wins
        $won[$teams[$fixture['HomeTeamID']]]++;
        $lost[$teams[$fixture['AwayTeamID']]]++;
        $points[$teams[$fixture['HomeTeamID']]] += 3;
    } elseif ($home_score == $away_score) {
        // Draw
        $drawn[$teams[$fixture['HomeTeamID']]]++;
        $drawn[$teams[$fixture['AwayTeamID']]]++;
        $points[$teams[$fixture['HomeTeamID']]] += 1;
        $points[$teams[$fixture['AwayTeamID']]] += 1;
    } else {
        // Away team wins
        $won[$teams[$fixture['AwayTeamID']]]++;
        $lost[$teams[$fixture['HomeTeamID']]]++;
        $points[$teams[$fixture['AwayTeamID']]] += 3;
    }
}

// Calculate goal difference
foreach ($teams as $teamID => $teamName) {
    $goalDifference[$teamName] = $goalsFor[$teamName] - $goalsAgainst[$teamName];
}

// Custom sorting function to sort by points and then by goal difference
uasort($teams, function($a, $b) use ($points, $goalDifference) {
    if ($points[$a] == $points[$b]) { // Check if points are equal
        // If points are equal, compare goal difference
        return $goalDifference[$b] - $goalDifference[$a];
    }
    // Otherwise, compare points
    return $points[$b] - $points[$a];
});

// Close the database connection
$conn->close(); // Close the database connection after fetching data
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>League Table</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to an external stylesheet -->
</head>
<body>
    <h2>League Table</h2> <!-- Heading for the league table -->
    <table border="1"> <!-- Table to display the league table -->
        <tr>
            <th>Position</th>
            <th>Team</th>
            <th>Played</th>
            <th>Won</th>
            <th>Drawn</th>
            <th>Lost</th>
            <th>Goals For</th>
            <th>Goals Against</th>
            <th>Goal Difference</th>
            <th>Points</th>
        </tr>
        <?php $position = 1; ?> <!-- Initialize position counter -->
        <?php foreach ($teams as $teamID => $teamName): ?> <!-- Loop through teams -->
            <tr>
                <td><?php echo $position; ?></td> <!-- Display position -->
                <td><?php echo $teamName; ?></td> <!-- Display team name -->
                <td><?php echo $played[$teamName]; ?></td> <!-- Display matches played -->
                <td><?php echo $won[$teamName]; ?></td> <!-- Display matches won -->
                <td><?php echo $drawn[$teamName]; ?></td> <!-- Display matches drawn -->
                <td><?php echo $lost[$teamName]; ?></td> <!-- Display matches lost -->
                <td><?php echo $goalsFor[$teamName]; ?></td> <!-- Display goals scored -->
                <td><?php echo $goalsAgainst[$teamName]; ?></td> <!-- Display goals conceded -->
                <td><?php echo $goalDifference[$teamName]; ?></td> <!-- Display goal difference -->
                <td><?php echo $points[$teamName]; ?></td> <!-- Display points -->
            </tr>
            <?php $position++; ?> <!-- Increment position counter -->
        <?php endforeach; ?> <!-- End of team loop -->
    </table>
</body>
</html>
