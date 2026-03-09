<?php
/**
 * TEMPLATE BASIC - Simple dengan RSVP Form
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
$rsvp_submitted = false;

// Handle RSVP form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_rsvp'])) {
    $nama_tamu = mysqli_real_escape_string($conn, $_POST['nama_tamu']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $respon = $_POST['respon'] === 'hadir' ? 'Hadir' : 'Tidak Hadir';
    $jumlah = (int)$_POST['jumlah_hadir'];
    
    if (!empty($nama_tamu)) {
        // Cek & insert/update RSVP
        $cek = mysqli_query($conn, "SELECT id FROM tamu_undangan WHERE pesanan_id='$pesanan_id' AND nama_tamu='$nama_tamu'");
        
        if (mysqli_num_rows($cek) > 0) {
            mysqli_query($conn, "UPDATE tamu_undangan SET status_rsvp='$respon', jumlah_hadir='$jumlah', no_whatsapp='$no_hp' WHERE pesanan_id='$pesanan_id' AND nama_tamu='$nama_tamu'");
        } else {
            mysqli_query($conn, "INSERT INTO tamu_undangan (pesanan_id, nama_tamu, no_whatsapp, status_rsvp, jumlah_hadir) VALUES ('$pesanan_id', '$nama_tamu', '$no_hp', '$respon', '$jumlah')");
        }
        
        $rsvp_submitted = true;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pesanan['nama_pemesan']) ?> - Undangan Digital</title>
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
        
        .form-section {
            text-align: left;
            background: #f8f9fa;
            padding: 30px;
            border-radius: 15px;
            margin: 30px 0;
        }
        
        .form-section h3 {
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #555;
            font-size: 0.9rem;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.95rem;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 10px;
        }
        
        .radio-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .radio-item input[type="radio"] {
            width: auto;
            cursor: pointer;
        }
        
        .success-message {
            background: #dcfce7;
            border: 2px solid #86efac;
            color: #166534;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
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
        
        <?php if ($rsvp_submitted): ?>
        <div class="success-message">
            ✓ <strong>Terima kasih!</strong> Respons Anda telah tersimpan dengan baik.
        </div>
        <?php endif; ?>
        
        <div class="form-section">
            <h3>📝 Konfirmasi Kehadiran Anda</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Nama Anda *</label>
                    <input type="text" name="nama_tamu" placeholder="Masukkan nama lengkap..." required>
                </div>
                
                <div class="form-group">
                    <label>Nomor WhatsApp</label>
                    <input type="tel" name="no_hp" placeholder="Misal: 62812345678">
                </div>
                
                <div class="form-group">
                    <label>Status Kehadiran *</label>
                    <div class="radio-group">
                        <div class="radio-item">
                            <input type="radio" id="hadir" name="respon" value="hadir" checked="">
                            <label for="hadir" style="margin: 0; cursor: pointer;">✓ Saya Hadir</label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" id="tidak_hadir" name="respon" value="tidak_hadir">
                            <label for="tidak_hadir" style="margin: 0; cursor: pointer;">✗ Tidak Hadir</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group" id="jumlah_group">
                    <label>Jumlah Tamu yang Hadir</label>
                    <input type="number" name="jumlah_hadir" value="1" min="1" max="10">
                </div>
                
                <button type="submit" name="submit_rsvp" class="btn btn-primary" style="width: 100%; margin-top: 20px;">
                    Kirim Respons
                </button>
            </form>
        </div>
        
        <div class="footer">
            <p>Undangan Digital oleh <strong>Embun Visual</strong></p>
            <p>© 2026 - Semua Hak Dilindungi</p>
        </div>
    </div>
    
    <script>
        // Toggle jumlah hadir field
        const hadir = document.getElementById('hadir');
        const tidakHadir = document.getElementById('tidak_hadir');
        const jumlahGroup = document.getElementById('jumlah_group');
        
        function toggleJumlah() {
            jumlahGroup.style.display = hadir.checked ? 'block' : 'none';
        }
        
        hadir.addEventListener('change', toggleJumlah);
        tidakHadir.addEventListener('change', toggleJumlah);
        toggleJumlah();
    </script>
</body>
</html>
