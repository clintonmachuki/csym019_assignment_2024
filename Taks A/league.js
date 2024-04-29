// Function used to fetch and validate data from League.json using AJAX
function fetchDataAndValidate(callback) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var data = JSON.parse(xhr.responseText);
                validateData(data);
                callback(data);
            } else {
                console.error('Error fetching data:', xhr.status);
            }
        }
    };
    xhr.open('GET', 'League.json', true);
    xhr.send();
}
// Function to generate the league table from the fixtures data
function generateLeagueTable(fixtures) {
    // Object to store team statistics
    var teams = {};

    // Calculate team statistics from fixtures data
    fixtures.forEach(function(fixture) {
        // Extract fixture details
        var homeTeam = fixture.homeTeam;
        var awayTeam = fixture.awayTeam;
        var homeScore = parseInt(fixture.score.split(' - ')[0]); // Convert home score to integer
        var awayScore = parseInt(fixture.score.split(' - ')[1]); // Convert away score to integer

        // Update home team statistics
        if (!teams[homeTeam]) {
            // Initialize team statistics if not already present
            teams[homeTeam] = { goalsFor: 0, goalsAgainst: 0, wins: 0, draws: 0, losses: 0, points: 0 };
        }
        // Update goals scored and conceded for home team
        teams[homeTeam].goalsFor += homeScore;
        teams[homeTeam].goalsAgainst += awayScore;
        // Update wins, draws, losses, and points for home team based on match result
        if (homeScore > awayScore) {
            teams[homeTeam].wins += 1;
            teams[homeTeam].points += 3;
        } else if (homeScore < awayScore) {
            teams[homeTeam].losses += 1;
        } else {
            teams[homeTeam].draws += 1;
            teams[homeTeam].points += 1;
        }

        // Update away team statistics (similar logic as above)
        if (!teams[awayTeam]) {
            teams[awayTeam] = { goalsFor: 0, goalsAgainst: 0, wins: 0, draws: 0, losses: 0, points: 0 };
        }
        teams[awayTeam].goalsFor += awayScore;
        teams[awayTeam].goalsAgainst += homeScore;
        if (awayScore > homeScore) {
            teams[awayTeam].wins += 1;
            teams[awayTeam].points += 3;
        } else if (awayScore < homeScore) {
            teams[awayTeam].losses += 1;
        } else {
            teams[awayTeam].draws += 1;
            teams[awayTeam].points += 1;
        }
    });

    // Convert teams object to array for sorting
    var teamsArray = Object.keys(teams).map(function(teamName) {
        return { name: teamName, info: teams[teamName] };
    });

    // Sort teams array based on league table rules
    teamsArray.sort(function(a, b) {
        // Sort by points
        if (a.info.points !== b.info.points) {
            return b.info.points - a.info.points;
        }
        // Sort by goal difference if points are equal
        if (a.info.goalsFor - a.info.goalsAgainst !== b.info.goalsFor - b.info.goalsAgainst) {
            return (b.info.goalsFor - b.info.goalsAgainst) - (a.info.goalsFor - a.info.goalsAgainst);
        }
        // Sort by goals scored if goal difference is equal
        if (a.info.goalsFor !== b.info.goalsFor) {
            return b.info.goalsFor - a.info.goalsFor;
        }
        // Sort alphabetically by team name if all else is equal
        return a.name.localeCompare(b.name);
    });

    // Generate HTML markup for the league table
    var tableHTML = '<table>';
    tableHTML += '<thead><tr><th>Position</th><th>Team Name</th><th>Played</th><th>W</th><th>D</th><th>L</th><th>Goals For</th><th>Goals Against</th><th>Goal Difference</th><th>Points</th></tr></thead>'; // Create table header row
    tableHTML += '<tbody>'; // Start table body
    teamsArray.forEach(function(team, index) {
        // Calculate team position
        var position = index + 1;
        tableHTML += '<tr>'; // Start new table row
        // Add position, team name, and other statistics to table
        tableHTML += '<td>' + position + '</td>';
        tableHTML += '<td>' + team.name + '</td>';
        tableHTML += '<td>' + (team.info.wins + team.info.draws + team.info.losses) + '</td>'; // Add games played
        tableHTML += '<td>' + team.info.wins + '</td>'; // Add wins
        tableHTML += '<td>' + team.info.draws + '</td>'; // Add draws
        tableHTML += '<td>' + team.info.losses + '</td>'; // Add losses
        tableHTML += '<td>' + team.info.goalsFor + '</td>'; // Add goals scored
        tableHTML += '<td>' + team.info.goalsAgainst + '</td>'; // Add goals conceded
        tableHTML += '<td>' + (team.info.goalsFor - team.info.goalsAgainst) + '</td>'; // Add goal difference
        tableHTML += '<td>' + team.info.points + '</td>'; // Add points
        tableHTML += '</tr>'; // Close table row
    });
    tableHTML += '</tbody>'; // Close table body
    tableHTML += '</table>'; // Close table

    // Update the content of the league table div with the generated table HTML
    var leagueTableDiv = document.getElementById('league-table');
    leagueTableDiv.innerHTML = tableHTML;
}


