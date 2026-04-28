<?php
include '../config/koneksi.php';

$query = mysqli_query($koneksi, "SELECT * FROM tbl_permintaan_data ORDER BY tanggal_jadwal ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jadwal Permintaan Data — PAK CARIK Gandusari</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header class="site-header scrolled" id="site-header">
  <div class="topbar"><div class="container"><span>📍 Jl. Raya Kawi No. 57 Gandusari, Blitar &nbsp;·&nbsp; ☎ (0342) 692032</span></div></div>
  <div class="navbar-wrap"><div class="container"><nav class="navbar">
    <a class="brand" href="../index.html"><img class="brand-logo" src="../assets/img/carik.png" alt="PAK CARIK"><div class="brand-copy"><p class="brand-kicker">Peningkatan Layanan Kesekretariatan</p><h1 class="brand-title">Kecamatan Gandusari</h1><p class="brand-sub">Melalui Aplikasi E-Letter</p></div></a>
    <button class="nav-toggle" aria-label="Menu">☰</button>
    <ul class="nav-list"><li><a href="../index.html">Beranda</a></li><li><a href="../index.html#layanan" class="active">Layanan</a></li><li><a href="../index.html#kontak">Kontak</a></li></ul>
  </nav></div></div>
</header>
<div class="page-hero page-hero-data">
  <div class="container">
    <div class="breadcrumb"><a href="../index.html">Beranda</a><span>/</span><span>Jadwal Permintaan Data</span></div>
    <h1>Jadwal Permintaan Data</h1>
    <p>Layanan pengajuan dan penjadwalan permintaan data administrasi yang terstruktur.</p>
  </div>
</div>
<main style="padding:48px 0 80px;">
  <div class="container">
    <div class="panel">
      <div class="action-bar">
        <div><div class="section-badge">📊 Data</div><h2 class="section-title" style="font-size:1.6rem;">Daftar Jadwal Permintaan Data</h2></div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
          <div class="search-box"><span>🔍</span><input type="text" id="searchInput" placeholder="Cari data..."></div>
          <button class="btn btn-blue btn-sm" onclick="printTable()">🖨️ Cetak</button>
        </div>
      </div>
      <div class="table-wrapper">
        <table id="tabelData">
          <thead><tr><th>No</th><th>Nama Pemohon</th><th>Instansi</th><th>Jenis Data</th><th>Tgl Pengajuan</th><th>Tgl Jadwal</th><th>Keperluan</th><th>Status</th></tr></thead>
          <tbody>
<?php
$no = 1;
if (mysqli_num_rows($query) > 0) {
  while ($row = mysqli_fetch_assoc($query)) {
?>
  <tr>
    <td><?= $no++; ?></td>
    <td><?= $row['nama_pemohon']; ?></td>
    <td><?= $row['instansi']; ?></td>
    <td><?= $row['jenis_data']; ?></td>
    <td><?= $row['tanggal_pengajuan']; ?></td>
    <td><?= $row['tanggal_jadwal']; ?></td>
    <td><?= $row['keperluan']; ?></td>
    <td><?= $row['status']; ?></td>
  </tr>
<?php
  }
} else {
?>
  <tr>
    <td colspan="8" style="text-align:center;padding:40px;color:var(--text-soft);">
      <div style="font-size:2rem;margin-bottom:10px;">📭</div>
      <strong>Belum ada data permintaan.</strong><br>
      <span style="font-size:13px;">Data akan tampil setelah dihubungkan ke database.</span>
    </td>
  </tr>
<?php } ?>
</tbody>
        </table>
      </div>
    </div>
  </div>
</main>
<footer class="site-footer" style="padding-top:30px;"><div class="container"><div class="footer-bottom"><span>© 2024 Kecamatan Gandusari — Portal Layanan Digital v2.0</span><a href="../index.html">← Kembali ke Beranda</a></div></div></footer>
<script src="../assets/js/script.js"></script>
<script>searchTable('searchInput','tabelData');</script>
</body>
</html>
