<?php
/**
 * Admin Dashboard Lokal untuk Undangan DEDE
 */

require_once 'config.php';

// Check if setup is needed
if (needs_setup()) {
    die("<div style='text-align: center; padding: 50px; background: #0a0e27; color: #f44336; font-family: sans-serif; min-height: 100vh; display: flex; flex-direction: column; justify-content: center;'><h2>⚙️ Setup Diperlukan</h2><p style='margin: 20px 0;'>Database belum diinisialisasi.</p><p style='margin: 10px 0;'><a href='setup.php' style='background: #d4af37; color: #0a0e27; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: bold; display: inline-block;'>Jalankan Setup</a></p></div>");
}

$undangan = get_undangan();
if (!$undangan) {
    die("<div style='text-align: center; padding: 50px; color: #f44336; font-family: sans-serif;'><h2>❌ Undangan tidak ditemukan</h2><p>Silakan jalankan <a href='setup.php'>setup.php</a> terlebih dahulu</p></div>");
}

$undangan_id = $undangan['id'];

// Get RSVP list
$rsvp_query = "SELECT * FROM tamu_undangan WHERE undangan_id = $undangan_id ORDER BY respon DESC, created_at DESC";
$rsvp_result = $mysqli->query($rsvp_query);
$rsvp_rows = [];
if ($rsvp_result) {
    while ($row = $rsvp_result->fetch_assoc()) {
        $rsvp_rows[] = $row;
    }
}

