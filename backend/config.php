<?php
$servername = "db";                   // Nama service di docker-compose
$username = "swiftmeal_user";         // User di docker-compose
$password = "SwiftMeal123!";          // Password di docker-compose
$dbname = "db_swiftmeal";             // Nama database di docker-compose

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
