<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
require_once '../assets/php/koneksi.php';

$msg = '';
$edit = null;

// HAPUS
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $conn->query("DELETE FROM tbl_undangan WHERE id=$id");
    $msg = '<div class="alert alert-success">✅ Data berhasil dihapus.</div>';
}

// SIMPAN / UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id           = (int)($_POST['id'] ?? 0);
    $judul        = esc($conn, $_POST['judul'] ?? '');
    $tanggal      = esc($conn, $_POST['tanggal'] ?? '');
    $waktu        = esc($conn, $_POST['waktu'] ?? '');
    $lokasi       = esc($conn, $_POST['lokasi'] ?? '');
    $penyelenggara = esc($conn, $_POST['penyelenggara'] ?? '');
    $keterangan   = esc($conn, $_POST['keterangan'] ?? '');
    $status       = esc($conn, $_POST['status'] ?? 'aktif');

    if ($id > 0) {
        $conn->query("UPDATE tbl_undangan SET judul='$judul',tanggal='$tanggal',waktu='$waktu',lokasi='$lokasi',penyelenggara='$penyelenggara',keterangan='$keterangan',status='$status' WHERE id=$id");
        $msg = '<div class="alert alert-success">✅ Data berhasil diupdate.</div>';
    } else {
        $conn->query("INSERT INTO tbl_undangan (judul,tanggal,waktu,lokasi,penyelenggara,keterangan,status) VALUES ('$judul','$tanggal','$waktu','$lokasi','$penyelenggara','$keterangan','$status')");
        $msg = '<div class="alert alert-success">✅ Data berhasil ditambahkan.</div>';
    }
}

// EDIT
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $r  = $conn->query("SELECT * FROM tbl_undangan WHERE id=$id");
    $edit = $r->fetch_assoc();
}

