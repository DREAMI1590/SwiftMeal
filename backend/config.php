<?php
$servername = "sql211.infinityfree.com";
$username = "if0_40042420";
$password = "C8apLwQUNqa";
$dbname = "if0_40042420_db_swiftmeal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
