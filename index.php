<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role']!="teknisi"){
    header("Location: pilih_role.php");
    exit;
}

// Get statistics
$terjadwal = mysqli_query($conn, "SELECT COUNT(*) as total FROM jadwal WHERE status='Terjadwal'");
$terjadwal_row = mysqli_fetch_assoc($terjadwal);
$count_terjadwal = $terjadwal_row['total'];

$selesai = mysqli_query($conn, "SELECT COUNT(*) as total FROM jadwal WHERE status='Selesai'");
$selesai_row = mysqli_fetch_assoc($selesai);
$count_selesai = $selesai_row['total'];

$total_jadwal = mysqli_query($conn, "SELECT COUNT(*) as total FROM jadwal");
$total_jadwal_row = mysqli_fetch_assoc($total_jadwal);
$count_total = $total_jadwal_row['total'];

// Get today's scheduled
$today_jadwal = mysqli_query($conn, "SELECT COUNT(*) as total FROM jadwal WHERE DATE(tanggal) = CURDATE()");
$today_row = mysqli_fetch_assoc($today_jadwal);
$count_today = $today_row['total'];

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Dashboard Teknisi - MyRepublic</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

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
    transition: all 0.3s ease;
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

button[disabled] {
    background: #cbd7ea;
    cursor: not-allowed;
    box-shadow: none;
    color: #556b86;
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

    button {
        width: 100%;
        padding: 12px 16px;
        font-size: 14px;
    }
}

/* STAT CARDS */

.stat-grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(160px,1fr));
gap:15px;
margin-bottom:20px;
}

.stat-card{
background:linear-gradient(135deg,#1c92d2,#0f2027);
color:white;
padding:20px;
border-radius:15px;
box-shadow:0 6px 15px rgba(0,0,0,0.1);
text-align:center;
}

.stat-card h4{
margin:0 0 10px 0;
font-weight:600;
opacity:0.9;
font-size:12px;
}

.stat-value{
font-size:28px;
font-weight:700;
}

.stat-card.warning{
background:linear-gradient(135deg,#ffc107,#ff9800);
}

.stat-card.success{
background:linear-gradient(135deg,#28a745,#20c997);
}

.stat-card.info{
background:linear-gradient(135deg,#17a2b8,#20c997);
}

</style>
</head>

<body>

<div class="navbar">
<div>
<h2>Dashboard Teknisi</h2>
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
    <div class="stat-card warning">
        <h4>Menunggu Dikerjakan</h4>
        <div class="stat-value"><?= $count_terjadwal; ?></div>
    </div>
    
    <div class="stat-card success">
        <h4>Telah Selesai</h4>
        <div class="stat-value"><?= $count_selesai; ?></div>
    </div>
    
    <div class="stat-card info">
        <h4>Total Jadwal</h4>
        <div class="stat-value"><?= $count_total; ?></div>
    </div>
    
    <div class="stat-card">
        <h4>Hari Ini</h4>
        <div class="stat-value"><?= $count_today; ?></div>
    </div>
</div>

<!-- RINGKASAN STATUS -->
<div class="card">
<h3>📋 Ringkasan Status Pemasangan</h3>
<table>
<tr>
<th>Status</th>
<th>Jumlah</th>
</tr>
<tr>
<td>Menunggu Dikerjakan</td>
<td><?= $count_terjadwal; ?></td>
</tr>
<tr>
<td>Telah Selesai</td>
<td><?= $count_selesai; ?></td>
</tr>
</table>
</div>

<div class="card">
<h3>Daftar Jadwal Pemasangan</h3>
<table>
<tr>
<th>ID Jadwal</th>
<th>Nama Pelanggan</th>
<th>Paket</th>
<th>Tanggal</th>
<th>Status</th>
<th>Aksi</th>
</tr>
<?php
$jadwalList = mysqli_query($conn, "SELECT j.id, u.nama, t.paket, j.tanggal, j.status FROM jadwal j JOIN transaksi t ON j.transaksi_id = t.id JOIN users u ON t.user_id = u.id ORDER BY j.tanggal");
while($task = mysqli_fetch_assoc($jadwalList)){
    echo '<tr>';
    echo '<td>'. $task['id'] .'</td>';
    echo '<td>'. htmlspecialchars($task['nama']) .'</td>';
    echo '<td>'. htmlspecialchars($task['paket']) .'</td>';
    echo '<td>'. date('Y-m-d', strtotime($task['tanggal'])) .'</td>';
    echo '<td>'. $task['status'] .'</td>';
    echo '<td>';
    if($task['status'] == 'Terjadwal'){
        echo '<form method="POST" action="update_jadwal.php" style="display:inline;">';
        echo '<input type="hidden" name="jadwal_id" value="'.$task['id'].'">';
        echo '<button type="submit" style="background:red;color:white;border:none;padding:8px 12px;border-radius:5px;cursor:pointer;">Belum Dikerjakan</button>';
        echo '</form>';
    } else {
        echo '<button style="background:green;color:white;" disabled>Sudah Dikerjakan</button>';
    }
    echo '</td>';
    echo '</tr>';
}
?>
</table>
</div>



</div>

</body>
</html>