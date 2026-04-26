<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once('includes/session.php');
require_once('includes/koneksi.php');

if (!is_logged_in()) {
    $_SESSION['flash_message'] = 'Silakan login untuk menambahkan ke keranjang.';
    $_SESSION['flash_type'] = 'warning';
    header("Location: login.php");
    exit();
}

$user = get_logged_in_user();
$user_id = $user['id'];

$product_id = intval($_POST['product_id'] ?? 0);
$quantity = 1;

$product_stmt = $conn->prepare("SELECT * FROM shop_item WHERE id = ?");
$product_stmt->bind_param("i", $product_id);
$product_stmt->execute();
$product = $product_stmt->get_result()->fetch_assoc();

if (!$product) {
    $_SESSION['flash_message'] = 'Produk tidak ditemukan.';
    $_SESSION['flash_type'] = 'danger';
    header("Location: shop.php");
    exit();
}

// Cek apakah produk sudah ada di keranjang
$check_stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
$check_stmt->bind_param("ii", $user_id, $product_id);
$check_stmt->execute();
$existing_item = $check_stmt->get_result()->fetch_assoc();

if ($existing_item) {
    $update_stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
    $update_stmt->bind_param("ii", $user_id, $product_id);
    $update_stmt->execute();
} else {
    $insert_stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
    $insert_stmt->bind_param("iii", $user_id, $product_id, $quantity);
    $insert_stmt->execute();
}

$_SESSION['flash_message'] = 'Produk berhasil ditambahkan ke keranjang.';
$_SESSION['flash_type'] = 'success';
header("Location: https://swiftmeal.42web.io/shop_detail.php?id=$product_id");
exit();


