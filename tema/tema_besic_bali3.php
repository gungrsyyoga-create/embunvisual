<?php
// tema/tema_besic_bali3.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Wedding of Romeo & Juliet | Bali Minimalist Theme</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Pinyon+Script&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            /* Palette: Soft White & Minimalist Bali */
            --primary: #A38C5B;        
            --primary-light: #C5AF7D;  
            --bg-body: #FAFAFA;        /* Pure Soft White */
            --surface: #FFFFFF;        
            --text-main: #4A4A4A;      
            --text-muted: #888888;     
            --accent: #D4AF37;         /* Bright Soft Gold */
            --accent-hover: #B5952F;
            
            --font-serif: 'Playfair Display', serif;
            --font-sans: 'Plus Jakarta Sans', sans-serif;
            --font-script: 'Pinyon Script', cursive;
            
            --border-radius: 8px;
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
            background: url('https://images.unsplash.com/photo-1544465544-1b71aee9dfa3?auto=format&fit=crop&w=1920&q=80') center/cover; 
            z-index: 9999; display: flex; flex-direction: column; align-items: center; justify-content: center; 
            text-align: center; transition: opacity 1.5s ease-in-out, visibility 1.5s; color: var(--text-main); 
            border: 15px solid var(--surface);
        }
        #cover-overlay::before { content: ''; position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(250, 250, 250, 0.7), rgba(250, 250, 250, 0.95)); pointer-events: none; }
        #cover-overlay.open { opacity: 0; visibility: hidden; }
        .cover-content { position: relative; z-index: 10; max-width: 650px; padding: 50px; background: transparent; margin: 0 20px; }
        .cover-badge { font-size: 0.75rem; letter-spacing: 3px; text-transform: uppercase; padding: 8px 0; border-bottom: 1px solid var(--primary); color: var(--primary); margin-bottom: 25px; display: inline-block; }
        .cover-title { font-size: 5.5rem; color: var(--primary); margin: 0 0 10px 0; line-height: 1.2; font-weight: 300; }
        .cover-subtitle { font-family: var(--font-serif); font-size: 1.2rem; margin-bottom: 15px; letter-spacing: 2px; color: var(--text-main); text-transform: uppercase; }
        .cover-guest { margin-top: 35px; padding-top: 20px; }
        .cover-guest span { font-size: 0.85rem; letter-spacing: 1px; color: var(--text-muted); display: block; margin-bottom: 5px; }
        .cover-guest h2 { font-family: var(--font-serif); font-size: 1.5rem; letter-spacing: 1px; color: var(--text-main); }
        .btn-buka { background: transparent; color: var(--primary); padding: 12px 35px; border: 1px solid var(--primary); border-radius: 4px; cursor: pointer; margin: 30px auto 0; font-family: var(--font-sans); font-size: 0.85rem; letter-spacing: 3px; text-transform: uppercase; transition: var(--transition); display: flex; align-items: center; gap: 10px; justify-content: center; }
        .btn-buka:hover { background: var(--primary); color: #FFF; }

        /* HERO */
        .hero { min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; background: url('https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=1920&q=80') center/cover fixed; padding: 20px; position: relative; }
        .hero::after { content: ''; position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(250, 250, 250, 0.8), rgba(250, 250, 250, 1)); pointer-events: none; }
        .hero-content { position: relative; z-index: 2; max-width: 600px; transition: opacity 1.5s ease-in-out, transform 1.5s ease-out; }
        .hidden-content { opacity: 0; transform: translateY(30px); pointer-events: none; }
        .show-content { opacity: 1; transform: translateY(0); pointer-events: auto; }
        .hero-subtitle { font-family: var(--font-serif); font-style: italic; font-size: 1.3rem; color: var(--text-muted); margin-bottom: 15px; }
        .hero-title { font-size: 6rem; color: var(--primary); margin-bottom: 10px; line-height: 1; font-weight: 400; }
        .hero-date { font-size: 0.9rem; letter-spacing: 6px; color: var(--text-main); padding: 15px 0; margin-bottom: 30px; position: relative; text-transform: uppercase; }
        .hero-date::before, .hero-date::after { content: ''; position: absolute; left: 50%; transform: translateX(-50%); width: 30px; height: 1px; background: var(--primary-light); }
        .hero-date::before { top: 0; }
        .hero-date::after { bottom: 0; }

        /* SECTIONS */
        .section { padding: 100px 20px; max-width: 850px; margin: 0 auto; text-align: center; }
        .section-title { font-size: 3rem; color: var(--primary); margin-bottom: 25px; font-weight: 300; position: relative; display: inline-block; letter-spacing: 2px; }
        
        /* QUOTE */
        .quote-section { max-width: 700px; margin: 0 auto; }
        .quote-text { font-family: var(--font-serif); font-style: italic; color: var(--text-muted); font-size: 1.4rem; line-height: 2; position: relative; }

        /* COUPLE */
        .couple-wrapper { background: var(--surface); border-radius: 8px; padding: 60px 40px; margin-top: 40px; border: 1px solid rgba(0,0,0,0.03); box-shadow: 0 5px 20px rgba(0,0,0,0.02); }
        .couple-container { display: flex; flex-direction: column; gap: 40px; align-items: center; }
        .profile { display: flex; flex-direction: column; align-items: center; width: 100%; }
        .profile-img-wrap { width: 220px; height: 320px; padding: 0; border-radius: 4px; margin-bottom: 30px; position: relative; overflow: hidden; }
        .profile-img { width: 100%; height: 100%; object-fit: cover; filter: brightness(1.02) grayscale(10%); transition: var(--transition); }
        .profile-img:hover { transform: scale(1.05); }
        .profile-name { font-size: 3rem; color: var(--primary); margin-bottom: 10px; line-height: 1; font-weight: 300; }
        .profile-desc { color: var(--text-muted); font-size: 0.95rem; line-height: 1.6; font-weight: 300; }
        .ampersand { font-size: 5rem; color: var(--primary-light); margin: -20px 0; z-index: 2; line-height: 0.5; font-weight: 300; }

        /* EVENTS */
        .event-container { display: grid; grid-template-columns: 1fr; gap: 30px; margin-top: 40px; }
        .event-card { background: var(--surface); padding: 50px 40px; border-radius: 8px; position: relative; transition: var(--transition); border: 1px solid rgba(0,0,0,0.03); }
        .event-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.04); }
        .event-icon { font-size: 2rem; color: var(--primary-light); margin-bottom: 20px; font-weight: 300; }
        .event-title { font-size: 2.2rem; margin-bottom: 15px; color: var(--primary); font-weight: 300; }
        .event-date { font-family: var(--font-sans); font-weight: 500; font-size: 1.05rem; color: var(--text-main); margin-bottom: 8px; letter-spacing: 1px; }
        .event-time { color: var(--text-muted); font-weight: 400; margin-bottom: 20px; font-size: 0.95rem; }
        .event-location { font-size: 0.95rem; color: var(--text-muted); margin-bottom: 25px; line-height: 1.7; }
        .btn-maps { background: transparent; color: var(--text-main); border-bottom: 1px solid var(--text-main); padding: 4px 0; text-decoration: none; display: inline-block; font-size: 0.8rem; letter-spacing: 2px; text-transform: uppercase; transition: var(--transition); }
        .btn-maps:hover { color: var(--primary); border-color: var(--primary); }

        /* COUNTDOWN */
        .countdown-wrapper { margin-top: 30px; }
        .countdown { display: flex; justify-content: center; gap: 30px; margin-top: 20px; flex-wrap: wrap; }
        .time-box { text-align: center; }
        .time-box span { display: block; font-family: var(--font-serif); font-size: 2.5rem; font-weight: 400; line-height: 1; margin-bottom: 5px; color: var(--text-main); font-variant-numeric: tabular-nums; }
        .time-box small { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 3px; color: var(--text-muted); font-weight: 400; }

        /* GALLERY */
        .gallery-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-top: 40px; padding: 0; }
        .gallery-item { width: 100%; height: 300px; object-fit: cover; border-radius: 4px; transition: var(--transition); filter: grayscale(20%); }
        .gallery-item:hover { filter: grayscale(0%); box-shadow: 0 10px 20px rgba(0,0,0,0.1); z-index: 2; position: relative; }
        .gallery-item:first-child { grid-column: 1 / -1; height: 450px; }

        /* GIFT */
        .gift-container { max-width: 500px; margin: 40px auto 0; }
        .gift-card { background: var(--surface); padding: 35px; border-radius: 8px; display: flex; align-items: center; justify-content: space-between; border: 1px solid rgba(0,0,0,0.05); margin-bottom: 20px; text-align: left; transition: var(--transition); }
        .btn-copy { background: transparent; color: var(--primary); border: 1px solid var(--primary); padding: 8px 18px; border-radius: 4px; cursor: pointer; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; transition: var(--transition); }
        .btn-copy:hover { background: var(--primary); color: white; }

        /* RSVP */
        .rsvp-box { background: var(--surface); padding: 50px 40px; border-radius: 8px; text-align: left; margin-top: 40px; border: 1px solid rgba(0,0,0,0.05); }
        .form-group { margin-bottom: 20px; }
        .form-control { width: 100%; padding: 16px 0; border: none; border-bottom: 1px solid rgba(0,0,0,0.1); font-family: inherit; font-size: 0.95rem; background: transparent; transition: var(--transition); color: var(--text-main); font-weight: 300; }
        .form-control:focus { outline: none; border-bottom-color: var(--primary); }
        .btn-submit { background: var(--primary); color: white; width: 100%; padding: 18px; border: none; border-radius: 4px; font-weight: 400; font-size: 0.85rem; letter-spacing: 3px; text-transform: uppercase; cursor: pointer; transition: var(--transition); margin-top: 10px; }
        .btn-submit:hover { background: var(--primary-light); }

        /* FOOTER */
        footer { background: var(--bg-body); color: var(--text-main); text-align: center; padding: 80px 20px; margin-top: 50px; position: relative; border-top: 1px solid rgba(0,0,0,0.05); }
        
        .music-btn { position: fixed; bottom: 30px; right: 30px; background: var(--surface); color: var(--primary); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; box-shadow: 0 5px 15px rgba(0,0,0,0.08); cursor: pointer; z-index: 1000; border: 1px solid rgba(0,0,0,0.05); outline: none; transition: var(--transition); }
        .music-btn:hover { background: var(--primary); color: white; }
        .spin { animation: spin 4s linear infinite; }
        @keyframes spin { 100% { transform: rotate(360deg); } }

        @media (min-width: 768px) {
            .couple-container { flex-direction: row; justify-content: center; align-items: flex-start; gap: 60px; }
            .profile { width: 40%; }
            .ampersand { width: auto; margin: 0; display: flex; align-items: center; justify-content: center; margin-top: 120px; }
            .event-container { grid-template-columns: 1fr 1fr; }
            .gallery-grid { grid-template-columns: repeat(3, 1fr); gap: 20px;}
            .gallery-item:first-child { grid-column: span 2; height: 500px; }
            .rsvp-box { padding: 60px; }
        }
    </style>
