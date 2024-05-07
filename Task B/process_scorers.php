<?php
include 'connector.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form data
    if (isset($_POST['fixture']) && isset($_POST['player']) && isset($_POST['goals'])) {
        // Retrieve form data
        $fixtureID = $_POST['fixture'];
        $players = $_POST['player'];
        $goals = $_POST['goals'];

        // Insert data into the goals table
        $sql = "INSERT INTO goals (FixtureID, PlayerID, goals) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Bind parameters and execute the statement for each scorer
        foreach ($players as $index => $playerID) {
            $stmt->bind_param("iii", $fixtureID, $playerID, $goals[$index]);
            $stmt->execute();
        }

        // Close statement and database connection
        $stmt->close();
        $conn->close();
        
        // Redirect to a success page or perform any other actions
        header("Location: add_scorers.php");
        exit();
    } else {
        // Handle form validation errors
        echo "Error: Please fill in all required fields.";
    }
}
?>
