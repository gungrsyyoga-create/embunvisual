<?php
/**
 * EXCLUSIVE INVITATION - Bali Theme dengan E-Ticket Card
 * Features: Welcome Overlay, RSVP + Database, E-Ticket Card
 * Self-contained system dalam folder /undangan/exclusive/dede/
 */

session_start();

require_once 'config.php';

// Check if database setup is needed
if (needs_setup()) {
    header('Location: setup.php');
    exit;
}

// Get undangan data
$undangan_id = UNDANGAN_ID;
$inv_data = get_undangan();

if (!$inv_data) {
    die("<div style='background: #0a0e27; color: #f44336; font-family: sans-serif; padding: 50px; text-align: center; min-height: 100vh; display: flex; flex-direction: column; justify-content: center;'><h2>❌ Undangan tidak ditemukan</h2><p><a href='setup.php' style='color: #d4af37;'>Setup database terlebih dahulu</a></p></div>");
}

// Handle RSVP submission
$rsvp_status = 'pending';
$etiket_number = null;
$barcode_value = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_rsvp'])) {
    $nama_tamu = mysqli_real_escape_string($mysqli, $_POST['nama_tamu']);
    $no_hp = mysqli_real_escape_string($mysqli, $_POST['no_hp']);
    $respon = $_POST['respon'] === 'hadir' ? 'Hadir' : 'Tidak Hadir';
    $jumlah = (int)($_POST['jumlah_hadir'] ?? 1);
    
    if (!empty($nama_tamu) && !empty($inv_data)) {
        // Save to database
        $insert = "INSERT INTO tamu_undangan (undangan_id, nama_tamu, no_hp, respon, jumlah_tamu, status_etiket)
                  VALUES ({$inv_data['id']}, '$nama_tamu', '$no_hp', '$respon', $jumlah, 'pending')";
        
        if ($mysqli->query($insert)) {
            $tamu_id = $mysqli->insert_id;
            
            // Generate E-Ticket
            $etiket_number = 'ETK-' . strtoupper(substr(md5($nama_tamu . time()), 0, 10));
            $barcode_value = 'DEDE-' . date('Ymd') . '-' . str_pad($tamu_id, 5, '0', STR_PAD_LEFT);
            
            // Save E-Ticket to database
            $etiket_insert = "INSERT INTO etiket (tamu_undangan_id, undangan_id, etiket_number, barcode_value, status)
                             VALUES ($tamu_id, {$inv_data['id']}, '$etiket_number', '$barcode_value', 'generated')";
            $mysqli->query($etiket_insert);
            
            // Update tamu status
            $mysqli->query("UPDATE tamu_undangan SET etiket_number = '$etiket_number', status_etiket = 'generated', etiket_generated_at = NOW() WHERE id = $tamu_id");
            
            $rsvp_status = 'success';
            
            // Store in session
            $_SESSION['rsvp_tamu'] = array(
                'nama' => $nama_tamu,
                'status' => $respon,
                'jumlah' => $jumlah,
                'etiket_number' => $etiket_number,
                'barcode_value' => $barcode_value,
                'no_hp' => $no_hp
            );
        }
    }
}

