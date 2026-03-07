<?php
error_reporting(0);
include '../config.php';

$results = [];

// Tambah kolom email_pemesan
$cek = mysqli_query($conn, "SHOW COLUMNS FROM pesanan LIKE 'email_pemesan'");
if(mysqli_num_rows($cek) == 0) {
    $r = mysqli_query($conn, "ALTER TABLE pesanan ADD COLUMN email_pemesan VARCHAR(255) NULL DEFAULT NULL AFTER nama_pemesan");
    $results[] = $r ? "✅ Kolom email_pemesan ditambahkan." : "❌ " . mysqli_error($conn);
} else {
    $results[] = "✅ Kolom email_pemesan sudah ada.";
}

foreach($results as $msg) echo $msg . "<br>";
?>
