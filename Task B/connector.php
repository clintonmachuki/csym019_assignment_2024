<?php
session_start(); // Start a new session

// Database connection details
$host = 'localhost'; // Hostname of the database server
$username = 'root'; // Username to connect to the database
$password = ''; // Password to connect to the database
///these are default logins to sql
$database = 'premier_league'; // Name of the database

// Connect to the database
$conn = new mysqli($host, $username, $password, $database); // Create a new MySQLi object to establish a connection

// Check connection
if ($conn->connect_error) { // Check if there is an error in establishing the connection
    die("Connection failed: " . $conn->connect_error); // If there is an error, terminate the script and display an error message
}
?>
