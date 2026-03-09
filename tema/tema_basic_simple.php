<?php
/**
 * TEMPLATE BASIC - Undangan Digital Sederhana
 * Akses: /undangan/basic/{nama-klien}/index.php
 */
session_start();
include dirname(dirname(dirname(__FILE__))) . '/config.php';

$pesanan_id = isset($_GET['pid']) ? (int)$_GET['pid'] : 1;

// Ambil data pesanan
$q = mysqli_query($conn, "SELECT p.*, k.nama_tema FROM pesanan p LEFT JOIN katalog_tema k ON p.tema_id=k.id WHERE p.id='$pesanan_id'");
$pesanan = mysqli_fetch_assoc($q);

if (!$pesanan) { die("Pesanan tidak ditemukan."); }

$nama_klien = htmlspecialchars($pesanan['nama_pemesan']);
$tanggal_acara = date('d M Y', strtotime($pesanan['tanggal_acara']));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $nama_klien ?> - Undangan Digital</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            max-width: 600px;
            width: 100%;
            padding: 50px 30px;
            text-align: center;
        }
        
        .header {
            margin-bottom: 30px;
        }
        
        .title {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 10px;
            font-weight: 700;
        }
        
        .subtitle {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 20px;
        }
        
        .divider {
            width: 60px;
            height: 3px;
            background: #667eea;
            margin: 20px auto;
            border-radius: 2px;
        }
        
        .date-section {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 15px;
            margin: 30px 0;
        }
        
        .date-label {
            font-size: 0.9rem;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        
        .date-value {
            font-size: 1.8rem;
            color: #333;
            font-weight: 600;
        }
        
        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 40px;
            justify-content: center;
        }
        
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }
        
        .btn-secondary:hover {
            background: #e0e0e0;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #999;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="title">🎉 Undangan Digital</h1>
            <p class="subtitle">Kami dengan gembira mengundang Anda</p>
            <div class="divider"></div>
        </div>
        
        <h2 style="font-size: 1.8rem; color: #333; margin: 20px 0;">
            <?= $nama_klien ?>
        </h2>
        
        <div class="date-section">
            <div class="date-label">📅 Tanggal Acara</div>
            <div class="date-value"><?= $tanggal_acara ?></div>
        </div>
        
        <p style="color: #666; font-size: 1rem; line-height: 1.6; margin: 20px 0;">
            Kehadiran Anda adalah kebahagiaan bagi kami. Silakan konfirmasi kehadiran Anda melalui tombol di bawah.
        </p>
        
        <div class="button-group">
            <a href="rsvp.php?pesanan=<?= $pesanan_id ?>&respon=hadir" class="btn btn-primary">
                ✓ Saya Hadir
            </a>
            <a href="rsvp.php?pesanan=<?= $pesanan_id ?>&respon=tidak_hadir" class="btn btn-secondary">
                ✗ Tidak Hadir
            </a>
        </div>
        
        <div class="footer">
            <p>Undangan Digital oleh <strong>Embun Visual</strong></p>
            <p>© 2026 - Semua Hak Dilindungi</p>
        </div>
    </div>
</body>
</html>
