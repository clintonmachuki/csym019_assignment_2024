<?php
include 'connector.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $fixture_id = $_POST['fixture'];
    $result = $_POST['result']; // Assuming result can be a string

    // Insert result into the database
    $sql = "UPDATE Fixtures SET Result = '$result' WHERE FixtureID = $fixture_id";
    if ($conn->query($sql) === TRUE) {
        // Redirect to add_scorers.php with success message
        header("Location: add_scorers.php");
        exit();
    } else {
        // Redirect to add_scorers.php with error message
        header("Location: add_scorers.php");
        exit();
    }
}

// Close the database connection
$conn->close();
?>
