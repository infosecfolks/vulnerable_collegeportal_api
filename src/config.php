<?php
$host = 'db';  // Use 'db' for Docker or 'localhost' if testing locally
$user = 'root';
$pass = 'root';
$dbname = 'college_portal';

// Establish MySQL connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
