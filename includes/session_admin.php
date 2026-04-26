<?php
session_start();
require_once 'koneksi.php';

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function get_logged_in_user() {
    global $conn;
    if (!is_logged_in()) return null;

    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function is_admin_logged_in() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}
?>
