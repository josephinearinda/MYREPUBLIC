<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role']!="pelanggan"){
    header("Location: pilih_role.php");
    exit;
}

$id_user = $_SESSION['user']['id'];

// Get latest transaction
$transaksi = mysqli_query($conn, "SELECT * FROM transaksi WHERE user_id='$id_user' ORDER BY id DESC LIMIT 1");
$data_transaksi = mysqli_fetch_assoc($transaksi);

// Get scheduled installation
$jadwal = mysqli_query($conn, "SELECT j.*, t.paket FROM jadwal j JOIN transaksi t ON j.transaksi_id = t.id WHERE t.user_id='$id_user' ORDER BY j.tanggal DESC LIMIT 1");
$data_jadwal = mysqli_fetch_assoc($jadwal);

// Count transactions
$count_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi WHERE user_id='$id_user'");
$count_row = mysqli_fetch_assoc($count_query);
$total_transaksi = $count_row['total'];

// Get active transactions (paid and installed)
$aktif_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi WHERE user_id='$id_user' AND status IN ('Lunas', 'Selesai')");
$aktif_row = mysqli_fetch_assoc($aktif_query);
$transaksi_aktif = $aktif_row['total'];

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Dashboard - MyRepublic</title>
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

/* ===== CONTENT ===== */
.container {
    padding: 24px;
    max-width: 1200px;
    margin: 0 auto;
}

/* MESSAGE */
.msg {
    padding: 14px 18px;
    border-radius: 12px;
    margin-bottom: 24px;
    border-left: 4px solid;
}

.msg-success {
    background: #d4f4dd;
    color: #2d7a3e;
    border-color: #2d7a3e;
}

.msg-warning {
    background: #fff3d6;
    color: #c17d11;
    border-color: #c17d11;
}

.msg-error {
    background: #ffe6e6;
    color: #c92a2a;
    border-color: #c92a2a;
}

/* CARD */
.card {
    background: white;
    padding: 28px;
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
    margin: 0 0 20px;
    font-size: 20px;
    color: #0f2027;
    font-weight: 700;
}

/* GRID */
.grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
    margin-bottom: 28px;
}

