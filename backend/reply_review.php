<?php
require_once '../session_admin.php';
require_once '../koneksi.php';

// Pastikan hanya admin yang bisa mengakses
if (!is_admin_logged_in()) {
    $_SESSION['flash_error'] = "Akses ditolak.";
    header("Location: login_admin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review_id = intval($_POST['review_id'] ?? 0);
    $reply_text = trim($_POST['reply'] ?? '');

    if ($review_id === 0) {
        $_SESSION['flash_error'] = "ID review tidak valid.";
        header("Location: reviews_admin.php");
        exit;
    }

    if ($reply_text === '') {
        $_SESSION['flash_error'] = "Balasan tidak boleh kosong.";
        header("Location: reviews_admin.php");
        exit;
    }

    // Update admin_reply di review
    $stmt = $conn->prepare("UPDATE reviews SET admin_reply = ? WHERE id = ?");
    $stmt->bind_param("si", $reply_text, $review_id);
    $stmt->execute();

    $_SESSION['flash_success'] = "Balasan berhasil dikirim.";
    header("Location: reviews_admin.php");
    exit;
} else {
    header("Location: reviews_admin.php");
    exit;
}
