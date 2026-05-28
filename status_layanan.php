<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role']!="pelanggan"){
    header("Location: pilih_role.php");
    exit;
}

$id_user = $_SESSION['user']['id'];

// Get all transactions
$transaksi = mysqli_query($conn, "SELECT * FROM transaksi WHERE user_id='$id_user' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Riwayat Transaksi - MyRepublic</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
*{box-sizing:border-box;}

body{
    margin:0;
    font-family:'Inter',sans-serif;
    background:#f4f9ff;
}

.navbar{
    background:linear-gradient(135deg,#0f2027,#1c92d2,#2c5364);
    padding:20px;
    color:white;
    border-bottom-left-radius:25px;
    border-bottom-right-radius:25px;
}

.nav-top{
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.nav-menu{
    display:flex;
    flex-wrap:wrap;
    gap:12px;
    margin-top:15px;
    font-size:14px;
}

.nav-menu a{
    display:flex;
    align-items:center;
    gap:8px;
    padding:10px 14px;
    border-radius:14px;
    color:white;
    text-decoration:none;
    font-weight:600;
    transition:background 0.2s ease;
}

.nav-menu a span,
.nav-menu a i{
    display:inline-flex;
    width:20px;
    justify-content:center;
}

.nav-menu a:hover{
    background:rgba(255,255,255,0.12);
    color:white;
}

.nav-menu a:focus,
.nav-menu a:active,
.nav-menu a:visited{
    color:white;
    background:transparent;
    outline:none;
}

.nav-menu a.active{
    background:rgba(255,255,255,0.16);
}

.container{
    padding:20px;
}

.card{
    background:white;
    padding:20px;
    border-radius:20px;
    box-shadow:0 8px 20px rgba(0,0,0,0.08);
    margin-bottom:20px;
}

.card h3{
    margin-top:0;
    color:#0f2027;
}

.status-badge{
    display:inline-block;
    padding:8px 12px;
    border-radius:20px;
    font-weight:600;
    font-size:12px;
}

.status-aktif{
    background:#d4edda;
    color:#155724;
}

.status-terjadwal{
    background:#fff3cd;
    color:#856404;
}

.status-diproses{
    background:#cfe2ff;
    color:#084298;
}

.status-selesai{
    background:#d1e7dd;
    color:#0f5132;
}

.detail-item{
    display:flex;
    justify-content:space-between;
    padding:8px 0;
    border-bottom:1px solid #f0f0f0;
}

.detail-item:last-child{
    border-bottom:none;
}

.detail-label{
    font-weight:600;
    color:#666;
}

.detail-value{
    color:#0f2027;
    font-weight:500;
}

.empty-state{
    text-align:center;
    color:#666;
    padding:40px 20px;
}

.empty-state p{
    margin:10px 0;
}
</style>
</head>

<body>

<div class="navbar">
    <div class="nav-top">
        <div>
            <h2>Riwayat Transaksi</h2>
            <small>Halo, <?= $_SESSION['user']['nama']; ?>!</small>
        </div>
        <div>
            <a href="logout.php" style="color:white;text-decoration:none;">Logout</a>
        </div>
    </div>
    <div class="nav-menu">
        <a href="home_pelanggan.php"><i class="fas fa-home"></i> Home</a>
        <a href="dashboard_pelanggan.php"><i class="fas fa-chart-line"></i> Dashboard</a>
        <a href="status_layanan.php"><i class="fas fa-info-circle"></i> Status Layanan</a>
        <a href="riwayat.php" class="active"><i class="fas fa-history"></i> Riwayat</a>
        <a href="profil.php"><i class="fas fa-user"></i> Profil</a>
    </div>
</div>

<div class="container">

<?php if(mysqli_num_rows($transaksi) > 0): ?>
    <?php while($row = mysqli_fetch_assoc($transaksi)): ?>
        <?php
        $status = $row['status'];
        $status_class = 'status-diproses';
        
        if($status == 'Lunas') $status_class = 'status-aktif';
        elseif($status == 'Terjadwal') $status_class = 'status-terjadwal';
        elseif($status == 'Selesai') $status_class = 'status-selesai';
        ?>
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                <h4 style="margin:0;">Paket: <?= htmlspecialchars($row['paket']); ?></h4>
                <span class="status-badge <?= $status_class; ?>"><?= $status; ?></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">ID Transaksi</span>
                <span class="detail-value">#<?= $row['id']; ?></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Harga</span>
                <span class="detail-value">Rp <?= number_format($row['harga'] ?? 0, 0, ',', '.'); ?></span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Metode Pembayaran</span>
                <span class="detail-value"><?= htmlspecialchars($row['metode']); ?></span>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <div class="empty-state">
        <p>Belum ada riwayat transaksi</p>
    </div>
<?php endif; ?>

</div>

</body>
</html>