<?php
// tema/tema_besic_bali2.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Wedding of Romeo & Juliet | Bali Nature Theme</title>

    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Great+Vibes&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            /* Palette: Soft Earthy Green Bali */
            --primary: #38493C;        
            --primary-light: #566E5B;  
            --bg-body: #F4F7F5;        /* Soft mint/green tint */
            --surface: #FFFFFF;        
            --text-main: #2C362F;      
            --text-muted: #6B7A70;     
            --accent: #A58B53;         /* Muted Bamboo Gold */
            --accent-hover: #8C7441;
            
            --font-serif: 'Cormorant Garamond', serif;
            --font-sans: 'Montserrat', sans-serif;
            --font-script: 'Great Vibes', cursive;
            
            --border-radius: 16px;
            --transition: all 0.5s cubic-bezier(0.25, 1, 0.5, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: var(--font-sans); background-color: var(--bg-body); color: var(--text-main); overflow-x: hidden; -webkit-font-smoothing: antialiased; }
        h1, h2, h3, h4 { font-weight: 500; line-height: 1.2; }
        p { line-height: 1.8; }
        .font-serif { font-family: var(--font-serif); }
        .font-script { font-family: var(--font-script); font-weight: 400; }
        .text-center { text-align: center; }

        /* OVERLAY */
        #cover-overlay { 
            position: fixed; top: 0; left: 0; width: 100%; height: 100vh; 
            background: url('https://images.unsplash.com/photo-1511285560929-80b456fea0bc?auto=format&fit=crop&w=1920&q=80') center/cover; 
            z-index: 9999; display: flex; flex-direction: column; align-items: center; justify-content: center; 
            text-align: center; transition: transform 1.2s cubic-bezier(0.77, 0, 0.175, 1); color: white; 
            border: 15px solid var(--surface);
        }
        #cover-overlay::before { content: ''; position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(56, 73, 60, 0.4), rgba(56, 73, 60, 0.9)); pointer-events: none; }
        #cover-overlay.open { transform: translateY(-100vh); }
        .cover-content { position: relative; z-index: 10; max-width: 650px; padding: 40px; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-radius: 24px; border: 1px solid rgba(255,255,255,0.2); box-shadow: 0 20px 50px rgba(0,0,0,0.2); margin: 0 20px; }
        .cover-badge { font-size: 0.75rem; letter-spacing: 3px; text-transform: uppercase; padding: 8px 25px; border: 1px solid var(--surface); color: var(--surface); border-radius: 50px; margin-bottom: 20px; display: inline-block; }
        .cover-title { font-size: 5rem; color: #FFF; margin: 0 0 10px 0; text-shadow: 2px 4px 15px rgba(0,0,0,0.2); line-height: 1.2; }
        .cover-subtitle { font-family: var(--font-serif); font-style: italic; font-size: 1.2rem; margin-bottom: 15px; letter-spacing: 1px; color: var(--surface); }
        .cover-guest { margin-top: 25px; padding-top: 20px; border-top: 1px solid rgba(255, 255, 255, 0.3); }
        .cover-guest span { font-size: 0.85rem; letter-spacing: 1px; color: rgba(255,255,255,0.8); display: block; margin-bottom: 5px; }
        .cover-guest h2 { font-family: var(--font-sans); font-weight: 500; font-size: 1.5rem; letter-spacing: 1px; color: var(--accent); }
        .btn-buka { background: var(--surface); color: var(--primary); padding: 15px 40px; border: none; border-radius: 50px; cursor: pointer; margin: 30px auto 0; font-family: var(--font-sans); font-size: 0.9rem; font-weight: 500; letter-spacing: 2px; text-transform: uppercase; transition: var(--transition); display: flex; align-items: center; gap: 10px; justify-content: center; box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .btn-buka:hover { background: var(--accent); color: #FFF; transform: translateY(-3px); }

        /* HERO */
        .hero { min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; background: url('https://images.unsplash.com/photo-1604928148386-353d9e4ea20c?auto=format&fit=crop&w=1920&q=80') center/cover fixed; padding: 20px; position: relative; }
        .hero::after { content: ''; position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(244, 247, 245, 0.6), rgba(244, 247, 245, 1)); pointer-events: none; }
        .hero-content { position: relative; z-index: 2; max-width: 600px; transition: opacity 1.5s ease-in-out, transform 1.5s ease-out; }
        .hidden-content { opacity: 0; transform: translateY(30px); pointer-events: none; }
        .show-content { opacity: 1; transform: translateY(0); pointer-events: auto; }
        .hero-subtitle { font-family: var(--font-serif); font-style: italic; font-size: 1.4rem; color: var(--primary); margin-bottom: 15px; }
        .hero-title { font-size: 6rem; color: var(--primary); margin-bottom: 10px; line-height: 1; }
        .hero-date { font-size: 0.95rem; font-weight: 500; letter-spacing: 4px; color: var(--primary); padding: 15px 0; margin-bottom: 30px; position: relative; border-top: 1px solid rgba(56, 73, 60, 0.3); border-bottom: 1px solid rgba(56, 73, 60, 0.3); display: inline-block; }

        /* SECTIONS */
        .section { padding: 100px 20px; max-width: 900px; margin: 0 auto; text-align: center; }
        .section-title { font-size: 3.5rem; color: var(--primary); margin-bottom: 25px; font-weight: 400; position: relative; display: inline-block; }
        
        /* QUOTE */
        .quote-section { max-width: 700px; margin: 0 auto; }
        .quote-text { font-family: var(--font-serif); font-style: italic; color: var(--primary-light); font-size: 1.5rem; line-height: 1.8; position: relative; }

        /* COUPLE */
        .couple-wrapper { background: var(--surface); border-radius: 30px; padding: 60px 40px; box-shadow: 0 15px 35px rgba(56, 73, 60, 0.05); margin-top: 40px; }
        .couple-container { display: flex; flex-direction: column; gap: 40px; align-items: center; }
        .profile { display: flex; flex-direction: column; align-items: center; width: 100%; }
        .profile-img-wrap { width: 220px; height: 220px; padding: 8px; border: 2px dashed var(--accent); border-radius: 50%; margin-bottom: 30px; position: relative; }
        .profile-img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; filter: brightness(1.05) sepia(10%); }
        .profile-name { font-size: 3.5rem; color: var(--primary); margin-bottom: 10px; line-height: 1; }
        .profile-desc { color: var(--text-muted); font-size: 0.9rem; line-height: 1.6; font-weight: 400; text-transform: uppercase; letter-spacing: 1px; }
        .ampersand { font-size: 4rem; color: var(--accent); margin: -20px 0; z-index: 2; line-height: 0.5; font-family: var(--font-serif); font-style: italic; }

        /* EVENTS */
        .event-container { display: grid; grid-template-columns: 1fr; gap: 30px; margin-top: 40px; }
        .event-card { background: var(--surface); padding: 50px 40px; border-radius: 24px; position: relative; overflow: hidden; transition: var(--transition); border-bottom: 3px solid var(--accent); box-shadow: 0 10px 30px rgba(56, 73, 60, 0.03); }
        .event-card:hover { transform: translateY(-5px); box-shadow: 0 15px 40px rgba(56, 73, 60, 0.08); }
        .event-icon { font-size: 2.2rem; color: var(--accent); margin-bottom: 20px; }
        .event-title { font-size: 2.5rem; margin-bottom: 15px; color: var(--primary); }
        .event-date { font-family: var(--font-sans); font-weight: 600; font-size: 1rem; color: var(--text-main); margin-bottom: 8px; letter-spacing: 1px; }
        .event-time { color: var(--primary-light); font-weight: 500; margin-bottom: 20px; font-size: 0.95rem; }
        .event-location { font-size: 0.95rem; color: var(--text-muted); margin-bottom: 25px; line-height: 1.7; }
        .btn-maps { background: var(--bg-body); color: var(--primary); padding: 12px 30px; border-radius: 50px; text-decoration: none; display: inline-block; font-size: 0.85rem; font-weight: 500; letter-spacing: 1px; text-transform: uppercase; transition: var(--transition); border: 1px solid rgba(56, 73, 60, 0.1); }
        .btn-maps:hover { background: var(--primary); color: white; border-color: var(--primary); }

        /* COUNTDOWN */
        .countdown-wrapper { margin-top: 40px; }
        .countdown { display: flex; justify-content: center; gap: 20px; margin-top: 20px; flex-wrap: wrap; }
        .time-box { background: var(--surface); color: var(--primary); padding: 20px 15px; min-width: 90px; border-radius: 16px; box-shadow: 0 10px 20px rgba(56, 73, 60, 0.05); }
        .time-box span { display: block; font-family: var(--font-serif); font-size: 2.8rem; font-weight: 600; line-height: 1; margin-bottom: 5px; color: var(--primary-light); font-variant-numeric: tabular-nums; }
        .time-box small { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); font-weight: 500; }

        /* GALLERY */
        .gallery-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-top: 40px; padding: 20px; background: var(--surface); border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); }
        .gallery-item { width: 100%; height: 250px; object-fit: cover; border-radius: 12px; transition: var(--transition); filter: sepia(10%); }
        .gallery-item:hover { filter: sepia(0%); transform: scale(1.02); }
        .gallery-item:first-child { grid-column: 1 / -1; height: 350px; }

        /* GIFT */
        .gift-container { max-width: 500px; margin: 40px auto 0; }
        .gift-card { background: var(--surface); padding: 35px; border-radius: 20px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; text-align: left; transition: var(--transition); border: 1px solid rgba(56, 73, 60, 0.1); box-shadow: 0 10px 20px rgba(56, 73, 60, 0.03); }
        .btn-copy { background: var(--primary-light); color: var(--surface); border: none; padding: 10px 20px; border-radius: 50px; cursor: pointer; font-size: 0.8rem; font-weight: 500; text-transform: uppercase; letter-spacing: 1px; transition: var(--transition); }
        .btn-copy:hover { background: var(--primary); }

        /* RSVP */
        .rsvp-box { background: var(--surface); padding: 50px 40px; border-radius: 24px; text-align: left; margin-top: 40px; box-shadow: 0 15px 40px rgba(56, 73, 60, 0.05); }
        .form-group { margin-bottom: 25px; }
        .form-control { width: 100%; padding: 15px; border: 1px solid rgba(56, 73, 60, 0.2); border-radius: 12px; font-family: inherit; font-size: 0.95rem; background: var(--bg-body); transition: var(--transition); color: var(--text-main); font-weight: 400; }
        .form-control:focus { outline: none; border-color: var(--primary); background: var(--surface); box-shadow: 0 0 0 3px rgba(56, 73, 60, 0.1); }
        .btn-submit { background: var(--primary); color: white; width: 100%; padding: 16px; border: none; border-radius: 12px; font-weight: 500; font-size: 0.95rem; letter-spacing: 2px; text-transform: uppercase; cursor: pointer; transition: var(--transition); margin-top: 10px; }
        .btn-submit:hover { background: var(--primary-light); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(56, 73, 60, 0.2); }

        /* FOOTER */
        footer { background: var(--primary); color: white; text-align: center; padding: 80px 20px; margin-top: 50px; position: relative; }
        
        .music-btn { position: fixed; bottom: 30px; right: 30px; background: var(--surface); color: var(--primary); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; box-shadow: 0 10px 25px rgba(56, 73, 60, 0.15); cursor: pointer; z-index: 1000; border: none; outline: none; transition: var(--transition); }
        .music-btn:hover { transform: scale(1.1); background: var(--primary); color: var(--surface); }
        .spin { animation: spin 4s linear infinite; }
        @keyframes spin { 100% { transform: rotate(360deg); } }

        @media (min-width: 768px) {
            .couple-container { flex-direction: row; justify-content: center; align-items: stretch; gap: 50px; }
            .profile { width: 40%; }
            .ampersand { width: auto; margin: 0; display: flex; align-items: center; justify-content: center; font-size: 6rem; }
            .event-container { grid-template-columns: 1fr 1fr; }
            .gallery-grid { grid-template-columns: repeat(3, 1fr); padding: 30px; gap: 20px;}
            .gallery-item:first-child { grid-column: span 2; height: 520px; }
            .rsvp-box { padding: 60px; }
        }
    </style>
