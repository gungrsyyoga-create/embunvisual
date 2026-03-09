<?php
include "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pesanan_id = (int)$_POST['pesanan_id'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $kehadiran = mysqli_real_escape_string($conn, $_POST['kehadiran']);
    $ucapan = mysqli_real_escape_string($conn, $_POST['ucapan']);
    
    if ($pesanan_id > 0 && !empty($nama)) {
        $q = "INSERT INTO tamu_undangan (pesanan_id, nama_tamu, status_konfirmasi, ucapan) 
              VALUES ('$pesanan_id', '$nama', '$kehadiran', '$ucapan')";
        
        if (mysqli_query($conn, $q)) {
            echo json_encode(['status' => 'ok', 'message' => 'RSVP berhasil terkirim!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan ke database.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap.']);
    }
}
?>
