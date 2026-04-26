<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['address_id'])) {
    $address_id = intval($_POST['address_id']);

    // Reset semua alamat jadi tidak utama
    $stmt = $conn->prepare("UPDATE addresses SET is_selected = 0 WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    // Set alamat ini jadi utama
    $stmt = $conn->prepare("UPDATE addresses SET is_selected = 1 WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $address_id, $user_id);
    $stmt->execute();

    header("Location: ../profile.php");
    exit();
} else {
    header("Location: ../profile.php");
    exit();
}
