<?php
// Ganti dengan konfigurasi yang ada di docker-compose.yml kamu
$host = 'db';                 // Nama service database di docker-compose.yml
$user = 'root';               // Biasanya root, cek MYSQL_ROOT_PASSWORD/USER
$pass = 'password_kamu';      // Password yang kamu set di MYSQL_PASSWORD
$db   = 'swiftmeal_db';       // Nama database yang kamu buat

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
