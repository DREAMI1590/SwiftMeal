<?php
// Cegah pemanggilan session_start() dua kali
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/koneksi.php';

// Mengecek apakah user sudah login
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Mengambil data user dari database jika sudah login
function get_logged_in_user() {
    global $conn;

    if (!is_logged_in()) return null;

    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc(); // kembalikan data user sebagai array asosiatif
}
?>
