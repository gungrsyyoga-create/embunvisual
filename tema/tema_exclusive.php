<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Wedding of Romeo & Juliet | Exclusive</title>

    <!-- Google Fonts Premium: Playfair Display, Plus Jakarta Sans & Pinyon Script -->
    <link href="https://fonts.googleapis.com/css2?family=Pinyon+Script&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --bg-deep: #08090a;          /* Pure Black / Vantablack feel */
            --bg-charcoal: #121415;       /* Charcoal for cards */
            --gold-primary: #D4AF37;      /* Rich Metallic Gold */
            --gold-light: #F9E5C9;        /* Champagne Gold for highlights */
            --gold-dark: #9B7E20;         /* Deep Gold for borders */
            --text-main: #F4F4F4;         /* Soft White */
            --text-muted: #A0A0A0;        /* Elegant Grey */
            
            --font-serif: 'Playfair Display', serif;
            --font-sans: 'Plus Jakarta Sans', sans-serif;
            --font-script: 'Pinyon Script', cursive;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { 
            font-family: var(--font-sans); 
            background-color: var(--bg-deep); 
            color: var(--text-main); 
            overflow-x: hidden; 
            line-height: 1.6;
        }

        /* --- TYPOGRAPHY UTILS --- */
        .font-serif { font-family: var(--font-serif); }
        .font-script { font-family: var(--font-script); }
        .text-gold { color: var(--gold-primary); }
        .text-center { text-align: center; }

        /* --- EXCLUSIVE GOLD BUTTON --- */
        .btn-gold-solid, .btn-gold-outline {
            display: inline-block;
            padding: 12px 30px;
            font-family: var(--font-sans);
            font-size: 0.85rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            border-radius: 40px;
            cursor: pointer;
            transition: all 0.4s ease;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }
        .btn-gold-solid {
            background: linear-gradient(135deg, var(--gold-primary), var(--gold-light));
            color: var(--bg-deep);
            border: none;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.2);
        }
        .btn-gold-outline {
            background: transparent;
            color: var(--gold-primary);
            border: 1px solid var(--gold-primary);
        }
        .btn-gold-solid:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4); }
        .btn-gold-outline:hover { background: rgba(212, 175, 55, 0.1); }

        /* --- COVER OVERLAY (THE SEAL) --- */
        #welcome-overlay { 
            position: fixed; top: 0; left: 0; width: 100%; height: 100vh; 
            background: var(--bg-deep);
            z-index: 9999; display: flex; flex-direction: column; 
            align-items: center; justify-content: center; text-align: center; 
            transition: transform 1.2s cubic-bezier(0.77, 0, 0.175, 1); 
            padding: 20px;
        }
        /* Refined luxury border around the cover */
        .cover-frame {
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 5px;
            padding: 40px 20px;
            width: 100%; max-width: 500px;
            position: relative;
        }
        .cover-frame::before {
            content: ''; position: absolute; inset: 5px;
            border: 1px solid rgba(212, 175, 55, 0.1);
            pointer-events: none;
        }
        #welcome-overlay.open { transform: translateY(-100vh); }
        .welcome-title { font-size: 4.5rem; color: var(--gold-light); margin: 15px 0; line-height: 1.1; }
        
        /* --- MAIN HERO --- */
        .hero { 
            height: 100vh; display: flex; flex-direction: column; 
            justify-content: center; align-items: center; text-align: center; 
            background: url('https://images.unsplash.com/photo-1519225421980-715cb0215aed?auto=format&fit=crop&w=1920&q=80') center/cover fixed; 
            position: relative; 
        }
        .hero::before { 
            content: ''; position: absolute; inset: 0; 
            background: linear-gradient(to bottom, rgba(8, 9, 10, 0.6), var(--bg-deep)); 
        }
        .hero-content { position: relative; z-index: 2; transition: opacity 2s ease, transform 2s ease; }
        /* Used for animation sync with cover */
        .hero-hidden { opacity: 0; transform: translateY(30px); pointer-events: none; }
        .hero-show { opacity: 1; transform: translateY(0); pointer-events: auto; }
        
        .hero-title { font-size: 5rem; color: var(--gold-light); margin: 20px 0; text-shadow: 0 10px 30px rgba(0,0,0,0.8); line-height: 1; }
        .hero-subtitle { font-size: 0.85rem; letter-spacing: 5px; text-transform: uppercase; color: var(--gold-primary); }

        /* --- COUNTDOWN --- */
        .countdown-wrapper { margin-top: 40px; }
        .countdown { display: flex; justify-content: center; gap: 20px; margin-top: 15px; }
        .time-box {
            background: rgba(18, 20, 21, 0.8);
            border: 1px solid rgba(212, 175, 55, 0.2);
            padding: 15px; min-width: 80px;
            border-radius: 8px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.5);
        }
        .time-box span {
            display: block; font-family: var(--font-serif);
            font-size: 2rem; color: var(--gold-light);
            margin-bottom: 5px; line-height: 1;
        }
        .time-box small {
            font-size: 0.65rem; text-transform: uppercase;
            letter-spacing: 2px; color: var(--text-muted);
        }

        /* --- SCROLL MOUSE INDICATOR --- */
        .scroll-indicator {
            position: absolute; bottom: 40px; left: 50%; transform: translateX(-50%);
            display: flex; flex-direction: column; align-items: center; gap: 8px;
            opacity: 0.7; transition: opacity 0.3s;
        }
        .scroll-indicator:hover { opacity: 1; }
        .mouse {
            width: 24px; height: 38px; border: 2px solid var(--gold-primary);
            border-radius: 15px; position: relative;
        }
        .mouse::before {
            content: ''; position: absolute; top: 6px; left: 50%;
            width: 4px; height: 6px; background: var(--gold-light);
            border-radius: 2px; transform: translateX(-50%);
            animation: scrollWheel 2s infinite ease-in-out;
        }
        @keyframes scrollWheel {
            0% { transform: translate(-50%, 0); opacity: 1; }
            50% { transform: translate(-50%, 8px); opacity: 0; }
            100% { transform: translate(-50%, 0); opacity: 0; }
        }
        .scroll-text { font-size: 0.65rem; letter-spacing: 3px; text-transform: uppercase; color: var(--gold-primary); }

        /* --- SECTIONS --- */
        .section { padding: 100px 20px; max-width: 900px; margin: 0 auto; text-align: center; position: relative; z-index: 5; }
        .sec-title { font-size: 3rem; color: var(--gold-light); margin-bottom: 50px; font-weight: 400; }
        .sec-title span { display: block; font-family: var(--font-sans); font-size: 0.85rem; font-weight: 300; letter-spacing: 4px; text-transform: uppercase; color: var(--gold-primary); margin-bottom: -10px; }

        /* --- THE COUPLE --- */
        .couple-wrap { display: flex; flex-direction: column; gap: 60px; position: relative; }
        .couple-card {
            background: var(--bg-charcoal);
            padding: 40px 20px;
            border-radius: 10px;
            border: 1px solid rgba(212, 175, 55, 0.1);
            position: relative;
            z-index: 2;
        }
        .profile-img-wrap {
            width: 200px; height: 260px; margin: 0 auto 25px;
            border-radius: 150px 150px 0 0;
            overflow: hidden;
            border: 2px solid var(--gold-dark);
            padding: 5px;
        }
        .profile-img { width: 100%; height: 100%; object-fit: cover; border-radius: 150px 150px 0 0; }
        .profile-name { font-size: 3rem; color: var(--gold-light); margin-bottom: 10px; line-height: 1; }
        .ampersand {
            font-size: 4rem; color: rgba(212, 175, 55, 0.2);
            font-family: var(--font-serif); font-style: italic;
            position: absolute; top: 50%; left: 50%;
            transform: translate(-50%, -50%); z-index: 1;
        }

        /* --- EVENT CARDS --- */
        .event-grid { display: grid; grid-template-columns: 1fr; gap: 30px; }
        .card-event { 
            background: linear-gradient(145deg, var(--bg-charcoal), #16181a);
            padding: 50px 30px; border-radius: 12px; 
            border: 1px solid rgba(212, 175, 55, 0.15); 
            box-shadow: 0 15px 40px rgba(0,0,0,0.6);
            position: relative; overflow: hidden;
        }
        .card-event::before {
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 3px;
            background: linear-gradient(90deg, transparent, var(--gold-primary), transparent);
        }
        .event-icon { font-size: 2rem; color: var(--gold-primary); margin-bottom: 25px; opacity: 0.8; }
        .event-title { font-size: 2.2rem; margin-bottom: 15px; color: var(--gold-light); }
        .event-date { font-weight: 500; font-size: 1.1rem; letter-spacing: 1px; color: var(--text-main); margin-bottom: 10px; }

        /* --- GALLERY --- */
        .gallery-wrap { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .gallery-item { 
            position: relative; overflow: hidden; border-radius: 5px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.5);
            aspect-ratio: 1 / 1;
        }
        .gallery-item img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease; filter: brightness(0.85); }
        .gallery-item:hover img { transform: scale(1.05); filter: brightness(1); }
        .g-large { grid-column: span 2; aspect-ratio: 16 / 9; }

        /* --- GIFT & RSVP FORMS --- */
        .luxury-panel {
            background: var(--bg-charcoal);
            padding: 40px; border-radius: 15px;
            border: 1px solid rgba(212, 175, 55, 0.1);
        }
        .gift-box { 
            background: var(--bg-deep); padding: 25px; border-radius: 10px; 
            margin-top: 30px; display: flex; align-items: center; justify-content: space-between; 
            border: 1px solid rgba(212, 175, 55, 0.2);
        }
        
        .form-control { 
            width: 100%; padding: 15px 20px; margin-bottom: 20px; 
            border-radius: 8px; border: 1px solid rgba(255,255,255,0.08); 
            background: var(--bg-deep); color: var(--text-main); 
            font-family: inherit; font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        .form-control:focus { outline: none; border-color: var(--gold-primary); box-shadow: 0 0 10px rgba(212,175,55,0.1); }
        ::placeholder { color: #555; }

        /* Floating Audio */
        .music-btn { 
            position: fixed; bottom: 30px; right: 30px; 
            background: transparent; color: var(--gold-primary); 
            width: 50px; height: 50px; border-radius: 50%; 
            border: 1px solid var(--gold-primary); 
            font-size: 1.2rem; cursor: pointer; z-index: 100; 
            backdrop-filter: blur(5px); transition: 0.3s;
        }
        .music-btn:hover { background: rgba(212, 175, 55, 0.1); }
        .spin { animation: spin 4s linear infinite; }
        @keyframes spin { 100% { transform: rotate(360deg); } }

        /* --- FLOATING ACTION BUTTONS --- */
        .floating-actions {
            position: fixed; bottom: 30px; left: 30px; z-index: 99;
            display: flex; gap: 10px;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform: translateY(150px); opacity: 0; pointer-events: none;
        }
        .floating-actions.show { transform: translateY(0); opacity: 1; pointer-events: auto; }
        
        .btn-float {
            padding: 12px 20px; border-radius: 30px;
            font-size: 0.75rem; font-weight: 600; letter-spacing: 1px; text-transform: uppercase;
            box-shadow: 0 5px 15px rgba(0,0,0,0.5); text-decoration: none;
            display: flex; align-items: center; gap: 8px; transition: all 0.3s ease;
        }
        .btn-float-rsvp { background: linear-gradient(135deg, var(--gold-primary), var(--gold-dark)); color: var(--bg-deep); }
        .btn-float-map { background: var(--bg-charcoal); color: var(--gold-primary); border: 1px solid rgba(212,175,55,0.4); }
        
        .btn-float-rsvp:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4); }
        .btn-float-map:hover { transform: translateY(-3px); background: rgba(212,175,55,0.1); border-color: var(--gold-primary); }

        /* RESPONSIVE */
        @media (min-width: 768px) {
            .couple-wrap { flex-direction: row; justify-content: center; gap: 5%; }
            .couple-card { width: 45%; }
            .event-grid { grid-template-columns: repeat(2, 1fr); }
            .gallery-wrap { grid-template-columns: repeat(4, 1fr); }
            .g-large { grid-column: span 2; aspect-ratio: auto; }
            .gift-box { flex-direction: row; text-align: left; }
        }
        @media (max-width: 767px) {
            .hero-title { font-size: 4rem; }
            .gift-box { flex-direction: column; text-align: center; gap: 15px; }
            .ampersand { font-size: 3rem; }
        }
    </style>
