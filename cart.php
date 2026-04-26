<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// kode selanjutnya...
?>

<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once('includes/session.php');
require_once('includes/koneksi.php');

if (!is_logged_in()) {
  header("Location: login.php");
  exit();
}

$user = get_logged_in_user();
$user_id = $user['id'];

$stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_result = $stmt->get_result();

$cart_items = [];
$cart_subtotal = 0;

while ($item = $cart_result->fetch_assoc()) {
  $product_stmt = $conn->prepare("SELECT * FROM shop_item WHERE id = ?");
  $product_stmt->bind_param("i", $item['product_id']);
  $product_stmt->execute();
  $product = $product_stmt->get_result()->fetch_assoc();

  if ($product) {
    $images = json_decode($product['images'], true);
    $image_url = $images[0] ?? 'img/default.png';
    $total_price = $product['price'] * $item['quantity'];

    $cart_items[] = [
      'id' => $product['id'],
      'name' => $product['name'],
      'price_float' => $product['price'],
      'quantity' => $item['quantity'],
      'image_url' => $image_url,
      'total' => $total_price,
    ];

    $cart_subtotal += $total_price;
  }
}

$shipping_charge = 10000;
$total_amount = $cart_subtotal + $shipping_charge;
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SwiftMeal - Keranjang</title>

  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/auth.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="assets/css/menu.css">
  <link rel="stylesheet" href="assets/css/cart.css">
</head>

<body>

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
        <a href="index.php"">Home</a>
        <a href=" menu.php">Menu</a>
        <a href="promotion.php">Promotion</a>
        <a href="shop.php">Shop</a>
        <a href="about_us.php">About Us</a>
      </div>
      <div class="navbar-icons">
        <?php if (isset($_SESSION['user'])): ?>
          <a href="profile.php">
            <img src="assets/img/base/User.png" alt="User" />
          </a>
        <?php else: ?>
          <a href="login.php">
            <img src="assets/img/base/User.png" alt="User" />
          </a>
        <?php endif; ?>
        <a href="cart.php">
          <img src="assets/img/base/Cart.png" alt="Cart" />
        </a>
      </div>
    </div>
  </nav>

  <main>
    <section class="cart-page">
      <h2 class="cart-title">Shopping Cart</h2>

      <?php if (!empty($_SESSION['flash_message'])): ?>
        <div class="alert <?= htmlspecialchars($_SESSION['flash_type']) ?>">
          <?= htmlspecialchars($_SESSION['flash_message']) ?>
        </div>
        <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
      <?php endif; ?>

      <?php if (empty($cart_items)): ?>
        <p>Keranjang Anda kosong.</p>
      <?php else: ?>
        <div class="cart-table">
          <table>
            <thead>
              <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Remove</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($cart_items as $item): ?>
                <tr>
                  <td class="product-info">
                    <img src="assets/img/shop/<?= htmlspecialchars($item['image_url']) ?>"
                      alt="<?= htmlspecialchars($item['name']) ?>">
                    <div>
                      <p class="product-name"><?= htmlspecialchars($item['name']) ?></p>
                    </div>
                  </td>
                  <td class="price" data-price="<?= $item['price_float'] ?>">
                    Rp<?= number_format($item['price_float'], 0, ',', '.') ?>
                  </td>
                  <td>
                    <div class="quantity-control" data-product-id="<?= $item['id'] ?>">
                      <button class="decrement">−</button>
                      <span class="quantity"><?= $item['quantity'] ?></span>
                      <button class="increment">+</button>
                    </div>
                  </td>
                  <td class="total">
                    Rp<?= number_format($item['total'], 0, ',', '.') ?>
                  </td>
                  <td>
                    <form action="remove_from_cart.php" method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus item ini dari keranjang?');">
                      <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                      <button type="submit" class="remove-item">✕</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <div class="cart-bottom">
          <div class="coupon-section">
            <h3>Coupon Code</h3>
            <p>Apakah anda mempunyai kupon? Ayo gunakan sekarang</p>
            <div class="coupon-form">
              <input type="text" placeholder="Enter Here code">
              <button class="apply-btn">Apply</button>
            </div>
          </div>

          <div class="total-bill">
            <h3>Total Bill</h3>
            <div class="bill-details">
              <div class="bill-row">
                <span>Cart Subtotal</span>
                <span class="subtotal-value">
                  Rp<?= number_format($cart_subtotal, 0, ',', '.') ?>
                </span>
              </div>
              <div class="bill-row">
                <span>Shipping Charge</span>
                <span class="shipping-value">
                  Rp<?= number_format($shipping_charge, 0, ',', '.') ?>
                </span>
              </div>
              <div class="bill-row total-amount">
                <span>Total Amount</span>
                <span class="total-value">
                  Rp<?= number_format($total_amount, 0, ',', '.') ?>
                </span>
              </div>
            </div>
          </div>
        </div>

        <div class="checkout-btn-wrapper">
          <a href="checkout.php" class="checkout-btn">
            Proceed to Checkout <span>↗</span>
          </a>
        </div>
      <?php endif; ?>
    </section>
  </main>

  <footer class="footer">
    <div class="footer-help">
      <h2><span>Masih</span> Butuh Bantuan dari Kami?</h2>
      <p>Kami siap membantu menjawab semua pertanyaan dan kebutuhan Anda seputar layanan kami.</p>
      <div class="footer-divider"></div>
    </div>

    <div class="footer-container">
      <div class="footer-column">
        <h4>About Us</h4>
        <p>
          Kami adalah restoran makanan cepat saji yang menghadirkan pengalaman kuliner cepat, lezat, dan terjangkau.
          Dengan bahan-bahan segar dan tim yang berpengalaman, kami berkomitmen untuk menyajikan menu terbaik yang siap
          memuaskan rasa lapar Anda kapan saja.
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

  <script src="assets/js/cart.js"></script>
</body>

</html>