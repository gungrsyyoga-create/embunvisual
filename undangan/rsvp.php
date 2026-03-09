<?php
/**
 * undangan/rsvp.php
 * Handler untuk RSVP dari semua tier (basic, premium, exclusive)
 * Akses: /undangan/{tier}/{nama-klien}/rsvp.php
 */
session_start();
include dirname(dirname(__FILE__)) . '/config.php';

$pesanan_id = (int)($_GET['pesanan'] ?? $_POST['pesanan'] ?? 0);
$respon = $_GET['respon'] ?? $_POST['respon'] ?? '';
$nama_tamu = mysqli_real_escape_string($conn, $_POST['nama_tamu'] ?? 'Tamu');
$no_hp = mysqli_real_escape_string($conn, $_POST['no_hp'] ?? '');
$jumlah = (int)($_POST['jumlah_hadir'] ?? 1);

if (!$pesanan_id || !$respon) {
    die('<h3>Error: Parameter tidak lengkap</h3>');
}

// Validasi respons
$respon_valid = ($respon === 'hadir') ? 'Hadir' : (($respon === 'tidak_hadir') ? 'Tidak Hadir' : null);
if (!$respon_valid) {
    die('<h3>Error: Respons tidak valid</h3>');
}

// Ambil data pesanan untuk verifikasi
$q_pesanan = mysqli_query($conn, "SELECT * FROM pesanan WHERE id='$pesanan_id'");
if (!$q_pesanan || mysqli_num_rows($q_pesanan) == 0) {
    die('<h3>Error: Pesanan tidak ditemukan</h3>');
}

$pesanan_data = mysqli_fetch_assoc($q_pesanan);

// Cek apakah sudah ada RSVP sebelumnya
$q_cek_rsvp = mysqli_query($conn, "SELECT id FROM tamu_undangan WHERE pesanan_id='$pesanan_id' AND nama_tamu='$nama_tamu'");

if (mysqli_num_rows($q_cek_rsvp) > 0) {
    // Update existing RSVP
    mysqli_query($conn, "UPDATE tamu_undangan SET status_rsvp='$respon_valid', jumlah_hadir='$jumlah', no_whatsapp='$no_hp' WHERE pesanan_id='$pesanan_id' AND nama_tamu='$nama_tamu'");
    $status_msg = 'Respons Anda telah <strong>diperbarui</strong>';
} else {
    // Insert RSVP baru
    mysqli_query($conn, "INSERT INTO tamu_undangan (pesanan_id, nama_tamu, no_whatsapp, status_rsvp, jumlah_hadir) VALUES ('$pesanan_id', '$nama_tamu', '$no_hp', '$respon_valid', '$jumlah')");
    $status_msg = 'Terima kasih! Respons Anda telah <strong>tercatat</strong>';
}

// Log activity
catatLog($conn, 0, 'RSVP Tamu', $pesanan_data['invoice'], "RSVP dari $nama_tamu: $respon_valid ($jumlah orang)", null);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSVP Konfirmasi - Embun Visual</title>
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
            max-width: 500px;
            width: 100%;
            padding: 60px 40px;
            text-align: center;
        }
        
        .success-icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }
        
        h1 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 15px;
        }
        
        .message {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 10px;
            line-height: 1.6;
        }
        
        .details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin: 30px 0;
            text-align: left;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        .detail-item:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 600;
            color: #555;
        }
        
        .detail-value {
            color: #333;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 20px;
            transition: all 0.3s;
        }
        
        .btn:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">✅</div>
        <h1>Terima Kasih!</h1>
        <p class="message"><?= $status_msg ?></p>
        
        <div class="details">
            <div class="detail-item">
                <span class="detail-label">📅 Acara:</span>
                <span class="detail-value"><?= date('d M Y', strtotime($pesanan_data['tanggal_acara'])) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">👤 Nama:</span>
                <span class="detail-value"><?= htmlspecialchars($nama_tamu) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">📍 Respons:</span>
                <span class="detail-value" style="font-weight: 700; color: <?= $respon_valid === 'Hadir' ? '#22c55e' : '#ef4444' ?>;">
                    <?= $respon_valid === 'Hadir' ? '✓ Saya Hadir' : '✗ Tidak Hadir' ?>
                </span>
            </div>
            <?php if ($respon_valid === 'Hadir' && $jumlah > 1): ?>
            <div class="detail-item">
                <span class="detail-label">👥 Jumlah Tamu:</span>
                <span class="detail-value"><?= $jumlah ?> orang</span>
            </div>
            <?php endif; ?>
        </div>
        
        <p style="color: #999; font-size: 0.9rem;">
            Anda dapat kembali ke undangan digital untuk mengubah respons kapan saja sebelum hari H.
        </p>
        
        <a href="index.php" class="btn">← Kembali ke Undangan</a>
    </div>
</body>
</html>
