<?php
include 'config.php';

// Revert status_pembayaran rules
$q1 = "ALTER TABLE pesanan MODIFY status_pembayaran enum('Belum Bayar','Menunggu Konfirmasi','Lunas') DEFAULT 'Belum Bayar'";
if(mysqli_query($conn, $q1)){
    echo "SUCCESS: status_pembayaran reverted.\n";
} else {
    echo "ERROR: " . mysqli_error($conn) . "\n";
}

// Add 'Menunggu Verifikasi' to status_pengerjaan
$q2 = "ALTER TABLE pesanan MODIFY status_pengerjaan enum('Pending','Proses Desain','Menunggu Verifikasi','Selesai') DEFAULT 'Pending'";
if(mysqli_query($conn, $q2)){
    echo "SUCCESS: status_pengerjaan updated.\n";
} else {
    echo "ERROR: " . mysqli_error($conn) . "\n";
}
?>
