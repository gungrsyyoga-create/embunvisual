<?php
// admin_premium/api_chat.php - AJAX endpoint for chat
include '../config.php';

if (!isset($_SESSION['klien_premium_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']); exit;
}

$pesanan_id = (int)$_SESSION['klien_pesanan_id'];
$action = $_GET['action'] ?? 'fetch';

header('Content-Type: application/json');

if ($action === 'fetch') {
    $since_id = isset($_GET['since']) ? (int)$_GET['since'] : 0;
    $q = mysqli_query($conn, "SELECT id, pengirim, nama_pengirim, pesan, created_at FROM pesan_proyek WHERE pesanan_id='$pesanan_id' AND id > '$since_id' ORDER BY id ASC");
    $messages = [];
    while ($row = mysqli_fetch_assoc($q)) {
        $messages[] = $row;
    }
    echo json_encode(['status' => 'ok', 'messages' => $messages]);

} elseif ($action === 'send' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $pesan = mysqli_real_escape_string($conn, trim($_POST['pesan'] ?? ''));
    $nama = mysqli_real_escape_string($conn, $_SESSION['klien_nama']);
    if ($pesan === '') { echo json_encode(['status' => 'error', 'message' => 'Pesan kosong']); exit; }
    if (mysqli_query($conn, "INSERT INTO pesan_proyek (pesanan_id, pengirim, nama_pengirim, pesan) VALUES ('$pesanan_id', 'klien', '$nama', '$pesan')")) {
        echo json_encode(['status' => 'ok']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
}
