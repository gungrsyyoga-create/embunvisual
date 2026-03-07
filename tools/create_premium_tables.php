<?php
/*
 * Migration Script: Create Premium Dashboard Tables
 * Run once via browser: http://localhost/embunvisual/tools/create_premium_tables.php
 * DELETE this file after running!
 */
include '../config.php';

$queries = [
    // Table 1: klien_premium - credentials for premium client login
    "CREATE TABLE IF NOT EXISTS `klien_premium` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `pesanan_id` INT(11) NOT NULL,
        `username` VARCHAR(100) NOT NULL,
        `password` VARCHAR(255) NOT NULL,
        `is_active` TINYINT(1) NOT NULL DEFAULT 1,
        `created_by` INT(11) DEFAULT NULL,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `unique_pesanan` (`pesanan_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    // Table 2: pesan_proyek - chat messages between client and staff
    "CREATE TABLE IF NOT EXISTS `pesan_proyek` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `pesanan_id` INT(11) NOT NULL,
        `pengirim` ENUM('klien','admin') NOT NULL,
        `nama_pengirim` VARCHAR(100) NOT NULL,
        `pesan` TEXT NOT NULL,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `idx_pesanan_id` (`pesanan_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    // Table 3: tamu_undangan - guest list + RSVP tracking
    "CREATE TABLE IF NOT EXISTS `tamu_undangan` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `pesanan_id` INT(11) NOT NULL,
        `nama_tamu` VARCHAR(200) NOT NULL,
        `no_whatsapp` VARCHAR(30) DEFAULT NULL,
        `status_rsvp` ENUM('Belum Konfirmasi','Hadir','Tidak Hadir') NOT NULL DEFAULT 'Belum Konfirmasi',
        `jumlah_hadir` INT(11) DEFAULT 0,
        `status_kirim` ENUM('Belum','Terkirim') NOT NULL DEFAULT 'Belum',
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `idx_pesanan_id` (`pesanan_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
];

$success = [];
$errors = [];

foreach ($queries as $q) {
    if (mysqli_query($conn, $q)) {
        $success[] = "OK: " . substr($q, 0, 60) . "...";
    } else {
        $errors[] = "ERROR: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>DB Migration</title>
    <style>
        body { font-family: monospace; padding: 30px; background: #f8f9fa; }
        .ok { color: #16a34a; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 10px; margin: 10px 0; border-radius: 6px; }
        .err { color: #dc2626; background: #fef2f2; border: 1px solid #fecaca; padding: 10px; margin: 10px 0; border-radius: 6px; }
        h2 { margin-bottom: 20px; }
    </style>
</head>
<body>
    <h2>🗄️ Migration: Premium Dashboard Tables</h2>
    <?php foreach ($success as $s): ?>
        <div class="ok">✅ <?= htmlspecialchars($s) ?></div>
    <?php endforeach; ?>
    <?php foreach ($errors as $e): ?>
        <div class="err">❌ <?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>
    <?php if (empty($errors)): ?>
        <div class="ok" style="margin-top: 20px; font-size: 1.1rem;">
            ✅ <strong>Semua tabel berhasil dibuat!</strong><br>
            ⚠️ Segera hapus file ini: <code>tools/create_premium_tables.php</code>
        </div>
    <?php endif; ?>
</body>
</html>
