<?php
require_once 'session.php';
require_once 'koneksi.php';

if (!is_logged_in()) {
    $_SESSION['flash_message'] = 'Silakan login dahulu.';
    $_SESSION['flash_type'] = 'warning';
    header("Location: login.php");
    exit();
}

$user = get_logged_in_user();
$user_id = $user['id'];

$selected_address_id = $_POST['selected_address_id'] ?? null;
$payment_method = $_POST['payment_method'] ?? 'COD';

if (!$selected_address_id) {
    $_SESSION['flash_message'] = 'Alamat belum dipilih.';
    $_SESSION['flash_type'] = 'danger';
    header("Location: checkout.php");
    exit();
}

// Update alamat utama
$stmt = $conn->prepare("UPDATE addresses SET is_selected = false WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$stmt = $conn->prepare("UPDATE addresses SET is_selected = true WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $selected_address_id, $user_id);
$stmt->execute();

// Ambil isi keranjang
$stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_result = $stmt->get_result();

$items_data = [];
$subtotal = 0;

while ($item = $cart_result->fetch_assoc()) {
    $product_stmt = $conn->prepare("SELECT * FROM shop_items WHERE id = ?");
    $product_stmt->bind_param("i", $item['product_id']);
    $product_stmt->execute();
    $product = $product_stmt->get_result()->fetch_assoc();

    if ($product) {
        $price = $product['price'];
        $total = $price * $item['quantity'];
        $subtotal += $total;

        $items_data[] = [
            'product_id' => $product['id'],
            'name' => $product['name'],
            'portion' => $item['quantity'],
            'price' => $price,
            'total' => $total,
        ];
    }
}

if (empty($items_data)) {
    $_SESSION['flash_message'] = 'Keranjang kosong.';
    $_SESSION['flash_type'] = 'warning';
    header("Location: checkout.php");
    exit();
}

// Hitung total
$shipping = 15000;
$discount = $subtotal * 0.10;
$tax = 5000;
$grand_total = $subtotal - $discount + $shipping + $tax;

// Simpan pesanan
$stmt = $conn->prepare("INSERT INTO orders (user_id, address_id, items, total_price, payment_method, status) VALUES (?, ?, ?, ?, ?, 'pending')");
$items_json = json_encode($items_data);
$stmt->bind_param("iisds", $user_id, $selected_address_id, $items_json, $grand_total, $payment_method);
$stmt->execute();

// Kosongkan keranjang
$stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$_SESSION['flash_message'] = 'Pesanan berhasil dibuat!';
$_SESSION['flash_type'] = 'success';
header("Location: checkout_success.php");
exit();
