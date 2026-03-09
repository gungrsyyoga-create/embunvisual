<?php
/**
 * TEMPLATE PREMIUM - Elegant dengan Countdown + RSVP Form
 * Akses: /undangan/premium/{nama-klien}/index.php
 */
session_start();
include dirname(dirname(dirname(__FILE__))) . '/config.php';

$pesanan_id = isset($_GET['pid']) ? (int)$_GET['pid'] : 1;

// Ambil data pesanan
$q = mysqli_query($conn, "SELECT p.*, k.nama_tema FROM pesanan p LEFT JOIN katalog_tema k ON p.tema_id=k.id WHERE p.id='$pesanan_id'");
$pesanan = mysqli_fetch_assoc($q);

if (!$pesanan) { die("Pesanan tidak ditemukan."); }

$nama_klien = htmlspecialchars($pesanan['nama_pemesan']);
$tanggal_acara = date('d M Y H:i', strtotime($pesanan['tanggal_acara']));
$hari_h = $pesanan['tanggal_acara'];
$rsvp_submitted = false;

// Handle RSVP form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_rsvp'])) {
    $nama_tamu = mysqli_real_escape_string($conn, $_POST['nama_tamu']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $respon = $_POST['respon'] === 'hadir' ? 'Hadir' : 'Tidak Hadir';
    $jumlah = (int)$_POST['jumlah_hadir'];
    
    if (!empty($nama_tamu)) {
        $cek = mysqli_query($conn, "SELECT id FROM tamu_undangan WHERE pesanan_id='$pesanan_id' AND nama_tamu='$nama_tamu'");
        
        if (mysqli_num_rows($cek) > 0) {
            mysqli_query($conn, "UPDATE tamu_undangan SET status_rsvp='$respon', jumlah_hadir='$jumlah', no_whatsapp='$no_hp' WHERE pesanan_id='$pesanan_id' AND nama_tamu='$nama_tamu'");
        } else {
            mysqli_query($conn, "INSERT INTO tamu_undangan (pesanan_id, nama_tamu, no_whatsapp, status_rsvp, jumlah_hadir) VALUES ('$pesanan_id', '$nama_tamu', '$no_hp', '$respon', '$jumlah')");
        }
        
        $rsvp_submitted = true;
    }
}

// Hitung statistik RSVP
$hadir_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM tamu_undangan WHERE pesanan_id='$pesanan_id' AND status_rsvp='Hadir'");
$hadir_count = mysqli_fetch_assoc($hadir_result)['total'];

