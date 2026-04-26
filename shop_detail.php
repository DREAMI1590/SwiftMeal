<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once('includes/session.php');
require_once('includes/koneksi.php');

if (!isset($_GET['id'])) {
  header("Location: shop.php");
  exit;
}

$product_id = intval($_GET['id']);

// ambil data produk langsung dari shop_item
$stmt = $conn->prepare("SELECT * FROM shop_item WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
  $_SESSION['flash_error'] = "Produk tidak ditemukan.";
  header("Location: shop.php");
  exit;
}

// ambil gambar dari kolom images (JSON)
$product_images = json_decode($product['images'], true);
if (!is_array($product_images)) {
  $product_images = [];
}

$stmt = $conn->prepare("
    SELECT r.*, u.username 
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    WHERE r.product_id = ?
    ORDER BY r.timestamp DESC
");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$reviews = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$stmt = $conn->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as review_count FROM reviews WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$rating_data = $stmt->get_result()->fetch_assoc();

$avg_rating = round($rating_data['avg_rating'] ?? 0, 1);
$review_count = $rating_data['review_count'] ?? 0;

$has_purchased = false;
$eligible_orders = [];

if (is_logged_in()) {
  $user_id = $_SESSION['user_id'];
  $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ?");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

  foreach ($orders as $order) {
    $items = json_decode($order['items'], true);
    foreach ($items as $item) {
      if ($item['product_id'] == $product_id) {
        $stmt_check = $conn->prepare("SELECT id FROM reviews WHERE user_id = ? AND product_id = ? AND order_id = ?");
        $stmt_check->bind_param("iii", $user_id, $product_id, $order['id']);
        $stmt_check->execute();
        $review_exists = $stmt_check->get_result()->fetch_assoc();

        if (!$review_exists) {
          $eligible_orders[] = $order['id'];
        }

        $has_purchased = true;
        break;
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars((string) $product['name']) ?> | SwiftMeal</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/auth.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="assets/css/menu.css">
  <link rel="stylesheet" href="assets/css/shop_detail.css">
</head>

<body>
  <nav class="navbar font-inter">
    <div class="navbar-top">
      <div class="navbar-title">
        <a href="index.php"><strong>Swift</strong><span style="color: var(--orange);">Meal</span></a>
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
        <a href="profile.php"><img src="assets/img/base/User.png" alt="User" /></a>
        <a href="cart.php"><img src="assets/img/base/Cart.png" alt="Cart" /></a>
      </div>
    </div>
  </nav>

  <main>
    <div class="shop-detail-wrapper">
      <div class="product-detail-container">

        <!-- PRODUCT GALLERY -->
        <div class="product-gallery">
          <?php if (!empty($product_images)): ?>
            <div class="gallery-wrapper">
              <div class="product-thumbnails vertical-thumbnails">
                <?php foreach ($product_images as $img): ?>
                  <img src="assets/img/shop/<?= htmlspecialchars($img) ?>" alt="Thumbnail" class="thumbnail">
                <?php endforeach; ?>
              </div>
              <div class="main-preview-image">
                <img id="mainImage" src="assets/img/shop/<?= htmlspecialchars($product_images[0]) ?>" alt="Main Image">
              </div>
            </div>
          <?php else: ?>
            <div class="main-preview-image">
              <img
                src="<?= !empty($product['image']) ? htmlspecialchars($product['image']) : 'assets/img/base/no_image.png' ?>"
                alt="Main Image">
            </div>
          <?php endif; ?>
        </div> <!-- END PRODUCT GALLERY -->

        <!-- PRODUCT INFO (pindah di sini sebagai sibling gallery) -->
        <div class="product-info">
          <h2 class="product-title"><?= htmlspecialchars((string) $product['name']) ?></h2>
          <p class="product-description"><?= nl2br(htmlspecialchars((string) $product['description'])) ?></p>
          <p class="product-price">Rp<?= number_format($product['price'], 0, ',', '.') ?></p>

          <div class="product-rating">
            <?php for ($i = 1; $i <= 5; $i++): ?>
              <span class="fa fa-star<?= $i <= round($avg_rating) ? ' checked' : '' ?>"></span>
            <?php endfor; ?>
            <span class="rating-score">(<?= $review_count ?> ulasan)</span>
          </div>

          <?php if (is_logged_in()): ?>
            <form action="add_to_cart.php" method="post" class="add-to-cart-form">
              <input type="hidden" name="product_id" value="<?= $product_id ?>">
              <button class="add-to-cart-btn" type="submit">Tambah ke Keranjang</button>
            </form>
          <?php else: ?>
            <a href="login.php" class="add-to-cart-btn">Login untuk membeli</a>
          <?php endif; ?>

          <?php if (isset($_SESSION['flash_cart'])): ?>
            <div class="flash-message-local">
              <div class="flash-cart"><?= htmlspecialchars($_SESSION['flash_cart']) ?></div>
            </div>
            <?php unset($_SESSION['flash_cart']); ?>
          <?php endif; ?>

          <div class="product-meta">
            <p>Kategori: <?= htmlspecialchars((string) ($product['category'] ?? '')) ?></p>
            <p>
              Share :
              <a href="#"><i class="fab fa-facebook-f"></i></a>
              <a href="#"><i class="fab fa-instagram"></i></a>
            </p>
          </div>
        </div> <!-- END PRODUCT INFO -->
      </div> <!-- Tutup .product-detail-container -->

      <!-- PRODUCT TABS -->
      <div class="product-tabs">
        <button class="tab active" data-tab="description">Description</button>
        <button class="tab" data-tab="reviews">Reviews (<?= $review_count ?>)</button>
      </div>

      <!-- WRAPPER UNTUK TAB KONTEN -->
      <div class="tab-content-wrapper">
        <div class="tab-content active" id="description">
          <div class="product-description-full">
            <p><?= nl2br(htmlspecialchars((string) $product['description'])) ?></p>
          </div>
        </div>

        <div class="tab-content" id="reviews">
          <?php if (!empty($reviews)): ?>
            <?php foreach ($reviews as $rev): ?>
              <div class="review">
                <strong><?= htmlspecialchars($rev['username']) ?></strong>
                <span class="review-stars">
                  <?php for ($i = 1; $i <= 5; $i++): ?>
                    <span class="fa fa-star<?= $i <= $rev['rating'] ? ' checked' : '' ?>"></span>
                  <?php endfor; ?>
                </span>
                <p class="review-comment"><?= nl2br(htmlspecialchars($rev['comment'])) ?></p>
                <small class="review-time"><?= htmlspecialchars($rev['timestamp']) ?></small>

                <?php if (!empty($rev['admin_reply'])): ?>
                  <div class="admin-reply">
                    <strong>Admin:</strong> <?= htmlspecialchars($rev['admin_reply']) ?>
                  </div>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p>Belum ada review untuk produk ini.</p>
          <?php endif; ?>

          <!-- Form Review -->
          <?php if (is_logged_in() && $has_purchased && !empty($eligible_orders)): ?>
            <h3>Tambahkan Review</h3>
            <form class="review-form" method="POST" action="backend/submit_review.php">
              <input type="hidden" name="product_id" value="<?= $product_id ?>">
              <div class="form-group">
                <label for="order_id">Pilih Pembelian:</label>
                <select id="order_id" name="order_id" required>
                  <option value="">-- Pilih --</option>
                  <?php foreach ($eligible_orders as $oid): ?>
                    <option value="<?= $oid ?>">Order #<?= $oid ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="form-group">
                <label for="rating">Rating (1-5):</label>
                <select id="rating" name="rating" required>
                  <?php for ($i = 1; $i <= 5; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?></option>
                  <?php endfor; ?>
                </select>
              </div>

              <div class="form-group">
                <label for="comment">Komentar:</label>
                <textarea id="comment" name="comment" rows="4" placeholder="Tulis komentar Anda di sini..."
                  required></textarea>
                <button type="submit" class="submit-review-btn">Kirim Review</button>
              </div>
            </form>
          <?php elseif (is_logged_in()): ?>
            <p>Anda sudah memberikan review untuk produk ini.</p>
          <?php else: ?>
            <p><a href="login.php">Login</a> untuk memberikan review.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
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
        <p>Kami adalah restoran makanan cepat saji yang menghadirkan pengalaman kuliner cepat, lezat, dan terjangkau.
          Dengan bahan-bahan segar dan tim yang berpengalaman, kami berkomitmen untuk menyajikan menu terbaik yang siap
          memuaskan rasa lapar Anda kapan saja.</p>
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

  <script src="assets/js/shop_detail.js"></script>
</body>

</html>