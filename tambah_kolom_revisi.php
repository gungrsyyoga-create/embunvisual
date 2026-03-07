<?php
error_reporting(0);
include 'config.php';

// Tambah kolom catatan_revisi ke tabel pesanan jika belum ada
$cek = mysqli_query($conn, "SHOW COLUMNS FROM pesanan LIKE 'catatan_revisi'");
if(mysqli_num_rows($cek) == 0) {
    $r = mysqli_query($conn, "ALTER TABLE pesanan ADD COLUMN catatan_revisi TEXT NULL DEFAULT NULL AFTER status_pengerjaan");
    echo $r ? "✅ Kolom catatan_revisi berhasil ditambahkan." : "❌ Gagal: " . mysqli_error($conn);
} else {
    echo "✅ Kolom catatan_revisi sudah ada.";
}
?>