</head>
<body>

    <div id="cover-overlay">
        <div class="cover-content" data-aos="zoom-in" data-aos-duration="1500" class="text-center">
            <div class="cover-badge">Undangan Pernikahan</div>
            <div class="cover-subtitle">The Wedding Of</div>
            <h1 class="cover-title font-script">Aditya & Bunga</h1>
            
            <div class="hero-date" style="margin-top: 15px;">15 . 06 . 2026</div>

            <div class="cover-guest">
                <span>Kepada Yth. Bapak/Ibu/Saudara/i</span>
                <h2><?php echo isset($_GET['to']) ? htmlspecialchars($_GET['to']) : 'Tamu Istimewa'; ?></h2>
            </div>

            <button class="btn-buka" onclick="bukaUndangan()">
                Buka Undangan
            </button>
        </div>
    </div>

    <!-- AUDIO -->
    <audio id="bgMusic" loop><source src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-3.mp3" type="audio/mpeg"></audio>
    <button class="music-btn" id="musicBtn" onclick="toggleMusic()"><i class="fas fa-music"></i></button>

    <div id="main-content">
        
        <section class="hero">
            <div id="heroText" class="hero-content hidden-content">
                <i class="far fa-heart" style="font-size: 1.5rem; color: var(--primary); margin-bottom: 25px;"></i>
                <div class="hero-subtitle">Maha Suci Tuhan</div>
                <h1 class="hero-title font-script">Aditya & Bunga</h1>
                <div class="hero-date">15 . 06 . 2026</div>

                <div class="countdown-wrapper">
                    <div class="countdown" id="countdown">
                        <div class="time-box"><span id="days">00</span><small>Hari</small></div>
                        <div class="time-box"><span id="hours">00</span><small>Jam</small></div>
                        <div class="time-box"><span id="mins">00</span><small>Menit</small></div>
                        <div class="time-box"><span id="secs">00</span><small>Detik</small></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="quote-section" data-aos="fade-up">
                <p class="quote-text">
                    "Cinta sejati tidak pernah memaksakan kehendak, ia hadir dengan kelembutan yang menyatukan dua jiwa dalam harmoni dan kedamaian."
                </p>
            </div>
        </section>

        <section class="section" style="padding-top: 20px;">
            <div class="couple-wrapper" data-aos="fade-up">
                <h2 class="section-title font-serif">Sang Mempelai</h2>
                <p style="color: var(--text-muted); margin-bottom: 40px; font-weight: 300;">
                    Kami mengundang Anda untuk hadir dan memberikan doa restu pada hari bahagia kami.
                </p>
                
                <div class="couple-container">
                    <div class="profile" data-aos="fade-up" data-aos-delay="100">
                        <div class="profile-img-wrap">
                            <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=600&q=80" alt="Groom" class="profile-img">
                        </div>
                        <h3 class="profile-name font-script">Aditya Pratama</h3>
                        <div class="profile-desc">Putra dari<br><strong style="color: var(--text-main); font-weight: 500;">Bpk. Pratama & Ibu Pratama</strong></div>
                    </div>

                    <div class="ampersand font-serif" data-aos="zoom-in" data-aos-delay="200">&</div>

                    <div class="profile" data-aos="fade-up" data-aos-delay="300">
                        <div class="profile-img-wrap">
                            <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=600&q=80" alt="Bride" class="profile-img">
                        </div>
                        <h3 class="profile-name font-script">Bunga Lestari</h3>
                        <div class="profile-desc">Putri dari<br><strong style="color: var(--text-main); font-weight: 500;">Bpk. Lestari & Ibu Lestari</strong></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <h2 class="section-title font-serif" data-aos="fade-up">Waktu & Tempat</h2>
            
            <div class="event-container">
                <div class="event-card" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="event-title font-serif">Pemberkatan</h3>
                    <p class="event-date">Minggu, 15 Juni 2026</p>
                    <p class="event-time">09.00 - 11.00 WIB</p>
                    <p class="event-location"><strong style="color: var(--text-main);">Gereja Katedral</strong><br>Jakarta.</p>
                    <a href="https://maps.google.com" target="_blank" class="btn-maps">Lihat Peta</a>
                </div>

                <div class="event-card" data-aos="fade-up" data-aos-delay="200">
                    <h3 class="event-title font-serif">Resepsi</h3>
                    <p class="event-date">Minggu, 15 Juni 2026</p>
                    <p class="event-time">19.00 - Selesai</p>
                    <p class="event-location"><strong style="color: var(--text-main);">Hotel Mulia Senayan</strong><br>Jakarta.</p>
                    <a href="https://maps.google.com" target="_blank" class="btn-maps">Lihat Peta</a>
                </div>
            </div>
        </section>

        <section class="section">
            <h2 class="section-title font-serif" data-aos="fade-up">Galeri</h2>
            <div class="gallery-grid" data-aos="zoom-in" data-aos-delay="200">
                <img src="https://images.unsplash.com/photo-1544465544-1b71aee9dfa3?auto=format&fit=crop&w=1000&q=80" class="gallery-item">
                <img src="https://images.unsplash.com/photo-1511285560929-80b456fea0bc?auto=format&fit=crop&w=600&q=80" class="gallery-item">
                <img src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=600&q=80" class="gallery-item">
            </div>
        </section>

        <section class="section">
            <h2 class="section-title font-serif" data-aos="fade-up">Tanda Kasih</h2>
            <p style="color: var(--text-muted); margin-bottom: 10px; font-weight: 300;" data-aos="fade-up" data-aos-delay="100">
                Doa restu Anda adalah kado terindah bagi kami.
            </p>
            
            <div class="gift-container">
                <div class="gift-card" data-aos="fade-up" data-aos-delay="200">
                    <div>
                        <h3 style="color: var(--primary); margin-bottom: 8px; font-size: 1rem; text-transform: uppercase;">BCA</h3>
                        <p style="font-size: 1.1rem; font-family: var(--font-sans); font-weight: 500; letter-spacing: 2px; color: var(--text-main);">543 210 9876</p>
                        <p style="color: var(--text-muted); font-size: 0.85rem; font-weight: 300;">a.n Aditya Pratama</p>
                    </div>
                    <button onclick="salinTeks('5432109876')" class="btn-copy">Salin</button>
                </div>
            </div>
        </section>

        <section class="section">
            <h2 class="section-title font-serif" data-aos="fade-up">Kehadiran</h2>
            
            <div class="rsvp-box" data-aos="fade-up" data-aos-delay="200">
                <form id="formRSVP" onsubmit="kirimRSVP(event)">
                    <div class="form-group">
                        <input type="text" id="namaTamu" class="form-control" placeholder="Nama Lengkap" required>
                    </div>
                    <div class="form-group">
                        <select id="statusHadir" class="form-control" required style="color: var(--text-muted);" onchange="this.style.color='var(--text-main)'">
                            <option value="" disabled selected>Kehadiran</option>
                            <option value="Hadir">Hadir</option>
                            <option value="Tidak Hadir">Tidak Hadir</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <textarea id="pesanTamu" class="form-control" rows="3" placeholder="Doa & Harapan..." required></textarea>
                    </div>
                    <button type="submit" class="btn-submit">Kirim Form</button>
                </form>
            </div>
        </section>

        <footer>
            <h2 class="font-script" style="font-size: 3.5rem; margin-bottom: 10px; color: var(--text-main);">Aditya & Bunga</h2>
            <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 40px; font-weight: 300; letter-spacing: 1px;">
                Terima Kasih
            </p>
            <div style="font-size: 0.75rem; color: var(--text-muted); padding-top: 25px; letter-spacing: 2px; text-transform: uppercase;">
                Minimalist Theme by <strong style="color: var(--text-main);">Embun Visual</strong>
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
            musicBtn.classList.add("spin");
            
            // Remove overlay from DOM to allow clicking underneath
            setTimeout(() => { document.getElementById('cover-overlay').style.display = 'none'; AOS.refresh(); }, 1500);
        }

        function toggleMusic() {
            if (isPlaying) { audio.pause(); musicBtn.classList.remove("spin"); } 
            else { audio.play(); musicBtn.classList.add("spin"); }
            isPlaying = !isPlaying;
        }

        const targetDate = new Date("Jun 15, 2026 09:00:00").getTime();
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
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Disalin!', showConfirmButton: false, timer: 2000, background: '#FFF', color: '#4A4A4A', iconColor: '#A38C5B' });
        }

        function kirimRSVP(e) {
            e.preventDefault(); 
            let nama = document.getElementById('namaTamu').value;
            let hadir = document.getElementById('statusHadir').value;
            let pesan = document.getElementById('pesanTamu').value;
            
            let noMempelai = "6281234567890"; 
            let textWA = `Halo, saya *${nama}*.%0A%0AKonfirmasi kehadiran: *${hadir}*.%0A%0A*Pesan:*%0A"${pesan}"`;
            
            window.open(`https://api.whatsapp.com/send?phone=${noMempelai}&text=${textWA}`, '_blank');
            
            Swal.fire({ icon: 'success', title: 'Terkirim', text: 'Terima kasih', confirmButtonColor: '#A38C5B' });
            document.getElementById('formRSVP').reset();
            document.getElementById('statusHadir').style.color = "var(--text-muted)";
        }
    </script>
</body>
</html>
