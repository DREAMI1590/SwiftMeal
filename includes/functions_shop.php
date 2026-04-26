<?php
require_once 'koneksi.php';

function get_product_by_id($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM shop_items WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function get_similar_products($category, $exclude_id = null) {
    global $conn;
    if ($exclude_id) {
        $stmt = $conn->prepare("SELECT * FROM shop_items WHERE category = ? AND id != ?");
        $stmt->bind_param("si", $category, $exclude_id);
    } else {
        $stmt = $conn->prepare("SELECT * FROM shop_items WHERE category = ?");
        $stmt->bind_param("s", $category);
    }
    $stmt->execute();
    return $stmt->get_result();
}
?>
