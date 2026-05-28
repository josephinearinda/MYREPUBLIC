<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role']!="pelanggan"){
    // jika belum login atau bukan pelanggan, arahkan ke pemilihan role/login
    header("Location: pilih_role.php");
    exit;
}

$id_user = $_SESSION['user']['id'];

/* CEK TRANSAKSI TERAKHIR */
$cek = mysqli_query($conn,"
    SELECT * FROM transaksi 
    WHERE user_id='$id_user'
    ORDER BY id DESC
    LIMIT 1
");

$data = mysqli_fetch_assoc($cek);

$status_layanan = "Tidak Aktif";
$tagihan = 0;

$paketHarga = [
    'Super Seru' => 350000,
    'Gaming Pro' => 500000,
    'Family Plan' => 300000,
    'Ultra Max' => 750000
];

if($data){
    if($data['status'] == "Diproses"){
        $status_layanan = "Menunggu Pembayaran";
        $tagihan = isset($data['harga']) ? $data['harga'] : ($paketHarga[$data['paket']] ?? 0);
    }
    elseif($data['status'] == "Lunas"){
        $status_layanan = "Aktif";
        $tagihan = 0;
    }
}

$notifCount = mysqli_query($conn, "SELECT COUNT(*) as total FROM notifikasi WHERE user_id='$id_user' AND status='Belum dibaca'");
$unread_notifikasi = mysqli_fetch_assoc($notifCount)['total'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Home - MyRepublic</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    background: #f4f8fc;
    color: #334758;
}

/* ===== NAVBAR ===== */
.navbar {
    background: linear-gradient(135deg, #0f2027 0%, #1c92d2 45%, #2c5364 100%);
    padding: 24px 20px;
    color: white;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}

.nav-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
    flex-wrap: wrap;
    gap: 12px;
}

.nav-top h2 {
    margin: 0;
    font-size: 26px;
    font-weight: 700;
}

.nav-top small {
    display: block;
    color: rgba(255, 255, 255, 0.8);
    font-size: 13px;
}

.nav-top a {
    padding: 10px 18px;
    background: rgba(255, 255, 255, 0.15);
    color: white;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.25);
}

.nav-top a:hover {
    background: rgba(255, 255, 255, 0.25);
    border-color: rgba(255, 255, 255, 0.35);
}

.nav-menu {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    font-size: 14px;
}

.nav-menu a {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 12px;
    color: white;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.25s ease;
    border: 1px solid rgba(255, 255, 255, 0.15);
    background: rgba(255, 255, 255, 0.08);
}

.nav-menu a:hover,
.nav-menu a.active {
    background: rgba(255, 255, 255, 0.18);
    border-color: rgba(255, 255, 255, 0.3);
}

.nav-menu a i {
    font-size: 16px;
}

/* ===== CONTENT ===== */
.container {
    padding: 24px;
    max-width: 1200px;
    margin: 0 auto;
}

/* SEARCH */
.search {
    margin-bottom: 28px;
}

.search input {
    width: 100%;
    padding: 14px 18px;
    border: 1.5px solid #dce5f0;
    border-radius: 14px;
    font-family: 'Inter', sans-serif;
    font-size: 14px;
    color: #334758;
    background: white;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
}

.search input::placeholder {
    color: #a0afc0;
}

.search input:focus {
    outline: none;
    border-color: #1c92d2;
    box-shadow: 0 8px 20px rgba(28, 146, 210, 0.15);
}

/* CARD */
.card {
    background: white;
    padding: 24px;
    border-radius: 18px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    margin-bottom: 24px;
    border: 1px solid rgba(0, 0, 0, 0.04);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}

.card h3 {
    margin: 0 0 16px;
    font-size: 20px;
    color: #0f2027;
    font-weight: 700;
}

.card p {
    color: #556b86;
    line-height: 1.6;
    margin: 8px 0;
}

