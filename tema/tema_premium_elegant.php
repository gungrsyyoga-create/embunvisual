<?php
/**
 * TEMPLATE PREMIUM - Undangan Digital Premium dengan Fitur Lengkap
 * Akses: /undangan/premium/{nama-klien}/index.php
 */
session_start();
include dirname(dirname(dirname(__FILE__))) . '/config.php';

$pesanan_id = isset($_GET['pid']) ? (int)$_GET['pid'] : 1;

// Ambil data pesanan
$q = mysqli_query($conn, "SELECT p.*, k.nama_tema FROM pesanan p LEFT JOIN katalog_tema k ON p.tema_id=k.id WHERE p.id='$pesanan_id'");
$pesanan = mysqli_fetch_assoc($q);

if (!$pesanan) { die("Pesanan tidak ditemukan."); }

// Hitung countdown
$hari_h = $pesanan['tanggal_acara'];
$diff = (strtotime($hari_h) - time()) / 86400;
$hari_mundur = max(0, (int)$diff);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pesanan['nama_pemesan']) ?> - Undangan Premium</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1A1614;
            --gold: #D4AF37;
            --bg: #FAF8F5;
            --text: #2A2522;
            --serif: 'Playfair Display', serif;
            --sans: 'Inter', sans-serif;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: var(--sans);
            background: var(--bg);
            color: var(--text);
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--primary) 0%, #2d2520 100%);
            color: white;
            padding: 80px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(212,175,55,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .hero h1 {
            font-family: var(--serif);
            font-size: 3.5rem;
            font-style: italic;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .hero-names {
            font-size: 2rem;
            margin: 30px 0;
            letter-spacing: 2px;
        }
        
        .divider {
            width: 80px;
            height: 2px;
            background: var(--gold);
            margin: 25px auto;
        }
        
        /* Countdown */
        .countdown-section {
            background: white;
            padding: 50px 40px;
            text-align: center;
        }
        
        .countdown-title {
            font-family: var(--serif);
            font-size: 1.8rem;
            font-style: italic;
            color: var(--primary);
            margin-bottom: 30px;
        }
        
        .countdown {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .countdown-item {
            background: #f8f6f3;
            padding: 20px;
            border-radius: 12px;
            border: 2px solid var(--gold);
        }
        
        .countdown-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--gold);
        }
        
        .countdown-label {
            font-size: 0.8rem;
            color: var(--text);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 8px;
        }
        
        /* Details Section */
        .details {
            background: #f8f6f3;
            padding: 50px 40px;
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            border-bottom: 2px solid var(--gold);
            padding-bottom: 40px;
        }
        
        .detail-item h3 {
            font-family: var(--serif);
            font-size: 1.3rem;
            font-style: italic;
            color: var(--primary);
            margin-bottom: 15px;
        }
        
        .detail-item p {
            color: var(--text);
            font-size: 0.95rem;
            line-height: 1.8;
        }
        
        .detail-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        /* RSVP Section */
        .rsvp-section {
            padding: 50px 40px;
            text-align: center;
            background: white;
        }
        
        .rsvp-title {
            font-family: var(--serif);
            font-size: 1.8rem;
            font-style: italic;
            color: var(--primary);
            margin-bottom: 20px;
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
            background: var(--primary);
            color: white;
            border: 2px solid var(--primary);
        }
        
        .btn-primary:hover {
            background: var(--gold);
            border-color: var(--gold);
            color: var(--primary);
        }
        
        .btn-secondary {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }
        
        .btn-secondary:hover {
            background: var(--primary);
            color: white;
        }
        
        /* Footer */
        .footer {
            background: var(--primary);
            color: white;
            text-align: center;
            padding: 40px;
            font-size: 0.85rem;
        }
        
        .footer p {
            margin: 8px 0;
        }
        
        @media (max-width: 768px) {
            .hero h1 { font-size: 2.5rem; }
            .hero-names { font-size: 1.3rem; }
            .countdown { grid-template-columns: repeat(2, 1fr); }
            .details-grid { grid-template-columns: 1fr; }
            .button-group { flex-direction: column; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Hero Section -->
        <div class="hero">
            <div class="hero-content">
                <h1>Diundang Istimewa</h1>
                <div class="hero-names">
                    <?= htmlspecialchars($pesanan['nama_pemesan']) ?>
                </div>
                <div class="divider"></div>
                <p style="font-size: 1.1rem; letter-spacing: 0.5px;">
                    Dengan hormat kami mengundang kehadiran Anda
                </p>
            </div>
        </div>
        
        <!-- Countdown Section -->
        <div class="countdown-section">
            <h2 class="countdown-title">Acara Dimulai Dalam</h2>
            <div class="countdown">
                <div class="countdown-item">
                    <div class="countdown-number"><?= $hari_mundur ?></div>
                    <div class="countdown-label">Hari</div>
                </div>
                <div class="countdown-item">
                    <div class="countdown-number">23</div>
                    <div class="countdown-label">Jam</div>
                </div>
                <div class="countdown-item">
                    <div class="countdown-number">45</div>
                    <div class="countdown-label">Menit</div>
                </div>
                <div class="countdown-item">
                    <div class="countdown-number">12</div>
                    <div class="countdown-label">Detik</div>
                </div>
            </div>
        </div>
        
        <!-- Details Section -->
        <div class="details">
            <div class="details-grid">
                <div class="detail-item">
                    <div class="detail-icon">📅</div>
                    <h3>Tanggal & Waktu</h3>
                    <p>
                        <?= date('l, d F Y', strtotime($pesanan['tanggal_acara'])) ?><br>
                        Pukul 19:00 - 23:00 WIB
                    </p>
                </div>
                <div class="detail-item">
                    <div class="detail-icon">📍</div>
                    <h3>Lokasi Acara</h3>
                    <p>
                        Grand Ballroom<br>
                        Jalan Udayana No. 25<br>
                        Bali, Indonesia
                    </p>
                </div>
            </div>
        </div>
        
        <!-- RSVP Section -->
        <div class="rsvp-section">
            <h2 class="rsvp-title">Konfirmasi Kehadiran Anda</h2>
            <p style="font-size: 0.95rem; color: #666; margin-bottom: 10px;">
                Mohon konfirmasi kehadiran Anda sebelum tanggal 25 Maret 2026
            </p>
            <div class="button-group">
                <a href="rsvp.php?pesanan=<?= $pesanan_id ?>&respon=hadir" class="btn btn-primary">
                    ✓ Saya Hadir
                </a>
                <a href="rsvp.php?pesanan=<?= $pesanan_id ?>&respon=tidak_hadir" class="btn btn-secondary">
                    ✗ Tidak Hadir
                </a>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p><strong>Embun Visual</strong></p>
            <p>Undangan Digital Premium</p>
            <p style="margin-top: 15px; opacity: 0.7;">© 2026 - Semua Hak Dilindungi</p>
        </div>
    </div>
    
    <script>
        // Countdown timer
        function updateCountdown() {
            const targetDate = new Date('<?= $hari_h ?>').getTime();
            const timer = setInterval(() => {
                const now = new Date().getTime();
                const remaining = targetDate - now;
                
                if (remaining <= 0) {
                    clearInterval(timer);
                    document.querySelectorAll('.countdown-number')[0].textContent = '0';
                    return;
                }
                
                const days = Math.floor(remaining / (1000 * 60 * 60 * 24));
                const hours = Math.floor((remaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((remaining % (1000 * 60)) / 1000);
                
                document.querySelectorAll('.countdown-number')[0].textContent = days;
                document.querySelectorAll('.countdown-number')[1].textContent = hours;
                document.querySelectorAll('.countdown-number')[2].textContent = minutes;
                document.querySelectorAll('.countdown-number')[3].textContent = seconds;
            }, 1000);
        }
        
        updateCountdown();
    </script>
</body>
</html>
