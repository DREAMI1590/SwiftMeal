<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php
require_once('includes/session.php');
require_once('includes/koneksi.php');

if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

$user = get_logged_in_user();
$user_id = $user['id'];
$product_id = intval($_POST['product_id'] ?? 0);

if ($product_id > 0) {
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();

    $_SESSION['flash_message'] = 'Produk dihapus dari keranjang.';
    $_SESSION['flash_type'] = 'success';
} else {
    $_SESSION['flash_message'] = 'Produk tidak valid.';
    $_SESSION['flash_type'] = 'error';
}

header("Location: cart.php");
exit();
