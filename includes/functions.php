<?php
/**
 * includes/functions.php
 * ========================
 * Kumpulan fungsi helper global untuk Embun Visual Admin
 */

// ── Catat Log ke audit_logs
function catatLog($conn, $admin_id, $action, $target, $keterangan, $foto = null) {
    $action      = mysqli_real_escape_string($conn, $action);
    $target      = mysqli_real_escape_string($conn, $target);
    $keterangan  = mysqli_real_escape_string($conn, $keterangan);
    $foto        = $foto ? mysqli_real_escape_string($conn, $foto) : null;
    $foto_sql    = $foto ? "'$foto'" : "NULL";
    mysqli_query($conn,
        "INSERT INTO audit_logs (admin_id, action_type, target_id, keterangan, screenshot_path)
         VALUES ('$admin_id', '$action', '$target', '$keterangan', $foto_sql)"
    );
}

// ── Generate Invoice Number
function generateInvoice() {
    return 'INV-' . date('Ymd') . '-' . rand(100, 999);
}

// ── Format Rupiah
function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

// ── Sanitize input dari user
function bersihkan($conn, $str) {
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($str)));
}

// ── Cek apakah user adalah Super Admin
function isSuperAdmin() {
    return isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'Super Admin';
}

// ── Redirect dengan notif Swal
function redirectNotif($url, $swalJs) {
    $_SESSION['notif_pesan'] = $swalJs;
    header("Location: $url");
    exit;
}
?>
