<?php
/**
 * API E-Tiket Lokal untuk Undangan DEDE
 */

require_once 'config.php';

header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$undangan_id = UNDANGAN_ID;

// ===== GENERATE E-TIKET =====
if ($action === 'generate_etiket' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $tamu_undangan_id = (int)($_POST['tamu_undangan_id'] ?? 0);
    
    if (!$tamu_undangan_id) {
        die(json_encode(['status' => 'error', 'message' => 'ID Tamu tidak ditemukan']));
    }
    
    // Check if tamu exists
    $result = $mysqli->query("SELECT id, nama_tamu FROM tamu_undangan WHERE id = $tamu_undangan_id AND undangan_id = $undangan_id");
    if (!$result || $result->num_rows == 0) {
        die(json_encode(['status' => 'error', 'message' => 'Tamu tidak ditemukan']));
    }
    
    $tamu = $result->fetch_assoc();
    
    // Generate unique etiket number
    $etiket_number = 'ETK-' . strtoupper(substr(md5($tamu['nama_tamu'] . time()), 0, 10));
    $barcode_value = 'DEDE-' . date('Ymd') . '-' . str_pad($tamu_undangan_id, 5, '0', STR_PAD_LEFT);
    
    // Insert etiket
    $sql = "INSERT INTO etiket (tamu_undangan_id, undangan_id, etiket_number, barcode_value, status) 
            VALUES ($tamu_undangan_id, $undangan_id, '$etiket_number', '$barcode_value', 'generated')";
    
    if ($mysqli->query($sql)) {
        // Update tamu status
        $mysqli->query("UPDATE tamu_undangan SET 
            etiket_number = '$etiket_number', 
            status_etiket = 'generated', 
            etiket_generated_at = NOW() 
            WHERE id = $tamu_undangan_id");
        
        echo json_encode([
            'status' => 'success',
            'data' => [
                'etiket_number' => $etiket_number,
                'barcode_value' => $barcode_value,
                'tamu_nama' => $tamu['nama_tamu'],
                'generated_at' => date('Y-m-d H:i:s')
            ]
        ]);
    } else {
        die(json_encode(['status' => 'error', 'message' => 'Gagal generate etiket: ' . $mysqli->error]));
    }
}

// ===== VERIFY E-TIKET =====
else if ($action === 'verify_etiket') {
    $barcode = $_GET['barcode'] ?? '';
    
    if (!$barcode) {
        die(json_encode(['status' => 'error', 'message' => 'Barcode tidak ditemukan']));
    }
    
    $result = $mysqli->query("SELECT e.*, t.nama_tamu FROM etiket e 
                            JOIN tamu_undangan t ON e.tamu_undangan_id = t.id 
                            WHERE e.barcode_value = '$barcode' AND e.undangan_id = $undangan_id");
    
    if ($result && $result->num_rows > 0) {
        $etiket = $result->fetch_assoc();
        echo json_encode([
            'status' => 'success',
            'data' => $etiket
        ]);
    } else {
        die(json_encode(['status' => 'error', 'message' => 'E-tiket tidak valid']));
    }
}

// ===== SCAN E-TIKET (Check-in) =====
else if ($action === 'scan_etiket' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $barcode = $_POST['barcode'] ?? '';
    $scanner_name = $_POST['scanner_name'] ?? 'Scanner';
    
    if (!$barcode) {
        die(json_encode(['status' => 'error', 'message' => 'Barcode tidak ditemukan']));
    }
    
    // Find etiket
    $result = $mysqli->query("SELECT id, tamu_undangan_id FROM etiket WHERE barcode_value = '$barcode' AND undangan_id = $undangan_id");
    
    if ($result && $result->num_rows > 0) {
        $etiket = $result->fetch_assoc();
        $etiket_id = $etiket['id'];
        $tamu_id = $etiket['tamu_undangan_id'];
        
        // Log scan
        $scanner_ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
        $sql = "INSERT INTO barcode_scans (etiket_id, tamu_undangan_id, undangan_id, barcode_value, scanner_name, scanner_ip) 
                VALUES ($etiket_id, $tamu_id, $undangan_id, '$barcode', '$scanner_name', '$scanner_ip')";
        
        if ($mysqli->query($sql)) {
            // Update etiket status
            $mysqli->query("UPDATE etiket SET status = 'used', used_at = NOW(), scanned_by = '$scanner_name' WHERE id = $etiket_id");
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Check-in berhasil',
                'scan_id' => $mysqli->insert_id
            ]);
        } else {
            die(json_encode(['status' => 'error', 'message' => 'Gagal log scan']));
        }
    } else {
        die(json_encode(['status' => 'error', 'message' => 'E-tiket tidak ditemukan']));
    }
}

// ===== GET STATISTICS =====
else if ($action === 'stats') {
    $result = $mysqli->query("SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN respon='Hadir' THEN 1 ELSE 0 END) as hadir,
        SUM(CASE WHEN respon='Tidak Hadir' THEN 1 ELSE 0 END) as tidak_hadir,
        SUM(CASE WHEN respon='Hadir' THEN jumlah_tamu ELSE 0 END) as total_tamu,
        SUM(CASE WHEN status_etiket='generated' THEN 1 ELSE 0 END) as etiket_generated
        FROM tamu_undangan WHERE undangan_id = $undangan_id");
    
    if ($result) {
        $stats = $result->fetch_assoc();
        echo json_encode([
            'status' => 'success',
            'data' => $stats
        ]);
    } else {
        die(json_encode(['status' => 'error', 'message' => $mysqli->error]));
    }
}

// ===== LIST RSVP =====
else if ($action === 'list_rsvp') {
    $result = $mysqli->query("SELECT * FROM tamu_undangan WHERE undangan_id = $undangan_id ORDER BY created_at DESC");
    
    if ($result) {
        $rsvp = [];
        while ($row = $result->fetch_assoc()) {
            $rsvp[] = $row;
        }
        echo json_encode([
            'status' => 'success',
            'data' => $rsvp,
            'count' => count($rsvp)
        ]);
    } else {
        die(json_encode(['status' => 'error', 'message' => $mysqli->error]));
    }
}

// Default response
else {
    die(json_encode([
        'status' => 'error',
        'message' => 'Action tidak dikenali',
        'available' => ['generate_etiket', 'verify_etiket', 'scan_etiket', 'stats', 'list_rsvp']
    ]));
}

?>
