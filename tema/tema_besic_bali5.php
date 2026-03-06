<?php
// tema/tema_besic_bali5.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawiwahan Rama & Sita | Hybrid (Tradition & Nature)</title>

    <!-- Google Fonts: Playfair Display & Dosis (Swalapatra vibe) -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Dosis:wght@300;400;500;600&family=Damion&family=Medula+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            /* Palette: Hybrid Dark Wood & Jungle Nature */
            --primary: #2C1E16;        /* Deep Expresso/Dark Wood */
            --primary-light: #4A3515;  
            --bg-body: #161D15;        /* Very dark forest green base */
            --surface: rgba(44, 30, 22, 0.85); /* Transparent dark wood for glass effect */
            --text-main: #E8E5DF;      
            --text-muted: #A3A09A;     
            --accent: #D4AF37;         /* Classic Prada Gold */
            --accent-hover: #b8962e;   
            --green: #2B4522;          /* Moss green for accents */
            
            --font-serif: 'Playfair Display', serif;
            --font-sans: 'Dosis', sans-serif;
            --font-script: 'Damion', cursive;
            --font-tall: 'Medula One', cursive;
            
            --border-radius: 12px;
            --transition: all 0.5s cubic-bezier(0.25, 1, 0.5, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: var(--font-sans); background-color: var(--bg-body); color: var(--text-main); overflow-x: hidden; -webkit-font-smoothing: antialiased; letter-spacing: 0.5px; }
        h1, h2, h3, h4 { font-weight: 500; line-height: 1.3; color: var(--accent); }
        p { line-height: 1.7; }
        
        .text-center { text-align: center; }

        /* SCROLLBAR */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-body); }
        ::-webkit-scrollbar-thumb { background: var(--accent); border-radius: 4px; }

        /* OVERLAY - Nature blurred */
        #cover-overlay { 
            position: fixed; top: 0; left: 0; width: 100%; height: 100vh; 
            background: url('https://images.unsplash.com/photo-1518531933037-91b2f5f229cc?auto=format&fit=crop&w=1920&q=80') center/cover; 
            z-index: 9999; display: flex; flex-direction: column; align-items: center; justify-content: center; 
            text-align: center; transition: opacity 1.5s ease, visibility 1.5s ease; color: white; 
        }
        #cover-overlay::before { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(44,30,22,0.95), rgba(22,29,21,0.8)); pointer-events: none; }
        #cover-overlay.open { opacity: 0; visibility: hidden; pointer-events: none; }
        
        .cover-content { position: relative; z-index: 10; padding: 40px; text-align: center; }
        .cover-dear { font-family: var(--font-sans); font-size: 1rem; color: var(--text-muted); margin-bottom: 5px; text-transform: uppercase; letter-spacing: 4px;}
        .cover-guest { font-family: var(--font-serif); font-size: 3rem; font-weight: 600; color: var(--accent); margin-bottom: 20px; text-shadow: 0 5px 15px rgba(0,0,0,0.5); }
        
        .btn-buka { background: transparent; color: var(--accent); padding: 12px 30px; border: 1px solid var(--accent); cursor: pointer; font-family: var(--font-sans); font-size: 1rem; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; transition: var(--transition); display: inline-flex; align-items: center; gap: 8px; }
        .btn-buka:hover { background: var(--accent); color: var(--primary); box-shadow: 0 0 20px rgba(212, 175, 55, 0.4); }

        /* HERO - Parallax Nature */
        .hero { min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; position: relative; background: var(--bg-body); padding-bottom: 100px; }
        .hero-img-bg { position: absolute; inset: 0; background: url('https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=1920&q=80') center/cover fixed; opacity: 0.3; } /* Fixed attachment for parallax */
        .hero-img-bg::after { content: ''; position: absolute; inset: 0; background: linear-gradient(to top, var(--primary) 0%, transparent 100%); }
        
        .hero-content { position: relative; z-index: 2; padding: 20px; margin-top: 50px; background: var(--surface); backdrop-filter: blur(10px); border: 1px solid rgba(212,175,55,0.2); border-radius: 4px; padding: 50px 30px;}
        .hero-date { font-family: var(--font-serif); font-size: 1.2rem; letter-spacing: 4px; margin-bottom: 15px; color: var(--text-main); }
        .hero-title { font-size: 5.5rem; font-family: var(--font-serif); font-weight: 400; color: var(--accent); line-height: 1; margin-bottom: 10px; }
        .hero-title span { font-size: 4rem; font-family: var(--font-script); color: #fff; margin: 0 10px; opacity: 0.8;}

        /* SECTIONS - Interspersed Parallax */
        .section { padding: 80px 20px; position: relative; z-index: 2; background: var(--primary); }
        .section-parallax { background: url('https://images.unsplash.com/photo-1518531933037-91b2f5f229cc?auto=format&fit=crop&w=1920&q=80') center/cover fixed; position: relative; padding: 100px 20px;}
        .section-parallax::before { content: ''; position: absolute; inset: 0; background: rgba(44,30,22,0.85); }
        
        .container { max-width: 900px; margin: 0 auto; text-align: center; position: relative; z-index: 2;}
        .section-title { font-family: var(--font-serif); font-size: 2.5rem; letter-spacing: 3px; color: var(--accent); margin-bottom: 50px; text-transform: uppercase;}

        /* QUOTE */
        .quote-box { max-width: 700px; margin: 0 auto;}
        .quote-icon { text-align: center; margin-bottom: 20px; }
        .quote-icon i { font-size: 2rem; color: var(--accent); opacity: 0.5;}
        .quote-text { font-family: var(--font-serif); font-size: 1.2rem; color: var(--text-main); line-height: 1.8; font-style: italic; margin-bottom: 20px; }
        .quote-source { font-family: var(--font-sans); font-weight: 600; color: var(--accent); font-size: 1rem; letter-spacing: 2px; text-transform: uppercase; }

        /* COUPLE SECTION */
        .couple-grid { display: grid; grid-template-columns: 1fr; gap: 50px; align-items: center; max-width: 900px; margin: 0 auto; }
        .profile { display: flex; flex-direction: column; align-items: center; }
        .profile-img-wrap { width: 220px; height: 300px; margin-bottom: 25px; border-radius: 100px 100px 0 0; overflow: hidden; border: 2px solid var(--accent); padding: 5px; position: relative;}
        .profile-img-inner { width: 100%; height: 100%; border-radius: 95px 95px 0 0; overflow: hidden;}
        .profile-img-inner img { width: 100%; height: 100%; object-fit: cover; filter: sepia(50%) brightness(0.8); transition: var(--transition); }
        .profile-img-wrap:hover .profile-img-inner img { filter: sepia(0%) brightness(1); transform: scale(1.05); }
        
        .profile-name { font-size: 3rem; color: var(--accent); margin-bottom: 5px; line-height: 1; font-family: var(--font-serif); }
        .profile-title { font-family: var(--font-sans); font-size: 1.1rem; margin-bottom: 10px; color: #fff; text-transform: uppercase; letter-spacing: 2px;}
        .profile-desc { font-family: var(--font-sans); font-size: 0.95rem; line-height: 1.5; color: var(--text-muted); }
        
        .ampersand { font-size: 4rem; font-family: var(--font-script); color: #fff; opacity: 0.5; margin: 20px 0; }

        /* EVENT SECTION */
        .event-container { display: grid; grid-template-columns: 1fr; gap: 30px; position: relative; z-index: 2;}
        .event-card { background: var(--surface); padding: 50px 30px; border: 1px solid rgba(212,175,55,0.2); backdrop-filter: blur(5px); text-align: center; transition: var(--transition); }
        .event-card:hover { border-color: var(--accent); background: rgba(44, 30, 22, 0.95); }
        
        .event-type { font-family: var(--font-script); font-size: 3rem; color: var(--accent); margin-bottom: 20px; }
        
        .event-detail-item { margin-bottom: 15px; }
        .event-detail-item i { color: var(--accent); width: 25px; }
        .event-detail-item span { font-family: var(--font-sans); font-size: 1.1rem; color: var(--text-main); font-weight: 400; }
        
        .event-map-btn { display: inline-block; background: transparent; color: var(--accent); border: 1px solid var(--accent); padding: 10px 25px; text-decoration: none; font-family: var(--font-sans); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 2px; margin-top: 25px; transition: var(--transition); }
        .event-map-btn:hover { background: var(--accent); color: var(--primary); }

        /* COUNTDOWN IN EVENT SECTION */
        .countdown-wrap { margin-top: 60px; padding: 40px 0; border-top: 1px dashed rgba(212,175,55,0.3); border-bottom: 1px dashed rgba(212,175,55,0.3);}
        .countdown { display: flex; justify-content: center; gap: 10px; flex-wrap: wrap; }
        .time-box { background: transparent; color: var(--text-main); min-width: 80px; text-align: center; }
        .time-box span { display: block; font-family: var(--font-serif); font-size: 2.5rem; font-weight: 400; line-height: 1; margin-bottom: 5px; color: var(--accent);}
        .time-box small { font-size: 0.75rem; font-family: var(--font-sans); text-transform: uppercase; letter-spacing: 2px; opacity: 0.6; }

        /* GALLERY */
        .gallery-section { padding: 0; }
        .gallery-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0; }
        .gallery-item { width: 100%; height: 300px; object-fit: cover; filter: grayscale(40%) contrast(1.1); transition: var(--transition); cursor: pointer; }
        .gallery-item:hover { filter: grayscale(0%) contrast(1); z-index: 2; position: relative; transform: scale(1.05); box-shadow: 0 0 30px rgba(0,0,0,0.8);}

        /* GIFT */
        .gift-card { background: var(--surface); padding: 40px; display: inline-block; margin: 10px; border: 1px solid rgba(212,175,55,0.3); backdrop-filter: blur(5px); }
        .bank-name { font-family: var(--font-serif); font-size: 1.5rem; color: #fff; margin-bottom: 10px; letter-spacing: 2px;}
        .bank-account { font-family: var(--font-sans); font-size: 1.8rem; letter-spacing: 3px; margin-bottom: 5px; color: var(--accent);}
        .bank-user { font-family: var(--font-sans); font-size: 1rem; opacity: 0.7; margin-bottom: 20px; text-transform: uppercase;}
        .btn-copy-rek { background: transparent; color: var(--accent); border: 1px solid var(--accent); padding: 10px 25px; cursor: pointer; font-family: var(--font-sans); font-size: 0.9rem; letter-spacing: 1px; text-transform: uppercase; transition: var(--transition); }
        .btn-copy-rek:hover { background: var(--accent); color: var(--primary); }

        /* RSVP */
        .rsvp-section { background: var(--bg-body); padding-bottom: 120px;}
        .rsvp-box { background: var(--surface); max-width: 600px; margin: 0 auto; padding: 50px 40px; border: 1px solid rgba(212,175,55,0.3); position: relative;}
        .rsvp-box::before { content: ''; position: absolute; top: -1px; left: 50%; transform: translateX(-50%); width: 100px; height: 3px; background: var(--accent); }
        .form-group { margin-bottom: 25px; text-align: left; }
        .form-control { width: 100%; padding: 15px; border: none; border-bottom: 1px solid #555; background: transparent; color: white; font-family: var(--font-sans); font-size: 1rem; transition: var(--transition); }
        .form-control:focus { outline: none; border-bottom-color: var(--accent); }
        textarea.form-control { resize: vertical; min-height: 100px; }
        .btn-submit { background: var(--accent); color: var(--primary); width: 100%; padding: 15px; border: none; font-family: var(--font-sans); font-weight: 600; font-size: 1rem; text-transform: uppercase; letter-spacing: 2px; cursor: pointer; transition: var(--transition); margin-top: 10px;}
        .btn-submit:hover { background: #fff; }

        /* FOOTER */
        footer { background: #000; color: #666; text-align: center; padding: 60px 20px 100px; }
        .footer-logo { font-family: var(--font-script); font-size: 4rem; color: #fff; opacity: 0.8; margin-bottom: 15px; }

        /* MUSIC BTN */
        .music-btn { position: fixed; bottom: 30px; right: 30px; background: var(--surface); color: var(--accent); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; border: 1px solid var(--accent); cursor: pointer; z-index: 1000; backdrop-filter: blur(5px); transition: var(--transition); }
        .music-btn:hover { background: var(--accent); color: var(--primary);}
        .spin { animation: spin 4s linear infinite; }
        @keyframes spin { 100% { transform: rotate(360deg); } }

        /* Media Queries */
        @media (min-width: 768px) {
            .couple-grid { grid-template-columns: 1fr auto 1fr; gap: 40px; }
            .event-container { grid-template-columns: 1fr 1fr; gap: 40px; }
            .gallery-grid { grid-template-columns: repeat(4, 1fr); }
            .gallery-item:nth-child(1), .gallery-item:nth-child(6) { grid-column: span 2; grid-row: span 2; height: 600px; }
        }
    </style>
</head>
<body>

    <!-- OVERLAY -->
    <div id="cover-overlay">
        <div class="cover-content" data-aos="zoom-in" data-aos-duration="1500">
            <div class="cover-dear">Kepada Yth.</div>
            <div class="cover-guest"><?php echo isset($_GET['to']) ? htmlspecialchars($_GET['to']) : 'Keluarga & Sahabat'; ?></div>
            <p style="font-family: var(--font-sans); font-size: 0.9rem; color: #888; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 30px;">Acara Pawiwahan Agong</p>
            
            <button class="btn-buka" onclick="bukaUndangan()">
                <i class="far fa-envelope-open"></i> Buka Undangan
            </button>
        </div>
    </div>

    <!-- AUDIO -->
    <audio id="bgMusic" loop><source src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-5.mp3" type="audio/mpeg"></audio>
    <button class="music-btn" id="musicBtn" onclick="toggleMusic()"><i class="fas fa-music"></i></button>

    <div id="main-content">
        
        <!-- HERO -->
        <section class="hero">
            <div class="hero-img-bg"></div>
            
            <div class="hero-content" data-aos="fade-up" data-aos-duration="2000">
                <p style="font-family: var(--font-sans); color: var(--text-muted); letter-spacing: 4px; text-transform: uppercase; margin-bottom: 20px; font-weight: 500;">The Wedding Of</p>
                <h1 class="hero-title">Rama <span>&</span> Sita</h1>
                <div style="width: 50px; height: 1px; background: var(--accent); margin: 20px auto;"></div>
                <div class="hero-date">19 . 10 . 2026</div>
            </div>
        </section>

        <!-- QUOTE (Parallax) -->
        <section class="section-parallax">
            <div class="container">
                <div class="quote-box" data-aos="fade-up">
                    <div class="quote-icon"><i class="fas fa-leaf"></i></div>
                    <p class="quote-text">
                        "Dalam sebuah pernikahan kalian disatukan demi sebuah kebahagiaan dengan janji hati untuk saling membahagiakan. Bersamaku engkau akan hidup selamanya karena Tuhan pasti akan memberikan karunia sebagai pelindung dan saksi..."
                    </p>
                    <div class="quote-source">Rgveda : X.85.36</div>
                </div>
            </div>
        </section>

        <!-- COUPLE -->
        <section class="section">
            <div class="container">
                <h2 class="section-title">Sang Mempelai</h2>
                <div class="couple-grid">
                    <div class="profile" data-aos="fade-up">
                        <div class="profile-img-wrap">
                            <div class="profile-img-inner">
                                <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=500&q=80" alt="Groom">
                            </div>
                        </div>
                        <h2 class="profile-name">Rama</h2>
                        <div class="profile-title">Anak Agung Rama Wijaya</div>
                        <div class="profile-desc">Putra pertama dari pasangan<br>A.A. Putu Wijaya &amp; Ayu Trisna</div>
                    </div>

                    <div class="ampersand" data-aos="zoom-in">&</div>

                    <div class="profile" data-aos="fade-up" data-aos-delay="200">
                        <div class="profile-img-wrap">
                            <div class="profile-img-inner">
                                <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=500&q=80" alt="Bride">
                            </div>
                        </div>
                        <h2 class="profile-name">Sita</h2>
                        <div class="profile-title">G.A.K. Sita Widayani</div>
                        <div class="profile-desc">Putri kedua dari pasangan<br>I.G.K Widayana &amp; G.A. Komang</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- EVENTS (Parallax) -->
        <section class="section-parallax">
            <div class="container">
                <h2 class="section-title">Rangkaian Acara</h2>

                <div class="event-container">
                    <div class="event-card" data-aos="fade-up">
                        <h3 class="event-type">Akad Nikah</h3>
                        <div class="event-detail-item">
                            <i class="far fa-calendar-alt"></i> <span>Rabu, 19 Oktober 2026</span>
                        </div>
                        <div class="event-detail-item">
                            <i class="far fa-clock"></i> <span>09.00 Wita - Selesai</span>
                        </div>
                        <div class="event-detail-item">
                            <i class="fas fa-map-marker-alt"></i> <span>Jln Imam Bonjol No.19 Br.Panti, Bali</span>
                        </div>
                        <a href="https://maps.google.com" target="_blank" class="event-map-btn">Lihat Peta</a>
                    </div>

                    <div class="event-card" data-aos="fade-up" data-aos-delay="200">
                        <h3 class="event-type">Resepsi</h3>
                        <div class="event-detail-item">
                            <i class="far fa-calendar-alt"></i> <span>Rabu, 19 Oktober 2026</span>
                        </div>
                        <div class="event-detail-item">
                            <i class="far fa-clock"></i> <span>13.00 Wita - Selesai</span>
                        </div>
                        <div class="event-detail-item">
                            <i class="fas fa-map-marker-alt"></i> <span>Jln Imam Bonjol No.19 Br.Panti, Bali</span>
                        </div>
                        <a href="https://maps.google.com" target="_blank" class="event-map-btn">Lihat Peta</a>
                    </div>
                </div>

                <div class="countdown-wrap" data-aos="fade-up">
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
        <section class="gallery-section">
            <div class="gallery-grid" data-aos="fade-up">
                <img src="https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1000&q=80" class="gallery-item">
                <img src="https://images.unsplash.com/photo-1520854221256-17451cc331bf?auto=format&fit=crop&w=600&q=80" class="gallery-item">
                <img src="https://images.unsplash.com/photo-1460500063983-994d4c27756c?auto=format&fit=crop&w=600&q=80" class="gallery-item">
                <img src="https://images.unsplash.com/photo-1511285560929-80b456fea0bc?auto=format&fit=crop&w=600&q=80" class="gallery-item">
                <img src="https://images.unsplash.com/photo-1515934751635-c81c6bc9a2d8?auto=format&fit=crop&w=600&q=80" class="gallery-item">
                <img src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=1000&q=80" class="gallery-item">
            </div>
        </section>

        <!-- GIFT (Parallax) -->
        <section class="section-parallax gift-section">
            <div class="container">
                <h2 class="section-title">Tanda Kasih</h2>
                <p style="margin-bottom: 40px; color: var(--text-muted);">Doa restu Anda merupakan karunia yang sangat berarti bagi kami. Namun jika ingin memberikan tanda kasih, dapat melalui rekening di bawah ini:</p>
                
                <div data-aos="fade-up">
                    <div class="gift-card">
                        <div class="bank-name">BCA</div>
                        <div class="bank-account">1234 5678 90</div>
                        <div class="bank-user">A/N Rama Wijaya</div>
                        <button onclick="salinTeks('1234567890')" class="btn-copy-rek">Salin Rekening</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- RSVP -->
        <section class="section rsvp-section">
            <div class="container">
                <div class="rsvp-box" data-aos="fade-up">
                    <h2 class="section-title" style="margin-bottom: 20px;">Buku Tamu</h2>
                    <p style="color: var(--text-muted); margin-bottom: 40px;">Mohon konfirmasi kehadiran Anda</p>
                    
                    <form id="formRSVP" onsubmit="kirimRSVP(event)">
                        <div class="form-group">
                            <input type="text" id="namaTamu" class="form-control" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="form-group">
                            <select id="statusHadir" class="form-control" required style="color: #666;" onchange="this.style.color='#fff'">
                                <option value="" disabled selected>Pilih Kehadiran</option>
                                <option value="Hadir">Akan Hadir</option>
                                <option value="Tidak Hadir">Tidak Bisa Hadir</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <textarea id="pesanTamu" class="form-control" placeholder="Tuliskan doa restu Anda..." required></textarea>
                        </div>
                        <button type="submit" class="btn-submit">Kirim via WhatsApp</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer>
            <div class="footer-logo">Rama &amp; Sita</div>
            <p style="font-family: var(--font-sans); font-size: 0.9rem; color: #555;">
                Merupakan suatu kehormatan apabila Anda berkenan hadir.
            </p>
            <div style="margin-top: 50px; font-size: 0.7rem; color: #333; text-transform: uppercase; letter-spacing: 4px;">
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
        const targetDate = new Date("Oct 19, 2026 09:00:00").getTime();
        const timer = setInterval(function() {
            const now = new Date().getTime();
            const distance = targetDate - now;

            if (distance < 0) {
                clearInterval(timer);
                document.getElementById("countdown").innerHTML = "<h3 style='color:var(--accent); font-family: var(--font-serif); font-weight:400;'>Acara Berlangsung</h3>";
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
                title: 'Disalin!', showConfirmButton: false, 
                timer: 2000, background: '#2C1E16', color: '#D4AF37', iconColor: '#D4AF37' 
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
            
            Swal.fire({ icon: 'success', title: 'Terima Kasih!', text: 'Pesan diteruskan ke WhatsApp.', confirmButtonColor: '#D4AF37', background: '#2C1E16', color: '#fff' });
            document.getElementById('formRSVP').reset();
            document.getElementById('statusHadir').style.color = "#666";
        }
    </script>
</body>
</html>
