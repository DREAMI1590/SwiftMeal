<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
// kode selanjutnya...
?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config.php';

// Validasi data
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$label = $_POST['label'] ?? '';
$detail = $_POST['detail'] ?? '';
$is_selected = isset($_POST['is_selected']) ? 1 : 0;

if ($is_selected) {
    // Reset semua alamat jadi tidak utama
    $conn->query("UPDATE addresses SET is_selected = 0 WHERE user_id = $user_id");
}

// Insert alamat baru
$stmt = $conn->prepare("INSERT INTO addresses (user_id, label, detail, is_selected) VALUES (?, ?, ?, ?)");
$stmt->bind_param("issi", $user_id, $label, $detail, $is_selected);
$stmt->execute();

header("Location: ../profile.php");
exit();
?>
