<?php
include 'config.php';

$sql = "CREATE TABLE IF NOT EXISTS audit_logs (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    admin_id INT(11) NOT NULL,
    action_type VARCHAR(100) NOT NULL,
    target_id VARCHAR(50) NOT NULL,
    keterangan TEXT NOT NULL,
    screenshot_path VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql)) {
    echo "Tabel audit_logs berhasil dibuat atau sudah ada.";
} else {
    echo "Gagal membuat tabel audit_logs: " . mysqli_error($conn);
}
?>
