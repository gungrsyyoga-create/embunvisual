<?php
/**
 * Folder Generation Handler
 * Menangani POST dari test page atau admin panel
 */

session_start();
header('Content-Type: text/html; charset=utf-8');

include dirname(dirname(__FILE__)) . '/config.php';

// Check if POST action
if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'generate') {
    $pesanan_id = (int)$_POST['pesanan_id'];
    $tier = $_POST['tier'] ?? 'basic';
    
    // Validate tier
    $valid_tiers = ['basic', 'premium', 'exclusive'];
    if(!in_array($tier, $valid_tiers)) {
        die("❌ Invalid tier: {$tier}");
    }
    
    // Get pesanan data
    $q_pesanan = mysqli_query($conn, "SELECT p.*, kp.id as kp_id 
        FROM pesanan p 
        LEFT JOIN klien_premium kp ON kp.pesanan_id=p.id 
        WHERE p.id='$pesanan_id'");
    
    if(mysqli_num_rows($q_pesanan) === 0) {
        die("❌ Pesanan tidak ditemukan (ID: {$pesanan_id})");
    }
    
    $p_data = mysqli_fetch_assoc($q_pesanan);
    
    echo "<!DOCTYPE html>
    <html>
    <head>
        <meta charset='utf-8'>
        <title>Generation Progress</title>
        <style>
            body { font-family: Arial; padding: 20px; background: #f5f5f5; }
            .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
            h1 { color: #333; margin-bottom: 20px; }
            .log { background: #f9f9f9; border: 1px solid #ddd; border-radius: 4px; padding: 15px; margin: 10px 0; font-family: monospace; font-size: 0.9em; line-height: 1.6; }
            .log-item { padding: 5px 0; }
            .success { color: #28a745; }
            .error { color: #dc3545; }
            .info { color: #0066cc; }
            .result { margin-top: 20px; padding: 15px; background: #dcfce7; border: 2px solid #28a745; border-radius: 4px; color: #155724; }
            a { color: #667eea; text-decoration: none; margin-top: 15px; display: inline-block; }
            a:hover { text-decoration: underline; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h1>📁 Folder Generation Progress</h1>
            <div class='log'>";
    
    function log_gen($msg, $type = 'info') {
        $class = $type;
        echo "<div class='log-item {$class}'>→ {$msg}</div>";
    }
    
    // === STEP 1: Validasi status pembayaran ===
    log_gen("Validating pesanan status...", 'info');
    if($p_data['status_pembayaran'] !== 'Lunas') {
        log_gen("❌ Status pembayaran: {$p_data['status_pembayaran']} (harus 'Lunas')", 'error');
        echo "</div></div></body></html>";
        die();
    }
    log_gen("✓ Status pembayaran: Lunas", 'success');
    
    // === STEP 2: Prepare folder name ===
    log_gen("Preparing folder name...", 'info');
    $nama_klien = strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $p_data['nama_pemesan']));
    log_gen("✓ Folder name: {$nama_klien}", 'success');
    
    // === STEP 3: Create directory structure ===
    log_gen("Creating directory structure...", 'info');
    $base_dir = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'undangan';
    $tier_dir = $base_dir . DIRECTORY_SEPARATOR . $tier;
    $client_dir = $tier_dir . DIRECTORY_SEPARATOR . $nama_klien;
    
    if(!is_dir($base_dir)) {
        if(mkdir($base_dir, 0755, true)) {
            log_gen("✓ Created base directory: undangan", 'success');
        } else {
            log_gen("❌ Failed to create base directory", 'error');
            echo "</div></div></body></html>";
            die();
        }
    } else {
        log_gen("✓ Base directory exists: undangan", 'success');
    }
    
    if(!is_dir($tier_dir)) {
        if(mkdir($tier_dir, 0755, true)) {
            log_gen("✓ Created tier directory: undangan/{$tier}", 'success');
        } else {
            log_gen("❌ Failed to create tier directory", 'error');
            echo "</div></div></body></html>";
            die();
        }
    } else {
        log_gen("✓ Tier directory exists: undangan/{$tier}", 'success');
    }
    
    if(!is_dir($client_dir)) {
        if(mkdir($client_dir, 0755, true)) {
            log_gen("✓ Created client directory: undangan/{$tier}/{$nama_klien}", 'success');
        } else {
            log_gen("❌ Failed to create client directory", 'error');
            echo "</div></div></body></html>";
            die();
        }
    } else {
        log_gen("✓ Client directory exists: undangan/{$tier}/{$nama_klien}", 'success');
    }
    
    // === STEP 4: Copy template ===
    log_gen("Copying template file...", 'info');
    $template_map = [
        'basic' => 'tema_basic_rsvp.php',
        'premium' => 'tema_premium_rsvp.php',
        'exclusive' => 'tema_exclusive_rsvp.php'
    ];
    
    $template_file = $template_map[$tier];
    $template_source = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'tema' . DIRECTORY_SEPARATOR . $template_file;
    $index_dest = $client_dir . DIRECTORY_SEPARATOR . 'index.php';
    
    if(!file_exists($template_source)) {
        log_gen("❌ Template file not found: {$template_file}", 'error');
        echo "</div></div></body></html>";
        die();
    }
    log_gen("✓ Template file found: {$template_file}", 'success');
    
    if(copy($template_source, $index_dest)) {
        log_gen("✓ Copied template to: undangan/{$tier}/{$nama_klien}/index.php", 'success');
    } else {
        log_gen("❌ Failed to copy template", 'error');
        echo "</div></div></body></html>";
        die();
    }
    
    // === STEP 5: Update database ===
    log_gen("Updating database...", 'info');
    $folder_rel = "undangan/{$tier}/{$nama_klien}";
    
    if($p_data['kp_id']) {
        // Update existing klien_premium
        if(mysqli_query($conn, "UPDATE klien_premium SET tipe='$tier', folder_path='$folder_rel' WHERE id='".$p_data['kp_id']."'")) {
            log_gen("✓ Updated klien_premium record", 'success');
        } else {
            log_gen("❌ Failed to update klien_premium: " . mysqli_error($conn), 'error');
            echo "</div></div></body></html>";
            die();
        }
    } else {
        // Create new klien_premium
        $uname = 'klien_' . substr(md5(time()), 0, 8);
        $pass = md5('123456');
        
        if(mysqli_query($conn, "INSERT INTO klien_premium (pesanan_id, username, password, tipe, folder_path, is_active) 
            VALUES ('$pesanan_id', '$uname', '$pass', '$tier', '$folder_rel', 1)")) {
            log_gen("✓ Created new klien_premium record", 'success');
            log_gen("  Username: {$uname}", 'info');
            log_gen("  Password: 123456", 'info');
        } else {
            log_gen("❌ Failed to create klien_premium: " . mysqli_error($conn), 'error');
            echo "</div></div></body></html>";
            die();
        }
    }
    
    // === STEP 6: Log activity ===
    log_gen("Logging activity...", 'info');
    $invoice = $p_data['invoice_number'];
    if(mysqli_query($conn, "INSERT INTO audit_logs (admin_id, aksi, keterangan, data_id) 
        VALUES (0, 'Generate Folder Client', 'Invoice: {$invoice}, Tier: {$tier}, Path: {$folder_rel}', '$pesanan_id')")) {
        log_gen("✓ Activity logged", 'success');
    } else {
        log_gen("⚠️ Failed to log activity (non-critical)", 'info');
    }
    
    echo "            </div>";
    echo "            <div class='result'>";
    echo "                <h2>✅ Folder Generation Successful!</h2>";
    echo "                <p><strong>Invoice:</strong> {$invoice}</p>";
    echo "                <p><strong>Klien:</strong> {$p_data['nama_pemesan']}</p>";
    echo "                <p><strong>Tier:</strong> " . strtoupper($tier) . "</p>";
    echo "                <p><strong>Folder Path:</strong> <code>{$folder_rel}</code></p>";
    echo "                <p><strong>Template:</strong> {$template_file}</p>";
    echo "                <h3>📋 Test Links:</h3>";
    
    $base_url = "/embunvisual/undangan/{$tier}/{$nama_klien}/index.php?pid={$pesanan_id}";
    
    if($tier === 'exclusive') {
        echo "<ul>";
        echo "<li><a href='{$base_url}&mode=invitation' target='_blank'>📬 View Invitation</a></li>";
        echo "<li><a href='{$base_url}&mode=barcode' target='_blank'>📱 View Barcode</a></li>";
        echo "<li><a href='{$base_url}&mode=rsvp' target='_blank'>✓ View RSVP Form</a></li>";
        echo "</ul>";
    } else {
        echo "<ul>";
        echo "<li><a href='{$base_url}' target='_blank'>👁️ View Invitation</a></li>";
        echo "</ul>";
    }
    
    echo "                <p style='margin-top: 20px;'>";
    echo "                    <a href='/embunvisual/tools/test_folder_rsvp.php'>← Back to Test Page</a> | ";
    echo "                    <a href='/embunvisual/admin.php?menu=folder_manager'>Go to Admin Panel</a>";
    echo "                </p>";
    echo "            </div>";
    echo "        </div>";
    echo "    </body>";
    echo "</html>";
    
    mysqli_close($conn);
    exit;
}

// If not POST, redirect to test page
header("Location: test_folder_rsvp.php");
?>
