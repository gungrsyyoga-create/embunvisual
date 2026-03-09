<?php
/**
 * Admin Dashboard - Kelola RSVP & E-Tickets
 */

$mysqli = new mysqli("localhost", "root", "", "embun_visual");
if ($mysqli->connect_error) die("Connection failed: " . $mysqli->connect_error);
mysqli_set_charset($mysqli, "utf8");

$undangan_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Get undangan data
$undangan_result = $mysqli->query("SELECT * FROM undangan WHERE id = $undangan_id");
$undangan = $undangan_result ? $undangan_result->fetch_assoc() : null;

if (!$undangan) {
    die("<div style='text-align: center; padding: 50px; color: #f44336;'><h2>❌ Undangan tidak ditemukan</h2><p>ID: $undangan_id</p></div>");
}

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
$scans = $mysqli->query("SELECT * FROM barcode_scans WHERE undangan_id = $undangan_id ORDER BY scanned_at DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - RSVP & E-Tickets</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f5f5f5; }
        
        .header {
            background: linear-gradient(135deg, #0a0e27 0%, #1a1f3a 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
            border-bottom: 3px solid #d4af37;
        }
        
        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            color: #d4af37;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #d4af37;
        }
        
        .stat-card h3 {
            color: #666;
            font-size: 0.85rem;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #d4af37;
        }
        
        .section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .section h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.5rem;
            border-bottom: 2px solid #d4af37;
            padding-bottom: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            background: #f9f9f9;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #d4af37;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        
        tr:hover {
            background: #f9f9f9;
        }
        
        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .badge.hadir {
            background: #d4edda;
            color: #155724;
        }
        
        .badge.tidak-hadir {
            background: #f8d7da;
            color: #721c24;
        }
        
        .badge.generated {
            background: #cfe2ff;
            color: #084298;
        }
        
        .badge.scanned {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .no-data {
            text-align: center;
            color: #999;
            padding: 30px;
        }
        
        .info-box {
            background: #e3f2fd;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            color: #1565c0;
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
            margin-right: 5px;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }
            
            table {
                font-size: 0.85rem;
            }
            
            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Admin Dashboard - E-Tickets</h1>
        <p><?php echo $undangan['nama_acara']; ?></p>
        <p style="font-size: 0.9rem; opacity: 0.8; margin-top: 10px;"><?php echo $undangan['invoice_number']; ?></p>
    </div>
    
    <div class="container">
        <div class="info-box">
            📌 <strong><?php echo $undangan['nama_pemesan']; ?></strong> | 
            📅 <?php echo date('d F Y H:i', strtotime($undangan['tanggal_acara'])); ?> | 
            📍 <?php echo $undangan['lokasi_acara']; ?>
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
                        <td><strong><?php echo $row['nama_tamu']; ?></strong></td>
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
            
            <?php 
            $scan_rows = [];
            if ($scans && $scans->num_rows > 0) {
                while ($row = $scans->fetch_assoc()) {
                    $scan_rows[] = $row;
                }
            }
            ?>
            
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
                        <td><?php echo $scan['scanner_name']; ?></td>
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
        
        <!-- ACTIONS -->
        <div class="section">
            <h2>🔧 Aksi</h2>
            <a href="mailto:admin@embunvisual.com" class="btn">📧 Export ke Email</a>
            <a href="javascript:window.print()" class="btn">🖨️ Print</a>
            <a href="undangan/exclusive/dede/index.php" class="btn">👁️ Lihat Undangan</a>
        </div>
    </div>
</body>
</html>
<?php $mysqli->close(); ?>
