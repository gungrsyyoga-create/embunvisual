<?php
// tema/tema_besic_bali2.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawiwahan Rama & Sita | Bali Banget 2 (Terracotta Temple)</title>

    <!-- Google Fonts: Crimson Text & Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Text:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600&family=Sacramento&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            /* Palette: Terracotta & Temple Stone */
            --primary: #C15B3D;        /* Terracotta Brick Red */
            --primary-dark: #8c3b23;   
            --bg-body: #EFEBE4;        /* Warm Stone/Sand color */
            --bg-card: #FFFFFF;        
            --text-main: #333333;      
            --text-muted: #777777;     
            --accent: #8E8E8E;         /* Stone Grey */
            
            --font-display: 'Crimson Text', serif;
            --font-body: 'Plus Jakarta Sans', sans-serif;
            --font-script: 'Sacramento', cursive;
            
            --border-radius: 8px;      /* Slight rounding */
            --transition: all 0.4s ease;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: var(--font-body); background-color: var(--bg-body); color: var(--text-main); overflow-x: hidden; -webkit-font-smoothing: antialiased; }
        h1, h2, h3, h4 { font-family: var(--font-display); font-weight: 600; line-height: 1.2; color: var(--primary-dark); }
        p { line-height: 1.7; }
        
        .text-center { text-align: center; }

        /* SCROLLBAR */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-body); }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 4px; }

        /* OVERLAY */
        #cover-overlay { 
            position: fixed; top: 0; left: 0; width: 100%; height: 100vh; 
            background: var(--bg-body) url('https://images.unsplash.com/photo-1555581126-df05eb6db0c2?auto=format&fit=crop&w=1920&q=80') center/cover; 
            z-index: 9999; display: flex; flex-direction: column; align-items: center; justify-content: center; 
            text-align: center; transition: opacity 1s ease, visibility 1s ease; 
        }
        #cover-overlay::before { content: ''; position: absolute; inset: 0; background: rgba(239, 235, 228, 0.85); backdrop-filter: blur(5px); pointer-events: none; }
        #cover-overlay.open { opacity: 0; visibility: hidden; pointer-events: none; }
        
        .cover-content { position: relative; z-index: 10; padding: 50px 30px; border: 2px solid var(--primary); max-width: 90%; background: #fff; box-shadow: 0 10px 40px rgba(193, 91, 61, 0.15); border-radius: var(--border-radius); }
        .cover-dear { font-size: 0.9rem; color: var(--text-muted); margin-bottom: 5px; text-transform: uppercase; letter-spacing: 2px; }
        .cover-guest { font-family: var(--font-display); font-size: 2.5rem; font-weight: 700; color: var(--primary); margin-bottom: 20px; }
        
        .btn-buka { background: var(--primary); color: #fff; padding: 12px 30px; border: none; cursor: pointer; font-family: var(--font-body); font-size: 1rem; font-weight: 600; letter-spacing: 1px; transition: var(--transition); border-radius: 4px; display: inline-flex; align-items: center; gap: 8px;}
        .btn-buka:hover { background: var(--primary-dark); transform: translateY(-3px); box-shadow: 0 5px 15px rgba(193, 91, 61, 0.4); }

        /* HERO - Split Screen Desktop Style */
        .hero { min-height: 100vh; position: relative; background: var(--bg-body); display: flex; align-items: center; justify-content: center; padding: 20px;}
        .hero-inner { max-width: 1100px; width: 100%; display: grid; grid-template-columns: 1fr; gap: 0; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.1); }
        
        .hero-img { background: url('https://images.unsplash.com/photo-1555581126-df05eb6db0c2?auto=format&fit=crop&w=1000&q=80') center/cover; min-height: 300px; position: relative;}
        .hero-img::after { content: ''; position: absolute; inset: 0; background: rgba(193, 91, 61, 0.2); mix-blend-mode: multiply;}
        
        .hero-content { padding: 60px 30px; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; position: relative; }
        .hero-intro { font-family: var(--font-body); text-transform: uppercase; letter-spacing: 3px; font-size: 0.8rem; color: var(--accent); margin-bottom: 15px; }
        .hero-title { font-size: 4.5rem; color: var(--primary); line-height: 1; margin-bottom: 15px; }
        .hero-title span { font-family: var(--font-script); font-size: 3rem; color: var(--text-main); display: block; margin: -10px 0; font-weight: 400;}
        .hero-date { font-weight: 600; font-size: 1.1rem; color: var(--text-main); padding: 10px 0; border-top: 1px solid #eee; border-bottom: 1px solid #eee; margin-top: 20px; width: 60%; }

        /* SECTIONS */
        .section { padding: 80px 20px; position: relative; }
        .container { max-width: 900px; margin: 0 auto; }
        .section-title { text-align: center; font-size: 2.5rem; margin-bottom: 50px; color: var(--primary); position: relative; padding-bottom: 20px; }
        .section-title::after { content: ''; position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); width: 50px; height: 3px; background: var(--accent); }

        /* QUOTE */
        .quote-wrap { background: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); text-align: center; position: relative; margin-top: -60px; z-index: 5;}
        .quote-icon { color: var(--primary); font-size: 2rem; margin-bottom: 15px; opacity: 0.5; }
        .quote-text { font-family: var(--font-display); font-size: 1.3rem; color: var(--text-main); font-style: italic; margin-bottom: 20px; line-height: 1.6;}
        .quote-source { font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; color: var(--accent); }

        /* COUPLE */
        .couple-grid { display: grid; gap: 40px; text-align: center; margin-top: 50px; }
        .profile { background: #fff; padding: 40px 20px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); position: relative; overflow: hidden; }
        .profile::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 5px; background: var(--primary); }
        
        .profile-img { width: 160px; height: 160px; margin: 0 auto 20px; border-radius: 50%; overflow: hidden; border: 5px solid var(--bg-body); }
        .profile-img img { width: 100%; height: 100%; object-fit: cover; filter: sepia(20%); transition: var(--transition); }
        .profile:hover .profile-img img { filter: sepia(0%); transform: scale(1.1); }
        
        .profile-name { font-size: 2.2rem; margin-bottom: 5px; }
        .profile-title { font-size: 0.95rem; font-weight: 600; color: var(--primary); margin-bottom: 15px; }
        .profile-desc { font-size: 0.9rem; color: var(--text-muted); }
        
        .ampersand { font-family: var(--font-script); font-size: 4rem; color: var(--accent); padding: 20px 0; }

        /* EVENT - Masonry Style */
        .event-section { background: var(--primary-dark); color: #fff; border-radius: 0; }
        .event-section .section-title { color: #fff; }
        .event-section .section-title::after { background: #fff; opacity: 0.5; }
        
        .event-grid { display: grid; grid-template-columns: 1fr; gap: 30px; }
        .event-card { background: #fff; color: var(--text-main); padding: 40px 30px; border-radius: 12px; text-align: center; box-shadow: 0 15px 40px rgba(0,0,0,0.2); }
        
        .event-type { font-size: 1.8rem; margin-bottom: 20px; color: var(--primary); }
        .event-detail { margin-bottom: 12px; font-weight: 500;}
        .event-detail i { color: var(--accent); width: 25px; }
        .event-loc { font-size: 0.9rem; color: var(--text-muted); margin: 15px 0 25px; line-height: 1.5; }
        
        .btn-outline { background: transparent; border: 1px solid var(--primary); color: var(--primary); padding: 10px 20px; border-radius: 4px; font-weight: 600; font-size: 0.9rem; transition: var(--transition); display: inline-block; text-decoration: none;}
        .btn-outline:hover { background: var(--primary); color: #fff; }

        /* COUNTDOWN */
        .countdown-wrap { background: #fff; padding: 30px; border-radius: 12px; margin-top: 50px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        .countdown-wrap h3 { font-family: var(--font-body); font-size: 1.2rem; text-transform: uppercase; letter-spacing: 2px; color: var(--accent); margin-bottom: 20px; }
        .countdown { display: flex; justify-content: center; gap: 15px; flex-wrap: wrap; }
        .time-box { display: flex; flex-direction: column; align-items: center; justify-content: center; width: 70px; height: 70px; background: var(--bg-body); border-radius: 50%; border: 2px solid var(--primary); }
        .time-box span { font-family: var(--font-display); font-size: 1.8rem; font-weight: 700; color: var(--primary); line-height: 1; margin-top: 5px; }
        .time-box small { font-size: 0.65rem; font-weight: 600; text-transform: uppercase; color: var(--text-muted); margin-top: 2px;}

        /* GALLERY */
        .gallery-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; }
        .gallery-item { width: 100%; height: 200px; border-radius: 8px; overflow: hidden; background: #ddd; }
        .gallery-item img { width: 100%; height: 100%; object-fit: cover; transition: var(--transition); }
        .gallery-item:hover img { transform: scale(1.1); }

        /* GIFT */
        .gift-section { background: #fff; }
        .gift-cards { display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; }
        .gift-card { background: var(--bg-body); padding: 30px; border-radius: 12px; text-align: center; min-width: 250px; border: 1px dashed var(--accent); }
        .bank-name { font-weight: 700; font-size: 1.2rem; color: var(--primary); margin-bottom: 10px; }
        .bank-account { font-family: var(--font-display); font-size: 1.6rem; margin-bottom: 5px; }
        .bank-user { font-size: 0.9rem; color: var(--text-muted); margin-bottom: 15px; }

        /* RSVP */
        .rsvp-box { background: #fff; padding: 40px 30px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .form-group { margin-bottom: 20px; }
        .form-control { width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 6px; font-family: var(--font-body); font-size: 0.95rem; background: var(--bg-body); transition: var(--transition); }
        .form-control:focus { outline: none; border-color: var(--primary); background: #fff; box-shadow: 0 0 0 3px rgba(193, 91, 61, 0.1); }
        textarea.form-control { resize: vertical; min-height: 120px; }
        .btn-submit { background: var(--primary); color: #fff; width: 100%; padding: 15px; border: none; border-radius: 6px; font-weight: 600; font-family: var(--font-body); letter-spacing: 1px; cursor: pointer; transition: var(--transition); }
        .btn-submit:hover { background: var(--primary-dark); }

        /* FOOTER */
        footer { background: var(--primary-dark); color: #fff; text-align: center; padding: 60px 20px 100px; border-top: 5px solid var(--primary); }
        .footer-logo { font-family: var(--font-script); font-size: 3rem; margin-bottom: 15px; }

        /* MUSIC BTN */
        .music-btn { position: fixed; bottom: 30px; right: 30px; background: var(--primary); color: #fff; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; border: none; cursor: pointer; z-index: 1000; box-shadow: 0 5px 15px rgba(0,0,0,0.3); transition: var(--transition); }
        .music-btn:hover { background: var(--primary-dark); transform: scale(1.05); }
        .spin { animation: spin 4s linear infinite; }
        @keyframes spin { 100% { transform: rotate(360deg); } }

        /* Media Queries */
        @media (min-width: 768px) {
            .hero-inner { grid-template-columns: 1fr 1fr; min-height: 600px;}
            .hero-img { min-height: 100%; }
            .couple-grid { grid-template-columns: 1fr auto 1fr; align-items: center; }
            .ampersand { padding: 0; }
            .event-grid { grid-template-columns: 1fr 1fr; }
            .gallery-grid { grid-template-columns: repeat(3, 1fr); }
            .gallery-item:nth-child(1) { grid-column: span 2; grid-row: span 2; height: 415px; }
        }
    </style>
</head>
<body>

    <!-- OVERLAY -->
    <div id="cover-overlay">
        <div class="cover-content" data-aos="zoom-in" data-aos-duration="1500">
            <div class="cover-dear">Kpd Bpk/Ibu/Saudara/i</div>
            <div class="cover-guest"><?php echo isset($_GET['to']) ? htmlspecialchars($_GET['to']) : 'Tamu Undangan'; ?></div>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 25px;">Tanpa mengurangi rasa hormat, kami mengundang Anda untuk hadir di acara kami.</p>
            
            <button class="btn-buka" onclick="bukaUndangan()">
                <i class="far fa-envelope-open"></i> Buka Undangan
            </button>
        </div>
    </div>

    <!-- AUDIO -->
    <audio id="bgMusic" loop><source src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-2.mp3" type="audio/mpeg"></audio>
    <button class="music-btn" id="musicBtn" onclick="toggleMusic()"><i class="fas fa-music"></i></button>

    <div id="main-content">
        
        <!-- HERO -->
        <section class="hero">
            <div class="hero-inner" data-aos="fade-up" data-aos-duration="2000">
                <div class="hero-img"></div>
                <div class="hero-content">
                    <div class="hero-intro">The Wedding Of</div>
                    <h1 class="hero-title">Rama <span>&</span> Sita</h1>
                    <div class="hero-date">19 . 10 . 2026</div>
                </div>
            </div>
        </section>

        <!-- QUOTE -->
        <div class="container" style="position: relative; z-index: 10;">
            <div class="quote-wrap" data-aos="fade-up">
                <div class="quote-icon"><i class="fas fa-quote-right"></i></div>
                <p class="quote-text">
                    "Dalam sebuah pernikahan kalian disatukan demi sebuah kebahagiaan dengan janji hati untuk saling membahagiakan. Bersamaku engkau akan hidup selamanya..."
                </p>
                <div class="quote-source">RGVEDA : X.85.36</div>
            </div>
        </div>

        <!-- COUPLE -->
        <section class="section">
            <div class="container">
                <h2 class="section-title">Pasangan Mempelai</h2>
                
                <div class="couple-grid">
                    <div class="profile" data-aos="fade-right">
                        <div class="profile-img">
                            <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=500&q=80" alt="Groom">
                        </div>
                        <h2 class="profile-name">Rama</h2>
                        <div class="profile-title">Anak Agung Rama Wijaya</div>
                        <div class="profile-desc">Putra pertama dari pasangan<br>A.A. Putu Wijaya &amp; Ayu Trisna</div>
                    </div>

                    <div class="ampersand" data-aos="zoom-in">and</div>

                    <div class="profile" data-aos="fade-left">
                        <div class="profile-img">
                            <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=500&q=80" alt="Bride">
                        </div>
                        <h2 class="profile-name">Sita</h2>
                        <div class="profile-title">G.A.K. Sita Widayani</div>
                        <div class="profile-desc">Putri kedua dari pasangan<br>I.G.K Widayana &amp; G.A. Komang</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- EVENTS -->
        <section class="section event-section">
            <div class="container">
                <h2 class="section-title">Waktu & Tempat</h2>

                <div class="event-grid">
                    <div class="event-card" data-aos="fade-up">
                        <h3 class="event-type">Akad Nikah</h3>
                        <div class="event-detail"><i class="far fa-calendar-alt"></i> Rabu, 19 Oktober 2026</div>
                        <div class="event-detail"><i class="far fa-clock"></i> 09:00 Wita - Selesai</div>
                        <div class="event-loc">Jln Imam Bonjol No.19 Br.Panti, Kediri, Tabanan, Bali</div>
                        <a href="https://maps.google.com" target="_blank" class="btn-outline">Google Maps</a>
                    </div>
                    
                    <div class="event-card" data-aos="fade-up" data-aos-delay="200">
                        <h3 class="event-type">Resepsi</h3>
                        <div class="event-detail"><i class="far fa-calendar-alt"></i> Rabu, 19 Oktober 2026</div>
                        <div class="event-detail"><i class="far fa-clock"></i> 13:00 Wita - Selesai</div>
                        <div class="event-loc">Jln Imam Bonjol No.19 Br.Panti, Kediri, Tabanan, Bali</div>
                        <a href="https://maps.google.com" target="_blank" class="btn-outline">Google Maps</a>
                    </div>
                </div>

                <div class="countdown-wrap" data-aos="zoom-in">
                    <h3>Hitung Mundur</h3>
                    <div class="countdown" id="countdown">
                        <div class="time-box"><span id="days">00</span><small>Hari</small></div>
                        <div class="time-box"><span id="hours">00</span><small>Jam</small></div>
                        <div class="time-box"><span id="mins">00</span><small>Menit</small></div>
                        <div class="time-box"><span id="secs">00</span><small>Detik</small></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- GALLERY -->
        <section class="section">
            <div class="container">
                <h2 class="section-title">Momen Bahagia</h2>
                <div class="gallery-grid" data-aos="fade-up">
                    <div class="gallery-item"><img src="https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80"></div>
                    <div class="gallery-item"><img src="https://images.unsplash.com/photo-1520854221256-17451cc331bf?auto=format&fit=crop&w=600&q=80"></div>
                    <div class="gallery-item"><img src="https://images.unsplash.com/photo-1460500063983-994d4c27756c?auto=format&fit=crop&w=600&q=80"></div>
                    <div class="gallery-item"><img src="https://images.unsplash.com/photo-1511285560929-80b456fea0bc?auto=format&fit=crop&w=600&q=80"></div>
                    <div class="gallery-item"><img src="https://images.unsplash.com/photo-1515934751635-c81c6bc9a2d8?auto=format&fit=crop&w=600&q=80"></div>
                </div>
            </div>
        </section>

        <!-- GIFT -->
        <section class="section gift-section">
            <div class="container">
                <h2 class="section-title">Kirim Hadiah</h2>
                <p class="text-center" style="margin-bottom: 30px; color: var(--text-muted); max-width: 600px; margin-left: auto; margin-right: auto;">Kehadiran dan doa restu Anda adalah anugerah terbesar. Namun jika ingin memberikan tanda kasih, dapat disalurkan melalui rekening berikut:</p>
                
                <div class="gift-cards" data-aos="fade-up">
                    <div class="gift-card">
                        <div class="bank-name">BCA</div>
                        <div class="bank-account">1234 5678 90</div>
                        <div class="bank-user">a/n Rama Wijaya</div>
                        <button onclick="salinTeks('1234567890')" class="btn-outline" style="border-color: var(--accent); color: var(--text-main);"><i class="far fa-copy"></i> Salin</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- RSVP -->
        <section class="section">
            <div class="container">
                <h2 class="section-title">Pesan & Kehadiran</h2>
                
                <div class="rsvp-box" data-aos="fade-up">
                    <form id="formRSVP" onsubmit="kirimRSVP(event)">
                        <div class="form-group">
                            <input type="text" id="namaTamu" class="form-control" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="form-group">
                            <select id="statusHadir" class="form-control" required style="color: var(--text-muted);" onchange="this.style.color='var(--text-main)'">
                                <option value="" disabled selected>Konfirmasi Kehadiran</option>
                                <option value="Hadir">Ya, Saya Akan Hadir</option>
                                <option value="Tidak Hadir">Sampaikan Maaf, Saya Berhalangan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <textarea id="pesanTamu" class="form-control" placeholder="Tuliskan ucapan dan doa restu di sini..." required></textarea>
                        </div>
                        <button type="submit" class="btn-submit">Kirim via WhatsApp</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer>
            <div class="footer-logo">Rama &amp; Sita</div>
            <p style="font-size: 0.95rem; opacity: 0.8;">Suatu kehormatan bagi kami atas doa dan kehadiran Anda.</p>
            <div style="margin-top: 40px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; opacity: 0.5;">
                Dibangun oleh Embun Visual
            </div>
        </footer>

    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ once: true, offset: 50, duration: 1000 });

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
                document.getElementById("countdown").innerHTML = "<h3 style='color:var(--primary);'>Acara Sedang Berlangsung</h3>";
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
                timer: 2000, background: '#C15B3D', color: '#fff', iconColor: '#fff' 
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
            Swal.fire({ icon: 'success', title: 'Terima Kasih!', text: 'Pesan diteruskan ke WhatsApp.', confirmButtonColor: '#C15B3D' });
            document.getElementById('formRSVP').reset();
            document.getElementById('statusHadir').style.color = "var(--text-muted)";
        }
    </script>
</body>
</html>
