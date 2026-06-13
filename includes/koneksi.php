<?php
// Konfigurasi ini disesuaikan dengan docker-compose.yml
$host = 'db';                     // Nama service database di Docker
$user = 'swiftmeal_user';         // User yang didefinisikan di docker-compose
$pass = 'SwiftMeal123!';          // Password user di docker-compose
$db   = 'db_swiftmeal';           // Nama database di docker-compose

$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
