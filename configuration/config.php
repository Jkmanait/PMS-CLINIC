<?php
// Database connection parameters
$host = "localhost";
$dbuser = "root";
$dbpass = "";
$db = "uhc";

// Create a new MySQLi instance
$mysqli = new mysqli($host, $dbuser, $dbpass, $db);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Your database connection is now established and can be used for queries
?>
