<?php
// tema/tema_besic_bali1.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawiwahan Rama & Sita | Bali Banget 1 (Prada Gold)</title>

    <!-- Google Fonts: Cinzel for Headings, Lora for body -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700&family=Lora:ital,wght@0,400;0,500;1,400&family=Great+Vibes&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            /* Palette: Classic Prada Gold & Black */
            --primary: #111111;        /* Deep Midnight Black */
            --bg-body: #1a1a1a;        /* Very dark grey for section depth */
            --surface: #222222;        
            --text-main: #E0E0E0;      
            --text-muted: #A0A0A0;     
            --accent: #D4AF37;         /* Prada Gold */
            --accent-hover: #b8962e;   
            --dark-crimson: #5c0f0f;   /* For subtle gradients/details */
            
            --font-display: 'Cinzel', serif;
            --font-body: 'Lora', serif;
            --font-script: 'Great Vibes', cursive;
            
            --border-radius: 4px;      /* Sharper edges for classic look */
            --transition: all 0.5s ease;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: var(--font-body); background-color: var(--primary); color: var(--text-main); overflow-x: hidden; -webkit-font-smoothing: antialiased; }
        h1, h2, h3, h4 { font-family: var(--font-display); font-weight: 500; line-height: 1.2; color: var(--accent); }
        p { line-height: 1.8; }
        
        /* Utility classes */
        .text-center { text-align: center; }
        .gold-border { border: 1px solid var(--accent); padding: 5px; position: relative; }
        .gold-border::before { content: ''; position: absolute; inset: 4px; border: 1px dashed rgba(212, 175, 55, 0.5); pointer-events: none;}

        /* SCROLLBAR */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--primary); }
        ::-webkit-scrollbar-thumb { background: var(--accent); border-radius: 4px; }

        /* OVERLAY - Black transparent */
        #cover-overlay { 
            position: fixed; top: 0; left: 0; width: 100%; height: 100vh; 
            background: url('https://images.unsplash.com/photo-1544644557-4bfeb1c46369?auto=format&fit=crop&w=1920&q=80') center/cover; 
            z-index: 9999; display: flex; flex-direction: column; align-items: center; justify-content: center; 
            text-align: center; transition: opacity 1.5s ease, visibility 1.5s ease; color: white; 
        }
        #cover-overlay::before { content: ''; position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(17,17,17,0.8), rgba(17,17,17,0.95)); pointer-events: none; }
        #cover-overlay.open { opacity: 0; visibility: hidden; pointer-events: none; }
        
        .cover-content { position: relative; z-index: 10; padding: 40px; border-top: 2px solid var(--accent); border-bottom: 2px solid var(--accent); max-width: 90%; background: rgba(0,0,0,0.4); backdrop-filter: blur(5px); }
        .cover-dear { font-size: 1rem; color: #ccc; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 2px; }
        .cover-guest { font-family: var(--font-display); font-size: 2.5rem; font-weight: 600; color: var(--accent); margin-bottom: 15px; text-shadow: 0 0 10px rgba(212, 175, 55, 0.3); }
        
        .btn-buka { background: transparent; color: var(--accent); padding: 12px 35px; border: 1px solid var(--accent); cursor: pointer; font-family: var(--font-display); font-size: 1rem; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; transition: var(--transition); display: inline-flex; align-items: center; gap: 10px; margin-top: 20px;}
        .btn-buka:hover { background: var(--accent); color: var(--primary); box-shadow: 0 0 20px rgba(212, 175, 55, 0.4); }

        /* HERO - Classic Centered */
        .hero { min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; position: relative; background: var(--primary); padding-bottom: 80px; }
        .hero-img-bg { position: absolute; inset: 0; background: url('https://images.unsplash.com/photo-1544644557-4bfeb1c46369?auto=format&fit=crop&w=1920&q=80') center/cover; opacity: 0.15; filter: grayscale(100%); mix-blend-mode: luminosity;}
        .hero-content { position: relative; z-index: 2; padding: 20px; }
        
        .hero-date { font-family: var(--font-body); font-size: 1.2rem; letter-spacing: 5px; margin-bottom: 20px; color: var(--accent); text-transform: uppercase; }
        .hero-title { font-size: 5rem; font-family: var(--font-display); color: white; line-height: 1.1; margin-bottom: 20px; text-shadow: 0 5px 25px rgba(0,0,0,1); }
        .hero-title span { color: var(--accent); font-family: var(--font-script); font-size: 4rem; display: block; margin: -10px 0; font-weight: 300; text-transform: none;}

        /* SECTIONS */
        .section { padding: 90px 20px; position: relative; }
        .container { max-width: 850px; margin: 0 auto; text-align: center; }
        
        /* QUOTE */
        .quote-box { border-top: 1px solid rgba(212, 175, 55, 0.3); border-bottom: 1px solid rgba(212, 175, 55, 0.3); padding: 40px 20px; position: relative; }
        .quote-icon { position: absolute; top: -15px; left: 50%; transform: translateX(-50%); background: var(--primary); padding: 0 15px; color: var(--accent); font-size: 1.5rem; }
        .quote-text { font-size: 1.1rem; color: #ddd; font-style: italic; margin-bottom: 20px; }
        .quote-source { font-family: var(--font-display); color: var(--accent); letter-spacing: 2px; font-size: 0.9rem; }

        /* COUPLE SECTION */
        .couple-section { background-image: radial-gradient(circle at center, var(--surface) 0%, var(--primary) 100%); }
        .couple-grid { display: flex; flex-direction: column; gap: 60px; align-items: center; max-width: 900px; margin: 0 auto; position: relative; z-index: 2;}
        .profile { display: flex; flex-direction: column; align-items: center; }
        
        .profile-img-wrap { width: 200px; height: 280px; margin-bottom: 25px; overflow: hidden; border: 2px solid var(--accent); position: relative; padding: 5px; }
        .profile-img-inner { width: 100%; height: 100%; overflow: hidden; }
        .profile-img-wrap img { width: 100%; height: 100%; object-fit: cover; filter: grayscale(80%) contrast(1.2); transition: var(--transition); }
        .profile-img-wrap:hover img { filter: grayscale(0%) contrast(1); transform: scale(1.05); }
        
        .profile-name { font-size: 2.5rem; color: var(--accent); margin-bottom: 10px; letter-spacing: 2px; }
        .profile-title { font-family: var(--font-body); font-size: 1.1rem; margin-bottom: 10px; color: #fff; text-transform: uppercase; font-size: 0.9rem; letter-spacing: 1px;}
        .profile-desc { font-size: 0.95rem; color: var(--text-muted); }
        
        .ampersand { font-size: 5rem; color: #333; font-family: var(--font-display); position: relative; z-index: 0; line-height: 0; margin: 20px 0;}

        /* EVENT SECTION */
        .event-section { background: var(--bg-body); border-top: 1px solid #333; border-bottom: 1px solid #333; }
        .event-container { display: grid; grid-template-columns: 1fr; gap: 40px; margin-top: 40px; }
        
        .event-card { background: var(--primary); padding: 50px 30px; position: relative; border: 1px solid #333; transition: var(--transition); }
        .event-card::before { content: ''; position: absolute; inset: 5px; border: 1px solid rgba(212, 175, 55, 0.2); pointer-events: none;}
        .event-card:hover { border-color: var(--accent); box-shadow: 0 0 30px rgba(0,0,0,0.8); }
        
        .event-type { font-size: 2rem; color: var(--accent); margin-bottom: 25px; letter-spacing: 2px; text-transform: uppercase; }
        
        .event-detail-item { margin-bottom: 15px; display: flex; flex-direction: column; align-items: center; gap: 5px;}
        .event-detail-item i { color: var(--accent); font-size: 1.2rem; }
        .event-detail-item span { color: #ccc; }
        
        .event-map-btn { display: inline-block; background: transparent; color: var(--accent); padding: 10px 25px; border: 1px solid var(--accent); text-decoration: none; font-family: var(--font-display); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 2px; margin-top: 25px; transition: var(--transition); }
        .event-map-btn:hover { background: var(--accent); color: var(--primary); }

        /* COUNTDOWN */
        .countdown-wrap { margin-top: 60px; padding: 30px 15px; border-top: 1px solid rgba(212, 175, 55, 0.3); border-bottom: 1px solid rgba(212, 175, 55, 0.3);}
        .countdown { display: flex; justify-content: center; gap: 15px; flex-wrap: wrap; }
        .time-box { background: transparent; color: var(--accent); padding: 15px 10px; min-width: 70px; text-align: center; border-right: 1px solid #333; }
        .time-box:last-child { border-right: none; }
        .time-box span { display: block; font-family: var(--font-display); font-size: 2.5rem; font-weight: 400; line-height: 1; margin-bottom: 5px; }
        .time-box small { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; color: #888; }

        /* GALLERY */
        .gallery-section { background: var(--primary); }
        .gallery-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; max-width: 900px; margin: 40px auto 0; }
        .gallery-item { width: 100%; height: 250px; object-fit: cover; filter: grayscale(50%) brightness(0.8); transition: var(--transition); border: 1px solid #222;}
        .gallery-item:hover { filter: grayscale(0%) brightness(1); border-color: var(--accent); z-index: 2; position: relative; transform: scale(1.02);}

        /* GIFT */
        .gift-section { background: var(--bg-body); border-top: 1px dashed rgba(212, 175, 55, 0.3); }
        .gift-card { background: var(--primary); padding: 40px; border: 1px solid var(--accent); display: inline-block; margin: 20px 10px; position: relative; }
        .gift-card::before { content: ''; position: absolute; inset: 4px; border: 1px dashed rgba(212, 175, 55, 0.4); pointer-events: none;}
        .bank-name { font-family: var(--font-display); font-size: 1.5rem; color: #fff; margin-bottom: 15px; letter-spacing: 2px; }
        .bank-account { font-size: 1.8rem; color: var(--accent); letter-spacing: 3px; font-weight: 600; margin-bottom: 10px; }
        .btn-copy-rek { background: transparent; color: var(--accent); border: 1px solid var(--accent); padding: 8px 20px; cursor: pointer; font-family: var(--font-display); font-size: 0.8rem; letter-spacing: 1px; text-transform: uppercase; transition: var(--transition); margin-top: 15px; }
        .btn-copy-rek:hover { background: var(--accent); color: var(--primary); }

        /* RSVP */
        .rsvp-section { background: var(--primary); padding-bottom: 100px;}
        .rsvp-box { background: var(--surface); max-width: 600px; margin: 40px auto 0; padding: 40px; border: 1px solid #333; position: relative;}
        .rsvp-box::before { content: ''; position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 50px; height: 2px; background: var(--accent); }
        .form-group { margin-bottom: 25px; text-align: left; }
        .form-control { width: 100%; padding: 15px; border: 1px solid #444; font-family: var(--font-body); font-size: 1rem; background: transparent; color: white; transition: var(--transition); }
        .form-control:focus { outline: none; border-color: var(--accent); }
        textarea.form-control { resize: vertical; min-height: 100px; }
        
        .btn-submit { background: var(--accent); color: var(--primary); width: 100%; padding: 15px; border: none; font-family: var(--font-display); font-weight: 600; font-size: 1rem; text-transform: uppercase; letter-spacing: 2px; cursor: pointer; transition: var(--transition); }
        .btn-submit:hover { background: #fff; }

        /* FOOTER */
        footer { background: #0a0a0a; color: #666; text-align: center; padding: 60px 20px; border-top: 1px solid #222; }
        .footer-logo { font-family: var(--font-display); font-size: 2rem; color: var(--accent); margin-bottom: 20px; letter-spacing: 5px; }

        /* MUSIC BTN */
        .music-btn { position: fixed; bottom: 30px; left: 30px; background: rgba(0,0,0,0.8); color: var(--accent); width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem; border: 1px solid var(--accent); cursor: pointer; z-index: 1000; transition: var(--transition); }
        .music-btn:hover { background: var(--accent); color: var(--primary); }
        .spin { animation: spin 4s linear infinite; }
        @keyframes spin { 100% { transform: rotate(360deg); } }

        /* Media Queries */
        @media (min-width: 768px) {
            .couple-grid { flex-direction: row; justify-content: center; gap: 80px; }
            .event-container { grid-template-columns: 1fr 1fr; }
            .gallery-grid { grid-template-columns: repeat(3, 1fr); }
            .cover-content { padding: 60px; }
            .profile-img-wrap { width: 280px; height: 380px; }
        }
    </style>
</head>
<body>

    <!-- OVERLAY (SAMPUL) -->
    <div id="cover-overlay">
        <div class="cover-content" data-aos="zoom-in" data-aos-duration="1500">
            <div class="cover-dear">Kepada Yth.</div>
            <div class="cover-guest"><?php echo isset($_GET['to']) ? htmlspecialchars($_GET['to']) : 'Tamu Kehormatan'; ?></div>
            <div style="width: 40px; height: 1px; background: var(--accent); margin: 0 auto 20px;"></div>
            
            <button class="btn-buka" onclick="bukaUndangan()">
                Buka Undangan
            </button>
        </div>
    </div>

    <!-- AUDIO -->
    <audio id="bgMusic" loop><source src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3" type="audio/mpeg"></audio>
    <button class="music-btn" id="musicBtn" onclick="toggleMusic()"><i class="fas fa-music"></i></button>

    <div id="main-content">
        
        <!-- HERO -->
        <section class="hero">
            <div class="hero-img-bg"></div>
            <div class="hero-content" data-aos="fade-up" data-aos-duration="2000">
                <div class="hero-date">19 . 10 . 2026</div>
                <h1 class="hero-title">Rama <span>&</span> Sita</h1>
                <p style="text-transform: uppercase; letter-spacing: 5px; font-size: 0.85rem; color: #888;">Pawiwahan Agong</p>
            </div>
        </section>

        <!-- QUOTE -->
        <section class="section">
            <div class="container">
                <div class="quote-box" data-aos="fade-up">
                    <div class="quote-icon"><i class="fas fa-om"></i></div>
                    <p class="quote-text">
                        "Dalam sebuah pernikahan kalian disatukan demi sebuah kebahagiaan dengan janji hati untuk saling membahagiakan. Bersamaku engkau akan hidup selamanya karena Tuhan pasti akan memberikan karunia sebagai pelindung dan saksi..."
                    </p>
                    <p class="quote-source">RGVEDA : X.85.36</p>
                </div>
            </div>
        </section>

        <!-- COUPLE -->
        <section class="section couple-section">
            <div class="container">
                <h2 style="font-size: 2.5rem; letter-spacing: 3px; margin-bottom: 50px; text-transform: uppercase;">Sang Mempelai</h2>
                <div class="couple-grid">
                    
                    <div class="profile" data-aos="fade-up">
                        <div class="profile-img-wrap">
                            <div class="profile-img-inner">
                                <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=500&q=80" alt="Groom">
                            </div>
                        </div>
                        <h2 class="profile-name">Rama</h2>
                        <div class="profile-title">Anak Agung Rama Wijaya</div>
                        <div class="profile-desc">Putra pertama dari<br>A.A. Putu Wijaya &amp; Ayu Trisna</div>
                    </div>

                    <div class="ampersand" data-aos="zoom-in">&amp;</div>

                    <div class="profile" data-aos="fade-up" data-aos-delay="200">
                        <div class="profile-img-wrap">
                            <div class="profile-img-inner">
                                <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=500&q=80" alt="Bride">
                            </div>
                        </div>
                        <h2 class="profile-name">Sita</h2>
                        <div class="profile-title">G.A.K. Sita Widayani</div>
                        <div class="profile-desc">Putri kedua dari<br>I.G.K Widayana &amp; G.A. Komang</div>
                    </div>

                </div>
            </div>
        </section>

        <!-- EVENTS -->
        <section class="section event-section">
            <div class="container">
                <h2 style="font-size: 2rem; letter-spacing: 3px;">Rangkaian Acara</h2>
                <div style="width: 60px; height: 2px; background: var(--accent); margin: 20px auto 0;"></div>

                <div class="event-container">
                    <div class="event-card" data-aos="fade-up">
                        <h3 class="event-type">Akad Nikah</h3>
                        <div class="event-detail-item">
                            <span>Rabu, 19 Oktober 2026</span>
                        </div>
                        <div class="event-detail-item">
                            <span>09:00 Wita - Selesai</span>
                        </div>
                        <div class="event-detail-item" style="margin-top: 15px;">
                            <span style="font-size: 0.9rem; color: var(--text-muted);">Griya Agung Tabanan<br>Jln Imam Bonjol No.19, Bali</span>
                        </div>
                        <a href="https://maps.google.com" target="_blank" class="event-map-btn">Lihat Peta</a>
                    </div>

                    <div class="event-card" data-aos="fade-up" data-aos-delay="200">
                        <h3 class="event-type">Resepsi</h3>
                        <div class="event-detail-item">
                            <span>Rabu, 19 Oktober 2026</span>
                        </div>
                        <div class="event-detail-item">
                            <span>15:00 Wita - Selesai</span>
                        </div>
                        <div class="event-detail-item" style="margin-top: 15px;">
                            <span style="font-size: 0.9rem; color: var(--text-muted);">Griya Agung Tabanan<br>Jln Imam Bonjol No.19, Bali</span>
                        </div>
                        <a href="https://maps.google.com" target="_blank" class="event-map-btn">Lihat Peta</a>
                    </div>
                </div>

                <div class="countdown-wrap" data-aos="fade-up">
                    <p style="color: var(--accent); letter-spacing: 3px; text-transform: uppercase; font-size: 0.85rem; margin-bottom: 20px;">Menuju Hari Bahagia</p>
                    <div class="countdown" id="countdown">
                        <div class="time-box"><span id="days">00</span><small>D</small></div>
                        <div class="time-box"><span id="hours">00</span><small>H</small></div>
                        <div class="time-box"><span id="mins">00</span><small>M</small></div>
                        <div class="time-box"><span id="secs">00</span><small>S</small></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- GALLERY -->
        <section class="section gallery-section">
            <div class="container">
                <h2 style="font-size: 2rem; margin-bottom: 40px; letter-spacing: 3px;">Potret Kasih</h2>
                <div class="gallery-grid" data-aos="fade-up">
                    <img src="https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=600&q=80" class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1520854221256-17451cc331bf?auto=format&fit=crop&w=600&q=80" class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1460500063983-994d4c27756c?auto=format&fit=crop&w=600&q=80" class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1511285560929-80b456fea0bc?auto=format&fit=crop&w=600&q=80" class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1515934751635-c81c6bc9a2d8?auto=format&fit=crop&w=600&q=80" class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=600&q=80" class="gallery-item">
                </div>
            </div>
        </section>

        <!-- GIFT -->
        <section class="section gift-section" data-aos="fade-up">
            <div class="container">
                <h2 style="font-size: 2rem; margin-bottom: 20px; color: var(--accent);">Tanda Kasih</h2>
                <p style="color: var(--text-muted); font-size: 0.95rem;">Doa restu Anda adalah anugerah terbesar. Namun jika ingin memberikan tanda kasih, dapat melalui rekening berikut:</p>
                
                <div class="gift-card">
                    <div class="bank-name">BCA</div>
                    <div class="bank-account">1234 5678 90</div>
                    <div style="font-size: 0.9rem; color: #888; text-transform: uppercase; margin-bottom: 15px;">A/N Rama Wijaya</div>
                    <button onclick="salinTeks('1234567890')" class="btn-copy-rek">Salin Rekening</button>
                </div>
            </div>
        </section>

        <!-- RSVP -->
        <section class="section rsvp-section">
            <div class="container">
                <h2 style="font-size: 2rem; color: var(--accent); margin-bottom: 10px;">Buku Tamu</h2>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 30px;">Konfirmasi kehadiran Anda</p>
                
                <div class="rsvp-box" data-aos="fade-up">
                    <form id="formRSVP" onsubmit="kirimRSVP(event)">
                        <div class="form-group">
                            <input type="text" id="namaTamu" class="form-control" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="form-group">
                            <select id="statusHadir" class="form-control" required style="color: #666;" onchange="this.style.color='white'">
                                <option value="" disabled selected>Pilih Kehadiran</option>
                                <option value="Hadir">Akan Hadir</option>
                                <option value="Tidak Hadir">Tidak Bisa Hadir</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <textarea id="pesanTamu" class="form-control" placeholder="Tuliskan pesan atau doa restu..." required></textarea>
                        </div>
                        <button type="submit" class="btn-submit">Kirim via WhatsApp</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer>
            <div class="footer-logo">R &amp; S</div>
            <p style="font-size: 0.85rem; letter-spacing: 1px;">Terima kasih atas doa dan restunya.</p>
            <div style="margin-top: 40px; font-size: 0.7rem; color: #444; text-transform: uppercase; letter-spacing: 3px;">
                Powered by Embun Visual
            </div>
        </footer>

    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ once: true, offset: 50, duration: 1200, easing: 'ease-out-cubic' });

        // Lock scroll on init
        document.body.style.overflow = "hidden";

        const audio = document.getElementById("bgMusic");
        const musicBtn = document.getElementById("musicBtn");
        let isPlaying = false;

        function bukaUndangan() {
            document.getElementById('cover-overlay').classList.add('open');
            document.body.style.overflow = "auto";
            
            audio.play().catch(e => console.log("Auto-play prevented"));
            isPlaying = true;
            musicBtn.classList.add("spin");
            setTimeout(() => { AOS.refresh(); }, 500);
        }

        function toggleMusic() {
            if (isPlaying) { 
                audio.pause(); 
                musicBtn.classList.remove("spin"); 
                musicBtn.innerHTML = '<i class="fas fa-volume-mute"></i>';
            } else { 
                audio.play(); 
                musicBtn.classList.add("spin"); 
                musicBtn.innerHTML = '<i class="fas fa-music"></i>';
            }
            isPlaying = !isPlaying;
        }

        // Countdown
        const targetDate = new Date("Oct 19, 2026 09:00:00").getTime();
        const timer = setInterval(function() {
            const now = new Date().getTime();
            const distance = targetDate - now;

            if (distance < 0) {
                clearInterval(timer);
                document.getElementById("countdown").innerHTML = "<h3 style='color:var(--accent); font-family: var(--font-display);'>Acara Berlangsung</h3>";
                return;
            }
            document.getElementById("days").innerText = Math.floor(distance / (1000 * 60 * 60 * 24)).toString().padStart(2, '0');
            document.getElementById("hours").innerText = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).toString().padStart(2, '0');
            document.getElementById("mins").innerText = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)).toString().padStart(2, '0');
            document.getElementById("secs").innerText = Math.floor((distance % (1000 * 60)) / 1000).toString().padStart(2, '0');
        }, 1000);

        function salinTeks(teks) {
            navigator.clipboard.writeText(teks);
            Swal.fire({ 
                toast: true, position: 'top-end', icon: 'success', 
                title: 'Disalin!', showConfirmButton: false, 
                timer: 2000, background: '#222', color: '#D4AF37', iconColor: '#D4AF37' 
            });
        }

        function kirimRSVP(e) {
            e.preventDefault(); 
            let nama = document.getElementById('namaTamu').value;
            let hadir = document.getElementById('statusHadir').value;
            let pesan = document.getElementById('pesanTamu').value;
            let noMempelai = "6281234567890"; 
            let textWA = `Om Swastyastu,%0A%0ASaya *${nama}*, mengkonfirmasi bahwa saya *${hadir}* pada acara pawiwahan.%0A%0A*Pesan & Doa:*%0A"${pesan}"`;
            
            window.open(`https://api.whatsapp.com/send?phone=${noMempelai}&text=${textWA}`, '_blank');
            Swal.fire({ icon: 'success', title: 'Terima Kasih!', text: 'Pesan diteruskan ke WhatsApp.', confirmButtonColor: '#D4AF37', background: '#222', color: '#fff' });
            document.getElementById('formRSVP').reset();
            document.getElementById('statusHadir').style.color = "#666";
        }
    </script>
</body>
</html>
