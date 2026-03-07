<?php
// Full Exclusive Invitation - Yoga & Ayu
// Linked to Order ID 22 (Invoice: INV-20260307-988)
$pesanan_id = 22; 
$tamu_nama = isset($_GET['to']) ? $_GET['to'] : 'Tamu Undangan';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Wedding of Yoga & Ayu | Exclusive Celebration</title>

    <!-- UI Libraries -->
    <link href="https://fonts.googleapis.com/css2?family=Pinyon+Script&family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <style>
        :root {
            --bg-deep: #050505;
            --bg-card: #0e0e0e;
            --gold-primary: #D4AF37;
            --gold-light: #F9E5C9;
            --gold-dark: #9B7E20;
            --text-main: #fcfcfc;
            --text-muted: #888;
            --font-serif: 'Playfair Display', serif;
            --font-script: 'Pinyon Script', cursive;
            --font-sans: 'Plus Jakarta Sans', sans-serif;
        }

        * { margin:0; padding:0; box-sizing:border-box; }
        html { scroll-behavior: smooth; }
        body { background: var(--bg-deep); color: var(--text-main); font-family: var(--font-sans); overflow-x: hidden; }

        /* --- PRELOADER (THE SEAL) --- */
        #welcome-overlay {
            position: fixed; inset: 0; background: var(--bg-deep); z-index: 9999;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            transition: 1.2s cubic-bezier(0.77, 0, 0.175, 1);
        }
        #welcome-overlay.open { transform: translateY(-100%); }
        .seal-wrap {
            border: 1px solid rgba(212, 175, 55, 0.2);
            padding: 60px 40px; border-radius: 5px; text-align: center;
            max-width: 500px; width: 90%;
            position: relative;
        }
        .seal-wrap::before {
            content: ''; position: absolute; inset: 10px; border: 1px solid rgba(212, 175, 55, 0.05);
        }

        /* --- HERO --- */
        .hero {
            height: 100vh; position: relative; display: flex; align-items: center; justify-content: center;
            text-align: center; overflow: hidden;
        }
        .hero-bg {
            position: absolute; inset: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.3), var(--bg-deep)),
                        url('https://images.unsplash.com/photo-1511285560929-80b456fea0bc?auto=format&fit=crop&w=1920&q=80') center/cover;
            filter: brightness(0.7); transform: scale(1.1);
        }
        .hero-content { position: relative; z-index: 10; opacity: 0; transform: translateY(30px); transition: 1.5s ease 0.5s; }
        .hero-content.active { opacity: 1; transform: translateY(0); }
        .hero-names { font-family: var(--font-script); font-size: 6rem; color: var(--gold-light); line-height: 1; margin: 20px 0; }
        
        /* --- SECTION BASE --- */
        .section { padding: 100px 20px; max-width: 900px; margin: 0 auto; text-align: center; }
        .sec-title { font-size: 3.5rem; color: var(--gold-light); font-family: var(--font-script); margin-bottom: 50px; }
        .sec-title span { display: block; font-family: var(--font-sans); font-size: 0.8rem; letter-spacing: 5px; text-transform: uppercase; color: var(--gold-primary); margin-bottom: -15px; }

        /* --- COUPLE --- */
        .couple-grid { display: flex; flex-wrap: wrap; justify-content: center; gap: 40px; margin-top: 50px; position: relative; }
        .couple-item { flex: 1; min-width: 280px; background: var(--bg-card); padding: 40px 20px; border-radius: 15px; border: 1px solid rgba(212, 175, 55, 0.1); }
        .couple-img { width: 180px; height: 180px; border-radius: 50%; object-fit: cover; border: 3px solid var(--gold-primary); padding: 5px; margin-bottom: 25px; }
        .couple-name { font-family: var(--font-serif); font-size: 2rem; color: var(--gold-light); }

        /* --- QR CHECK-IN (EXCLUSIVE FEATURE) --- */
        .qr-card {
            background: linear-gradient(145deg, #111, #050505);
            padding: 40px; border-radius: 20px; border: 1px solid var(--gold-primary);
            margin: 50px auto; max-width: 400px; position: relative; overflow: hidden;
        }
        .qr-card::after {
            content: 'EXCLUSIVE ACCESS'; position: absolute; top: 10px; right: -30px;
            background: var(--gold-primary); color: #000; font-size: 0.6rem; font-weight: 800;
            padding: 5px 40px; transform: rotate(45deg);
        }
        #qrcode { background: #fff; padding: 15px; border-radius: 10px; display: inline-block; margin-top: 20px; }

        /* --- BUTTONS --- */
        .btn-luxury {
            display: inline-block; padding: 15px 40px; background: linear-gradient(135deg, var(--gold-primary), var(--gold-dark));
            color: #000; text-decoration: none; border-radius: 50px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 2px; font-size: 0.8rem; transition: 0.4s; border: none; cursor: pointer;
        }
        .btn-luxury:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(212, 175, 55, 0.4); }

        /* --- FORM --- */
        .form-group { margin-bottom: 20px; text-align: left; }
        .form-control {
            width: 100%; padding: 15px; border-radius: 10px; border: 1px solid #333;
            background: #000; color: #fff; font-family: inherit; font-size: 1rem;
        }
        .form-control:focus { outline: none; border-color: var(--gold-primary); }

        /* --- MUSIC FLOATING --- */
        .music-toggle {
            position: fixed; bottom: 30px; right: 30px; width: 50px; height: 50px;
            border-radius: 50%; border: 1px solid var(--gold-primary); background: rgba(0,0,0,0.5);
            color: var(--gold-primary); display: flex; align-items: center; justify-content: center;
            cursor: pointer; z-index: 1000; transition: 0.3s;
        }
        .music-toggle.playing i { animation: rotate 3s linear infinite; }
        @keyframes rotate { 100% { transform: rotate(360deg); } }

        /* --- RESPONSIVE --- */
        @media (max-width: 768px) {
            .hero-names { font-size: 4rem; }
        }
    </style>