// Get RSVP statistics
$stats = $mysqli->query("SELECT 
    SUM(CASE WHEN respon='Hadir' THEN 1 ELSE 0 END) as hadir_count,
    SUM(CASE WHEN respon='Tidak Hadir' THEN 1 ELSE 0 END) as tidak_hadir_count
    FROM tamu_undangan WHERE undangan_id = {$inv_data['id']}");
$stat_data = $stats ? $stats->fetch_assoc() : ['hadir_count' => 0, 'tidak_hadir_count' => 0];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dede & Prasetya - Undangan Pernikahan Eksklusif Bali</title>
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
        
        /* WELCOME OVERLAY */
        #welcome-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #0a0e27 0%, #1a1f3a 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            text-align: center;
            padding: 20px;
        }
        
        #welcome-overlay.hidden {
            display: none;
        }
        
        .welcome-content {
            animation: slideUp 0.8s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .welcome-badge {
            display: inline-block;
            background: linear-gradient(135deg, #d4af37 0%, #f0e68c 100%);
            color: #0a0e27;
            padding: 10px 25px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .welcome-title {
            font-family: 'Playfair Display', serif;
            font-size: 4rem;
            background: linear-gradient(135deg, #d4af37 0%, #f0e68c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-style: italic;
            margin-bottom: 15px;
            line-height: 1.1;
        }
        
        .welcome-subtitle {
            color: #b5a0ff;
            font-size: 1.3rem;
            margin-bottom: 10px;
            font-style: italic;
        }
        
        .guest-name-input {
            margin: 30px 0;
            display: flex;
            gap: 10px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .guest-name-input input {
            flex: 1;
            padding: 12px 20px;
            border: 2px solid #d4af37;
            background: rgba(255, 255, 255, 0.05);
            color: #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
        }
        
        .guest-name-input input:focus {
            outline: none;
            border-color: #f0e68c;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
        }
        
        .guest-name-input input::placeholder {
            color: #888;
        }
        
        .btn-open-invitation {
            background: linear-gradient(135deg, #d4af37 0%, #f0e68c 100%);
            color: #0a0e27;
            border: none;
            padding: 14px 40px;
            font-size: 1.1rem;
            font-weight: 700;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 20px;
        }
        
        .btn-open-invitation:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(212, 175, 55, 0.4);
        }
        
        .welcome-location {
            color: #d4af37;
            font-size: 1rem;
            margin-top: 20px;
            letter-spacing: 1px;
        }
        
        .welcome-decoration {
            font-size: 3rem;
            margin: 30px 0;
            opacity: 0.8;
        }
        
        /* MAIN CONTAINER */
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #1a1f3a;
            border-radius: 25px;
            overflow: hidden;
            border: 2px solid #d4af37;
            box-shadow: 0 30px 80px rgba(212, 175, 55, 0.25);
        }
        
        .hero {
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.2) 0%, rgba(255, 193, 7, 0.1) 50%, rgba(212, 175, 55, 0.05) 100%);
            padding: 60px 30px;
            text-align: center;
            position: relative;
        }
        
        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3.2rem;
            background: linear-gradient(135deg, #d4af37 0%, #f0e68c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-style: italic;
            margin-bottom: 10px;
            line-height: 1.1;
        }
        
        .content {
            padding: 50px 30px;
        }
        
        .mode-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            border-bottom: 1px solid #333;
        }
        
        .mode-tab {
            flex: 1;
            padding: 12px;
            border: none;
            background: transparent;
            color: #d4af37;
            border-bottom: 3px solid transparent;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            text-align: center;
            font-size: 0.95rem;
        }
        
        .mode-tab.active {
            border-bottom-color: #d4af37;
            background: rgba(212, 175, 55, 0.05);
        }
        
        .countdown-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 40px;
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
            color: #d4af37;
            margin-bottom: 5px;
        }
        
        .countdown-label {
            font-size: 0.8rem;
            color: #b5a0ff;
            text-transform: uppercase;
        }
        
        .stats-section {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
            margin-bottom: 40px;
        }
        
        .stat-box {
            text-align: center;
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #d4af37;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #d4af37;
        }
        
        .stat-label {
            font-size: 0.8rem;
            color: #b5a0ff;
            text-transform: uppercase;
            margin-top: 5px;
        }
        
        /* E-TIKET CARD STYLE */
        .etiket-card {
            background: linear-gradient(135deg, #d4af37 0%, #f0e68c 100%);
            border-radius: 15px;
            padding: 40px;
            color: #0a0e27;
            margin: 40px 0;
            box-shadow: 0 20px 50px rgba(212, 175, 55, 0.3);
            text-align: center;
            animation: fadeInScale 0.6s ease-out;
        }
        
        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        .etiket-card h3 {
            font-size: 1.3rem;
            margin-bottom: 15px;
            font-weight: 700;
        }
        
        .etiket-card-box {
            background: white;
            border-radius: 10px;
            padding: 30px;
            margin: 20px 0;
            border: 2px solid #0a0e27;
        }
        
        .barcode-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 15px 0;
            display: inline-block;
        }
        
        #barcode {
            display: block;
        }
        
        .etiket-number {
            font-size: 1.2rem;
            font-weight: 700;
            color: #0a0e27;
            letter-spacing: 2px;
            margin-top: 15px;
        }
        
        .etiket-info {
            margin-top: 15px;
            font-size: 0.9rem;
            color: #0a0e27;
        }
        
        .etiket-info strong {
            display: block;
            margin-top: 10px;
            font-size: 1rem;
        }
        
        /* RSVP FORM */
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
        
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
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
        
        .success-message {
            background: rgba(34, 197, 94, 0.1);
            border: 2px solid #22c55e;
            color: #86efac;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .footer {
            background: #0a0e27;
            padding: 30px;
            text-align: center;
            color: #b5a0ff;
            font-size: 0.85rem;
            border-top: 1px solid #d4af37;
        }
        
        .hidden { display: none; }
        
        @media (max-width: 480px) {
            .countdown-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .stats-section {
                grid-template-columns: 1fr;
            }
            .welcome-title {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- WELCOME OVERLAY -->
    <div id="welcome-overlay">
        <div class="welcome-content">
            <div class="welcome-badge">📬 Undangan Istimewa</div>
            <div class="welcome-decoration">💍</div>
            <h1 class="welcome-title">Dede & Prasetya</h1>
            <p class="welcome-subtitle">Mengundang Anda pada upacara pernikahan mereka</p>
            
            <div class="guest-name-input">
                <input type="text" id="guest-name" placeholder="Siapa nama Anda?" />
            </div>
            
            <button class="btn-open-invitation" onclick="bukaUndangan()">
                📬 Buka Undangan
            </button>
            
            <p class="welcome-location">🏝️ Bali, Indonesia | 14 Maret 2026</p>
        </div>
    </div>

    <!-- MAIN INVITATION -->
    <div class="container" id="main-invitation" style="display: none;">
        <div class="hero">
            <h1>Dede & Prasetya</h1>
            <p style="color: #b5a0ff; font-size: 1.2rem; margin-top: 10px;">✨ Pernikahan Eksklusif di Bali ✨</p>
        </div>
        
        <div class="content">
            <div class="mode-tabs">
                <button class="mode-tab active" onclick="switchMode('undangan')">📬 Undangan</button>
                <button class="mode-tab" onclick="switchMode('rsvp')">✓ RSVP</button>
                <button class="mode-tab" onclick="switchMode('etiket')" id="etiket-tab" style="display: none;">📱 E-Tiket</button>
            </div>
            
            <!-- MODE 1: UNDANGAN -->
            <div id="mode-undangan" class="mode-content">
                <p style="text-align: center; color: #b5a0ff; margin-bottom: 30px;">📅 Sabtu, 14 Maret 2026 | 17:00 WIB</p>
                
                <div class="countdown-grid">
                    <div class="countdown-item">
                        <div class="countdown-value" id="days">0</div>
                        <div class="countdown-label">Hari</div>
                    </div>
                    <div class="countdown-item">
                        <div class="countdown-value" id="hours">0</div>
                        <div class="countdown-label">Jam</div>
                    </div>
                    <div class="countdown-item">
                        <div class="countdown-value" id="minutes">0</div>
                        <div class="countdown-label">Menit</div>
                    </div>
                    <div class="countdown-item">
                        <div class="countdown-value" id="seconds">0</div>
                        <div class="countdown-label">Detik</div>
                    </div>
                </div>
                
                <div style="background: rgba(255, 255, 255, 0.05); padding: 30px; border-radius: 15px; border: 1px solid #d4af37; margin-bottom: 30px;">
                    <div style="margin-bottom: 20px; display: flex; gap: 15px;">
                        <div style="font-size: 1.5rem;">📍</div>
                        <div>
                            <h3 style="color: #b5a0ff; font-size: 0.9rem; margin-bottom: 5px; text-transform: uppercase;">LOKASI</h3>
                            <p style="color: #d4af37; font-size: 1.1rem; font-weight: 600;">Tirtha Cafe Uluwatu, Bali</p>
                        </div>
                    </div>
                    <div style="margin-bottom: 20px; display: flex; gap: 15px;">
                        <div style="font-size: 1.5rem;">👗</div>
                        <div>
                            <h3 style="color: #b5a0ff; font-size: 0.9rem; margin-bottom: 5px; text-transform: uppercase;">DRESS CODE</h3>
                            <p style="color: #d4af37; font-size: 1.1rem; font-weight: 600;">Formal / Tradisional Bali</p>
                        </div>
                    </div>
                    <div style="display: flex; gap: 15px;">
                        <div style="font-size: 1.5rem;">🎫</div>
                        <div>
                            <h3 style="color: #b5a0ff; font-size: 0.9rem; margin-bottom: 5px; text-transform: uppercase;">INVOICE</h3>
                            <p style="color: #d4af37; font-size: 1.1rem; font-weight: 600;"><?php echo $inv_data['invoice_number']; ?></p>
                        </div>
                    </div>
                </div>
                
                <div style="background: rgba(212, 175, 55, 0.08); padding: 30px; border-radius: 15px; border: 1px solid #d4af37;">
                    <h3 style="color: #d4af37; margin-bottom: 15px; font-size: 1.1rem;">🎊 ACARA SPESIAL</h3>
                    <div style="color: #e0e0e0; line-height: 1.8;">
                        <p style="margin-bottom: 10px;">✓ Upacara Pernikahan & Resepsi</p>
                        <p style="margin-bottom: 10px;">✓ Pertunjukan Budaya Bali</p>
                        <p style="margin-bottom: 10px;">✓ Hidangan Bali Autentik & Internasional</p>
                        <p>✓ Akomodasi di GH Bali Resort</p>
                    </div>
                </div>
            </div>
            
            <!-- MODE 2: RSVP FORM -->
            <div id="mode-rsvp" class="mode-content hidden">
                <?php if ($rsvp_status === 'success'): ?>
                    <div class="success-message">
                        ✓ Terima kasih! Respons Anda telah diterima. Silakan lihat E-Tiket Anda di tab berikutnya.
                    </div>
                <?php endif; ?>
                
                <div class="rsvp-section">
                    <h3>📝 Konfirmasi Kehadiran</h3>
                    
                    <div class="stats-section">
                        <div class="stat-box">
                            <div class="stat-number"><?php echo $stat_data['hadir_count'] ?: 0; ?></div>
                            <div class="stat-label">Akan Hadir</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-number"><?php echo $stat_data['tidak_hadir_count'] ?: 0; ?></div>
                            <div class="stat-label">Tidak Dapat</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-number"><?php echo ($stat_data['hadir_count'] ?: 0) + ($stat_data['tidak_hadir_count'] ?: 0); ?></div>
                            <div class="stat-label">Total</div>
                        </div>
                    </div>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label>Nama Lengkap *</label>
                            <input type="text" name="nama_tamu" placeholder="Masukkan nama Anda..." required>
                        </div>
                        
                        <div class="form-group">
                            <label>Nomor WhatsApp (Opsional)</label>
                            <input type="tel" name="no_hp" placeholder="Contoh: 62812345678">
                        </div>
                        
                        <div class="form-group">
                            <label>Konfirmasi Kehadiran *</label>
                            <div class="radio-group">
                                <div class="radio-item">
                                    <input type="radio" id="hadir" name="respon" value="hadir" checked>
                                    <label for="hadir">✓ Saya Akan Hadir</label>
                                </div>
                                <div class="radio-item">
                                    <input type="radio" id="tidak_hadir" name="respon" value="tidak_hadir">
                                    <label for="tidak_hadir">✗ Saya Tidak Dapat</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group" id="jumlah_group">
                            <label>Jumlah Tamu</label>
                            <input type="number" name="jumlah_hadir" value="1" min="1" max="10">
                        </div>
                        
                        <button type="submit" name="submit_rsvp" class="btn btn-primary">
                            ✓ Kirim RSVP & Dapatkan E-Tiket
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- MODE 3: E-TIKET CARD -->
            <div id="mode-etiket" class="mode-content hidden">
                <?php if (isset($_SESSION['rsvp_tamu'])): ?>
                    <div class="etiket-card">
                        <h3>🎫 TIKET MASUK EKSKLUSIF</h3>
                        
                        <div class="etiket-card-box">
                            <p style="font-size: 1.2rem; font-weight: 700; margin-bottom: 15px;">
                                <?php echo $_SESSION['rsvp_tamu']['nama']; ?>
                            </p>
                            
                            <div class="barcode-container">
                                <svg id="barcode"></svg>
                            </div>
                            
                            <div class="etiket-number">
                                <?php echo $_SESSION['rsvp_tamu']['etiket_number']; ?>
                            </div>
                            
                            <div class="etiket-info">
                                <strong>Status Kehadiran:</strong> <?php echo $_SESSION['rsvp_tamu']['status']; ?>
                                <strong>Jumlah Tamu: <?php echo $_SESSION['rsvp_tamu']['jumlah']; ?> Orang</strong>
                                <strong style="margin-top: 15px; font-size: 0.85rem; color: #666;">Tunjukkan barcode ini saat check-in</strong>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px; color: #b5a0ff;">
                        <p style="font-size: 1.1rem;">📭 E-Tiket belum tersedia</p>
                        <p style="margin-top: 10px;">Silakan isi form RSVP terlebih dahulu untuk mendapatkan E-Tiket Anda</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>💍 Dede & Prasetya 💍</strong></p>
            <p>Pernikahan Eksklusif | 14 Maret 2026 | Bali</p>
            <p style="margin-top: 15px; opacity: 0.7;">Powered by Embun Visual</p>
        </div>
    </div>

    <script>
        function bukaUndangan() {
            const guestName = document.getElementById('guest-name').value.trim();
            if (!guestName) {
                alert('Silakan masukkan nama Anda terlebih dahulu');
                return;
            }
            document.getElementById('welcome-overlay').style.display = 'none';
            document.getElementById('main-invitation').style.display = 'block';
            sessionStorage.setItem('guest-name', guestName);
            updateCountdown();
            setInterval(updateCountdown, 1000);
        }
        
        function switchMode(mode) {
            document.querySelectorAll('.mode-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.mode-tab').forEach(el => el.classList.remove('active'));
            
            if (mode === 'undangan') {
                document.getElementById('mode-undangan').classList.remove('hidden');
                document.querySelectorAll('.mode-tab')[0].classList.add('active');
            } else if (mode === 'rsvp') {
                document.getElementById('mode-rsvp').classList.remove('hidden');
                document.querySelectorAll('.mode-tab')[1].classList.add('active');
            } else if (mode === 'etiket') {
                document.getElementById('mode-etiket').classList.remove('hidden');
                document.querySelectorAll('.mode-tab')[2].classList.add('active');
                generateBarcode();
            }
        }
        
        function updateCountdown() {
            const targetDate = new Date('2026-03-14T17:00:00').getTime();
            const now = new Date().getTime();
            const remaining = targetDate - now;
            
            if (remaining > 0) {
                document.getElementById('days').textContent = Math.floor(remaining / (1000 * 60 * 60 * 24));
                document.getElementById('hours').textContent = Math.floor((remaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                document.getElementById('minutes').textContent = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
                document.getElementById('seconds').textContent = Math.floor((remaining % (1000 * 60)) / 1000);
            }
        }
        
        function generateBarcode() {
            <?php if (isset($_SESSION['rsvp_tamu'])): ?>
                JsBarcode("#barcode", "<?php echo $_SESSION['rsvp_tamu']['barcode_value']; ?>", {
                    format: "CODE128",
                    width: 2,
                    height: 80,
                    displayValue: false
                });
            <?php endif; ?>
        }
        
        // Toggle jumlah field
        document.querySelectorAll('input[name="respon"]').forEach(input => {
            input.addEventListener('change', () => {
                const jumlahGroup = document.getElementById('jumlah_group');
                jumlahGroup.style.display = document.getElementById('hadir').checked ? 'block' : 'none';
            });
        });
        
        // Show E-Tiket tab if RSVP already submitted
        <?php if ($rsvp_status === 'success'): ?>
            document.getElementById('etiket-tab').style.display = 'block';
            switchMode('etiket');
        <?php endif; ?>
    </script>
</body>
</html>
