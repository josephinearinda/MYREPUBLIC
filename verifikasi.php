<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != "teknisi"){
    header("Location: pilih_role.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $jadwal_id = intval($_POST['jadwal_id'] ?? 0);
    $laporan = trim($_POST['laporan'] ?? '');

    if($jadwal_id > 0){
        // Update jadwal dengan laporan (jika ada) dan status
        if(!empty($laporan)){
            $updateJadwal = mysqli_query($conn, "UPDATE jadwal SET laporan='$laporan', status='Selesai' WHERE id='$jadwal_id'");
        } else {
            $updateJadwal = mysqli_query($conn, "UPDATE jadwal SET status='Selesai' WHERE id='$jadwal_id'");
        }

        if($updateJadwal){
            // Update status transaksi juga
            $getTransaksi = mysqli_query($conn, "SELECT j.transaksi_id, t.user_id FROM jadwal j JOIN transaksi t ON j.transaksi_id = t.id WHERE j.id='$jadwal_id'");
            $row = mysqli_fetch_assoc($getTransaksi);
            if($row){
                mysqli_query($conn, "UPDATE transaksi SET status='Selesai' WHERE id='{$row['transaksi_id']}'");
                
                // Insert laporan otomatis dari teknisi
                $getTransaction = mysqli_query($conn, "SELECT paket FROM transaksi WHERE id='{$row['transaksi_id']}'");
                $transData = mysqli_fetch_assoc($getTransaction);
                $paket = $transData['paket'];
                $laporanTitle = 'Pemasangan Selesai';
                $laporanDesc = "Pemasangan paket $paket untuk transaksi ID {$row['transaksi_id']} telah selesai dan layanan aktif.";
                mysqli_query($conn, "INSERT INTO laporan(transaksi_id, cs_id, judul, deskripsi, status) VALUES('{$row['transaksi_id']}', '{$_SESSION['user']['id']}', '$laporanTitle', '$laporanDesc', 'Selesai')");
                
                // Insert notifikasi ke pelanggan
                $insertNotif = mysqli_query($conn, "INSERT INTO notifikasi(user_id, judul, pesan, tipe, status) 
                VALUES('{$row['user_id']}', '✅ Pemasangan Selesai', 'Pemasangan untuk paket $paket telah selesai. Layanan Anda sekarang aktif!', 'pemasangan', 'Belum dibaca')");
            }

            $_SESSION['msg'] = 'Status berhasil diperbarui.';
        } else {
            $_SESSION['msg'] = 'Gagal memperbarui status.';
        }
    } else {
        $_SESSION['msg'] = 'ID jadwal harus diisi.';
    }

    header('Location: home_teknisi.php');
    exit;
}

header('Location: home_teknisi.php');
exit;
?>