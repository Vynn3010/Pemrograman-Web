<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
require_once '../assets/php/koneksi.php';
$data = $conn->query("SELECT * FROM tbl_sidang_waris ORDER BY id DESC");
$page_title = "Sidang Waris";
$page_icon  = "⚖️";
?><!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title><?= $page_title ?> — Admin PAK CARIK</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Sora:wght@800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    body{background:#f4f7ff;display:flex;min-height:100vh;font-family:'Plus Jakarta Sans',sans-serif;}
    .sidebar{width:260px;background:linear-gradient(175deg,#0c1e5a,#081340);color:#fff;position:fixed;top:0;left:0;bottom:0;display:flex;flex-direction:column;z-index:100;}
    .sidebar-head{padding:24px 20px;border-bottom:1px solid rgba(255,255,255,.1);}
    .sidebar-head img{width:48px;margin-bottom:8px;}
    .sidebar-head h2{font-family:'Sora',sans-serif;font-size:.95rem;font-weight:900;color:#f9b234;}
    .sidebar-nav{flex:1;padding:14px 10px;overflow-y:auto;}
    .nav-sec{font-size:10px;font-weight:800;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.4);padding:12px 10px 6px;}
    .sidebar-nav a{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:10px;color:rgba(255,255,255,.78);font-size:13px;font-weight:600;margin-bottom:2px;transition:background .18s;text-decoration:none;}
    .sidebar-nav a:hover,.sidebar-nav a.active{background:rgba(255,255,255,.14);color:#fff;}
    .sidebar-foot{padding:14px 10px;border-top:1px solid rgba(255,255,255,.1);}
    .sidebar-foot a{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:10px;color:rgba(255,255,255,.65);font-size:13px;font-weight:600;text-decoration:none;}
    .sidebar-foot a:hover{background:rgba(255,0,0,.2);color:#ff8080;}
    .main{margin-left:260px;flex:1;padding:28px;}
    .topbar{background:#fff;border-radius:16px;padding:16px 22px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;box-shadow:0 2px 12px rgba(20,40,120,.07);border:1px solid #d0dcff;}
    .topbar h1{font-family:'Sora',sans-serif;font-size:1.1rem;font-weight:900;color:#1a3a8f;}
    .card{background:#fff;border:1px solid #d0dcff;border-radius:20px;padding:26px;margin-bottom:24px;box-shadow:0 4px 16px rgba(20,40,120,.07);}
    .card-title{font-family:'Sora',sans-serif;font-size:1rem;font-weight:800;color:#0d1b3e;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid #e8eeff;}
    .info-box{background:#f0f5ff;border:1px solid #d0dcff;border-radius:12px;padding:16px 20px;font-size:14px;color:#334168;line-height:1.75;}
    table{width:100%;border-collapse:collapse;font-size:13px;}
    thead{background:#1a3a8f;color:#fff;}
    thead th{padding:11px 14px;text-align:left;font-size:11.5px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;white-space:nowrap;}
    tbody tr{border-bottom:1px solid #eef1ff;transition:background .15s;}
    tbody tr:hover{background:#f6f9ff;}
    tbody td{padding:11px 14px;vertical-align:middle;color:#2d3f6e;}
    .table-wrap{overflow-x:auto;border-radius:14px;border:1px solid #d0dcff;}
  </style>
</head>
<body>
<aside class="sidebar">
  <div class="sidebar-head"><img src="../assets/img/carik.png" alt="PAK CARIK"><h2>PAK CARIK Admin</h2></div>
  <nav class="sidebar-nav">
    <div class="nav-sec">Menu Utama</div>
    <a href="dashboard.php">📊 Dashboard</a>
    <div class="nav-sec">Kelola Layanan</div>
    <a href="undangan.php">📋 Jadwal Undangan</a>
    <a href="apel.php" <?php if(basename(__FILE__)=='apel.php') echo 'class="active"'; ?>>👥 Jadwal Apel</a>
    <a href="permintaan-data.php" <?php if(basename(__FILE__)=='permintaan-data.php') echo 'class="active"'; ?>>📊 Permintaan Data</a>
    <a href="ektp.php" <?php if(basename(__FILE__)=='ektp.php') echo 'class="active"'; ?>>🪪 E-KTP</a>
    <a href="sidang-waris.php" <?php if(basename(__FILE__)=='sidang-waris.php') echo 'class="active"'; ?>>⚖️ Sidang Waris</a>
    <a href="pbb.php" <?php if(basename(__FILE__)=='pbb.php') echo 'class="active"'; ?>>🏠 PBB</a>
    <a href="kearsipan.php" <?php if(basename(__FILE__)=='kearsipan.php') echo 'class="active"'; ?>>📁 Kearsipan</a>
    <a href="pelayanan.php" <?php if(basename(__FILE__)=='pelayanan.php') echo 'class="active"'; ?>>✅ Pelayanan</a>
  </nav>
  <div class="sidebar-foot"><a href="logout.php">🚪 Keluar</a></div>
</aside>
<div class="main">
  <div class="topbar">
    <h1><?= $page_icon ?> <?= $page_title ?></h1>
    <a href="dashboard.php" style="font-size:13px;color:#2550c0;font-weight:700;text-decoration:none;">← Dashboard</a>
  </div>
  <div class="card">
    <div class="card-title">ℹ️ Tentang Halaman Ini</div>
    <div class="info-box">
      Halaman CRUD untuk <strong><?= $page_title ?></strong> mengikuti pola yang sama dengan <a href="undangan.php" style="color:#2550c0;font-weight:700;">Jadwal Undangan</a> yang sudah lengkap. Data dari tabel <code><strong><?= "tbl_sidang_waris" ?></strong></code> sudah siap di bawah. Silakan kembangkan form tambah/edit sesuai kebutuhan dengan referensi dari <code>undangan.php</code>.
    </div>
  </div>
  <div class="card">
    <div class="card-title"><?= $page_icon ?> Data <?= $page_title ?></div>
    <div class="table-wrap">
      <table>
        <thead><tr><?php
          if ($data && $data->num_rows > 0) {
            $first = $data->fetch_assoc();
            echo '<th>No</th>';
            foreach (array_keys($first) as $col) echo '<th>'.strtoupper(str_replace('_',' ',$col)).'</th>';
            echo '<th>AKSI</th>';
            $data->data_seek(0);
          }
        ?></tr></thead>
        <tbody><?php
          if ($data && $data->num_rows > 0) {
            $no = 1;
            while ($row = $data->fetch_assoc()) {
              echo '<tr><td>'.$no++.'</td>';
              foreach ($row as $val) echo '<td>'.htmlspecialchars($val ?: '-').'</td>';
              echo '<td><span style="font-size:12px;color:#94a3b8;font-style:italic;">Segera hadir</span></td></tr>';
            }
          } else {
            echo '<tr><td colspan="20" style="text-align:center;padding:30px;color:#64748b;">Belum ada data.</td></tr>';
          }
        ?></tbody>
      </table>
    </div>
  </div>
</div>
</body></html>
