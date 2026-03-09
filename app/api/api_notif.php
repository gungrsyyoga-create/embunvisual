<?php
error_reporting(0);
session_start();
include 'config.php';

header('Content-Type: application/json');

if(!isset($_SESSION['admin_embun'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$current_role = isset($_SESSION['admin_role']) ? $_SESSION['admin_role'] : 'Super Admin';
$my_id        = isset($_SESSION['admin_id'])   ? $_SESSION['admin_id']   : 1;
$adminAnd     = ($current_role != 'Super Admin') ? "AND admin_id='$my_id'" : "";

// ── Badge Pesanan: HANYA "Menunggu Konfirmasi" (bayar belum dikonfirmasi)
$q_bayar = mysqli_query($conn, "SELECT COUNT(id) as jml FROM pesanan WHERE status_pembayaran='Menunggu Konfirmasi' $adminAnd");
$jml_menunggu_bayar = ($q_bayar) ? (int)mysqli_fetch_assoc($q_bayar)['jml'] : 0;

// ── Badge Tugas: "Menunggu Verifikasi" (pekerjaan menunggu dicek Super Admin)
if($current_role == 'Super Admin') {
    $q_verif = mysqli_query($conn, "SELECT COUNT(id) as jml FROM pesanan WHERE status_pengerjaan='Menunggu Verifikasi'");
    $jml_menunggu_verif = ($q_verif) ? (int)mysqli_fetch_assoc($q_verif)['jml'] : 0;
} else {
    $jml_menunggu_verif = 0;
}

// ── Request Custom baru
$q_request = mysqli_query($conn, "SELECT COUNT(id) as jml FROM request_custom WHERE status_request='Menunggu Review'");
$jml_request = ($q_request) ? (int)mysqli_fetch_assoc($q_request)['jml'] : 0;

// Total: hanya bayar+request untuk JS toast (tugas tidak trigger toast, sudah ada badge sendiri)
$total_notif = $jml_menunggu_bayar + $jml_request;

echo json_encode([
    'status'      => 'success',
    'total_notif' => $total_notif,
    'detail'      => [
        'menunggu_konfirmasi' => $jml_menunggu_bayar,
        'menunggu_verifikasi' => $jml_menunggu_verif,
        'request_baru'        => $jml_request,
    ]
]);
?>
