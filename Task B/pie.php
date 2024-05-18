<?php
include 'connector.php';

// Fetch fixtures from the database
$sql = "SELECT FixtureID, HomeTeamID, AwayTeamID, Result FROM Fixtures";
$result = $conn->query($sql);

$fixtures = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if (!empty($row['Result'])) {
            $fixtures[] = $row;
        }
    }
}

// Initialize arrays
$points = array();
$played = array();
$won = array();
$drawn = array();
$lost = array();
$goalsFor = array();
$goalsAgainst = array();
$goalDifference = array();

// Fetch team names from the database
$sql = "SELECT TeamID, TeamName FROM Teams";
$result = $conn->query($sql);

$teams = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $teamID = $row['TeamID'];
        $teamName = $row['TeamName'];
        $teams[$teamID] = $teamName;

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

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>League Table Pie Chart</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js library -->
</head>
<body>
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
    <h2>League Table Pie Chart</h2>
    <!-- Canvas element to render the pie chart -->
    <canvas id="leagueTablePieChart" width="400" height="400"></canvas>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Get the canvas element
            var ctx = document.getElementById('leagueTablePieChart').getContext('2d');

            // Extract team names and points from PHP arrays
            var teams = <?php echo json_encode(array_values($teams)); ?>;
            var points = <?php echo json_encode(array_values($points)); ?>;

            // Create a new pie chart using Chart.js library
            var myChart = new Chart(ctx, {
                type: 'pie', // Set chart type to pie
                data: {
                    labels: teams, // Use team names as labels
                    datasets: [{
                        label: 'Points Distribution', // Dataset label
                        data: points, // Points as data values
                        backgroundColor: [ // Array of background colors for each slice
                            'rgba(255, 99, 132, 0.2)', // Red
                            'rgba(54, 162, 235, 0.2)', // Blue
                            'rgba(255, 206, 86, 0.2)', // Yellow
                            'rgba(75, 192, 192, 0.2)', // Green
                            'rgba(153, 102, 255, 0.2)', // Purple
                            'rgba(255, 159, 64, 0.2)', // Orange
                            'rgba(255, 0, 0, 0.2)', // Custom color
                            // Add more colors as needed
                        ],
                        borderColor: [ // Border color for each slice
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255, 0, 0, 1)',
                            // Add more colors as needed
                        ],
                        borderWidth: 1 // Border width for each slice
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        });
    </script>
    <footer>
        <p>&copy; 2024 EPL. All rights reserved.</p>
    </footer>
</body>
</html>
