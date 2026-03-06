<?php
// tema/tema_besic_bali1.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Wedding of Romeo & Juliet | Bali Agung Theme</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Pinyon+Script&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            /* Palette: Soft Dark Gold Bali */
            --primary: #2A2518;        
            --primary-light: #4A4230;  
            --bg-body: #FAF6F0;        /* Soft sand */
            --surface: #FFFFFF;        
            --text-main: #362D24;      
            --text-muted: #7A6F62;     
            --accent: #C49F53;         /* Soft Gold */
            --accent-hover: #A3823F;
            
            --font-serif: 'Playfair Display', serif;
            --font-sans: 'Plus Jakarta Sans', sans-serif;
            --font-script: 'Pinyon Script', cursive;
            
            --border-radius: 12px;
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
            background: url('https://images.unsplash.com/photo-1544644181-1484b3fdfc62?auto=format&fit=crop&w=1920&q=80') center/cover; 
            z-index: 9999; display: flex; flex-direction: column; align-items: center; justify-content: center; 
            text-align: center; transition: transform 1.2s cubic-bezier(0.77, 0, 0.175, 1); color: white; 
            border: 15px solid var(--surface);
        }
        #cover-overlay::before { content: ''; position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(42, 37, 24, 0.5), rgba(42, 37, 24, 0.85)); pointer-events: none; }
        #cover-overlay.open { transform: translateY(-100vh); }
        .cover-content { position: relative; z-index: 10; max-width: 650px; padding: 40px; background: rgba(42, 37, 24, 0.7); backdrop-filter: blur(12px); border-radius: 20px; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 20px 50px rgba(0,0,0,0.3); margin: 0 20px; }
        .cover-badge { font-size: 0.75rem; letter-spacing: 3px; text-transform: uppercase; padding: 8px 25px; border: 1px solid var(--accent); color: var(--accent); border-radius: 50px; margin-bottom: 20px; display: inline-block; }
        .cover-title { font-size: 4.5rem; color: #FFF; margin: 0 0 10px 0; text-shadow: 2px 4px 15px rgba(0,0,0,0.3); line-height: 1.2; }
        .cover-subtitle { font-family: var(--font-serif); font-style: italic; font-size: 1.2rem; margin-bottom: 15px; letter-spacing: 1px; color: var(--accent); }
        .cover-guest { margin-top: 25px; padding-top: 20px; border-top: 1px solid rgba(196, 159, 83, 0.3); }
        .cover-guest span { font-size: 0.85rem; letter-spacing: 1px; color: rgba(255,255,255,0.7); display: block; margin-bottom: 5px; }
        .cover-guest h2 { font-family: var(--font-serif); font-size: 1.5rem; letter-spacing: 1px; color: var(--accent); }
        .btn-buka { background: rgba(196, 159, 83, 0.2); color: white; padding: 15px 40px; border: 1px solid var(--accent); border-radius: 50px; cursor: pointer; margin: 30px auto 0; font-family: var(--font-sans); font-size: 0.9rem; letter-spacing: 2px; text-transform: uppercase; transition: var(--transition); display: flex; align-items: center; gap: 10px; justify-content: center; }
        .btn-buka:hover { background: var(--accent); color: #FFF; transform: translateY(-3px); }

        /* HERO */
        .hero { min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; background: url('https://images.unsplash.com/photo-1544644181-1484b3fdfc62?auto=format&fit=crop&w=1920&q=80') center/cover fixed; padding: 20px; position: relative; }
        .hero::after { content: ''; position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(250, 246, 240, 0.7), rgba(250, 246, 240, 1)); pointer-events: none; }
        .hero-content { position: relative; z-index: 2; max-width: 600px; transition: opacity 1.5s ease-in-out, transform 1.5s ease-out; }
        .hidden-content { opacity: 0; transform: translateY(30px); pointer-events: none; }
        .show-content { opacity: 1; transform: translateY(0); pointer-events: auto; }
        .hero-subtitle { font-family: var(--font-serif); font-style: italic; font-size: 1.2rem; color: var(--accent); margin-bottom: 15px; }
        .hero-title { font-size: 5.5rem; color: var(--primary); margin-bottom: 20px; line-height: 1; text-shadow: 2px 2px 10px rgba(255,255,255,0.5); }
        .hero-date { font-size: 0.9rem; letter-spacing: 4px; color: var(--text-main); padding: 15px 0; margin-bottom: 30px; position: relative; }
        .hero-date::before, .hero-date::after { content: ''; position: absolute; left: 50%; transform: translateX(-50%); width: 40px; height: 1px; background: var(--accent); }
        .hero-date::before { top: 0; }
        .hero-date::after { bottom: 0; }

        /* SECTIONS */
        .section { padding: 100px 20px; max-width: 900px; margin: 0 auto; text-align: center; }
        .section-title { font-size: 3rem; color: var(--primary); margin-bottom: 25px; font-style: italic; position: relative; display: inline-block; }
        
        /* QUOTE */
        .quote-section { max-width: 700px; margin: 0 auto; }
        .quote-text { font-family: var(--font-serif); font-style: italic; color: var(--text-muted); font-size: 1.3rem; line-height: 1.9; position: relative; }

        /* COUPLE */
        .couple-wrapper { background: var(--surface); border-radius: 20px; padding: 60px 40px; box-shadow: 0 20px 50px rgba(42, 37, 24, 0.05); margin-top: 40px; border: 1px solid rgba(196, 159, 83, 0.2); }
        .couple-container { display: flex; flex-direction: column; gap: 40px; align-items: center; }
        .profile { display: flex; flex-direction: column; align-items: center; width: 100%; }
        .profile-img-wrap { width: 220px; height: 300px; padding: 10px; border: 1px solid var(--accent); border-radius: 120px 120px 0 0; margin-bottom: 30px; position: relative; }
        /* Soft Bali filter */
        .profile-img { width: 100%; height: 100%; object-fit: cover; border-radius: 110px 110px 0 0; filter: sepia(30%) contrast(90%); }
        .profile-name { font-size: 3rem; color: var(--primary); margin-bottom: 10px; line-height: 1; }
        .profile-desc { color: var(--text-muted); font-size: 0.95rem; line-height: 1.6; font-weight: 300; }
        .ampersand { font-size: 5rem; color: var(--accent); margin: -30px 0; z-index: 2; line-height: 0.5; }

        /* EVENTS */
        .event-container { display: grid; grid-template-columns: 1fr; gap: 30px; margin-top: 40px; }
        .event-card { background: var(--surface); padding: 50px 40px; border-radius: 16px; position: relative; overflow: hidden; border: 1px solid rgba(0,0,0,0.05); transition: var(--transition); border-top: 4px solid var(--accent); }
        .event-card:hover { transform: translateY(-5px); box-shadow: 0 15px 40px rgba(42, 37, 24, 0.08); }
        .event-icon { font-size: 2.2rem; color: var(--accent); margin-bottom: 20px; }
        .event-title { font-size: 2.2rem; margin-bottom: 15px; color: var(--primary); font-style: italic; }
        .event-date { font-family: var(--font-sans); font-weight: 500; font-size: 1.05rem; color: var(--text-main); margin-bottom: 8px; letter-spacing: 1px; }
        .event-time { color: var(--accent); font-weight: 500; margin-bottom: 20px; font-size: 0.95rem; }
        .event-location { font-size: 0.95rem; color: var(--text-muted); margin-bottom: 25px; line-height: 1.7; }
        .btn-maps { background: transparent; color: var(--primary); border: 1px solid var(--primary); padding: 12px 30px; border-radius: 50px; text-decoration: none; display: inline-block; font-size: 0.85rem; letter-spacing: 2px; text-transform: uppercase; transition: var(--transition); }
        .btn-maps:hover { background: var(--primary); color: white; }

        /* COUNTDOWN */
        .countdown-wrapper { margin-top: 25px; }
        .countdown { display: flex; justify-content: center; gap: 15px; margin-top: 15px; flex-wrap: wrap; }
        .time-box { background: rgba(42, 37, 24, 0.03); color: var(--primary); padding: 15px 12px; min-width: 80px; border-radius: 12px; border: 1px solid rgba(196, 159, 83, 0.3); box-shadow: inset 0 2px 5px rgba(0,0,0,0.02); }
        .time-box span { display: block; font-family: var(--font-sans); font-size: 2.2rem; font-weight: 600; line-height: 1; margin-bottom: 5px; color: var(--text-main); font-variant-numeric: tabular-nums; animation: pulse-tick 1s infinite alternate; }
        .time-box small { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); font-weight: 500; }
        @keyframes pulse-tick { from { opacity: 0.85; transform: scale(0.98); } to { opacity: 1; transform: scale(1); } }

        /* GALLERY */
        .gallery-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-top: 40px; padding: 15px; background: var(--surface); border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); }
        .gallery-item { width: 100%; height: 250px; object-fit: cover; border-radius: 8px; transition: var(--transition); filter: sepia(30%); }
        .gallery-item:hover { filter: sepia(0%); transform: scale(1.02); }
        .gallery-item:first-child { grid-column: 1 / -1; height: 400px; }

        /* GIFT */
        .gift-container { max-width: 500px; margin: 40px auto 0; }
        .gift-card { background: var(--surface); padding: 35px; border-radius: 16px; display: flex; align-items: center; justify-content: space-between; border: 1px solid rgba(196, 159, 83, 0.3); margin-bottom: 20px; text-align: left; transition: var(--transition); position: relative; overflow: hidden; }
        .gift-card::before { content: ''; position: absolute; left: 0; top: 0; width: 4px; height: 100%; background: var(--accent); }
        .btn-copy { background: transparent; color: var(--accent); border: 1px solid var(--accent); padding: 8px 18px; border-radius: 50px; cursor: pointer; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; transition: var(--transition); }
        .btn-copy:hover { background: var(--accent); color: white; }

        /* RSVP */
        .rsvp-box { background: var(--surface); padding: 50px 40px; border-radius: 16px; text-align: left; margin-top: 40px; border: 1px solid rgba(0,0,0,0.05); box-shadow: 0 20px 50px rgba(42, 37, 24, 0.04); }
        .form-group { margin-bottom: 20px; }
        .form-control { width: 100%; padding: 16px 0; border: none; border-bottom: 1px solid rgba(0,0,0,0.1); font-family: inherit; font-size: 0.95rem; background: transparent; transition: var(--transition); color: var(--text-main); font-weight: 300; }
        .form-control:focus { outline: none; border-bottom-color: var(--accent); }
        .btn-submit { background: var(--primary); color: white; width: 100%; padding: 18px; border: none; border-radius: 12px; font-weight: 400; font-size: 0.9rem; letter-spacing: 2px; text-transform: uppercase; cursor: pointer; transition: var(--transition); margin-top: 10px; }
        .btn-submit:hover { background: var(--primary-light); }

        /* FOOTER */
        footer { background: var(--primary); color: white; text-align: center; padding: 80px 20px; margin-top: 50px; position: relative; }
        footer::before { content:''; position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, var(--accent), transparent); }
        
        .music-btn { position: fixed; bottom: 30px; right: 30px; background: var(--surface); color: var(--accent); width: 55px; height: 55px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1); cursor: pointer; z-index: 1000; border: 1px solid rgba(196, 159, 83, 0.3); outline: none; transition: var(--transition); }
        .music-btn:hover { transform: scale(1.1); }
        .spin { animation: spin 4s linear infinite; }
        @keyframes spin { 100% { transform: rotate(360deg); } }

        @media (min-width: 768px) {
            .couple-container { flex-direction: row; justify-content: center; align-items: stretch; gap: 0; }
            .profile { width: 45%; }
            .ampersand { width: 10%; margin: 0; display: flex; align-items: center; justify-content: center; }
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
            <div class="cover-badge">Undangan Pawiwahan</div>
            <div class="cover-subtitle">The Wedding Of</div>
            <h1 class="cover-title font-script">Dewa & Ayu</h1>
            
            <div class="hero-date" style="border-color: rgba(255,255,255,0.3); color: white; display: inline-block; padding: 8px 30px; font-size: 0.8rem; letter-spacing: 3px; margin: 5px 0 10px;">22 . 04 . 2026</div>

            <div class="cover-guest">
                <span>Kepada Yth. Bapak/Ibu/Saudara/i</span>
                <h2><?php echo isset($_GET['to']) ? htmlspecialchars($_GET['to']) : 'Tamu Undangan'; ?></h2>
            </div>

            <button class="btn-buka" onclick="bukaUndangan()">
                <i class="fas fa-envelope-open"></i> Buka Undangan
            </button>
        </div>
    </div>

    <!-- AUDIO PLAYER -->
    <audio id="bgMusic" loop>
        <source src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3" type="audio/mpeg">
    </audio>
    <button class="music-btn" id="musicBtn" onclick="toggleMusic()"><i class="fas fa-music"></i></button>

    <div id="main-content">
        
        <section class="hero">
            <div id="heroText" class="hero-content hidden-content">
                <div class="hero-subtitle">Maha Suksema Sang Hyang Widhi Wasa</div>
                <h1 class="hero-title font-script" style="margin-bottom: 25px;">Dewa & Ayu</h1>
                <div class="hero-date">22 . 04 . 2026</div>

                <div class="countdown-wrapper">
                    <h3 class="font-serif" style="font-size: 1.3rem; color: var(--text-muted); font-style: italic;">Menuju Hari Bahagia</h3>
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
                <i class="fas fa-om" style="font-size: 2.5rem; color: var(--accent); margin-bottom: 25px;"></i>
                <p class="quote-text">
                    "Ihaiva stam ma vi yaustam, visvam ayur vyasnutam..."<br>
                    <span style="font-size: 1rem;">(Rg Veda X.85.42)</span><br><br>
                    Wahai pasangan suami-istri, semoga kalian tetap bersatu dan tidak pernah terpisahkan. Semoga kalian mencapai hidup penuh kebahagiaan.
                </p>
            </div>
        </section>

        <section class="section" style="padding-top: 40px;">
            <div class="couple-wrapper" data-aos="fade-up">
                <h2 class="section-title font-serif">Om Swastyastu</h2>
                <p style="color: var(--text-muted); margin-bottom: 50px; font-weight: 300;">
                    Atas Asung Kertha Wara Nugraha Ida Sang Hyang Widhi Wasa, kami bermaksud mengundang Bapak/Ibu/Saudara/i pada Acara Pawiwahan Putra-Putri kami:
                </p>
                
                <div class="couple-container">
                    <div class="profile" data-aos="fade-right" data-aos-delay="100">
                        <div class="profile-img-wrap">
                            <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=600&q=80" alt="Groom" class="profile-img">
                        </div>
                        <h3 class="profile-name font-script">Dewa Gede Dharmendra</h3>
                        <div class="profile-desc">Putra Ketiga dari<br><strong style="color: var(--text-main); font-weight: 500;">Dewa Ketut Alit & Desak Putu</strong><br>Br. Menak Tulikup</div>
                    </div>

                    <div class="ampersand font-script" data-aos="zoom-in" data-aos-delay="200">&</div>

                    <div class="profile" data-aos="fade-left" data-aos-delay="300">
                        <div class="profile-img-wrap">
                            <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=600&q=80" alt="Bride" class="profile-img">
                        </div>
                        <h3 class="profile-name font-script">I Gusti Ayu Diah</h3>
                        <div class="profile-desc">Putri Pertama dari<br><strong style="color: var(--text-main); font-weight: 500;">I Gusti Ngurah & Ni Gusti Luwes</strong><br>Br. Prajamukti</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <h2 class="section-title font-serif" data-aos="fade-up">Rangkaian Acara</h2>
            <p style="color: var(--text-muted); margin-bottom: 20px;" data-aos="fade-up" data-aos-delay="100">Acara ini akan diselenggarakan pada:</p>
            
            <div class="event-container">
                <div class="event-card" data-aos="fade-up" data-aos-delay="200">
                    <i class="fas fa-hand-holding-heart event-icon"></i>
                    <h3 class="event-title">Mekala-Kalan</h3>
                    <p class="event-date">Rabu, 22 April 2026</p>
                    <p class="event-time"><i class="far fa-clock"></i> 09.00 WITA - Selesai</p>
                    <p class="event-location"><strong style="color: var(--text-main);">Kediaman Mempelai Pria</strong><br>Jl. Raya Tulikup, Gianyar, Bali.</p>
                    <a href="https://maps.google.com" target="_blank" class="btn-maps">Lihat Peta</a>
                </div>

                <div class="event-card" data-aos="fade-up" data-aos-delay="300">
                    <i class="fas fa-users event-icon"></i>
                    <h3 class="event-title">Resepsi</h3>
                    <p class="event-date">Rabu, 22 April 2026</p>
                    <p class="event-time"><i class="far fa-clock"></i> 14.00 WITA - 20.00 WITA</p>
                    <p class="event-location"><strong style="color: var(--text-main);">Kediaman Mempelai Pria</strong><br>Jl. Raya Tulikup, Gianyar, Bali.</p>
                    <a href="https://maps.google.com" target="_blank" class="btn-maps">Lihat Peta</a>
                </div>
            </div>
        </section>

        <section class="section">
            <h2 class="section-title font-serif" data-aos="fade-up">Galeri Momen</h2>
            <div class="gallery-grid" data-aos="zoom-in" data-aos-delay="200">
                <img src="https://images.unsplash.com/photo-1511285560929-80b456fea0bc?auto=format&fit=crop&w=1000&q=80" class="gallery-item">
                <img src="https://images.unsplash.com/photo-1544644181-1484b3fdfc62?auto=format&fit=crop&w=600&q=80" class="gallery-item">
                <img src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=600&q=80" class="gallery-item">
            </div>
        </section>

        <section class="section">
            <h2 class="section-title font-serif" data-aos="fade-up">Tanda Kasih</h2>
            <p style="color: var(--text-muted); margin-bottom: 10px; font-weight: 300;" data-aos="fade-up" data-aos-delay="100">
                Bagi keluarga dan sahabat yang ingin mengirimkan hadiah, silakan mengirimkannya ke rekening berikut:
            </p>
            
            <div class="gift-container">
                <div class="gift-card" data-aos="fade-up" data-aos-delay="200">
                    <div>
                        <h3 style="color: var(--primary); margin-bottom: 8px; font-size: 1.1rem;"><i class="fas fa-university"></i> Bank BCA</h3>
                        <p style="font-size: 1.25rem; font-family: var(--font-sans); font-weight: 600; letter-spacing: 2px; color: var(--text-main);">0987123456</p>
                        <p style="color: var(--text-muted); font-size: 0.9rem; font-weight: 300;">a.n Dewa Gede Dharmendra</p>
                    </div>
                    <button onclick="salinTeks('0987123456')" class="btn-copy">Salin</button>
                </div>
            </div>
        </section>

        <section class="section">
            <h2 class="section-title font-serif" data-aos="fade-up">Kehadiran</h2>
            <p style="color: var(--text-muted); font-weight: 300;" data-aos="fade-up" data-aos-delay="100">
                Kehadiran Bapak/Ibu/Saudara/i sangat berarti bagi kami. Lengkapi form di bawah ini untuk RSVP.
            </p>
            
            <div class="rsvp-box" data-aos="zoom-in" data-aos-delay="200">
                <form id="formRSVP" onsubmit="kirimRSVP(event)">
                    <div class="form-group">
                        <input type="text" id="namaTamu" class="form-control" placeholder="Nama Lengkap Anda" required>
                    </div>

                    <div class="form-group">
                        <select id="statusHadir" class="form-control" required style="color: var(--text-muted);" onchange="this.style.color='var(--text-main)'">
                            <option value="" disabled selected>Pilih Status Kehadiran</option>
                            <option value="Hadir">Ya, Saya Akan Hadir</option>
                            <option value="Tidak Hadir">Maaf, Saya Tidak Bisa Hadir</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <textarea id="pesanTamu" class="form-control" rows="3" placeholder="Tuliskan harapan dan doa restu..." required></textarea>
                    </div>

                    <button type="submit" class="btn-submit">Kirim Konfirmasi</button>
                </form>
            </div>
        </section>

        <footer>
            <i class="fas fa-om" style="font-size: 2rem; color: var(--accent); margin-bottom: 20px;"></i>
            <h2 class="font-script" style="font-size: 4rem; margin-bottom: 10px; color: var(--accent);">Dewa & Ayu</h2>
            <p style="font-size: 1rem; color: rgba(255,255,255,0.8); margin-bottom: 40px; font-weight: 300; letter-spacing: 1px;">
                Matur Suksma atas doa dan restunya. Om Shanti Shanti Shanti Om.
            </p>
            <div style="font-size: 0.8rem; color: rgba(255,255,255,0.5); border-top: 1px solid rgba(255,255,255,0.1); padding-top: 25px; letter-spacing: 2px; text-transform: uppercase;">
                Bali Invitation by <strong style="color: var(--surface);">Embun Visual</strong>
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
            
            setTimeout(() => { AOS.refresh(); }, 500);
        }

        function toggleMusic() {
            if (isPlaying) { audio.pause(); musicBtn.classList.remove("spin"); } 
            else { audio.play(); musicBtn.classList.add("spin"); }
            isPlaying = !isPlaying;
        }

        const targetDate = new Date("Apr 22, 2026 09:00:00").getTime();
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
            Swal.fire({ 
                toast: true, position: 'top-end', icon: 'success', 
                title: 'Disalin!', showConfirmButton: false, timer: 2000,
                background: '#2A2518', color: '#fff', iconColor: '#C49F53'
            });
        }

        function kirimRSVP(e) {
            e.preventDefault(); 
            let nama = document.getElementById('namaTamu').value;
            let hadir = document.getElementById('statusHadir').value;
            let pesan = document.getElementById('pesanTamu').value;
            
            let noMempelai = "6281234567890"; 
            let textWA = `Om Swastyastu,%0ASaya *${nama}*.%0A%0ATerkait undangan pawiwahan, saya konfirmasi: *${hadir}*.%0A%0A*Pesan & Doa:*%0A"${pesan}"`;
            
            window.open(`https://api.whatsapp.com/send?phone=${noMempelai}&text=${textWA}`, '_blank');
            
            Swal.fire({ icon: 'success', title: 'Terkirim', text: 'Matur Suksma atas doa dan konfirmasinya.', confirmButtonColor: '#2A2518' });
            document.getElementById('formRSVP').reset();
            document.getElementById('statusHadir').style.color = "var(--text-muted)";
        }
    </script>
</body>
</html>
