<?php
/**
 * Test Script: Folder Generation & RSVP System
 * Manual test untuk verify semua fitur bekerja
 */

session_start();
header('Content-Type: text/html; charset=utf-8');

include dirname(dirname(__FILE__)) . '/config.php';

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Test Folder & RSVP System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; background: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden; }
        
        header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; }
        header h1 { font-size: 2rem; margin-bottom: 5px; }
        header p { opacity: 0.9; }
        
        .content { padding: 30px; }
        .section { margin: 30px 0; padding: 20px; background: #f9f9f9; border-left: 4px solid #667eea; border-radius: 4px; }
        .section h2 { color: #667eea; margin-bottom: 15px; font-size: 1.3rem; }
        
        .test-item { padding: 15px; margin: 10px 0; background: white; border-radius: 4px; border-left: 4px solid #ddd; }
        .test-item.success { border-left-color: #28a745; background: #d4edda; color: #155724; }
        .test-item.error { border-left-color: #dc3545; background: #f8d7da; color: #721c24; }
        .test-item.warning { border-left-color: #ffc107; background: #fff3cd; color: #856404; }
        
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th { background: #667eea; color: white; padding: 12px; text-align: left; }
        td { padding: 10px 12px; border-bottom: 1px solid #eee; }
        tr:hover { background: #f5f5f5; }
        
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; font-family: monospace; font-size: 0.9em; }
        pre { background: #f4f4f4; padding: 12px; border-radius: 4px; overflow-x: auto; font-size: 0.85em; line-height: 1.4; }
        
        button { background: #667eea; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 0.95em; }
        button:hover { background: #5568d3; }
        
        .link-box { background: #e7f3ff; border: 1px solid #667eea; padding: 15px; border-radius: 4px; margin: 10px 0; }
        .link-box a { color: #667eea; text-decoration: none; font-weight: 600; }
        .link-box a:hover { text-decoration: underline; }
        
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(500px, 1fr)); gap: 20px; }
        
        footer { background: #f5f5f5; padding: 20px; text-align: center; color: #999; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class='container'>
        <header>
            <h1>🔬 Test Folder & RSVP System</h1>
            <p>Comprehensive testing untuk verifikasi semua fitur bekerja dengan baik</p>
        </header>
        
        <div class='content'>
";

// ===== TEST 1: Database Connectivity =====
echo "<div class='section'>";
echo "<h2>1️⃣ Database Connectivity</h2>";

$test_queries = [
    'tamu_undangan' => 'SELECT COUNT(*) as total FROM tamu_undangan',
    'pesanan' => 'SELECT COUNT(*) as total FROM pesanan',
    'klien_premium' => 'SELECT COUNT(*) as total FROM klien_premium',
    'barcode_scans' => 'SELECT COUNT(*) as total FROM barcode_scans'
];

foreach($test_queries as $table => $query) {
    $result = mysqli_query($conn, $query);
    if($result) {
        $row = mysqli_fetch_assoc($result);
        echo "<div class='test-item success'>";
        echo "✓ <strong>{$table}</strong>: {$row['total']} records";
        echo "</div>";
    } else {
        echo "<div class='test-item error'>";
        echo "✗ <strong>{$table}</strong>: " . mysqli_error($conn);
        echo "</div>";
    }
}

echo "</div>";

// ===== TEST 2: Template Files =====
echo "<div class='section'>";
echo "<h2>2️⃣ Template Files</h2>";

$templates = [
    'tema_basic_rsvp.php' => 'Basic Tier',
    'tema_premium_rsvp.php' => 'Premium Tier',
    'tema_exclusive_rsvp.php' => 'Exclusive Tier'
];

$tema_dir = dirname(dirname(__FILE__)) . '/tema/';
foreach($templates as $file => $label) {
    $path = $tema_dir . $file;
    if(file_exists($path)) {
        $size = round(filesize($path) / 1024, 2);
        echo "<div class='test-item success'>";
        echo "✓ <strong>{$label}</strong> ({$file}) - {$size} KB";
        echo "</div>";
    } else {
        echo "<div class='test-item error'>";
        echo "✗ <strong>{$label}</strong> ({$file}) - FILE NOT FOUND";
        echo "</div>";
    }
}

echo "</div>";

// ===== TEST 3: Folder Directories =====
echo "<div class='section'>";
echo "<h2>3️⃣ Folder Directories</h2>";

$dirs = [
    'undangan' => '/undangan',
    'basic' => '/undangan/basic',
    'premium' => '/undangan/premium',
    'exclusive' => '/undangan/exclusive'
];

$base_path = dirname(dirname(__FILE__));
foreach($dirs as $name => $path) {
    $full_path = $base_path . $path;
    if(is_dir($full_path)) {
        echo "<div class='test-item success'>";
        echo "✓ <strong>{$name}</strong> directory exists";
        echo "</div>";
    } else {
        echo "<div class='test-item warning'>";
        echo "⚠️ <strong>{$name}</strong> directory not found - akan dibuat saat folder generation";
        echo "</div>";
    }
}

echo "</div>";

// ===== TEST 4: Test Pesanan Data =====
echo "<div class='section'>";
echo "<h2>4️⃣ Test Pesanan Data</h2>";

$q_test = mysqli_query($conn, "SELECT p.id, p.invoice_number, p.nama_pemesan, p.status_pembayaran, p.tanggal_acara, kp.id as kp_id, kp.tipe, kp.folder_path 
    FROM pesanan p 
    LEFT JOIN klien_premium kp ON kp.pesanan_id=p.id 
    WHERE p.status_pembayaran='Lunas' 
    LIMIT 1");

if(mysqli_num_rows($q_test) > 0) {
    $test_data = mysqli_fetch_assoc($q_test);
    echo "<div class='test-item success'>";
    echo "<strong>Test Pesanan Found:</strong>";
    echo "<table>";
    echo "<tr><td><strong>Invoice:</strong></td><td><code>{$test_data['invoice_number']}</code></td></tr>";
    echo "<tr><td><strong>Klien:</strong></td><td>{$test_data['nama_pemesan']}</td></tr>";
    echo "<tr><td><strong>Status:</strong></td><td>{$test_data['status_pembayaran']}</td></tr>";
    echo "<tr><td><strong>Acara:</strong></td><td>" . date('d M Y H:i', strtotime($test_data['tanggal_acara'])) . "</td></tr>";
    echo "<tr><td><strong>Tier:</strong></td><td>" . ($test_data['tipe'] ? $test_data['tipe'] : 'Belum ditentukan') . "</td></tr>";
    echo "<tr><td><strong>Folder:</strong></td><td>" . ($test_data['folder_path'] ? $test_data['folder_path'] : 'Belum ada') . "</td></tr>";
    echo "</table>";
    echo "</div>";
    
    $test_pid = $test_data['id'];
    $test_invoice = $test_data['invoice_number'];
} else {
    echo "<div class='test-item error'>";
    echo "✗ Tidak ada pesanan dengan status 'Lunas'. Jalankan setup_folder_system.php terlebih dahulu.";
    echo "</div>";
}

echo "</div>";

// ===== TEST 5: Manual Folder Generation =====
if(!empty($test_pid)) {
    echo "<div class='section'>";
    echo "<h2>5️⃣ Manual Folder Generation Test</h2>";
    echo "<p>Anda bisa mencoba membuat folder secara manual di sini untuk testing:</p>";
    
    echo "<form method='POST' action='handle_folder_generation.php' style='margin-top: 15px;'>";
    echo "<div style='margin: 10px 0;'>";
    echo "<label><strong>Pilih Tier:</strong></label><br>";
    echo "<select name='tier' style='padding: 8px; font-size: 1em; margin-top: 5px;' required>";
    echo "<option value=''>-- Pilih Tier --</option>";
    echo "<option value='basic'>Basic (Simple, no countdown)</option>";
    echo "<option value='premium'>Premium (Countdown + Stats)</option>";
    echo "<option value='exclusive'>Exclusive (VIP + Barcode + 3 Modes)</option>";
    echo "</select>";
    echo "</div>";
    
    echo "<div style='margin: 10px 0;'>";
    echo "<input type='hidden' name='pesanan_id' value='{$test_pid}'>";
    echo "<input type='hidden' name='action' value='generate'>";
    echo "<button type='submit'>🚀 Generate Folder Sekarang</button>";
    echo "</div>";
    echo "</form>";
    
    echo "</div>";
}

// ===== TEST 6: RSVP Submission Example =====
echo "<div class='section'>";
echo "<h2>6️⃣ RSVP Submission Example</h2>";
echo "<p>Contoh bagaimana guest akan submit RSVP form:</p>";

echo "<pre>POST /undangan/rsvp.php

Parameters:
- pesanan_id: 1
- nama_tamu: 'Budi Santoso'
- no_hp: '62812345678'
- respon: 'hadir' (atau 'tidak_hadir')
- jumlah_hadir: 2

Expected Response:
✓ Terima kasih! Respons Anda telah tersimpan dengan baik.
</pre>";

echo "</div>";

// ===== TEST 7: Barcode Scanning Example =====
echo "<div class='section'>";
echo "<h2>7️⃣ Barcode Scanning API Example (Exclusive Tier)</h2>";
echo "<p>API untuk scanning barcode di event exclusive:</p>";

echo "<pre>POST /admin_premium/api_barcode_scan.php

{
  \"action\": \"scan_barcode\",
  \"barcode\": \"EVL-INV-2026-001-20260308143052\",
  \"klien_premium_id\": 5
}

Response:
{
  \"status\": \"ok\",
  \"message\": \"Scan recorded\",
  \"nama_tamu\": \"Budi Santoso\",
  \"barcode\": \"EVL-INV-2026-001-20260308143052\"
}
</pre>";

echo "</div>";

// ===== TEST 8: Access URLs =====
if(!empty($test_pid)) {
    echo "<div class='section'>";
    echo "<h2>8️⃣ Quick Access URLs (untuk testing)</h2>";
    
    $clean_name = strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $test_data['nama_pemesan']));
    
    echo "<p><strong>Basic Tier Invitation:</strong></p>";
    echo "<div class='link-box'>";
    echo "<a href='/embunvisual/undangan/basic/{$clean_name}/index.php?pid={$test_pid}' target='_blank'>";
    echo "/undangan/basic/{$clean_name}/index.php?pid={$test_pid}";
    echo "</a>";
    echo "</div>";
    
    echo "<p><strong>Premium Tier Invitation:</strong></p>";
    echo "<div class='link-box'>";
    echo "<a href='/embunvisual/undangan/premium/{$clean_name}/index.php?pid={$test_pid}' target='_blank'>";
    echo "/undangan/premium/{$clean_name}/index.php?pid={$test_pid}";
    echo "</a>";
    echo "</div>";
    
    echo "<p><strong>Exclusive Tier Invitation:</strong></p>";
    echo "<div class='link-box'>";
    echo "<a href='/embunvisual/undangan/exclusive/{$clean_name}/index.php?pid={$test_pid}&mode=invitation' target='_blank'>";
    echo "/undangan/exclusive/{$clean_name}/index.php?pid={$test_pid}&mode=invitation";
    echo "</a>";
    echo "</div>";
    
    echo "<p><strong>Exclusive Tier Barcode:</strong></p>";
    echo "<div class='link-box'>";
    echo "<a href='/embunvisual/undangan/exclusive/{$clean_name}/index.php?pid={$test_pid}&mode=barcode' target='_blank'>";
    echo "/undangan/exclusive/{$clean_name}/index.php?pid={$test_pid}&mode=barcode";
    echo "</a>";
    echo "</div>";
    
    echo "<p><strong>Exclusive Tier RSVP:</strong></p>";
    echo "<div class='link-box'>";
    echo "<a href='/embunvisual/undangan/exclusive/{$clean_name}/index.php?pid={$test_pid}&mode=rsvp' target='_blank'>";
    echo "/undangan/exclusive/{$clean_name}/index.php?pid={$test_pid}&mode=rsvp";
    echo "</a>";
    echo "</div>";
    
    echo "</div>";
}

// ===== TEST 9: Folder Manager Access =====
echo "<div class='section'>";
echo "<h2>9️⃣ Admin Panel Access</h2>";
echo "<p><strong>Kelola Folder Klien:</strong></p>";
echo "<div class='link-box'>";
echo "<a href='/embunvisual/admin.php?menu=folder_manager' target='_blank'>";
echo "http://localhost/embunvisual/admin.php?menu=folder_manager";
echo "</a>";
echo "</div>";
echo "<p><em>Note: Login sebagai Super Admin terlebih dahulu</em></p>";
echo "</div>";

echo "        </div>";

echo "        <footer>";
echo "        <p><strong>Created:</strong> March 8, 2026</p>";
echo "        <p><strong>Version:</strong> 1.0.0 - Folder & RSVP Management System</p>";
echo "        </footer>";
echo "    </div>";
echo "</body></html>";

mysqli_close($conn);
?>
