<?php
/**
 * admin_premium/api_barcode_scan.php
 * API untuk scan barcode tamu di exclusive tier events
 */
header('Content-Type: application/json; charset=utf-8');

session_start();
require_once __DIR__ . '/../config/bootstrap.php';

if (!isset($_SESSION['klien_premium_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$pesanan_id = (int)$_SESSION['klien_pesanan_id'];
$tier = $_SESSION['klien_tier'] ?? 'basic';

// Hanya exclusive yang bisa scan barcode
if ($tier !== 'exclusive') {
    echo json_encode(['status' => 'error', 'message' => 'Fitur ini hanya untuk tier Exclusive']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'scan_barcode') {
        $barcode = mysqli_real_escape_string($conn, $_POST['barcode'] ?? '');
        
        if (empty($barcode)) {
            echo json_encode(['status' => 'error', 'message' => 'Barcode tidak boleh kosong']);
            exit;
        }
        
        // Extract invoice dari barcode (format: EVL-{INVOICE}-{TIMESTAMP})
        $barcode_parts = explode('-', $barcode);
        
        if (count($barcode_parts) < 2) {
            echo json_encode(['status' => 'error', 'message' => 'Format barcode tidak valid']);
            exit;
        }
        
        $invoice_code = $barcode_parts[1];
        
        // Cari kesesuaian dengan pesanan
        $q_invoice = mysqli_query($conn, "SELECT p.id, p.invoice, p.nama_pemesan FROM pesanan p WHERE p.invoice LIKE '%$invoice_code%' LIMIT 1");
        
        if (mysqli_num_rows($q_invoice) > 0) {
            $invoice_data = mysqli_fetch_assoc($q_invoice);
            $guest_id = $invoice_data['id'];
            $guest_name = $invoice_data['nama_pemesan'];
            
            // Cek status RSVP tamu
            $q_rsvp = mysqli_query($conn, "SELECT status_rsvp FROM tamu_undangan WHERE pesanan_id='$pesanan_id' AND status_rsvp='Hadir' LIMIT 1");
            
            if (mysqli_num_rows($q_rsvp) > 0) {
                // Catat scan ke database (optional: untuk tracking)
                $timestamp = date('Y-m-d H:i:s');
                mysqli_query($conn, "INSERT INTO barcode_scans (pesanan_id, barcode, nama_tamu, scan_time) VALUES ('$pesanan_id', '$barcode', '$guest_name', '$timestamp') 
                ON DUPLICATE KEY UPDATE scan_time='$timestamp'");
                
                echo json_encode([
                    'status' => 'ok',
                    'message' => 'Tamu berhasil dicatat hadir',
                    'nama_tamu' => $guest_name,
                    'barcode' => $barcode
                ]);
            } else {
                echo json_encode(['status' => 'warning', 'message' => 'Tamu tidak ada dalam daftar RSVP Hadir']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Barcode tidak dikenali dalam sistem']);
        }
        exit;
    }
    
    if ($action === 'get_scan_stats') {
        // Ambil statistik scan hari ini
        $today = date('Y-m-d');
        
        $q_stats = mysqli_query($conn, "SELECT 
            COUNT(*) as total_scan,
            COUNT(DISTINCT nama_tamu) as unique_guests
        FROM barcode_scans 
        WHERE pesanan_id='$pesanan_id' 
        AND DATE(scan_time)='$today'");
        
        $stats = mysqli_fetch_assoc($q_stats);
        
        echo json_encode([
            'status' => 'ok',
            'total_scanned' => (int)$stats['total_scan'],
            'unique_guests' => (int)$stats['unique_guests']
        ]);
        exit;
    }
}

echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
?>
