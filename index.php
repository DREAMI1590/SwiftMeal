<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Beranda - SwiftMeal</title>

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
          <strong>Swift</strong><span style="color: var(--orange);">Ilyassa</span>
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

  <!-- Konten Beranda -->
  <main>
    <div class="beranda dark-background">

      <!-- Hero Section -->
      <section class="hero font-inter">
        <div class="hero-social">
          <div class="line"></div>
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-x-twitter"></i></a>
          <a href="#"><i class="fab fa-pinterest-p"></i></a>
          <div class="line"></div>
        </div>

        <div class="hero-text">
          <h3 class="font-great-vibes">Klik, Langsung Kenyang!</h3>
          <h1>
            Seni <span class="highlight-top">Kecepatan</span><br>
            Rasa <span class="highlight-bottom">Berkualitas</span>
          </h1>
          <p>Lapar? Jangan Tunda! Order Makanan Favoritmu<br>dengan Mudah dan Cepat</p>
          <button class="btn" onclick="location.href='menu.php'">See Menu</button>
        </div>

        <div class="hero-image">
          <img src="assets/img/home/hero-image.png" alt="Hero Image">
        </div>
      </section>

      <!-- Menu Paling Laris -->
      <section class="popular-menu">
        <div class="popular-content">
          <div class="popular-text">
            <p class="section-title font-great-vibes">Menu Paling Laris</p>
            <h2 class="section-heading"><span>Kami</span> Sajikan Makanan <br> Terbaik Cepat dan Lezat</h2>
            <p class="section-description">
              Nikmati hidangan lezat yang disajikan dengan cepat tanpa mengorbankan kualitas rasa.
              Kami selalu memastikan setiap menu menggunakan bahan-bahan segar pilihan, dipersiapkan dengan teliti,
              dan disajikan dengan kecepatan tinggi agar Anda bisa menikmati pengalaman makan yang sempurna dalam setiap gigitan.
            </p>
            <ul class="checklist">
              <li>Masakan Siap Saji Terbaik Bahan Segar Tersaji Cepat!</li>
              <li>Pengalaman Kuliner Cepat Lezat dan Praktis Bersama Kami</li>
              <li>Rasa Otentik dalam Setiap Menu Tersaji dengan Cepat dan Nikmat</li>
            </ul>
            <button class="btn" onclick="location.href='shop.php'">See More</button>
          </div>
          <div class="popular-images">
            <div class="large-image">
              <img src="assets/img/home/popular-large.png" alt="Popular Menu">
            </div>
            <div class="small-images">
              <div class="small-image">
                <img src="assets/img/home/popular-small1.png" alt="Makanan Populer 1">
              </div>
              <div class="small-image">
                <img src="assets/img/home/popular-small2.png" alt="Makanan Populer 2">
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Kategori Makanan -->
      <section class="kategori-makanan">
        <div class="text-container">
          <p class="section-title">Kategori Makanan</p>
          <h2 class="section-heading">
            <span class="highlight-top">Pilih</span> Menu Makanan
          </h2>
        </div>

        <div class="kategori-grid" id="kategoriScroll">
          <?php for ($i = 1; $i <= 12; $i++): ?>
            <div class="card">
              <img src="assets/img/home/kategori<?= $i ?>.png" alt="Kategori <?= $i ?>">
            </div>
          <?php endfor; ?>
        </div>

        <div class="carousel-dots">
          <span class="dot active"></span>
          <span class="dot"></span>
          <span class="dot"></span>
        </div>
      </section>

      <!-- Promo Section -->
      <section class="promo-section">
        <div class="promo-container">
          <div class="promo-images">
            <div class="promo-img-box big">
              <img src="assets/img/home/promo1.png" alt="Promo besar 1" />
            </div>
            <div class="promo-img-box small">
              <img src="assets/img/home/promo2.png" alt="Promo kecil 1" />
            </div>
            <div class="promo-img-box big">
              <img src="assets/img/home/promo3.png" alt="Promo besar 2" />
            </div>
            <div class="promo-img-box small">
              <img src="assets/img/home/promo4.png" alt="Promo kecil 2" />
            </div>
          </div>
          <div class="promo-text">
            <p class="section-title font-great-vibes">Promo Menarik Setiap Hari!</p>
            <h2><span class="highlight-top">Diskon</span> Spesial untuk<br>Menu Pilihan!</h2>
            <p class="promo-description">
              Nikmati berbagai hidangan lezat dengan kualitas terbaik dan harga yang sangat terjangkau.
              Setiap minggu, kami menawarkan promo-promo menarik yang memungkinkan Anda menikmati makanan favorit
              dengan harga lebih hemat, tanpa mengurangi kualitas rasa yang selalu memuaskan.
            </p>
            <div class="promo-categories">
              <a href="promotion.php" class="promo-category" onclick="setPromoFilter('all')">All Promotions</a>
              <a href="promotion.php" class="promo-category" onclick="setPromoFilter('menu')">Promo Menu</a>
              <a href="promotion.php" class="promo-category" onclick="setPromoFilter('exclusive')">Exclusive Offers</a>
              <script>
                function setPromoFilter(filter) {
                  localStorage.setItem('promoFilter', filter);
                }
              </script>
            </div>
            <button class="btn" onclick="location.href='promotion.php'">See More</button>
          </div>
        </div>
      </section>

    </div>
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
          Dengan bahan-bahan segar dan tim yang berpengalaman, kami berkomitmen untuk menyajikan menu terbaik yang siap memuaskan rasa lapar Anda kapan saja.
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

  <!-- Script -->
  <script src="assets/js/script.js"></script>
</body>

</html>
