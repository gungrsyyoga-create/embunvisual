<?php
// api_update_status.php - Admin/Staff AJAX endpoint to update status pengerjaan
include 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']); exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid method']); exit;
}

$pesanan_id  = (int)$_POST['pesanan_id'];
$new_status  = mysqli_real_escape_string($conn, $_POST['new_status'] ?? '');
$catatan     = mysqli_real_escape_string($conn, $_POST['catatan_revisi'] ?? '');
$admin_name  = mysqli_real_escape_string($conn, $_SESSION['username'] ?? 'Admin');
$admin_id    = (int)$_SESSION['admin_id'];

$allowed_statuses = ['Belum Dimulai', 'Sedang Dikerjakan', 'Perlu Revisi', 'Menunggu Verifikasi', 'Selesai'];
if (!in_array($new_status, $allowed_statuses)) {
    echo json_encode(['status' => 'error', 'message' => 'Status tidak valid']); exit;
}

// Super admin can set any status; staff can only set Sedang Dikerjakan, Menunggu Verifikasi
$role = $_SESSION['role'] ?? 'Staff';
if ($role !== 'Super Admin' && in_array($new_status, ['Selesai', 'Perlu Revisi'])) {
    echo json_encode(['status' => 'error', 'message' => 'Hanya Super Admin yang bisa set status ini']); exit;
}

// Update
$catatan_sql = ($new_status === 'Perlu Revisi') ? ", catatan_revisi='$catatan'" : ", catatan_revisi=''";
$result = mysqli_query($conn, "UPDATE pesanan SET status_pengerjaan='$new_status' $catatan_sql WHERE id='$pesanan_id'");

if ($result) {
    // Log it
    $p = mysqli_fetch_assoc(mysqli_query($conn, "SELECT invoice, nama_pemesan FROM pesanan WHERE id='$pesanan_id'"));
    $inv = $p['invoice'] ?? '';
    mysqli_query($conn, "INSERT INTO audit_logs (admin_id, action_type, target_id, keterangan) 
                         VALUES ('$admin_id', 'Update Status', '$inv', 'Status diubah ke: $new_status oleh $admin_name')");
    echo json_encode(['status' => 'ok', 'new_status' => $new_status, 'catatan' => $catatan]);
} else {
    echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
}
