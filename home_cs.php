<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
}

$user_id = $_SESSION['user']['id'];
$paket = $_GET['paket'] ?? '';

$nama = "";
$kecepatan = "";
$harga = 0;
$deskripsi = "";
$features = [];
$promo = $_GET['promo'] ?? '';
$promoLabel = '';

/* DATA PAKET */
switch($paket){

case "super":
$nama="Super Seru";
$kecepatan="100 Mbps";
$harga=350000;
$deskripsi="Cocok untuk streaming, browsing, dan kebutuhan rumah tangga.";
$features = [
    "Streaming HD tanpa buffering",
    "Browsing cepat untuk 4-6 perangkat",
    "Dukungan teknis 24/7",
    "Keamanan jaringan terjamin"
];
break;

case "gaming":
$nama="Gaming Pro";
$kecepatan="150 Mbps";
$harga=500000;
$deskripsi="Khusus gamer dengan koneksi stabil dan latency rendah.";
$features = [
    "Latency rendah < 10ms",
    "Koneksi stabil untuk gaming online",
    "Prioritas bandwidth gaming",
    "Dukungan esports"
];
break;

case "family":
$nama="Family Plan";
$kecepatan="50 Mbps";
$harga=300000;
$deskripsi="Paket hemat untuk keluarga kecil dengan penggunaan standar.";
$features = [
    "Hemat untuk keluarga 2-4 orang",
    "Kecepatan standar untuk browsing",
    "Streaming SD/HD",
    "Penggunaan fleksibel"
];
break;

case "ultra":
$nama="Ultra Max";
$kecepatan="300 Mbps";
$harga=750000;
$deskripsi="Kecepatan maksimal untuk rumah besar dan kebutuhan bisnis.";
$features = [
    "Kecepatan ultra tinggi 300 Mbps",
    "Untuk rumah besar hingga 10+ perangkat",
    "Dukungan bisnis dan remote work",
    "SLA uptime 99.9%"
];
break;

default:
header("Location: home_pelanggan.php");
}

$promoHarga = $harga;
switch($promo){
    case 'gaming20':
        $promoHarga = 400000;
        $promoLabel = 'Promo 20%';
        break;
    case 'supercore':
        $promoHarga = 300000;
        $promoLabel = 'Core Deal';
        break;
    case 'ultraelite':
        $promoHarga = 750000;
        $promoLabel = 'Premium Elite';
        break;
}
$harga = $promoHarga;


/* PROSES PEMBAYARAN */
if(isset($_POST['konfirmasi'])){

$metode = $_POST['metode'];

// Pastikan paket ada di tabel paket, agar paket_id FK valid
$paketQuery = mysqli_query($conn, "SELECT id FROM paket WHERE nama_paket = '" . mysqli_real_escape_string($conn, $nama) . "' LIMIT 1");
if(mysqli_num_rows($paketQuery) > 0){
    $paketRow = mysqli_fetch_assoc($paketQuery);
    $paket_id = $paketRow['id'];
} else {
    mysqli_query($conn, "INSERT INTO paket(nama_paket, kecepatan, harga, deskripsi) VALUES('" . mysqli_real_escape_string($conn, $nama) . "', '" . mysqli_real_escape_string($conn, $kecepatan) . "', '$harga', '" . mysqli_real_escape_string($conn, $deskripsi) . "')");
    $paket_id = mysqli_insert_id($conn);
}

mysqli_query($conn,"INSERT INTO transaksi(user_id,paket_id,paket,harga,metode,status)
VALUES('$user_id','$paket_id','$nama','$harga','$metode','Diproses')");

echo "<script>
alert('Pembayaran dikonfirmasi');
window.location='home_pelanggan.php';
</script>";

}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Detail Paket</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>

body{
    margin:0;
    font-family:'Inter',sans-serif;
    background: linear-gradient(135deg, #0f2027 0%, #1c92d2 45%, #2c5364 100%);
    min-height: 100vh;
    color: #12263f;
}

body::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background:
        radial-gradient(circle at 15% 20%, rgba(255,255,255,0.18), transparent 20%),
        radial-gradient(circle at 85% 10%, rgba(255,255,255,0.12), transparent 25%);
    pointer-events: none;
}

/* HEADER */

.header{
    background: linear-gradient(135deg,#0f2027,#1c92d2,#2c5364);
    color:white;
    padding:30px 20px;
    border-bottom-left-radius:30px;
    border-bottom-right-radius:30px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.12);
}

.header h2 {
    margin:0;
    font-size: 32px;
    letter-spacing: 0.02em;
}

.container{
    padding:25px;
    position: relative;
    z-index: 1;
    max-width: 1060px;
    margin: 0 auto;
}

.card{
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    padding: 32px;
    border-radius: 28px;
    box-shadow: 0 30px 80px rgba(0,0,0,0.14);
    border: 1px solid rgba(255,255,255,0.32);
}

.package-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 24px;
    flex-wrap: wrap;
    margin-bottom: 28px;
}

