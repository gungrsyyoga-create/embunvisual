<?php
/**
 * Konfigurasi Database Lokal untuk Undangan DEDE
 * Sistem ini berdiri sendiri dan tidak tergantung file global
 */

// Database Connection
$mysqli = new mysqli("localhost", "root", "", "embun_visual");

if ($mysqli->connect_error) {
    die("❌ Error Koneksi Database: " . $mysqli->connect_error);
}

mysqli_set_charset($mysqli, "utf8mb4");

// Konfigurasi Undangan
define('UNDANGAN_ID', 1); // ID tetap untuk Dede Bali
define('INVOICE_NUMBER', 'INV-DEDE-BALI-2026');
define('NAMA_ACARA', 'Pernikahan Dede & Bali');
define('NAMA_PEMESAN', 'Dede Santoso');
define('TANGGAL_ACARA', '2026-03-14 17:00:00');
define('LOKASI_ACARA', 'Royal Bali Beach Club, Sanur');
define('TIER', 'exclusive');
define('TEMA_WARNA', 'dark-gold');
define('DRESS_CODE', 'Black Tie Optional');

// Folder Info
define('BASE_URL', 'http://localhost/embunvisual/undangan/exclusive/dede/');
define('FOLDER_PATH', __DIR__);

// Fungsi Utility
function get_undangan() {
    global $mysqli;
    $result = $mysqli->query("SELECT * FROM undangan WHERE invoice_number = '" . INVOICE_NUMBER . "'");
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

function ensure_database_exists() {
    global $mysqli;
    
    // Check if undangan table exists
    $tables = [
        'undangan',
        'tamu_undangan',
        'etiket',
        'barcode_scans',
        'undangan_konfigurasi'
    ];
    
    foreach ($tables as $table) {
        $exists = $mysqli->query("SHOW TABLES LIKE '$table'");
        if (!$exists || $exists->num_rows == 0) {
            return false;
        }
    }
    
    return true;
}

function check_undangan_record() {
    global $mysqli;
    $result = $mysqli->query("SELECT id FROM undangan WHERE invoice_number = '" . INVOICE_NUMBER . "'");
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['id'];
    }
    return 0;
}

// Helper: Check if setup is needed
function needs_setup() {
    return !ensure_database_exists() || !check_undangan_record();
}

?>
