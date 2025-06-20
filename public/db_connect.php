<?php
// public/db_connect.php

$servername = "localhost";
$username = "root"; // Your database username
$password = "";     // Your database password
$dbname = "aksarawalk_db"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully"; // Uncomment for testing
?>