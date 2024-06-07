<?php
// Database parameters
$servername = "127.0.0.1"; 
$port = 3306; 
$username = "root";
$password = "1234";
$dbname = "bookingDB";

// Create a new database connection using mysqli
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>