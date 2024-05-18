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
    <link rel="stylesheet" href="styles.css"> <!-- Link to an external stylesheet -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<ul>
        <!-- List of navigation links to other pages -->
        <li id="league_table"><a href="league_table.php">League Table</a></li>
        <li id="add_results"><a href="add_results.php">Add Results</a></li>
        <li id="add_scorers"><a href="add_scorers.php">Add Scorers</a></li>
        <li id="top_scorers"><a href="top_scorers.php">Top Scorers</a></li>
        <li id="statistics"><a href="statistics.php">Statistics</a></li>
        <li id="display_fixtures"><a href="display_fixtures.php">Display Fixtures</a></li>
        <li id="fixtures"><a href="fixtures.php">Fixtures</a></li>
        <li id="add_player"><a href="add_player.php">Add Player</a></li>
        <li id="teams_input"><a href="teams_input.php">Teams Input</a></li>
        <li id="register_admin"><a href="register_admin.html">Register admin</a></li>
        <li id="Login"><a href="Login.html">Login</a></li>
    </ul>
    <div class="container">
        <form id="teamComparisonForm">
            <label for="team1">Select Team 1:</label>
            <select id="team1" name="team1"></select>

            <label for="team2">Select Team 2:</label>
            <select id="team2" name="team2"></select>

            <label for="metric1">Select Metric 1:</label>
            <select id="metric1" name="metric1">
                <option value="">Select Metric</option>
                <option value="points">Points</option>
                <option value="goalsFor">Goals For</option>
                <option value="goalsAgainst">Goals Against</option>
                <option value="wins">Wins</option>
                <option value="losses">Losses</option>
                <option value="draws">Draws</option>
            </select>

            <label for="metric2">Select Metric 2:</label>
            <select id="metric2" name="metric2">
                <option value="">Select Metric</option>
                <option value="points">Points</option>
                <option value="goalsFor">Goals For</option>
                <option value="goalsAgainst">Goals Against</option>
                <option value="wins">Wins</option>
                <option value="losses">Losses</option>
                <option value="draws">Draws</option>
            </select>

            <label for="metric3">Select Metric 3:</label>
            <select id="metric3" name="metric3">
                <option value="">Select Metric</option>
                <option value="points">Points</option>
                <option value="goalsFor">Goals For</option>
                <option value="goalsAgainst">Goals Against</option>
                <option value="wins">Wins</option>
                <option value="losses">Losses</option>
                <option value="draws">Draws</option>
            </select>

            <button type="button" onclick="updateCharts()">Compare</button>
        </form>
        <canvas id="barChart" width="400" height="200"></canvas>
        <canvas id="pieChart" width="400" height="200"></canvas>
    </div>

    <script>
        var teams = <?php echo json_encode(array_values($teams)); ?>;
        var teamStats = {
            "points": <?php echo json_encode(array_values($points)); ?>,
            "goalsFor": <?php echo json_encode(array_values($goalsFor)); ?>,
            "goalsAgainst": <?php echo json_encode(array_values($goalsAgainst)); ?>,
            "wins": <?php echo json_encode(array_values($won)); ?>,
            "losses": <?php echo json_encode(array_values($lost)); ?>,
            "draws": <?php echo json_encode(array_values($drawn)); ?>
        };

        document.addEventListener("DOMContentLoaded", function() {
            var team1Select = document.getElementById('team1');
            var team2Select = document.getElementById('team2');

            // Populate team dropdowns
            teams.forEach(function(team) {
                var option1 = document.createElement('option');
                option1.value = team;
                option1.textContent = team;
                team1Select.appendChild(option1);

                var option2 = document.createElement('option');
                option2.value = team;
                option2.textContent = team;
                team2Select.appendChild(option2);
            });
        });

        function updateCharts() {
            var team1 = document.getElementById('team1').value;
            var team2 = document.getElementById('team2').value;
            var metric1 = document.getElementById('metric1').value;
            var metric2 = document.getElementById('metric2').value;
            var metric3 = document.getElementById('metric3').value;

            var selectedMetrics = [metric1, metric2, metric3].filter(Boolean);

            var team1Data = selectedMetrics.map(metric => teamStats[metric][teams.indexOf(team1)]);
            var team2Data = selectedMetrics.map(metric => teamStats[metric][teams.indexOf(team2)]);

            var ctxBar = document.getElementById('barChart').getContext('2d');
            var ctxPie = document.getElementById('pieChart').getContext('2d');

            // Destroy previous charts if they exist
            if (window.myBarChart) {
                window.myBarChart.destroy();
            }
            if (window.myPieChart) {
                window.myPieChart.destroy();
            }

            // Create new bar chart
            window.myBarChart = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: selectedMetrics,
                    datasets: [
                        {
                            label: team1,
                            data: team1Data,
                            backgroundColor: 'rgba(54, 162, 235, 0.5)', // Random color for team 1
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: team2,
                            data: team2Data,
                            backgroundColor: 'rgba(255, 99, 132, 0.5)', // Random color for team 2
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Create new pie chart with random background colors
            var pieLabels = selectedMetrics.flatMap(metric => [`${team1} - ${metric}`, `${team2} - ${metric}`]);
            var pieData = team1Data.concat(team2Data);
            var pieBackgroundColors = [];

            for (let i = 0; i < pieLabels.length; i++) {
                const randomColor = `rgba(${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, 0.5)`;
                pieBackgroundColors.push(randomColor);
            }

            window.myPieChart = new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: pieLabels,
                    datasets: [{
                        data: pieData,
                        backgroundColor: pieBackgroundColors,
                        borderColor: 'rgba(255, 255, 255, 1)', // White border for all sections
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.raw;
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>

<script>
    // Check if the cookie named "username" exists
    const isLoggedIn = document.cookie.split(';').some((item) => item.trim().startsWith('username='));

    // Get the list items corresponding to the menu items you want to hide/deny access to
    const addResults = document.getElementById('add_results');
    const addScorers = document.getElementById('add_scorers');
    const fixtures = document.getElementById('fixtures');
    const addPlayer = document.getElementById('add_player');
    const teamsInput = document.getElementById('teams_input');
    const registerAdmin = document.getElementById('register_admin');

    if (!isLoggedIn) {
        // User is not logged in, hide menu items and deny access to pages
        addResults.style.display = 'none'; // Hide "Add Results" menu item
        addScorers.style.display = 'none'; // Hide "Add Scorers" menu item
        fixtures.style.display = 'none'; // Hide "Fixtures" menu item
        addPlayer.style.display = 'none'; // Hide "Add Player" menu item
        teamsInput.style.display = 'none'; // Hide "Teams Input" menu item
        registerAdmin.style.display = 'none'; // Hide "Register admin" menu item

        // Redirect user to login page if they try to access certain pages directly
        const restrictedPages = ['add_results.php', 'add_scorers.php', 'fixtures.php', 'add_player.php', 'teams_input.php', 'register_admin.html'];
        if (restrictedPages.includes(window.location.pathname.split('/').pop())) {
            window.location.href = 'login.html'; // Redirect to login page
        }
    }
</script>
    <footer>
        <p>&copy; 2024 EPL. All rights reserved.</p>
    </footer>
</body>
</html>