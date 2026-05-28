<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role']!="cs"){
    header("Location: pilih_role.php");
    exit;
}

// Get statistics
$diproses = mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi WHERE status='Diproses'");
$diproses_row = mysqli_fetch_assoc($diproses);
$count_diproses = $diproses_row['total'];

$lunas = mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi WHERE status='Lunas'");
$lunas_row = mysqli_fetch_assoc($lunas);
$count_lunas = $lunas_row['total'];

$terjadwal = mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi WHERE status='Terjadwal'");
$terjadwal_row = mysqli_fetch_assoc($terjadwal);
$count_terjadwal = $terjadwal_row['total'];

$selesai = mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi WHERE status='Selesai'");
$selesai_row = mysqli_fetch_assoc($selesai);
$count_selesai = $selesai_row['total'];

$total_users = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='pelanggan'");
$total_users_row = mysqli_fetch_assoc($total_users);
$count_users = $total_users_row['total'];

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard CS - MyRepublic</title>

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
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    flex-wrap: wrap;
    gap: 12px;
}

.navbar h2 {
    font-size: 24px;
    font-weight: 700;
    margin: 0;
}

.navbar a {
    padding: 10px 18px;
    background: rgba(255, 255, 255, 0.15);
    color: white;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.25);
}

.navbar a:hover {
    background: rgba(255, 255, 255, 0.25);
    border-color: rgba(255, 255, 255, 0.35);
}

/* ===== CONTENT ===== */
.container {
    padding: 24px;
    max-width: 1200px;
    margin: 0 auto;
}

/* STAT GRID */
.stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 18px;
    margin-bottom: 28px;
}

