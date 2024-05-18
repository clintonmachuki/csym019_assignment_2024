<?php
session_start();

include 'connector.php';
// Check if the user is not logged in (session variable not set)
if (!isset($_SESSION['username'])) {
    header("Location: login.html"); // Redirect to the login page
    exit(); // Terminate the script execution
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add New User
    if (isset($_POST['add_user'])) {
        $new_username = $_POST['new_username'];
        $new_password = $_POST['new_password'];

        // Hash the password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Insert new user into the database
        $sql = "INSERT INTO admin_users (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $new_username, $hashed_password);
        $stmt->execute();
    }
    // Edit User Password
    elseif (isset($_POST['edit_password'])) {
        $user_id = $_POST['user_id'];
        $new_password = $_POST['new_password'];

        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update user password in the database
        $sql = "UPDATE admin_users SET password=? WHERE user_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $hashed_password, $user_id);
        $stmt->execute();
    }
}

// Fetch users from the database
$sql = "SELECT * FROM admin_users";
$result = $conn->query($sql);

$users = array(); // Initialize an array to store users

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row; // Add each user to the users array
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include your CSS file -->
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
    </ul>
    <h2>Add New User</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <input type="text" name="new_username" placeholder="Username">
        <input type="password" name="new_password" placeholder="Password">
        <button type="submit" name="add_user">Add User</button>
    </form>

    <h2>Users</h2>
    <table>
        <tr>
            <th>Username</th>
            <th>Password</th>
            <th>Action</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['password']; ?></td>
                <td>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                        <input type="password" name="new_password" placeholder="New Password">
                        <button type="submit" name="edit_password">Edit Password</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
