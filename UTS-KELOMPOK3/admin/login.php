<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin — PAK CARIK Gandusari</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Sora:wght@800;900&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --primary: #1a3a8f;
      --primary-mid: #2550c0;
      --accent: #f9b234;
      --text: #0d1b3e;
      --surface: #ffffff;
      --border: #d0dcff;
      --shadow: 0 20px 60px rgba(20,40,120,.18);
    }
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #0b1e5c 0%, #1a3a8f 50%, #0d4d8a 100%);
      padding: 20px;
    }
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background: repeating-linear-gradient(45deg, rgba(255,255,255,.02) 0, rgba(255,255,255,.02) 1px, transparent 1px, transparent 40px);
      pointer-events: none;
    }
    .login-card {
      background: #fff;
      border-radius: 28px;
      padding: 48px 44px;
      width: 100%;
      max-width: 440px;
      box-shadow: var(--shadow);
      position: relative;
    }
    .login-logo {
      text-align: center;
      margin-bottom: 32px;
    }
    .login-logo img {
      width: 80px;
      margin: 0 auto 14px;
    }
    .login-logo h1 {
      font-family: 'Sora', sans-serif;
      font-size: 1.5rem;
      font-weight: 900;
      color: var(--primary);
      margin-bottom: 6px;
    }
    .login-logo p {
      font-size: 13px;
      color: #64748b;
    }
    .form-group {
      margin-bottom: 20px;
    }
    .form-group label {
      display: block;
      font-size: 13px;
      font-weight: 700;
      color: var(--text);
      margin-bottom: 8px;
      letter-spacing: .04em;
      text-transform: uppercase;
    }
    .form-group input {
      width: 100%;
      padding: 13px 16px;
      border: 1.5px solid var(--border);
      border-radius: 12px;
      font-size: 15px;
      font-family: inherit;
      color: var(--text);
      outline: none;
      transition: border-color .2s, box-shadow .2s;
    }
    .form-group input:focus {
      border-color: var(--primary-mid);
      box-shadow: 0 0 0 4px rgba(37,80,192,.1);
    }
    .btn-login {
      width: 100%;
      padding: 15px;
      background: var(--primary-mid);
      color: #fff;
      border: none;
      border-radius: 12px;
      font-size: 15px;
      font-weight: 800;
      font-family: inherit;
      cursor: pointer;
      transition: background .2s, transform .2s;
      margin-top: 8px;
    }
    .btn-login:hover { background: var(--primary); transform: translateY(-1px); }
    .alert {
      padding: 12px 16px;
      border-radius: 10px;
      font-size: 13.5px;
      font-weight: 600;
      margin-bottom: 20px;
    }
    .alert-danger { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }
    .back-link {
      display: block;
      text-align: center;
      margin-top: 22px;
      color: #64748b;
      font-size: 13.5px;
      font-weight: 600;
    }
    .back-link a { color: var(--primary-mid); }
    .back-link a:hover { text-decoration: underline; }
  </style>
</head>
<body>

<div class="login-card">
  <div class="login-logo">
    <img src="../assets/img/carik.png" alt="PAK CARIK">
    <h1>Panel Admin</h1>
    <p>PAK CARIK — Kecamatan Gandusari</p>
  </div>

  <?php
  session_start();
  require_once '../assets/php/koneksi.php';

  $error = '';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = esc($conn, $_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
      $stmt = $conn->prepare("SELECT id, nama, password, level FROM tbl_admin WHERE username = ? LIMIT 1");
      $stmt->bind_param('s', $username);
      $stmt->execute();
      $result = $stmt->get_result();
      $admin = $result->fetch_assoc();

      if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id']    = $admin['id'];
        $_SESSION['admin_nama']  = $admin['nama'];
        $_SESSION['admin_level'] = $admin['level'];
        header('Location: dashboard.php');
        exit;
      } else {
        $error = 'Username atau password salah!';
      }
    } else {
      $error = 'Username dan password wajib diisi!';
    }
  }

  if ($error): ?>
    <div class="alert alert-danger">⚠️ <?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <div class="form-group">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" placeholder="Masukkan username" required autocomplete="username">
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="Masukkan password" required autocomplete="current-password">
    </div>
    <button type="submit" class="btn-login">🔐 Masuk ke Panel Admin</button>
  </form>

  <p class="back-link"><a href="../index.html">← Kembali ke halaman utama</a></p>
</div>

</body>
</html>
