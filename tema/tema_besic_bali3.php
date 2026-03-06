<?php
// tema/tema_besic_bali3.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawiwahan Rama & Sita | Soft Alam 1 (Tropical Beach)</title>

    <!-- Google Fonts: Montserrat & Pinyon Script -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Pinyon+Script&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            /* Palette: Soft Ocean / Beach */
            --primary: #A2D5DF;        /* Soft Ocean Blue */
            --primary-dark: #6CAEBD;   /* Darker Blue for text */
            --bg-body: #F5F5DC;        /* Sand Beige */
            --bg-white: #FFFFFF;       
            --text-main: #4A4A4A;      
            --text-light: #7A7A7A;     
            --accent: #E3B069;         /* Soft sunset gold/orange */
            
            --font-sans: 'Montserrat', sans-serif;
            --font-script: 'Pinyon Script', cursive;
            
            --border-radius: 30px;     /* Very soft, pill-like corners */
            --transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: var(--font-sans); background-color: var(--bg-body); color: var(--text-main); overflow-x: hidden; -webkit-font-smoothing: antialiased; letter-spacing: 0.5px;}
        h1, h2, h3, h4 { font-weight: 400; line-height: 1.3; color: var(--primary-dark); }
        p { line-height: 1.8; }
        
        .text-center { text-align: center; }

        /* SCROLLBAR */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-white); }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 10px; }

        /* OVERLAY */
        #cover-overlay { 
            position: fixed; top: 0; left: 0; width: 100%; height: 100vh; 
            background: var(--bg-white); 
            z-index: 9999; display: flex; flex-direction: column; align-items: center; justify-content: center; 
            text-align: center; transition: transform 1.2s cubic-bezier(0.77, 0, 0.175, 1); 
        }
        #cover-overlay.open { transform: translateY(-100%); }
        
        .cover-circle-bg { position: absolute; top: -20vh; right: -10vw; width: 60vh; height: 60vh; background: var(--primary); border-radius: 50%; opacity: 0.2; filter: blur(40px); pointer-events: none;}
        .cover-circle-bg-2 { position: absolute; bottom: -20vh; left: -10vw; width: 50vh; height: 50vh; background: var(--accent); border-radius: 50%; opacity: 0.15; filter: blur(40px); pointer-events: none;}

        .cover-content { position: relative; z-index: 10; padding: 40px; }
        .cover-dear { font-size: 0.85rem; color: var(--text-light); margin-bottom: 10px; letter-spacing: 3px; text-transform: uppercase;}
        .cover-guest { font-family: var(--font-script); font-size: 3.5rem; color: var(--primary-dark); margin-bottom: 20px; line-height: 1;}
        
        .btn-buka { background: #fff; color: var(--primary-dark); padding: 12px 35px; border: 1px solid var(--primary); border-radius: 50px; cursor: pointer; font-family: var(--font-sans); font-size: 0.9rem; font-weight: 500; letter-spacing: 2px; text-transform: uppercase; transition: var(--transition); box-shadow: 0 10px 20px rgba(162, 213, 223, 0.2);}
        .btn-buka:hover { background: var(--primary); color: #fff; transform: translateY(-3px); box-shadow: 0 15px 25px rgba(162, 213, 223, 0.4); }

        /* HERO - Soft Ocean Video/Image Background */
        .hero { min-height: 100vh; position: relative; background: var(--bg-white); display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; overflow: hidden; }
        .hero-img-bg { position: absolute; inset: 0; background: url('https://images.unsplash.com/photo-1505852903341-fc8d3db10436?auto=format&fit=crop&w=1920&q=80') center/cover; opacity: 0.6; }
        .hero-overlay { position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(245,245,220,0.3), rgba(245,245,220,0.9)); }
        
        .hero-content { position: relative; z-index: 2; padding: 20px; margin-top: 50px; }
        .hero-pre { font-size: 0.85rem; letter-spacing: 4px; text-transform: uppercase; color: var(--text-main); margin-bottom: 20px; font-weight: 500;}
        .hero-title { font-size: 6rem; font-family: var(--font-script); color: var(--primary-dark); line-height: 0.9; margin-bottom: 10px; }
        .hero-date { font-size: 1rem; font-weight: 400; letter-spacing: 3px; color: var(--text-main); margin-top: 20px; }
        
        .wave-bottom { position: absolute; bottom: -5px; left: 0; width: 100%; overflow: hidden; line-height: 0; }
        .wave-bottom svg { position: relative; display: block; width: calc(100% + 1.3px); height: 100px; }
        .wave-bottom .shape-fill { fill: var(--bg-white); }

        /* SECTIONS */
        .section { padding: 80px 20px; position: relative; background: var(--bg-white); }
        .container { max-width: 900px; margin: 0 auto; text-align: center; }
        
        .section-title { font-family: var(--font-script); font-size: 3.5rem; margin-bottom: 40px; color: var(--primary-dark); }

        /* QUOTE */
        .quote-box { max-width: 700px; margin: 0 auto; padding: 40px 20px; }
        .quote-text { font-size: 1.1rem; color: var(--text-main); font-weight: 300; line-height: 2; margin-bottom: 20px; }
        .quote-source { font-size: 0.8rem; font-weight: 500; text-transform: uppercase; letter-spacing: 2px; color: var(--accent); }

        /* COUPLE */
        .couple-section { background: var(--bg-body); padding-bottom: 150px; position: relative;}
        .wave-top { position: absolute; top: -1px; left: 0; width: 100%; overflow: hidden; line-height: 0; transform: rotate(180deg); }
        .wave-top svg { display: block; width: calc(100% + 1.3px); height: 100px; }
        .wave-top .shape-fill { fill: var(--bg-white); }
        
        .couple-grid { display: flex; flex-direction: column; gap: 50px; align-items: center; justify-content: center; margin-top: 60px; position: relative; z-index: 2;}
        .profile { background: var(--bg-white); padding: 40px; border-radius: var(--border-radius); box-shadow: 0 15px 40px rgba(162, 213, 223, 0.15); max-width: 350px; width: 100%; }
        
        .profile-img { width: 180px; height: 180px; margin: 0 auto 25px; border-radius: 50%; overflow: hidden; }
        .profile-img img { width: 100%; height: 100%; object-fit: cover; transition: var(--transition); filter: brightness(1.1) saturate(0.8); }
        .profile:hover .profile-img img { transform: scale(1.05); filter: brightness(1) saturate(1); }
        
        .profile-name { font-family: var(--font-script); font-size: 3rem; margin-bottom: 5px; color: var(--primary-dark); }
        .profile-title { font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: var(--accent); margin-bottom: 15px; }
        .profile-desc { font-size: 0.9rem; color: var(--text-light); }
        
        .ampersand { font-family: var(--font-script); font-size: 5rem; color: var(--primary); margin: 20px 0; }

        /* EVENT SECTION */
        .event-section { padding-top: 150px; position: relative;}
        /* Overlap wave on couple section */
        .event-section::before { content:''; position: absolute; top: -100px; left:0; width: 100%; height: 200px; background: url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1920&q=80') center/cover; z-index: 0; opacity: 0.3; mask-image: linear-gradient(to bottom, transparent, black 50%, transparent);}
        
        .event-grid { display: grid; gap: 40px; position: relative; z-index: 2; }
        .event-card { background: #fff; padding: 50px 30px; border-radius: var(--border-radius); box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid rgba(162, 213, 223, 0.3); }
        
        .event-type { font-size: 1.5rem; margin-bottom: 25px; font-weight: 500; text-transform: uppercase; letter-spacing: 2px;}
        .event-detail { margin-bottom: 15px; color: var(--text-main); font-size: 0.95rem;}
        .event-detail i { color: var(--primary-dark); width: 25px; }
        
        .btn-outline { background: var(--bg-white); border: 1px solid var(--primary); color: var(--primary-dark); padding: 12px 25px; border-radius: 50px; font-weight: 500; font-size: 0.85rem; letter-spacing: 1px; text-transform: uppercase; transition: var(--transition); display: inline-block; text-decoration: none; margin-top: 20px;}
        .btn-outline:hover { background: var(--primary); color: #fff; box-shadow: 0 8px 20px rgba(162, 213, 223, 0.4);}

        /* COUNTDOWN */
        .countdown-wrap { background: var(--primary); color: #fff; padding: 50px 20px; border-radius: var(--border-radius); margin-top: 60px; position: relative; overflow: hidden;}
        .countdown-wrap::after { content: ''; position: absolute; width: 300px; height: 300px; background: #fff; opacity: 0.1; border-radius: 50%; top: -150px; right: -50px; }
        
        .countdown { display: flex; justify-content: center; gap: 20px; flex-wrap: wrap; position: relative; z-index: 2;}
        .time-box { text-align: center; }
        .time-box span { font-size: 2.5rem; font-weight: 300; display: block; margin-bottom: 5px; }
        .time-box small { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; opacity: 0.9;}

        /* GALLERY */
        .gallery-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; max-width: 1000px; margin: 0 auto; }
        .gallery-item { width: 100%; height: 250px; border-radius: var(--border-radius); overflow: hidden; }
        .gallery-item img { width: 100%; height: 100%; object-fit: cover; transition: var(--transition); filter: sepia(10%) contrast(1.1); }
        .gallery-item:hover img { transform: scale(1.05); filter: none; }

        /* GIFT */
        .gift-section { background: var(--bg-body); position: relative; padding-bottom: 150px;}
        .gift-section .wave-top { top: -1px; }
        .gift-card { background: #fff; padding: 40px; border-radius: var(--border-radius); display: inline-block; margin: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); min-width: 280px; }
        .bank-name { font-weight: 600; font-size: 1.1rem; color: var(--primary-dark); margin-bottom: 10px; letter-spacing: 1px;}
        .bank-account { font-size: 1.5rem; font-weight: 300; margin-bottom: 10px; color: var(--text-main); }
        .bank-user { font-size: 0.9rem; color: var(--text-light); margin-bottom: 20px; }

        /* RSVP */
        .rsvp-section { padding-top: 10px; position: relative; z-index: 2; margin-top: -100px;}
        .rsvp-box { background: #fff; padding: 50px 40px; border-radius: var(--border-radius); box-shadow: 0 20px 50px rgba(162, 213, 223, 0.2); max-width: 600px; margin: 0 auto; }
        .form-group { margin-bottom: 25px; text-align: left; }
        .form-control { width: 100%; padding: 15px 20px; border: none; background: var(--bg-body); border-radius: 50px; font-family: var(--font-sans); font-size: 0.95rem; color: var(--text-main); transition: var(--transition); }
        .form-control:focus { outline: none; box-shadow: 0 0 0 2px var(--primary); background: #fff; }
        textarea.form-control { border-radius: 20px; resize: vertical; min-height: 120px; }
        
        .btn-submit { background: var(--primary); color: #fff; width: 100%; padding: 15px; border: none; border-radius: 50px; font-weight: 600; font-family: var(--font-sans); font-size: 0.95rem; text-transform: uppercase; letter-spacing: 2px; cursor: pointer; transition: var(--transition); }
        .btn-submit:hover { background: var(--primary-dark); box-shadow: 0 10px 20px rgba(162, 213, 223, 0.4); }

        /* FOOTER */
        footer { background: var(--bg-white); text-align: center; padding: 80px 20px 100px; }
        .footer-logo { font-family: var(--font-script); font-size: 3.5rem; color: var(--primary-dark); margin-bottom: 15px; }

        /* MUSIC BTN */
        .music-btn { position: fixed; bottom: 30px; right: 30px; background: #fff; color: var(--primary-dark); width: 55px; height: 55px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; border: none; cursor: pointer; z-index: 1000; box-shadow: 0 10px 25px rgba(162, 213, 223, 0.4); transition: var(--transition); }
        .music-btn:hover { background: var(--primary); color: #fff; transform: scale(1.1); }
        .spin { animation: spin 4s linear infinite; }
        @keyframes spin { 100% { transform: rotate(360deg); } }

        /* Media Queries */
        @media (min-width: 768px) {
            .couple-grid { flex-direction: row; }
            .event-grid { grid-template-columns: 1fr 1fr; }
            .gallery-grid { grid-template-columns: repeat(3, 1fr); gap: 30px;}
            .gallery-item:nth-child(2) { grid-column: span 2; grid-row: span 2; height: 530px; }
        }
    </style>
</head>
<body>

    <!-- OVERLAY -->
    <div id="cover-overlay">
        <div class="cover-circle-bg"></div>
        <div class="cover-circle-bg-2"></div>
        <div class="cover-content" data-aos="zoom-in" data-aos-duration="1500">
            <div class="cover-dear">Dear,</div>
            <div class="cover-guest"><?php echo isset($_GET['to']) ? htmlspecialchars($_GET['to']) : 'Tamu Spesial'; ?></div>
            <p style="font-size: 0.9rem; color: var(--text-light); margin-bottom: 30px; font-weight: 300;">Kami memohon kehadiran Anda di acara pernikahan kami</p>
            
            <button class="btn-buka" onclick="bukaUndangan()">Buka Undangan</button>
        </div>
    </div>

    <!-- AUDIO -->
    <audio id="bgMusic" loop><source src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-3.mp3" type="audio/mpeg"></audio>
    <button class="music-btn" id="musicBtn" onclick="toggleMusic()"><i class="fas fa-volume-up"></i></button>

    <div id="main-content">
        
        <!-- HERO -->
        <section class="hero">
            <div class="hero-img-bg"></div>
            <div class="hero-overlay"></div>
            
            <div class="hero-content" data-aos="fade-up" data-aos-duration="2000">
                <div class="hero-pre">The Wedding Of</div>
                <h1 class="hero-title">Rama & Sita</h1>
                <div class="hero-date">19 . 10 . 2026</div>
            </div>

            <!-- Wave Bottom -->
            <div class="wave-bottom">
                <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                    <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V0C71.55,39.06,146.53,60.89,223,65.88,256.33,68,289.47,62.33,321.39,56.44Z" class="shape-fill"></path>
                </svg>
            </div>
        </section>

        <!-- QUOTE -->
        <section class="section">
            <div class="container">
                <div class="quote-box" data-aos="fade-up">
                    <p class="quote-text">
                        "Cinta bukan tentang menatap satu sama lain, melainkan melihat ke arah yang sama. Semoga cinta yang mempersatukan kita hari ini, terus tumbuh dan bersemi di setiap hembusan angin pantai dan langkah kita."
                    </p>
                    <div class="quote-source">RGVEDA : X.85.36</div>
                </div>
            </div>
        </section>

        <!-- COUPLE -->
        <section class="couple-section">
            <div class="wave-top">
                <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                    <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V0C71.55,39.06,146.53,60.89,223,65.88,256.33,68,289.47,62.33,321.39,56.44Z" class="shape-fill"></path>
                </svg>
            </div>
            
            <div class="container">
                <h2 class="section-title" data-aos="zoom-in" style="margin-top: 80px;">Groom & Bride</h2>
                
                <div class="couple-grid">
                    <div class="profile" data-aos="fade-up">
                        <div class="profile-img">
                            <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=500&q=80" alt="Groom">
                        </div>
                        <h2 class="profile-name">Rama</h2>
                        <div class="profile-title">Anak Agung Rama Wijaya</div>
                        <div class="profile-desc">Putra pertama Bapak A.A. Putu Wijaya &amp; Ibu Ayu Trisna</div>
                    </div>

                    <div class="ampersand" data-aos="zoom-in">&</div>

                    <div class="profile" data-aos="fade-up" data-aos-delay="200">
                        <div class="profile-img">
                            <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=500&q=80" alt="Bride">
                        </div>
                        <h2 class="profile-name">Sita</h2>
                        <div class="profile-title">G.A.K. Sita Widayani</div>
                        <div class="profile-desc">Putri kedua Bapak I.G.K Widayana &amp; Ibu G.A. Komang</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- EVENTS -->
        <section class="section event-section">
            <div class="container">
                <h2 class="section-title">Save The Date</h2>

                <div class="event-grid">
                    <div class="event-card" data-aos="fade-up">
                        <h3 class="event-type">Akad Nikah</h3>
                        <div class="event-detail"><i class="far fa-calendar"></i> Rabu, 19 Oktober 2026</div>
                        <div class="event-detail"><i class="far fa-clock"></i> 09:00 Wita - Selesai</div>
                        <div class="event-detail"><i class="fas fa-map-marker-alt"></i> Pantai Melasti, Ungasan, Bali</div>
                        <a href="https://maps.google.com" target="_blank" class="btn-outline">Open Map</a>
                    </div>
                    
                    <div class="event-card" data-aos="fade-up" data-aos-delay="200">
                        <h3 class="event-type">Resepsi</h3>
                        <div class="event-detail"><i class="far fa-calendar"></i> Rabu, 19 Oktober 2026</div>
                        <div class="event-detail"><i class="far fa-clock"></i> 16:00 Wita (Sunset)</div>
                        <div class="event-detail"><i class="fas fa-map-marker-alt"></i> Palmilla Beach Club, Bali</div>
                        <a href="https://maps.google.com" target="_blank" class="btn-outline">Open Map</a>
                    </div>
                </div>

                <div class="countdown-wrap" data-aos="fade-up">
                    <h3 style="color: #fff; margin-bottom: 30px; letter-spacing: 2px;">Menghitung Hari</h3>
                    <div class="countdown" id="countdown">
                        <div class="time-box"><span id="days">00</span><small>Days</small></div>
                        <div class="time-box"><span id="hours">00</span><small>Hours</small></div>
                        <div class="time-box"><span id="mins">00</span><small>Mins</small></div>
                        <div class="time-box"><span id="secs">00</span><small>Secs</small></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- GALLERY -->
        <section class="section">
            <div class="container">
                <h2 class="section-title">Gallery</h2>
                <div class="gallery-grid" data-aos="fade-up">
                    <div class="gallery-item"><img src="https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80"></div>
                    <div class="gallery-item"><img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80"></div>
                    <div class="gallery-item"><img src="https://images.unsplash.com/photo-1460500063983-994d4c27756c?auto=format&fit=crop&w=600&q=80"></div>
                    <div class="gallery-item"><img src="https://images.unsplash.com/photo-1511285560929-80b456fea0bc?auto=format&fit=crop&w=600&q=80"></div>
                    <div class="gallery-item"><img src="https://images.unsplash.com/photo-1515934751635-c81c6bc9a2d8?auto=format&fit=crop&w=600&q=80"></div>
                </div>
            </div>
        </section>

        <!-- GIFT -->
        <section class="gift-section">
            <div class="wave-top">
                <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                    <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V0C71.55,39.06,146.53,60.89,223,65.88,256.33,68,289.47,62.33,321.39,56.44Z" class="shape-fill"></path>
                </svg>
            </div>
            
            <div class="container" style="padding-top: 80px;">
                <h2 class="section-title">Wedding Gift</h2>
                <p class="text-center" style="margin-bottom: 40px; color: var(--text-light); font-weight: 300;">Jika Bapak/Ibu/Saudara/i berkenan memberikan tanda kasih, dapat melalui rekening berikut:</p>
                
                <div data-aos="fade-up">
                    <div class="gift-card">
                        <div class="bank-name">BCA</div>
                        <div class="bank-account">1234 5678 90</div>
                        <div class="bank-user">A.N. Rama Wijaya</div>
                        <button onclick="salinTeks('1234567890')" class="btn-outline"><i class="far fa-copy"></i> Salin Rekening</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- RSVP -->
        <section class="rsvp-section">
            <div class="container">
                <div class="rsvp-box" data-aos="fade-up">
                    <h2 class="section-title" style="font-size: 2.5rem; margin-bottom: 20px;">RSVP</h2>
                    <p style="text-align: center; color: var(--text-light); margin-bottom: 30px; font-weight: 300;">Mohon konfirmasi kehadiran Anda di bawah ini</p>
                    
                    <form id="formRSVP" onsubmit="kirimRSVP(event)">
                        <div class="form-group">
                            <input type="text" id="namaTamu" class="form-control" placeholder="Nama Anda" required>
                        </div>
                        <div class="form-group">
                            <select id="statusHadir" class="form-control" required style="color: var(--text-light);" onchange="this.style.color='var(--text-main)'">
                                <option value="" disabled selected>Pilih Kehadiran</option>
                                <option value="Hadir">Akan Hadir</option>
                                <option value="Tidak Hadir">Tidak Hadir</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <textarea id="pesanTamu" class="form-control" placeholder="Ucapkan doa dan pesan Anda..." required></textarea>
                        </div>
                        <button type="submit" class="btn-submit">Kirim via WhatsApp</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer>
            <div class="footer-logo">Rama & Sita</div>
            <p style="font-size: 0.9rem; color: var(--text-light); font-weight: 300;">Merupakan kebahagiaan bagi kami atas doa dan kehadiran Anda.</p>
            <div style="margin-top: 50px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 3px; color: #ccc;">
                Embun Visual
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
            musicBtn.innerHTML = '<i class="fas fa-volume-up"></i>';
            setTimeout(() => { AOS.refresh(); }, 500);
        }

        function toggleMusic() {
            if (isPlaying) { 
                audio.pause(); 
                musicBtn.innerHTML = '<i class="fas fa-volume-mute"></i>';
            } else { 
                audio.play(); 
                musicBtn.innerHTML = '<i class="fas fa-volume-up"></i>';
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
                document.getElementById("countdown").innerHTML = "<h3 style='color:#fff; font-weight:300;'>Hari Bahagia Tiba!</h3>";
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
                timer: 2000, background: '#A2D5DF', color: '#fff', iconColor: '#fff' 
            });
        }

        function kirimRSVP(e) {
            e.preventDefault(); 
            let nama = document.getElementById('namaTamu').value;
            let hadir = document.getElementById('statusHadir').value;
            let pesan = document.getElementById('pesanTamu').value;
            let noMempelai = "6281234567890"; 
            let textWA = `Om Swastyastu,%0A%0ASaya *${nama}*, mengkonfirmasi kehadiran: *${hadir}*.%0A%0A*Pesan & Doa:*%0A"${pesan}"`;
            
            window.open(`https://api.whatsapp.com/send?phone=${noMempelai}&text=${textWA}`, '_blank');
            Swal.fire({ icon: 'success', title: 'Terkirim!', text: 'Proses dilanjutkan ke WhatsApp.', confirmButtonColor: '#A2D5DF' });
            document.getElementById('formRSVP').reset();
            document.getElementById('statusHadir').style.color = "var(--text-light)";
        }
    </script>
</body>
</html>