</head>
<body>

    <!-- PRELOADER -->
    <div id="welcome-overlay">
        <div class="seal-wrap" data-aos="fade-up">
            <p style="letter-spacing: 4px; color: var(--gold-primary); margin-bottom: 20px;">EXCLUSIVE INVITATION</p>
            <h1 class="font-script" style="font-size: 3.5rem; color: var(--gold-light);">Yoga & Ayu</h1>
            <div style="height: 1px; width: 50px; background: var(--gold-primary); margin: 30px auto;"></div>
            <p style="font-size: 0.9rem; color: #888; margin-bottom: 30px;">Kepada Yth. Bapak/Ibu/Saudara/i:<br><strong style="color:#fff; font-size:1.2rem;"><?= htmlspecialchars($tamu_nama) ?></strong></p>
            <button class="btn-luxury" onclick="openInvitation()">Buka Undangan</button>
        </div>
    </div>

    <!-- AUDIO -->
    <audio id="bgMusic" loop>
        <source src="https://storage.googleapis.com/audio_assets/kahitna_menikah_instrumental.mp3" type="audio/mpeg">
    </audio>
    <div class="music-toggle" id="musicBtn" onclick="toggleMusic()"><i class="fas fa-compact-disc fa-lg"></i></div>

    <!-- HERO -->
    <section class="hero">
        <div class="hero-bg"></div>
        <div class="hero-content" id="heroContent">
            <p style="letter-spacing: 8px; text-transform: uppercase;">The Wedding Of</p>
            <h2 class="hero-names">Yoga & Ayu</h2>
            <div style="font-family: var(--font-serif); font-size: 1.2rem; opacity: 0.8;">12 . 12 . 2026</div>
        </div>
    </section>

    <!-- INTRO -->
    <section class="section" data-aos="fade-up">
        <h2 class="sec-title"><span>Welcome to Our</span> Exclusive Day</h2>
        <p style="font-style: italic; line-height: 1.8; color: var(--text-muted);">"Cinta tidak terlihat dengan mata, melainkan dengan hati." — William Shakespeare</p>
        <div style="margin-top: 50px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px;" id="timer">
            <!-- Simplified countdown boxes -->
            <div style="background: var(--bg-card); padding: 15px; border-radius: 10px;"><span id="days" style="font-size: 1.5rem; color: var(--gold-primary); display: block;">00</span><small>HARI</small></div>
            <div style="background: var(--bg-card); padding: 15px; border-radius: 10px;"><span id="hours" style="font-size: 1.5rem; color: var(--gold-primary); display: block;">00</span><small>JAM</small></div>
            <div style="background: var(--bg-card); padding: 15px; border-radius: 10px;"><span id="mins" style="font-size: 1.5rem; color: var(--gold-primary); display: block;">00</span><small>MENIT</small></div>
            <div style="background: var(--bg-card); padding: 15px; border-radius: 10px;"><span id="secs" style="font-size: 1.5rem; color: var(--gold-primary); display: block;">00</span><small>DETIK</small></div>
        </div>
    </section>

    <!-- COUPLE -->
    <section class="section">
        <h2 class="sec-title" data-aos="fade-up"><span>Mempelai</span> Berbahagia</h2>
        <div class="couple-grid">
            <div class="couple-item" data-aos="fade-right">
                <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=400&q=80" class="couple-img">
                <h3 class="couple-name">Yoga Pratama</h3>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 10px;">Putra dari Bpk. X & Ibu Y</p>
            </div>
            <div class="couple-item" data-aos="fade-left">
                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80" class="couple-img">
                <h3 class="couple-name">Ayu Lestari</h3>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 10px;">Putri dari Bpk. A & Ibu B</p>
            </div>
        </div>
    </section>

    <!-- QR CHECK-IN (EXCLUSIVE) -->
    <section class="section" style="background: #080808;">
        <h2 class="sec-title" data-aos="fade-up"><span>Digital</span> QR Access</h2>
        <p style="color: var(--text-muted);">Gunakan QR Code di bawah untuk akses masuk & absensi digital di lokasi acara.</p>
        <div class="qr-card" data-aos="zoom-in">
            <div id="qrcode"></div>
            <p style="margin-top: 20px; font-weight: 600; color: var(--gold-light);"><?= htmlspecialchars($tamu_nama) ?></p>
            <p style="font-size: 0.7rem; color: #555; margin-top: 5px;">ID: EX-YOGA-22-<?= strtoupper(substr(md5($tamu_nama), 0, 6)) ?></p>
        </div>
    </section>

    <!-- RSVP -->
    <section class="section">
        <h2 class="sec-title" data-aos="fade-up"><span>Konfirmasi</span> Kehadiran</h2>
        <div style="background: var(--bg-card); padding: 40px; border-radius: 20px; border: 1px solid rgba(212,175,55,0.1);" data-aos="fade-up">
            <form id="rsvpForm">
                <input type="hidden" name="pesanan_id" value="<?= $pesanan_id ?>">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($tamu_nama) ?>" required>
                </div>
                <div class="form-group">
                    <label>Rencana Kehadiran</label>
                    <select name="kehadiran" class="form-control">
                        <option value="Hadir">Saya Akan Hadir</option>
                        <option value="Tidak Hadir">Berhalangan Hadir</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Doa & Ucapan</label>
                    <textarea name="ucapan" class="form-control" rows="4" placeholder="Tulis ucapan selamat Anda..."></textarea>
                </div>
                <button type="submit" class="btn-luxury" style="width: 100%;">Kirim RSVP</button>
            </form>
        </div>
    </section>

    <!-- FOOTER -->
    <footer style="padding: 100px 20px; text-align: center; border-top: 1px solid #111;">
        <h2 class="font-script" style="font-size: 3rem; color: var(--gold-light);">Yoga & Ayu</h2>
        <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 20px;">Created with Excellence by <strong>Embun Visual</strong></p>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 1200, once: true });

        // QR Code Generation
        new QRCode(document.getElementById("qrcode"), {
            text: "CHECKIN_YOGA_22_<?= urlencode($tamu_nama) ?>",
            width: 150,
            height: 150,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });

        // Invitation Logic
        function openInvitation() {
            document.getElementById('welcome-overlay').classList.add('open');
            document.getElementById('heroContent').classList.add('active');
            document.getElementById('bgMusic').play();
            document.getElementById('musicBtn').classList.add('playing');
        }

        function toggleMusic() {
            const m = document.getElementById('bgMusic');
            const b = document.getElementById('musicBtn');
            if (m.paused) { m.play(); b.classList.add('playing'); }
            else { m.pause(); b.classList.remove('playing'); }
        }

        // Countdown
        const target = new Date("Dec 12, 2026 09:00:00").getTime();
        setInterval(() => {
            const now = new Date().getTime();
            const diff = target - now;
            document.getElementById('days').innerText = Math.floor(diff / (1000 * 60 * 60 * 24));
            document.getElementById('hours').innerText = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            document.getElementById('mins').innerText = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            document.getElementById('secs').innerText = Math.floor((diff % (1000 * 60)) / 1000);
        }, 1000);

        // RSVP AJAX
        document.getElementById('rsvpForm').onsubmit = function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('../api_rsvp.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                if(data.status === 'ok') {
                    Swal.fire({ icon: 'success', title: 'Terima Kasih!', text: 'RSVP Anda telah tersimpan.', background:'#0e0e0e', color:'#fff', confirmButtonColor: '#D4AF37' });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            }).catch(() => Swal.fire('Error', 'Gagal kirim data', 'error'));
        }
    </script>
</body>
</html>
