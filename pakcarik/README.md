# PAK CARIK — Portal Layanan Digital Kecamatan Gandusari
## Panduan Setup & Instalasi

---

## 📁 Struktur Folder

```
pakcarik/
├── assets/
│   ├── css/
│   │   └── style.css          ← CSS utama
│   ├── js/
│   │   └── script.js          ← JavaScript utama
│   ├── img/                   ← Semua gambar
│   │   ├── berita/            ← Foto berita (berita1.jpg - berita5.jpg)
│   │   ├── carik.png
│   │   ├── logo-kab.png
│   │   ├── ikon_*.jpg
│   │   └── ...
│   └── php/
│       └── koneksi.php        ← Konfigurasi database
├── pages/                     ← Halaman layanan
│   ├── jadwal-undangan.html
│   ├── jadwal-apel.html
│   ├── jadwal-data.html
│   ├── ektp.html
│   ├── sidang-waris.html
│   ├── pbb.html
│   ├── kearsipan.html
│   └── pelayanan.html
├── admin/                     ← Panel admin (PHP)
│   ├── login.php
│   ├── dashboard.php
│   ├── undangan.php
│   ├── apel.php
│   ├── permintaan-data.php
│   ├── ektp.php
│   ├── sidang-waris.php
│   ├── pbb.php
│   ├── kearsipan.php
│   ├── pelayanan.php
│   └── logout.php
├── db_pakcarik.sql            ← File database (import ke phpMyAdmin)
└── index.html                 ← Halaman utama
```

---

## 🚀 Cara Setup di Laragon

### 1. Copy Folder ke Laragon
```
Salin folder `pakcarik` ke:
C:\laragon\www\pakcarik\
```

### 2. Copy Gambar ke assets/img/
Salin semua file gambar (carik.png, logo-kab.png, ikon_*.jpg, dll.)
ke folder: `assets/img/`

Untuk foto berita, simpan di: `assets/img/berita/`
- berita1.jpg → Foto Apel Kerja
- berita2.jpg → Foto Purnawiyata MIN 10
- berita3.jpg → Foto Pembangunan Gedung
- berita4.jpg → Foto Rapat Koordinasi PKK
- berita5.jpg → Foto Peta Proses Bisnis

### 3. Import Database
1. Buka phpMyAdmin: `localhost/phpmyadmin`
2. Klik **New** → buat database baru: `db_pakcarik`
3. Klik database `db_pakcarik`
4. Klik tab **Import**
5. Pilih file `db_pakcarik.sql`
6. Klik **Go**

### 4. Akses Website
- **Halaman Utama:** `localhost/pakcarik`
- **Panel Admin:**   `localhost/pakcarik/admin/login.php`

---

## 🔐 Login Admin Default
| Username | Password  |
|----------|-----------|
| admin    | admin123  |

> ⚠️ Segera ganti password setelah login pertama!

---

## 🔗 Menghubungkan HTML ke PHP/Database

Halaman layanan (pages/*.html) saat ini menampilkan pesan "belum ada data".
Untuk menampilkan data dari database, ganti ekstensi file dari `.html` ke `.php`
dan tambahkan kode PHP di bagian `<tbody>`.

### Contoh untuk jadwal-undangan.html → jadwal-undangan.php:
```php
<?php
require_once '../assets/php/koneksi.php';
$data = $conn->query("SELECT * FROM tbl_undangan ORDER BY tanggal DESC");
?>
...
<tbody>
<?php $no=1; while ($row = $data->fetch_assoc()): ?>
<tr>
  <td><?= $no++ ?></td>
  <td><?= htmlspecialchars($row['judul']) ?></td>
  <td><?= tglIndo($row['tanggal']) ?></td>
  <td><?= substr($row['waktu'],0,5) ?> WIB</td>
  <td><?= htmlspecialchars($row['lokasi']) ?></td>
  <td><?= htmlspecialchars($row['penyelenggara']) ?></td>
  <td><?= htmlspecialchars($row['keterangan'] ?: '-') ?></td>
  <td><?= badgeStatus($row['status']) ?></td>
</tr>
<?php endwhile; ?>
</tbody>
```

---

## 📝 Catatan
- Admin Panel (folder `admin/`) sudah menggunakan PHP + MySQL secara penuh
- Halaman layanan publik (folder `pages/`) masih HTML statis — perlu diubah ke PHP untuk menampilkan data dinamis
- Semua 8 CRUD admin sudah tersedia di folder `admin/`

---

## 📞 Kontak
Kecamatan Gandusari · Jl. Raya Kawi No. 57 · (0342) 692032
