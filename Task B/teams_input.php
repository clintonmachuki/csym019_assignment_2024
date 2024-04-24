<?php
    include 'connector.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $team_name = $_POST['team_name'];
        $team_logo = $_POST['team_logo']; // Assuming team logo is stored as a URL

        // Insert new team into the database
        $sql = "INSERT INTO Teams (TeamName, TeamLogo) VALUES ('$team_name', '$team_logo')";
        if ($conn->query($sql) === TRUE) {
            // Redirect to teams_input.html with success message
            header("Location: teams_input.html?status=success");
            exit();
        } else {
            // Redirect to teams_input.html with error message
            header("Location: teams_input.html?status=error");
            exit();
        }
    }

    $conn->close();
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Team</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Add New Team</h2>
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <label for="team_name">Team Name:</label>
        <input type="text" id="team_name" name="team_name" required><br><br>
        <label for="team_logo">Team Logo:</label>
        <input type="text" id="team_logo" name="team_logo"><br><br>
        <button type="submit">Add Team</button>
    </form>
</body>
</html>
