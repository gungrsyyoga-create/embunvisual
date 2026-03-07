<?php
include '../config.php';
$queries = [
    "ALTER TABLE `pesan_proyek` ADD COLUMN IF NOT EXISTS `gambar_path` VARCHAR(255) DEFAULT NULL",
    "CREATE TABLE IF NOT EXISTS `chat_uploads` (id INT PRIMARY KEY)" // dummy check
];
$results = [];
// Only run what we need
$r = mysqli_query($conn, "ALTER TABLE `pesan_proyek` ADD COLUMN IF NOT EXISTS `gambar_path` VARCHAR(255) DEFAULT NULL");
$err = mysqli_error($conn);
?>
<!doctype html><html><body style="font-family:monospace;padding:30px">
<h2>🔧 DB Patch: Add gambar_path</h2>
<?php if (!$err || strpos($err, 'Duplicate') !== false): ?>
    <div style="background:#f0fdf4;border:1px solid #bbf7d0;padding:12px;border-radius:8px;color:#15803d;">✅ gambar_path column added (or already exists). <br><strong>Hapus file ini sekarang!</strong></div>
<?php else: ?>
    <div style="background:#fef2f2;border:1px solid #fecaca;padding:12px;border-radius:8px;color:#dc2626;">❌ Error: <?= htmlspecialchars($err) ?></div>
<?php endif; ?>
</body></html>
