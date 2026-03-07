<?php
error_reporting(0);
session_start();
include 'config.php';

header('Content-Type: application/json');

if(!isset($_SESSION['admin_embun'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$current_role  = isset($_SESSION['admin_role']) ? $_SESSION['admin_role'] : 'Super Admin';
$my_id = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : 1;

$adminFilterStatAnd = ($current_role != 'Super Admin') ? "AND admin_id='$my_id'" : "";

// Count pending orders
$statusFilterNotif = ($current_role == 'Super Admin') ? "(status_pembayaran='Menunggu Konfirmasi' OR status_pengerjaan='Menunggu Verifikasi')" : "status_pembayaran='Menunggu Konfirmasi'";
$q_pesanan = mysqli_query($conn, "SELECT COUNT(id) as jml FROM pesanan WHERE $statusFilterNotif $adminFilterStatAnd");
$jml_menunggu = 0;
if($q_pesanan) {
    $row = mysqli_fetch_assoc($q_pesanan);
    $jml_menunggu = (int)$row['jml'];
}

// Count custom requests (assuming only Super Admin cares about these, but we can just query it globally)
$q_request = mysqli_query($conn, "SELECT COUNT(id) as jml FROM request_custom WHERE status_request='Menunggu Review'");
$jml_request = 0;
if($q_request) {
    $row = mysqli_fetch_assoc($q_request);
    $jml_request = (int)$row['jml'];
}

// Total notifications
$total_notif = $jml_menunggu + $jml_request;

echo json_encode([
    'status' => 'success',
    'total_notif' => $total_notif,
    'detail' => [
        'menunggu_konfirmasi' => $jml_menunggu,
        'request_baru' => $jml_request
    ]
]);
?>