/* PROMO */
.promo {
    background: linear-gradient(135deg, #1c92d2 0%, #0f456c 100%);
    color: white;
    border: none;
}

.promo h3 {
    color: white;
}

.promo p {
    color: rgba(255, 255, 255, 0.9);
    font-size: 15px;
}

/* PACKAGE GRID */
.section-title {
    font-size: 22px;
    font-weight: 700;
    color: #0f2027;
    margin: 32px 0 20px;
}

.grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
    margin-bottom: 28px;
}

.package {
    background: white;
    padding: 20px;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border: 1.5px solid #f0f4f9;
    transition: all 0.3s ease;
    cursor: pointer;
    text-align: center;
}

.package:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 28px rgba(28, 146, 210, 0.18);
    border-color: #1c92d2;
}

.package h4 {
    margin: 0 0 8px;
    font-size: 18px;
    color: #0f2027;
    font-weight: 700;
}

.package p {
    color: #7a8fa0;
    font-size: 14px;
    margin: 0;
}

.speed-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 12px;
    background: rgba(28, 146, 210, 0.1);
    color: #1c92d2;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    margin: 8px 0;
}

.price {
    font-size: 24px;
    font-weight: 800;
    color: #1c92d2;
    margin: 14px 0;
}

/* BUTTON */
button {
    padding: 12px 20px;
    border: none;
    border-radius: 12px;
    background: linear-gradient(135deg, #1c92d2, #0f456c);
    color: white;
    cursor: pointer;
    font-family: 'Inter', sans-serif;
    font-weight: 700;
    font-size: 14px;
    width: 100%;
    transition: all 0.3s ease;
    margin-top: 12px;
    box-shadow: 0 4px 12px rgba(28, 146, 210, 0.2);
}

button:hover {
    background: linear-gradient(135deg, #0f456c, #1c92d2);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(28, 146, 210, 0.3);
}

button:active {
    transform: translateY(0);
}

#no-results {
    text-align: center;
    color: #d00;
    padding: 20px;
    font-weight: 600;
}

/* STATUS CARD */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 13px;
    margin-bottom: 12px;
}

.status-badge.active {
    background: #d4f4dd;
    color: #2d7a3e;
}

.status-badge.inactive {
    background: #ffe6e6;
    color: #c92a2a;
}

.status-badge.pending {
    background: #fff3d6;
    color: #c17d11;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        padding: 16px;
    }

    .navbar {
        padding: 18px 16px;
    }

    .nav-top {
        gap: 16px;
    }

    .nav-top h2 {
        font-size: 22px;
    }

    .nav-menu {
        gap: 8px;
        font-size: 13px;
    }

    .nav-menu a {
        padding: 8px 12px;
        font-size: 12px;
    }

    .grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .card {
        padding: 18px;
        margin-bottom: 16px;
    }

    .section-title {
        font-size: 18px;
        margin: 24px 0 16px;
    }

    button {
        padding: 12px 16px;
        font-size: 14px;
    }

    .search input {
        padding: 12px 16px;
        font-size: 16px;
    }
}
</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="nav-top">
        <div>
            <h2>Halo, <?= $_SESSION['user']['nama']; ?>!</h2>
            <small>Selamat datang di MyRepublic</small>
        </div>
        <div>
            <a href="logout.php" style="color:white;text-decoration:none;">Logout</a>
        </div>
    </div>

    <div class="nav-menu">
        <a href="home_pelanggan.php" class="active"><i class="fas fa-home"></i> Home</a>
        <a href="dashboard_pelanggan.php"><i class="fas fa-chart-line"></i> Dashboard</a>
        <a href="status_layanan.php"><i class="fas fa-info-circle"></i> Status Layanan</a>
        <a href="riwayat.php"><i class="fas fa-history"></i> Riwayat</a>
        <a href="profil.php"><i class="fas fa-user"></i> Profil</a>
    </div>
</div>

