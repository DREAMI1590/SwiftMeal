<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once ('includes/koneksi.php');

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
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR phone = ? OR email = ?");
        $stmt->bind_param("sss", $username, $phone, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($row['username'] === $username) {
                    $message = 'Username sudah digunakan.';
                    break;
                } elseif ($row['phone'] === $phone) {
                    $message = 'Nomor HP sudah digunakan.';
                    break;
                } elseif ($row['email'] === $email) {
                    $message = 'Email sudah digunakan.';
                    break;
                }
            }
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, phone, email, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $phone, $email, $hashed_password);
            $stmt->execute();

            header("Location: login.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register - SwiftMeal</title>
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
        <a href="beranda.html">
          <strong>Swift</strong><span style="color: var(--orange);">Meal</span>
        </a>
      </div>
    </div>
    <div class="navbar-bottom">
      <div class="navbar-links">
        <a href="beranda.html">Home</a>
        <a href="menu.html">Menu</a>
        <a href="promotion.html">Promotion</a>
        <a href="shop.html">Shop</a>
        <a href="about_us.html">About Us</a>
      </div>
      <div class="navbar-icons">
        <a href="login.html">
          <img src="assets/img/base/User.png" alt="User" />
        </a>
        <a href="cart.html">
          <img src="assets/img/base/Cart.png" alt="Cart" />
        </a>
      </div>
    </div>
  </nav>

  <!-- Konten Register -->
  <main>
    <div class="auth-container">
      <div class="auth-form-box">
        <h2>Sign Up</h2>

        <?php if ($message): ?>
          <div class="alert" style="color: red; margin-bottom: 10px;">
            <?= htmlspecialchars($message) ?>
          </div>
        <?php endif; ?>

        <form method="POST" action="register.php">
          <input type="text" name="username" placeholder="Username" autocomplete="username" required>
          <input type="text" name="phone" placeholder="Phone Number" autocomplete="tel" required>
          <input type="email" name="email" placeholder="Email" autocomplete="email" required>
          <input type="password" name="password" placeholder="Password" autocomplete="new-password" required>

          <button type="submit">Sign Up</button>

          <div class="form-footer">
            Sudah punya akun? <a href="login.php">Masuk di sini</a>
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
        <a href="about_us.html">FAQ</a><br>
        <a href="about_us.html">Live Chat</a><br>
        <a href="about_us.html">Contact</a>
      </div>

      <div class="footer-column">
        <h4>More Info</h4>
        <a href="about_us.html">Our Team</a><br>
        <a href="about_us.html">Brand Story</a>
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
