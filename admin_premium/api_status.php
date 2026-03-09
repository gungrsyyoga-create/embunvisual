<?php
// admin_premium/api_status.php - Real-time status polling for client dashboard
require_once __DIR__ . '/../config/bootstrap.php';

header('Content-Type: application/json');

if (!isset($_SESSION['klien_premium_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']); exit;
}

$pesanan_id = (int)$_SESSION['klien_pesanan_id'];

$q = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT p.status_pengerjaan, p.catatan_revisi, p.status_pembayaran,
           kp.tipe as tier
    FROM pesanan p
    LEFT JOIN klien_premium kp ON kp.pesanan_id = p.id
    WHERE p.id = '$pesanan_id'
"));

if ($q) {
    echo json_encode([
        'status'           => 'ok',
        'status_pengerjaan'=> $q['status_pengerjaan'],
        'catatan_revisi'   => $q['catatan_revisi'] ?? '',
        'status_pembayaran'=> $q['status_pembayaran'],
        'tier'             => $q['tier'] ?? 'Premium',
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Data not found']);
}