<div class="container">

    <!-- SEARCH -->
    <div class="search">
        <input type="text" id="search" placeholder="🔍 Cari paket internet..." value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
    </div>
    <p id="no-results" style="display:none; margin-top:10px; color:#d00;">Paket tidak ditemukan.</p>

    <!-- PROMO -->
    <div class="card promo">
        <h3>🔥 Promo Spesial Bulan Ini</h3>
        <p>Upgrade ke 150 Mbps dan dapat diskon 20% selama 3 bulan!</p>
        <button type="button" onclick="window.location='promo.php'">Lihat Promo</button>
    </div>

    <!-- PAKET -->
    <h3>Paket Internet Terpopuler</h3>

    <div class="grid">

        <div class="package" data-name="Super Seru" data-speed="100 Mbps" onclick="window.location='detail.php?paket=super'">
            <h4>Super Seru</h4>
            <p>100 Mbps</p>
            <div class="price">Rp 350.000</div>
            <button>Pilih Paket</button>
        </div>

        <div class="package" data-name="Gaming Pro" data-speed="150 Mbps" onclick="window.location='detail.php?paket=gaming'">
            <h4>Gaming Pro</h4>
            <p>150 Mbps</p>
            <div class="price">Rp 500.000</div>
            <button>Pilih Paket</button>
        </div>

        <div class="package" data-name="Family Plan" data-speed="50 Mbps" onclick="window.location='detail.php?paket=family'">
            <h4>Family Plan</h4>
            <p>50 Mbps</p>
            <div class="price">Rp 300.000</div>
            <button>Pilih Paket</button>
        </div>

        <div class="package" data-name="Ultra Max" data-speed="300 Mbps" onclick="window.location='detail.php?paket=ultra'">
            <h4>Ultra Max</h4>
            <p>300 Mbps</p>
            <div class="price">Rp 750.000</div>
            <button>Pilih Paket</button>
        </div>

    </div>

    <div class="card">
        <h3>Status Layanan</h3>
        <div class="status-badge <?php echo ($status_layanan === 'Aktif') ? 'active' : (($status_layanan === 'Menunggu Pembayaran') ? 'pending' : 'inactive'); ?>">
            <i class="fas <?php echo ($status_layanan === 'Aktif') ? 'fa-check-circle' : (($status_layanan === 'Menunggu Pembayaran') ? 'fa-clock' : 'fa-times-circle'); ?>"></i>
            <?= $status_layanan ?>
        </div>
        <p><strong>Tagihan Bulan Ini:</strong><br>Rp <?= number_format($tagihan,0,',','.') ?></p>
    </div>
    
    <div class="card">
        <h3><i class="fas fa-bell"></i> Notifikasi</h3>
        <p style="font-size: 16px; color: #1c92d2; font-weight: 700;">
            <span style="font-size: 24px;"><?= $unread_notifikasi; ?></span> Notifikasi Baru
        </p>
        <button type="button" onclick="window.location='notifikasi.php'" style="background: linear-gradient(135deg, #1c92d2, #0f456c); width: 100%;">
            <i class="fas fa-bell"></i> Lihat Semua Notifikasi
        </button>
    </div>
</div>

<script>
const searchInput = document.getElementById('search');
const noResults = document.getElementById('no-results');

function filterPackages() {
    const query = searchInput.value.trim().toLowerCase();
    const packages = document.querySelectorAll('.package');
    let matchCount = 0;

    packages.forEach(pkg => {
        const name = pkg.dataset.name.toLowerCase();
        const speed = pkg.dataset.speed.toLowerCase();

        if (!query || name.includes(query) || speed.includes(query)) {
            pkg.style.display = '';
            matchCount++;
        } else {
            pkg.style.display = 'none';
        }
    });

    noResults.style.display = matchCount === 0 ? 'block' : 'none';
}

searchInput.addEventListener('input', filterPackages);

// Jalankan filter sekali saat halaman dimuat, untuk mendukung query param ?q=
filterPackages();
</script>

</body>
</html>