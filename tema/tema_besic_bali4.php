<?php
// tema/tema_besic_bali4.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawiwahan Rama & Sita | Soft Alam 2 (Ubud Jungle)</title>

    <!-- Google Fonts: Nunito & Caveat -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&family=Caveat:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            /* Palette: Soft Ubud Jungle */
            --primary: #6A8A61;        /* Sage / Moss Green */
            --primary-dark: #4A6343;   
            --bg-body: #FDFBF7;        /* Very Soft Cream/Off-white */
            --bg-accent: #EAECE6;      /* Light greenish grey */
            --text-main: #4A5645;      /* Dark green-grey for softer text */
            --text-muted: #8B9B85;     
            --accent: #D29D52;         /* Soft Honey/Wood color for highlights */
            
            --font-sans: 'Nunito', sans-serif;
            --font-script: 'Caveat', cursive;
            
            --border-radius: 20px;
            --blob-1: 60% 40% 30% 70% / 60% 30% 70% 40%;
            --blob-2: 30% 70% 70% 30% / 30% 30% 70% 70%;
            --transition: all 0.5s ease;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: var(--font-sans); background-color: var(--bg-body); color: var(--text-main); overflow-x: hidden; -webkit-font-smoothing: antialiased; }
        h1, h2, h3, h4 { font-weight: 700; line-height: 1.2; color: var(--primary-dark); }
        p { line-height: 1.6; }
        
        .text-center { text-align: center; }

        /* SCROLLBAR */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-body); }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 10px; }

        /* OVERLAY */
        #cover-overlay { 
            position: fixed; top: 0; left: 0; width: 100%; height: 100vh; 
            background: var(--primary-dark) url('https://images.unsplash.com/photo-1544644557-4bfeb1c46369?auto=format&fit=crop&w=1920&q=80') center/cover; 
            z-index: 9999; display: flex; flex-direction: column; align-items: center; justify-content: center; 
            text-align: center; transition: opacity 1.5s ease, visibility 1.5s ease; 
        }
        #cover-overlay::before { content: ''; position: absolute; inset: 0; background: rgba(106, 138, 97, 0.9); pointer-events: none; }
        #cover-overlay.open { opacity: 0; visibility: hidden; pointer-events: none; }
        
        .cover-content { position: relative; z-index: 10; padding: 50px 30px; border-radius: var(--blob-1); background: rgba(253, 251, 247, 0.1); border: 2px solid rgba(253, 251, 247, 0.3); backdrop-filter: blur(10px); }
        .cover-dear { font-size: 0.95rem; color: #eee; margin-bottom: 5px; font-weight: 600; }
        .cover-guest { font-family: var(--font-script); font-size: 3.5rem; font-weight: 700; color: #fff; margin-bottom: 15px; }
        
        .btn-buka { background: #fff; color: var(--primary-dark); padding: 12px 30px; border: none; border-radius: 50px; cursor: pointer; font-family: var(--font-sans); font-size: 1rem; font-weight: 700; transition: var(--transition); display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.2);}
        .btn-buka:hover { background: var(--accent); color: #fff; transform: translateY(-3px); }

        /* HERO - Organic Shapes */
        .hero { min-height: 100vh; position: relative; background: var(--bg-body); display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; overflow: hidden; padding-bottom: 50px;}
        .hero-blob-bg { position: absolute; top: -10vh; right: -10vw; width: 60vw; height: 60vw; max-width: 600px; max-height: 600px; background: var(--bg-accent); border-radius: var(--blob-2); z-index: 0; opacity: 0.8;}
        
        .hero-img-wrap { width: 300px; height: 350px; border-radius: var(--blob-1); overflow: hidden; position: relative; z-index: 2; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(106, 138, 97, 0.2); border: 5px solid #fff;}
        .hero-img-wrap img { width: 100%; height: 100%; object-fit: cover; }
        
        .hero-content { position: relative; z-index: 2; padding: 0 20px; }
        .hero-intro { font-family: var(--font-script); font-size: 2rem; color: var(--accent); margin-bottom: 5px; }
        .hero-title { font-size: 4.5rem; color: var(--primary-dark); line-height: 1; margin-bottom: 10px; font-weight: 800; letter-spacing: -1px;}
        .hero-date { font-weight: 600; font-size: 1.1rem; color: var(--text-muted); letter-spacing: 2px; }
        
        /* LEAF DECORATION */
        .leaf-icon { font-size: 2rem; color: var(--primary); opacity: 0.6; margin: 20px 0; }

        /* SECTIONS */
        .section { padding: 80px 20px; position: relative; z-index: 2;}
        .container { max-width: 900px; margin: 0 auto; text-align: center; }
        
        .section-title { font-family: var(--font-script); font-size: 3.5rem; margin-bottom: 30px; color: var(--primary-dark); }

        /* QUOTE */
        .quote-box { background: var(--bg-accent); padding: 50px 30px; border-radius: var(--blob-2); max-width: 700px; margin: 0 auto; position: relative; }
        .quote-quote { font-size: 4rem; color: var(--primary); opacity: 0.2; position: absolute; top: 10px; left: 20px; font-family: serif; line-height: 1;}
        .quote-text { font-size: 1.1rem; color: var(--text-main); font-weight: 600; line-height: 1.8; margin-bottom: 20px; position: relative; z-index: 2;}
        .quote-source { font-size: 0.9rem; font-weight: 700; color: var(--accent); }

        /* COUPLE */
        .couple-grid { display: grid; gap: 40px; margin-top: 50px; }
        .profile { background: #fff; padding: 40px 20px; border-radius: 30px; box-shadow: 0 15px 40px rgba(106, 138, 97, 0.08); display: flex; flex-direction: column; align-items: center; }
        
        .profile-img { width: 150px; height: 150px; margin-bottom: 20px; border-radius: var(--blob-1); overflow: hidden; border: 4px solid var(--bg-body); }
        .profile-img img { width: 100%; height: 100%; object-fit: cover; transition: var(--transition); }
        .profile:hover .profile-img { border-radius: var(--blob-2); }
        .profile:hover .profile-img img { transform: scale(1.1); }
        
        .profile-name { font-size: 2rem; margin-bottom: 5px; color: var(--primary-dark); }
        .profile-title { font-size: 0.9rem; font-weight: 700; color: var(--accent); margin-bottom: 10px; }
        .profile-desc { font-size: 0.95rem; color: var(--text-muted); }
        
        .ampersand { font-family: var(--font-script); font-size: 4rem; color: var(--primary); align-self: center; }

        /* EVENT SECTION */
        .event-section { background: var(--primary-dark); color: #fff; padding: 100px 20px; position: relative; overflow: hidden;}
        /* Add some organic wave at the top of event */
        .event-section::before { content: ''; position: absolute; top: -50px; left: -10%; width: 120%; height: 100px; background: var(--bg-body); border-radius: 50%; }
        
        .event-section .section-title { color: #fff; }
        .event-grid { display: grid; grid-template-columns: 1fr; gap: 30px; position: relative; z-index: 2; margin-top: 50px;}
        .event-card { background: rgba(255,255,255,0.05); padding: 40px 30px; border-radius: 20px; text-align: center; border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(5px); }
        
        .event-type { font-family: var(--font-script); font-size: 2.5rem; margin-bottom: 20px; color: var(--bg-body); }
        .event-detail { margin-bottom: 15px; font-weight: 400; font-size: 1.05rem;}
        .event-detail i { color: var(--accent); width: 25px; }
        
        .btn-outline { background: #fff; color: var(--primary-dark); padding: 10px 25px; border-radius: 50px; font-weight: 700; font-size: 0.9rem; transition: var(--transition); display: inline-block; text-decoration: none; margin-top: 15px;}
        .btn-outline:hover { background: var(--accent); color: #fff; }

        /* COUNTDOWN */
        .countdown-wrap { margin-top: 60px; position: relative; z-index: 2;}
        .countdown { display: flex; justify-content: center; gap: 15px; flex-wrap: wrap; }
        .time-box { background: rgba(255,255,255,0.1); border-radius: var(--blob-2); width: 80px; height: 80px; display: flex; flex-direction: column; justify-content: center; align-items: center; border: 1px solid rgba(255,255,255,0.2);}
        .time-box span { font-size: 2rem; font-weight: 700; line-height: 1; margin-bottom: 5px; color: #fff;}
        .time-box small { font-size: 0.7rem; font-weight: 600; text-transform: uppercase; color: rgba(255,255,255,0.7);}

        /* GALLERY */
        .gallery-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; }
        .gallery-item { width: 100%; height: 200px; border-radius: 20px; overflow: hidden; }
        .gallery-item img { width: 100%; height: 100%; object-fit: cover; transition: var(--transition); }
        .gallery-item:hover img { transform: scale(1.1); }

        /* GIFT */
        .gift-section { background: var(--bg-accent); padding: 100px 20px;}
        .gift-card { background: #fff; padding: 40px; border-radius: 30px; text-align: center; max-width: 400px; margin: 0 auto; box-shadow: 0 10px 30px rgba(106, 138, 97, 0.1); }
        .bank-name { font-weight: 800; font-size: 1.3rem; color: var(--primary-dark); margin-bottom: 10px; }
        .bank-account { font-size: 1.8rem; font-weight: 700; margin-bottom: 5px; color: var(--text-main); }
        .bank-user { font-size: 0.95rem; color: var(--text-muted); margin-bottom: 20px; }

        /* RSVP */
        .rsvp-section { background: var(--bg-body); position: relative; padding-bottom: 120px;}
        .rsvp-box { background: #fff; padding: 40px; border-radius: 30px; box-shadow: 0 15px 40px rgba(106, 138, 97, 0.08); max-width: 600px; margin: 0 auto; border: 2px solid var(--bg-accent);}
        .form-group { margin-bottom: 20px; text-align: left;}
        .form-control { width: 100%; padding: 15px 20px; border: 2px solid var(--bg-accent); border-radius: 15px; font-family: var(--font-sans); font-size: 1rem; color: var(--text-main); background: var(--bg-body); transition: var(--transition); }
        .form-control:focus { outline: none; border-color: var(--primary); background: #fff; }
        textarea.form-control { resize: vertical; min-height: 120px; border-radius: 20px;}
        .btn-submit { background: var(--primary); color: #fff; width: 100%; padding: 15px; border: none; border-radius: 15px; font-weight: 700; font-family: var(--font-sans); font-size: 1rem; cursor: pointer; transition: var(--transition); }
        .btn-submit:hover { background: var(--primary-dark); }

        /* FOOTER */
        footer { background: var(--primary-dark); color: rgba(255,255,255,0.7); text-align: center; padding: 60px 20px 100px; }
        .footer-logo { font-family: var(--font-script); font-size: 3rem; color: #fff; margin-bottom: 10px; }

        /* MUSIC BTN */
        .music-btn { position: fixed; bottom: 30px; right: 30px; background: var(--bg-body); color: var(--primary-dark); width: 50px; height: 50px; border-radius: var(--blob-1); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; border: none; cursor: pointer; z-index: 1000; box-shadow: 0 5px 15px rgba(106, 138, 97, 0.3); transition: var(--transition); }
        .music-btn:hover { background: var(--primary); color: #fff; border-radius: 50%; transform: rotate(15deg);}
        .spin { animation: spin 4s linear infinite; border-radius: 50%; }
        @keyframes spin { 100% { transform: rotate(360deg); } }

        /* Media Queries */
        @media (min-width: 768px) {
            .couple-grid { grid-template-columns: 1fr auto 1fr; align-items: center; }
            .event-grid { grid-template-columns: 1fr 1fr; }
            .gallery-grid { grid-template-columns: repeat(3, 1fr); gap: 20px; }
            .gallery-item:nth-child(2) { grid-column: span 2; grid-row: span 2; height: 420px; }
            .hero { flex-direction: row; justify-content: center; gap: 50px; text-align: left; }
            .hero-content { margin-top: 0; padding: 0; }
            .hero-blob-bg { left: -10vw; right: auto; }
        }
    </style>
</head>
<body>

    <!-- OVERLAY -->
    <div id="cover-overlay">
        <div class="cover-content" data-aos="zoom-in" data-aos-duration="1500">
            <div class="cover-dear">Untuk:</div>
            <div class="cover-guest"><?php echo isset($_GET['to']) ? htmlspecialchars($_GET['to']) : 'Keluarga & Sahabat'; ?></div>
            <p style="font-size: 0.9rem; color: #eee; margin-bottom: 25px;">Kami mengundang Anda merasakan keindahan hari bahagia kami.</p>
            
            <button class="btn-buka" onclick="bukaUndangan()">
                <i class="fas fa-leaf"></i> Buka Undangan
            </button>
        </div>
    </div>

    <!-- AUDIO -->
    <audio id="bgMusic" loop><source src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-4.mp3" type="audio/mpeg"></audio>
    <button class="music-btn" id="musicBtn" onclick="toggleMusic()"><i class="fas fa-music"></i></button>

    <div id="main-content">
        
        <!-- HERO -->
        <section class="hero">
            <div class="hero-blob-bg"></div>
            
            <div class="hero-img-wrap" data-aos="fade-right" data-aos-duration="1500">
                <img src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=600&q=80" alt="Prewedding">
            </div>

            <div class="hero-content" data-aos="fade-left" data-aos-duration="1500" data-aos-delay="300">
                <div class="hero-intro">Resepsi Pernikahan</div>
                <h1 class="hero-title">Rama<br>& Sita</h1>
                <div class="hero-date">19 Oktober 2026</div>
                <i class="fas fa-leaf leaf-icon"></i>
            </div>
        </section>

        <!-- QUOTE -->
        <section class="section">
            <div class="container">
                <div class="quote-box" data-aos="zoom-in">
                    <div class="quote-quote">"</div>
                    <p class="quote-text">
                        "Dalam sebuah pernikahan kalian disatukan demi sebuah kebahagiaan dengan janji hati untuk saling membahagiakan. Semoga ketenangan alam senantiasa mengiringi langkah kita bersama keluarga tercinta."
                    </p>
                    <div class="quote-source">RGVEDA : X.85.36</div>
                </div>
            </div>
        </section>

        <!-- COUPLE -->
        <section class="section">
            <div class="container">
                <h2 class="section-title">Mempelai</h2>
                
                <div class="couple-grid">
                    <div class="profile" data-aos="fade-up">
                        <div class="profile-img">
                            <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=500&q=80" alt="Groom">
                        </div>
                        <h2 class="profile-name">Rama</h2>
                        <div class="profile-title">A.A. Rama Wijaya</div>
                        <div class="profile-desc">Putra Bpk A.A. Putu Wijaya<br>&amp; Ibu Ayu Trisna</div>
                    </div>

                    <div class="ampersand" data-aos="zoom-in">&</div>

                    <div class="profile" data-aos="fade-up" data-aos-delay="200">
                        <div class="profile-img">
                            <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=500&q=80" alt="Bride">
                        </div>
                        <h2 class="profile-name">Sita</h2>
                        <div class="profile-title">G.A.K. Sita Widayani</div>
                        <div class="profile-desc">Putri Bpk I.G.K Widayana<br>&amp; Ibu G.A. Komang</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- EVENTS -->
        <section class="event-section">
            <div class="container">
                <h2 class="section-title text-center" style="color: #fff;">Acara</h2>

                <div class="event-grid">
                    <div class="event-card" data-aos="fade-up">
                        <h3 class="event-type">Akad Nikah</h3>
                        <div class="event-detail"><i class="far fa-calendar-alt"></i> 19 Oktober 2026</div>
                        <div class="event-detail"><i class="far fa-clock"></i> 09:00 Wita</div>
                        <div class="event-detail"><i class="fas fa-map-marker-alt"></i> The Kayon Resort, Ubud</div>
                        <a href="https://maps.google.com" target="_blank" class="btn-outline">Lihat Lokasi</a>
                    </div>
                    
                    <div class="event-card" data-aos="fade-up" data-aos-delay="200">
                        <h3 class="event-type">Resepsi</h3>
                        <div class="event-detail"><i class="far fa-calendar-alt"></i> 19 Oktober 2026</div>
                        <div class="event-detail"><i class="far fa-clock"></i> 13:00 Wita</div>
                        <div class="event-detail"><i class="fas fa-map-marker-alt"></i> The Kayon Resort, Ubud</div>
                        <a href="https://maps.google.com" target="_blank" class="btn-outline">Lihat Lokasi</a>
                    </div>
                </div>

                <div class="countdown-wrap" data-aos="fade-up">
                    <h3 style="text-align: center; margin-bottom: 20px; font-weight: 600; color: #fff;">Hitung Mundur</h3>
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
                <h2 class="section-title">Galeri Kebahagiaan</h2>
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
            <div class="container">
                <h2 class="section-title">Tanda Kasih</h2>
                <p class="text-center" style="margin-bottom: 40px; color: var(--text-muted);">Apabila Bapak/Ibu/Saudara/i ingin memberikan tanda kasih, dapat melalui rekening berikut:</p>
                
                <div data-aos="fade-up">
                    <div class="gift-card">
                        <div class="bank-name">BCA</div>
                        <div class="bank-account">1234 5678 90</div>
                        <div class="bank-user">A/N Rama Wijaya</div>
                        <button onclick="salinTeks('1234567890')" class="btn-buka" style="background: var(--bg-accent); padding: 8px 20px; color: var(--text-main); font-size: 0.9rem; box-shadow: none;"><i class="far fa-copy"></i> Salin Rekening</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- RSVP -->
        <section class="rsvp-section section">
            <div class="container">
                <div class="rsvp-box" data-aos="fade-up">
                    <h2 class="section-title" style="margin-bottom: 10px;">Buku Tamu</h2>
                    <p style="text-align: center; color: var(--text-muted); margin-bottom: 30px;">Mohon kabari kami melalui form di bawah</p>
                    
                    <form id="formRSVP" onsubmit="kirimRSVP(event)">
                        <div class="form-group">
                            <input type="text" id="namaTamu" class="form-control" placeholder="Nama Anda" required>
                        </div>
                        <div class="form-group">
                            <select id="statusHadir" class="form-control" required style="color: var(--text-muted);" onchange="this.style.color='var(--text-main)'">
                                <option value="" disabled selected>Apakah Anda akan hadir?</option>
                                <option value="Hadir">Ya, Tentu Membahagiakan</option>
                                <option value="Tidak Hadir">Sampaikan Doa, Belum Bisa Hadir</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <textarea id="pesanTamu" class="form-control" placeholder="Tuliskan pesan..." required></textarea>
                        </div>
                        <button type="submit" class="btn-submit">Kirim via WhatsApp</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer>
            <div class="footer-logo">R & S</div>
            <p style="font-size: 0.95rem;">Terima kasih dari kami yang berbahagia.</p>
            <div style="margin-top: 40px; font-size: 0.8rem; font-weight: 700; color: rgba(255,255,255,0.3);">
                DESIGN BY EMBUN VISUAL
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
                document.getElementById("countdown").innerHTML = "<h3 style='color:#fff;'>Acara Berlangsung</h3>";
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
                timer: 2000, background: '#6A8A61', color: '#fff', iconColor: '#fff' 
            });
        }

        function kirimRSVP(e) {
            e.preventDefault(); 
            let nama = document.getElementById('namaTamu').value;
            let hadir = document.getElementById('statusHadir').value;
            let pesan = document.getElementById('pesanTamu').value;
            let noMempelai = "6281234567890"; 
            let textWA = `Om Swastyastu,%0A%0ASaya *${nama}*, mengkonfirmasi: *${hadir}*.%0A%0A*Pesan & Doa:*%0A"${pesan}"`;
            
            window.open(`https://api.whatsapp.com/send?phone=${noMempelai}&text=${textWA}`, '_blank');
            Swal.fire({ icon: 'success', title: 'Terkirim!', text: 'Proses dilanjutkan ke WhatsApp.', confirmButtonColor: '#6A8A61' });
            document.getElementById('formRSVP').reset();
            document.getElementById('statusHadir').style.color = "var(--text-muted)";
        }
    </script>
</body>
</html>
