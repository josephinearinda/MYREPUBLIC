📋 PANDUAN MIGRASI DATABASE KE LARAGON
=====================================

File yang sudah dibuat: myrepublic_db.sql

LANGKAH-LANGKAH MIGRASI:

1️⃣ INSTALL & SETUP LARAGON
   - Download Laragon dari https://laragon.org/
   - Install dan jalankan Laragon
   - Pastikan MySQL sudah running

2️⃣ COPY PROJECT KE LARAGON
   - Copy folder MYREPUBLIC ke: C:\laragon\www\MYREPUBLIC
   - atau folder www Laragon Anda

3️⃣ UPDATE FILE KONEKSI (PENTING!)
   Buka file: koneksi.php
   
   Ubah dari:
   $conn = mysqli_connect("localhost","root","","myrepublic_db");
   
   Menjadi:
   $conn = mysqli_connect("localhost","root","","myrepublic_db");
   
   ⚠️ Note: Laragon default username adalah 'root' (sama seperti XAMPP)
   
4️⃣ IMPORT DATABASE VIA LARAGON PHPMYADMIN
   - Buka browser: http://localhost/phpmyadmin
   - Login dengan username: root (password kosong)
   - Klik tab "Import"
   - Pilih file: myrepublic_db.sql
   - Klik "Go" untuk import

   ATAU

5️⃣ IMPORT DATABASE VIA COMMAND LINE
   - Buka Command Prompt/PowerShell
   - Arahkan ke folder project: cd C:\laragon\www\MYREPUBLIC
   - Jalankan command:
   
   mysql -u root -p myrepublic_db < myrepublic_db.sql
   
   (tekan Enter jika diminta password, Laragon tidak pake password)

6️⃣ VERIFIKASI DATABASE
   - Buka http://localhost/phpmyadmin
   - Cek apakah database 'myrepublic_db' sudah ada
   - Cek semua tabel sudah dibuat (users, paket, transaksi, jadwal, laporan, notifikasi)

7️⃣ TEST APLIKASI
   - Buka browser: http://localhost/MYREPUBLIC/
   - Coba login dengan data sample atau register akun baru
   - Pastikan tidak ada error di database

⚠️ JIKA ADA ERROR:

- Error "Database tidak ada":
  Pastikan sudah import SQL file dengan benar

- Error "Koneksi gagal":
  Cek apakah MySQL di Laragon sudah running
  Buka Laragon app, klik tombol MySQL "Start"

- Error "Access Denied":
  Update username/password di koneksi.php
  Default Laragon: username='root', password='' (kosong)

- Error "Table sudah ada":
  Edit myrepublic_db.sql, hapus atau edit CREATE TABLE statements
  Atau gunakan PhpMyAdmin untuk drop database terlebih dahulu

✅ TIPS:
- Laragon jauh lebih cepat dari XAMPP
- MySQL di Laragon lebih stabil
- PhpMyAdmin di Laragon lebih responsive
- Jika perlu reset database, cukup drop di PhpMyAdmin lalu import ulang

Sukses migrasi! 🎉
