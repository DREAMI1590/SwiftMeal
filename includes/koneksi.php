<?php
$host = 'sql211.infinityfree.com'; // host MySQL dari InfinityFree
$user = 'if0_40042420';            // username database
$pass = 'C8apLwQUNqa';             // password database
$db   = 'if0_40042420_db_swiftmeal'; // nama database

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
