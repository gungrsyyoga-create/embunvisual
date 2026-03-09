<?php
// ═══════════════════════════════════════════════════════════════
// EMBUN VISUAL - API TEMPLATE
// Lokasi: /app/api/example-api.php
//
// Gunakan file ini sebagai template untuk membuat API endpoint baru
// ═══════════════════════════════════════════════════════════════

header('Content-Type: application/json');

// Load bootstrap
require_once __DIR__ . '/../../config/bootstrap.php';

// Validasi method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response('error', 'Method not allowed', ['allowed' => 'POST']);
}

// Validasi action
$action = $_POST['action'] ?? null;
if (!$action) {
    json_response('error', 'Action tidak ditentukan');
}

// Validasi user permission
if (!is_logged_in()) {
    json_response('error', 'Unauthorized', ['code' => 401]);
}

// ─── REQUEST HANDLER ────────────────────────────────────────────

try {
    switch ($action) {
        case 'create':
            handleCreate();
            break;

        case 'update':
            handleUpdate();
            break;

        case 'delete':
            handleDelete();
            break;

        case 'list':
            handleList();
            break;

        default:
            json_response('error', 'Action tidak dikenali');
    }
} catch (Exception $e) {
    json_response('error', $e->getMessage(), ['exception' => get_class($e)]);
}

// ─── ACTION HANDLERS ────────────────────────────────────────────

function handleCreate() {
    global $conn;

    // Validate input
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');

    if (empty($name) || empty($email)) {
        json_response('error', 'Nama dan email wajib diisi');
    }

    // Process
    $query = "INSERT INTO users (name, email, created_at) VALUES ('{$name}', '{$email}', NOW())";
    if (mysqli_query($conn, $query)) {
        json_response('success', 'Data berhasil dibuat', [
            'id' => mysqli_insert_id($conn)
        ]);
    } else {
        throw new Exception("Database error: " . mysqli_error($conn));
    }
}

function handleUpdate() {
    global $conn;

    $id = sanitize($_POST['id'] ?? '');
    $name = sanitize($_POST['name'] ?? '');

    if (empty($id)) {
        json_response('error', 'ID wajib diisi');
    }

    $query = "UPDATE users SET name = '{$name}', updated_at = NOW() WHERE id = {$id}";
    if (mysqli_query($conn, $query)) {
        json_response('success', 'Data berhasil diperbarui');
    } else {
        throw new Exception("Database error: " . mysqli_error($conn));
    }
}

function handleDelete() {
    global $conn;

    $id = sanitize($_POST['id'] ?? '');

    if (empty($id)) {
        json_response('error', 'ID wajib diisi');
    }

    $query = "DELETE FROM users WHERE id = {$id}";
    if (mysqli_query($conn, $query)) {
        json_response('success', 'Data berhasil dihapus');
    } else {
        throw new Exception("Database error: " . mysqli_error($conn));
    }
}

function handleList() {
    global $conn;

    $query = "SELECT id, name, email, created_at FROM users ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        throw new Exception("Database error: " . mysqli_error($conn));
    }

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    json_response('success', 'Data berhasil diambil', [
        'count' => count($data),
        'data' => $data
    ]);
}

?>
