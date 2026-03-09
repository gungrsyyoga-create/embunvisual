<?php
/**
 * Setup Database untuk Undangan DEDE - Bali Exclusive
 * Jalankan file ini sekali untuk initialize database
 */

$mysqli = new mysqli("localhost", "root", "", "embun_visual");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
mysqli_set_charset($mysqli, "utf8mb4");

$output = [];
$success = true;

// === TABLE CREATION ===
$output[] = "<h2 style='color: #d4af37; border-bottom: 2px solid #d4af37; padding-bottom: 10px;'>🔧 Inisialisasi Database Undangan DEDE</h2>";

// 1. UNDANGAN TABLE
$sql_undangan = "CREATE TABLE IF NOT EXISTS undangan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    invoice_number VARCHAR(100) UNIQUE NOT NULL,
    nama_pemesan VARCHAR(255) NOT NULL,
    nama_acara VARCHAR(255) NOT NULL,
    tanggal_acara DATETIME NOT NULL,
    lokasi_acara VARCHAR(255),
    tier ENUM('basic', 'premium', 'exclusive') DEFAULT 'basic',
    tema_warna VARCHAR(50),
    dress_code VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY (invoice_number),
    KEY (created_at)
)";

if ($mysqli->query($sql_undangan)) {
    $output[] = "✅ <strong>Tabel undangan</strong> - Created/Verified";
} else {
    $output[] = "❌ <strong>Tabel undangan</strong> - Error: " . $mysqli->error;
    $success = false;
}

// 2. TAMU_UNDANGAN TABLE
$sql_tamu = "CREATE TABLE IF NOT EXISTS tamu_undangan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    undangan_id INT NOT NULL,
    nama_tamu VARCHAR(255) NOT NULL,
    no_hp VARCHAR(20),
    respon ENUM('Hadir', 'Tidak Hadir', 'Menunggu') DEFAULT 'Menunggu',
    jumlah_tamu INT DEFAULT 1,
    etiket_number VARCHAR(100) UNIQUE,
    status_etiket ENUM('pending', 'generated', 'used') DEFAULT 'pending',
    etiket_generated_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY (undangan_id),
    KEY (etiket_number),
    KEY (respon),
    FOREIGN KEY (undangan_id) REFERENCES undangan(id) ON DELETE CASCADE
)";

if ($mysqli->query($sql_tamu)) {
    $output[] = "✅ <strong>Tabel tamu_undangan</strong> - Created/Verified";
} else {
    $output[] = "❌ <strong>Tabel tamu_undangan</strong> - Error: " . $mysqli->error;
    $success = false;
}

// 3. ETIKET TABLE
$sql_etiket = "CREATE TABLE IF NOT EXISTS etiket (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tamu_undangan_id INT,
    undangan_id INT NOT NULL,
    etiket_number VARCHAR(100) UNIQUE NOT NULL,
    barcode_value VARCHAR(100) UNIQUE NOT NULL,
    status ENUM('generated', 'used', 'scanned') DEFAULT 'generated',
    generated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    used_at DATETIME,
    scanned_by VARCHAR(100),
    KEY (undangan_id),
    KEY (etiket_number),
    KEY (barcode_value),
    KEY (status),
    FOREIGN KEY (undangan_id) REFERENCES undangan(id) ON DELETE CASCADE,
    FOREIGN KEY (tamu_undangan_id) REFERENCES tamu_undangan(id) ON DELETE SET NULL
)";

if ($mysqli->query($sql_etiket)) {
    $output[] = "✅ <strong>Tabel etiket</strong> - Created/Verified";
} else {
    $output[] = "❌ <strong>Tabel etiket</strong> - Error: " . $mysqli->error;
    $success = false;
}

// 4. BARCODE_SCANS TABLE
$sql_scans = "CREATE TABLE IF NOT EXISTS barcode_scans (
    id INT PRIMARY KEY AUTO_INCREMENT,
    etiket_id INT,
    tamu_undangan_id INT,
    undangan_id INT NOT NULL,
    barcode_value VARCHAR(100),
    scanned_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    scanner_name VARCHAR(100),
    scanner_ip VARCHAR(20),
    KEY (undangan_id),
    KEY (barcode_value),
    KEY (scanned_at),
    INDEX (etiket_id, tamu_undangan_id),
    FOREIGN KEY (undangan_id) REFERENCES undangan(id) ON DELETE CASCADE
)";

if ($mysqli->query($sql_scans)) {
    $output[] = "✅ <strong>Tabel barcode_scans</strong> - Created/Verified";
} else {
    $output[] = "❌ <strong>Tabel barcode_scans</strong> - Error: " . $mysqli->error;
    $success = false;
}

// 5. KONFIGURASI TABLE
$sql_config = "CREATE TABLE IF NOT EXISTS undangan_konfigurasi (
    id INT PRIMARY KEY AUTO_INCREMENT,
    undangan_id INT NOT NULL,
    setting_key VARCHAR(100),
    setting_value TEXT,
    KEY (undangan_id),
    UNIQUE KEY (undangan_id, setting_key),
    FOREIGN KEY (undangan_id) REFERENCES undangan(id) ON DELETE CASCADE
)";

if ($mysqli->query($sql_config)) {
    $output[] = "✅ <strong>Tabel undangan_konfigurasi</strong> - Created/Verified";
} else {
    $output[] = "❌ <strong>Tabel undangan_konfigurasi</strong> - Error: " . $mysqli->error;
    $success = false;
}