.stat-card {
    background: linear-gradient(135deg, #1c92d2 0%, #0f456c 100%);
    color: white;
    padding: 28px;
    border-radius: 16px;
    box-shadow: 0 8px 20px rgba(28, 146, 210, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(28, 146, 210, 0.3);
}

.stat-card h4 {
    margin: 0 0 12px;
    font-weight: 600;
    opacity: 0.9;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-value {
    font-size: 36px;
    font-weight: 800;
    margin: 0;
}

.stat-card.success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    box-shadow: 0 8px 20px rgba(40, 167, 69, 0.2);
}

/* INFO ITEMS */
.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 0;
    border-bottom: 1px solid #f0f4f9;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 700;
    color: #556b86;
    font-size: 14px;
}

.info-value {
    color: #0f2027;
    font-weight: 600;
    text-align: right;
}

/* STATUS BADGE */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-aktif {
    background: #d4f4dd;
    color: #2d7a3e;
}

.status-diproses {
    background: #cfe2ff;
    color: #084298;
}

.status-terjadwal {
    background: #fff3d6;
    color: #c17d11;
}

.status-selesai {
    background: #d1e7dd;
    color: #0f5132;
}

/* BUTTON */
button,
.button {
    padding: 12px 20px;
    border: none;
    border-radius: 12px;
    background: linear-gradient(135deg, #1c92d2, #0f456c);
    color: white;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-family: 'Inter', sans-serif;
    font-weight: 700;
    font-size: 14px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(28, 146, 210, 0.2);
    width: 100%;
    margin-top: 16px;
}

button:hover,
.button:hover {
    background: linear-gradient(135deg, #0f456c, #1c92d2);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(28, 146, 210, 0.3);
}

button:active,
.button:active {
    transform: translateY(0);
}

/* EMPTY STATE */
.empty-state {
    text-align: center;
    color: #7a8fa0;
    padding: 40px 20px;
}

.empty-state p {
    margin: 10px 0;
    font-size: 15px;
}

.empty-state small {
    color: #a0afc0;
    display: block;
    margin-top: 8px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        padding: 16px;
    }

    .navbar {
        padding: 18px 16px;
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

    .card h3 {
        font-size: 18px;
        margin-bottom: 16px;
    }

    .stat-value {
        font-size: 28px;
    }

    .info-item {
        flex-direction: column;
        align-items: flex-start;
        padding: 12px 0;
    }

    .info-value {
        text-align: left;
        margin-top: 6px;
        width: 100%;
    }

    button,
    .button {
        width: 100%;
        padding: 12px 16px;
        font-size: 14px;
    }
}
</style>
</head>

<body>

<div class="navbar">
    <div class="nav-top">
        <div>
            <h2>Dashboard MyRepublic</h2>
            <small>Halo, <?= $_SESSION['user']['nama']; ?>!</small>
        </div>
        <div>
            <a href="logout.php" style="color:white;text-decoration:none;">Logout</a>
        </div>
    </div>
    <div class="nav-menu">
        <a href="home_pelanggan.php"><i class="fas fa-home"></i> Home</a>
        <a href="dashboard_pelanggan.php" class="active"><i class="fas fa-chart-line"></i> Dashboard</a>
        <a href="status_layanan.php"><i class="fas fa-info-circle"></i> Status Layanan</a>
        <a href="riwayat.php"><i class="fas fa-history"></i> Riwayat</a>
        <a href="profil.php"><i class="fas fa-user"></i> Profil</a>
    </div>
</div>

<div class="container">

<?php if(isset($_SESSION['msg'])): ?>
<div class="msg msg-success">
    <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
    <?= $_SESSION['msg']; ?>
</div>
<?php unset($_SESSION['msg']); endif; ?>

<!-- STATISTIK -->
<div class="grid">
    <div class="stat-card">
        <h4>Total Transaksi</h4>
        <div class="stat-value"><?= $total_transaksi; ?></div>
    </div>
    
    <div class="stat-card success">
        <h4>Layanan Aktif</h4>
        <div class="stat-value"><?= $transaksi_aktif; ?></div>
    </div>
</div>

<!-- TRANSAKSI TERBARU -->
<div class="card">
    <h3>📋 Transaksi Terbaru</h3>
    
    <?php if($data_transaksi): ?>
        <div class="info-item">
            <span class="info-label">ID Transaksi</span>
            <span class="info-value">#<?= $data_transaksi['id']; ?></span>
        </div>
        
        <div class="info-item">
            <span class="info-label">Paket</span>
            <span class="info-value"><?= htmlspecialchars($data_transaksi['paket']); ?></span>
        </div>
        
        <div class="info-item">
            <span class="info-label">Harga</span>
            <span class="info-value">Rp <?= number_format($data_transaksi['harga'] ?? 0, 0, ',', '.'); ?></span>
        </div>
        
        <div class="info-item">
            <span class="info-label">Metode Pembayaran</span>
            <span class="info-value"><?= htmlspecialchars($data_transaksi['metode']); ?></span>
        </div>
        
        <div class="info-item">
            <span class="info-label">Status</span>
            <span class="info-value">
                <?php
                $status = $data_transaksi['status'];
                $status_class = 'status-diproses';
                if($status == 'Lunas') $status_class = 'status-aktif';
                elseif($status == 'Terjadwal') $status_class = 'status-terjadwal';
                elseif($status == 'Selesai') $status_class = 'status-selesai';
                ?>
                <span class="status-badge <?= $status_class; ?>"><?= $status; ?></span>
            </span>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <p>Belum ada transaksi</p>
            <a href="home_pelanggan.php" class="button">Pilih Paket Sekarang</a>
        </div>
    <?php endif; ?>
</div>

<!-- JADWAL PEMASANGAN -->
<div class="card">
    <h3>📅 Jadwal Pemasangan</h3>
    
    <?php if($data_jadwal): ?>
        <div class="info-item">
            <span class="info-label">Paket</span>
            <span class="info-value"><?= htmlspecialchars($data_jadwal['paket']); ?></span>
        </div>
        
        <div class="info-item">
            <span class="info-label">Tanggal & Waktu</span>
            <span class="info-value"><?= date('d/m/Y H:i', strtotime($data_jadwal['tanggal'])); ?></span>
        </div>
        
        <div class="info-item">
            <span class="info-label">Status</span>
            <span class="info-value">
                <?php
                $status_jadwal = $data_jadwal['status'];
                $status_class = 'status-terjadwal';
                if($status_jadwal == 'Selesai') $status_class = 'status-selesai';
                ?>
                <span class="status-badge <?= $status_class; ?>"><?= $status_jadwal; ?></span>
            </span>
        </div>
        
        <?php if($data_jadwal['laporan'] && $status_jadwal == 'Selesai'): ?>
        <div class="info-item">
            <span class="info-label">Laporan Teknisi</span>
            <span class="info-value"><?= htmlspecialchars($data_jadwal['laporan']); ?></span>
        </div>
        <?php endif; ?>
        
        <br>
        <a href="status_layanan.php" class="button">Lihat Detail Lengkap</a>
    <?php else: ?>
        <div class="empty-state">
            <p>Belum ada jadwal pemasangan</p>
            <small>Jadwal akan ditampilkan setelah CS mengatur jadwal pemasangan Anda</small>
        </div>
    <?php endif; ?>
</div>

<!-- QUICK ACTIONS -->
<div class="card">
    <h3>⚡ Aksi Cepat</h3>
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
        <a href="home_pelanggan.php" class="button" style="text-align:center;">Lihat Paket</a>
        <a href="status_layanan.php" class="button" style="text-align:center;">Status Layanan</a>
    </div>
</div>

</div>

</body>
</html>