// Function to validate data against a JSON schema
function validateData(data) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var schema = JSON.parse(xhr.responseText);
                var ajv = new Ajv();
                var validate = ajv.compile(schema);
                var valid = validate(data);
                if (!valid) {
                    console.error('Data validation error:', validate.errors);
                }
            } else {
                console.error('Error loading schema:', xhr.status);
            }
        }
    };
    xhr.open('GET', 'Leagueschema.json', true);
    xhr.send();
}




function populateTopScorers(data) {
    // Get a reference to the div element where the Top Scorers table will be displayed
    var topScorersDiv = document.getElementById('top-scorers');
    
    // Extract the top scorers data from the provided 'data' object
    var topScorers = data.premierLeagueTopScorers;

    // Sort the top scorers array based on the goals scored in descending order
    topScorers.sort(function(a, b) {
        return b.goalsScored - a.goalsScored; // Sort in descending order of goals scored
    });

    // Generate the HTML markup for the Top Scorers table
    var tableHTML = '<table>';
    tableHTML += '<tr><th>Player Name</th><th>Team</th><th>Goals Scored</th></tr>'; // Create the table header row
    
    // Loop through each top scorer and add a row to the table for each one
    topScorers.forEach(function(scorer) {
        tableHTML += '<tr>'; // Starts a new table row
        tableHTML += '<td>' + scorer.playerName + '</td>'; // Add the player's name to the table
        tableHTML += '<td>' + scorer.team + '</td>'; // Add the team name to the table
        tableHTML += '<td>' + scorer.goalsScored + '</td>'; // Add the number of goals scored by the player to the table
        tableHTML += '</tr>'; // Close the table row
    });
    
    tableHTML += '</table>'; // Close the table

    // Update the content of the top scorers div with the generated table HTML
    topScorersDiv.innerHTML = tableHTML;
}




// This function is used to populate the football scores and fixtures table
function populateFixturesTable(fixtures) {
    // Get a reference to the div element where the Premier League fixtures table will be displayed
    var eplFixture = document.getElementById('EPL-fixture');
    
    // Initialize the HTML markup for the table
    var tableHTML = '<table>';
    tableHTML += '<thead><tr><th>Date</th><th>Home Team</th><th>Score</th><th>Away Team</th></tr></thead>'; // Create the table header row
    tableHTML += '<tbody>'; // Start the table body
    
    // Loop through each fixture and add a row to the table for each one
    fixtures.forEach(function(fixture) {
        tableHTML += '<tr>'; // Start a new table row
        tableHTML += '<td>' + fixture.date + '</td>'; // Add the date of the fixture to the table
        tableHTML += '<td>' + fixture.homeTeam + '</td>'; // Add the home team to the table
        tableHTML += '<td>' + fixture.score + '</td>'; // Add the score of the fixture to the table
        tableHTML += '<td>' + fixture.awayTeam + '</td>'; // Add the away team to the table
        tableHTML += '</tr>'; // Close the table row
    });
    
    tableHTML += '</tbody>'; // Close the table body
    tableHTML += '</table>'; // Close the table
    
    // Update the content of the div with the generated table HTML
    eplFixture.innerHTML = tableHTML;
}





// Event listener for the DOMContentLoaded event
document.addEventListener('DOMContentLoaded', function () {
    // Function to be executed when data fetching and validation are complete
    function onDataFetchComplete(data) {
        // Check if the current page URL contains 'league.htm'
        if (window.location.pathname.includes('league.htm')) {
            // If the URL contains 'league.htm', load the league table and fixtures table
            generateLeagueTable(data.footballScoresFixtures);
            populateFixturesTable(data.footballScoresFixtures);
        } else {
            // If the URL does not contain 'league.htm', load the top scorers table
            populateTopScorers(data);
        }
    }

    // Fetch and validate data from League.json
    fetchDataAndValidate(onDataFetchComplete);
});