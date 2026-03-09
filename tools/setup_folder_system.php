<?php
/**
 * Setup Script untuk Folder Manager System
 * Jalankan dari terminal: php setup_folder_system.php
 * ATAU buka dari browser: localhost/embunvisual/tools/setup_folder_system.php
 */

session_start();
header('Content-Type: text/html; charset=utf-8');

// Include config
include dirname(dirname(__FILE__)) . '/config.php';

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Setup Folder System</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 3px solid #007bff; padding-bottom: 10px; }
        .step { margin: 20px 0; padding: 15px; background: #f9f9f9; border-left: 4px solid #007bff; }
        .step h3 { margin-top: 0; color: #007bff; }
        .success { background: #d4edda; border-left-color: #28a745; color: #155724; }
        .error { background: #f8d7da; border-left-color: #dc3545; color: #721c24; }
        .warning { background: #fff3cd; border-left-color: #ffc107; color: #856404; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; font-family: 'Courier New'; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 3px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔧 Setup Folder Manager System</h1>
        <p>Script ini akan mempersiapkan database dan membuat test data untuk sistem folder klien.</p>
";

$steps_completed = 0;
$errors = [];

// ===== STEP 1: Alter klien_premium table =====
echo "<div class='step'>";
echo "<h3>📋 Step 1: Update Table Structure (klien_premium)</h3>";

$sql_updates = [
    "ALTER TABLE `klien_premium` ADD COLUMN IF NOT EXISTS `is_active` TINYINT(1) DEFAULT 1",
    "ALTER TABLE `klien_premium` ADD COLUMN IF NOT EXISTS `tipe` ENUM('basic','premium','exclusive') DEFAULT 'basic'",
    "ALTER TABLE `klien_premium` ADD COLUMN IF NOT EXISTS `folder_path` VARCHAR(255)",
    "ALTER TABLE `admin_users` MODIFY COLUMN `role` ENUM('Super Admin','Staff','Basic') DEFAULT 'Staff'"
];

foreach($sql_updates as $sql) {
    if(mysqli_query($conn, $sql)) {
        echo "<p>✓ <code>{$sql}</code></p>";
        $steps_completed++;
    } else {
        $err = mysqli_error($conn);
        echo "<p class='error'>✗ Error: {$err}</p>";
        $errors[] = $err;
    }
}

echo "</div>";

// ===== STEP 2: Create barcode_scans table =====
echo "<div class='step'>";
echo "<h3>🎟️ Step 2: Create Table (barcode_scans)</h3>";

$sql_barcode = "CREATE TABLE IF NOT EXISTS `barcode_scans` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `pesanan_id` INT NOT NULL,
  `barcode` VARCHAR(100) NOT NULL,
  `nama_tamu` VARCHAR(200),
  `scan_time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `scan_ip` VARCHAR(45),
  UNIQUE KEY `unique_scan` (`pesanan_id`, `barcode`),
  KEY `idx_pesanan` (`pesanan_id`),
  KEY `idx_scan_date` (`scan_time`),
  FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan`(`id`) ON DELETE CASCADE
)";

if(mysqli_query($conn, $sql_barcode)) {
    echo "<p class='success'>✓ Table barcode_scans created/verified</p>";
    $steps_completed++;
} else {
    $err = mysqli_error($conn);
    echo "<p class='error'>✗ Error: {$err}</p>";
    $errors[] = $err;
}

echo "</div>";

// ===== STEP 3: Ensure tema files exist =====
echo "<div class='step'>";
echo "<h3>📝 Step 3: Verify Template Files</h3>";

$template_files = [
    'tema_basic_rsvp.php',
    'tema_premium_rsvp.php', 
    'tema_exclusive_rsvp.php'
];

$tema_dir = dirname(dirname(__FILE__)) . '/tema/';
foreach($template_files as $file) {
    $path = $tema_dir . $file;
    if(file_exists($path)) {
        $size = round(filesize($path) / 1024, 2);
        echo "<p class='success'>✓ {$file} ({$size} KB)</p>";
        $steps_completed++;
    } else {
        echo "<p class='error'>✗ {$file} NOT FOUND at {$path}</p>";
        $errors[] = "Template file missing: {$file}";
    }
}

echo "</div>";

// ===== STEP 4: Create test pesanan =====
echo "<div class='step'>";
echo "<h3>🧪 Step 4: Create Test Data (Pesanan & Klien)</h3>";

// Check if test data already exists
$check_test = mysqli_query($conn, "SELECT id FROM pesanan WHERE invoice_number='INV-TEST-2026-01'");
if(mysqli_num_rows($check_test) > 0) {
    echo "<p class='warning'>⚠️ Test data already exists (INV-TEST-2026-01), skipping creation</p>";
    echo "<div class='success'><strong>Existing Test Data:</strong><ul>";
    
    $test_data = mysqli_fetch_assoc($check_test);
    $pid = $test_data['id'];
    
    // Get klien data
    $kp_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM klien_premium WHERE pesanan_id='$pid'"));
    
    echo "<li>Pesanan ID: {$pid}</li>";
    echo "<li>Invoice: INV-TEST-2026-01</li>";
    if($kp_data) {
        echo "<li>Username: {$kp_data['username']}</li>";
        echo "<li>Tier: {$kp_data['tipe']}</li>";
        echo "<li>Folder: {$kp_data['folder_path']}</li>";
    }
    echo "</ul></div>";
    $steps_completed++;
    
} else {
    // Create tema first (if not exists)
    $tema_check = mysqli_query($conn, "SELECT id FROM katalog_tema LIMIT 1");
    if(mysqli_num_rows($tema_check) > 0) {
        $tema_id = mysqli_fetch_assoc($tema_check)['id'];
    } else {
        mysqli_query($conn, "INSERT INTO katalog_tema (nama_tema, deskripsi, harga, is_active) VALUES ('Template Test', 'Template Untuk Testing', 0, 1)");
        $tema_id = mysqli_insert_id($conn);
    }
    
    // Create test pesanan
    $event_date = date('Y-m-d H:i:s', strtotime('+7 days'));
    $sql_pesanan = "INSERT INTO pesanan (invoice_number, nama_pemesan, email_pemesan, tanggal_acara, lokasi, tema_id, status_pembayaran, tamu_total, tamu_datang)
        VALUES ('INV-TEST-2026-01', 'Test Client Wedding', 'test@wedding.com', '$event_date', 'Bali Resort & Spa', '$tema_id', 'Lunas', 50, 0)";
    
    if(mysqli_query($conn, $sql_pesanan)) {
        $pid = mysqli_insert_id($conn);
        echo "<p class='success'>✓ Test pesanan dibuat (ID: {$pid})</p>";
        $steps_completed++;
        
        // Create klien_premium for this pesanan
        $uname = 'klien_test_' . substr(md5(time()), 0, 8);
        $pass = md5('123456');
        
        $sql_klien = "INSERT INTO klien_premium (pesanan_id, username, password, tipe, folder_path, is_active)
            VALUES ('$pid', '$uname', '$pass', 'basic', NULL, 1)";
        
        if(mysqli_query($conn, $sql_klien)) {
            echo "<p class='success'>✓ Klien premium created</p>";
            echo "<div class='success'><strong>Test Credentials:</strong><ul>";
            echo "<li>Username: {$uname}</li>";
            echo "<li>Password: 123456</li>";
            echo "<li>Pesanan ID: {$pid}</li>";
            echo "</ul></div>";
            $steps_completed++;
        } else {
            echo "<p class='error'>✗ Failed to create klien_premium: " . mysqli_error($conn) . "</p>";
            $errors[] = mysqli_error($conn);
        }
        
    } else {
        echo "<p class='error'>✗ Failed to create test pesanan: " . mysqli_error($conn) . "</p>";
        $errors[] = mysqli_error($conn);
    }
}

echo "</div>";

// ===== STEP 5: Create folder structure =====
echo "<div class='step'>";
echo "<h3>📁 Step 5: Create Folder Directories</h3>";

$undangan_dir = dirname(dirname(__FILE__)) . '/undangan';

$folders = [
    $undangan_dir,
    $undangan_dir . '/basic',
    $undangan_dir . '/premium',
    $undangan_dir . '/exclusive'
];

foreach($folders as $folder) {
    if(!is_dir($folder)) {
        if(mkdir($folder, 0755, true)) {
            echo "<p class='success'>✓ Created: {$folder}</p>";
            $steps_completed++;
        } else {
            echo "<p class='error'>✗ Failed to create: {$folder}</p>";
            $errors[] = "Failed to create folder: {$folder}";
        }
    } else {
        echo "<p class='success'>✓ Exists: {$folder}</p>";
        $steps_completed++;
    }
}

echo "</div>";

// ===== FINAL SUMMARY =====
echo "<div class='step success'>";
echo "<h3>✅ Setup Summary</h3>";
echo "<p><strong>Steps Completed:</strong> {$steps_completed}</p>";

if(count($errors) > 0) {
    echo "<p class='error'><strong>Errors Found:</strong></p>";
    echo "<ul>";
    foreach($errors as $error) {
        echo "<li>{$error}</li>";
    }
    echo "</ul>";
} else {
    echo "<p class='success'><strong>✓ All setup steps completed successfully!</strong></p>";
}

echo "</div>";

// ===== NEXT STEPS =====
echo "<div class='step warning'>";
echo "<h3>📌 Next Steps:</h3>";
echo "<ol>";
echo "<li><strong>Login as Super Admin</strong> ke admin.php</li>";
echo "<li>Buka menu <code>🗂️ Kelola Folder Klien</code> dari sidebar</li>";
echo "<li>Pilih pesanan 'Test Client Wedding' dengan status 'Lunas'</li>";
echo "<li>Pilih tier: <code>basic</code>, <code>premium</code>, atau <code>exclusive</code></li>";
echo "<li>Click <code>Buat Folder</code> - folder otomatis dibuat</li>";
echo "<li>Akses invitation template via link yang diberikan</li>";
echo "</ol>";
echo "</div>";

echo "</div></body></html>";

mysqli_close($conn);
?>