</head>
<body>

    <div id="cover-overlay">
        <div class="cover-content" data-aos="zoom-in" data-aos-duration="1500" class="text-center">
            <div class="cover-badge">Undangan Pernikahan</div>
            <div class="cover-subtitle">Momen Tak Terlupakan</div>
            <h1 class="cover-title font-script">Arjuna & Sinta</h1>
            
            <div class="hero-date" style="border-color: rgba(255,255,255,0.4); color: white; margin-top: 20px;">12 SEPTEMBER 2026</div>

            <div class="cover-guest">
                <span>Kepada Yth.</span>
                <h2><?php echo isset($_GET['to']) ? htmlspecialchars($_GET['to']) : 'Keluarga / Sahabat'; ?></h2>
            </div>

            <button class="btn-buka" onclick="bukaUndangan()">
                Buka Undangan
            </button>
        </div>
    </div>

    <!-- AUDIO -->
    <audio id="bgMusic" loop><source src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-2.mp3" type="audio/mpeg"></audio>
    <button class="music-btn" id="musicBtn" onclick="toggleMusic()"><i class="fas fa-play"></i></button>

    <div id="main-content">
        
        <section class="hero">
            <div id="heroText" class="hero-content hidden-content">
                <i class="fas fa-leaf" style="font-size: 2rem; color: var(--primary); margin-bottom: 20px;"></i>
                <div class="hero-subtitle">Momen Bahagia</div>
                <h1 class="hero-title font-script">Arjuna & Sinta</h1>
                <div class="hero-date">12 SEPTEMBER 2026</div>

                <div class="countdown-wrapper">
                    <div class="countdown" id="countdown">
                        <div class="time-box"><span id="days">00</span><small>Days</small></div>
                        <div class="time-box"><span id="hours">00</span><small>Hours</small></div>
                        <div class="time-box"><span id="mins">00</span><small>Mins</small></div>
                        <div class="time-box"><span id="secs">00</span><small>Secs</small></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="quote-section" data-aos="fade-up">
                <p class="quote-text">
                    "Dan di antara tanda-tanda kebesaran-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang."
                </p>
                <div style="margin-top: 15px; font-weight: 500; font-family: var(--font-sans); color: var(--text-main); font-size: 0.9rem;">(QS. Ar-Rum: 21)</div>
            </div>
        </section>

        <section class="section" style="padding-top: 20px;">
            <div class="couple-wrapper" data-aos="fade-up">
                <i class="fab fa-envira" style="font-size: 2.5rem; color: var(--accent); margin-bottom: 15px;"></i>
                <h2 class="section-title font-script">Sang Mempelai</h2>
                <p style="color: var(--text-muted); margin-bottom: 50px; font-weight: 400;">
                    Atas rahmat Tuhan Yang Maha Esa, kami mengucapkan syukur yang mendalam atas terselenggaranya ikatan suci ini.
                </p>
                
                <div class="couple-container">
                    <div class="profile" data-aos="fade-up" data-aos-delay="100">
                        <div class="profile-img-wrap">
                            <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=500&q=80" alt="Groom" class="profile-img">
                        </div>
                        <h3 class="profile-name font-script">Arjuna Wibawa</h3>
                        <div class="profile-desc">Putra dari<br><strong style="color: var(--text-main); font-weight: 600;">Bpk. Wibawa & Ibu Wibawa</strong></div>
                    </div>

                    <div class="ampersand" data-aos="zoom-in" data-aos-delay="200">&</div>

                    <div class="profile" data-aos="fade-up" data-aos-delay="300">
                        <div class="profile-img-wrap">
                            <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=500&q=80" alt="Bride" class="profile-img">
                        </div>
                        <h3 class="profile-name font-script">Sinta Mutiara</h3>
                        <div class="profile-desc">Putri dari<br><strong style="color: var(--text-main); font-weight: 600;">Bpk. Mutiara & Ibu Mutiara</strong></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <h2 class="section-title font-script" data-aos="fade-up">Pelaksanaan Acara</h2>
            
            <div class="event-container">
                <div class="event-card" data-aos="fade-right" data-aos-delay="100">
                    <i class="far fa-heart event-icon"></i>
                    <h3 class="event-title font-serif">Pemberkatan Nikah</h3>
                    <p class="event-date">Sabtu, 12 September 2026</p>
                    <p class="event-time"><i class="far fa-clock"></i> 09.00 WIB - 11.00 WIB</p>
                    <p class="event-location"><strong style="color: var(--text-main);">Villa Hijau Bali</strong><br>Kawasan Ubud, Bali.</p>
                    <a href="https://maps.google.com" target="_blank" class="btn-maps">Peta Lokasi</a>
                </div>

                <div class="event-card" data-aos="fade-left" data-aos-delay="200">
                    <i class="fas fa-wine-glass-alt event-icon"></i>
                    <h3 class="event-title font-serif">Resepsi Nuansa Alam</h3>
                    <p class="event-date">Sabtu, 12 September 2026</p>
                    <p class="event-time"><i class="far fa-clock"></i> 18.00 WIB - Selesai</p>
                    <p class="event-location"><strong style="color: var(--text-main);">Villa Hijau Bali</strong><br>Kawasan Ubud, Bali.</p>
                    <a href="https://maps.google.com" target="_blank" class="btn-maps">Peta Lokasi</a>
                </div>
            </div>
        </section>

        <section class="section">
            <h2 class="section-title font-script" data-aos="fade-up">Galeri Cinta</h2>
            <div class="gallery-grid" data-aos="zoom-in" data-aos-delay="200">
                <img src="https://images.unsplash.com/photo-1460500063983-994d4c27756c?auto=format&fit=crop&w=1000&q=80" class="gallery-item">
                <img src="https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=600&q=80" class="gallery-item">
                <img src="https://images.unsplash.com/photo-1520854221256-17451cc331bf?auto=format&fit=crop&w=600&q=80" class="gallery-item">
            </div>
        </section>

        <section class="section">
            <h2 class="section-title font-script" data-aos="fade-up">Tanda Kasih</h2>
            <div class="gift-container">
                <div class="gift-card" data-aos="fade-up" data-aos-delay="200">
                    <div>
                        <h3 style="color: var(--primary-light); margin-bottom: 8px; font-size: 1.1rem; font-family: var(--font-sans); font-weight: 600;">Bank Mandiri</h3>
                        <p style="font-size: 1.25rem; font-family: var(--font-serif); font-weight: 600; letter-spacing: 2px; color: var(--text-main);">123 456 789 000</p>
                        <p style="color: var(--text-muted); font-size: 0.9rem; font-weight: 400;">a.n Arjuna Wibawa</p>
                    </div>
                    <button onclick="salinTeks('123456789000')" class="btn-copy">Salin Rekening</button>
                </div>
            </div>
        </section>

        <section class="section">
            <h2 class="section-title font-script" data-aos="fade-up">Kehadiran</h2>
            <div class="rsvp-box" data-aos="fade-up" data-aos-delay="200">
                <form id="formRSVP" onsubmit="kirimRSVP(event)">
                    <div class="form-group">
                        <input type="text" id="namaTamu" class="form-control" placeholder="Nama Lengkap Anda" required>
                    </div>
                    <div class="form-group">
                        <select id="statusHadir" class="form-control" required style="color: var(--text-muted);" onchange="this.style.color='var(--text-main)'">
                            <option value="" disabled selected>Pilih Kehadiran</option>
                            <option value="Hadir">Saya Akan Hadir</option>
                            <option value="Tidak Hadir">Saya Tidak Bisa Hadir</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <textarea id="pesanTamu" class="form-control" rows="3" placeholder="Pesan untuk mempelai..." required></textarea>
                    </div>
                    <button type="submit" class="btn-submit">Kirim Pesan</button>
                </form>
            </div>
        </section>

        <footer>
            <i class="fab fa-envira" style="font-size: 2.5rem; color: var(--surface); margin-bottom: 15px; opacity: 0.8;"></i>
            <h2 class="font-script" style="font-size: 4rem; margin-bottom: 10px; color: var(--surface);">Arjuna & Sinta</h2>
            <div style="font-size: 0.8rem; color: rgba(255,255,255,0.6); margin-top: 40px; letter-spacing: 2px; text-transform: uppercase;">
                Nature Theme by <strong style="color: var(--surface);">Embun Visual</strong>
            </div>
        </footer>

    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ once: true, offset: 50, duration: 1200, easing: 'ease-out-cubic' });

        document.body.style.overflow = "hidden";

        const audio = document.getElementById("bgMusic");
        const musicBtn = document.getElementById("musicBtn");
        let isPlaying = false;

        function bukaUndangan() {
            document.getElementById('cover-overlay').classList.add('open');
            document.body.style.overflow = "auto";
            
            let heroText = document.getElementById('heroText');
            heroText.classList.remove('hidden-content');
            heroText.classList.add('show-content');
            
            audio.play().catch(function(error) { console.log("Auto-play prevented"); });
            isPlaying = true;
            musicBtn.innerHTML = '<i class="fas fa-pause"></i>';
            
            setTimeout(() => { AOS.refresh(); }, 500);
        }

        function toggleMusic() {
            if (isPlaying) { audio.pause(); musicBtn.innerHTML = '<i class="fas fa-play"></i>'; } 
            else { audio.play(); musicBtn.innerHTML = '<i class="fas fa-pause"></i>'; }
            isPlaying = !isPlaying;
        }

        const targetDate = new Date("Sep 12, 2026 09:00:00").getTime();
        const timer = setInterval(function() {
            const now = new Date().getTime();
            const distance = targetDate - now;

            if (distance < 0) {
                clearInterval(timer);
                document.getElementById("countdown").innerHTML = "<h3 style='color:var(--primary); font-family: var(--font-serif); font-style: italic;'>Acara Sedang Berlangsung...</h3>";
                return;
            }
            document.getElementById("days").innerText = Math.floor(distance / (1000 * 60 * 60 * 24)).toString().padStart(2, '0');
            document.getElementById("hours").innerText = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).toString().padStart(2, '0');
            document.getElementById("mins").innerText = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)).toString().padStart(2, '0');
            document.getElementById("secs").innerText = Math.floor((distance % (1000 * 60)) / 1000).toString().padStart(2, '0');
        }, 1000);

        function salinTeks(teks) {
            navigator.clipboard.writeText(teks);
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Nomor disalin!', showConfirmButton: false, timer: 2000, background: '#38493C', color: '#fff', iconColor: '#A58B53' });
        }

        function kirimRSVP(e) {
            e.preventDefault(); 
            let nama = document.getElementById('namaTamu').value;
            let hadir = document.getElementById('statusHadir').value;
            let pesan = document.getElementById('pesanTamu').value;
            
            let noMempelai = "6281234567890"; 
            let textWA = `Halo, saya *${nama}*.%0A%0ATerkait undangan pernikahan alam, saya menyatakan: *${hadir}*.%0A%0A*Pesan:*%0A"${pesan}"`;
            
            window.open(`https://api.whatsapp.com/send?phone=${noMempelai}&text=${textWA}`, '_blank');
            
            Swal.fire({ icon: 'success', title: 'Terkirim', text: 'Terima kasih atas konfirmasinya.', confirmButtonColor: '#38493C' });
            document.getElementById('formRSVP').reset();
            document.getElementById('statusHadir').style.color = "var(--text-muted)";
        }
    </script>
</body>
</html>
