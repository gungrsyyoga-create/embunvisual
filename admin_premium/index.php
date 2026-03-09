<?php
// admin_premium/index.php - Premium Client Dashboard
require_once __DIR__ . '/../config/bootstrap.php';

// Auth guard
if (!isset($_SESSION['klien_premium_id'])) {
    header("Location: login.php"); exit;
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php"); exit;
}

$pesanan_id = (int)$_SESSION['klien_pesanan_id'];
$nama       = $_SESSION['klien_nama'];
$invoice    = $_SESSION['klien_invoice'];
$tema       = $_SESSION['klien_tema'];
$tgl_acara  = $_SESSION['klien_tanggal'];
$st_kerja   = $_SESSION['klien_status_kerja'] ?? 'Belum Dimulai';
$st_bayar   = $_SESSION['klien_status_bayar'] ?? '-';
$nama_staff = $_SESSION['klien_nama_staff'] ?? 'Tim Embun Visual';


// Refresh status & tier dari DB
$q_update = mysqli_fetch_assoc(mysqli_query($conn, "SELECT p.status_pembayaran, p.status_pengerjaan, p.catatan_revisi, kp.tipe as tier, kp.folder_path FROM pesanan p LEFT JOIN klien_premium kp ON kp.pesanan_id=p.id WHERE p.id='$pesanan_id'"));
if ($q_update) { $st_bayar = $q_update['status_pembayaran']; $st_kerja = $q_update['status_pengerjaan']; }
$tier = $q_update['tier'] ?? 'Basic';
$folder_path = $q_update['folder_path'] ?? '';
$is_exclusive = ($tier === 'Exclusive');
$is_basic = ($tier === 'Basic');

// Progress step mapping
$steps = ['Belum Dimulai' => 1, 'Dikerjakan' => 2, 'Perlu Revisi' => 2, 'Menunggu Verifikasi' => 3, 'Selesai' => 4];
$current_step = $steps[$st_kerja] ?? 1;

