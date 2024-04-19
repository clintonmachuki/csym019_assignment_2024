// Function used to fetch and validate data from League.json using AJAX
function fetchDataAndValidate(callback, fixturesCallback) {
    // used to create a new XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // used to define a callback function to handle the state changes of the XMLHttpRequest object
    xhr.onreadystatechange = function() {
        // used to check if the request has been completed
        if (xhr.readyState === XMLHttpRequest.DONE) {
            // used to check if the request was successful (status code 200)
            if (xhr.status === 200) {
                // used to parse the response text as JSON data
                var data = JSON.parse(xhr.responseText);
                
                // used to validate the fetched data against a schema
                validateData(data);
                
                // used to execute the callback function with the fetched data
                callback(data);
                
                // the If function is used to check if the fixturesCallback function was provided and executes it with the fixtures data
                if (fixturesCallback) {
                    fixturesCallback(data.footballScoresFixtures);
                }
            } else {
                // used to log an error message if the request fails
                console.error('Error fetching data:', xhr.status);
            }
        }
    };

    // used to open a GET request to fetch the League.json file asynchronously
    xhr.open('GET', 'League.json', true);

    // used to send the request
    xhr.send();
}


// the function is used to validate data against JSON schema
function validateData(data) {
    // used to create a new XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // used to define a callback function to handle the state changes of the XMLHttpRequest object
    xhr.onreadystatechange = function() {
        // used to check if the request has been completed
        if (xhr.readyState === XMLHttpRequest.DONE) {
            // used to check if the request was successful (status code 200)
            if (xhr.status === 200) {
                // used to parse the response text as JSON to get the schema
                var schema = JSON.parse(xhr.responseText);
                
                // used to create a new Ajv (Another JSON Schema Validator) instance
                var ajv = new Ajv();
                
                // used to compile the schema using Ajv
                var validate = ajv.compile(schema);
                
                // used to validate the data against the compiled schema
                var valid = validate(data);
                
                // If the data is not valid a an error will be loged to the console.
                if (!valid) {
                    console.error('Data validation error:', validate.errors);
                }
            } else {
                // used to log an error message if the request fails
                console.error('Error loading schema:', xhr.status);
            }
        }
    };

    // used to open a GET request to fetch the JSON schema file asynchronously
    xhr.open('GET', 'Leagueschema.json', true);

    // used to send the request
    xhr.send();
}

// Function sortTeams is used to sort teams based on points and goal difference
function sortTeams(teams) {
    // Uses the sort method of arrays to sort the 'teams' array
    teams.sort(function(a, b) {
        // Compares the points of team 'a' and team 'b'
        if (a.info.points !== b.info.points) {
            // If the points are not equal the teams are sorted by points in descending order
            return b.info.points - a.info.points; // Sort by points
        } else {
            // If the points are equal then we compare the goal difference of team 'a' and team 'b'
            return (b.info.goalsFor - b.info.goalsAgainst) - (a.info.goalsFor - a.info.goalsAgainst); // then we sort by goal difference
        }
    });
}


// thi function is used to calculate goal difference for each team
function calculateGoalDifference(teams) {
    // it iterates through each team in the 'teams' array
    teams.forEach(function(team) {
        // used to calculate the goal difference for the current team
        team.info.goalDifference = team.info.goalsFor - team.info.goalsAgainst;
    });
}

// this function is used to calculate number of games played by each team
function countGamesPlayed(teams) {
    // it iterates through each team in the 'teams' array
    teams.forEach(function(team) {
        // used to calculate the total number of games played by the current team
        team.info.gamesPlayed = team.info.wins + team.info.losses + team.info.draws;
    });
}

// tis function is used to calculate total points for each team
function totalpoints(teams) {
    // it iterate through each team in the 'teams' array
    teams.forEach(function(team) {
        // used to calculate the total points for the current team
        // the points are calculated based on the number of wins and draws
        team.info.points = team.info.wins * 3 + team.info.draws;
    });
}






