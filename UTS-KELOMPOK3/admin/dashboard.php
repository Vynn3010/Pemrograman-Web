<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
require_once '../assets/php/koneksi.php';

// Hitung total tiap layanan
function countTable($conn, $table) {
    $r = $conn->query("SELECT COUNT(*) as total FROM `$table`");
    return $r ? $r->fetch_assoc()['total'] : 0;
}

$totals = [
    'undangan'        => countTable($conn, 'tbl_undangan'),
    'apel'            => countTable($conn, 'tbl_apel'),
    'permintaan_data' => countTable($conn, 'tbl_permintaan_data'),
    'ektp'            => countTable($conn, 'tbl_ektp'),
    'sidang_waris'    => countTable($conn, 'tbl_sidang_waris'),
    'pbb'             => countTable($conn, 'tbl_pbb'),
    'arsip'           => countTable($conn, 'tbl_arsip'),
    'pelayanan'       => countTable($conn, 'tbl_pelayanan'),
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin — PAK CARIK</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Sora:wght@800;900&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --primary: #1a3a8f; --primary-mid: #2550c0;
      --accent: #f9b234; --text: #0d1b3e;
      --text-soft: #5a6a8e; --border: #d0dcff;
      --surface: #f4f7ff; --white: #ffffff;
      --sidebar-w: 260px;
    }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--surface); color: var(--text); display: flex; min-height: 100vh; }

    /* SIDEBAR */
    .sidebar {
      width: var(--sidebar-w); background: linear-gradient(175deg,#0c1e5a,#081340);
      color: #fff; position: fixed; top: 0; left: 0; bottom: 0;
      display: flex; flex-direction: column; z-index: 100;
    }
    .sidebar-head { padding: 28px 22px; border-bottom: 1px solid rgba(255,255,255,.1); }
    .sidebar-head img { width: 52px; margin-bottom: 10px; }
    .sidebar-head h2 { font-family: 'Sora',sans-serif; font-size: 1rem; font-weight: 900; color: var(--accent); }
    .sidebar-head p { font-size: 11px; opacity: .65; margin-top: 2px; }
    .sidebar-nav { flex: 1; padding: 16px 12px; overflow-y: auto; }
    .nav-section { font-size: 10px; font-weight: 800; letter-spacing: .18em; text-transform: uppercase; color: rgba(255,255,255,.4); padding: 14px 10px 8px; }
    .sidebar-nav a {
      display: flex; align-items: center; gap: 10px;
      padding: 10px 12px; border-radius: 10px;
      color: rgba(255,255,255,.78); font-size: 13.5px; font-weight: 600;
      margin-bottom: 2px; transition: background .18s, color .18s;
      text-decoration: none;
    }
    .sidebar-nav a:hover, .sidebar-nav a.active { background: rgba(255,255,255,.14); color: #fff; }
    .sidebar-nav a .icon { font-size: 16px; width: 22px; text-align: center; }
    .sidebar-foot { padding: 16px 12px; border-top: 1px solid rgba(255,255,255,.1); }
    .sidebar-foot a { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 10px; color: rgba(255,255,255,.65); font-size: 13px; font-weight: 600; text-decoration: none; transition: background .18s; }
    .sidebar-foot a:hover { background: rgba(255,0,0,.2); color: #ff8080; }

    /* MAIN */
    .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; }
    .topbar {
      background: var(--white); border-bottom: 1px solid var(--border);
      padding: 14px 28px; display: flex; align-items: center; justify-content: space-between;
      box-shadow: 0 2px 8px rgba(20,40,120,.06); position: sticky; top: 0; z-index: 50;
    }
    .topbar h1 { font-family: 'Sora',sans-serif; font-size: 1.15rem; font-weight: 900; color: var(--primary); }
    .topbar-user { display: flex; align-items: center; gap: 10px; font-size: 13.5px; font-weight: 700; }
    .topbar-user .avatar { width: 36px; height: 36px; background: var(--primary-mid); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 800; }

    .content { padding: 28px; flex: 1; }

    /* STAT CARDS */
    .stats-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 18px; margin-bottom: 28px; }
    .stat-card { background: var(--white); border: 1px solid var(--border); border-radius: 18px; padding: 20px 22px; box-shadow: 0 4px 16px rgba(20,40,120,.07); }
    .stat-card .icon { font-size: 1.8rem; margin-bottom: 10px; }
    .stat-card .num { font-family: 'Sora',sans-serif; font-size: 2rem; font-weight: 900; color: var(--primary-mid); line-height: 1; }
    .stat-card .lbl { font-size: 13px; color: var(--text-soft); margin-top: 6px; font-weight: 600; }
    .stat-card .link { display: inline-block; margin-top: 10px; font-size: 12px; color: var(--primary-mid); font-weight: 700; text-decoration: none; }
    .stat-card .link:hover { text-decoration: underline; }

    /* MENU GRID */
    .menu-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; }
    .menu-card {
      background: var(--white); border: 1px solid var(--border); border-radius: 18px;
      padding: 22px; text-align: center; text-decoration: none; color: var(--text);
      box-shadow: 0 4px 16px rgba(20,40,120,.06); transition: transform .2s, box-shadow .2s, border-color .2s;
    }
    .menu-card:hover { transform: translateY(-5px); box-shadow: 0 14px 36px rgba(20,40,120,.14); border-color: rgba(37,80,192,.3); }
    .menu-card .icon { font-size: 2.2rem; margin-bottom: 10px; }
    .menu-card h3 { font-size: 14px; font-weight: 800; margin-bottom: 6px; }
    .menu-card span { font-size: 12px; color: var(--text-soft); }

    .section-title { font-family: 'Sora',sans-serif; font-size: 1.2rem; font-weight: 900; color: var(--text); margin-bottom: 18px; }

    @media (max-width: 1100px) { .stats-grid, .menu-grid { grid-template-columns: repeat(2,1fr); } }
    @media (max-width: 768px) {
      .sidebar { transform: translateX(-100%); }
      .main { margin-left: 0; }
    }
  </style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
  <div class="sidebar-head">
    <img src="../assets/img/carik.png" alt="PAK CARIK">
    <h2>PAK CARIK Admin</h2>
    <p>Kecamatan Gandusari</p>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-section">Menu Utama</div>
    <a href="dashboard.php" class="active"><span class="icon">📊</span> Dashboard</a>
    <div class="nav-section">Kelola Layanan</div>
    <a href="undangan.php"><span class="icon">📋</span> Jadwal Undangan</a>
    <a href="apel.php"><span class="icon">👥</span> Jadwal Apel</a>
    <a href="permintaan-data.php"><span class="icon">📊</span> Permintaan Data</a>
    <a href="ektp.php"><span class="icon">🪪</span> E-KTP</a>
    <a href="sidang-waris.php"><span class="icon">⚖️</span> Sidang Waris</a>
    <a href="pbb.php"><span class="icon">🏠</span> PBB</a>
    <a href="kearsipan.php"><span class="icon">📁</span> Kearsipan</a>
    <a href="pelayanan.php"><span class="icon">✅</span> Pelayanan</a>
    <div class="nav-section">Pengaturan</div>
    <a href="admin-list.php"><span class="icon">👤</span> Kelola Admin</a>
  </nav>
  <div class="sidebar-foot">
    <a href="logout.php">🚪 Keluar</a>
  </div>
