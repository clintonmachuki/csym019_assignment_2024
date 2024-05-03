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
    <title>League Table Bar Graph</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    <canvas id="leagueTableChart" width="400" height="200"></canvas>
    <script>
        var teams = <?php echo json_encode(array_values($teams)); ?>;
        var points = <?php echo json_encode(array_values($points)); ?>;
        var wins = <?php echo json_encode(array_values($won)); ?>;
        var losses = <?php echo json_encode(array_values($lost)); ?>;
        var draws = <?php echo json_encode(array_values($drawn)); ?>;

        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('leagueTableChart').getContext('2d');

            // Create an array of objects with team data
            var teamData = [];
            for (var i = 0; i < teams.length; i++) {
                teamData.push({
                    team: teams[i],
                    points: points[i],
                    wins: wins[i],
                    losses: losses[i],
                    draws: draws[i]
                });
            }

            // Sort team data by points (descending order)
            teamData.sort(function(a, b) {
                return b.points - a.points;
            });

            // Extract sorted data back into separate arrays
            for (var i = 0; i < teamData.length; i++) {
                teams[i] = teamData[i].team;
                points[i] = teamData[i].points;
                wins[i] = teamData[i].wins;
                losses[i] = teamData[i].losses;
                draws[i] = teamData[i].draws;
            }

            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: teams,
                    datasets: [{
                        label: 'Points',
                        data: points,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Wins',
                        data: wins,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Losses',
                        data: losses,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Draws',
                        data: draws,
                        backgroundColor: 'rgba(255, 206, 86, 0.2)',
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 1
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
</body>
</html>
