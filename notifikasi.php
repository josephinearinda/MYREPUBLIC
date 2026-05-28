
CREATE DATABASE IF NOT EXISTS `myrepublic_db`;
USE `myrepublic_db`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` VARCHAR(50) NOT NULL DEFAULT 'pelanggan',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `paket` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_paket` VARCHAR(255) NOT NULL,
  `kecepatan` VARCHAR(50) NOT NULL,
  `harga` INT NOT NULL,
  `deskripsi` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `transaksi` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `paket_id` INT NOT NULL,
  `paket` VARCHAR(255) NOT NULL,
  `harga` INT NOT NULL,
  `metode` VARCHAR(100),
  `status` VARCHAR(50) DEFAULT 'Diproses',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
  FOREIGN KEY (`paket_id`) REFERENCES `paket`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `jadwal` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `transaksi_id` INT NOT NULL,
  `tanggal` DATE NOT NULL,
  `status` VARCHAR(50) DEFAULT 'Terjadwal',
  `laporan` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `laporan` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `transaksi_id` INT NOT NULL,
  `cs_id` INT NOT NULL,
  `judul` VARCHAR(255) NOT NULL,
  `deskripsi` TEXT,
  `status` VARCHAR(50),
  `tanggal` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi`(`id`),
  FOREIGN KEY (`cs_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `notifikasi` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `judul` VARCHAR(255) NOT NULL,
  `pesan` TEXT NOT NULL,
  `tipe` VARCHAR(50),
  `status` VARCHAR(50) DEFAULT 'Belum dibaca',
  `tanggal` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`nama`, `email`, `password`, `role`) VALUES
('Admin', 'admin@myrepublic.com', '$2y$10$YourHashedPasswordHere', 'admin'),
('Customer Service', 'cs@myrepublic.com', '$2y$10$YourHashedPasswordHere', 'cs'),
('Teknisi Instalasi', 'teknisi@myrepublic.com', '$2y$10$YourHashedPasswordHere', 'teknisi'),
('Pelanggan Demo', 'pelanggan@myrepublic.com', '$2y$10$YourHashedPasswordHere', 'pelanggan');


INSERT INTO `paket` (`nama_paket`, `kecepatan`, `harga`, `deskripsi`) VALUES
('Family Plan', '50 Mbps', 300000, 'Paket hemat untuk keluarga kecil dengan penggunaan standar.'),
('Super Seru', '100 Mbps', 350000, 'Cocok untuk streaming, browsing, dan kebutuhan rumah tangga.'),
('Gaming Pro', '150 Mbps', 500000, 'Khusus gamer dengan koneksi stabil dan latency rendah.'),
('Ultra Max', '300 Mbps', 750000, 'Kecepatan maksimal untuk rumah besar dan kebutuhan bisnis.');

-- ===================================
-- SELESAI
-- ===================================
-- Database siap digunakan dengan Laragon
-- Semua tabel sudah dibuat dengan relasi yang benar
