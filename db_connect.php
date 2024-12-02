<?php
// Database connection settings
$host = "localhost"; // Change if your database is hosted elsewhere
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$database = "ParadiseRoster"; // Replace with your database name

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optionally set the charset to UTF-8 for consistent encoding
$conn->set_charset("utf8");

// Use $conn throughout your project for queries
?>
