<?php
include 'koneksi.php';

mysqli_report(MYSQLI_REPORT_OFF);

// Buat tabel laporan
$query2 = "CREATE TABLE IF NOT EXISTS laporan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaksi_id INT NOT NULL,
    cs_id INT NOT NULL,
    judul VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    status VARCHAR(50),
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (transaksi_id) REFERENCES transaksi(id),
    FOREIGN KEY (cs_id) REFERENCES users(id)
)";
mysqli_query($conn, $query2);
echo "✓ Tabel laporan siap.<br>";

// Buat tabel notifikasi
$query3 = "CREATE TABLE IF NOT EXISTS notifikasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    judul VARCHAR(255) NOT NULL,
    pesan TEXT NOT NULL,
    tipe VARCHAR(50),
    status VARCHAR(50) DEFAULT 'Belum dibaca',
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";
mysqli_query($conn, $query3);
echo "✓ Tabel notifikasi siap.<br>";

echo "<br>✅ Setup database selesai!";
?>