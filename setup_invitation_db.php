<?php
/**
 * Setup Database Tables untuk Undangan System
 * Automatic table creation & schema setup
 */

$mysqli = new mysqli("localhost", "root", "", "embun_visual");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
mysqli_set_charset($mysqli, "utf8");

$output = "<style>
    body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
    .container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .step { margin: 20px 0; padding: 15px; border-left: 4px solid #4CAF50; background: #f9f9f9; }
    .step.error { border-left-color: #f44336; background: #ffebee; }
    .step.info { border-left-color: #2196F3; background: #e3f2fd; }
    .success { color: #4CAF50; font-weight: bold; }
    .error { color: #f44336; font-weight: bold; }
    h2 { color: #333; }
    code { background: #f0f0f0; padding: 2px 6px; border-radius: 3px; font-family: monospace; }
</style>";

$output .= "<div class='container'><h1>🗂️ Setup Undangan Database</h1>";

// ===== TABLE 1: undangan (Master Invitation Data) =====
$table1 = "CREATE TABLE IF NOT EXISTS undangan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_number VARCHAR(100) UNIQUE NOT NULL,
    nama_pemesan VARCHAR(255) NOT NULL,
    nama_acara VARCHAR(255) NOT NULL,
    tanggal_acara DATETIME NOT NULL,
    lokasi_acara VARCHAR(255) NOT NULL,
    tier ENUM('basic', 'premium', 'exclusive') DEFAULT 'premium',
    folder_path VARCHAR(255),
    tema_warna VARCHAR(50),
    dress_code VARCHAR(255),
    deskripsi_acara LONGTEXT,
    catering_info VARCHAR(255),
    akomodasi_info VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($mysqli->query($table1)) {
    $output .= "<div class='step'><span class='success'>✓ Table 'undangan' created/verified</span></div>";
} else {
    $output .= "<div class='step error'><span class='error'>✗ Error creating undangan table:</span> " . $mysqli->error . "</div>";
}

// ===== TABLE 2: tamu_undangan (RSVP Responses) =====
$table2 = "CREATE TABLE IF NOT EXISTS tamu_undangan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    undangan_id INT NOT NULL,
    nama_tamu VARCHAR(255) NOT NULL,
    no_hp VARCHAR(20),
    respon ENUM('Hadir', 'Tidak Hadir') NOT NULL,
    jumlah_tamu INT DEFAULT 1,
    catatan TEXT,
    status_etiket ENUM('pending', 'generated', 'scanned') DEFAULT 'pending',
    etiket_number VARCHAR(100) UNIQUE,
    etiket_generated_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (undangan_id) REFERENCES undangan(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($mysqli->query($table2)) {
    $output .= "<div class='step'><span class='success'>✓ Table 'tamu_undangan' created/verified</span></div>";
} else {
    $output .= "<div class='step error'><span class='error'>✗ Error creating tamu_undangan table:</span> " . $mysqli->error . "</div>";
}

// ===== TABLE 3: etiket (E-Ticket Management) =====
$table3 = "CREATE TABLE IF NOT EXISTS etiket (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tamu_undangan_id INT NOT NULL,
    undangan_id INT NOT NULL,
    etiket_number VARCHAR(100) UNIQUE NOT NULL,
    barcode_value VARCHAR(100) UNIQUE NOT NULL,
    status ENUM('active', 'used', 'cancelled') DEFAULT 'active',
    generated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    used_at DATETIME,
    scanned_by VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tamu_undangan_id) REFERENCES tamu_undangan(id) ON DELETE CASCADE,
    FOREIGN KEY (undangan_id) REFERENCES undangan(id) ON DELETE CASCADE,
    INDEX (etiket_number),
    INDEX (barcode_value)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($mysqli->query($table3)) {
    $output .= "<div class='step'><span class='success'>✓ Table 'etiket' created/verified</span></div>";
} else {
    $output .= "<div class='step error'><span class='error'>✗ Error creating etiket table:</span> " . $mysqli->error . "</div>";
}

// ===== TABLE 4: barcode_scans (Check-in Logs) =====
$table4 = "CREATE TABLE IF NOT EXISTS barcode_scans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    etiket_id INT,
    tamu_undangan_id INT,
    undangan_id INT,
    barcode_value VARCHAR(100),
    scanned_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    scanner_name VARCHAR(255),
    scanner_ip VARCHAR(45),
    FOREIGN KEY (etiket_id) REFERENCES etiket(id) ON DELETE SET NULL,
    FOREIGN KEY (tamu_undangan_id) REFERENCES tamu_undangan(id) ON DELETE SET NULL,
    FOREIGN KEY (undangan_id) REFERENCES undangan(id) ON DELETE SET NULL,
    INDEX (barcode_value),
    INDEX (scanned_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($mysqli->query($table4)) {
    $output .= "<div class='step'><span class='success'>✓ Table 'barcode_scans' created/verified</span></div>";
} else {
    $output .= "<div class='step error'><span class='error'>✗ Error creating barcode_scans table:</span> " . $mysqli->error . "</div>";
}

// ===== TABLE 5: undangan_konfigurasi (invitation Settings) =====
$table5 = "CREATE TABLE IF NOT EXISTS undangan_konfigurasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    undangan_id INT NOT NULL,
    setting_key VARCHAR(100) NOT NULL,
    setting_value LONGTEXT,
    UNIQUE KEY unique_undangan_setting (undangan_id, setting_key),
    FOREIGN KEY (undangan_id) REFERENCES undangan(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($mysqli->query($table5)) {
    $output .= "<div class='step'><span class='success'>✓ Table 'undangan_konfigurasi' created/verified</span></div>";
} else {
    $output .= "<div class='step error'><span class='error'>✗ Error creating undangan_konfigurasi table:</span> " . $mysqli->error . "</div>";
}

// ===== INSERT SAMPLE DATA FOR DEDE =====
$output .= "<h2>📝 Sample Data Bali Dede</h2>";

$check_dede = $mysqli->query("SELECT id FROM undangan WHERE invoice_number = 'INV-DEDE-BALI-2026'");
if ($check_dede->num_rows == 0) {
    $insert_dede = "INSERT INTO undangan (
        invoice_number, nama_pemesan, nama_acara, tanggal_acara, lokasi_acara, tier, 
        tema_warna, dress_code, deskripsi_acara, catering_info, akomodasi_info
    ) VALUES (
        'INV-DEDE-BALI-2026',
        'Dede & Prasetya',
        'Pernikahan Eksklusif di Bali',
        '2026-03-14 17:00:00',
        'Tirtha Cafe Uluwatu, Bali',
        'exclusive',
        'bali_gold',
        'Formal / Tradisional Bali',
        'Upacara Pernikahan, Resepsi, dan Pertunjukan Budaya Bali',
        'Hidangan Bali Autentik & Internasional Premium',
        'GH Bali Resort - Paket Spesial untuk Tamu'
    )";
    
    if ($mysqli->query($insert_dede)) {
        $undangan_id = $mysqli->insert_id;
        $output .= "<div class='step'><span class='success'>✓ Sample undangan Dede created (ID: $undangan_id)</span></div>";
        
        // Insert sample RSVP
        $insert_rsvp = "INSERT INTO tamu_undangan (undangan_id, nama_tamu, no_hp, respon, jumlah_tamu)
        VALUES 
        ($undangan_id, 'Krisna Wijaya', '6281234567890', 'Hadir', 2),
        ($undangan_id, 'Siti Nurhaliza', '6282345678901', 'Hadir', 1),
        ($undangan_id, 'Ahmad Rahman', '6283456789012', 'Tidak Hadir', 1)";
        
        if ($mysqli->query($insert_rsvp)) {
            $output .= "<div class='step'><span class='success'>✓ Sample RSVP data inserted (3 tamu)</span></div>";
        }
    } else {
        $output .= "<div class='step error'><span class='error'>✗ Error inserting sample data:</span> " . $mysqli->error . "</div>";
    }
} else {
    $output .= "<div class='step info'><strong>ℹ️ Undangan DEDE bereits exists - skipping sample data</strong></div>";
}

// Summary
$output .= "<h2>✅ Setup Complete!</h2>";
$output .= "<div class='step info'>
    <strong>Database Tables Created:</strong><br>
    ✓ undangan - Master invitation data<br>
    ✓ tamu_undangan - RSVP responses<br>
    ✓ etiket - E-ticket management<br>
    ✓ barcode_scans - Check-in logs<br>
    ✓ undangan_konfigurasi - Settings per invitation
</div>";

$output .= "</div>";

echo $output;
$mysqli->close();
?>