// AMBIL DATA
$data = $conn->query("SELECT * FROM tbl_undangan ORDER BY tanggal DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jadwal Undangan — Admin PAK CARIK</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Sora:wght@800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    body { background: #f4f7ff; display: flex; min-height: 100vh; }
    .sidebar { width:260px; background:linear-gradient(175deg,#0c1e5a,#081340); color:#fff; position:fixed; top:0; left:0; bottom:0; display:flex; flex-direction:column; z-index:100; }
    .sidebar-head { padding:24px 20px; border-bottom:1px solid rgba(255,255,255,.1); }
    .sidebar-head img { width:48px; margin-bottom:8px; }
    .sidebar-head h2 { font-family:'Sora',sans-serif; font-size:.95rem; font-weight:900; color:#f9b234; }
    .sidebar-nav { flex:1; padding:14px 10px; overflow-y:auto; }
    .nav-sec { font-size:10px; font-weight:800; letter-spacing:.18em; text-transform:uppercase; color:rgba(255,255,255,.4); padding:12px 10px 6px; }
    .sidebar-nav a { display:flex; align-items:center; gap:10px; padding:9px 12px; border-radius:10px; color:rgba(255,255,255,.78); font-size:13px; font-weight:600; margin-bottom:2px; transition:background .18s; text-decoration:none; }
    .sidebar-nav a:hover, .sidebar-nav a.active { background:rgba(255,255,255,.14); color:#fff; }
    .sidebar-foot { padding:14px 10px; border-top:1px solid rgba(255,255,255,.1); }
    .sidebar-foot a { display:flex; align-items:center; gap:10px; padding:9px 12px; border-radius:10px; color:rgba(255,255,255,.65); font-size:13px; font-weight:600; text-decoration:none; transition:background .18s; }
    .sidebar-foot a:hover { background:rgba(255,0,0,.2); color:#ff8080; }
    .main { margin-left:260px; flex:1; padding:28px; }
    .topbar { background:#fff; border-radius:16px; padding:16px 22px; margin-bottom:24px; display:flex; align-items:center; justify-content:space-between; box-shadow:0 2px 12px rgba(20,40,120,.07); border:1px solid #d0dcff; }
    .topbar h1 { font-family:'Sora',sans-serif; font-size:1.1rem; font-weight:900; color:#1a3a8f; }
    .card { background:#fff; border:1px solid #d0dcff; border-radius:20px; padding:26px; margin-bottom:24px; box-shadow:0 4px 16px rgba(20,40,120,.07); }
    .card-title { font-family:'Sora',sans-serif; font-size:1rem; font-weight:800; color:#0d1b3e; margin-bottom:20px; padding-bottom:14px; border-bottom:1px solid #e8eeff; }
    .form-row { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px; }
    .form-row.full { grid-template-columns:1fr; }
    .form-group label { display:block; font-size:12.5px; font-weight:700; color:#334168; margin-bottom:6px; letter-spacing:.04em; text-transform:uppercase; }
    .form-group input, .form-group select, .form-group textarea { width:100%; padding:11px 14px; border:1.5px solid #d0dcff; border-radius:10px; font-size:14px; font-family:inherit; color:#0d1b3e; outline:none; transition:border-color .2s; }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color:#2550c0; }
    .form-group textarea { resize:vertical; min-height:80px; }
    .btn-save { padding:11px 24px; background:#2550c0; color:#fff; border:none; border-radius:10px; font-size:14px; font-weight:800; font-family:inherit; cursor:pointer; transition:background .2s; }
    .btn-save:hover { background:#1a3a8f; }
    .btn-cancel { padding:11px 20px; background:#f4f7ff; color:#334168; border:1.5px solid #d0dcff; border-radius:10px; font-size:14px; font-weight:700; font-family:inherit; cursor:pointer; transition:background .2s; text-decoration:none; display:inline-flex; align-items:center; }
    .btn-edit { padding:6px 14px; background:#dbeafe; color:#1d4ed8; border:none; border-radius:8px; font-size:12px; font-weight:700; cursor:pointer; text-decoration:none; transition:background .2s; }
    .btn-hapus { padding:6px 14px; background:#fee2e2; color:#b91c1c; border:none; border-radius:8px; font-size:12px; font-weight:700; cursor:pointer; text-decoration:none; transition:background .2s; }
    .alert { padding:12px 16px; border-radius:10px; font-size:13.5px; font-weight:600; margin-bottom:18px; }
    .alert-success { background:#dcfce7; color:#15803d; border:1px solid #86efac; }
    table { width:100%; border-collapse:collapse; font-size:13.5px; }
    thead { background:#1a3a8f; color:#fff; }
    thead th { padding:12px 14px; text-align:left; font-size:12px; font-weight:700; letter-spacing:.05em; text-transform:uppercase; white-space:nowrap; }
    tbody tr { border-bottom:1px solid #eef1ff; transition:background .15s; }
    tbody tr:hover { background:#f6f9ff; }
    tbody td { padding:12px 14px; vertical-align:middle; color:#2d3f6e; }
    .badge { display:inline-flex; padding:4px 10px; border-radius:99px; font-size:11px; font-weight:700; }
    .badge-green { background:#dcfce7; color:#15803d; }
    .badge-yellow { background:#fef9c3; color:#854d0e; }
    .badge-red { background:#fee2e2; color:#b91c1c; }
    .table-wrap { overflow-x:auto; border-radius:14px; border:1px solid #d0dcff; }
    @media (max-width:860px) { .form-row { grid-template-columns:1fr; } .main { margin-left:0; } }
  </style>
</head>
<body>
<aside class="sidebar">
  <div class="sidebar-head">
    <img src="../assets/img/carik.png" alt="PAK CARIK">
    <h2>PAK CARIK Admin</h2>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-sec">Menu Utama</div>
    <a href="dashboard.php">📊 Dashboard</a>
    <div class="nav-sec">Kelola Layanan</div>
    <a href="undangan.php" class="active">📋 Jadwal Undangan</a>
    <a href="apel.php">👥 Jadwal Apel</a>
    <a href="permintaan-data.php">📊 Permintaan Data</a>
    <a href="ektp.php">🪪 E-KTP</a>
    <a href="sidang-waris.php">⚖️ Sidang Waris</a>
    <a href="pbb.php">🏠 PBB</a>
    <a href="kearsipan.php">📁 Kearsipan</a>
    <a href="pelayanan.php">✅ Pelayanan</a>
  </nav>
  <div class="sidebar-foot"><a href="logout.php">🚪 Keluar</a></div>
</aside>

<div class="main">
  <div class="topbar">
    <h1>📋 Jadwal Undangan</h1>
    <a href="dashboard.php" style="font-size:13px;color:#2550c0;font-weight:700;text-decoration:none;">← Dashboard</a>
  </div>

  <?= $msg ?>

  <!-- FORM TAMBAH / EDIT -->
  <div class="card">
    <div class="card-title"><?= $edit ? '✏️ Edit Data Undangan' : '➕ Tambah Jadwal Undangan' ?></div>
    <form method="POST" action="">
      <?php if ($edit): ?><input type="hidden" name="id" value="<?= $edit['id'] ?>"><?php endif; ?>
      <div class="form-row full">
        <div class="form-group">
          <label>Judul Kegiatan</label>
          <input type="text" name="judul" value="<?= htmlspecialchars($edit['judul'] ?? '') ?>" placeholder="Judul kegiatan..." required>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Tanggal</label>
          <input type="date" name="tanggal" value="<?= $edit['tanggal'] ?? '' ?>" required>
        </div>
        <div class="form-group">
          <label>Waktu</label>
          <input type="time" name="waktu" value="<?= $edit['waktu'] ?? '' ?>" required>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Lokasi</label>
          <input type="text" name="lokasi" value="<?= htmlspecialchars($edit['lokasi'] ?? '') ?>" placeholder="Lokasi kegiatan..." required>
        </div>
        <div class="form-group">
          <label>Penyelenggara</label>
          <input type="text" name="penyelenggara" value="<?= htmlspecialchars($edit['penyelenggara'] ?? '') ?>" placeholder="Nama penyelenggara..." required>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Status</label>
          <select name="status">
            <option value="aktif" <?= ($edit['status']??'')=='aktif'?'selected':'' ?>>Aktif</option>
            <option value="selesai" <?= ($edit['status']??'')=='selesai'?'selected':'' ?>>Selesai</option>
            <option value="batal" <?= ($edit['status']??'')=='batal'?'selected':'' ?>>Batal</option>
          </select>
        </div>
        <div class="form-group">
          <label>Keterangan</label>
          <input type="text" name="keterangan" value="<?= htmlspecialchars($edit['keterangan'] ?? '') ?>" placeholder="Keterangan tambahan...">
        </div>
      </div>
      <div style="display:flex;gap:10px;margin-top:6px;">
        <button type="submit" class="btn-save"><?= $edit ? '💾 Update' : '➕ Simpan' ?></button>
        <?php if ($edit): ?><a href="undangan.php" class="btn-cancel">Batal</a><?php endif; ?>
      </div>
    </form>
  </div>

  <!-- TABEL DATA -->
  <div class="card">
    <div class="card-title">📋 Daftar Jadwal Undangan</div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr><th>No</th><th>Judul Kegiatan</th><th>Tanggal</th><th>Waktu</th><th>Lokasi</th><th>Penyelenggara</th><th>Keterangan</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
          <?php $no=1; while ($row = $data->fetch_assoc()): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><strong><?= htmlspecialchars($row['judul']) ?></strong></td>
            <td><?= tglIndo($row['tanggal']) ?></td>
            <td><?= substr($row['waktu'],0,5) ?> WIB</td>
            <td><?= htmlspecialchars($row['lokasi']) ?></td>
            <td><?= htmlspecialchars($row['penyelenggara']) ?></td>
            <td><?= htmlspecialchars($row['keterangan'] ?: '-') ?></td>
            <td><?= badgeStatus($row['status']) ?></td>
            <td style="white-space:nowrap;">
              <a href="?edit=<?= $row['id'] ?>" class="btn-edit">✏️ Edit</a>
              <a href="?hapus=<?= $row['id'] ?>" class="btn-hapus" onclick="return confirm('Yakin hapus data ini?')">🗑️ Hapus</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
