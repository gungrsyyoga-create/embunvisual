<?php
/**
 * TEMPLATE EXCLUSIVE - Undangan Digital Eksklusif Dengan Barcode & Fitur VIP
 * Akses: /undangan/exclusive/{nama-klien}/index.php
 */
session_start();
include dirname(dirname(dirname(__FILE__))) . '/config.php';

$pesanan_id = isset($_GET['pid']) ? (int)$_GET['pid'] : 1;

// Ambil data pesanan
$q = mysqli_query($conn, "SELECT p.*, k.nama_tema FROM pesanan p LEFT JOIN katalog_tema k ON p.tema_id=k.id WHERE p.id='$pesanan_id'");
$pesanan = mysqli_fetch_assoc($q);

if (!$pesanan) { die("Pesanan tidak ditemukan."); }

$hari_h = $pesanan['tanggal_acara'];
$diff = (strtotime($hari_h) - time()) / 86400;
$hari_mundur = max(0, (int)$diff);

// Generate unique barcode untuk tamu ini
$barcode_data = "EVL-" . strtoupper($pesanan['invoice']) . "-" . date('YmdHis');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pesanan['nama_pemesan']) ?> - Undangan Eksklusif</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700&family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.5/JsBarcode.all.min.js"></script>
    <style>
        :root {
            --dark: #0a0e27;
            --gold: #D4AF37;
            --gold-dark: #B8960C;
            --white: #f5f3f0;
            --accent: #e8d5b7;
            --serif: 'Playfair Display', serif;
            --sans: 'Montserrat', sans-serif;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: var(--sans);
            background: var(--dark);
            color: var(--white);
            overflow-x: hidden;
        }
        
        /* ── HERO SECTION ── */
        .hero {
            background: linear-gradient(135deg, var(--dark) 0%, #1a1f3a 100%);
            padding: 100px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(212, 175, 55, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(212, 175, 55, 0.05) 0%, transparent 50%);
            z-index: 0;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
        }
        
        .badge-exclusive {
            display: inline-block;
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color: var(--dark);
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.85rem;
            letter-spacing: 2px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        
        .hero h1 {
            font-family: var(--serif);
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 15px;
            background: linear-gradient(135deg, var(--gold), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.1;
        }
        
        .hero-subtitle {
            color: var(--accent);
            font-size: 1.2rem;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }
        
        .hero-names {
            font-size: 2.2rem;
            font-weight: 700;
            margin: 30px 0;
            color: var(--white);
        }
        
        .divider-gold {
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, var(--gold), transparent);
            margin: 30px auto;
        }
        
        /* ── COUNTDOWN SECTION ── */
        .countdown-container {
            background: rgba(212, 175, 55, 0.05);
            backdrop-filter: blur(10px);
            padding: 60px 40px;
            border-top: 2px solid var(--gold);
            border-bottom: 2px solid var(--gold);
        }
        
        .countdown-title {
            font-family: var(--serif);
            font-size: 2rem;
            color: var(--gold);
            margin-bottom: 40px;
            text-align: center;
        }
        
        .countdown {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            max-width: 700px;
            margin: 0 auto;
        }
        
        .countdown-item {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid var(--gold);
            border-radius: 15px;
            padding: 25px 15px;
            text-align: center;
        }
        
        .countdown-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--gold);
            line-height: 1;
        }
        
        .countdown-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--accent);
            margin-top: 10px;
        }
        
        /* ── DETAILS SECTION ── */
        .details {
            background: var(--dark);
            padding: 60px 40px;
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
        }
        
        .detail-card {
            border-left: 4px solid var(--gold);
            padding-left: 30px;
        }
        
        .detail-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }
        
        .detail-card h3 {
            font-family: var(--serif);
            font-size: 1.4rem;
            color: var(--gold);
            margin-bottom: 15px;
        }
        
        .detail-card p {
            color: var(--accent);
            line-height: 1.8;
            font-size: 0.95rem;
        }
        
        /* ── BARCODE SECTION ── */
        .barcode-section {
            background: rgba(212, 175, 55, 0.08);
            padding: 50px 40px;
            text-align: center;
            border: 2px dashed var(--gold);
            border-radius: 15px;
            margin: 50px 0;
        }
        
        .barcode-title {
            font-family: var(--serif);
            font-size: 1.5rem;
            color: var(--gold);
            margin-bottom: 20px;
        }
        
        .barcode-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            display: inline-block;
            margin: 15px 0;
        }
        
        #barcode {
            max-width: 100%;
            height: auto;
        }
        
        .barcode-note {
            font-size: 0.8rem;
            color: var(--accent);
            margin-top: 15px;
            font-style: italic;
        }
        
        /* ── RSVP SECTION ── */
        .rsvp-section {
            background: var(--dark);
            padding: 60px 40px;
            text-align: center;
        }
        
        .rsvp-title {
            font-family: var(--serif);
            font-size: 2rem;
            color: var(--gold);
            margin-bottom: 15px;
        }
        
        .button-group {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 30px;
        }
        
        .btn {
            padding: 15px 40px;
            border: 2px solid var(--gold);
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            font-weight: 700;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            font-family: var(--sans);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .btn-primary {
            background: var(--gold);
            color: var(--dark);
        }
        
        .btn-primary:hover {
            background: var(--gold-dark);
            border-color: var(--gold-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(212, 175, 55, 0.3);
        }
        
        .btn-secondary {
            background: transparent;
            color: var(--gold);
        }
        
        .btn-secondary:hover {
            background: var(--gold);
            color: var(--dark);
        }
        
        /* ── FOOTER ── */
        .footer {
            background: rgba(0, 0, 0, 0.4);
            color: var(--accent);
            text-align: center;
            padding: 40px;
            border-top: 2px solid var(--gold);
            font-size: 0.85rem;
        }
        
        .footer p {
            margin: 8px 0;
        }
        
        .footer-brand {
            font-family: var(--serif);
            font-size: 1.2rem;
            color: var(--gold);
            margin-bottom: 10px;
        }
        
        /* ── RESPONSIVE ── */
        @media (max-width: 1000px) {
            .hero h1 { font-size: 3rem; }
            .details-grid { grid-template-columns: 1fr; gap: 30px; }
            .countdown { grid-template-columns: repeat(2, 1fr); }
        }
        
        @media (max-width: 600px) {
            .hero h1 { font-size: 2rem; }
            .hero { padding: 50px 20px; }
            .countdown { grid-template-columns: 1fr; }
            .button-group { flex-direction: column; }
            .btn { width: 100%; }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="badge-exclusive">✨ Exclusive Invitation</div>
            <h1>Anda Diundang</h1>
            <p class="hero-subtitle">Untuk perayaan istimewa</p>
            <div class="divider-gold"></div>
            <div class="hero-names">
                <?= htmlspecialchars($pesanan['nama_pemesan']) ?>
            </div>
            <p class="hero-subtitle" style="margin-top: 20px;">
                Kami dengan hormat mengundang Anda untuk hadir di acara eksklusif kami
            </p>
        </div>
    </section>
    
    <!-- Countdown Section -->
    <section class="countdown-container">
        <h2 class="countdown-title">Acara Dimulai Dalam</h2>
        <div class="countdown">
            <div class="countdown-item">
                <div class="countdown-number"><?= $hari_mundur ?></div>
                <div class="countdown-label">Hari</div>
            </div>
            <div class="countdown-item">
                <div class="countdown-number" id="hours">23</div>
                <div class="countdown-label">Jam</div>
            </div>
            <div class="countdown-item">
                <div class="countdown-number" id="minutes">45</div>
                <div class="countdown-label">Menit</div>
            </div>
            <div class="countdown-item">
                <div class="countdown-number" id="seconds">12</div>
                <div class="countdown-label">Detik</div>
            </div>
        </div>
    </section>
    
    <!-- Details Section -->
    <section class="details">
        <div class="details-grid">
            <div class="detail-card">
                <div class="detail-icon">📅</div>
                <h3>Tanggal & Waktu</h3>
                <p>
                    <?= date('l, d F Y', strtotime($pesanan['tanggal_acara'])) ?><br>
                    <strong>Pukul 19:00 - Selesai</strong><br>
                    Harap tiba 15 menit lebih dulu
                </p>
            </div>
            <div class="detail-card">
                <div class="detail-icon">📍</div>
                <h3>Lokasi Eksklusif</h3>
                <p>
                    <strong>Grand Ballroom Premium</strong><br>
                    Jalan Udayana No. 25<br>
                    Kuta, Bali 80361
                </p>
            </div>
        </div>
    </section>
    
    <!-- Barcode Section (VIP Exclusive Feature) -->
    <section class="barcode-section">
        <h3 class="barcode-title">🎟️ VIP Barcode Pengecekan Tamu</h3>
        <p style="color: var(--accent); margin-bottom: 20px;">
            Tunjukkan barcode ini saat tiba untuk verifikasi cepat
        </p>
        <div class="barcode-container">
            <svg id="barcode"></svg>
        </div>
        <p class="barcode-note">
            Barcode unik untuk: <strong><?= $barcode_data ?></strong>
        </p>
    </section>
    
    <!-- RSVP Section -->
    <section class="rsvp-section">
        <h2 class="rsvp-title">Konfirmasi Kehadiran</h2>
        <p style="color: var(--accent); margin-bottom: 10px;">
            Mohon berikan penegasan sebelum <?= date('d M Y', strtotime($pesanan['tanggal_acara'] . ' -7 days')) ?>
        </p>
        <div class="button-group">
            <a href="rsvp.php?pesanan=<?= $pesanan_id ?>&respon=hadir" class="btn btn-primary">
                ✓ Saya Hadir
            </a>
            <a href="rsvp.php?pesanan=<?= $pesanan_id ?>&respon=tidak_hadir" class="btn btn-secondary">
                ✗ Tidak Hadir
            </a>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-brand">Embun Visual</div>
        <p>Digital Invitation Experience</p>
        <p style="margin-top: 15px; opacity: 0.5;">© 2026 - Semua Hak Dilindungi</p>
    </footer>
    
    <script>
        // Generate Barcode
        JsBarcode("#barcode", "<?= $barcode_data ?>", {
            format: "CODE128",
            width: 2,
            height: 60,
            displayValue: true,
            fontSize: 14
        });
        
        // Countdown Timer
        function updateCountdown() {
            const targetDate = new Date('<?= $hari_h ?>').getTime();
            setInterval(() => {
                const now = new Date().getTime();
                const remaining = targetDate - now;
                
                if (remaining <= 0) return;
                
                const hours = Math.floor((remaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((remaining % (1000 * 60)) / 1000);
                
                document.getElementById('hours').textContent = hours;
                document.getElementById('minutes').textContent = minutes;
                document.getElementById('seconds').textContent = seconds;
            }, 1000);
        }
        updateCountdown();
    </script>
</body>
</html>
