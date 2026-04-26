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

require_once('includes/session.php');
require_once('includes/koneksi.php');

if (!is_logged_in()) {
  $_SESSION['flash_message'] = 'Silakan login terlebih dahulu.';
  $_SESSION['flash_type'] = 'warning';
  header("Location: login.php");
  exit();
}

$user = get_logged_in_user();
$user_id = $user['id'];

$stmt = $conn->prepare("SELECT * FROM addresses WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$addresses_result = $stmt->get_result();
$addresses = [];
$selected_address = null;

while ($addr = $addresses_result->fetch_assoc()) {
  $addresses[] = $addr;
  if ($addr['is_selected']) {
    $selected_address = $addr;
  }
}
if (empty($addresses)) {
  $_SESSION['flash_message'] = 'Silakan tambahkan alamat pengiriman terlebih dahulu.';
  $_SESSION['flash_type'] = 'warning';
  header("Location: profile.php");
  exit();
}
if (!$selected_address) {
  $selected_address = $addresses[0];
}

$sql = "SELECT c.quantity, s.id, s.name, s.price, s.images
        FROM cart c
        JOIN shop_item s ON c.product_id = s.id
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
$cart_subtotal = 0;

while ($row = $result->fetch_assoc()) {
  $images = json_decode($row['images'], true);
  $image_url = 'img/shop/' . ($images[0] ?? 'default.png');
  $total_price = $row['price'] * $row['quantity'];

  $cart_items[] = [
    'id' => $row['id'],
    'name' => $row['name'],
    'price' => $row['price'],
    'portion' => 'Regular',
    'quantity' => $row['quantity'],
    'image' => $image_url,
    'total_price' => $total_price,
  ];

  $cart_subtotal += $total_price;
}

$shipping = 15000;
$discount_percent = 10;
$discount_amount = $cart_subtotal * ($discount_percent / 100);
$tax = 5000;
$grand_total = $cart_subtotal - $discount_amount + $shipping + $tax;
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Checkout - SwiftMeal</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/auth.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="assets/css/menu.css">
  <link rel="stylesheet" href="assets/css/checkout.css">
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar font-inter">
    <div class="navbar-top">
      <div class="navbar-title">
        <a href="index.php">
          <strong>Swift</strong><span style="color: var(--orange);">Meal</span>
        </a>
      </div>
    </div>
    <div class="navbar-bottom">
      <div class="navbar-links">
        <a href="index.php">Home</a>
        <a href="menu.php">Menu</a>
        <a href="promotion.php">Promotion</a>
        <a href="shop.php">Shop</a>
        <a href="about_us.php">About Us</a>
      </div>
      <div class="navbar-icons">
        <?php if (isset($_SESSION['user'])): ?>
          <a href="profile.php"><img src="assets/img/base/User.png" alt="User" /></a>
        <?php else: ?>
          <a href="login.php"><img src="assets/img/base/User.png" alt="User" /></a>
        <?php endif; ?>
        <a href="cart.php"><img src="assets/img/base/Cart.png" alt="Cart" /></a>
      </div>
    </div>
  </nav>

  <!-- Konten Checkout -->
  <div class="checkout-page-wrapper">
    <main class="checkout-container">

      <!-- Kiri -->
      <div class="checkout-left">
        <div class="address-card">
          <div class="address-header">
            <h3><i class="fa fa-map-marker-alt"></i> Shipping Address</h3>
          </div>
          <label for="selected_address">Pilih Alamat Pengiriman:</label>
          <select name="selected_address_id" id="selected_address" form="checkout-form">
            <?php foreach ($addresses as $address): ?>
              <option value="<?= $address['id'] ?>" <?= ($address['id'] == $selected_address['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($address['label']) ?> - <?= htmlspecialchars($address['detail']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="order-summary">
          <?php foreach ($cart_items as $item): ?>
            <div class="order-item">
              <img src="assets/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
              <div class="item-info">
                <h4><?= htmlspecialchars($item['name']) ?></h4>
                <p><?= htmlspecialchars($item['portion']) ?></p>
                <p><?= $item['quantity'] ?> x Rp<?= number_format($item['price'], 0, ',', '.') ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <a href="cart.php" class="back-btn">
          <i class="fa fa-arrow-left"></i> Back to cart
        </a>
      </div>

      <!-- Kanan -->
      <div class="checkout-right">
        <form id="checkout-form" action="/checkout_success.php" method="POST">
          <div class="payment-card">
            <h3>Payment Methods</h3>

            <div class="payment-method">
              <label><input type="radio" name="payment_method" value="COD" checked> <span>Cash on
                  Delivery</span></label>
              <label><input type="radio" name="payment_method" value="OVO"> <span>OVO</span></label>
              <label><input type="radio" name="payment_method" value="DANA"> <span>DANA</span></label>
              <label><input type="radio" name="payment_method" value="GOPAY"> <span>GOPAY</span></label>
            </div>

            <div class="payment-detail">
              <p>Sub-total <span id="subtotal">Rp<?= number_format($cart_subtotal, 0, ',', '.') ?></span></p>
              <p>Shipping <span id="shipping">Rp<?= number_format($shipping, 0, ',', '.') ?></span></p>
              <p>Discount <span id="discount"><?= $discount_percent ?>%
                  (Rp<?= number_format($discount_amount, 0, ',', '.') ?>)</span></p>
              <p>Tax <span id="tax">Rp<?= number_format($tax, 0, ',', '.') ?></span></p>
              <p class="total">Total <span id="total">Rp<?= number_format($grand_total, 0, ',', '.') ?></span></p>
            </div>

            <button class="place-order-btn" type="submit">
              Place an order <i class="fa fa-arrow-right"></i>
            </button>
          </div>
        </form>
      </div>

    </main>
  </div>

  <!-- Footer -->
  <footer class="footer">
    <div class="footer-help">
      <h2><span>Masih</span> Butuh Bantuan dari Kami?</h2>
      <p>Kami siap membantu menjawab semua pertanyaan dan kebutuhan Anda seputar layanan kami.</p>
      <div class="footer-divider"></div>
    </div>

    <div class="footer-container">
      <div class="footer-column">
        <h4>About Us</h4>
        <p>Kami adalah restoran makanan cepat saji yang menghadirkan pengalaman kuliner cepat, lezat, dan terjangkau...
        </p>
        <div class="opening-hours">
          <div class="icon-box"><i class="fas fa-clock"></i></div>
          <div>
            <strong>Opening Hours</strong><br>
            Senin – Sabtu : 08.00 – 18.00<br>
            Minggu : Libur
          </div>
        </div>
      </div>
      <div class="footer-column">
        <h4>Need Help?</h4>
        <a href="about_us.php">FAQ</a><br>
        <a href="about_us.php">Live Chat</a><br>
        <a href="about_us.php">Contact</a>
      </div>
      <div class="footer-column">
        <h4>More Info</h4>
        <a href="about_us.php">Our Team</a><br>
        <a href="about_us.php">Brand Story</a>
      </div>
      <div class="footer-column">
        <h4>New Menu</h4>
        <div class="new-menu-item">
          <div class="menu-icon"><i class="fas fa-image"></i></div>
          <div>
            <p>Nama Makanan</p>
            <small>Hari-Bulan-Tahun</small>
          </div>
        </div>
      </div>
    </div>

    <div class="footer-bottom-wrapper">
      <div class="footer-bottom">
        <p>© SwiftMeal 2025. All Rights Reserved.</p>
        <div class="social-icons">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-youtube"></i></a>
          <a href="#"><i class="fab fa-pinterest-p"></i></a>
        </div>
      </div>
    </div>
  </footer>

  <script src="assets/js/checkout.js"></script>
</body>

</html>