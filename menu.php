<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php
require_once('includes/session.php');
require_once('backend/config.php');

$query = "SELECT * FROM menu ORDER BY id";
$result = $conn->query($query);

$menu_items = [];
while ($row = $result->fetch_assoc()) {
  $menu_items[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Menu - SwiftMeal</title>

  <!-- CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/auth.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="assets/css/menu.css">
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

  <!-- Konten dinamis -->
  <main>
    <div class="container">

      <!-- DAFTAR MENU -->
      <section class="menu-list-section">
        <?php foreach ($menu_items as $index => $item): ?>
          <div class="menu-section <?= ($index % 2 == 1) ? 'reverse' : '' ?>">
            <div class="menu-left">
              <img src="assets/img/menu/<?= htmlspecialchars($item['image']) ?>"
                alt="<?= htmlspecialchars($item['name']) ?>">
            </div>
            <div class="menu-right">
              <div class="menu-header">
                <h1><?= htmlspecialchars($item['name']) ?></h1>
                <span class="price">Rp <?= number_format($item['price'], 0, ",", ".") ?></span>
              </div>
              <?php if (!empty($item['description'])): ?>
                <h3><?= htmlspecialchars($item['description']) ?></h3>
              <?php endif; ?>
              <p class="cal"><?= htmlspecialchars($item['calories'] ?? '0') ?> CAL</p>
            </div>
          </div>
        <?php endforeach; ?>
      </section>

    </div>
  </main>

  <!-- Footer -->
  <footer class="footer">
    <!-- Bantuan Section -->
    <div class="footer-help">
      <h2><span>Masih</span> Butuh Bantuan dari Kami?</h2>
      <p>Kami siap membantu menjawab semua pertanyaan dan kebutuhan Anda seputar layanan kami.</p>
      <div class="footer-divider"></div>
    </div>

    <!-- Main Footer Content -->
    <div class="footer-container">
      <!-- About Us -->
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

      <!-- Need Help -->
      <div class="footer-column">
        <h4>Need Help?</h4>
        <a href="about_us.php">FAQ</a><br>
        <a href="about_us.php">Live Chat</a><br>
        <a href="about_us.php">Contact</a>
      </div>

      <!-- More Info -->
      <div class="footer-column">
        <h4>More Info</h4>
        <a href="about_us.php">Our Team</a><br>
        <a href="about_us.php">Brand Story</a>
      </div>

      <!-- New Menu -->
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

    <!-- Footer Bottom -->
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

  <script src="assets/js/menu.js"></script>
</body>

</html>