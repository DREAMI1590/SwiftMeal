<?php
require_once('includes/session.php');
require_once ('includes/koneksi.php');

$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : null;

$today = date('Y-m-d');
$sql = "SELECT * FROM promotions WHERE end_date >= ?";
$params = [$today];
$types = "s";

if ($kategori === 'menu' || $kategori === 'exclusive') {
    $sql .= " AND category = ?";
    $params[] = $kategori;
    $types .= "s";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$promotions = [];
while ($row = $result->fetch_assoc()) {
    $promotions[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Promotion - SwiftMeal</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/auth.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="assets/css/menu.css">
  <link rel="stylesheet" href="assets/css/promotion.css">
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
      <a href="promotion.php" class="active">Promotion</a>
      <a href="shop.php">Shop</a>
      <a href="about_us.php">About Us</a>
    </div>
    <div class="navbar-icons">
      <a href="profile.php"><img src="assets/img/base/User.png" alt="User" /></a>
      <a href="cart.php"><img src="assets/img/base/Cart.png" alt="Cart" /></a>
    </div>
  </div>
</nav>

<!-- Konten Promosi -->
<main>
  <section class="promotion-section">
    <!-- TABS KATEGORI -->
    <div class="promo-tabs">
      <a href="promotion.php" class="tab <?= is_null($kategori) ? 'active' : '' ?>">All Promotions</a>
      <a href="promotion.php?kategori=menu" class="tab <?= $kategori === 'menu' ? 'active' : '' ?>">Promo Menu</a>
      <a href="promotion.php?kategori=exclusive" class="tab <?= $kategori === 'exclusive' ? 'active' : '' ?>">Exclusive Offers</a>
    </div>

    <!-- DAFTAR PROMOSI -->
    <div class="promo-wrapper">
      <div class="promo-grid">
        <?php if (!empty($promotions)): ?>
          <?php foreach ($promotions as $promo): ?>
          <div class="promo-card" data-category="<?= htmlspecialchars($promo['category']) ?>">
            <div class="promo-image-wrapper">
              <img src="assets/img/promotion/<?= htmlspecialchars($promo['image']) ?>" alt="<?= htmlspecialchars($promo['title']) ?>">
            </div>
            <h3><?= htmlspecialchars($promo['title']) ?></h3>
            <p><?= htmlspecialchars($promo['description']) ?></p>
            <?php if (!empty($promo['end_date'])): ?>
            <span class="promo-label">
              <i class="fa fa-calendar"></i> Berlaku hingga <?= date('d F Y', strtotime($promo['end_date'])) ?>
            </span>
            <?php endif; ?>
          </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="promo-empty">Tidak ada promosi yang tersedia.</p>
        <?php endif; ?>
      </div>
    </div>
  </section>
</main>

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
      <p>Kami adalah restoran makanan cepat saji yang menghadirkan pengalaman kuliner cepat, lezat, dan terjangkau...</p>
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

<script src="assets/js/promotion.js"></script>
</body>
</html>
