<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once ('includes/koneksi.php');

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    header("Location: profile.php");
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$phone || !$email || !$password) {
        $message = 'Harap isi semua field.';
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND phone = ? AND email = ?");
        $stmt->bind_param("sss", $username, $phone, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin'];
            $_SESSION['flash_message'] = 'Berhasil login!';
            $_SESSION['flash_type'] = 'success';

            header("Location: profile.php");
            exit();
        } else {
            $message = 'Username, nomor HP, email, atau password salah.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - SwiftMeal</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/auth.css">
  <link rel="stylesheet" href="assets/css/menu.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
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

  <main>
    <div class="auth-container">
      <div class="auth-form-box">
        <h2>Sign In</h2>

        <!-- Flash message -->
        <?php if (!empty($_SESSION['flash_message'])): ?>
          <div class="alert <?= htmlspecialchars($_SESSION['flash_type'] ?? 'info') ?>">
              <?= htmlspecialchars($_SESSION['flash_message']) ?>
          </div>
          <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
        <?php endif; ?>

        <!-- Error message -->
        <?php if ($message): ?>
          <div class="alert warning"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
          <input type="text" name="username" placeholder="Username" required>
          <input type="text" name="phone" placeholder="Phone Number" autocomplete="tel" required>
          <input type="email" name="email" placeholder="Email" autocomplete="email" required>
          <input type="password" name="password" placeholder="Password" required>

          <button type="submit">Login</button>

          <div class="form-footer">
            Belum punya akun? <a href="register.php">Daftar di sini</a>
          </div>
        </form>
      </div>
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