// this function is used to populate Premier League Table
function populateLeagueTable(data) {
    // used to get the reference to the league table container div
    var leagueTableDiv = document.getElementById('league-table');

    // used to extract the team data from the provided 'data' object
    var teams = data.premierLeagueTable;

    // Calculate goal difference
    calculateGoalDifference(teams);

    // Count games played
    countGamesPlayed(teams);

    //points
    totalpoints(teams)

    // Sort teams
    sortTeams(teams);

    // used to generate table HTML
    var tableHTML = '<table>';// Used to display the table header with column titles
    tableHTML += '<tr><th>Position</th><th>Icons</th><th>Team Name</th><th>Played</th><th>W</th><th>D</th><th>L</th><th>Goals For</th><th>Goals Against</th><th>Goal Difference</th><th>Points</th></tr>';
    
    // used to iterate through each team and populate the table with team data
    teams.forEach(function(team, index) {
        var position = index + 1; // used to calculate the position of the team
    
        // used to start a new row for each team
        tableHTML += '<tr>';
    
        // used to add the position of the team to the table
        tableHTML += '<td>' + position + '</td>';
    
        // used to check if the team has a logo
        if (team.icons) {
            // used to add an image tag with the team's logo
            tableHTML += '<td><img src="' + team.icons + '" alt="' + team.name + '"></td>';
        } else {
            // If  there is no logo is available, display a placeholder
            tableHTML += '<td>No Logo Available</td>';
        }
    
        // used to add the team's name to the table
        tableHTML += '<td>' + team.name + '</td>';
    
        // used to add other statistics such as games played, wins, draws, losses, goals for, goals against, goal difference, and points to the table
        tableHTML += '<td>' + team.info.gamesPlayed + '</td>';
        tableHTML += '<td>' + team.info.wins + '</td>';
        tableHTML += '<td>' + team.info.draws + '</td>';
        tableHTML += '<td>' + team.info.losses + '</td>';
        tableHTML += '<td>' + team.info.goalsFor + '</td>';
        tableHTML += '<td>' + team.info.goalsAgainst + '</td>';
        tableHTML += '<td>' + team.info.goalDifference + '</td>';
        tableHTML += '<td>' + team.info.points + '</td>';
    
        // used to close the row for the current team
        tableHTML += '</tr>';
    });
    
    // used to close the table after populating it with all team data
    tableHTML += '</table>';
      

    // used to Update the content of the table 
    leagueTableDiv.innerHTML = tableHTML;
}

// this function is used to populate the Top Scorers table
function populateTopScorers(data) {
    // used to get a reference to the div element where the Top Scorers table will be displayed
    var topScorersDiv = document.getElementById('top-scorers');
    
    // used to extract the top scorers data from the provided 'data' object
    var topScorers = data.premierLeagueTopScorers;

    // used to generate the HTML markup for the Top Scorers table
    var tableHTML = '<table>';
    tableHTML += '<tr><th>Player Name</th><th>Team</th><th>Goals Scored</th></tr>'; // Create the table header row
    
    // used to loop through each top scorer and adds a row to the table for each one
    topScorers.forEach(function(scorer) {
        tableHTML += '<tr>'; // Starts a new table row
        tableHTML += '<td>' + scorer.playerName + '</td>'; // used to add the player's name to the table
        tableHTML += '<td>' + scorer.team + '</td>'; // used to add the team name to the table
        tableHTML += '<td>' + scorer.goalsScored + '</td>'; // used to add the number of goals scored by the player to the table
        tableHTML += '</tr>'; // Closes the table row
    });
    
    tableHTML += '</table>'; // used to close the table

    // used to update the content of the top scorers div with the generated table HTML
    topScorersDiv.innerHTML = tableHTML;
}


// this function is used to populate the football scores and fixtures table
function populateFixturesTable(fixtures) {
    // use toi reference to the EPL-fixture div where the table will be displayed
    var eplFixture = document.getElementById('EPL-fixture');
    
    // used to Generate the HTML markup for the table
    var tableHTML = '<table>'; // used to start the table element
    tableHTML += '<thead><tr><th>Date</th><th>Home Team</th><th>Score</th><th>Away Team</th></tr></thead>'; // used to add the table header row
    tableHTML += '<tbody>'; // used to start the table body
    
    // used to loop through each fixture and add a row to the table for each one
    fixtures.forEach(function(fixture) {
        tableHTML += '<tr>'; // used to start a new table row
        tableHTML += '<td>' + fixture.date + '</td>'; // used to add  the date of the fixture to the table
        tableHTML += '<td>' + fixture.homeTeam + '</td>'; // used to add  the home team to the table
        tableHTML += '<td>' + fixture.score + '</td>'; // used to add  the score of the fixture to the table
        tableHTML += '<td>' + fixture.awayTeam + '</td>'; // used to add  the away team to the table
        tableHTML += '</tr>'; // used to close the table row
    });
    
    tableHTML += '</tbody>'; // used to close the table body
    tableHTML += '</table>'; // used to close the table

    // used to set the inner HTML of the EPL-fixture div to the generated table HTML, effectively displaying the table on the webpage
    eplFixture.innerHTML = tableHTML;
}

// This code attaches an event listener to the DOMContentLoaded event, which fires when the initial HTML document has been completely loaded and parsed, without waiting for stylesheets, images, and subframes to finish loading.
document.addEventListener('DOMContentLoaded', function () {
    // This function fetchDataAndValidate is called with two callback functions as arguments.
    fetchDataAndValidate(
        // The first callback function is executed when the data fetching and validation process is complete.
        function(data) {
            // It checks if the current page URL contains 'league.htm'.
            if (window.location.pathname.includes('league.htm')) {
                // If the URL contains 'league.htm', it calls the populateLeagueTable function, passing the fetched and validated data as an argument.
                populateLeagueTable(data);
            } else {
                // If the URL does not contain 'league.htm', it calls the populateTopScorers function, passing the fetched and validated data as an argument.
                populateTopScorers(data);
            }
        }, 
        // The second callback function is executed when the data fetching and validation process is complete and includes the football fixtures data.
        function(fixtures) {
            // It calls the populateFixturesTable function, passing the fetched football fixtures data as an argument to populate the fixtures table.
            populateFixturesTable(fixtures);
        }
    );
});
