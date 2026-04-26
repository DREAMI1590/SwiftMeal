<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Koneksi database
$host = "sql211.infinityfree.com";
$user = "if0_40042420";
$password = "C8apLwQUNqa";
$dbname = "if0_40042420_db_swiftmeal";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi login
function is_logged_in()
{
  return isset($_SESSION['user_id']);
}

function get_logged_in_user()
{
  global $conn;
  if (!is_logged_in())
    return null;
  $user_id = $_SESSION['user_id'];
  $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  return $result->fetch_assoc();
}

$user = get_logged_in_user();
if (!$user) {
    // kalau user null, redirect ke login
    header("Location: login.php");
    exit();
}
$user_id = $user['id'];

// Ambil alamat
$stmt = $conn->prepare("SELECT * FROM addresses WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$addresses_result = $stmt->get_result();
$addresses = [];
while ($row = $addresses_result->fetch_assoc()) {
  $addresses[] = $row;
}

// Ambil pesanan
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY timestamp DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders_result = $stmt->get_result();
$orders = [];
while ($row = $orders_result->fetch_assoc()) {
  $orders[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profil - SwiftMeal</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/auth.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="assets/css/menu.css">
  <link rel="stylesheet" href="assets/css/profile.css">
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
    <div class="profile-container">
      <div class="profile-sidebar">
        <button class="sidebar-btn active" data-target="profile-info"><i class="fa fa-user"></i> Profile
          Information</button>
        <button class="sidebar-btn" data-target="saved-address"><i class="fa fa-home"></i> Saved Address</button>
        <button class="sidebar-btn" data-target="history"><i class="fa fa-history"></i> History</button>
        <a href="logout.php" class="sidebar-btn"><i class="fa fa-sign-out"></i> Log Out</a>
      </div>

      <div class="profile-content profile-main">
        <div id="profile-info" class="profile-section active">
          <h2>My Profile</h2>
          <div class="profile-avatar">
            <i class="fa fa-user-circle"></i>
          </div>
          <form class="profile-form" method="POST" action="profile.php">
            <div class="form-group">
              <label>Username</label>
              <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" readonly>
            </div>
            <div class="form-group">
              <label>Phone Number</label>
              <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" readonly>
            </div>
            <div class="form-group">
              <label>Email</label>
              <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" readonly>
            </div>
          </form>
        </div>

        <div id="saved-address" class="profile-section">
          <h2>Saved Address</h2>

          <div id="address-form" class="address-form" style="display: none;">
            <form method="POST" action="backend/add_address.php">
              <input type="text" name="label" placeholder="Label (contoh: Rumah)" required>
              <textarea name="detail" placeholder="Detail Alamat" required maxlength="95"></textarea>

              <label style="display: flex; align-items: center; gap: 8px; margin-top: 8px;">
                <input type="checkbox" name="is_selected">
                Jadikan alamat utama
              </label>

              <button type="submit">Simpan Alamat</button>
              <button type="button" onclick="hideAddressForm()">Batal</button>
            </form>
          </div>

          <div class="address-card add-new" onclick="showAddressForm()">
            <div class="icon">
              <i class="fa fa-plus-circle"></i>
            </div>
            <div class="address-info">
              <strong>Add New Address</strong>
              <p>Save your favorite delivery location</p>
            </div>
          </div>

          <?php foreach ($addresses as $address): ?>
            <div class="address-card">
              <?php if ($address['is_selected']): ?>
                <div class="main-address-label">Alamat Utama</div>
              <?php else: ?>
                <form method="POST" action="backend/set_main_address.php">
                  <input type="hidden" name="address_id" value="<?= $address['id'] ?>">
                  <button type="submit" class="set-main-btn">Set as Main Address</button>
                </form>
              <?php endif; ?>
              <div class="icon"><i class="fa fa-map-marker-alt"></i></div>
              <div class="address-info">
                <strong><?= htmlspecialchars($address['label']) ?></strong>
                <p title="<?= htmlspecialchars($address['detail']) ?>">
                  <?= htmlspecialchars(substr($address['detail'], 0, 95)) ?>  <?= strlen($address['detail']) > 95 ? '...' : '' ?>
                </p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>


        <div id="history" class="profile-section">
          <h2>History</h2>
          <div class="history-list">
            <?php if (!empty($orders)): ?>
              <?php foreach ($orders as $order): ?>
                <div class="history-item">
                  <div class="history-details">
                    <?php $items = json_decode($order['items'], true); ?>
                    <?php foreach ($items as $item): ?>
                      <h3><?= htmlspecialchars($item['name']) ?></h3>
                      <p><?= $item['portion'] ?>x - Rp <?= number_format($item['total'], 0, ',', '.') ?></p>
                    <?php endforeach; ?>
                    <p><strong>Total: Rp <?= number_format($order['total_price'], 0, ',', '.') ?></strong></p>
                  </div>
                  <div class="history-time"><?= date('H:i, d M Y', strtotime($order['timestamp'])) ?></div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p>Belum ada riwayat pesanan.</p>
            <?php endif; ?>
          </div>
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

  <script src="assets/js/profile_v2.js"></script>
</body>

</html>