.stat-card {
    background: linear-gradient(135deg, #1c92d2 0%, #0f456c 100%);
    color: white;
    padding: 28px;
    border-radius: 16px;
    box-shadow: 0 8px 20px rgba(28, 146, 210, 0.2);
    text-align: center;
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
    font-size: 13px;
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

.stat-card.warning {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
    box-shadow: 0 8px 20px rgba(255, 193, 7, 0.2);
}

.stat-card.info {
    background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
    box-shadow: 0 8px 20px rgba(23, 162, 184, 0.2);
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

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 16px;
}

thead {
    background: linear-gradient(135deg, #1c92d2, #0f456c);
    color: white;
}

th {
    padding: 14px;
    text-align: left;
    font-weight: 700;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

td {
    padding: 12px 14px;
    border-bottom: 1px solid #f0f4f9;
    color: #556b86;
}

tbody tr:hover {
    background: #f9fbfd;
}

tbody tr:last-child td {
    border-bottom: none;
}

/* BUTTON */
button,
.btn {
    padding: 12px 20px;
    border: none;
    border-radius: 12px;
    background: linear-gradient(135deg, #1c92d2, #0f456c);
    color: white;
    cursor: pointer;
    font-family: 'Inter', sans-serif;
    font-weight: 700;
    font-size: 14px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(28, 146, 210, 0.2);
}

button:hover,
.btn:hover {
    background: linear-gradient(135deg, #0f456c, #1c92d2);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(28, 146, 210, 0.3);
}

button:active,
.btn:active {
    transform: translateY(0);
}

select,
input {
    padding: 12px 16px;
    border: 1.5px solid #dce5f0;
    border-radius: 12px;
    font-family: 'Inter', sans-serif;
    font-size: 14px;
    color: #334758;
    transition: all 0.3s ease;
}

select:focus,
input:focus {
    outline: none;
    border-color: #1c92d2;
    box-shadow: 0 0 0 4px rgba(28, 146, 210, 0.1);
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        padding: 16px;
    }

    .navbar {
        padding: 18px 16px;
    }

    .navbar h2 {
        font-size: 20px;
        width: 100%;
    }

    .stat-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .card {
        padding: 18px;
        margin-bottom: 16px;
    }

    .card h3 {
        font-size: 18px;
    }

    table {
        font-size: 13px;
    }

    th,
    td {
        padding: 10px 8px;
    }

    button,
    .btn {
        width: 100%;
        padding: 12px 16px;
        font-size: 14px;
    }

    select,
    input {
        width: 100%;
        margin-bottom: 12px;
    }
}

</style>
</head>

<body>

<div class="navbar">
<div>
<h2>Dashboard Customer Service</h2>
<small>Halo, <?= $_SESSION['user']['nama']; ?></small>
</div>

<a href="logout.php" style="color:white;text-decoration:none;">Logout</a>
</div>


<div class="container">

<?php if(isset($_SESSION['msg'])): ?>
<div style="background:#d4edda;color:#155724;border:1px solid #c3e6cb;padding:12px;border-radius:10px;margin-bottom:15px;">
<?= $_SESSION['msg']; ?>
</div>
<?php unset($_SESSION['msg']); endif; ?>

<!-- STATISTIK DASHBOARD -->
<h3>📊 Statistik & Monitoring</h3>
<div class="stat-grid">
    <div class="stat-card info">
        <h4>Total Pelanggan</h4>
        <div class="stat-value"><?= $count_users; ?></div>
    </div>
    
    <div class="stat-card warning">
        <h4>Menunggu Verifikasi</h4>
        <div class="stat-value"><?= $count_diproses; ?></div>
    </div>
    
    <div class="stat-card success">
        <h4>Pembayaran Lunas</h4>
        <div class="stat-value"><?= $count_lunas; ?></div>
    </div>
    
    <div class="stat-card">
        <h4>Jadwal Penjadwalan</h4>
        <div class="stat-value"><?= $count_terjadwal; ?></div>
    </div>
</div>

<!-- RINGKASAN STATUS -->
<div class="card">
<h3>📈 Ringkasan Status Layanan</h3>
<table>
<tr>
<th>Status</th>
<th>Jumlah</th>
</tr>
<tr>
<td>Menunggu Verifikasi</td>
<td><?= $count_diproses; ?></td>
</tr>
<tr>
<td>Pembayaran Lunas</td>
<td><?= $count_lunas; ?></td>
</tr>
<tr>
<td>Jadwal Terjadwal</td>
<td><?= $count_terjadwal; ?></td>
</tr>
<tr>
<td>Pemasangan Selesai</td>
<td><?= $count_selesai; ?></td>
</tr>
</table>
</div>

<!-- VERIFIKASI PEMBAYARAN -->

<div class="card">
<h3>Verifikasi Pembayaran</h3>

<table>

<tr>
<th>Nama Pelanggan</th>
<th>Paket</th>
<th>Status</th>
<th>Aksi</th>
</tr>

<?php

$data = mysqli_query($conn,"
SELECT transaksi.*, users.nama 
FROM transaksi
JOIN users ON transaksi.user_id = users.id
WHERE transaksi.status='Diproses'
");

while($row = mysqli_fetch_assoc($data)){

?>

<tr>
<td><?= $row['nama']; ?></td>
<td><?= $row['paket']; ?></td>
<td><?= $row['status']; ?></td>

<td>

<a href="verifikasi.php?id=<?= $row['id']; ?>">
<button>Verifikasi</button>
</a>

</td>

</tr>

<?php } ?>

</table>

</div>



<!-- PENJADWALAN -->

<div class="card">

<h3>Buat Jadwal Pemasangan</h3>

<form method="POST" action="jadwal.php">

<select name="id_transaksi" required style="width:100%;max-width:420px;padding:10px;border-radius:10px;margin-bottom:10px;">
<option value="">-- Pilih Transaksi (status Lunas) --</option>
<?php
$jadwalOptions = mysqli_query($conn, "SELECT t.id, u.nama, t.paket, t.status FROM transaksi t JOIN users u ON t.user_id = u.id WHERE t.status='Lunas'");
while($opt = mysqli_fetch_assoc($jadwalOptions)){
    echo '<option value="'. $opt['id'] .'">ID '. $opt['id'] .' - '. htmlspecialchars($opt['nama']) .' - '. htmlspecialchars($opt['paket']) .'</option>';
}
?>
</select>

<input type="date" name="tanggal" required style="width:100%;max-width:260px;padding:10px;border-radius:10px;margin-bottom:10px;">

<button>Jadwalkan</button>

</form>

</div>

<div class="card">
<h3>Jadwal Pemasangan</h3>
<table>
<tr><th>ID</th><th>ID Transaksi</th><th>Nama Pelanggan</th><th>Paket</th><th>Tanggal</th><th>Status</th></tr>
<?php
$jadwalData = mysqli_query($conn, "SELECT j.*, t.paket, u.nama FROM jadwal j JOIN transaksi t ON j.transaksi_id = t.id JOIN users u ON t.user_id = u.id ORDER BY j.tanggal");
while($row = mysqli_fetch_assoc($jadwalData)){
    echo '<tr>';
    echo '<td>'. $row['id'] .'</td>';
    echo '<td>'. $row['transaksi_id'] .'</td>';
    echo '<td>'. htmlspecialchars($row['nama']) .'</td>';
    echo '<td>'. htmlspecialchars($row['paket']) .'</td>';
    echo '<td>'. date('d/m/Y', strtotime($row['tanggal'])) .'</td>';
    echo '<td>'. $row['status'] .'</td>';
    echo '</tr>';
}
?>
</table>
</div>

<div class="card">
<h3>📊 Laporan Otomatis</h3>
<p style="margin-top:0; color:#666;">Laporan dibuat otomatis berdasarkan proses verifikasi dan penjadwalan layanan.</p>
<table>
<tr><th>ID</th><th>Transaksi ID</th><th>Judul</th><th>Created By</th><th>Tanggal</th><th>Status</th></tr>
<?php
$laporanData = mysqli_query($conn, "SELECT l.*, u.nama FROM laporan l JOIN users u ON l.cs_id = u.id ORDER BY l.tanggal DESC");
if($laporanData && mysqli_num_rows($laporanData) > 0){
    while($row = mysqli_fetch_assoc($laporanData)){
        echo '<tr>';
        echo '<td>'. $row['id'] .'</td>';
        echo '<td>'. $row['transaksi_id'] .'</td>';
        echo '<td>'. htmlspecialchars($row['judul']) .'</td>';
        echo '<td>'. htmlspecialchars($row['nama']) .'</td>';
        echo '<td>'. date('d/m/Y H:i', strtotime($row['tanggal'])) .'</td>';
        echo '<td>'. htmlspecialchars($row['status']) .'</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="6" style="text-align:center;">Belum ada laporan otomatis</td></tr>';
}
?>
</table>
</div>

</div>

</body>
</html>