</aside>

<!-- MAIN -->
<div class="main">
  <div class="topbar">
    <h1>📊 Dashboard</h1>
    <div class="topbar-user">
      <div class="avatar"><?= strtoupper(substr($_SESSION['admin_nama'],0,1)) ?></div>
      <span><?= htmlspecialchars($_SESSION['admin_nama']) ?></span>
    </div>
  </div>

  <div class="content">
    <h2 class="section-title">Ringkasan Data Layanan</h2>
    <div class="stats-grid">
      <div class="stat-card"><div class="icon">📋</div><div class="num"><?= $totals['undangan'] ?></div><div class="lbl">Jadwal Undangan</div><a class="link" href="undangan.php">Kelola →</a></div>
      <div class="stat-card"><div class="icon">👥</div><div class="num"><?= $totals['apel'] ?></div><div class="lbl">Jadwal Apel</div><a class="link" href="apel.php">Kelola →</a></div>
      <div class="stat-card"><div class="icon">📊</div><div class="num"><?= $totals['permintaan_data'] ?></div><div class="lbl">Permintaan Data</div><a class="link" href="permintaan-data.php">Kelola →</a></div>
      <div class="stat-card"><div class="icon">🪪</div><div class="num"><?= $totals['ektp'] ?></div><div class="lbl">Data E-KTP</div><a class="link" href="ektp.php">Kelola →</a></div>
      <div class="stat-card"><div class="icon">⚖️</div><div class="num"><?= $totals['sidang_waris'] ?></div><div class="lbl">Sidang Waris</div><a class="link" href="sidang-waris.php">Kelola →</a></div>
      <div class="stat-card"><div class="icon">🏠</div><div class="num"><?= $totals['pbb'] ?></div><div class="lbl">Data PBB</div><a class="link" href="pbb.php">Kelola →</a></div>
      <div class="stat-card"><div class="icon">📁</div><div class="num"><?= $totals['arsip'] ?></div><div class="lbl">Data Arsip</div><a class="link" href="kearsipan.php">Kelola →</a></div>
      <div class="stat-card"><div class="icon">✅</div><div class="num"><?= $totals['pelayanan'] ?></div><div class="lbl">Data Pelayanan</div><a class="link" href="pelayanan.php">Kelola →</a></div>
    </div>

    <h2 class="section-title">Menu Kelola Layanan</h2>
    <div class="menu-grid">
      <a class="menu-card" href="undangan.php"><div class="icon">📋</div><h3>Jadwal Undangan</h3><span>Tambah, edit & hapus data undangan</span></a>
      <a class="menu-card" href="apel.php"><div class="icon">👥</div><h3>Jadwal Apel</h3><span>Kelola jadwal apel kecamatan</span></a>
      <a class="menu-card" href="permintaan-data.php"><div class="icon">📊</div><h3>Permintaan Data</h3><span>Kelola jadwal permintaan data</span></a>
      <a class="menu-card" href="ektp.php"><div class="icon">🪪</div><h3>E-KTP</h3><span>Kelola data pengajuan E-KTP</span></a>
      <a class="menu-card" href="sidang-waris.php"><div class="icon">⚖️</div><h3>Sidang Waris</h3><span>Kelola jadwal sidang waris</span></a>
      <a class="menu-card" href="pbb.php"><div class="icon">🏠</div><h3>PBB</h3><span>Kelola data Pajak Bumi & Bangunan</span></a>
      <a class="menu-card" href="kearsipan.php"><div class="icon">📁</div><h3>Kearsipan</h3><span>Kelola dokumen arsip</span></a>
      <a class="menu-card" href="pelayanan.php"><div class="icon">✅</div><h3>Pelayanan</h3><span>Kelola perwalian & legalisasi</span></a>
    </div>
  </div>
</div>

</body>
</html>
