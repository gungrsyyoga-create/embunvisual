<?php
/**
 * tools/index.php
 * ========================
 * Panel Tools Database & Utilitas Embun Visual
 * Hanya bisa diakses dari localhost atau oleh developer
 */

// ── Security: Hanya dari localhost
error_reporting(0);
$allowed_ips = ['127.0.0.1', '::1'];
if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_ips)) {
    http_response_code(403);
    die('<h2 style="color:red;font-family:sans-serif;">403 Forbidden — Tools hanya bisa diakses dari localhost.</h2>');
}

include '../config.php';

$action = $_GET['do'] ?? '';
$result = '';

// ── AKSI
if ($action === 'check_db') {
    // Cek semua tabel yang ada
    $tables = [];
    $r = mysqli_query($conn, "SHOW TABLES");
    while ($row = mysqli_fetch_row($r)) { $tables[] = $row[0]; }
    $result = '<b>Tabel ditemukan:</b> ' . implode(', ', $tables);
}

if ($action === 'fix_enum') {
    $sql = "ALTER TABLE pesanan MODIFY COLUMN status_pengerjaan 
            ENUM('Pending','Proses Desain','Sedang Dikerjakan','Menunggu Verifikasi','Perlu Revisi','Selesai') 
            NOT NULL DEFAULT 'Pending'";
    if (mysqli_query($conn, $sql)) {
        $result = '✅ ENUM status_pengerjaan berhasil diperbarui.<br>';
        mysqli_query($conn, "UPDATE pesanan SET status_pengerjaan='Sedang Dikerjakan' WHERE status_pengerjaan='Proses Desain'");
        $result .= '✅ Data lama dimigrasi (' . mysqli_affected_rows($conn) . ' baris).';
    } else {
        $result = '❌ ' . mysqli_error($conn);
    }
}

if ($action === 'add_col_revisi') {
    $cek = mysqli_query($conn, "SHOW COLUMNS FROM pesanan LIKE 'catatan_revisi'");
    if (mysqli_num_rows($cek) == 0) {
        $r = mysqli_query($conn, "ALTER TABLE pesanan ADD COLUMN catatan_revisi TEXT NULL DEFAULT NULL AFTER status_pengerjaan");
        $result = $r ? '✅ Kolom catatan_revisi berhasil ditambahkan.' : '❌ Gagal: ' . mysqli_error($conn);
    } else {
        $result = '✅ Kolom catatan_revisi sudah ada.';
    }
}

if ($action === 'cek_pesanan') {
    $r = mysqli_query($conn, "SHOW COLUMNS FROM pesanan LIKE 'status_pengerjaan'");
    $col = mysqli_fetch_assoc($r);
    $result = '<b>Type:</b> ' . $col['Type'] . '<br><b>Default:</b> ' . $col['Default'];
    $r2 = mysqli_query($conn, "SELECT DISTINCT status_pengerjaan, COUNT(*) as jml FROM pesanan GROUP BY status_pengerjaan");
    $result .= '<br><br><b>Status dalam DB:</b><br>';
    while ($row = mysqli_fetch_assoc($r2)) {
        $result .= '- ' . ($row['status_pengerjaan'] ?: '-') . ' : ' . $row['jml'] . ' data<br>';
    }
}

?><!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>🛠️ Tools | Embun Visual</title>
<style>
  body { font-family: 'Segoe UI', sans-serif; background: #f1f5f9; margin: 0; padding: 30px; color: #1e293b; }
  h1 { color: #3f5343; margin-bottom: 5px; }
  .subtitle { color: #64748b; margin-bottom: 30px; font-size: 0.9rem; }
  .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 15px; margin-bottom: 30px; }
  .card { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; }
  .card h3 { margin: 0 0 8px; font-size: 1rem; }
  .card p { margin: 0 0 15px; font-size: 0.82rem; color: #64748b; }
  .btn { display: inline-block; padding: 8px 16px; border-radius: 8px; background: #3f5343; color: white; text-decoration: none; font-size: 0.85rem; }
  .btn:hover { background: #2d3d31; }
  .result { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); border-left: 4px solid #22c55e; }
  .result h3 { margin-top: 0; color: #166534; }
  .badge { background: #fee2e2; color: #991b1b; padding: 2px 8px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; }
</style>
</head>
<body>
<h1>🛠️ Embun Visual — Tools Panel</h1>
<p class="subtitle">Utilitas database dan pengembangan. <span class="badge">LOCALHOST ONLY</span></p>

<div class="grid">
  <div class="card">
    <h3>📋 Cek Tabel DB</h3>
    <p>Tampilkan semua tabel yang ada di database.</p>
    <a href="?do=check_db" class="btn">Jalankan</a>
  </div>
  <div class="card">
    <h3>🔧 Fix ENUM Pesanan</h3>
    <p>Perbaiki ENUM status_pengerjaan agar mencakup Perlu Revisi & Sedang Dikerjakan.</p>
    <a href="?do=fix_enum" class="btn">Jalankan</a>
  </div>
  <div class="card">
    <h3>➕ Tambah Kolom Revisi</h3>
    <p>Tambah kolom catatan_revisi ke tabel pesanan jika belum ada.</p>
    <a href="?do=add_col_revisi" class="btn">Jalankan</a>
  </div>
  <div class="card">
    <h3>🔍 Cek Kolom Pesanan</h3>
    <p>Lihat tipe dan isi kolom status_pengerjaan dari tabel pesanan.</p>
    <a href="?do=cek_pesanan" class="btn">Jalankan</a>
  </div>
</div>

<?php if ($result): ?>
<div class="result">
  <h3>📤 Hasil:</h3>
  <?= $result ?>
</div>
<?php endif; ?>

</body>
</html>
