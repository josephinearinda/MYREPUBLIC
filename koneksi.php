<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role']!="cs"){
    header("Location: pilih_role.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id_transaksi = intval($_POST['id_transaksi'] ?? 0);
    $tanggal = $_POST['tanggal'] ?? '';

    if($id_transaksi > 0 && $tanggal){
        $cekTransaksi = mysqli_query($conn, "SELECT id, status, user_id, paket FROM transaksi WHERE id='$id_transaksi'");
        $rowTransaksi = mysqli_fetch_assoc($cekTransaksi);

        if($rowTransaksi){
            $user_id = $rowTransaksi['user_id'];
            $paket = mysqli_real_escape_string($conn, $rowTransaksi['paket']);
            $tanggalEsc = mysqli_real_escape_string($conn, $tanggal);

            mysqli_query($conn, "INSERT INTO jadwal(transaksi_id,tanggal,status) VALUES('$id_transaksi', '$tanggalEsc', 'Terjadwal')");

            // Update status transaksi jadi Terjadwal
            mysqli_query($conn, "UPDATE transaksi SET status='Terjadwal' WHERE id='$id_transaksi'");

            // Insert laporan otomatis
            $laporanTitle = 'Jadwal Pemasangan Dibuat';
            $laporanDesc = 'Jadwal pemasangan telah ditetapkan untuk transaksi ID '. $id_transaksi .'.';
            mysqli_query($conn, "INSERT INTO laporan(transaksi_id, cs_id, judul, deskripsi, status) VALUES('$id_transaksi', '{$_SESSION['user']['id']}', '$laporanTitle', '$laporanDesc', 'Terjadwal')");

            // Insert notifikasi otomatis ke pelanggan
            mysqli_query($conn, "INSERT INTO notifikasi(user_id, judul, pesan, tipe, status) VALUES('$user_id', '🗓️ Jadwal Pemasangan Dibuat', 'Jadwal pemasangan paket $paket telah ditetapkan pada $tanggalEsc. Silakan cek status layanan.', 'jadwal', 'Belum dibaca')");

            $_SESSION['msg'] = 'Jadwal pemasangan berhasil disimpan dan notifikasi dikirim.';
        } else {
            $_SESSION['msg'] = 'Transaksi tidak ditemukan.';
        }
    } else {
        $_SESSION['msg'] = 'ID transaksi dan tanggal jadwal harus diisi.';
    }

    header('Location: home_cs.php');
    exit;
}

header('Location: home_cs.php');
exit;
