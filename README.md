-- 1. Buat database
CREATE DATABASE IF NOT EXISTS db_pustaka_digital;
USE db_pustaka_digital;

-- 2. Tabel buku
CREATE TABLE `buku` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_buku` varchar(20) NOT NULL,
  `judul_buku` varchar(200) NOT NULL,
  `pengarang` varchar(100) NOT NULL,
  `kategori` varchar(200) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_buku` (`kode_buku`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- 3. Tabel peminjaman
CREATE TABLE `peminjaman` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_peminjaman` varchar(30) NOT NULL,
  `nama_peminjam` varchar(100) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `judul_buku` varchar(200) NOT NULL,
  `tanggal_peminjaman` date NOT NULL,
  `tanggal_pengembalian` date NOT NULL,
  `status` enum('Dipinjam','Dikembalikan') DEFAULT 'Dipinjam',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_peminjaman` (`kode_peminjaman`),
  KEY `id_buku` (`id_buku`),
  CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- 4. Tabel users (pakai ini, lebih lengkap)
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','petugas') DEFAULT 'petugas',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- 5. (Opsional) Hapus tabel user lama kalau ada
-- DROP TABLE IF EXISTS `user`;


-- 6. Data buku
INSERT INTO `buku` (`kode_buku`, `judul_buku`, `pengarang`, `kategori`, `stok`) VALUES
('BK001', 'Laskar Pelangi', 'Andrea Hirata', 'Fiksi,Petualangan', 9),
('BK002', 'Bumi', 'Tere Liye', 'Fiksi,Sains', 3),
('BK003', 'Filosofi Kopi', 'Dewi Lestari', 'Fiksi,Filsafat', 0),
('BK004', 'Pulang', 'Tere Liye', 'Fiksi,Drama', 5),
('BK005', 'Negeri 5 Menara', 'A.Fuadi', 'Fiksi,Inspirasi', 7);

-- 7. Data users
INSERT INTO `users` (`username`, `password`, `role`) VALUES
('admin', 'admin123', 'admin'),
('petugas', 'petugas123', 'petugas');

-- 8. Data peminjaman (contoh)
INSERT INTO `peminjaman` (`kode_peminjaman`, `nama_peminjam`, `id_buku`, `judul_buku`, `tanggal_peminjaman`, `tanggal_pengembalian`, `status`) VALUES
('PJM001', 'Ahmad Wijaya', 1, 'Laskar Pelangi', '2026-05-10', '2026-05-17', 'Dipinjam'),
('PJM002', 'Veronika', 1, 'Laskar Pelangi', '2026-05-13', '2026-05-18', 'Dipinjam');
