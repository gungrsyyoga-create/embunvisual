<?php
// admin_premium/api_admin_chat.php
// AJAX endpoint for ADMIN/STAFF side chat
require_once __DIR__ . '/../config/bootstrap.php';

if (!isset($_SESSION['admin_id'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']); exit;
}

header('Content-Type: application/json');

$admin_id   = (int)$_SESSION['admin_id'];
$admin_name = mysqli_real_escape_string($conn, $_SESSION['username'] ?? 'Admin');
$action     = $_GET['action'] ?? 'fetch';

if ($action === 'fetch') {
    $pesanan_id = (int)($_GET['pid'] ?? 0);
    $since_id   = (int)($_GET['since'] ?? 0);
    if ($pesanan_id === 0) { echo json_encode(['status' => 'ok', 'messages' => []]); exit; }
    $q = mysqli_query($conn, "SELECT id, pengirim, nama_pengirim, pesan, gambar_path, created_at 
                              FROM pesan_proyek 
                              WHERE pesanan_id='$pesanan_id' AND id > '$since_id' 
                              ORDER BY id ASC");
    $messages = [];
    while ($row = mysqli_fetch_assoc($q)) { $messages[] = $row; }
    echo json_encode(['status' => 'ok', 'messages' => $messages]);

} elseif ($action === 'send' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $pesanan_id = (int)($_POST['pid'] ?? 0);
    $pesan      = mysqli_real_escape_string($conn, trim($_POST['pesan'] ?? ''));
    $gambar_path = null;

    // Handle image upload
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $ftype   = mime_content_type($_FILES['gambar']['tmp_name']);
        if (in_array($ftype, $allowed) && $_FILES['gambar']['size'] <= 5 * 1024 * 1024) {
            $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
            $fname = 'chat_' . uniqid() . '.' . $ext;
            $dest = dirname(__DIR__) . '/uploads/chat/' . $fname;
            if (!is_dir(dirname($dest))) { mkdir(dirname($dest), 0755, true); }
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $dest)) {
                $gambar_path = 'uploads/chat/' . $fname;
            }
        }
    }

    if ($pesan === '' && $gambar_path === null) {
        echo json_encode(['status' => 'error', 'message' => 'Pesan kosong']); exit;
    }

    $gp_val = $gambar_path ? "'$gambar_path'" : "NULL";
    $safe_gp = $gambar_path ? mysqli_real_escape_string($conn, $gambar_path) : null;
    
    if (mysqli_query($conn, "INSERT INTO pesan_proyek (pesanan_id, pengirim, nama_pengirim, pesan, gambar_path) 
                             VALUES ('$pesanan_id', 'admin', '$admin_name', '$pesan', $gp_val)")) {
        echo json_encode(['status' => 'ok', 'gambar_path' => $gambar_path]);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
}
