-- ═══════════════════════════════════════════════════════
-- PAK CARIK — Database Schema
-- Kecamatan Gandusari, Kabupaten Blitar
-- 
-- Cara import:
-- 1. Buka phpMyAdmin (localhost/phpmyadmin)
-- 2. Buat database baru: db_pakcarik
-- 3. Klik Import → pilih file ini → klik Go
-- ═══════════════════════════════════════════════════════

CREATE DATABASE IF NOT EXISTS `db_pakcarik`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `db_pakcarik`;

-- ─────────────────────────────────────────
-- 1. ADMIN
-- ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `tbl_admin` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `nama`       VARCHAR(100) NOT NULL,
  `username`   VARCHAR(60)  NOT NULL UNIQUE,
  `password`   VARCHAR(255) NOT NULL COMMENT 'bcrypt hash',
  `level`      ENUM('superadmin','admin') NOT NULL DEFAULT 'admin',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default admin: username=admin | password=admin123
INSERT INTO `tbl_admin` (`nama`,`username`,`password`,`level`) VALUES
  ('Administrator','admin','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','superadmin');

-- ─────────────────────────────────────────
-- 2. JADWAL UNDANGAN
-- ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `tbl_undangan` (
  `id`             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `judul`          VARCHAR(255) NOT NULL,
  `tanggal`        DATE         NOT NULL,
  `waktu`          TIME         NOT NULL,
  `lokasi`         VARCHAR(255) NOT NULL,
  `penyelenggara`  VARCHAR(150) NOT NULL,
  `keterangan`     TEXT,
  `status`         ENUM('aktif','selesai','batal') NOT NULL DEFAULT 'aktif',
  `created_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Contoh data
INSERT INTO `tbl_undangan` (`judul`,`tanggal`,`waktu`,`lokasi`,`penyelenggara`,`keterangan`,`status`) VALUES
  ('Rapat Koordinasi Camat Se-Kabupaten Blitar','2024-05-13','09:00:00','Aula Pendopo Kabupaten Blitar','Pemerintah Kabupaten Blitar','Wajib hadir bagi seluruh Camat','aktif'),
  ('Undangan Apel Hari Kebangkitan Nasional','2024-05-20','07:00:00','Lapangan Kecamatan Gandusari','Kecamatan Gandusari','Seragam KORPRI','aktif'),
  ('Rapat Evaluasi Pelayanan Publik','2024-05-22','10:00:00','Ruang Rapat Kecamatan Gandusari','Kecamatan Gandusari','-','selesai');

-- ─────────────────────────────────────────
-- 3. JADWAL PERMINTAAN DATA
-- ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `tbl_permintaan_data` (
  `id`               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `nama_pemohon`     VARCHAR(150) NOT NULL,
  `instansi`         VARCHAR(200) NOT NULL,
  `jenis_data`       VARCHAR(200) NOT NULL,
  `tanggal_pengajuan` DATE        NOT NULL,
  `tanggal_jadwal`   DATE,
  `keperluan`        TEXT         NOT NULL,
  `status`           ENUM('pending','proses','selesai','batal') NOT NULL DEFAULT 'pending',
  `created_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tbl_permintaan_data` (`nama_pemohon`,`instansi`,`jenis_data`,`tanggal_pengajuan`,`tanggal_jadwal`,`keperluan`,`status`) VALUES
  ('Budi Santoso','BPS Kabupaten Blitar','Data Kependudukan 2024','2024-05-10','2024-05-15','Keperluan Sensus Penduduk','selesai'),
  ('Siti Rahayu','Dinas Kesehatan','Data Posyandu Se-Kecamatan','2024-05-12','2024-05-18','Evaluasi Program Kesehatan Ibu & Anak','proses');

-- ─────────────────────────────────────────
-- 4. JADWAL APEL
-- ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `tbl_apel` (
  `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `jenis_apel`    VARCHAR(150) NOT NULL,
  `tanggal`       DATE         NOT NULL,
  `waktu`         TIME         NOT NULL,
  `lokasi`        VARCHAR(255) NOT NULL,
  `pemimpin_apel` VARCHAR(150) NOT NULL,
  `keterangan`    TEXT,
  `status`        ENUM('aktif','selesai','batal') NOT NULL DEFAULT 'aktif',
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tbl_apel` (`jenis_apel`,`tanggal`,`waktu`,`lokasi`,`pemimpin_apel`,`keterangan`,`status`) VALUES
  ('Apel Pagi Senin','2024-05-13','07:00:00','Lapangan Kecamatan Gandusari','Camat Gandusari','Seluruh pegawai wajib hadir','selesai'),
  ('Apel Hari Kebangkitan Nasional','2024-05-20','07:00:00','Lapangan Kecamatan Gandusari','Camat Gandusari','Seragam KORPRI lengkap','aktif'),
  ('Apel Pagi Senin','2024-05-27','07:00:00','Lapangan Kecamatan Gandusari','Sekcam Gandusari','-','aktif');

-- ─────────────────────────────────────────
-- 5. E-KTP
-- ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `tbl_ektp` (
  `id`               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `nama`             VARCHAR(150) NOT NULL,
  `nik`              VARCHAR(16)  NOT NULL,
  `alamat`           TEXT         NOT NULL,
  `tanggal_pengajuan` DATE        NOT NULL,
  `tanggal_selesai`  DATE,
  `keterangan`       TEXT,
  `status`           ENUM('pending','proses','selesai','batal') NOT NULL DEFAULT 'pending',
  `created_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tbl_ektp` (`nama`,`nik`,`alamat`,`tanggal_pengajuan`,`tanggal_selesai`,`keterangan`,`status`) VALUES
  ('Ahmad Fauzi','3505012501900001','Ds. Gandusari RT 01/01 Kec. Gandusari','2024-05-08','2024-05-15','KTP Baru','selesai'),
  ('Dewi Rahmawati','3505015002950002','Ds. Sumberagung RT 02/03 Kec. Gandusari','2024-05-10',NULL,'Perpanjangan KTP','proses'),
  ('Supriadi','3505011203880003','Ds. Tulungrejo RT 01/02 Kec. Gandusari','2024-05-13',NULL,'KTP Baru','pending');

-- ─────────────────────────────────────────
-- 6. JADWAL SIDANG WARIS
-- ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `tbl_sidang_waris` (
  `id`             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `nama_pemohon`   VARCHAR(150) NOT NULL,
  `tanggal_sidang` DATE         NOT NULL,
  `waktu`          TIME         NOT NULL,
  `nomor_perkara`  VARCHAR(100),
  `keterangan`     TEXT,
  `status`         ENUM('terjadwal','selesai','batal','ditunda') NOT NULL DEFAULT 'terjadwal',
  `created_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tbl_sidang_waris` (`nama_pemohon`,`tanggal_sidang`,`waktu`,`nomor_perkara`,`keterangan`,`status`) VALUES
  ('Keluarga Bpk. Slamet Riyadi','2024-05-14','09:00:00','WRS/2024/001','Pembagian tanah warisan di Ds. Gandusari','selesai'),
  ('Keluarga Bpk. Sunarto','2024-05-21','10:00:00','WRS/2024/002','Pembagian harta peninggalan','terjadwal'),
  ('Keluarga Ibu Sutinah','2024-05-28','09:30:00','WRS/2024/003','-','terjadwal');

-- ─────────────────────────────────────────
-- 7. PAJAK BUMI DAN BANGUNAN (PBB)
-- ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `tbl_pbb` (
  `id`               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `nama_wajib_pajak` VARCHAR(150) NOT NULL,
  `nop`              VARCHAR(30)  NOT NULL COMMENT 'Nomor Objek Pajak',
  `alamat_objek`     TEXT         NOT NULL,
  `luas_tanah`       DECIMAL(10,2) NOT NULL COMMENT 'dalam m2',
  `luas_bangunan`    DECIMAL(10,2) NOT NULL COMMENT 'dalam m2',
  `njop`             BIGINT       NOT NULL COMMENT 'Nilai Jual Objek Pajak',
  `tahun`            YEAR         NOT NULL,
  `status_bayar`     ENUM('lunas','belum','cicilan') NOT NULL DEFAULT 'belum',
  `created_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tbl_pbb` (`nama_wajib_pajak`,`nop`,`alamat_objek`,`luas_tanah`,`luas_bangunan`,`njop`,`tahun`,`status_bayar`) VALUES
  ('Ahmad Suroto','35050100001001','Ds. Gandusari RT 01/01',200.00,80.00,150000000,2024,'lunas'),
  ('Sari Wulandari','35050100001002','Ds. Sumberagung RT 02/02',350.00,120.00,280000000,2024,'belum'),
  ('Bambang Supeno','35050100001003','Ds. Tulungrejo RT 03/01',180.00,60.00,90000000,2024,'lunas');

-- ─────────────────────────────────────────
-- 8. KEARSIPAN
-- ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `tbl_arsip` (
  `id`             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `nomor_arsip`    VARCHAR(100) NOT NULL,
  `judul_dokumen`  VARCHAR(255) NOT NULL,
  `jenis_arsip`    ENUM('surat_masuk','surat_keluar','sk','perda','lainnya') NOT NULL,
  `tanggal_dokumen` DATE        NOT NULL,
  `pengirim`       VARCHAR(150),
  `penerima`       VARCHAR(150),
  `lokasi_simpan`  VARCHAR(255),
  `keterangan`     TEXT,
  `created_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tbl_arsip` (`nomor_arsip`,`judul_dokumen`,`jenis_arsip`,`tanggal_dokumen`,`pengirim`,`penerima`,`lokasi_simpan`,`keterangan`) VALUES
  ('SM/2024/001','Surat Undangan Rapat Koordinasi','surat_masuk','2024-05-08','Bupati Blitar','Camat Gandusari','Lemari A Rak 1','Sudah direspon'),
  ('SK/2024/015','Surat Keterangan Domisili Perusahaan','surat_keluar','2024-05-10','Camat Gandusari','PT. Maju Jaya','Lemari B Rak 2','-'),
  ('SK/2024/016','Surat Rekomendasi SKCK','surat_keluar','2024-05-12','Camat Gandusari','Polsek Gandusari','Lemari B Rak 2','-');

-- ─────────────────────────────────────────
-- 9. PELAYANAN (Perwalian & Legalisasi)
-- ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `tbl_pelayanan` (
  `id`               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `jenis_layanan`    ENUM('perwalian','legalisasi','dispensasi_nikah','proposal','lainnya') NOT NULL,
  `nama_pemohon`     VARCHAR(150) NOT NULL,
  `nik`              VARCHAR(16)  NOT NULL,
  `alamat`           TEXT         NOT NULL,
  `keperluan`        TEXT         NOT NULL,
  `tanggal_pengajuan` DATE        NOT NULL,
  `tanggal_selesai`  DATE,
  `keterangan`       TEXT,
  `status`           ENUM('pending','proses','selesai','batal') NOT NULL DEFAULT 'pending',
  `created_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tbl_pelayanan` (`jenis_layanan`,`nama_pemohon`,`nik`,`alamat`,`keperluan`,`tanggal_pengajuan`,`tanggal_selesai`,`keterangan`,`status`) VALUES
  ('legalisasi','Siti Aminah','3505016505920001','Ds. Gandusari RT 02/01','Legalisasi fotokopi KK untuk beasiswa','2024-05-09','2024-05-09','Selesai hari yang sama','selesai'),
  ('perwalian','Bpk. Heru Susanto','3505011204750002','Ds. Sumberagung RT 01/03','Penetapan wali anak yatim','2024-05-11',NULL,'Menunggu berkas lengkap','proses'),
  ('dispensasi_nikah','Agus Prasetyo','3505010906050003','Ds. Tulungrejo RT 02/02','Dispensasi nikah usia 17 tahun','2024-05-13',NULL,'-','pending');

-- ─────────────────────────────────────────
-- SELESAI
-- ─────────────────────────────────────────
-- Total tabel: 9 (1 admin + 8 layanan)
-- Default admin login:
--   Username : admin
--   Password : admin123