// RSVP stats (from tamu_undangan if available)
$total_tamu = 0; $hadir = 0; $tidak_hadir = 0;
$q_rsvp_stats = mysqli_query($conn, "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status_rsvp='Hadir' THEN 1 ELSE 0 END) as hadir,
    SUM(CASE WHEN status_rsvp='Tidak Hadir' THEN 1 ELSE 0 END) as tidak_hadir
    FROM tamu_undangan WHERE pesanan_id='$pesanan_id'");
if ($q_rsvp_stats && mysqli_num_rows($q_rsvp_stats) > 0) {
    $rsvp_data = mysqli_fetch_assoc($q_rsvp_stats);
    $total_tamu = (int)($rsvp_data['total'] ?? 0);
    $hadir = (int)($rsvp_data['hadir'] ?? 0);
    $tidak_hadir = (int)($rsvp_data['tidak_hadir'] ?? 0);
}

// Chat messages (last 50)
$q_chat = mysqli_query($conn, "SELECT id, pengirim, nama_pengirim, pesan, gambar_path, created_at FROM pesan_proyek WHERE pesanan_id='$pesanan_id' ORDER BY id ASC LIMIT 50");
$messages = [];
$last_msg_id = 0;
while ($row = mysqli_fetch_assoc($q_chat)) { $messages[] = $row; $last_msg_id = $row['id']; }

// RSVP list
$q_rsvp_list = mysqli_query($conn, "SELECT nama_tamu, status_rsvp, jumlah_hadir, created_at FROM tamu_undangan WHERE pesanan_id='$pesanan_id' ORDER BY id DESC LIMIT 20");
$rsvp_list = [];
while ($row = mysqli_fetch_assoc($q_rsvp_list)) { $rsvp_list[] = $row; }

// Days countdown
$hari_ini = date('Y-m-d');
$hari_h = $tgl_acara;
$diff = (strtotime($hari_h) - strtotime($hari_ini)) / 86400;
$hitung_mundur = max(0, (int)$diff);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Klien | Embun Visual Premium</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400;1,600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary: #1A1614;
            --gold: #D4AF37;
            --gold-light: rgba(212,175,55,0.10);
            --bg: #FAF8F5;
            --surface: #FFFFFF;
            --border: #EAE3D9;
            --text: #2A2522;
            --muted: #6B6560;
            --sidebar-w: 280px;
            --serif: 'Playfair Display', serif;
            --sans: 'Inter', sans-serif;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: var(--sans); background: var(--bg); color: var(--text); display: flex; min-height: 100vh; -webkit-font-smoothing: antialiased; }
        a { text-decoration: none; color: inherit; }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w); background: var(--primary); color: #fff;
            position: fixed; height: 100vh; top: 0; left: 0;
            display: flex; flex-direction: column; padding: 35px 25px;
            z-index: 100; overflow-y: auto;
        }
        .sidebar-logo { display: flex; align-items: center; gap: 10px; margin-bottom: 40px; }
        .sidebar-logo i { color: var(--gold); font-size: 1.4rem; }
        .sidebar-logo span { font-family: var(--serif); font-size: 1.3rem; font-style: italic; color: #fff; }
        .client-badge {
            background: rgba(212,175,55,0.12); border: 1px solid rgba(212,175,55,0.3);
            border-radius: 12px; padding: 16px 18px; margin-bottom: 40px;
        }
        .client-badge .name { font-family: var(--serif); font-style: italic; font-size: 1.1rem; color: #fff; margin-bottom: 4px; }
        .client-badge .inv { font-size: 0.75rem; color: rgba(255,255,255,0.5); letter-spacing: 1px; }
        .nav-item {
            display: flex; align-items: center; gap: 12px;
            padding: 13px 16px; border-radius: 10px;
            color: rgba(255,255,255,0.6); font-size: 0.9rem; font-weight: 500;
            margin-bottom: 4px; transition: all 0.25s; cursor: pointer;
        }
        .nav-item i { width: 18px; text-align: center; }
        .nav-item:hover, .nav-item.active { background: rgba(212,175,55,0.12); color: var(--gold); }
        .nav-item.active { color: var(--gold); border-left: 3px solid var(--gold); }
        .sidebar-footer { margin-top: auto; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); }   
        .logout-btn {
            display: flex; align-items: center; gap: 10px;
            padding: 12px 16px; border-radius: 10px; color: rgba(255,255,255,0.5);
            font-size: 0.85rem; transition: all 0.2s;
        }
        .logout-btn:hover { color: #ef4444; background: rgba(239,68,68,0.1); }

        /* ── Main ── */
        .main { margin-left: var(--sidebar-w); flex: 1; padding: 40px 45px; }

        /* ── Header ── */
        .page-header { margin-bottom: 35px; }
        .page-header h1 { font-family: var(--serif); font-size: 2.2rem; font-style: italic; color: var(--primary); margin-bottom: 5px; }
        .page-header p { color: var(--muted); font-size: 0.9rem; font-weight: 300; }

        /* ── Cards ── */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }
        .stat-card {
            background: var(--surface); border-radius: 18px; padding: 24px;
            border: 1px solid var(--border);
            box-shadow: 0 8px 25px rgba(0,0,0,0.04);
            display: flex; align-items: center; gap: 18px;
        }
        .stat-icon {
            width: 56px; height: 56px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;
        }
        .stat-info h3 { font-size: 1.8rem; font-weight: 700; color: var(--primary); line-height: 1; margin-bottom: 4px; }
        .stat-info p { font-size: 0.8rem; color: var(--muted); font-weight: 400; }

        /* ── Progress ── */
        .progress-card { background: var(--surface); border-radius: 18px; padding: 30px 35px; border: 1px solid var(--border); box-shadow: 0 8px 25px rgba(0,0,0,0.04); margin-bottom: 30px; }
        .progress-title { font-family: var(--serif); font-size: 1.3rem; font-style: italic; color: var(--primary); margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; }
        .steps { display: flex; align-items: center; gap: 0; position: relative; }
        .step-item { flex: 1; display: flex; flex-direction: column; align-items: center; position: relative; }
        .step-circle {
            width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; font-weight: 700; border: 2px solid var(--border); background: var(--bg); color: var(--muted);
            position: relative; z-index: 2; transition: all 0.4s;
        }
        .step-circle.done { background: var(--gold); border-color: var(--gold); color: #fff; box-shadow: 0 5px 15px rgba(212,175,55,0.35); }
        .step-circle.active { background: var(--primary); border-color: var(--primary); color: #fff; box-shadow: 0 5px 15px rgba(26,22,20,0.2); }
        .step-label { font-size: 0.75rem; color: var(--muted); margin-top: 10px; text-align: center; font-weight: 400; }
        .step-label.active-label { color: var(--primary); font-weight: 600; }
        .step-line { position: absolute; top: 24px; left: 50%; width: 100%; height: 2px; background: var(--border); z-index: 1; }
        .step-line.done { background: var(--gold); }
        .step-item:last-child .step-line { display: none; }

        /* ── Content Grid ── */
        .content-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 25px; }
        .card { background: var(--surface); border-radius: 18px; border: 1px solid var(--border); box-shadow: 0 8px 25px rgba(0,0,0,0.04); overflow: hidden; }
        .card-header { padding: 20px 25px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .card-header h3 { font-family: var(--serif); font-size: 1.1rem; font-style: italic; color: var(--primary); }
        .card-header i { color: var(--gold); margin-right: 8px; }
        .card-body { padding: 20px 25px; }
        
        /* RSVP Table */
        .rsvp-table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
        .rsvp-table th { color: var(--muted); font-weight: 600; text-align: left; padding: 8px 12px; border-bottom: 1px solid var(--border); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .rsvp-table td { padding: 12px 12px; border-bottom: 1px solid rgba(234,227,217,0.5); }
        .rsvp-table tr:last-child td { border-bottom: none; }
        .badge { padding: 4px 12px; border-radius: 30px; font-size: 0.75rem; font-weight: 600; white-space: nowrap; }
        .badge-hadir { background: #dcfce7; color: #15803d; }
        .badge-tidakhadir { background: #fee2e2; color: #dc2626; }
        .badge-belum { background: #fef9c3; color: #854d0e; }

        /* ── Chat ── */
        .chat-card { grid-column: 1 / -1; }
        .chat-body { padding: 0; }
        .chat-messages {
            height: 350px; overflow-y: auto; padding: 20px 25px; display: flex; flex-direction: column; gap: 14px;
            background: linear-gradient(to bottom, #fdfcfb, var(--surface));
        }
        .chat-messages::-webkit-scrollbar { width: 4px; }
        .chat-messages::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }
        .msg-wrap { display: flex; gap: 10px; align-items: flex-end; }
        .msg-wrap.client { flex-direction: row-reverse; }
        .msg-avatar {
            width: 34px; height: 34px; border-radius: 50%; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 700;
        }
        .msg-avatar.admin-av { background: var(--primary); color: #fff; }
        .msg-avatar.client-av { background: var(--gold); color: var(--primary); }
        .msg-bubble { max-width: 55%; padding: 12px 16px; border-radius: 16px; font-size: 0.88rem; line-height: 1.6; }
        .msg-wrap.client .msg-bubble { background: var(--primary); color: #fff; border-bottom-right-radius: 4px; }
        .msg-wrap.admin .msg-bubble { background: var(--bg); color: var(--text); border: 1px solid var(--border); border-bottom-left-radius: 4px; }
        .msg-meta { font-size: 0.7rem; color: var(--muted); margin-top: 4px; }
        .msg-wrap.client .msg-meta { text-align: right; }
        .chat-input-bar {
            padding: 15px 20px; border-top: 1px solid var(--border);
            display: flex; gap: 12px; background: #fff;
        }
        .chat-input {
            flex: 1; padding: 12px 18px; border: 1px solid var(--border);
            border-radius: 50px; font-family: var(--sans); font-size: 0.9rem;
            background: var(--bg); outline: none; transition: all 0.25s;
        }
        .chat-input:focus { border-color: var(--gold); background: #fff; }
        .chat-send {
            background: var(--primary); color: #fff; border: none;
            width: 46px; height: 46px; border-radius: 50%; cursor: pointer;
            display: flex; align-items: center; justify-content: center; font-size: 1rem;
            transition: all 0.25s; flex-shrink: 0;
        }
        .chat-send:hover { background: var(--gold); }
        .chat-empty { text-align: center; color: var(--muted); padding: 50px 20px; font-size: 0.9rem; }
        .chat-empty i { font-size: 2rem; display: block; margin-bottom: 10px; opacity: 0.3; }

        /* ── Detail Info Card ── */
        .detail-row { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid rgba(234,227,217,0.5); }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-size: 0.8rem; color: var(--muted); font-weight: 400; }
        .detail-val { font-size: 0.9rem; font-weight: 600; color: var(--primary); }

        /* ── Section Tabs ── */
        .section { display: none; }
        .section.active { display: block; }

        @media (max-width: 1100px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .content-grid { grid-template-columns: 1fr; }
            .chat-card { grid-column: 1 / -1; }
        }
        @media (max-width: 768px) {
            .sidebar { width: 0; padding: 0; overflow: hidden; }
            .main { margin-left: 0; padding: 20px; }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <i class="fas fa-leaf"></i>
            <span>Embun Visual</span>
        </div>
        <div class="client-badge">
            <div class="name"><?= htmlspecialchars($nama) ?></div>
            <div class="inv"><?= htmlspecialchars($invoice) ?></div>
            <?php if ($is_exclusive): ?>
            <div style="margin-top:8px; display:inline-flex; align-items:center; gap:5px; background:linear-gradient(135deg,#D4AF37,#B8960C); color:#fff; padding:4px 12px; border-radius:30px; font-size:0.7rem; font-weight:700; letter-spacing:1px;">
                <i class="fas fa-gem" style="font-size:0.65rem;"></i> EXCLUSIVE
            </div>
            <?php elseif($is_basic): ?>
            <div style="margin-top:8px; display:inline-flex; align-items:center; gap:5px; background:#64748b; color:#fff; padding:4px 12px; border-radius:30px; font-size:0.7rem; font-weight:700; letter-spacing:1px;">
                <i class="fas fa-user" style="font-size:0.65rem;"></i> BASIC
            </div>
            <?php else: ?>
            <div style="margin-top:8px; display:inline-flex; align-items:center; gap:5px; background:var(--primary); color:#fff; padding:4px 12px; border-radius:30px; font-size:0.7rem; font-weight:700; letter-spacing:1px;">
                <i class="fas fa-crown" style="font-size:0.65rem;"></i> PREMIUM
            </div>
            <?php endif; ?>
        </div>
        <nav>
            <?php if(!empty($folder_path)): ?>
            <a href="/embunvisual/<?= $folder_path ?>" target="_blank" class="nav-item" style="color:var(--gold); border: 1px solid rgba(212,175,55,0.3); margin-bottom:15px; background:rgba(212,175,55,0.05);">
                <i class="fas fa-external-link-alt"></i> Lihat Undangan
            </a>
            <?php endif; ?>
            <a class="nav-item active" onclick="showSection('beranda', this)">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a class="nav-item" onclick="showSection('rsvp', this)">
                <i class="fas fa-clipboard-check"></i> Data Tamu & RSVP
            </a>
            <a class="nav-item" onclick="showSection('chat', this)">
                <i class="fas fa-comments"></i> Chat dengan Tim
            </a>
            <a class="nav-item" onclick="showSection('detail', this)">
                <i class="fas fa-file-invoice"></i> Detail Pesanan
            </a>
            <?php if ($is_exclusive): ?>
            <a class="nav-item" onclick="showSection('exclusive', this)" style="color:#D4AF37; background:rgba(212,175,55,0.08);">
                <i class="fas fa-gem"></i> Exclusive Perks
            </a>
            <?php endif; ?>
        </nav>
        <div class="sidebar-footer">
            <a href="?logout=true" class="logout-btn" onclick="return confirm('Yakin ingin keluar?')">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </a>
        </div>
    </aside>

    <!-- Main -->
    <main class="main">

        <!-- SECTION: BERANDA -->
        <div id="sec-beranda" class="section active">
            <div class="page-header">
                <h1>Halo, <?= htmlspecialchars(explode(' ', $nama)[0]) ?>!</h1>
                <p>Selamat datang di portal premium Embun Visual. Pantau perkembangan undangan digital Anda di sini.</p>
            </div>

            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: var(--gold-light); color: var(--gold);">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $hitung_mundur ?></h3>
                        <p>Hari Menuju Acara</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: #e0f2fe; color: #0369a1;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $total_tamu ?></h3>
                        <p>Total Tamu Diundang</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: #dcfce7; color: #15803d;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $hadir ?></h3>
                        <p>Konfirmasi Hadir</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: #fee2e2; color: #dc2626;">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $tidak_hadir ?></h3>
                        <p>Tidak Hadir</p>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="progress-card">
                <div class="progress-title">
                    <span><i class="fas fa-tasks" style="color:var(--gold); margin-right:8px;"></i> Status Pengerjaan Undangan</span>
                    <span style="font-family: var(--sans); font-size: 0.8rem; font-style: normal; color: var(--muted);">
                        Staff: <strong style="color: var(--primary);"><?= htmlspecialchars($nama_staff) ?></strong>
                    </span>
                </div>
                <?php
                $step_defs = [
                    1 => ['label' => 'Pesanan Diterima', 'icon' => 'fa-receipt'],
                    2 => ['label' => 'Sedang Dikerjakan', 'icon' => 'fa-paint-brush'],
                    3 => ['label' => 'Review & Revisi', 'icon' => 'fa-eye'],
                    4 => ['label' => 'Selesai & Terkirim', 'icon' => 'fa-check-double'],
                ];
                ?>
                <div class="steps">
                    <?php foreach ($step_defs as $num => $step): ?>
                    <div class="step-item">
                        <div class="step-circle <?= $num < $current_step ? 'done' : ($num === $current_step ? 'active' : '') ?>">
                            <?php if ($num < $current_step): ?>
                                <i class="fas fa-check"></i>
                            <?php else: ?>
                                <i class="fas <?= $step['icon'] ?>"></i>
                            <?php endif; ?>
                        </div>
                        <div class="step-line <?= $num < $current_step ? 'done' : '' ?>"></div>
                        <div class="step-label <?= $num === $current_step ? 'active-label' : '' ?>">
                            <?= $step['label'] ?>
                            <?php if ($num === $current_step): ?>
                                <br><small style="color: var(--gold); font-weight: 700;">← Saat ini</small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($st_kerja === 'Perlu Revisi'): 
                    $catatan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT catatan_revisi FROM pesanan WHERE id='$pesanan_id'"))['catatan_revisi'] ?? '';
                ?>
                <div style="margin-top: 25px; padding: 16px 20px; background: #fef9c3; border-radius: 10px; border-left: 4px solid #eab308; font-size: 0.88rem; color: #713f12;">
                    <strong><i class="fas fa-exclamation-triangle"></i> Catatan Revisi dari Admin:</strong><br>
                    <?= htmlspecialchars($catatan) ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Quick Chat Preview -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-comments"></i> Pesan Terbaru</h3>
                    <a onclick="showSection('chat', document.querySelectorAll('.nav-item')[2])" style="font-size: 0.8rem; color: var(--gold); cursor: pointer;">Lihat Semua →</a>
                </div>
                <div class="card-body" style="padding: 15px 25px;">
                    <?php if (empty($messages)): ?>
                        <p style="color: var(--muted); font-size: 0.88rem; text-align: center; padding: 20px 0;">Belum ada percakapan. Kirim pesan ke tim kami!</p>
                    <?php else:
                        $last3 = array_slice($messages, -3);
                        foreach ($last3 as $msg): ?>
                        <div style="padding: 10px 0; border-bottom: 1px solid rgba(234,227,217,0.5); display: flex; gap: 10px; align-items: flex-start;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: <?= $msg['pengirim'] === 'admin' ? 'var(--primary)' : 'var(--gold)' ?>; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 0.75rem; font-weight: 700; flex-shrink: 0;"><?= strtoupper(substr($msg['nama_pengirim'], 0, 1)) ?></div>
                            <div>
                                <div style="font-size: 0.78rem; font-weight: 600; margin-bottom: 2px; color: var(--muted);"><?= htmlspecialchars($msg['nama_pengirim']) ?></div>
                                <div style="font-size: 0.88rem; color: var(--text);"><?= htmlspecialchars($msg['pesan']) ?></div>
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        </div>

        <!-- SECTION: RSVP -->
        <div id="sec-rsvp" class="section">
            <div class="page-header">
                <h1>Data Tamu & RSVP</h1>
                <p>Pantau daftar tamu yang sudah mengkonfirmasi kehadiran mereka.</p>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-clipboard-list"></i> Daftar Konfirmasi Hadir</h3>
                    <span style="font-size: 0.8rem; color: var(--muted);"><?= $hadir ?> Hadir • <?= $tidak_hadir ?> Tidak Hadir • <?= ($total_tamu - $hadir - $tidak_hadir) ?> Belum</span>
                </div>
                <div class="card-body" style="padding: 10px 0;">
                    <?php if (empty($rsvp_list)): ?>
                    <p style="text-align: center; color: var(--muted); padding: 50px; font-size: 0.9rem;"><i class="fas fa-users fa-2x" style="display:block; margin-bottom:10px; opacity:0.3;"></i>Belum ada data RSVP dari tamu.</p>
                    <?php else: ?>
                    <table class="rsvp-table">
                        <thead>
                            <tr>
                                <th style="padding-left: 25px;">Nama Tamu</th>
                                <th>Status</th>
                                <th>Jml Hadir</th>
                                <th>Waktu Konfirmasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rsvp_list as $r): 
                                $badge_class = $r['status_rsvp'] === 'Hadir' ? 'badge-hadir' : ($r['status_rsvp'] === 'Tidak Hadir' ? 'badge-tidakhadir' : 'badge-belum');
                            ?>
                            <tr>
                                <td style="font-weight: 600; padding-left: 25px;"><?= htmlspecialchars($r['nama_tamu']) ?></td>
                                <td><span class="badge <?= $badge_class ?>"><?= htmlspecialchars($r['status_rsvp'] ?? 'Belum') ?></span></td>
                                <td><?= (int)($r['jumlah_hadir'] ?? 0) ?> orang</td>
                                <td style="color: var(--muted); font-size: 0.82rem;"><?= date('d M Y, H:i', strtotime($r['created_at'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>

                <!-- SECTION: CHAT -->
        <div id="sec-chat" class="section">
            <div class="page-header">
                <h1>Chat dengan Tim</h1>
                <p>Kirim pertanyaan atau permintaan langsung ke staff yang mengerjakan undangan Anda.</p>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-user-headset"></i> Tim: <?= htmlspecialchars($nama_staff) ?></h3>
                    <div style="display: flex; align-items: center; gap: 6px; font-size: 0.78rem; color: #16a34a;">
                        <span style="width: 8px; height: 8px; background: #16a34a; border-radius: 50%; display: inline-block;"></span> Online
                    </div>
                </div>
                <div class="chat-body">
                    <div class="chat-messages" id="chatBody">
                        <?php if (empty($messages)): ?>
                        <div class="chat-empty">
                            <i class="fas fa-comment-dots"></i>
                            Belum ada percakapan. Mulailah dengan menyapa tim kami!
                        </div>
                        <?php else: ?>
                            <?php foreach ($messages as $m):
                                $is_client = $m['pengirim'] === 'klien';
                                $initials = strtoupper(substr($m['nama_pengirim'], 0, 1));
                                $time = date('H:i', strtotime($m['created_at']));
                            ?>
                            <div class="msg-wrap <?= $is_client ? 'client' : 'admin' ?>">
                                <div class="msg-avatar <?= $is_client ? 'client-av' : 'admin-av' ?>"><?= $initials ?></div>
                                <div>
                                    <?php if (!empty($m['gambar_path'])): ?>
                                    <div style="margin-bottom:4px;">
                                        <a href="/embunvisual/<?= htmlspecialchars($m['gambar_path']) ?>" target="_blank">
                                            <img src="/embunvisual/<?= htmlspecialchars($m['gambar_path']) ?>" style="max-width:200px; max-height:150px; border-radius:12px; display:block; border:2px solid <?= $is_client ? 'var(--gold)' : 'var(--primary)' ?>;">
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                    <?php if (!empty($m['pesan'])): ?>
                                    <div class="msg-bubble"><?= nl2br(htmlspecialchars($m['pesan'])) ?></div>
                                    <?php endif; ?>
                                    <div class="msg-meta"><?= htmlspecialchars($m['nama_pengirim']) ?> · <?= $time ?></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Image Preview Strip -->
                    <div id="clientImgPreview" style="display:none; padding:8px 16px; background:#fdf8f0; border-top:1px solid var(--border); flex-shrink:0;">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <img id="clientImgThumb" style="height:45px; border-radius:8px; border:1px solid var(--border);">
                            <span id="clientImgName" style="font-size:0.82rem; color:var(--muted); flex:1; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"></span>
                            <button onclick="clearClientImg()" style="background:none; border:none; color:#ef4444; cursor:pointer; font-size:1.1rem;">✕</button>
                        </div>
                    </div>

                    <!-- Emoji Picker -->
                    <div id="clientEmojiPicker" style="display:none; position:absolute; bottom:70px; left:16px; background:#fff; border:1px solid var(--border); border-radius:16px; padding:14px; box-shadow:0 12px 40px rgba(0,0,0,0.1); z-index:500; width:290px;">
                        <div style="display:grid; grid-template-columns:repeat(8,1fr); gap:4px; max-height:160px; overflow-y:auto;">
                            <?php
                            $emojis = ['😊','😂','🥰','😍','🤩','😎','🥳','🤗','😇','🙏','👍','❤️','🔥','✨','🎉','🎊','💯','🌹','🌸','🌟','💪','🤝','👏','🎵','📸','🎨','💌','📋','✅','⏰','🏃','🌙','☀️','🌈','🦋','🍀','🌺','💎','🏆','🎁','📱','💻','🔑','🎯','💰','📅','🗓️','⭐','🌿'];
                            foreach ($emojis as $e) {
                                echo "<button onclick=\"addEmoji('$e')\" style=\"background:none;border:none;cursor:pointer;padding:4px;border-radius:6px;font-size:1.2rem;line-height:1;\">$e</button>";
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Input Bar -->
                    <div class="chat-input-bar" style="position:relative;">
                        <button onclick="toggleClientEmoji(event)" title="Emoji" style="width:40px; height:40px; border-radius:50%; background:var(--bg); border:1px solid var(--border); cursor:pointer; font-size:1.1rem; display:flex; align-items:center; justify-content:center; flex-shrink:0;">😊</button>
                        <label for="clientImageInput" title="Kirim Gambar" style="width:40px; height:40px; border-radius:50%; background:var(--bg); border:1px solid var(--border); display:flex; align-items:center; justify-content:center; cursor:pointer; color:var(--muted); font-size:0.9rem; flex-shrink:0;">
                            <i class="fas fa-image"></i>
                        </label>
                        <input type="file" id="clientImageInput" accept="image/*" style="display:none;" onchange="previewClientImg(this)">
                        <input type="text" class="chat-input" id="chatInput" placeholder="Ketik pesan..." onkeydown="if(event.key==='Enter' && !event.shiftKey){kirimPesan();event.preventDefault();}">
                        <button class="chat-send" onclick="kirimPesan()">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION: DETAIL -->
        <div id="sec-detail" class="section">
            <div class="page-header">
                <h1>Detail Pesanan</h1>
                <p>Informasi lengkap mengenai pesanan undangan digital Anda.</p>
            </div>
            <div class="card" style="max-width: 600px;">
                <div class="card-header"><h3><i class="fas fa-file-invoice"></i> Ringkasan Pesanan</h3></div>
                <div class="card-body">
                    <div class="detail-row"><span class="detail-label">Nomor Invoice</span><span class="detail-val"><?= htmlspecialchars($invoice) ?></span></div>
                    <div class="detail-row"><span class="detail-label">Nama Pemesan</span><span class="detail-val"><?= htmlspecialchars($nama) ?></span></div>
                    <div class="detail-row"><span class="detail-label">Tema Dipilih</span><span class="detail-val"><?= htmlspecialchars($tema ?? '-') ?></span></div>
                    <div class="detail-row"><span class="detail-label">Tanggal Acara</span><span class="detail-val"><?= date('d F Y', strtotime($tgl_acara)) ?></span></div>
                    <div class="detail-row"><span class="detail-label">Status Pembayaran</span>
                        <span class="badge <?= $st_bayar === 'Lunas' ? 'badge-hadir' : 'badge-belum' ?>"><?= htmlspecialchars($st_bayar) ?></span>
                    </div>
                    <div class="detail-row"><span class="detail-label">Status Pengerjaan</span>
                        <span class="badge <?= $st_kerja === 'Selesai' ? 'badge-hadir' : 'badge-belum' ?>"><?= htmlspecialchars($st_kerja) ?></span>
                    </div>
                    <div class="detail-row"><span class="detail-label">Staff Penanggungjawab</span><span class="detail-val"><?= htmlspecialchars($nama_staff) ?></span></div>
                </div>
            </div>
        </div>

        <!-- SECTION: EXCLUSIVE PERKS (only for Exclusive tier) -->
        <?php if ($is_exclusive): ?>
        <div id="sec-exclusive" class="section">
            <div class="page-header">
                <h1><i class="fas fa-gem" style="color:#D4AF37;"></i> Exclusive Perks</h1>
                <p>Nikmati layanan exclusive yang kami siapkan spesial untuk Anda.</p>
            </div>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(260px,1fr)); gap:20px;">
                <?php
                $perks = [
                    ['fas fa-headset', 'Dedicated Support 24/7', 'Akses langsung ke tim senior Embun Visual kapan saja Anda butuhkan.', '#D4AF37'],
                    ['fas fa-palette', 'Unlimited Revisi', 'Tidak ada batasan jumlah revisi. Kami kerjakan sampai sempurna.', '#7C3AED'],
                    ['fas fa-film', 'Video Preview Animasi', 'Dapatkan video preview undangan digital sebelum dikirimkan ke tamu.', '#0284C7'],
                    ['fas fa-qrcode', 'QR Code Tamu Premium', 'Setiap tamu mendapat QR code akses VIP dengan nama personal.', '#059669'],
                    ['fas fa-gift', 'Digital Gift Book', 'Buku tamu digital interaktif dengan galeri foto & ucapan.', '#DC2626'],
                    ['fas fa-crown', 'Priority Processing', 'Pesanan Anda diprioritaskan oleh tim kami di atas antrian lainnya.', '#B7791F'],
                ];
                foreach ($perks as $p): ?>
                <div class="card" style="padding:25px; border-top:3px solid <?= $p[3] ?>;">
                    <div style="width:50px; height:50px; border-radius:14px; background:<?= $p[3] ?>22; display:flex; align-items:center; justify-content:center; margin-bottom:16px;">
                        <i class="<?= $p[0] ?>" style="font-size:1.3rem; color:<?= $p[3] ?>;"></i>
                    </div>
                    <h3 style="font-family:var(--serif); font-style:italic; color:var(--primary); margin-bottom:8px; font-size:1.05rem;"><?= $p[1] ?></h3>
                    <p style="font-size:0.85rem; color:var(--muted); line-height:1.6;"><?= $p[2] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="card" style="margin-top:25px; padding:25px; background:linear-gradient(135deg,#1A1614,#2d2520); color:#fff; border:none;">
                <div style="display:flex; align-items:center; gap:15px;">
                    <i class="fas fa-gem" style="font-size:2rem; color:#D4AF37;"></i>
                    <div>
                        <h3 style="color:#D4AF37; font-family:var(--serif); font-style:italic; margin-bottom:5px;">Anda adalah Klien Exclusive Kami</h3>
                        <p style="font-size:0.85rem; opacity:0.8;">Terima kasih telah mempercayakan momen spesial Anda kepada Embun Visual. Tim kami berkomitmen memberikan yang terbaik.</p>
                    </div>
                    <button onclick="showSection('chat', document.querySelectorAll('.nav-item')[2])" style="padding:12px 22px; border-radius:30px; border:none; background:#D4AF37; color:#1A1614; font-weight:700; cursor:pointer; white-space:nowrap; font-size:0.88rem; margin-left:auto; flex-shrink:0;">
                        <i class="fas fa-comments"></i> Hubungi Tim
                    </button>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </main>

    <script>
        // ── Section Switching ──
        function showSection(name, el) {
            document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
            document.getElementById('sec-' + name).classList.add('active');
            document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
            if (el) el.classList.add('active');
            if (name === 'chat') scrollChatBottom();
        }

        // ── Real-time Status Polling ──
        const STATUS_STEPS = {
            'Belum Dimulai': 1, 
            'Sedang Dikerjakan': 2, 'Dikerjakan': 2, 'Sedang Dikerjakan (Revisi)': 2,
            'Perlu Revisi': 2, 
            'Menunggu Verifikasi': 3, 
            'Selesai': 4
        };
        let currentStatusKerja = <?= json_encode($st_kerja) ?>;

        function updateProgressUI(newStatus) {
            const stepNum = STATUS_STEPS[newStatus] || 1;
            document.querySelectorAll('.step-item').forEach((el, i) => {
                const num = i + 1;
                const circle = el.querySelector('.step-circle');
                const line = el.querySelector('.step-line');
                const label = el.querySelector('.step-label');

                if (circle) {
                    circle.className = 'step-circle ' + (num < stepNum ? 'done' : (num === stepNum ? 'active' : ''));
                    if (num < stepNum) circle.innerHTML = '<i class="fas fa-check"></i>';
                }
                if (line) {
                    line.className = 'step-line ' + (num < stepNum ? 'done' : '');
                }
                if (label) {
                    label.className = 'step-label ' + (num === stepNum ? 'active-label' : '');
                    // Only move the "Saat ini" text if it's the current step
                    if (num === stepNum) {
                        if (!label.innerHTML.includes('Saat ini')) {
                            label.innerHTML += '<br><small style="color: var(--gold); font-weight: 700;">← Saat ini</small>';
                        }
                    } else {
                        label.innerHTML = label.innerHTML.replace('<br><small style="color: var(--gold); font-weight: 700;">← Saat ini</small>', '');
                    }
                }
            });
        }

        function pollStatus() {
            fetch('api_status.php')
                .then(r => r.json())
                .then(d => {
                    if (d.status === 'ok' && d.status_pengerjaan !== currentStatusKerja) {
                        const oldStatus = currentStatusKerja;
                        currentStatusKerja = d.status_pengerjaan;
                        updateProgressUI(currentStatusKerja);
                        // Toast notification (Using SweetAlert2 which is included)
                        Swal.fire({
                            toast: true, position: 'top-end', icon: 'info',
                            title: 'Status diperbarui!',
                            text: `"${oldStatus}" → "${currentStatusKerja}"`,
                            showConfirmButton: false, timer: 5000, timerProgressBar: true
                        });
                    }
                }).catch(() => {});
        }
        setInterval(pollStatus, 15000);

        // ── Chat ──
        let lastId = <?= $last_msg_id ?>;
        const myName = <?= json_encode($nama) ?>;
        let clientImgFile = null;

        function scrollChatBottom() {
            const cb = document.getElementById('chatBody');
            if (cb) cb.scrollTop = cb.scrollHeight;
        }

        function addEmoji(emoji) {
            const inp = document.getElementById('chatInput');
            if (!inp) return;
            const pos = inp.selectionStart;
            inp.value = inp.value.slice(0, pos) + emoji + inp.value.slice(pos);
            inp.selectionStart = inp.selectionEnd = pos + emoji.length;
            inp.focus();
            document.getElementById('clientEmojiPicker').style.display = 'none';
        }

        function toggleClientEmoji(e) {
            e.stopPropagation();
            const p = document.getElementById('clientEmojiPicker');
            p.style.display = p.style.display === 'none' ? 'block' : 'none';
        }
        document.addEventListener('click', () => {
            const p = document.getElementById('clientEmojiPicker');
            if (p) p.style.display = 'none';
        });

        function previewClientImg(input) {
            if (input.files && input.files[0]) {
                clientImgFile = input.files[0];
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('clientImgThumb').src = e.target.result;
                    document.getElementById('clientImgName').textContent = clientImgFile.name;
                    document.getElementById('clientImgPreview').style.display = 'block';
                };
                reader.readAsDataURL(clientImgFile);
            }
        }

        function clearClientImg() {
            clientImgFile = null;
            document.getElementById('clientImageInput').value = '';
            document.getElementById('clientImgPreview').style.display = 'none';
        }

        function buildClientMsg(m, isClient) {
            const init = m.nama_pengirim.charAt(0).toUpperCase();
            const time = new Date(m.created_at).toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'});
            const escaped = (m.pesan||'').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\n/g,'<br>');
            const imgHtml = m.gambar_path ? `<div style="margin-bottom:4px;"><a href="/embunvisual/${m.gambar_path}" target="_blank"><img src="/embunvisual/${m.gambar_path}" style="max-width:200px;max-height:150px;border-radius:12px;display:block;border:2px solid ${isClient?'var(--gold)':'var(--primary)'};"></a></div>` : '';
            const txtHtml = escaped ? `<div class="msg-bubble">${escaped}</div>` : '';
            return `<div class="msg-wrap ${isClient?'client':'admin'}">
                <div class="msg-avatar ${isClient?'client-av':'admin-av'}">${init}</div>
                <div>${imgHtml}${txtHtml}<div class="msg-meta">${m.nama_pengirim} · ${time}</div></div>
            </div>`;
        }

        function appendMessage(m) {
            const cb = document.getElementById('chatBody');
            const emptyEl = cb.querySelector('.chat-empty');
            if (emptyEl) emptyEl.remove();
            const isClient = m.pengirim === 'klien';
            cb.insertAdjacentHTML('beforeend', buildClientMsg(m, isClient));
            scrollChatBottom();
        }

        function kirimPesan() {
            const input = document.getElementById('chatInput');
            const pesan = input.value.trim();
            if (!pesan && !clientImgFile) { input.focus(); return; }

            const fd = new FormData();
            fd.append('pesan', pesan);
            if (clientImgFile) fd.append('gambar', clientImgFile);
            input.value = '';
            clearClientImg();

            fetch('api_chat.php?action=send', { method:'POST', body: fd })
                .then(r => r.json())
                .then(d => {
                    if (d.status === 'ok') {
                        appendMessage({ pengirim:'klien', nama_pengirim: myName, pesan: pesan, gambar_path: d.gambar_path || null, created_at: new Date().toISOString() });
                    }
                }).catch(console.error);
        }

        function pollMessages() {
            fetch(`api_chat.php?action=fetch&since=${lastId}`)
                .then(r => r.json())
                .then(d => {
                    if (d.status === 'ok' && d.messages.length > 0) {
                        d.messages.forEach(m => {
                            if (m.pengirim !== 'klien') { appendMessage(m); }
                            lastId = Math.max(lastId, parseInt(m.id));
                        });
                    }
                }).catch(console.error);
        }
        setInterval(pollMessages, 5000);
        scrollChatBottom();
    </script>
</body>
</html>
