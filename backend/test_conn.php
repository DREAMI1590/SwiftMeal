<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = "sql211.infinityfree.com";      // MySQL server
$user = "if0_40042420";                 // Username dari panel
$password = "C8apLwQUNqa";              // Password MySQL
$dbname = "if0_40042420_db_swiftmeal";  // Nama database

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
echo "Koneksi MySQL berhasil!";
?>