</head>
<body>

    <!-- THE SEAL OVERLAY -->
    <div id="welcome-overlay">
        <div class="cover-frame" data-aos="zoom-in" data-aos-duration="1500">
            <p style="font-size: 0.75rem; letter-spacing: 4px; text-transform: uppercase; color: var(--gold-primary); margin-bottom: 20px;">Undangan Pernikahan</p>
            <h1 class="welcome-title font-script">Romeo & Juliet</h1>
            
            <div style="width: 50px; height: 1px; background: rgba(212,175,55,0.3); margin: 30px auto;"></div>

            <p style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 5px;">Kpd Yth. Bapak/Ibu/Saudara/i</p>
            <h2 class="font-serif" style="font-size: 1.8rem; letter-spacing: 1px; margin-bottom: 35px; color: var(--text-main);">Tamu Kehormatan</h2>
            
            <button class="btn-gold-outline" onclick="bukaUndangan()">
                <i class="fas fa-envelope-open-text" style="font-size: 0.9rem; margin-right: 8px;"></i> Buka Undangan
            </button>
        </div>
    </div>

    <!-- AUDIO DRIVER -->
    <audio id="bgMusic" loop>
        <!-- Sophisticated Orchestra / Strings -->
        <source src="https://storage.googleapis.com/audio_assets/kahitna_menikah_instrumental.mp3" type="audio/mpeg">
    </audio>
    <button class="music-btn" id="musicBtn" onclick="toggleMusic()"><i class="fas fa-music"></i></button>

    <div id="main-content">
        
        <!-- HERO SECTION -->
        <section class="hero" id="hero-sec">
            <div id="heroText" class="hero-content hero-hidden">
                <p class="hero-subtitle">The Wedding Of</p>
                <h1 class="hero-title font-script">Romeo & Juliet</h1>
                <p style="font-size: 1rem; letter-spacing: 3px; font-weight: 300; margin-top: 10px;">14 . 04 . 2026</p>

                <!-- COUNTDOWN -->
                <div class="countdown-wrapper">
                    <div class="countdown" id="countdown">
                        <div class="time-box"><span id="days">00</span><small>D</small></div>
                        <div class="time-box"><span id="hours">00</span><small>H</small></div>
                        <div class="time-box"><span id="mins">00</span><small>M</small></div>
                        <div class="time-box"><span id="secs">00</span><small>S</small></div>
                    </div>
                </div>
            </div>
            
            <!-- SCROLL INDICATOR -->
            <div id="scrollIndicator" class="scroll-indicator hero-hidden" style="transition-delay: 1s;">
                <div class="mouse"></div>
                <span class="scroll-text">Scroll Down</span>
            </div>
        </section>

        <!-- INTRO QUOTE -->
        <section class="section">
            <div class="luxury-panel" data-aos="fade-up">
                <i class="fas fa-quote-right" style="font-size: 2rem; color: rgba(212, 175, 55, 0.4); margin-bottom: 25px;"></i>
                <p class="font-serif" style="font-size: 1.4rem; font-style: italic; color: var(--text-main); line-height: 1.8;">
                    "Dan di antara tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu isteri-isteri dari jenismu sendiri, supaya kamu cenderung dan merasa tenteram kepadanya."
                </p>
                <p style="margin-top: 25px; font-size: 0.85rem; letter-spacing: 3px; text-transform: uppercase; color: var(--gold-primary);">(QS. Ar-Rum: 21)</p>
            </div>
        </section>

        <!-- THE COUPLE -->
        <section class="section">
            <h2 class="sec-title serif" data-aos="fade-up"><span>Sang</span> Mempelai</h2>
            <div class="couple-wrap">
                <span class="ampersand">&</span>
                
                <div class="couple-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="profile-img-wrap">
                        <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=500&q=80" alt="Romeo" class="profile-img">
                    </div>
                    <h3 class="profile-name font-script">Romeo Montague</h3>
                    <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 15px;">Putra Pertama dari<br>Bpk. Montague & Ibu Lady Montague</p>
                    <a href="#" style="color:var(--gold-primary); font-size:1.2rem; margin-top:20px; display:inline-block; transition: 0.3s; opacity: 0.7;"><i class="fab fa-instagram"></i></a>
                </div>

                <div class="couple-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="profile-img-wrap">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=500&q=80" alt="Juliet" class="profile-img">
                    </div>
                    <h3 class="profile-name font-script">Juliet Capulet</h3>
                    <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 15px;">Putri Bungsu dari<br>Bpk. Capulet & Ibu Lady Capulet</p>
                    <a href="#" style="color:var(--gold-primary); font-size:1.2rem; margin-top:20px; display:inline-block; transition: 0.3s; opacity: 0.7;"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </section>

        <!-- EVENT DETAILS -->
        <section class="section">
            <h2 class="sec-title serif" data-aos="fade-up"><span>Rangkaian</span> Acara</h2>
            
            <div class="event-grid">
                <div class="card-event" data-aos="fade-up" data-aos-delay="100">
                    <i class="fas fa-ring event-icon"></i>
                    <h3 class="event-title font-script">Akad Nikah</h3>
                    <div style="width: 40px; height: 1px; background: var(--gold-primary); margin: 0 auto 20px;"></div>
                    <p class="event-date">Sabtu, 14 April 2026</p>
                    <p style="color: var(--text-muted); margin-bottom: 20px; font-size: 0.9rem;">Pukul 08.00 WIB - Selesai</p>
                    <p style="font-size: 1rem; line-height: 1.5; margin-bottom: 30px;"><strong>Masjid Raya Al-Bina</strong><br><span style="color: var(--text-muted); font-size: 0.9rem;">Senayan, Jakarta Pusat</span></p>
                    
                    <div class="action-buttons" style="display: flex; flex-direction: column; gap: 10px; align-items: center;">
                        <a href="https://maps.google.com" target="_blank" class="btn-gold-solid" style="width: 100%; max-width: 200px;"><i class="fas fa-map-marker-alt" style="margin-right: 5px;"></i> Lokasi</a>
                        <a href="https://calendar.google.com/calendar/render?action=TEMPLATE&text=Akad+Nikah+Romeo+%26+Juliet&dates=20260414T010000Z/20260414T030000Z&details=Kehadiran+Anda+sangat+berarti+bagi+kami.&location=Masjid+Raya+Al-Bina,+Senayan,+Jakarta+Pusat" target="_blank" class="btn-gold-outline" style="width: 100%; max-width: 200px; padding: 10px 20px; font-size: 0.75rem;"><i class="far fa-calendar-alt" style="margin-right: 5px;"></i> Save The Date</a>
                    </div>
                </div>

                <div class="card-event" data-aos="fade-up" data-aos-delay="200">
                    <i class="fas fa-glass-cheers event-icon"></i>
                    <h3 class="event-title font-script">Resepsi</h3>
                    <div style="width: 40px; height: 1px; background: var(--gold-primary); margin: 0 auto 20px;"></div>
                    <p class="event-date">Sabtu, 14 April 2026</p>
                    <p style="color: var(--text-muted); margin-bottom: 20px; font-size: 0.9rem;">Pukul 11.00 - 14.00 WIB</p>
                    <p style="font-size: 1rem; line-height: 1.5; margin-bottom: 30px;"><strong>Grand Ballroom Hotel Mulia</strong><br><span style="color: var(--text-muted); font-size: 0.9rem;">Senayan, Jakarta Pusat</span></p>
                    
                    <div class="action-buttons" style="display: flex; flex-direction: column; gap: 10px; align-items: center;">
                        <a href="https://maps.google.com" target="_blank" class="btn-gold-solid" style="width: 100%; max-width: 200px;"><i class="fas fa-map-marker-alt" style="margin-right: 5px;"></i> Lokasi</a>
                        <a href="https://calendar.google.com/calendar/render?action=TEMPLATE&text=Resepsi+Pernikahan+Romeo+%26+Juliet&dates=20260414T040000Z/20260414T070000Z&details=Kehadiran+Anda+sangat+berarti+bagi+kami.&location=Hotel+Mulia+Senayan,+Jakarta+Pusat" target="_blank" class="btn-gold-outline" style="width: 100%; max-width: 200px; padding: 10px 20px; font-size: 0.75rem;"><i class="far fa-calendar-alt" style="margin-right: 5px;"></i> Save The Date</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- GALLERY DIGITAL -->
        <section class="section" style="padding-top: 50px;">
            <h2 class="sec-title serif" data-aos="fade-up"><span>Momen</span> Estetik</h2>
            <div class="gallery-wrap">
                <div class="gallery-item g-large" data-aos="fade-up"><img src="https://images.unsplash.com/photo-1511285560929-80b456fea0bc?auto=format&fit=crop&w=1200&q=80"></div>
                <div class="gallery-item" data-aos="fade-up" data-aos-delay="100"><img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?auto=format&fit=crop&w=600&q=80"></div>
                <div class="gallery-item" data-aos="fade-up" data-aos-delay="200"><img src="https://images.unsplash.com/photo-1522673607200-164d1b6ce486?auto=format&fit=crop&w=600&q=80"></div>
                <div class="gallery-item" data-aos="fade-up" data-aos-delay="100"><img src="https://images.unsplash.com/photo-1544465544-1b71aee9dfa3?auto=format&fit=crop&w=600&q=80"></div>
                <div class="gallery-item" data-aos="fade-up" data-aos-delay="200"><img src="https://images.unsplash.com/photo-1604928148386-353d9e4ea20c?auto=format&fit=crop&w=600&q=80"></div>
            </div>
        </section>

        <!-- LOKASI ACARA MAPS CARD -->
        <section class="section" style="padding-top: 50px;">
            <div class="luxury-panel" data-aos="fade-up" style="padding: 40px 20px;">
                <h2 class="sec-title serif" style="margin-bottom: 20px; font-size: 2rem;"><span>Denah</span> Lokasi</h2>
                <div style="width: 100%; max-width: 800px; margin: 0 auto; border: 1px solid var(--gold-dark); padding: 5px; border-radius: 5px;">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15865.051416450702!2d106.7918511!3d-6.2235282!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f1406f52e25f%3A0xe7975e5da465ec8d!2sHotel%20Mulia%20Senayan%2C%20Jakarta!5e0!3m2!1sen!2sid!4v1709772000000!5m2!1sen!2sid" width="100%" height="300" style="border:0; filter: grayscale(100%) invert(90%) contrast(1.2);" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                <p style="margin-top: 25px; font-size: 0.95rem; line-height: 1.6;">
                    <strong>Hotel Mulia Senayan</strong><br>
                    <span style="color: var(--text-muted); font-size: 0.85rem;">Jl. Asia Afrika, Senayan, Jakarta Pusat</span>
                </p>
                <a href="https://maps.google.com" target="_blank" class="btn-gold-outline" style="margin-top: 15px; display: inline-flex; align-items: center; justify-content: center; width: auto; padding: 10px 25px;"><i class="fas fa-location-arrow" style="margin-right: 8px;"></i> Buka di Google Maps</a>
            </div>

        <!-- DIGITAL GIFT -->
        <section class="section">
            <div class="luxury-panel" data-aos="fade-up">
                <h2 class="sec-title serif" style="margin-bottom: 20px;"><span>Tanda</span> Kasih</h2>
                <p style="color: var(--text-muted); font-size: 0.95rem; max-width: 600px; margin: 0 auto;">Kehadiran serta doa restu Anda merupakan hadiah terindah bagi kami. Namun apabila Anda hendak memberikan tanda kasih, fitur berikut telah kami sediakan:</p>
                
                <div class="gift-box">
                    <div>
                        <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" alt="BCA" style="height: 25px; margin-bottom: 15px; filter: brightness(0) invert(1) opacity(0.8);">
                        <p class="font-sans" style="font-size: 1.4rem; font-weight: 500; letter-spacing: 3px; color: var(--gold-light);" id="rekBCA">123 456 7890</p>
                        <p style="color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-top: 5px;">A.N. Romeo Montague</p>
                    </div>
                    <button onclick="salinRekening('1234567890')" class="btn-gold-outline" style="padding: 10px 20px; font-size: 0.75rem;"><i class="fas fa-copy"></i> Salin Rek</button>
                </div>
            </div>
        </section>

        <!-- RSVP & GUESTBOOK -->
        <section class="section" id="rsvp-sec" data-aos="fade-up">
            <h2 class="sec-title serif"><span>Konfirmasi</span> Kehadiran</h2>
            <div class="luxury-panel" style="max-width: 600px; margin: 0 auto;">
                <form onsubmit="kirimUcapan(event)">
                    <input type="text" id="namaTamu" class="form-control" placeholder="Nama Lengkap Anda" required>
                    <select id="kehadiran" class="form-control" required style="appearance: none;">
                        <option value="" disabled selected>Apakah Anda akan hadir?</option>
                        <option value="Hadir">Dengan senang hati, saya hadir</option>
                        <option value="Tidak Hadir">Maaf, tidak bisa hadir saat ini</option>
                    </select>
                    <textarea id="ucapan" class="form-control" rows="4" placeholder="Tulis doa dan harapan untuk mempelai..." required></textarea>
                    
                    <button type="submit" class="btn-gold-solid" style="width: 100%; border-radius: 8px;">
                        <i class="fas fa-paper-plane" style="margin-right: 8px;"></i> Kirim RSVP & Pesan
                    </button>
                </form>
            </div>
        </section>

        <!-- FOOTER -->
        <footer style="text-align: center; padding: 60px 20px; margin-top: 50px; background: var(--bg-charcoal);">
            <!-- Embellishment -->
            <div style="font-size: 1.5rem; color: var(--gold-primary); margin-bottom: 20px; opacity: 0.5;">✧</div>
            <p style="font-size: 0.9rem; color: var(--text-muted); max-width: 500px; margin: 0 auto 30px;">Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir memberikan doa restu.</p>
            <h2 class="font-script" style="font-size: 3.5rem; color: var(--gold-light); margin-bottom: 10px; line-height: 1;">Romeo & Juliet</h2>
            <p style="font-size: 0.75rem; letter-spacing: 2px; text-transform: uppercase; color: var(--text-muted); opacity: 0.7;">
                Created with Elegance by <a href="#" style="color: var(--gold-primary); text-decoration: none;">Embun Visual</a>
            </p>
        </footer>

    </div>

    <!-- FLOATING ACTION BUTTONS -->
    <div id="floatingActions" class="floating-actions">
        <a href="#rsvp-sec" class="btn-float btn-float-rsvp"><i class="fas fa-envelope-open-text"></i> RSVP</a>
        <a href="https://maps.google.com" target="_blank" class="btn-float btn-float-map"><i class="fas fa-map-marker-alt"></i> Petunjuk Arah</a>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Init Scroll Animations
        AOS.init({ once: true, duration: 1200, offset: 50, easing: 'ease-out-cubic' });

        // Audio System
        const audio = document.getElementById("bgMusic");
        const musicBtn = document.getElementById("musicBtn");
        let isPlaying = false;

        function bukaUndangan() {
            // Hilangkan cover atas
            document.getElementById('welcome-overlay').classList.add('open');
            document.body.style.overflow = "auto";
            
            // Tampilkan Hero secara halus
            setTimeout(() => {
                let heroText = document.getElementById('heroText');
                let scrollIndicator = document.getElementById('scrollIndicator');
                
                heroText.classList.remove('hero-hidden');
                heroText.classList.add('hero-show');
                
                scrollIndicator.classList.remove('hero-hidden');
                scrollIndicator.classList.add('hero-show');
            }, 300); // 300ms delay agar lebih dramatis
            
            // Play audio
            audio.play().catch(function(error) {
                console.log("Auto-play was prevented by browser");
            });
            isPlaying = true;
            musicBtn.classList.add("spin");
        }

        // Kunci scroll sebelum dibuka
        document.body.style.overflow = "hidden";

        // FLOATING ACTIONS LOGIC
        window.addEventListener('scroll', function() {
            const heroSection = document.getElementById('hero-sec');
            const floatingContainer = document.getElementById('floatingActions');
            
            // Tampilkan tombol melayang jika sudah lewat halaman pertama (hero)
            if (window.scrollY > (heroSection.offsetHeight - 100)) {
                floatingContainer.classList.add('show');
            } else {
                floatingContainer.classList.remove('show');
            }
        });

        function toggleMusic() {
            if (isPlaying) {
                audio.pause();
                musicBtn.classList.remove("spin");
            } else {
                audio.play();
                musicBtn.classList.add("spin");
            }
            isPlaying = !isPlaying;
        }

        // Fitur Salin
        function salinRekening(rek) {
            navigator.clipboard.writeText(rek);
            Swal.fire({ 
                toast: true, position: 'top', icon: 'success', 
                title: 'No. Rekening Disalin', 
                showConfirmButton: false, timer: 2000,
                background: '#121415', color: '#D4AF37', iconColor: '#D4AF37'
            });
        }

        // Fitur WhatsApp RSVP
        function kirimUcapan(e) {
            e.preventDefault();
            let nama = document.getElementById('namaTamu').value;
            let hadir = document.getElementById('kehadiran').value;
            let ucapan = document.getElementById('ucapan').value;
            
            let noMempelai = "6281234567890"; // Nomor WA
            let pesan = `Halo, saya *${nama}*.%0ASaya menyampaikan: *${hadir}*.%0A%0A*Pesan & Doa:*%0A${ucapan}`;
            
            window.open(`https://api.whatsapp.com/send?phone=${noMempelai}&text=${pesan}`, '_blank');
            Swal.fire({ 
                icon: 'success', title: 'RSVP Terkirim!', 
                text: 'Terima kasih atas partisipasi Anda.', 
                confirmButtonColor: '#D4AF37', background: '#121415', color: '#f4f4f4' 
            });
            e.target.reset();
        }

        // Hitung Mundur
        const targetDate = new Date("Apr 14, 2026 08:00:00").getTime();
        const timer = setInterval(function() {
            const now = new Date().getTime();
            const distance = targetDate - now;

            if (distance < 0) {
                clearInterval(timer);
                document.getElementById("countdown").innerHTML = "<div class='time-box'><span style='font-size: 1.2rem; font-family:var(--font-sans); color:var(--text-main);'>Hari Pertaruhan Cinta Telah Tiba</span></div>";
                return;
            }

            document.getElementById("days").innerText = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');
            document.getElementById("hours").innerText = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
            document.getElementById("mins").innerText = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
            document.getElementById("secs").innerText = String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0');
        }, 1000);

    </script>
</body>
</html>