$tidak_hadir_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM tamu_undangan WHERE pesanan_id='$pesanan_id' AND status_rsvp='Tidak Hadir'");
$tidak_hadir_count = mysqli_fetch_assoc($tidak_hadir_result)['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pesanan['nama_pemesan']) ?> - Undangan Elegan</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital@0;1&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0,0,0,0.3);
        }
        
        .hero {
            background: linear-gradient(135deg, #d4af37 0%, #b8930f 100%);
            padding: 60px 30px;
            text-align: center;
            color: white;
        }
        
        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            font-style: italic;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .hero p {
            font-size: 1.1rem;
            opacity: 0.95;
        }
        
        .content {
            padding: 50px 30px;
        }
        
        .title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: #1a1a2e;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .subtitle {
            text-align: center;
            color: #999;
            margin-bottom: 40px;
            font-size: 0.95rem;
            letter-spacing: 1px;
        }
        
        .countdown-section {
            background: #f8f9fa;
            padding: 40px 30px;
            border-radius: 15px;
            margin-bottom: 40px;
        }
        
        .countdown-label {
            text-align: center;
            color: #999;
            font-size: 0.9rem;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .countdown-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .countdown-item {
            background: white;
            border: 2px solid #d4af37;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
        }
        
        .countdown-value {
            font-size: 2rem;
            font-weight: 700;
            color: #d4af37;
            margin-bottom: 5px;
        }
        
        .countdown-label-small {
            font-size: 0.8rem;
            color: #999;
            text-transform: uppercase;
        }
        
        .details-section {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 40px;
        }
        
        .detail-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .detail-item:last-child {
            margin-bottom: 0;
        }
        
        .detail-icon {
            font-size: 1.5rem;
            min-width: 30px;
        }
        
        .detail-text h3 {
            font-size: 0.9rem;
            color: #999;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .detail-text p {
            font-size: 1.1rem;
            color: #333;
            font-weight: 600;
        }
        
        .rsvp-section {
            background: white;
            padding: 30px;
            border: 2px solid #f0f0f0;
            border-radius: 15px;
            margin-bottom: 30px;
        }
        
        .rsvp-section h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            color: #1a1a2e;
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
        .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.95rem;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #d4af37;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
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
        
        .stats-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-box {
            text-align: center;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
        }
        
        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: #d4af37;
        }
        
        .stat-label {
            font-size: 0.8rem;
            color: #999;
            margin-top: 5px;
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
            width: 100%;
        }
        
        .btn-primary {
            background: #d4af37;
            color: #1a1a2e;
        }
        
        .btn-primary:hover {
            background: #b8930f;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(212, 175, 55, 0.3);
        }
        
        .footer {
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
            color: #999;
            font-size: 0.85rem;
            border-top: 1px solid #eee;
        }
        
        .footer p {
            margin-bottom: 5px;
        }
        
        @media (max-width: 480px) {
            .countdown-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .title {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="hero">
            <h1>Diundang Istimewa</h1>
            <p>Kami dengan hormat mengundang Anda pada acara istimewa kami</p>
        </div>
        
        <div class="content">
            <h2 class="title"><?= $nama_klien ?></h2>
            <p class="subtitle">📅 <?= $tanggal_acara ?></p>
            
            <div class="countdown-section">
                <div class="countdown-label">⏳ Hitung Mundur Acara</div>
                <div class="countdown-grid">
                    <div class="countdown-item">
                        <div class="countdown-value" id="days">0</div>
                        <div class="countdown-label-small">Hari</div>
                    </div>
                    <div class="countdown-item">
                        <div class="countdown-value" id="hours">0</div>
                        <div class="countdown-label-small">Jam</div>
                    </div>
                    <div class="countdown-item">
                        <div class="countdown-value" id="minutes">0</div>
                        <div class="countdown-label-small">Menit</div>
                    </div>
                    <div class="countdown-item">
                        <div class="countdown-value" id="seconds">0</div>
                        <div class="countdown-label-small">Detik</div>
                    </div>
                </div>
            </div>
            
            <div class="details-section">
                <div class="detail-item">
                    <div class="detail-icon">📅</div>
                    <div class="detail-text">
                        <h3>Tanggal & Jam</h3>
                        <p><?= $tanggal_acara ?></p>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-icon">📍</div>
                    <div class="detail-text">
                        <h3>Lokasi</h3>
                        <p><?= htmlspecialchars($pesanan['lokasi'] ?? 'Lokasi akan dikirim') ?></p>
                    </div>
                </div>
            </div>
            
            <?php if ($rsvp_submitted): ?>
            <div class="success-message">
                ✓ <strong>Terima kasih!</strong> Respons Anda telah tersimpan dengan baik.
            </div>
            <?php endif; ?>
            
            <div class="rsvp-section">
                <h3>📝 Konfirmasi Kehadiran</h3>
                
                <div class="stats-section">
                    <div class="stat-box">
                        <div class="stat-number"><?= $hadir_count ?></div>
                        <div class="stat-label">Yang Hadir</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number"><?= $tidak_hadir_count ?></div>
                        <div class="stat-label">Tidak Hadir</div>
                    </div>
                </div>
                
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
                    
                    <button type="submit" name="submit_rsvp" class="btn btn-primary">
                        Kirim Respons
                    </button>
                </form>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Undangan Digital Premium</strong></p>
            <p>Dibuat dengan ❤️ oleh Embun Visual</p>
            <p>© 2026 - Semua Hak Dilindungi</p>
        </div>
    </div>
    
    <script>
        function updateCountdown() {
            const targetDate = new Date('<?= $hari_h ?>').getTime();
            const now = new Date().getTime();
            const remaining = targetDate - now;
            
            if (remaining > 0) {
                document.getElementById('days').textContent = Math.floor(remaining / (1000 * 60 * 60 * 24));
                document.getElementById('hours').textContent = Math.floor((remaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                document.getElementById('minutes').textContent = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
                document.getElementById('seconds').textContent = Math.floor((remaining % (1000 * 60)) / 1000);
            } else {
                document.getElementById('days').textContent = '0';
                document.getElementById('hours').textContent = '0';
                document.getElementById('minutes').textContent = '0';
                document.getElementById('seconds').textContent = '0';
            }
        }
        
        updateCountdown();
        setInterval(updateCountdown, 1000);
        
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
