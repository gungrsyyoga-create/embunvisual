<?php
/**
 * TEMPLATE EXCLUSIVE - VIP dengan Barcode Scanning + RSVP Form
 * Akses: /undangan/exclusive/{nama-klien}/index.php
 */
session_start();
include dirname(dirname(dirname(__FILE__))) . '/config.php';

$pesanan_id = isset($_GET['pid']) ? (int)$_GET['pid'] : 1;
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'invitation';

// Ambil data pesanan
$q = mysqli_query($conn, "SELECT p.*, k.nama_tema FROM pesanan p LEFT JOIN katalog_tema k ON p.tema_id=k.id WHERE p.id='$pesanan_id'");
$pesanan = mysqli_fetch_assoc($q);

if (!$pesanan) { die("Pesanan tidak ditemukan."); }

$nama_klien = htmlspecialchars($pesanan['nama_pemesan']);
$tanggal_acara = date('d M Y H:i', strtotime($pesanan['tanggal_acara']));
$hari_h = $pesanan['tanggal_acara'];
$invoice = htmlspecialchars($pesanan['invoice_number']);
$barcode_value = 'EVL-' . $invoice . '-' . date('YmdHis');
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

// Hitung scan statistik jika ada tabel barcode_scans
$scan_today = 0;
$scan_result = @mysqli_query($conn, "SELECT COUNT(*) as total FROM barcode_scans WHERE pesanan_id='$pesanan_id' AND DATE(scan_time)=CURDATE()");
if ($scan_result) {
    $scan_today = mysqli_fetch_assoc($scan_result)['total'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pesanan['nama_pemesan']) ?> - Undangan VIP</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital@0;1&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0a0e27 0%, #1a1f3a 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 750px;
            margin: 0 auto;
            background: #1a1f3a;
            border-radius: 25px;
            overflow: hidden;
            border: 2px solid #d4af37;
            box-shadow: 0 30px 60px rgba(212, 175, 55, 0.2);
        }
        
        .hero {
            background: radial-gradient(circle at 50% 50%, #2a3f5f 0%, #0a0e27 100%);
            padding: 80px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: rgba(212, 175, 55, 0.05);
            border-radius: 50%;
        }
        
        .vip-badge {
            display: inline-block;
            background: linear-gradient(135deg, #d4af37 0%, #f0e68c 100%);
            color: #0a0e27;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 700;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            background: linear-gradient(135deg, #d4af37 0%, #f0e68c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-style: italic;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            position: relative;
            z-index: 1;
        }
        
        .hero p {
            font-size: 1.1rem;
            color: #b5a0ff;
            position: relative;
            z-index: 1;
        }
        
        .content {
            padding: 50px 30px;
        }
        
        .title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            background: linear-gradient(135deg, #d4af37 0%, #f0e68c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .subtitle {
            text-align: center;
            color: #b5a0ff;
            margin-bottom: 40px;
            font-size: 0.95rem;
            letter-spacing: 1px;
        }
        
        .mode-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
        }
        
        .mode-tab {
            flex: 1;
            padding: 12px;
            border: 1px solid #d4af37;
            background: transparent;
            color: #d4af37;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: block;
            text-align: center;
        }
        
        .mode-tab.active {
            background: #d4af37;
            color: #0a0e27;
        }
        
        .mode-tab:hover {
            background: rgba(212, 175, 55, 0.1);
        }
        
        .countdown-section {
            background: rgba(255, 255, 255, 0.05);
            padding: 40px 30px;
            border-radius: 15px;
            border: 1px solid #d4af37;
            margin-bottom: 40px;
        }
        
        .countdown-label {
            text-align: center;
            color: #b5a0ff;
            font-size: 0.9rem;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .countdown-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }
        
        .countdown-item {
            background: transparent;
            border: 2px solid #d4af37;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
        }
        
        .countdown-value {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #d4af37 0%, #f0e68c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 5px;
        }
        
        .countdown-label-small {
            font-size: 0.8rem;
            color: #b5a0ff;
            text-transform: uppercase;
        }
        
        .barcode-section {
            background: rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 15px;
            border: 1px solid #d4af37;
            margin-bottom: 40px;
            text-align: center;
        }
        
        .barcode-section h3 {
            color: #d4af37;
            margin-bottom: 20px;
            font-size: 1.1rem;
        }
        
        #barcode {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            display: inline-block;
        }
        
        .barcode-info {
            color: #b5a0ff;
            font-size: 0.9rem;
        }
        
        .details-section {
            background: rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 15px;
            border: 1px solid #d4af37;
            margin-bottom: 40px;
        }
        
        .detail-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 20px;
            color: #e0e0e0;
        }
        
        .detail-item:last-child {
            margin-bottom: 0;
        }
        
        .detail-icon {
            font-size: 1.5rem;
            min-width: 30px;
        }
        
        .detail-text h3 {
            font-size: 0.85rem;
            color: #b5a0ff;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .detail-text p {
            font-size: 1rem;
            color: #d4af37;
            font-weight: 600;
        }
        
        .rsvp-section {
            background: rgba(255, 255, 255, 0.05);
            padding: 30px;
            border: 1px solid #d4af37;
            border-radius: 15px;
            margin-bottom: 30px;
        }
        
        .rsvp-section h3 {
            color: #d4af37;
            margin-bottom: 20px;
            text-align: center;
            font-size: 1.3rem;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #b5a0ff;
            font-size: 0.9rem;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d4af37;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.95rem;
            background: rgba(255, 255, 255, 0.05);
            color: #e0e0e0;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #f0e68c;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
        }
        
        .form-group input::placeholder {
            color: #757575;
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
        
        .radio-item label {
            margin: 0;
            cursor: pointer;
            color: #b5a0ff;
        }
        
        .success-message {
            background: rgba(34, 197, 94, 0.1);
            border: 2px solid #22c55e;
            color: #86efac;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .stats-section {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-box {
            text-align: center;
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #d4af37;
        }
        
        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: #d4af37;
        }
        
        .stat-label {
            font-size: 0.75rem;
            color: #b5a0ff;
            margin-top: 5px;
            text-transform: uppercase;
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
            background: linear-gradient(135deg, #d4af37 0%, #f0e68c 100%);
            color: #0a0e27;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(212, 175, 55, 0.4);
        }
        
        .footer {
            background: #0a0e27;
            padding: 30px;
            text-align: center;
            color: #b5a0ff;
            font-size: 0.85rem;
            border-top: 1px solid #d4af37;
        }
        
        .footer p {
            margin-bottom: 5px;
        }
        
        .hidden { display: none; }
        
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
            
            .stats-section {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="hero">
            <div class="vip-badge">✨ Exclusive Invitation</div>
            <h1>Diundang VIP</h1>
            <p>Acara eksklusif menanti kehadiran Anda</p>
        </div>
        
        <div class="content">
            <h2 class="title"><?= $nama_klien ?></h2>
            <p class="subtitle">📅 <?= $tanggal_acara ?></p>
            
            <div class="mode-tabs">
                <a href="?pid=<?= $pesanan_id ?>&mode=invitation" class="mode-tab <?= $mode === 'invitation' ? 'active' : '' ?>">
                    📬 Undangan
                </a>
                <a href="?pid=<?= $pesanan_id ?>&mode=barcode" class="mode-tab <?= $mode === 'barcode' ? 'active' : '' ?>">
                    📱 Barcode
                </a>
                <a href="?pid=<?= $pesanan_id ?>&mode=rsvp" class="mode-tab <?= $mode === 'rsvp' ? 'active' : '' ?>">
                    ✓ RSVP
                </a>
            </div>
            
            <!-- INVITATION MODE -->
            <div class="<?= $mode !== 'invitation' ? 'hidden' : '' ?>">
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
                    <div class="detail-item">
                        <div class="detail-icon">🎟️</div>
                        <div class="detail-text">
                            <h3>Invoice</h3>
                            <p><?= $invoice ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- BARCODE MODE -->
            <div class="<?= $mode !== 'barcode' ? 'hidden' : '' ?>">
                <div class="barcode-section">
                    <h3>🎫 Barcode Undangan</h3>
                    <svg id="barcode"></svg>
                    <div class="barcode-info">
                        <p style="margin-bottom: 10px;">Tunjukkan barcode ini saat datang ke acara</p>
                        <p><strong><?= $barcode_value ?></strong></p>
                    </div>
                </div>
                
                <div class="stats-section">
                    <div class="stat-box">
                        <div class="stat-number"><?= $hadir_count ?></div>
                        <div class="stat-label">Hadir</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number"><?= $tidak_hadir_count ?></div>
                        <div class="stat-label">Tidak Hadir</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number"><?= $scan_today ?></div>
                        <div class="stat-label">Scan Hari Ini</div>
                    </div>
                </div>
            </div>
            
            <!-- RSVP MODE -->
            <div class="<?= $mode !== 'rsvp' ? 'hidden' : '' ?>">
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
                            <div class="stat-label">Hadir</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-number"><?= $tidak_hadir_count ?></div>
                            <div class="stat-label">Tidak Hadir</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-number"><?= ($hadir_count + $tidak_hadir_count) ?></div>
                            <div class="stat-label">Total Respons</div>
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
                                    <label for="hadir">✓ Saya Hadir</label>
                                </div>
                                <div class="radio-item">
                                    <input type="radio" id="tidak_hadir" name="respon" value="tidak_hadir">
                                    <label for="tidak_hadir">✗ Tidak Hadir</label>
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
        </div>
        
        <div class="footer">
            <p><strong>Undangan Digital VIP</strong></p>
            <p>Dengan Barcode Scanning & RSVP Tracking</p>
            <p>© 2026 - Embun Visual - Semua Hak Dilindungi</p>
        </div>
    </div>
    
    <script>
        // Countdown
        function updateCountdown() {
            const targetDate = new Date('<?= $hari_h ?>').getTime();
            const now = new Date().getTime();
            const remaining = targetDate - now;
            
            if (remaining > 0) {
                document.getElementById('days').textContent = Math.floor(remaining / (1000 * 60 * 60 * 24));
                document.getElementById('hours').textContent = Math.floor((remaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                document.getElementById('minutes').textContent = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
                document.getElementById('seconds').textContent = Math.floor((remaining % (1000 * 60)) / 1000);
            }
        }
        
        updateCountdown();
        setInterval(updateCountdown, 1000);
        
        // Barcode
        JsBarcode("#barcode", "<?= $barcode_value ?>", {
            format: "CODE128",
            width: 2,
            height: 60,
            displayValue: false
        });
        
        // Toggle jumlah hadir
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