.title-group h3 {
    margin: 0;
    font-size: 36px;
    color: #0f456c;
}

.category-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    border-radius: 999px;
    background: rgba(255,255,255,0.2);
    color: #eef5ff;
    font-weight: 700;
    font-size: 13px;
    border: 1px solid rgba(255,255,255,0.25);
}

.speed-badge {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 18px;
    border-radius: 999px;
    background: rgba(15,32,39,0.08);
    color: #0f456c;
    font-weight: 700;
    font-size: 14px;
}

.price-block {
    text-align: right;
    min-width: 220px;
}

.price {
    font-size: 44px;
    font-weight: 800;
    color: #0f456c;
    margin: 0;
}

.price-section small {
    display: block;
    margin-top: 8px;
    color: #445a74;
    font-size: 14px;
}

    .promo-badge {
        display: inline-flex;
        margin-top: 10px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(28,146,210,0.15);
        color: #0f456c;
        font-size: 13px;
        font-weight: 700;
    }

.summary-card {
    background: rgba(28,146,210,0.1);
    border: 1px solid rgba(28,146,210,0.22);
    border-radius: 20px;
    padding: 22px;
}

.summary-card i {
    color: #1c92d2;
    font-size: 22px;
    margin-bottom: 12px;
}

.summary-card strong {
    display: block;
    margin-bottom: 8px;
    font-size: 16px;
    color: #0f456c;
}

.summary-card p {
    margin: 0;
    color: #556b86;
    font-size: 14px;
    line-height: 1.6;
}

.description {
    margin: 20px 0 30px;
    color: #445a74;
    line-height: 1.8;
    font-size: 16px;
}

.features {
    text-align: left;
    margin-bottom: 30px;
}

.features h4 {
    color: #0f456c;
    margin-bottom: 16px;
    font-size: 20px;
}

.features ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.features li {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 0;
    border-bottom: 1px solid rgba(15,32,39,0.08);
    color: #334758;
    font-size: 15px;
}

.features li:last-child {
    border-bottom: none;
}

.features li i {
    color: #1c92d2;
    width: 20px;
}

.buttons {
    display: flex;
    gap: 14px;
    justify-content: center;
    flex-wrap: wrap;
}

button{
    padding: 14px 26px;
    border:none;
    border-radius:14px;
    background:#1c92d2;
    color:white;
    cursor:pointer;
    font-weight: 700;
    transition: background 0.25s ease, transform 0.2s ease;
}

button:hover{
    background:#0f2027;
    transform: translateY(-1px);
}

button.secondary {
    background:#e6f0ff;
    color:#1c92d2;
}

button.secondary:hover {
    background:#d1e0f5;
    color:#0f456c;
}

/* POPUP */

.popup{
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.55);
    z-index: 1000;
    justify-content: center;
    align-items: center;
    padding: 24px;
}

.popup-content{
    background: white;
    padding:32px;
    border-radius:26px;
    width: min(520px, 100%);
    max-width: 520px;
    text-align:center;
    box-shadow: 0 20px 50px rgba(0,0,0,0.18);
    border: 1px solid rgba(15,32,39,0.08);
    max-height: 90vh;
    overflow-y: auto;
}

.popup-content h3 {
    margin-top:0;
    color: #0f456c;
}

.popup-content select,
.popup-content button {
    width: 100%;
}

.popup-content select {
    margin-top: 16px;
}

.popup-content input[type="radio"] {
    appearance: none;
    -webkit-appearance: none;
    width: 18px;
    height: 18px;
    border: 2px solid #1c92d2;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.popup-content input[type="radio"]:checked {
    background: #1c92d2;
    box-shadow: inset 0 0 0 3px white;
}

