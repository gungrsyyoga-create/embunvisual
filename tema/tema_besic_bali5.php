<?php
// tema/tema_besic_bali5.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawiwahan Rama & Sita | Bali Dark Wood Theme</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Dosis:wght@300;400;500;600&family=Damion&family=Medula+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            /* Palette: Dark Wood & Moss Green (Swalapatra inspired) */
            --primary: #352200;        /* Dark wood brown */
            --primary-light: #4A3515;  
            --bg-body: #F4F1E1;        /* Very light cream/yellow tint */
            --surface: #FFFFFF;        
            --text-main: #3D3D3D;      
            --text-muted: #666666;     
            --accent: #F2E29F;         /* Soft pale gold/yellow from button */
            --accent-hover: #D8AD83;   /* Deeper gold/copper */
            --green: #2B4522;          /* Moss green for accents if needed */
            
            --font-serif: 'Playfair Display', serif;
            --font-sans: 'Dosis', sans-serif;
            --font-script: 'Damion', cursive;
            --font-tall: 'Medula One', cursive;
            
            --border-radius: 20px;
            --transition: all 0.5s cubic-bezier(0.25, 1, 0.5, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: var(--font-sans); background-color: var(--bg-body); color: var(--text-main); overflow-x: hidden; -webkit-font-smoothing: antialiased; letter-spacing: 0.5px; }
        h1, h2, h3, h4 { font-weight: 400; line-height: 1.2; }
        p { line-height: 1.6; }
        
        /* Utility classes */
        .font-serif { font-family: var(--font-serif); }
        .font-sans { font-family: var(--font-sans); }
        .font-script { font-family: var(--font-script); }
        .font-tall { font-family: var(--font-tall); }
        .text-center { text-align: center; }

        /* OVERLAY - Black transparent */
        #cover-overlay { 
            position: fixed; top: 0; left: 0; width: 100%; height: 100vh; 
            background: url('https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=1920&q=80') center/cover; 
            z-index: 9999; display: flex; flex-direction: column; align-items: center; justify-content: center; 
            text-align: center; transition: opacity 1.2s ease, visibility 1.2s ease; color: white; 
        }
        #cover-overlay::before { content: ''; position: absolute; inset: 0; background: rgba(0, 0, 0, 0.7); pointer-events: none; }
        #cover-overlay.open { opacity: 0; visibility: hidden; pointer-events: none; }
        
        .cover-content { position: relative; z-index: 10; padding: 30px; }
        .cover-dear { font-family: var(--font-sans); font-size: 1.1rem; color: #fff; margin-bottom: 5px; }
        .cover-guest { font-family: var(--font-serif); font-size: 2.5rem; font-weight: 600; color: var(--accent); margin-bottom: 10px; text-shadow: 2px 2px 4px rgba(0,0,0,0.5); }
        .cover-text { font-family: var(--font-sans); font-size: 0.95rem; color: #ccc; margin-bottom: 30px; font-style: italic; }
        
        .btn-buka { background: var(--accent); color: var(--primary); padding: 12px 30px; border: none; border-radius: 5px; cursor: pointer; font-family: var(--font-sans); font-size: 1.1rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; transition: var(--transition); display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 15px rgba(242, 226, 159, 0.3); }
        .btn-buka:hover { background: #E5CD75; transform: scale(1.05); }

        /* HERO - Minimalist Text over full image */
        .hero { min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; position: relative; background: var(--primary); color: white; padding-bottom: 100px; }
        .hero-img-bg { position: absolute; inset: 0; background: url('https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=1920&q=80') center/cover; opacity: 0.4; }
        .hero-content { position: relative; z-index: 2; padding: 20px; margin-top: 50px; }
        .hero-date { font-family: var(--font-serif); font-size: 1.5rem; letter-spacing: 1px; margin-bottom: 10px; color: white; }
        .hero-title { font-size: 5rem; font-family: var(--font-serif); font-weight: 500; color: white; line-height: 1; margin-bottom: 20px; text-shadow: 0 5px 15px rgba(0,0,0,0.8); }
        
        /* Shape divider */
        .shape-bottom { position: absolute; bottom: 0; left: 0; width: 100%; overflow: hidden; line-height: 0; transform: rotate(180deg); }
        .shape-bottom svg { display: block; width: calc(100% + 1.3px); height: 70px; }
        .shape-bottom .shape-fill { fill: var(--bg-body); }

        /* SECTIONS */
        .section { padding: 80px 20px; position: relative; }
        .container { max-width: 900px; margin: 0 auto; text-align: center; }
        
        /* QUOTE */
        .quote-box { max-width: 700px; margin: 0 auto; padding: 40px 20px; }
        .quote-icon { text-align: center; margin-bottom: 15px; }
        .quote-icon img { width: 40px; }
        .quote-text { font-family: var(--font-sans); font-size: 1.1rem; color: var(--primary); line-height: 1.8; font-style: italic; margin-bottom: 10px; }
        .quote-source { font-family: var(--font-serif); font-weight: 600; color: #000; font-size: 1.1rem; }

        /* COUPLE SECTION - Dark Wood Background */
        .couple-section { background-color: var(--primary); color: white; padding: 100px 20px; margin-top: 40px; position: relative; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        /* Reverse wave shape top */
        .shape-top-wave { position: absolute; top: 0; left: 0; width: 100%; overflow: hidden; line-height: 0; transform: translateY(-99%); z-index: 1;}
        .shape-top-wave svg { display: block; width: calc(100% + 1.3px); height: 40px; }
        .shape-top-wave .shape-fill { fill: var(--primary); }
        
        .couple-grid { display: grid; grid-template-columns: 1fr; gap: 50px; align-items: center; max-width: 900px; margin: 0 auto; position: relative; z-index: 2;}
        .profile { display: flex; flex-direction: column; align-items: center; }
        .profile-img-wrap { width: 220px; height: 220px; margin-bottom: 25px; border-radius: 50%; overflow: hidden; border: 3px solid rgba(255,255,255,0.2); box-shadow: 0 10px 25px rgba(0,0,0,0.5); }
        .profile-img-wrap img { width: 100%; height: 100%; object-fit: cover; filter: sepia(30%) brightness(0.9); transition: var(--transition); }
        .profile-img-wrap:hover img { filter: sepia(0%) brightness(1); transform: scale(1.05); }
        .profile-name { font-size: 3rem; color: white; margin-bottom: 5px; line-height: 1; font-weight: 500; }
        .profile-title { font-family: var(--font-sans); font-size: 1.2rem; margin-bottom: 10px; color: var(--accent); }
        .profile-desc { font-family: var(--font-sans); font-size: 0.95rem; line-height: 1.5; color: #ddd; }
        
        .ampersand { font-size: 4rem; color: var(--accent); opacity: 0.8; margin: 20px 0; font-weight: 200; }

        /* EVENT SECTION */
        .event-section { padding: 80px 20px; background: url('https://images.unsplash.com/photo-1518531933037-91b2f5f229cc?auto=format&fit=crop&w=1920&q=80') center/cover fixed; position: relative; }
        .event-section::before { content: ''; position: absolute; inset: 0; background: rgba(244, 241, 225, 0.92); } /* F4F1E1 with opacity */
        .event-container { display: grid; grid-template-columns: 1fr; gap: 30px; position: relative; z-index: 2; max-width: 900px; margin: 0 auto;}
        
        .event-card { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); padding: 50px 30px; border-radius: 20px; text-align: center; border: 1px solid rgba(255,255,255,0.5); box-shadow: 0 15px 35px rgba(53, 34, 0, 0.05); transition: var(--transition); }
        .event-card:hover { transform: translateY(-5px); background: rgba(255, 255, 255, 0.9); }
        
        .event-type { font-family: var(--font-script); font-size: 2.5rem; color: var(--primary); margin-bottom: 20px; }
        .icon-circle { width: 60px; height: 60px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 20px; }
        
        .event-detail-item { margin-bottom: 15px; }
        .event-detail-item i { color: var(--accent-hover); margin-right: 10px; width: 20px; }
        .event-detail-item span { font-family: var(--font-sans); font-size: 1.1rem; color: var(--text-main); font-weight: 500; }
        
        .event-map-btn { display: inline-block; background: var(--primary); color: white; padding: 10px 25px; border-radius: 5px; text-decoration: none; font-family: var(--font-sans); font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1px; margin-top: 20px; transition: var(--transition); }
        .event-map-btn:hover { background: var(--primary-light); color: var(--accent); }

        /* COUNTDOWN IN EVENT SECTION */
        .countdown-wrap { margin-top: 40px; position: relative; z-index: 2;}
        .countdown { display: flex; justify-content: center; gap: 10px; flex-wrap: wrap; }
        .time-box { background: var(--primary); color: white; border-radius: 50px; padding: 15px 10px; min-width: 70px; text-align: center; box-shadow: 0 5px 15px rgba(53, 34, 0, 0.2); }
        .time-box span { display: block; font-family: var(--font-serif); font-size: 2rem; font-weight: 500; line-height: 1; margin-bottom: 5px; }
        .time-box small { font-size: 0.75rem; font-family: var(--font-sans); text-transform: uppercase; letter-spacing: 1px; opacity: 0.8; }

        /* GALLERY */
        .gallery-section { padding: 60px 20px; background: var(--bg-body); }
        .gallery-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; max-width: 1000px; margin: 0 auto; }
        .gallery-item { width: 100%; height: 250px; object-fit: cover; border-radius: 15px; transition: var(--transition); cursor: pointer; }
        .gallery-item:hover { opacity: 0.9; transform: scale(1.02); }

        /* GIFT */
        .gift-section { background: var(--primary); color: white; padding: 80px 20px; text-align: center; position: relative; border-radius: 20px; max-width: 900px; margin: 40px auto; }
        .gift-card { background: rgba(255,255,255,0.1); padding: 30px; border-radius: 15px; display: inline-block; margin: 10px; border: 1px solid rgba(242, 226, 159, 0.2); backdrop-filter: blur(5px); }
        .bank-name { font-family: var(--font-sans); font-size: 1.2rem; color: var(--accent); margin-bottom: 10px; font-weight: 600; text-transform: uppercase; }
        .bank-account { font-family: var(--font-serif); font-size: 1.5rem; letter-spacing: 2px; margin-bottom: 5px; }
        .bank-user { font-family: var(--font-sans); font-size: 1rem; opacity: 0.8; margin-bottom: 15px; }
        .btn-copy-rek { background: var(--bg-body); color: var(--primary); border: none; padding: 8px 20px; border-radius: 5px; cursor: pointer; font-family: var(--font-sans); font-size: 0.9rem; font-weight: 600; transition: var(--transition); }
        .btn-copy-rek:hover { background: var(--accent); }

        /* RSVP */
        .rsvp-section { background: var(--bg-body); padding: 80px 20px; }
        .rsvp-box { background: white; max-width: 600px; margin: 0 auto; padding: 40px; border-radius: 20px; box-shadow: 0 10px 40px rgba(53, 34, 0, 0.05); border-top: 5px solid var(--primary); }
        .form-group { margin-bottom: 20px; text-align: left; }
        .form-control { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-family: var(--font-sans); font-size: 1rem; background: #fafafa; transition: var(--transition); }
        .form-control:focus { outline: none; border-color: var(--primary); background: white; box-shadow: 0 0 0 3px rgba(53, 34, 0, 0.1); }
        .btn-submit { background: var(--primary); color: white; width: 100%; padding: 15px; border: none; border-radius: 8px; font-family: var(--font-sans); font-weight: 600; font-size: 1rem; text-transform: uppercase; letter-spacing: 1px; cursor: pointer; transition: var(--transition); }
        .btn-submit:hover { background: var(--primary-light); }

        /* FOOTER */
        footer { background: var(--bg-body); color: var(--text-main); text-align: center; padding: 60px 20px 100px; }
        .footer-logo { font-family: var(--font-tall); font-size: 3rem; color: var(--primary); margin-bottom: 15px; }

        /* MUSIC BTN */
        .music-btn { position: fixed; bottom: 30px; right: 30px; background: var(--accent); color: var(--primary); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; box-shadow: 0 5px 15px rgba(0,0,0,0.2); cursor: pointer; z-index: 1000; border: none; transition: var(--transition); }
        .music-btn:hover { transform: scale(1.1); background: var(--accent-hover); color: white;}
        .spin { animation: spin 4s linear infinite; }
        @keyframes spin { 100% { transform: rotate(360deg); } }

        /* Media Queries */
        @media (min-width: 768px) {
            .couple-grid { grid-template-columns: 1fr auto 1fr; gap: 30px; }
            .event-container { grid-template-columns: 1fr 1fr; gap: 40px; }
            .gallery-grid { grid-template-columns: repeat(3, 1fr); }
            .gallery-item:nth-child(1) { grid-column: span 2; grid-row: span 2; height: 515px; }
        }
    </style>
</head>
<body>

    <!-- OVERLAY (SAMPUL) -->
    <div id="cover-overlay">
        <div class="cover-content" data-aos="zoom-in" data-aos-duration="1500">
            <div class="cover-dear">Kpd Bpk/Ibu/Saudara/i</div>
            <div class="cover-guest"><?php echo isset($_GET['to']) ? htmlspecialchars($_GET['to']) : 'Tamu Undangan'; ?></div>
            <div class="cover-text">Mohon maaf apabila ada kesalahan penulisan nama dan gelar</div>
            
            <button class="btn-buka" onclick="bukaUndangan()">
                <i class="far fa-envelope-open"></i> Buka Undangan
            </button>
        </div>
    </div>

    <!-- AUDIO -->
    <audio id="bgMusic" loop><source src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-8.mp3" type="audio/mpeg"></audio>
    <button class="music-btn" id="musicBtn" onclick="toggleMusic()"><i class="fas fa-music"></i></button>

    <div id="main-content">
        
        <!-- HERO -->
        <section class="hero">
            <div class="hero-img-bg"></div>
            <div class="hero-content" data-aos="fade-up" data-aos-duration="2000">
                <p style="font-family: var(--font-sans); color: var(--accent); letter-spacing: 2px; text-transform: uppercase; margin-bottom: 20px; font-weight: 500;">Pawiwahan</p>
                <div class="hero-date">19 . 10 . 2026</div>
                <h1 class="hero-title" style="font-family: var(--font-serif); font-weight: 300;">Rama<br>&amp;<br>Sita</h1>
            </div>
            
            <div class="shape-bottom">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none">
                    <path class="shape-fill" d="M790.5,93.1c-59.3-5.3-116.8-18-192.6-50c-29.6-12.7-76.9-31-100.5-35.9c-23.6-4.9-52.6-7.8-75.5-5.3 c-10.2,1.1-22.6,1.4-50.1,7.4c-27.2,6.3-58.2,16.6-79.4,24.7c-41.3,15.9-94.9,21.9-134,22.6C72,58.2,0,25.8,0,25.8V100h1000V65.3 c0,0-51.5,19.4-106.2,25.7C839.5,97,814.1,95.2,790.5,93.1z"/>
                </svg>
            </div>
        </section>

        <!-- QUOTE -->
        <section class="section">
            <div class="container">
                <div class="quote-box" data-aos="fade-up">
                    <div class="quote-icon">
                        <i class="fas fa-leaf" style="font-size: 2rem; color: var(--accent-hover); opacity: 0.7;"></i>
                    </div>
                    <p class="quote-text">
                        "Dalam sebuah pernikahan kalian disatukan demi sebuah kebahagiaan dengan janji hati untuk saling membahagiakan. Bersamaku engkau akan hidup selamanya karena Tuhan pasti akan memberikan karunia sebagai pelindung dan saksi dalam pernikahan ini. Untuk itulah kalian dipersatukan dalam satu keluarga."
                    </p>
                    <p class="quote-source">(Rgveda : X.85.36)</p>
                </div>
            </div>
        </section>

        <!-- COUPLE -->
        <div class="container">
            <section class="couple-section">
                <!-- Top wave reversed so it blends with white/bg-body above it if needed, or simply round corners used. -->
                <div class="shape-top-wave">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none">
                        <path class="shape-fill" d="M790.5,93.1c-59.3-5.3-116.8-18-192.6-50c-29.6-12.7-76.9-31-100.5-35.9c-23.6-4.9-52.6-7.8-75.5-5.3 c-10.2,1.1-22.6,1.4-50.1,7.4c-27.2,6.3-58.2,16.6-79.4,24.7c-41.3,15.9-94.9,21.9-134,22.6C72,58.2,0,25.8,0,25.8V100h1000V65.3 c0,0-51.5,19.4-106.2,25.7C839.5,97,814.1,95.2,790.5,93.1z"/>
                    </svg>
                </div>

                <div class="couple-grid">
                    <div class="profile" data-aos="fade-right">
                        <div class="profile-img-wrap">
                            <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=500&q=80" alt="Groom">
                        </div>
                        <h2 class="profile-name font-serif">Rama</h2>
                        <div class="profile-title">Anak Agung Rama Wijaya, ST.</div>
                        <div class="profile-desc">Putra pertama dari pasangan<br>A.A. Putu Wijaya &amp; Ayu Trisna<br>Br. Panti, Kediri, Tabanan</div>
                    </div>

                    <div class="ampersand font-serif" data-aos="zoom-in">&amp;</div>

                    <div class="profile" data-aos="fade-left">
                        <div class="profile-img-wrap">
                            <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=500&q=80" alt="Bride">
                        </div>
                        <h2 class="profile-name font-serif">Sita</h2>
                        <div class="profile-title">Gusti Ayu Kade Sita Widayani, A.Md.</div>
                        <div class="profile-desc">Putri kedua dari pasangan<br>I Gusti Ketut Widayana &amp; Gusti Ayu Komang<br>Br. Bongan Kauh, Tabanan</div>
                    </div>
                </div>
            </section>
        </div>

        <!-- EVENTS -->
        <section class="event-section">
            <div class="container" style="position: relative; z-index: 2; margin-bottom: 30px;">
                <h2 style="font-family: var(--font-serif); font-size: 2.5rem; color: var(--primary); text-align: center; margin-bottom: 10px;">Acara &amp; Lokasi</h2>
                <i class="fas fa-leaf" style="font-size: 1.5rem; color: var(--accent-hover); display: block; text-align: center; margin-bottom: 40px;"></i>
            </div>

            <div class="event-container">
                <div class="event-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="icon-circle"><i class="far fa-heart"></i></div>
                    <h3 class="event-type">Akad Nikah</h3>
                    <div class="event-detail-item">
                        <i class="far fa-calendar-alt"></i> <span>Rabu, 19 Oktober 2026</span>
                    </div>
                    <div class="event-detail-item">
                        <i class="far fa-clock"></i> <span>09.00 Wita - Selesai</span>
                    </div>
                    <div class="event-detail-item">
                        <i class="fas fa-map-marker-alt"></i> <span>Jln Imam Bonjol No.19 Br.Panti, Kediri, Tabanan, Bali</span>
                    </div>
                    <a href="https://maps.google.com" target="_blank" class="event-map-btn"><i class="fas fa-map"></i> View Map</a>
                </div>

                <div class="event-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="icon-circle"><i class="fas fa-glass-cheers"></i></div>
                    <h3 class="event-type">Resepsi</h3>
                    <div class="event-detail-item">
                        <i class="far fa-calendar-alt"></i> <span>Rabu, 19 Oktober 2026</span>
                    </div>
                    <div class="event-detail-item">
                        <i class="far fa-clock"></i> <span>13.00 Wita - Selesai</span>
                    </div>
                    <div class="event-detail-item">
                        <i class="fas fa-map-marker-alt"></i> <span>Jln Imam Bonjol No.19 Br.Panti, Kediri, Tabanan, Bali</span>
                    </div>
                    <a href="https://maps.google.com" target="_blank" class="event-map-btn"><i class="fas fa-map"></i> View Map</a>
                </div>
            </div>

            <div class="countdown-wrap" data-aos="zoom-in">
                <div class="countdown" id="countdown">
                    <div class="time-box"><span id="days">00</span><small>Hari</small></div>
                    <div class="time-box"><span id="hours">00</span><small>Jam</small></div>
                    <div class="time-box"><span id="mins">00</span><small>Menit</small></div>
                    <div class="time-box"><span id="secs">00</span><small>Detik</small></div>
                </div>
            </div>
        </section>

        <!-- GALLERY -->
        <section class="gallery-section">
            <div class="container" style="margin-bottom: 40px;">
                <h2 style="font-family: var(--font-serif); font-size: 2.5rem; color: var(--primary);">Our Happy Moments</h2>
            </div>
            <div class="gallery-grid" data-aos="fade-up">
                <img src="https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1000&q=80" class="gallery-item">
                <img src="https://images.unsplash.com/photo-1520854221256-17451cc331bf?auto=format&fit=crop&w=600&q=80" class="gallery-item">
                <img src="https://images.unsplash.com/photo-1460500063983-994d4c27756c?auto=format&fit=crop&w=600&q=80" class="gallery-item">
                <img src="https://images.unsplash.com/photo-1511285560929-80b456fea0bc?auto=format&fit=crop&w=600&q=80" class="gallery-item">
                <img src="https://images.unsplash.com/photo-1515934751635-c81c6bc9a2d8?auto=format&fit=crop&w=600&q=80" class="gallery-item">
            </div>
        </section>

        <!-- GIFT -->
        <section class="gift-section" data-aos="fade-up">
            <h2 class="font-script" style="font-size: 3rem; margin-bottom: 20px; color: var(--accent);">Tanda Kasih</h2>
            <p style="margin-bottom: 30px; font-family: var(--font-sans); color: rgba(255,255,255,0.8);">Doa restu Anda merupakan karunia yang sangat berarti bagi kami. Namun jika Bapak/Ibu/Saudara/i ingin memberikan tanda kasih, dapat melalui rekening di bawah ini:</p>
            
            <div>
                <div class="gift-card">
                    <div class="bank-name">BCA</div>
                    <div class="bank-account">1234 5678 90</div>
                    <div class="bank-user">a/n Rama Wijaya</div>
                    <button onclick="salinTeks('1234567890')" class="btn-copy-rek"><i class="far fa-copy"></i> Salin Rekening</button>
                </div>
            </div>
        </section>

        <!-- RSVP -->
        <section class="rsvp-section">
            <div class="container">
                <h2 class="font-serif" style="font-size: 2.5rem; color: var(--primary); margin-bottom: 30px;">Buku Tamu &amp; Kehadiran</h2>
                
                <div class="rsvp-box" data-aos="fade-up">
                    <form id="formRSVP" onsubmit="kirimRSVP(event)">
                        <div class="form-group">
                            <input type="text" id="namaTamu" class="form-control" placeholder="Nama Anda" required>
                        </div>
                        <div class="form-group">
                            <select id="statusHadir" class="form-control" required style="color: var(--text-muted);" onchange="this.style.color='var(--text-main)'">
                                <option value="" disabled selected>Apakah Anda akan hadir?</option>
                                <option value="Hadir">Ya, Saya Akan Hadir</option>
                                <option value="Tidak Hadir">Maaf, Saya Tidak Bisa Hadir</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <textarea id="pesanTamu" class="form-control" rows="4" placeholder="Ucapkan doa dan pesan..." required></textarea>
                        </div>
                        <button type="submit" class="btn-submit">Kirim Pesan (via WhatsApp)</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer>
            <div class="footer-logo">Rama &amp; Sita</div>
            <p style="font-family: var(--font-sans); font-size: 0.9rem; color: var(--text-muted);">
                Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir <br>dan memberikan doa restu kepada kedua mempelai.
            </p>
            <div style="margin-top: 50px; font-size: 0.8rem; font-family: var(--font-sans); color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px;">
                Powered by Embun Visual
            </div>
        </footer>

    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ once: true, offset: 50, duration: 1000, easing: 'ease-out-cubic' });

        // Lock scroll on init
        document.body.style.overflow = "hidden";

        const audio = document.getElementById("bgMusic");
        const musicBtn = document.getElementById("musicBtn");
        let isPlaying = false;

        function bukaUndangan() {
            document.getElementById('cover-overlay').classList.add('open');
            document.body.style.overflow = "auto";
            
            // Play music
            audio.play().catch(function(error) { console.log("Auto-play prevented"); });
            isPlaying = true;
            musicBtn.classList.add("spin");
            
            // Re-trigger AOS to animate hero content
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

        // Countdown Logic
        const targetDate = new Date("Oct 19, 2026 13:00:00").getTime();
        const timer = setInterval(function() {
            const now = new Date().getTime();
            const distance = targetDate - now;

            if (distance < 0) {
                clearInterval(timer);
                document.getElementById("countdown").innerHTML = "<h3 style='color:white; font-family: var(--font-serif); font-weight:400;'>Acara Sedang Berlangsung</h3>";
                return;
            }
            document.getElementById("days").innerText = Math.floor(distance / (1000 * 60 * 60 * 24)).toString().padStart(2, '0');
            document.getElementById("hours").innerText = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).toString().padStart(2, '0');
            document.getElementById("mins").innerText = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)).toString().padStart(2, '0');
            document.getElementById("secs").innerText = Math.floor((distance % (1000 * 60)) / 1000).toString().padStart(2, '0');
        }, 1000);

        // Copy Text Logic
        function salinTeks(teks) {
            navigator.clipboard.writeText(teks);
            Swal.fire({ 
                toast: true, position: 'top-end', icon: 'success', 
                title: 'Nomor Rekening Disalin!', showConfirmButton: false, 
                timer: 2500, background: '#352200', color: '#fff', iconColor: '#F2E29F' 
            });
        }

        // RSVP WhatsApp Submission
        function kirimRSVP(e) {
            e.preventDefault(); 
            let nama = document.getElementById('namaTamu').value;
            let hadir = document.getElementById('statusHadir').value;
            let pesan = document.getElementById('pesanTamu').value;
            
            let noMempelai = "6281234567890"; // Ganti dengan nomor asli
            let textWA = `Om Swastyastu,%0A%0ASaya *${nama}*, menyatakan bahwa saya *${hadir}* pada acara pawiwahan.%0A%0A*Pesan & Doa:*%0A"${pesan}"`;
            
            window.open(`https://api.whatsapp.com/send?phone=${noMempelai}&text=${textWA}`, '_blank');
            
            Swal.fire({ icon: 'success', title: 'Terima Kasih!', text: 'Pesan Anda akan diteruskan ke WhatsApp.', confirmButtonColor: '#352200' });
            document.getElementById('formRSVP').reset();
            document.getElementById('statusHadir').style.color = "var(--text-muted)";
        }
    </script>
</body>
</html>
