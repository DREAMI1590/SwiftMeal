<?php
require_once '../includes/session.php';
require_once '../includes/koneksi.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Pastikan user sudah login
if (!is_logged_in()) {
    $_SESSION['flash_error'] = "Kamu harus login terlebih dahulu.";
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'] ?? 0;
    $product_id = intval($_POST['product_id'] ?? 0);
    $order_id = intval($_POST['order_id'] ?? 0);
    $rating = intval($_POST['rating'] ?? 0);
    $review_text = trim($_POST['comment'] ?? '');

    // Validasi awal
    if (!$product_id || !$order_id || !$rating || $review_text === '') {
        $_SESSION['flash_warning'] = "Semua field wajib diisi.";
        header("Location: ../shop_detail.php?id=" . $product_id);
        exit;
    }

    // Cek apakah order milik user
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
    if (!$stmt) {
        die("Query Error (orders check): " . $conn->error);
    }
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();

    if (!$order) {
        $_SESSION['flash_error'] = "Order tidak ditemukan.";
        header("Location: ../shop_detail.php?id=" . $product_id);
        exit;
    }

    // Cek apakah produk ada di dalam order
    $items = json_decode($order['items'], true);
    $contains_product = false;
    if (is_array($items)) {
        foreach ($items as $item) {
            if (isset($item['product_id']) && $item['product_id'] == $product_id) {
                $contains_product = true;
                break;
            }
        }
    }

    if (!$contains_product) {
        $_SESSION['flash_error'] = "Produk tidak ada dalam order tersebut.";
        header("Location: ../shop_detail.php?id=" . $product_id);
        exit;
    }

    // Cek apakah sudah pernah review untuk order ini
    $stmt = $conn->prepare("SELECT id FROM reviews WHERE user_id = ? AND product_id = ? AND order_id = ?");
    if (!$stmt) {
        die("Query Error (review check): " . $conn->error);
    }
    $stmt->bind_param("iii", $user_id, $product_id, $order_id);
    $stmt->execute();
    $existing = $stmt->get_result()->fetch_assoc();

    if ($existing) {
        $_SESSION['flash_info'] = "Kamu sudah memberikan review untuk pembelian ini.";
        header("Location: ../shop_detail.php?id=" . $product_id);
        exit;
    }

    // Simpan review baru
    $stmt = $conn->prepare("
        INSERT INTO reviews (user_id, product_id, order_id, rating, comment, timestamp)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    if (!$stmt) {
        die("Query Error (insert review): " . $conn->error);
    }
    $stmt->bind_param("iiiis", $user_id, $product_id, $order_id, $rating, $review_text);

    if ($stmt->execute()) {
        $_SESSION['flash_success'] = "Review berhasil dikirim!";
        header("Location: ../shop_detail.php?id=" . $product_id);
        exit;
    } else {
        die("❌ Gagal menyimpan review: " . $stmt->error);
    }

} else {
    header("Location: ../shop.php");
    exit;
}
?>