$output[] = "<hr style='border: 1px solid #d4af37; margin: 20px 0;'>";

// === DATA INSERTION ===
$output[] = "<h3 style='color: #d4af37;'>📊 Memasukkan Data Sample</h3>";

// Check if undangan already exists
$check = $mysqli->query("SELECT id FROM undangan WHERE invoice_number = 'INV-DEDE-BALI-2026'");
$undangan_id = 0;

if ($check && $check->num_rows > 0) {
    $row = $check->fetch_assoc();
    $undangan_id = $row['id'];
    $output[] = "✅ <strong>Undangan Dede Bali</strong> - Sudah ada (ID: $undangan_id)";
} else {
    $sql = "INSERT INTO undangan (invoice_number, nama_pemesan, nama_acara, tanggal_acara, lokasi_acara, tier, tema_warna, dress_code) 
            VALUES ('INV-DEDE-BALI-2026', 'Dede Santoso', 'Pernikahan Dede & Bali', '2026-03-14 17:00:00', 'Royal Bali Beach Club, Sanur', 'exclusive', 'dark-gold', 'Black Tie Optional')";
    
    if ($mysqli->query($sql)) {
        $undangan_id = $mysqli->insert_id;
        $output[] = "✅ <strong>Undangan Dede Bali</strong> - Berhasil dibuat (ID: $undangan_id)";
    } else {
        $output[] = "❌ <strong>Undangan Dede Bali</strong> - Error: " . $mysqli->error;
        $success = false;
    }
}

// Sample RSVP Data - hanya jika tabel kosong
$check_tamu = $mysqli->query("SELECT COUNT(*) as cnt FROM tamu_undangan WHERE undangan_id = $undangan_id");
$row_cnt = $check_tamu->fetch_assoc();

if ($row_cnt['cnt'] == 0 && $undangan_id > 0) {
    $sample_rsvp = [
        ['Krisna Wijaya', '628123456789', 'Hadir', 2],
        ['Siti Nurhaliza', '628987654321', 'Hadir', 1],
        ['Budi Santoso', '628555444333', 'Tidak Hadir', 0],
    ];
    
    foreach ($sample_rsvp as $rsvp) {
        $sql = "INSERT INTO tamu_undangan (undangan_id, nama_tamu, no_hp, respon, jumlah_tamu) 
                VALUES ($undangan_id, '" . $mysqli->real_escape_string($rsvp[0]) . "', '" . $rsvp[1] . "', '" . $rsvp[2] . "', " . $rsvp[3] . ")";
        
        if ($mysqli->query($sql)) {
            $output[] = "✅ RSVP: <strong>" . $rsvp[0] . "</strong> (" . $rsvp[2] . ")";
        } else {
            $output[] = "⚠️ RSVP " . $rsvp[0] . " - " . $mysqli->error;
        }
    }
} else if ($undangan_id > 0) {
    $output[] = "ℹ️ Data RSVP sudah ada atau tidak ada undangan";
}

$output[] = "<hr style='border: 1px solid #d4af37; margin: 20px 0;'>";

// === SUMMARY ===
if ($success) {
    $output[] = "<div style='background: #1a1f2e; border-left: 4px solid #00ff41; padding: 15px; margin-bottom: 20px;'>";
    $output[] = "<h3 style='color: #00ff41; margin: 0;'>✅ SETUP BERHASIL!</h3>";
    $output[] = "<p style='margin: 10px 0 0 0; color: #aaa;'>Semua tabel database sudah siap. Silakan <strong><a href='index.php' style='color: #d4af37;'>klik di sini</a></strong> untuk membuka undangan.</p>";
    $output[] = "</div>";
} else {
    $output[] = "<div style='background: #2a1a1a; border-left: 4px solid #ff4444; padding: 15px;'>";
    $output[] = "<h3 style='color: #ff4444; margin: 0;'>❌ Ada Kesalahan</h3>";
    $output[] = "<p style='margin: 10px 0 0 0; color: #ccc;'>Periksa error di atas dan coba lagi.</p>";
    $output[] = "</div>";
}

?><!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Undangan Dede - Bali Exclusive</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Courier New', monospace; 
            background: #0a0e27; 
            color: #ccc;
            padding: 20px;
            line-height: 1.8;
        }
        .container { 
            max-width: 900px; 
            margin: 0 auto; 
            background: #1a1f2e; 
            padding: 30px; 
            border-radius: 10px;
            border: 2px solid #d4af37;
        }
        h2, h3 { margin: 20px 0 10px 0; }
        a { color: #d4af37; text-decoration: none; }
        a:hover { text-decoration: underline; }
        div[style*="background"] { border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #d4af37; font-size: 2em; margin-bottom: 10px;">🎉 Undangan DEDE - Bali Exclusive</h1>
            <p style="color: #999;">Database Setup & Initialization</p>
        </div>
        
        <?php 
        foreach ($output as $line) {
            echo $line . "\n";
        }
        ?>
        
        <hr style="border: 1px solid #d4af37; margin-top: 30px;">
        
        <div style="margin-top: 20px; padding: 15px; background: #0f1419; border-radius: 5px;">
            <p style="color: #999; font-size: 0.9em;">
                <strong>Database Info:</strong><br>
                Server: localhost<br>
                Database: embun_visual<br>
                Tables Created: 5<br>
                Sample Data: 3 RSVP Records
            </p>
        </div>
    </div>
</body>
</html>
