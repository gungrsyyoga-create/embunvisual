<?php
/**
 * API untuk Manajemen E-Ticket & RSVP
 * Endpoints untuk generate, verify, dan scan e-ticket
 */

header('Content-Type: application/json');
$mysqli = new mysqli("localhost", "root", "", "embun_visual");
mysqli_set_charset($mysqli, "utf8");

$action = isset($_GET['action']) ? $_GET['action'] : null;
$response = ['status' => 'error', 'message' => 'Invalid request'];

// ===== GENERATE E-TICKET (setelah RSVP) =====
if ($action === 'generate_etiket') {
    $tamu_id = isset($_POST['tamu_id']) ? (int)$_POST['tamu_id'] : 0;
    
    if ($tamu_id > 0) {
        // Get tamu data
        $tamu = $mysqli->query("SELECT * FROM tamu_undangan WHERE id = $tamu_id")->fetch_assoc();
        
        if ($tamu) {
            $etiket_number = 'DEDE-' . strtoupper(substr($tamu['nama_tamu'], 0, 3)) . '-' . date('YmdHis');
            $barcode_value = 'EVL-' . $tamu['undangan_id'] . '-' . $tamu_id;
            
            $insert = "INSERT INTO etiket (tamu_undangan_id, undangan_id, etiket_number, barcode_value, status)
                      VALUES ($tamu_id, " . $tamu['undangan_id'] . ", '$etiket_number', '$barcode_value', 'active')";
            
            if ($mysqli->query($insert)) {
                $mysqli->query("UPDATE tamu_undangan SET etiket_number = '$etiket_number', status_etiket = 'generated' WHERE id = $tamu_id");
                
                $response = [
                    'status' => 'success',
                    'etiket_number' => $etiket_number,
                    'barcode_value' => $barcode_value,
                    'nama_tamu' => $tamu['nama_tamu'],
                    'respon' => $tamu['respon'],
                    'jumlah' => $tamu['jumlah_tamu']
                ];
            }
        }
    }
}

// ===== VERIFY E-TICKET (check-in verification) =====
elseif ($action === 'verify_etiket') {
    $barcode_value = isset($_GET['barcode']) ? mysqli_real_escape_string($mysqli, $_GET['barcode']) : '';
    
    if (!empty($barcode_value)) {
        $etiket = $mysqli->query("SELECT e.*, t.nama_tamu, t.respon, t.jumlah_tamu 
                                 FROM etiket e 
                                 JOIN tamu_undangan t ON e.tamu_undangan_id = t.id 
                                 WHERE e.barcode_value = '$barcode_value'")
                         ->fetch_assoc();
        
        if ($etiket && $etiket['status'] === 'active') {
            $response = [
                'status' => 'success',
                'message' => 'E-Ticket valid',
                'nama_tamu' => $etiket['nama_tamu'],
                'respon' => $etiket['respon'],
                'jumlah' => $etiket['jumlah_tamu'],
                'etiket_number' => $etiket['etiket_number'],
                'generated_at' => $etiket['generated_at']
            ];
        } else {
            $response = ['status' => 'error', 'message' => 'E-Ticket tidak valid atau sudah digunakan'];
        }
    }
}

// ===== SCAN E-TICKET (check-in/barcode scan) =====
elseif ($action === 'scan_etiket') {
    $barcode_value = isset($_POST['barcode']) ? mysqli_real_escape_string($mysqli, $_POST['barcode']) : '';
    $scanner_name = isset($_POST['scanner']) ? mysqli_real_escape_string($mysqli, $_POST['scanner']) : 'Sistem';
    
    if (!empty($barcode_value)) {
        $etiket = $mysqli->query("SELECT * FROM etiket WHERE barcode_value = '$barcode_value' AND status = 'active'")
                         ->fetch_assoc();
        
        if ($etiket) {
            // Log scan
            $log = "INSERT INTO barcode_scans (etiket_id, tamu_undangan_id, undangan_id, barcode_value, scanner_name, scanner_ip)
                   VALUES (" . $etiket['id'] . ", " . $etiket['tamu_undangan_id'] . ", " . $etiket['undangan_id'] . ", '$barcode_value', '$scanner_name', '". $_SERVER['REMOTE_ADDR'] . "')";
            
            if ($mysqli->query($log)) {
                // Update etiket status
                $mysqli->query("UPDATE etiket SET status = 'used', used_at = NOW(), scanned_by = '$scanner_name' WHERE id = " . $etiket['id']);
                
                $tamu = $mysqli->query("SELECT * FROM tamu_undangan WHERE id = " . $etiket['tamu_undangan_id'])->fetch_assoc();
                
                $response = [
                    'status' => 'success',
                    'message' => 'Check-in berhasil',
                    'nama_tamu' => $tamu['nama_tamu'],
                    'respon' => $tamu['respon'],
                    'jumlah' => $tamu['jumlah_tamu'],
                    'etiket_number' => $etiket['etiket_number']
                ];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Barcode tidak valid'];
        }
    }
}

// ===== GET RSVP STATS =====
elseif ($action === 'stats') {
    $undangan_id = isset($_GET['undangan_id']) ? (int)$_GET['undangan_id'] : 1;
    
    $stats = $mysqli->query("SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN respon='Hadir' THEN 1 ELSE 0 END) as hadir,
        SUM(CASE WHEN respon='Tidak Hadir' THEN 1 ELSE 0 END) as tidak_hadir,
        SUM(CASE WHEN status_etiket='generated' THEN 1 ELSE 0 END) as etiket_generated
        FROM tamu_undangan WHERE undangan_id = $undangan_id")
        ->fetch_assoc();
    
    $response = [
        'status' => 'success',
        'data' => $stats
    ];
}

// ===== LIST RSVP RESPONSES =====
elseif ($action === 'list_rsvp') {
    $undangan_id = isset($_GET['undangan_id']) ? (int)$_GET['undangan_id'] : 1;
    
    $result = $mysqli->query("SELECT * FROM tamu_undangan WHERE undangan_id = $undangan_id ORDER BY created_at DESC");
    $rsvp_list = [];
    
    while ($row = $result->fetch_assoc()) {
        $rsvp_list[] = $row;
    }
    
    $response = [
        'status' => 'success',
        'total' => count($rsvp_list),
        'data' => $rsvp_list
    ];
}

echo json_encode($response);
$mysqli->close();
?>