.popup-content input[type="radio"]:hover {
    box-shadow: 0 0 0 4px rgba(28,146,210,0.2);
}

/* Responsive Design */
@media (max-width: 900px) {
    .package-head {
        flex-direction: column;
        align-items: flex-start;
    }

    .price-block {
        width: 100%;
        text-align: left;
        margin-top: 16px;
    }

    .package-summary {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .header {
        padding: 20px 16px;
    }

    .header h2 {
        font-size: 26px;
    }

    .container {
        padding: 18px;
    }

    .card {
        padding: 24px;
        border-radius: 22px;
    }

    .title-group h3 {
        font-size: 28px;
    }

    .price {
        font-size: 32px;
    }

    .package-summary {
        gap: 14px;
    }

    .description,
    .features li,
    .summary-card p {
        font-size: 14px;
    }

    .buttons {
        flex-direction: column;
        width: 100%;
    }

    button {
        width: 100%;
        padding: 14px 20px;
    }

    .popup-content {
        padding: 22px;
        width: min(90vw, 100%);
    }

    #paymentInfo img {
        width: 180px !important;
        height: 180px !important;
    }
}
</style>
</head>

<body>

<div class="header">
<h2><?= $nama ?></h2>
</div>

<div class="container">

    <div class="card">

        <div class="package-head">
            <div class="title-group">
                <span class="category-badge"><i class="fas fa-wifi"></i> Paket Internet</span>
                <h3><?= $nama ?></h3>
                <div class="speed-badge"><i class="fas fa-tachometer-alt"></i> <?= $kecepatan ?></div>
            </div>

            <div class="price-block">
                <p class="price">Rp <?= number_format($harga,0,',','.') ?></p>
                <div class="price-section">
                    <small>per bulan (30 hari)</small>
                    <?php if($promoLabel): ?>
                    <div class="promo-badge"><?= $promoLabel ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="package-summary">
            <div class="summary-card">
                <i class="fas fa-calendar-day"></i>
                <strong>Durasi 30 Hari</strong>
                <p>Paket dibayar setiap bulan dengan masa aktif 30 hari.</p>
            </div>
            <div class="summary-card">
                <i class="fas fa-user-friends"></i>
                <strong>Untuk Banyak Perangkat</strong>
                <p>Cocok untuk keluarga, streaming, dan kerja dari rumah.</p>
            </div>
            <div class="summary-card">
                <i class="fas fa-headset"></i>
                <strong>Support 24/7</strong>
                <p>Dukungan teknis siap membantu kapan saja.</p>
            </div>
        </div>

        <div class="description">
            <p><?= $deskripsi ?></p>
        </div>

        <div class="features">
            <h4><i class="fas fa-star"></i> Fitur Paket</h4>
            <ul>
                <?php foreach($features as $feature): ?>
                <li><i class="fas fa-check-circle"></i> <?= $feature ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="buttons">
            <button onclick="openPopup()"><i class="fas fa-shopping-cart"></i> Pilih Paket</button>
            <button class="secondary" onclick="window.location='home_pelanggan.php'"><i class="fas fa-arrow-left"></i> Kembali</button>
        </div>

    </div>

</div>


<!-- POPUP PEMBAYARAN -->

