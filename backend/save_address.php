<?php
session_start();
require_once 'config.php'; // Koneksi ke database

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$label = $_POST['label'] ?? '';
$detail = $_POST['detail'] ?? '';
$is_selected = isset($_POST['is_selected']) ? 1 : 0;

if (empty($label) || empty($detail)) {
    echo "Label dan detail tidak boleh kosong.";
    exit();
}

if ($is_selected === 1) {
    $stmt = $conn->prepare("UPDATE addresses SET is_selected = 0 WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
}

$stmt = $conn->prepare("INSERT INTO addresses (user_id, label, detail, is_selected) VALUES (?, ?, ?, ?)");
$stmt->bind_param("issi", $user_id, $label, $detail, $is_selected);

if ($stmt->execute()) {
    header("Location: profile.php");
    exit();
} else {
    echo "Gagal menyimpan alamat: " . $stmt->error;
}
?>
