<?php
// about_us.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tentang Kami - SwiftMeal</title>

  <!-- CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/auth.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="assets/css/menu.css">
  <link rel="stylesheet" href="assets/css/about_us.css">
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

  <!-- Konten -->
  <main>
    <div class="about-section">
      <div class="parent">
        <div class="div1">
          <div class="about-img-card">
            <img src="assets/img/about us/about us gambar 1.png" alt="Deskripsi">
          </div>
        </div>

        <div class="div2">
          <div class="about-img-card">
            <img src="assets/img/about us/about us gambar 2.png" alt="Deskripsi">
          </div>
        </div>

        <div class="div3">
          <div class="about-img-card">
            <img src="assets/img/about us/about us gambar 3.png" alt="Deskripsi">
          </div>
        </div>
        <div class="about-content">
          <span class="section-subtitle">Tentang Kami</span>
          <h2 class="section-title">SwiftMeal: Solusi Cepat & Lezat</h2>
          <p class="section-description">
            Di SwiftMeal, kami membuktikan bahwa makanan enak tak perlu menunggu lama.
            Sebagai pelopor food-on-demand, kami menyajikan hidangan lezat dengan kecepatan layanan siap saji,
            namun dengan kualitas rasa bak restoran premium. Setiap menu kami dibuat oleh koki berpengalaman
            menggunakan bahan-bahan segar pilihan, diproses secara higienis, dan diolah dengan teknik
            khusus untuk mempertahankan cita rasa otentik meski disajikan dalam waktu singkat.
          </p>
        </div>
      </div>
    </div>

    <section class="team-section">
      <div class="team-header">
        <h2>Team Member</h2>
        <p>Kami adalah tim profesional di bidang kuliner dan bisnis yang berkomitmen menghadirkan makanan cepat saji
          berkualitas.
          Dengan pengalaman luas di industri restoran, kami menciptakan SwiftMeal untuk memberikan pengalaman makan terbaik
          - cepat, lezat,
          dan memuaskan.</p>
      </div>

      <div class="row">
        <div class="column">
          <div class="card">
            <div class="team-img">
              <img src="assets/img/about us/user-ilyassa.png" alt="Ilyassa">
            </div>
            <div class="container">
              <h2>Ilyassa</h2>
            </div>
          </div>
        </div>

        <div class="column">
          <div class="card">
            <div class="team-img">
              <img src="assets/img/about us/user-fahrizal.png" alt="Fahrizal">
            </div>
            <div class="container">
              <h2>Fahrizal</h2>
            </div>
          </div>
        </div>

        <div class="column">
          <div class="card">
            <div class="team-img">
              <img src="assets/img/about us/user-tegar.png" alt="Tegar">
            </div>
            <div class="container">
              <h2>Tegar</h2>
            </div>
          </div>
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

</body>

</html>