// Get stats
$stats_result = $mysqli->query("SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN respon='Hadir' THEN 1 ELSE 0 END) as hadir,
    SUM(CASE WHEN respon='Tidak Hadir' THEN 1 ELSE 0 END) as tidak_hadir,
    SUM(CASE WHEN respon='Hadir' THEN jumlah_tamu ELSE 0 END) as total_tamu,
    SUM(CASE WHEN status_etiket='generated' THEN 1 ELSE 0 END) as etiket_generated
    FROM tamu_undangan WHERE undangan_id = $undangan_id");
$stats = $stats_result ? $stats_result->fetch_assoc() : ['total' => 0, 'hadir' => 0, 'tidak_hadir' => 0, 'total_tamu' => 0, 'etiket_generated' => 0];

// Get check-in logs
$scan_query = "SELECT * FROM barcode_scans WHERE undangan_id = $undangan_id ORDER BY scanned_at DESC LIMIT 10";
$scans = $mysqli->query($scan_query);
$scan_rows = [];
if ($scans) {
    while ($row = $scans->fetch_assoc()) {
        $scan_rows[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Undangan DEDE</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', 'Segoe UI', sans-serif; background: #f5f5f5; }
        
        .header {
            background: linear-gradient(135deg, #0a0e27 0%, #1a1f2e 100%);
            color: #fff;
            padding: 30px;
            text-align: center;
            border-bottom: 3px solid #d4af37;
        }
        
        .header h1 { font-size: 2em; margin-bottom: 5px; }
        .header p { font-size: 0.95em; opacity: 0.9; }
        
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        
        .info-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            color: #856404;
            font-size: 0.9em;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin: 30px 0;
        }
        
        .stat-card {
            background: white;
            border-left: 4px solid #d4af37;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-card h3 { font-size: 0.85em; color: #666; margin-bottom: 10px; }
        .stat-value { font-size: 2.5em; font-weight: bold; color: #d4af37; }
        
        .section {
            background: white;
            padding: 25px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .section h2 { color: #0a0e27; margin-bottom: 20px; font-size: 1.5em; }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            background: #f8f9fa;
            color: #0a0e27;
            text-align: left;
            padding: 12px;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
        }
        
        tr:hover { background: #f8f9fa; }
        
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 600;
        }
        
        .badge.hadir {
            background: #c8e6c9;
            color: #2e7d32;
        }
        
        .badge.tidak-hadir {
            background: #ffcdd2;
            color: #c62828;
        }
        
        .badge.generated {
            background: #b3e5fc;
            color: #0277bd;
        }
        
        .no-data {
            text-align: center;
            color: #999;
            padding: 40px 20px;
        }
        
        .btn {
            display: inline-block;
            padding: 8px 15px;
            background: #d4af37;
            color: #0a0e27;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
        }
        
        .btn:hover { opacity: 0.9; }
        
        .action-buttons {
            margin-bottom: 20px;
        }
        
        .action-buttons .btn {
            margin-right: 10px;
        }
        
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }
            table { font-size: 0.9em; }
            th, td { padding: 8px; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Dashboard Admin - Undangan DEDE</h1>
        <p><?php echo $undangan['nama_acara']; ?></p>
        <p style="font-size: 0.85rem; opacity: 0.7; margin-top: 10px;"><?php echo $undangan['invoice_number']; ?></p>
    </div>
    
    <div class="container">
        <div class="info-box">
            📌 <strong><?php echo $undangan['nama_pemesan']; ?></strong> | 
            📅 <?php echo date('d F Y H:i', strtotime($undangan['tanggal_acara'])); ?> | 
            📍 <?php echo $undangan['lokasi_acara']; ?>
        </div>
        
        <div class="action-buttons">
            <a href="index.php" class="btn">👁️ Lihat Undangan</a>
            <a href="setup.php" class="btn">🔧 Re-setup Database</a>
            <a href="javascript:window.print()" class="btn">🖨️ Cetak</a>
        </div>
        
        <!-- STATISTICS -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>👥 Total RSVP</h3>
                <div class="stat-value"><?php echo (int)$stats['total']; ?></div>
            </div>
            <div class="stat-card">
                <h3>✓ Akan Hadir</h3>
                <div class="stat-value"><?php echo (int)$stats['hadir']; ?></div>
            </div>
            <div class="stat-card">
                <h3>✗ Tidak Hadir</h3>
                <div class="stat-value"><?php echo (int)$stats['tidak_hadir']; ?></div>
            </div>
            <div class="stat-card">
                <h3>👫 Total Tamu</h3>
                <div class="stat-value"><?php echo (int)$stats['total_tamu']; ?></div>
            </div>
            <div class="stat-card">
                <h3>🎫 E-Tiket Generated</h3>
                <div class="stat-value"><?php echo (int)$stats['etiket_generated']; ?></div>
            </div>
            <div class="stat-card">
                <h3>📱 Scan Rate</h3>
                <div class="stat-value">
                    <?php 
                    if ($stats['total'] > 0) {
                        echo round(($stats['etiket_generated'] / $stats['total']) * 100) . '%';
                    } else {
                        echo '0%';
                    }
                    ?>
                </div>
            </div>
        </div>
        
        <!-- RSVP TABLE -->
        <div class="section">
            <h2>📋 Daftar RSVP (Total: <?php echo (int)$stats['total']; ?>)</h2>
            
            <?php if ((int)$stats['total'] > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Tamu</th>
                        <th>No. WA</th>
                        <th>Status</th>
                        <th>Jumlah</th>
                        <th>E-Tiket</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach ($rsvp_rows as $row): 
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><strong><?php echo htmlspecialchars($row['nama_tamu']); ?></strong></td>
                        <td>
                            <?php 
                            if ($row['no_hp']) {
                                echo '<a href="https://api.whatsapp.com/send?phone=' . $row['no_hp'] . '" target="_blank" style="color: #25D366; text-decoration: none;">📱 ' . $row['no_hp'] . '</a>';
                            } else {
                                echo '<span style="color: #999;">-</span>';
                            }
                            ?>
                        </td>
                        <td>
                            <span class="badge <?php echo $row['respon'] === 'Hadir' ? 'hadir' : 'tidak-hadir'; ?>">
                                <?php echo $row['respon']; ?>
                            </span>
                        </td>
                        <td><?php echo $row['jumlah_tamu']; ?> org</td>
                        <td>
                            <?php if ($row['status_etiket'] === 'generated'): ?>
                                <span class="badge generated">
                                    ✓ <?php echo substr($row['etiket_number'], 0, 10); ?>...
                                </span>
                            <?php else: ?>
                                <span style="color: #999; font-size: 0.8rem;">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-size: 0.85rem; color: #999;">
                            <?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="no-data">
                <p>Belum ada data RSVP</p>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- RECENT CHECK-INS -->
        <div class="section">
            <h2>🔍 Riwayat Check-in Terbaru</h2>
            
            <?php if (count($scan_rows) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Waktu Scan</th>
                        <th>Nama Tamu</th>
                        <th>E-Tiket</th>
                        <th>Barcode</th>
                        <th>Pemindai</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($scan_rows as $scan): ?>
                    <tr>
                        <td><?php echo date('d/m/Y H:i:s', strtotime($scan['scanned_at'])); ?></td>
                        <td>
                            <?php 
                            $tamu_result = $mysqli->query("SELECT nama_tamu FROM tamu_undangan WHERE id = " . (int)$scan['tamu_undangan_id']);
                            $tamu_data = $tamu_result ? $tamu_result->fetch_assoc() : null;
                            echo $tamu_data['nama_tamu'] ?? '-';
                            ?>
                        </td>
                        <td>
                            <?php 
                            $etiket_result = $mysqli->query("SELECT etiket_number FROM etiket WHERE id = " . (int)$scan['etiket_id']);
                            $etiket_data = $etiket_result ? $etiket_result->fetch_assoc() : null;
                            echo $etiket_data ? substr($etiket_data['etiket_number'], 0, 15) . '...' : '-';
                            ?>
                        </td>
                        <td style="font-family: monospace; font-size: 0.8rem;">
                            <?php echo substr($scan['barcode_value'], 0, 12); ?>...
                        </td>
                        <td><?php echo htmlspecialchars($scan['scanner_name']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="no-data">
                <p>Belum ada check-in</p>
            </div>
            <?php endif; ?>
        </div>
        
        <div style="text-align: center; margin: 30px 0; color: #999; font-size: 0.9em;">
            <p>Dashboard Undangan DEDE © 2026 | Self-contained System</p>
        </div>
    </div>
</body>
</html>
