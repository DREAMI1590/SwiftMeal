<?php
require_once('includes/session.php');
require_once('includes/koneksi.php');

$query = "SELECT * FROM shop_item ORDER BY id ASC";
$result = $conn->query($query);

$products = [];

while ($row = $result->fetch_assoc()) {
  $images = json_decode($row['images'], true);
  if (!is_array($images))
    $images = [];

  $first_image = count($images) > 0 ? "assets/img/shop/" . $images[0] : "assets/img/default.png";

  $products[] = [
    "id" => $row['id'],
    "name" => $row['name'],
    "price" => number_format((int) $row['price'], 0, ',', '.'),
    "image" => $first_image,
    "category" => !empty($row['category']) ? $row['category'] : "Lainnya"
  ];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Shop - SwiftMeal</title>

  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/auth.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="assets/css/menu.css">
  <link rel="stylesheet" href="assets/css/shop.css">
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
        <a href="profile.php">
          <img src="assets/img/base/User.png" alt="User" />
        </a>
        <a href="cart.php">
          <img src="assets/img/base/Cart.png" alt="Cart" />
        </a>
      </div>
    </div>
  </nav>

  

      <!-- Produk -->
      <div class="shop-products-wrapper">
        <div class="shop-products" id="cardContainer">
          <?php foreach ($products as $product): ?>
            <?php
            $category_class = strtolower(str_replace([' ', '_'], '-', $product['category']));
            ?>
            <div class="shop-card filterDiv <?= htmlspecialchars($category_class) ?>">
              <a href="shop_detail.php?id=<?= $product['id'] ?>" class="shop-card-image">
                <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
              </a>
              <div class="shop-card-content">
                <h4><?= htmlspecialchars($product['name']) ?></h4>
                <p class="price"><span aria-label="Harga">Rp <?= htmlspecialchars($product['price']) ?></span></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Sidebar kanan: Search dan Filter Kategori -->
        <aside class="shop-sidebar">
          <input type="text" id="searchInput" placeholder="Cari produk..." onkeyup="filterBySearch()">

          <h4 class="sidebar-category-title">Kategori</h4>

          <div class="filter-buttons-column" id="sidebarFilterBtnContainer">
            <button class="btn active" onclick="filterSelection('all')">Semua</button>
            <button class="btn" onclick="filterSelection('hidangan-favorit')">Hidangan Favorit</button>
            <button class="btn" onclick="filterSelection('camilan-gurih')">Camilan Gurih</button>
            <button class="btn" onclick="filterSelection('sajian-lezat')">Sajian Lezat</button>
            <button class="btn" onclick="filterSelection('minuman-dingin')">Minuman Hangat</button>
          </div>
        </aside>
      </div>
      <p id="noResultsMsg" style="display:none; text-align:center;">Produk tidak ditemukan.</p>

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

  <script src="assets/js/shop.js"></script>
</body>

</html>
