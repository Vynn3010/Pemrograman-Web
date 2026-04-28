<?php
/**
 * PAK CARIK — Koneksi Database
 * Kecamatan Gandusari, Kabupaten Blitar
 * 
 * Konfigurasi untuk Laragon (MySQL)
 * Akses via: localhost/pakcarik
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');          // Laragon default: kosong
define('DB_NAME', 'db_pakcarik');
define('DB_CHARSET', 'utf8mb4');

// Buat koneksi
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Cek koneksi
if ($conn->connect_error) {
    die(json_encode([
        'status'  => 'error',
        'message' => 'Koneksi database gagal: ' . $conn->connect_error
    ]));
}

// Set charset
$conn->set_charset(DB_CHARSET);

// Timezone Indonesia WIB
date_default_timezone_set('Asia/Jakarta');

/**
 * Helper: Escape string untuk query aman
 */
function esc($conn, $str) {
    return $conn->real_escape_string(trim($str));
}

/**
 * Helper: Format tanggal Indonesia
 */
function tglIndo($tgl) {
    if (!$tgl || $tgl == '0000-00-00') return '-';
    $bulan = ['', 'Januari','Februari','Maret','April','Mei','Juni',
              'Juli','Agustus','September','Oktober','November','Desember'];
    $t = explode('-', $tgl);
    return (int)$t[2] . ' ' . $bulan[(int)$t[1]] . ' ' . $t[0];
}

/**
 * Helper: Badge status HTML
 */
function badgeStatus($status) {
    $map = [
        'aktif'     => ['green',  'Aktif'],
        'selesai'   => ['blue',   'Selesai'],
        'proses'    => ['yellow', 'Proses'],
        'batal'     => ['red',    'Batal'],
        'lunas'     => ['green',  'Lunas'],
        'belum'     => ['yellow', 'Belum Lunas'],
        'pending'   => ['yellow', 'Pending'],
    ];
    $s = strtolower($status);
    $cls = $map[$s][0] ?? 'gray';
    $lbl = $map[$s][1] ?? ucfirst($status);
    return "<span class=\"badge badge-{$cls}\">{$lbl}</span>";
}