<div class="popup" id="popup">

    <div class="popup-content">

        <h3>Konfirmasi Pembayaran</h3>

        <p style="color:#445a74;margin-bottom:24px;"><b>Paket:</b> <?= $nama ?></p>

        <div style="background:rgba(28,146,210,0.1);border:1px solid rgba(28,146,210,0.22);border-radius:16px;padding:16px;margin-bottom:28px;">
            <p style="color:#556b86;font-size:13px;margin:0 0 8px 0;">Total Tagihan</p>
            <p style="font-size:32px;font-weight:bold;color:#1c92d2;margin:0;">
                Rp <?= number_format($harga,0,',','.') ?>
            </p>
        </div>

        <form method="POST">

            <label style="display:block;color:#0f456c;font-weight:700;margin-bottom:14px;font-size:14px;">Pilih Metode Pembayaran</label>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:24px;" id="paymentMethodsContainer">
                <label style="display:flex;align-items:center;padding:14px;border:2px solid #dce5f0;border-radius:14px;cursor:pointer;transition:all 0.3s ease;background:white;">
                    <input type="radio" name="metode" value="GoPay" onchange="showPayment()" style="margin-right:10px;cursor:pointer;width:18px;height:18px;">
                    <span style="font-size:15px;color:#334758;"><span style="font-size:16px;">💳</span> GoPay</span>
                </label>

                <label style="display:flex;align-items:center;padding:14px;border:2px solid #dce5f0;border-radius:14px;cursor:pointer;transition:all 0.3s ease;background:white;">
                    <input type="radio" name="metode" value="QRIS" onchange="showPayment()" style="margin-right:10px;cursor:pointer;width:18px;height:18px;">
                    <span style="font-size:15px;color:#334758;"><span style="font-size:16px;">📱</span> QRIS</span>
                </label>

                <label style="display:flex;align-items:center;padding:14px;border:2px solid #dce5f0;border-radius:14px;cursor:pointer;transition:all 0.3s ease;background:white;grid-column:1/-1;">
                    <input type="radio" name="metode" value="Alfamart/Indomaret" onchange="showPayment()" style="margin-right:10px;cursor:pointer;width:18px;height:18px;">
                    <span style="font-size:15px;color:#334758;"><span style="font-size:16px;">🏪</span> Alfamart / Indomaret</span>
                </label>
            </div>

            <div id="paymentInfo" style="margin-bottom:24px;text-align:center;color:#405670;min-height:120px;display:flex;flex-direction:column;align-items:center;justify-content:center;"></div>

            <div style="margin-bottom:12px;">
                <button name="konfirmasi" type="submit" style="width:100%;">Konfirmasi Pembayaran</button>
            </div>

        </form>

        <button onclick="closePopup()" class="secondary" style="width:100%;">Batal</button>

    </div>

</div>


<script>

function openPopup(){
document.getElementById("popup").style.display="flex";
}

function closePopup(){
document.getElementById("popup").style.display="none";
}

function showPayment(){

let metode = document.querySelector('input[name="metode"]:checked');
let info = document.getElementById("paymentInfo");

if(!metode) {
    info.innerHTML = '';
    return;
}

let selectedValue = metode.value;

if(selectedValue == "GoPay"){

info.innerHTML = `
<div style="display:flex;flex-direction:column;align-items:center;width:100%;">
    <p style="margin:0 0 16px 0;color:#334758;font-weight:600;font-size:15px;">Scan QR GoPay</p>
    <img src="qris.jpeg" style="width:220px;height:220px;border-radius:14px;object-fit:cover;box-shadow:0 10px 30px rgba(0,0,0,0.12);margin-bottom:14px;">
    <p style="font-size:13px;color:#556b86;margin:0;">Gunakan aplikasi GoPay untuk melakukan pembayaran</p>
</div>
`;

}

else if(selectedValue == "QRIS"){

info.innerHTML = `
<div style="display:flex;flex-direction:column;align-items:center;width:100%;">
    <p style="margin:0 0 16px 0;color:#334758;font-weight:600;font-size:15px;">Scan QRIS Berikut</p>
    <img src="qris.jpeg" style="width:220px;height:220px;border-radius:14px;object-fit:cover;box-shadow:0 10px 30px rgba(0,0,0,0.12);margin-bottom:14px;">
    <p style="font-size:13px;color:#556b86;margin:0;">Scan menggunakan e-wallet atau mobile banking</p>
</div>
`;

}

else if(selectedValue == "Alfamart/Indomaret"){

let kode = "INV" + Math.floor(Math.random()*100000);

info.innerHTML = `
<div style="display:flex;flex-direction:column;align-items:center;width:100%;">
    <p style="margin:0 0 12px 0;color:#334758;font-weight:600;font-size:15px;">Kode Pembayaran</p>
    <div style="background:linear-gradient(135deg,#0f2027,#1c92d2);color:white;padding:20px;border-radius:14px;margin-bottom:14px;min-width:200px;text-align:center;">
        <p style="font-size:12px;margin:0 0 8px 0;opacity:0.9;">Gunakan kode berikut di kasir:</p>
        <h2 style="margin:0;font-size:28px;font-weight:bold;letter-spacing:2px;">${kode}</h2>
    </div>
    <p style="font-size:13px;color:#556b86;margin:0;">Tunjukkan kode ini di Alfamart atau Indomaret</p>
</div>
`;

}

}

</script>

</body>
</html>